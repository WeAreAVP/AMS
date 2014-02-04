package gr.ntua.ivml.mint.harvesting;

//import gr.ntua.ivml.mint.harvesting.xml.schema.ObjectFactory;
import gr.ntua.ivml.mint.harvesting.xml.schema.MetadataFormatType;
import gr.ntua.ivml.mint.harvesting.xml.schema.OAIPMHtype;
import gr.ntua.ivml.mint.harvesting.xml.schema.SetType;

import java.io.ByteArrayInputStream;
import java.io.IOException;
import java.io.InputStream;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;
//import java.util.List;

import javax.xml.bind.JAXBContext;
import javax.xml.bind.JAXBElement;
import javax.xml.bind.JAXBException;
import javax.xml.bind.Unmarshaller;
import javax.xml.parsers.ParserConfigurationException;
import javax.xml.transform.TransformerException;

import org.w3c.dom.NodeList;
import org.xml.sax.SAXException;

import ORG.oclc.oai.harvester2.verb.Identify;
import ORG.oclc.oai.harvester2.verb.ListMetadataFormats;
import ORG.oclc.oai.harvester2.verb.ListSets;

public class RepositoryValidator {

	private static JAXBContext jc;
	//private static ObjectFactory fact;
	private static Unmarshaller u;
	static{
		try {
			jc = JAXBContext.newInstance( "gr.ntua.ivml.mint.harvesting.xml.schema" );
			//fact = new ObjectFactory();
			u = jc.createUnmarshaller();
		} catch (JAXBException e) {
			//e.printStackTrace();
		}
		
	} //for application Scope.
	
	//call this method first for repository validity before do anything else...
	public static boolean isValid(String baseURL){
		boolean res = false;
		Identify ident = null;
		//validate baseURL
		try {
			ident = new Identify(baseURL);
			res = true;
		} catch (IOException e) {
			//e.printStackTrace();
			res = false;
		} catch (ParserConfigurationException e) {
			//e.printStackTrace();
			res = false;
		} catch (SAXException e) {
			//e.printStackTrace();
			res = false;
		} catch (TransformerException e) {
			//e.printStackTrace();
			res = false;
		}
		
		//validate response (version plus xml response structure)
		if(ident != null){
			
			try {
				NodeList errors = ident.getErrors();
				if( (errors != null) && (errors.getLength() > 0) ){
					res = false;
				}else{
					ident.getProtocolVersion();
					res = true;
				}
			} catch (TransformerException e) {
				//e.printStackTrace();
				res = false;
			} catch (NoSuchFieldException e) {
				//e.printStackTrace();
				res = false;
			}
		}
		
		
		ident = null; // clean up the mess ;)
		return res;
	}
	
