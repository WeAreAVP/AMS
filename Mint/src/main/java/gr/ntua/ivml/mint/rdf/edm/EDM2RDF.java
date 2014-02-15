package gr.ntua.ivml.mint.rdf.edm;


import gr.ntua.ivml.mint.rdf.edm.types.*;

import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.InputStream;
import java.net.URI;
import java.net.URISyntaxException;
import java.net.URL;
import java.util.Iterator;
import java.util.List;
import java.util.Properties;
import java.util.StringTokenizer;
import javax.xml.bind.JAXBContext;
import javax.xml.bind.JAXBElement;
import javax.xml.bind.JAXBException;
import javax.xml.bind.Unmarshaller;
import javax.xml.datatype.XMLGregorianCalendar;
import com.hp.hpl.jena.rdf.model.Model;
import com.hp.hpl.jena.rdf.model.ModelFactory;
import com.hp.hpl.jena.rdf.model.Resource;
import com.hp.hpl.jena.vocabulary.RDF;
import com.hp.hpl.jena.vocabulary.DC;
import com.hp.hpl.jena.vocabulary.DCTerms;


public class EDM2RDF {

	
InputStream inputXML;
Properties props;
int physicalThingCnt,aggregationCnt,proxyCnt,eventCnt;

	
	public static void main(String[] args) 
	{
		
		try {
			
			String path="/media/sda1/Documents/Education/NTUA/Projects/AceMedia/Reasoning/EDMXML2RDF2/";
			//Nikos Tests
//			File inputFile=new File("XMLs/EDMSchemaV9/MonaLisaLouvreObj3.xml");
//			File outputFile=new File("RDFs/EDMSchemaV9/MonaLisaLouvreObj3.rdf");			
//			File inputFile=new File("XMLs/EDMSchemaV9/MonaLisaLouvreEventRel3.xml");
//			File outputFile=new File("RDFs/EDMSchemaV9/MonaLisaLouvreEventRel3.rdf");
//			File inputFile=new File(path+"XMLs/EDMSchemaV9/ToTestURI.xml");
//			File outputFile=new File(path+"RDFs/EDMSchemaV9/ToTestURI.rdf");
//			File inputFile=new File(path+"XMLs/EDMSchemaV9/ToTestString.xml");
//			File outputFile=new File(path+"RDFs/EDMSchemaV9/ToTestString.rdf");
			File inputFile=new File(path+"XMLs/EDMSchemaV9/Output_1.xml");
			File outputFile=new File(path+"RDFs/EDMSchemaV9/Output_1.rdf");
			
			FileInputStream inputXML = new FileInputStream(inputFile);
			FileOutputStream outputRDFJena = new FileOutputStream(outputFile);
			EDM2RDF converter=new EDM2RDF(inputXML);
			ByteArrayOutputStream out = converter.convertToRDF();
			out.writeTo(outputRDFJena);
			System.out.println(inputFile.getName() + " converted to " + outputFile.getName());			
		} catch(Exception e) {
			e.printStackTrace();
		}
	}
	
	
	public EDM2RDF(InputStream inputXML) {
		this.inputXML=inputXML;
	}


	private void storeProperties() {
		props.setProperty("PhysicalThing", String.valueOf(physicalThingCnt));
		props.setProperty("Aggregation", String.valueOf(aggregationCnt));
		props.setProperty("Proxy", String.valueOf(proxyCnt));
		props.setProperty("Event", String.valueOf(eventCnt));
		
		
		FileOutputStream out;
		try {
			out = new FileOutputStream("Properties");
			props.store(out, "");
			//props.load(in);
			out.close();
		} catch (Exception e1) {
			// TODO Auto-generated catch block
			e1.printStackTrace();
		}
	}


	private void loadProperties() {
		try {
			props = new Properties();
			FileInputStream in = new FileInputStream("Properties");
			props.load(in);
			in.close();
			
			physicalThingCnt=Integer.parseInt(props.getProperty("PhysicalThing"));
			aggregationCnt=Integer.parseInt(props.getProperty("Aggregation"));
			proxyCnt=Integer.parseInt(props.getProperty("Proxy"));
			eventCnt=Integer.parseInt(props.getProperty("Event"));

			
		} catch (Exception e1) {
			// TODO Auto-generated catch block
			createProperties();
		}
		
	}


	private void createProperties() {
		
		props = new Properties();
		props.setProperty("PhysicalThing", "1");
		props.setProperty("Aggregation", "1");
		props.setProperty("Proxy", "1");
		props.setProperty("Event", "1");

		
		physicalThingCnt=1;
		aggregationCnt=1;
		proxyCnt=1;
		eventCnt=1;
		
		
		FileOutputStream out;
		try {
			out = new FileOutputStream("Properties");
			props.store(out, "");
			//props.load(in);
			out.close();
		} catch (Exception e1) {
			// TODO Auto-generated catch block
			e1.printStackTrace();
		}
		
	}
	
	private String urlEncoding(String urlStr, String element) throws Exception
	{
		boolean exception= false;
		try {
			URL url = new URL(urlStr);
			URI uri = new URI(url.getProtocol(), url.getAuthority(), url.getPath(), url.getQuery(), null);
			return uri.toASCIIString();
		} catch (Exception e) {
			e.printStackTrace();
			exception = true;
		}
		if(exception)throw new Exception("Invalid value '"+urlStr+"' for element "+element+". Check your mapping or the data entered. A valid URL is required.");
		return urlStr;
	}
	
	public ByteArrayOutputStream convertToRDF() throws Exception
	{
		loadProperties();
		
		JAXBContext jc = null;
		Unmarshaller u = null;
		AggregationType aggregation=null;
		AggregationWrapType aggregationWrap=null; 
		
		
		try {
			jc = JAXBContext.newInstance("gr.ntua.ivml.mint.rdf.edm.types");
		} catch (JAXBException e) {
			e.printStackTrace();
		}
		
		try {
			u = jc.createUnmarshaller();
		} catch (JAXBException e) {
			e.printStackTrace();
		}
		
		try {
			JAXBElement jaxBElement=(JAXBElement)u.unmarshal(inputXML);
//			aggregation = (AggregationType)jaxBElement.getValue();
			aggregationWrap= (AggregationWrapType)jaxBElement.getValue();
			
		} catch (JAXBException e) {
			e.printStackTrace();
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		//The ontology URIs
		String baseURI="http://baseURI/";
		String ore="http://www.openarchives.org/ore/terms/";
		String ese="http://www.europeana.eu/schemas/ese/";
		String skos="http://www.w3.org/2004/02/skos/core#";
		String edm="http://www.europeana.eu/schemas/edm/";
		
		
		//Create the model
		Model model = ModelFactory.createDefaultModel();
		model.setNsPrefix( "baseURI", baseURI );
		model.setNsPrefix( "ore", ore );
		model.setNsPrefix( "ese", ese );
		model.setNsPrefix( "skos", skos );
		model.setNsPrefix( "ens", edm );
		model.setNsPrefix( "dcterms", DCTerms.getURI() );
		

		for(Iterator<AggregationType> aggrIt = aggregationWrap.getAggregation().iterator(); aggrIt.hasNext();)
		{
			
			aggregation = aggrIt.next();
		
		
			//*********************************************************************************************************
			//Create the physical thing
			PhysicalThingType phyThing=aggregation.getAggregatedCHO();
			Resource phyThingRes;
			
			String phyThingResource;
			if(phyThing.getIdentifier()!=null)
			{
				if(phyThing.getIdentifier().getResType().equals("HTTP URI"))
				{
					phyThingResource=phyThing.getIdentifier().getValue();
					phyThingRes=model.createResource(urlEncoding(phyThingResource,"Aggregated CHO > identifier"));
				}
				else 
				{
					phyThingResource="PhysicalThing/"+phyThing.getIdentifier().getResType().replace(" ","")+"/"+phyThing.getIdentifier().getValue().replace(" ","")+physicalThingCnt++;
//					phyThingRes=model.createResource(baseURI+phyThingResource);
					URI uri = new URI(baseURI+phyThingResource);
					phyThingRes=model.createResource(uri.toASCIIString());
				}
			}else
			{
				phyThingResource="PhysicalThing/ID/"+physicalThingCnt++;
				phyThingRes=model.createResource(baseURI+phyThingResource);
			}
			phyThingRes.addProperty(RDF.type,model.createResource(edm+"PhysicalThing"));
			
			
			if(phyThing.getType()!=null)
			{
				phyThingRes.addProperty(RDF.type,phyThing.getType().getValue(),phyThing.getType().getLang());
			}
			
			List<String> webResources=phyThing.getRealizes();
			for(Iterator<String> it=webResources.iterator();it.hasNext();)
			{
				phyThingRes.addProperty(model.createProperty(edm,"realizes"), model.createResource(urlEncoding(it.next(), "Aggregated CHO > realizes")));
				//TODO Are these URIs going to be mapped as WebResources?In ESE to EDM they are not.  
			}
					
			
			//*********************************************************************************************************
			//Create the aggregation
			Resource aggrRes=model.createResource(baseURI+"Aggregation/"+"AggregationRes"+aggregationCnt++);
			aggrRes.addProperty(RDF.type, model.createResource(ore+"Aggregation"));
			aggrRes.addProperty(model.createProperty(edm,"aggregatedCHO"),phyThingRes);
			List<SimpleLiteral> creators=aggregation.getCreator();
			for(Iterator<SimpleLiteral> it=creators.iterator();it.hasNext();)
			{
				SimpleLiteral temp=it.next();
				aggrRes.addProperty(DC.creator,temp.getValue(),temp.getLang());
			}
			
			WebWrapperType webWrapper=aggregation.getWebResources();
			aggrRes.addProperty(model.createProperty(edm,"landingPage"),model.createResource(urlEncoding(webWrapper.getLandingPage(),"WebResources > landingPage")));
			List<String> views=webWrapper.getHasView();
			for(Iterator<String> it=views.iterator();it.hasNext();)
			{
				String view=it.next();
				if(!view.equals(""))
				{
					aggrRes.addProperty(model.createProperty(edm,"hasView"),model.createResource(urlEncoding(view,"WebResources > hasView")));
				}
			}
			
			
			//*********************************************************************************************************
			//Create the proxy
			ProxyType proxy=aggregation.getProxy();
			Resource proxyRes=model.createResource(baseURI+"Proxy/"+"ProxyRes"+proxyCnt++);
			proxyRes.addProperty(RDF.type,model.createResource(ore+"Proxy"));
			
			//*********************************************************************************************************
			//Add Current Location
			List<PlaceType> currentLocation=phyThing.getCurrentLocation();
			for(Iterator<PlaceType> it=currentLocation.iterator();it.hasNext();)
			{
				PlaceType place=it.next();
				proxyRes.addProperty(model.createProperty(edm,"currentLocation"),place.getPlace().getValue(),place.getPlace().getLang());
				
				
				List<String> placeUri=place.getPlaceResource();
				for(Iterator<String> it1=placeUri.iterator();it1.hasNext();)
				{
					//TODO this time we map the URI to Place
					Resource placeRes=model.createResource(urlEncoding(it1.next(),"Aggregated CHO > currentLocation > placeResource"));
					placeRes.addProperty(RDF.type,model.createResource(edm+"Place"));
					proxyRes.addProperty(model.createProperty(edm,"currentLocation"),placeRes);	
				}
			}
			
			//*********************************************************************************************************
			//Add Europeana Metadata
			EuropeanaType europeana=proxy.getEuropeana();
			
			//1-Country
			proxyRes.addProperty(model.createProperty(edm,"country"),europeana.getCountry().getValue(),europeana.getCountry().getLang());
			
			//2-DataProvider
			if(europeana.getDataProvider()!=null)
				proxyRes.addProperty(model.createProperty(edm,"dataProvider"),europeana.getDataProvider());
			
			//3-HasMet
			List<SimpleLiteral> hasMet=europeana.getHasMet();
			for(Iterator<SimpleLiteral> it=hasMet.iterator();it.hasNext();)
			{
				SimpleLiteral lit=it.next();
				proxyRes.addProperty(model.createProperty(edm,"hasMet"),lit.getValue(),lit.getLang());
			}
			
			//4-HasType
			List<SimpleLiteral> hasType=europeana.getHasType();
			for(Iterator<SimpleLiteral> it=hasType.iterator();it.hasNext();)
			{
				SimpleLiteral lit=it.next();
				proxyRes.addProperty(model.createProperty(edm,"hasType"),lit.getValue(),lit.getLang());
			}
			
			//5-Language
			proxyRes.addProperty(model.createProperty(edm,"language"),europeana.getLanguage().getValue(),europeana.getLanguage().getLang());
			
			//6-Object
			if(europeana.getObject()!=null)
				//TODO is this a WebResource???
				proxyRes.addProperty(model.createProperty(edm,"object"),model.createResource(urlEncoding(europeana.getObject(),"Europeana > object")));
			
			//7-Provider
			proxyRes.addProperty(model.createProperty(edm,"provider"),europeana.getProvider());
			
			//8-Rights
			if(europeana.getRights()!=null)
				//TODO is this a WebResource?
				proxyRes.addProperty(model.createProperty(edm,"rights"),model.createResource(urlEncoding(europeana.getRights(),"Europeana > rights")));
			
			//9-Type
			proxyRes.addProperty(model.createProperty(edm,"type"),europeana.getType().value());
			
			//10-Unstored
			List<String> unstored=europeana.getUnstored();
			for(Iterator<String> it=unstored.iterator();it.hasNext();)
			{
				proxyRes.addProperty(model.createProperty(edm,"unstored"),it.next());
			}
			
			//11-Uri
			if(europeana.getUri()!=null)
			{
				proxyRes.addProperty(model.createProperty(edm,"uri"),europeana.getUri().getValue(),europeana.getUri().getLang());
			}
			
			//12-User Tag
			List<SimpleLiteral> userTag=europeana.getUserTag();
			for(Iterator<SimpleLiteral> it=userTag.iterator();it.hasNext();)
			{
				SimpleLiteral temp=it.next();
				proxyRes.addProperty(model.createProperty(edm,"userTag"),temp.getValue(),temp.getLang());
			}
			
			//13-Year
			List<XMLGregorianCalendar> year=europeana.getYear();
			for(Iterator<XMLGregorianCalendar> it=year.iterator();it.hasNext();)
			{
				XMLGregorianCalendar date = it.next();
				if(date == null) throw new Exception("Invalid date for element Europeana > Year. Correct date format is YYYY-mm-dd.");
				else proxyRes.addProperty(model.createProperty(edm,"year"),date.toXMLFormat());
			}
			
			
			//*********************************************************************************************************
			//Add DC Metadata
			if(proxy.getDC()!=null)
			{
				DCType dc=proxy.getDC();
				
				//1-Contributor
				List<SimpleLiteral> contributors=dc.getContributor();
				for(Iterator<SimpleLiteral> it=contributors.iterator();it.hasNext();)
				{
					SimpleLiteral temp=it.next();
					proxyRes.addProperty(DC.contributor,temp.getValue(),temp.getLang() );
				}
				
				//2-Coverage
				List<SimpleLiteral> coverage=dc.getCoverage();
				for(Iterator<SimpleLiteral> it=coverage.iterator();it.hasNext();)
				{
					SimpleLiteral temp=it.next();
					proxyRes.addProperty(DC.coverage,temp.getValue(),temp.getLang() );
				}
				
				//3-Creator
				if(dc.getCreator()!=null)
					proxyRes.addProperty(DC.creator,dc.getCreator().getValue(),dc.getCreator().getLang());
				
				//4-Date
				List<SimpleLiteral> date=dc.getDate();
				for(Iterator<SimpleLiteral> it=date.iterator();it.hasNext();)
				{
					SimpleLiteral temp=it.next();
					proxyRes.addProperty(DC.date,temp.getValue(),temp.getLang());
				}
				
				//5-Description
				List<SimpleLiteral> description=dc.getDescription();
				for(Iterator<SimpleLiteral> it=description.iterator();it.hasNext();)
				{
					SimpleLiteral temp=it.next();
					proxyRes.addProperty(DC.description,temp.getValue(),temp.getLang());
				}
				
				//6-Format
				List<SimpleLiteral> format=dc.getFormat();
				for(Iterator<SimpleLiteral> it=format.iterator();it.hasNext();)
				{
					SimpleLiteral temp=it.next();
					proxyRes.addProperty(DC.format,temp.getValue(),temp.getLang());
				}
				
				//7-Identifier
				List<SimpleLiteral> identifier=dc.getIdentifier();
				for(Iterator<SimpleLiteral> it=identifier.iterator();it.hasNext();)
				{
					SimpleLiteral temp=it.next();
					proxyRes.addProperty(DC.identifier,temp.getValue(),temp.getLang());
				}
				
				//8-Language
				List<SimpleLiteral> dcLang=dc.getLanguage();
				for(Iterator<SimpleLiteral> it=dcLang.iterator();it.hasNext();)
				{
					SimpleLiteral temp=it.next();
					proxyRes.addProperty(DC.language,temp.getValue(),temp.getLang());
				}
				
				//9-Publisher
				List<SimpleLiteral> publisher=dc.getPublisher();
				for(Iterator<SimpleLiteral> it=publisher.iterator();it.hasNext();)
				{
					SimpleLiteral temp=it.next();
					proxyRes.addProperty(DC.publisher,temp.getValue(),temp.getLang());
				}
				
				//10-Relation
				List<SimpleLiteral> relation=dc.getRelation();
				for(Iterator<SimpleLiteral> it=relation.iterator();it.hasNext();)
				{
					SimpleLiteral temp=it.next();
					proxyRes.addProperty(DC.relation,temp.getValue(),temp.getLang());
				}
				
				//11-Rights
				List<SimpleLiteral> dcrights=dc.getRights();
				for(Iterator<SimpleLiteral> it=dcrights.iterator();it.hasNext();)
				{
					SimpleLiteral temp=it.next();
					proxyRes.addProperty(DC.rights,temp.getValue(),temp.getLang());
				}
				
				//12-Source
				List<SimpleLiteral> source=dc.getSource();
				for(Iterator<SimpleLiteral> it=source.iterator();it.hasNext();)
				{
					SimpleLiteral temp=it.next();
					proxyRes.addProperty(DC.source,temp.getValue(),temp.getLang());
				}
				
				//13-Subject
				List<SimpleLiteral> subject=dc.getSubject();
				for(Iterator<SimpleLiteral> it=subject.iterator();it.hasNext();)
				{
					SimpleLiteral temp=it.next();
					proxyRes.addProperty(DC.subject,temp.getValue(),temp.getLang());
				}
				
				//14-Title
				List<SimpleLiteral> title=dc.getTitle();
				for(Iterator<SimpleLiteral> it=title.iterator();it.hasNext();)
				{
					SimpleLiteral temp=it.next();
					proxyRes.addProperty(DC.title,temp.getValue(),temp.getLang());
				}
				
				//15-Type
				List<SimpleLiteral> type=dc.getType();
				for(Iterator<SimpleLiteral> it=type.iterator();it.hasNext();)
				{
					SimpleLiteral temp=it.next();
					proxyRes.addProperty(DC.type,temp.getValue(),temp.getLang());
				}
			}
			
			//*********************************************************************************************************
			//Add DCTerms Metadata
			if(proxy.getDCTerms()!=null)
			{
				DCTermsType dcTerms=proxy.getDCTerms();
				
				//1-Alternative
				List<SimpleLiteral> altTitles=dcTerms.getAlternative();
				for(Iterator<SimpleLiteral> it=altTitles.iterator();it.hasNext();)
				{
					SimpleLiteral temp=it.next();
					proxyRes.addProperty(DCTerms.alternative,temp.getValue(),temp.getLang() );
				}
				
				//2-ConformsTo
				List<SimpleLiteral> conformsTo=dcTerms.getConformsTo();
				for(Iterator<SimpleLiteral> it=conformsTo.iterator();it.hasNext();)
				{
					SimpleLiteral temp=it.next();
					proxyRes.addProperty(DCTerms.conformsTo,temp.getValue(),temp.getLang() );
				}
				
				//3-Created
				List<SimpleLiteral> created=dcTerms.getCreated();
				for(Iterator<SimpleLiteral> it=created.iterator();it.hasNext();)
				{
					SimpleLiteral temp=it.next();
					proxyRes.addProperty(DCTerms.created,temp.getValue(),temp.getLang() );
				}
				
				//4-Extent
				List<SimpleLiteral> extent=dcTerms.getExtent();
				for(Iterator<SimpleLiteral> it=extent.iterator();it.hasNext();)
				{
					SimpleLiteral temp=it.next();
					proxyRes.addProperty(DCTerms.extent,temp.getValue(),temp.getLang());
				}
				
				//5-HasFormat
				List<SimpleLiteral> hasFormat=dcTerms.getHasFormat();
				for(Iterator<SimpleLiteral> it=hasFormat.iterator();it.hasNext();)
				{
					SimpleLiteral temp=it.next();
					proxyRes.addProperty(DCTerms.hasFormat,temp.getValue(),temp.getLang());
				}
				
				//6-HasVersion
				List<SimpleLiteral> hasVersion=dcTerms.getHasVersion();
				for(Iterator<SimpleLiteral> it=hasVersion.iterator();it.hasNext();)
				{
					SimpleLiteral temp=it.next();
					proxyRes.addProperty(DCTerms.hasVersion,temp.getValue(),temp.getLang());
				}
				
				//7-IsFormatOf
				List<SimpleLiteral> isFormat=dcTerms.getIsFormatOf();
				for(Iterator<SimpleLiteral> it=isFormat.iterator();it.hasNext();)
				{
					SimpleLiteral temp=it.next();
					proxyRes.addProperty(DCTerms.isFormatOf,temp.getValue(),temp.getLang());
				}
				
				//8-IsReferencedBy
				List<SimpleLiteral> isReferencedBy=dcTerms.getIsReferencedBy();
				for(Iterator<SimpleLiteral> it=isReferencedBy.iterator();it.hasNext();)
				{
					SimpleLiteral temp=it.next();
					proxyRes.addProperty(DCTerms.isReferencedBy,temp.getValue(),temp.getLang());
				}
				
				//9-IsReplacedBy
				List<SimpleLiteral> isReplacedBy=dcTerms.getIsReplacedBy();
				for(Iterator<SimpleLiteral> it=isReplacedBy.iterator();it.hasNext();)
				{
					SimpleLiteral temp=it.next();
					proxyRes.addProperty(DCTerms.isReplacedBy,temp.getValue(),temp.getLang());
				}
				
				//10-IsRequiredBy
				List<SimpleLiteral> isRequiredBy=dcTerms.getIsRequiredBy();
				for(Iterator<SimpleLiteral> it=isRequiredBy.iterator();it.hasNext();)
				{
					SimpleLiteral temp=it.next();
					proxyRes.addProperty(DCTerms.isRequiredBy,temp.getValue(),temp.getLang());
				}
				
				//11-Issued
				List<SimpleLiteral> issued=dcTerms.getIssued();
				for(Iterator<SimpleLiteral> it=issued.iterator();it.hasNext();)
				{
					SimpleLiteral temp=it.next();
					proxyRes.addProperty(DCTerms.issued,temp.getValue(),temp.getLang());
				}
				
				//12-IsVersionOf
				List<SimpleLiteral> isVersionOf=dcTerms.getIsVersionOf();
				for(Iterator<SimpleLiteral> it=isVersionOf.iterator();it.hasNext();)
				{
					SimpleLiteral temp=it.next();
					proxyRes.addProperty(DCTerms.isVersionOf,temp.getValue(),temp.getLang());
				}
				
				//13-Medium
				List<SimpleLiteral> medium=dcTerms.getMedium();
				for(Iterator<SimpleLiteral> it=medium.iterator();it.hasNext();)
				{
					SimpleLiteral temp=it.next();
					proxyRes.addProperty(DCTerms.medium,temp.getValue(),temp.getLang());
				}
				
				//14-Provenance
				List<SimpleLiteral> provenance=dcTerms.getProvenance();
				for(Iterator<SimpleLiteral> it=provenance.iterator();it.hasNext();)
				{
					SimpleLiteral temp=it.next();
					proxyRes.addProperty(DCTerms.provenance,temp.getValue(),temp.getLang());
				}
				
				//15-References
				List<SimpleLiteral> references=dcTerms.getReferences();
				for(Iterator<SimpleLiteral> it=references.iterator();it.hasNext();)
				{
					SimpleLiteral temp=it.next();
					proxyRes.addProperty(DCTerms.references,temp.getValue(),temp.getLang());
				}	
				
				//16-Replaces
				List<SimpleLiteral> replaces=dcTerms.getReplaces();
				for(Iterator<SimpleLiteral> it=replaces.iterator();it.hasNext();)
				{
					SimpleLiteral temp=it.next();
					proxyRes.addProperty(DCTerms.replaces,temp.getValue(),temp.getLang());
				}
				
				//17-Requires
				List<SimpleLiteral> requires=dcTerms.getRequires();
				for(Iterator<SimpleLiteral> it=requires.iterator();it.hasNext();)
				{
					SimpleLiteral temp=it.next();
					proxyRes.addProperty(DCTerms.requires,temp.getValue(),temp.getLang());
				}
				
				//18-Spatial
				List<SimpleLiteral> spatial=dcTerms.getSpatial();
				for(Iterator<SimpleLiteral> it=spatial.iterator();it.hasNext();)
				{
					SimpleLiteral temp=it.next();
					proxyRes.addProperty(DCTerms.spatial,temp.getValue(),temp.getLang());
				}
				
				//19-Table of Contents
				List<SimpleLiteral> tableOfContents=dcTerms.getTableOfContents();
				for(Iterator<SimpleLiteral> it=tableOfContents.iterator();it.hasNext();)
				{
					SimpleLiteral temp=it.next();
					proxyRes.addProperty(DCTerms.tableOfContents,temp.getValue(),temp.getLang());
				}
				
				//20-Temporal
				List<SimpleLiteral> temporal=dcTerms.getTemporal();
				for(Iterator<SimpleLiteral> it=temporal.iterator();it.hasNext();)
				{
					SimpleLiteral temp=it.next();
					proxyRes.addProperty(DCTerms.temporal,temp.getValue(),temp.getLang());
				}
			}
			
			//*********************************************************************************************************
			EventWrapType eventWrap=proxy.getEventWrap();
			if(eventWrap!=null)
			{
				List<EventType> events=eventWrap.getWasPresentAt();
				for(Iterator<EventType> it=events.iterator();it.hasNext();)
				{
					//Event
					Resource eventRes=model.createResource(baseURI+"Event/"+"EventRes"+eventCnt++);
					eventRes.addProperty(RDF.type, model.createResource(edm+"Event"));
					EventType event=it.next();
					
					
					//Place
					PlaceType place=event.getHappenedAt();
					eventRes.addProperty(model.createProperty(edm,"happenedAt"),place.getPlace().getValue(),place.getPlace().getLang());
					
					
					List<String> placeUri=place.getPlaceResource();
					for(Iterator<String> it1=placeUri.iterator();it1.hasNext();)
					{
						//TODO this thime we map the URI to Place
						Resource placeRes=model.createResource(urlEncoding(it1.next(),"Events > wasPresentAt > happenedAt > placeResource"));
						placeRes.addProperty(RDF.type,model.createResource(edm+"Place"));
						eventRes.addProperty(model.createProperty(edm,"happenedAt"),placeRes);	
					}
					
					
					//TimeSpan
					TimeSpanType timeSpan=event.getOccuredAt();
					if(timeSpan.getStartDate()!=null)eventRes.addProperty(model.createProperty(edm,"occurredAt"), timeSpan.getStartDate().toXMLFormat());
					if(timeSpan.getEndDate()!=null)eventRes.addProperty(model.createProperty(edm,"occurredAt"), timeSpan.getEndDate().toXMLFormat());
					
					List<String> timSpanRes=timeSpan.getTimeSpanResource();
					for(Iterator<String> it1=timSpanRes.iterator();it1.hasNext();)
					{
						Resource timeSpanRes=model.createResource(urlEncoding(it1.next(),"Events > wasPresentAt > occuredAt > timeSpanResource"));
						timeSpanRes.addProperty(RDF.type, model.createResource(edm+"TimeSpan"));
						eventRes.addProperty(model.createProperty(edm,"occurredAt"), timeSpanRes);
					}
					
					
					//Agent
					List<AgentType> agents=event.getAgentWasPresentAt();
					for(Iterator<AgentType> it1=agents.iterator();it1.hasNext();)
					{
						AgentType tempAgent=it1.next();
	//					Literal agentLit=model.createLiteral(tempAgent.getAgentName().getValue(),tempAgent.getAgentName().getLang());
	//					agentLit.asResource().addProperty(model.createProperty(edm,"wasPresentAt"),eventRes);
						
						List<String> agentsList=tempAgent.getAgentResource();
						for(Iterator<String> it2=agentsList.iterator();it2.hasNext();)
						{
							//TODO This time we map URI to agent
							Resource agentRes=model.createResource(urlEncoding(it2.next(),"Events > wasPresentAt > agentWasPresentAt > agentResource"));
							agentRes.addProperty(RDF.type, model.createResource(edm+"Agent"));
							agentRes.addProperty(model.createProperty(edm,"wasPresentAt"),eventRes);
							List<SimpleLiteral> label=tempAgent.getSkosLabel();
							for(Iterator<SimpleLiteral> it3=label.iterator();it3.hasNext();)
							{
								SimpleLiteral temp=it3.next();
								agentRes.addProperty(model.createProperty(skos,"label"),temp.getValue(),temp.getLang());
							}
						}
					}
					
					//InfromationResource
					List<InformationResourceType> infResource=event.getInfResWasPresentAt();
					for(Iterator<InformationResourceType> it1=infResource.iterator();it1.hasNext();)
					{
						InformationResourceType tempInfRes=it1.next();
	//					Literal infResLit=model.createLiteral(tempInfRes.getDescription().getValue(),tempInfRes.getDescription().getLang());
	//					infResLit.asResource().addProperty(model.createProperty(edm,"wasPresentAt"),eventRes);
						
						
						//TODO this time we map the given URI to information resource 
						Resource infResRes=model.createResource(urlEncoding(tempInfRes.getResource(),"Events > wasPresentAt > infResWasPresentAt > resource"));
						infResRes.addProperty(RDF.type, model.createResource(edm+"InformationResource"));
						infResRes.addProperty(model.createProperty(edm,"wasPresentAt"),eventRes);
						
					}
					
					//PhysicalThing
					List<PhysicalThingType> physThing=event.getPhysThWasPresentAt();
					for(Iterator<PhysicalThingType> it1=physThing.iterator();it1.hasNext();)
					{
						PhysicalThingType tempPhyThing=it1.next();
						String phyThingString;
						Resource phyThingRes1;
						
						if(phyThing.getIdentifier()!=null)
						{
							if(phyThing.getIdentifier().getResType().equals("HTTP URI"))
							{
								phyThingString=phyThing.getIdentifier().getValue();
								phyThingRes1=model.createResource(urlEncoding(phyThingString,"Event > wasPresentAt > physThWasPresentAt > identifier"));
							}
							else 
							{
								phyThingString="PhysicalThing/"+phyThing.getIdentifier().getResType()+"/"+phyThing.getIdentifier().getValue()+physicalThingCnt++;
								URI uri = new URI(baseURI+phyThingString);
								phyThingRes1=model.createResource(uri.toASCIIString());
//								phyThingRes1=model.createResource(baseURI+phyThingString);
							}
						}else
						{
							phyThingString="PhysicalThing/ID/"+physicalThingCnt++;
							phyThingRes1=model.createResource(baseURI+phyThingResource);
						}
						
						phyThingRes1.addProperty(RDF.type,model.createResource(edm+"PhysicalThing"));
						
						//TODO Is this necessary
						if(tempPhyThing.getType()!=null)
						{
							phyThingRes1.addProperty(RDF.type,tempPhyThing.getType().getLang(),tempPhyThing.getType().getValue());
						}
						
								
						List<String> webResources2=tempPhyThing.getRealizes();
						for(Iterator<String> it2=webResources2.iterator();it2.hasNext();)
						{
							//TODO Are these URIs going to be mapped as WebResources?In ESE to EDM they are not.  
							phyThingRes1.addProperty(model.createProperty(edm,"realizes"), model.createResource(urlEncoding(it2.next(),"Event > wasPresentAt > physThWasPresentAt > realizes")));
						}
						
						//TODO Current location is added to proxy but what about this case
	//					List<PlaceType> currentLocation1 = tempPhyThing.getCurrentLocation();
						
						
						phyThingRes1.asResource().addProperty(model.createProperty(edm,"wasPresentAt"),eventRes);
	
					}
					
					//Add to Proxy
					proxyRes.addProperty(model.createProperty(edm,"wasPresentAt"),eventRes);
				}
			}
			proxyRes.addProperty(model.createProperty(edm,"proxyFor"),phyThingRes);
			proxyRes.addProperty(model.createProperty(edm,"proxyIn"),aggrRes);
			
			RelatedProxiesWrapType relatProxiesWrap=proxy.getRelatedWrap();
			if(relatProxiesWrap!=null)
			{
				List<RelatedProxiesType> relatedProxies=relatProxiesWrap.getRelatedProxies();
				for(Iterator<RelatedProxiesType> it=relatedProxies.iterator();it.hasNext();)
				{
					RelatedProxiesType temp=it.next();
					String relType=temp.getRelationType();
					
					Resource proxy2Res=model.createResource(urlEncoding(temp.getProxyUri(),"Related > relatedProxies > ProxyUri"));
					//TODO This is not necessary however we map it to proxy just to demonstrate the example
					proxy2Res.addProperty(RDF.type, model.createResource(ore+"Proxy"));
					
					if(relType.equals("hasPart"))proxyRes.addProperty(DCTerms.hasPart,proxy2Res);
					else proxyRes.addProperty(model.createProperty(edm,relType),proxy2Res);
		
				}
			}
		}		
		
		
		//*********************************************************************************************************
		
		storeProperties();
    	
		
		ByteArrayOutputStream outStream = new ByteArrayOutputStream();
		model.write(outStream,"RDF/XML");
		
		return outStream;
				
	}
	
	
	
}