	@SuppressWarnings("unchecked")
	public static ArrayList<String> getNameSpaces(String baseURL){
		ArrayList<String> res = new ArrayList<String>();
		OAIPMHtype xmlType = null;
		ListMetadataFormats list = null;
		try {
			list = new ListMetadataFormats(baseURL);
			String xmlRec = list.toString();
			if(xmlRec.startsWith("<?")){
				int offset = xmlRec.indexOf("?>");
				xmlRec = xmlRec.substring(offset+2);
			}
			InputStream is = new ByteArrayInputStream(xmlRec.getBytes("UTF-8"));
			JAXBElement<OAIPMHtype> oai = (JAXBElement<OAIPMHtype>)u.unmarshal(is);
			xmlType = oai.getValue();
			ArrayList<MetadataFormatType> res0 = (ArrayList<MetadataFormatType>) xmlType.getListMetadataFormats().getMetadataFormat();
			Iterator<MetadataFormatType> itr = res0.iterator();
			while(itr.hasNext()){
				MetadataFormatType typ = itr.next();
				res.add(typ.getMetadataPrefix());
			}
		} catch (IOException e) {
			e.printStackTrace();
		} catch (ParserConfigurationException e) {
			e.printStackTrace();
		} catch (SAXException e) {
			e.printStackTrace();
		} catch (TransformerException e) {
			e.printStackTrace();
		} catch (JAXBException e) {
			e.printStackTrace();
		}
		return res;
	}
	
	
	//retrieve the info available by the Identity verb of oai repository, e.g. protocol version
	//and date granularity
	@SuppressWarnings("unchecked")
	public static HashMap<String, String> getIdentifyResponseInfo(String baseURL){
		Identify ident = null;
		OAIPMHtype xmlType = null;
		HashMap<String, String> res = new HashMap<String, String>();
		
		try {
			ident = new Identify(baseURL);
			String xmlRec = ident.toString();
			if(xmlRec.startsWith("<?")){
				int offset = xmlRec.indexOf("?>");
				xmlRec = xmlRec.substring(offset+2);
			}
			InputStream is = new ByteArrayInputStream(xmlRec.getBytes("UTF-8"));
			JAXBElement<OAIPMHtype> oai = (JAXBElement<OAIPMHtype>)u.unmarshal(is);
			xmlType = oai.getValue();
			if(xmlType.getIdentify().getBaseURL() != null){
				res.put("Repository BaseURL", xmlType.getIdentify().getBaseURL());
			}
			if(xmlType.getIdentify().getEarliestDatestamp() != null){
				res.put("Earliest DateStamp", xmlType.getIdentify().getEarliestDatestamp());
			}
			if(xmlType.getIdentify().getProtocolVersion() != null){
				res.put("Protocol Version", xmlType.getIdentify().getProtocolVersion());
			}
			if(xmlType.getIdentify().getRepositoryName() != null){
				res.put("Repository Name", xmlType.getIdentify().getRepositoryName());
			}
			if( (xmlType.getIdentify().getAdminEmail() != null) && (xmlType.getIdentify().getAdminEmail().size() > 0) ){
				res.put("Admin Email", xmlType.getIdentify().getAdminEmail().get(0));
			}
			if(xmlType.getIdentify().getDeletedRecord() != null){
				res.put("Deleted Record", xmlType.getIdentify().getDeletedRecord().value());
			}
			if(xmlType.getIdentify().getGranularity() != null){
				res.put("Granularity", xmlType.getIdentify().getGranularity().value());
			}
		} catch (IOException e) {
			//e.printStackTrace();
		} catch (ParserConfigurationException e) {
			//e.printStackTrace();
		} catch (SAXException e) {
			//e.printStackTrace();
		} catch (TransformerException e) {
			//e.printStackTrace();
		} catch (JAXBException e) {
			//e.printStackTrace();
		}
		ident = null;
		xmlType = null;
		return res;
	}
	//Use Key for presentation reasons, it is the set name, for the actual harvesting the value is needed (SetSpec).
	@SuppressWarnings("unchecked")
	public static HashMap<String, String> getSets(String baseURL){
		ListSets sets = null;
		HashMap<String, String> res = new HashMap<String, String>();
		OAIPMHtype xmlType = null;
		try {
			sets = new ListSets(baseURL);
			NodeList errors = sets.getErrors();
			if( (errors != null) && (errors.getLength() > 0) ){
				return null;
			}else{
				String xmlRec = sets.toString();
				if(xmlRec.startsWith("<?")){
					int offset = xmlRec.indexOf("?>");
					xmlRec = xmlRec.substring(offset+2);
				}
				InputStream is = new ByteArrayInputStream(xmlRec.getBytes("UTF-8"));
				JAXBElement<OAIPMHtype> oai = (JAXBElement<OAIPMHtype>)u.unmarshal(is);
				xmlType = oai.getValue();
				Iterator<SetType> itr = xmlType.getListSets().getSet().iterator();
				SetType tmpType = null;
				while(itr.hasNext()){
					tmpType = itr.next();
					res.put(tmpType.getSetName(), tmpType.getSetSpec());
				}
			}
		} catch (IOException e) {
			e.printStackTrace();
		} catch (ParserConfigurationException e) {
			e.printStackTrace();
		} catch (SAXException e) {
			e.printStackTrace();
		} catch (TransformerException e) {
			e.printStackTrace();
		} catch (JAXBException e) {
			e.printStackTrace();
		}
		sets = null;
		xmlType = null;
		return res;
	}
	
}
