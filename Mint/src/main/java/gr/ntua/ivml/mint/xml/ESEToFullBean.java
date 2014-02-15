package gr.ntua.ivml.mint.xml;

import gr.ntua.ivml.mint.oaiexporter.xml.schema.ESE_V3_2.ESE_V3_22;
import gr.ntua.ivml.mint.oaiexporter.xml.schema.ESE_V3_2.recordType;
import gr.ntua.ivml.mint.oaiexporter.xml.schema.ESE_V3_2.dc.SimpleLiteral;
import gr.ntua.ivml.mint.oaiexporter.xml.schema.ESE_V3_2.xs.anyURI;
import gr.ntua.ivml.mint.oaiexporter.xml.schema.ESE_V3_2.xs.anyURIType;

import java.util.ArrayList;
import java.util.Iterator;

public class ESEToFullBean {
	
	static{}
	
	public static ArrayList<FullBean> getFullBeans(String ESEXml){
		ArrayList<FullBean> beanz = new ArrayList<FullBean>();
		FullBean bean = null;
		ArrayList<String> tmp = null;
		String[] a = null;
		Iterator itr = null;
		
		try {
			ESE_V3_22 doc = ESE_V3_22.loadFromString(ESEXml);
			
			Iterator mainItr = doc.metadata.first().record.iterator();
			
			while(mainItr.hasNext()){
				//MetadataType tmpMeta = mainItr.next();
				recordType tmprec = (recordType)mainItr.next();
				bean = new FullBean();
				
				itr = tmprec.coverage.iterator();
				tmp = new ArrayList<String>();
				while(itr.hasNext()){
					SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
					tmp.add(tmpElem.getValue());
				}
				a = new String[tmp.size()];
				bean.setDcCoverage(tmp.toArray(a));
				
				//europeana object.
				itr = tmprec.object.iterator();
				tmp = new ArrayList<String>();
				while(itr.hasNext()){
					anyURIType tmpElem = (anyURIType) itr.next();					
					tmp.add(tmpElem.getValue());
				}
				a = new String[tmp.size()];
				bean.setEuropeanaObject(tmp.toArray(a));
				//dc title
				itr = tmprec.title.iterator();
				tmp = new ArrayList<String>();
				while(itr.hasNext()){
					SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
					tmp.add(tmpElem.getValue());
				}
				a = new String[tmp.size()];
				bean.setDcTitle(tmp.toArray(a));
				//dc description
				itr = tmprec.description.iterator();
				tmp = new ArrayList<String>();
				while(itr.hasNext()){
					SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
					tmp.add(tmpElem.getValue());
				}
				a = new String[tmp.size()];
				bean.setDcDescription(tmp.toArray(a));
				
				//dc subject
				itr = tmprec.subject.iterator();
				tmp = new ArrayList<String>();
				while(itr.hasNext()){
					SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
					tmp.add(tmpElem.getValue());
				}
				a = new String[tmp.size()];
				bean.setDcSubject(tmp.toArray(a));
				//dc source
				itr = tmprec.source.iterator();
				tmp = new ArrayList<String>();
				while(itr.hasNext()){
					SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
					tmp.add(tmpElem.getValue());
				}
				a = new String[tmp.size()];
				bean.setDcSource(tmp.toArray(a));
				//dc type
				itr = tmprec.type.iterator();
				tmp = new ArrayList<String>();
				while(itr.hasNext()){
					SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
					tmp.add(tmpElem.getValue());
				}
				a = new String[tmp.size()];
				bean.setDcType(tmp.toArray(a));
				//if(bean.getDcType()!=null && bean.getDcType().length>0)
				//bean.setEuropeanaType(tmp.toArray(a)[0]);
				//else{bean.setEuropeanaType("TEXT");}
				//europeana type
				if(tmprec.type3.first() != null){
					bean.setEuropeanaType(tmprec.type3.first().getValue());
				}else{
					bean.setEuropeanaType("TEXT");
				}
				//itr = tmprec.type2.iterator();
				//tmp = new ArrayList<String>();
				//while(itr.hasNext()){
				//	SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
				//	tmp.add(tmpElem.getValue());
				//}
				//a = new String[tmp.size()];
				//bean.setDcType(tmp.toArray(a));
				//dc rights
				itr = tmprec.rights.iterator();
				tmp = new ArrayList<String>();
				while(itr.hasNext()){
					SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
					tmp.add(tmpElem.getValue());
				}
				a = new String[tmp.size()];
				bean.setDcRights(tmp.toArray(a));
				//dc relation
				itr = tmprec.relation.iterator();
				tmp = new ArrayList<String>();
				while(itr.hasNext()){
					SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
					tmp.add(tmpElem.getValue());
				}
				a = new String[tmp.size()];
				bean.setDcRelation(tmp.toArray(a));
				//dc publisher
				itr = tmprec.publisher.iterator();
				tmp = new ArrayList<String>();
				while(itr.hasNext()){
					SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
					tmp.add(tmpElem.getValue());
				}
				a = new String[tmp.size()];
				bean.setDcPublisher(tmp.toArray(a));
				//dc language
				itr = tmprec.language.iterator();
				tmp = new ArrayList<String>();
				while(itr.hasNext()){
					SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
					tmp.add(tmpElem.getValue());
				}
				a = new String[tmp.size()];
				bean.setDcLanguage(tmp.toArray(a));
				//dc identifier
				itr = tmprec.identifier.iterator();
				tmp = new ArrayList<String>();
				while(itr.hasNext()){
					SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
					tmp.add(tmpElem.getValue());
				}
				a = new String[tmp.size()];
				bean.setDcIdentifier(tmp.toArray(a));
				//dc format
				itr = tmprec.format.iterator();
				tmp = new ArrayList<String>();
				while(itr.hasNext()){
					SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
					tmp.add(tmpElem.getValue());
				}
				a = new String[tmp.size()];
				bean.setDcFormat(tmp.toArray(a));
				//dc date
				itr = tmprec.date.iterator();
				tmp = new ArrayList<String>();
				while(itr.hasNext()){
					SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
					tmp.add(tmpElem.getValue());
				}
				a = new String[tmp.size()];
				bean.setDcDate(tmp.toArray(a));
				//dc creator
				itr = tmprec.creator.iterator();
				tmp = new ArrayList<String>();
				while(itr.hasNext()){
					SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
					tmp.add(tmpElem.getValue());
				}
				a = new String[tmp.size()];
				bean.setDcCreator(tmp.toArray(a));
				//dc contributor
				itr = tmprec.contributor.iterator();
				tmp = new ArrayList<String>();
				while(itr.hasNext()){
					SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
					tmp.add(tmpElem.getValue());
				}
				a = new String[tmp.size()];
				bean.setDcContributor(tmp.toArray(a));
				
				//dcterms references
				itr = tmprec.references.iterator();
				tmp = new ArrayList<String>();
				while(itr.hasNext()){
					SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
					tmp.add(tmpElem.getValue());
				}
				a = new String[tmp.size()];
				bean.setDctermsReferences(tmp.toArray(a));
				//dcterms replaces
				itr = tmprec.replaces.iterator();
				tmp = new ArrayList<String>();
				while(itr.hasNext()){
					SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
					tmp.add(tmpElem.getValue());
				}
				a = new String[tmp.size()];
				bean.setDctermsReplaces(tmp.toArray(a));
				//dcterms requires
				itr = tmprec.requires.iterator();
				tmp = new ArrayList<String>();
				while(itr.hasNext()){
					SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
					tmp.add(tmpElem.getValue());
				}
				a = new String[tmp.size()];
				bean.setDctermsRequires(tmp.toArray(a));
				//dcterms spatial
				itr = tmprec.spatial.iterator();
				tmp = new ArrayList<String>();
				while(itr.hasNext()){
					SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
					tmp.add(tmpElem.getValue());
				}
				a = new String[tmp.size()];
				bean.setDctermsSpatial(tmp.toArray(a));
				//dcterms tableofcontents
				itr = tmprec.tableOfContents.iterator();
				tmp = new ArrayList<String>();
				while(itr.hasNext()){
					SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
					tmp.add(tmpElem.getValue());
				}
				a = new String[tmp.size()];
				bean.setDctermsTableOfContents(tmp.toArray(a));
				//dcterms temporal
				itr = tmprec.temporal.iterator();
				tmp = new ArrayList<String>();
				while(itr.hasNext()){
					SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
					tmp.add(tmpElem.getValue());
				}
				a = new String[tmp.size()];
				bean.setDctermsTemporal(tmp.toArray(a));
				//dcterms alternative
				itr = tmprec.alternative.iterator();
				tmp = new ArrayList<String>();
				while(itr.hasNext()){
					SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
					tmp.add(tmpElem.getValue());
				}
				a = new String[tmp.size()];
				bean.setDctermsAlternative(tmp.toArray(a));
				//dcterms created
				itr = tmprec.created.iterator();
				tmp = new ArrayList<String>();
				while(itr.hasNext()){
					SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
					tmp.add(tmpElem.getValue());
				}
				a = new String[tmp.size()];
				bean.setDctermsCreated(tmp.toArray(a));
				//dcterms conformsTo
				itr = tmprec.conformsTo.iterator();
				tmp = new ArrayList<String>();
				while(itr.hasNext()){
					SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
					tmp.add(tmpElem.getValue());
				}
				a = new String[tmp.size()];
				bean.setDctermsConformsTo(tmp.toArray(a));
				//dcterms extent
				itr = tmprec.extent.iterator();
				tmp = new ArrayList<String>();
				while(itr.hasNext()){
					SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
					tmp.add(tmpElem.getValue());
				}
				a = new String[tmp.size()];
				bean.setDctermsExtent(tmp.toArray(a));
				//dcterms hasFormat
				itr = tmprec.hasFormat.iterator();
				tmp = new ArrayList<String>();
				while(itr.hasNext()){
					SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
					tmp.add(tmpElem.getValue());
				}
				a = new String[tmp.size()];
				bean.setDctermsHasFormat(tmp.toArray(a));
				//dcterms hasPart
				itr = tmprec.hasPart.iterator();
				tmp = new ArrayList<String>();
				while(itr.hasNext()){
					SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
					tmp.add(tmpElem.getValue());
				}
				a = new String[tmp.size()];
				bean.setDctermsHasPart(tmp.toArray(a));
				//dcterms hasVersion
				itr = tmprec.hasVersion.iterator();
				tmp = new ArrayList<String>();
				while(itr.hasNext()){
					SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
					tmp.add(tmpElem.getValue());
				}
				a = new String[tmp.size()];
				bean.setDctermsHasVersion(tmp.toArray(a));
				//dcterms isFormatOf
				itr = tmprec.isFormatOf.iterator();
				tmp = new ArrayList<String>();
				while(itr.hasNext()){
					SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
					tmp.add(tmpElem.getValue());
				}
				a = new String[tmp.size()];
				bean.setDctermsIsFormatOf(tmp.toArray(a));
				//dcterms isPartOf
				itr = tmprec.isPartOf.iterator();
				tmp = new ArrayList<String>();
				while(itr.hasNext()){
					SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
					tmp.add(tmpElem.getValue());
				}
				a = new String[tmp.size()];
				bean.setDctermsIsPartOf(tmp.toArray(a));
				//dcterms isReferencedBy
				itr = tmprec.isReferencedBy.iterator();
				tmp = new ArrayList<String>();
				while(itr.hasNext()){
					SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
					tmp.add(tmpElem.getValue());
				}
				a = new String[tmp.size()];
				bean.setDctermsIsReferencedBy(tmp.toArray(a));
				//dcterms isReplacesBy
				itr = tmprec.isReplacedBy.iterator();
				tmp = new ArrayList<String>();
				while(itr.hasNext()){
					SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
					tmp.add(tmpElem.getValue());
				}
				a = new String[tmp.size()];
				bean.setDctermsIsReplacedBy(tmp.toArray(a));
				//dcterms isRequiredBy
				itr = tmprec.isRequiredBy.iterator();
				tmp = new ArrayList<String>();
				while(itr.hasNext()){
					SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
					tmp.add(tmpElem.getValue());
				}
				a = new String[tmp.size()];
				bean.setDctermsIsRequiredBy(tmp.toArray(a));
				//dcterms issued
				itr = tmprec.issued.iterator();
				tmp = new ArrayList<String>();
				while(itr.hasNext()){
					SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
					tmp.add(tmpElem.getValue());
				}
				a = new String[tmp.size()];
				bean.setDctermsIssued(tmp.toArray(a));
				//dcterms isVersionOf
				itr = tmprec.isVersionOf.iterator();
				tmp = new ArrayList<String>();
				while(itr.hasNext()){
					SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
					tmp.add(tmpElem.getValue());
				}
				a = new String[tmp.size()];
				bean.setDctermsIsVersionOf(tmp.toArray(a));
				//dcterms medium
				itr = tmprec.medium.iterator();
				tmp = new ArrayList<String>();
				while(itr.hasNext()){
					SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
					tmp.add(tmpElem.getValue());
				}
				a = new String[tmp.size()];
				bean.setDctermsMedium(tmp.toArray(a));
				//dcterms provenance
				itr = tmprec.provenance.iterator();
				tmp = new ArrayList<String>();
				while(itr.hasNext()){
					SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
					tmp.add(tmpElem.getValue());
				}
				a = new String[tmp.size()];
				bean.setDctermsProvenance(tmp.toArray(a));
				//europeana isShownAt
				itr = tmprec.isShownAt.iterator();
				tmp = new ArrayList<String>();
				while(itr.hasNext()){
					anyURIType tmpElem = (anyURIType) itr.next();
					tmp.add(tmpElem.getValue());
				}
				a = new String[tmp.size()];
				bean.setEuropeanaisShownAt(tmp.toArray(a));
				//europeana isShownBy
				itr = tmprec.isShownBy.iterator();
				tmp = new ArrayList<String>();
				while(itr.hasNext()){
					anyURIType tmpElem = (anyURIType) itr.next();
					tmp.add(tmpElem.getValue());
				}
				a = new String[tmp.size()];
				bean.setEuropeanaisShownBy(tmp.toArray(a));
				beanz.add(bean);
			}
			
		} catch (Exception e) {
			e.printStackTrace();
		}
		return beanz;
	}
	
	public static FullBean getFullBean(String ESEXml){
		FullBean bean = new FullBean();
		ArrayList<String> tmp = null;
		String[] a = null;
		Iterator itr = null;
		try {
			ESE_V3_22 doc = ESE_V3_22.loadFromString(ESEXml);
			
			itr = doc.metadata.first().record.first().coverage.iterator();
			tmp = new ArrayList<String>();
			while(itr.hasNext()){
				SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
				tmp.add(tmpElem.getValue());
			}
			a = new String[tmp.size()];
			bean.setDcCoverage(tmp.toArray(a));
			//europeana object.
			itr = doc.metadata.first().record.first().object.iterator();
			tmp = new ArrayList<String>();
			while(itr.hasNext()){
				anyURIType tmpElem = (anyURIType) itr.next();					
				tmp.add(tmpElem.getValue());
			}
			a = new String[tmp.size()];
			bean.setEuropeanaObject(tmp.toArray(a));
			
			//dc title
			itr = doc.metadata.first().record.first().title.iterator();
			tmp = new ArrayList<String>();
			while(itr.hasNext()){
				SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
				tmp.add(tmpElem.getValue());
			}
			a = new String[tmp.size()];
			bean.setDcTitle(tmp.toArray(a));
			//dc description
			itr = doc.metadata.first().record.first().description.iterator();
			tmp = new ArrayList<String>();
			while(itr.hasNext()){
				SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
				tmp.add(tmpElem.getValue());
			}
			a = new String[tmp.size()];
			bean.setDcDescription(tmp.toArray(a));
			
			//dc subject
			itr = doc.metadata.first().record.first().subject.iterator();
			tmp = new ArrayList<String>();
			while(itr.hasNext()){
				SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
				tmp.add(tmpElem.getValue());
			}
			a = new String[tmp.size()];
			bean.setDcSubject(tmp.toArray(a));
			//dc source
			itr = doc.metadata.first().record.first().source.iterator();
			tmp = new ArrayList<String>();
			while(itr.hasNext()){
				SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
				tmp.add(tmpElem.getValue());
			}
			a = new String[tmp.size()];
			bean.setDcSource(tmp.toArray(a));
			//dc type
			itr = doc.metadata.first().record.first().type.iterator();
			tmp = new ArrayList<String>();
			while(itr.hasNext()){
				SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
				tmp.add(tmpElem.getValue());
			}
			a = new String[tmp.size()];
			bean.setDcType(tmp.toArray(a));
			if(bean.getDcType()!=null && bean.getDcType().length>0)
			bean.setEuropeanaType(tmp.toArray(a)[0]);
			else{bean.setEuropeanaType("TEXT");}
			//dc rights
			itr = doc.metadata.first().record.first().rights.iterator();
			tmp = new ArrayList<String>();
			while(itr.hasNext()){
				SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
				tmp.add(tmpElem.getValue());
			}
			a = new String[tmp.size()];
			bean.setDcRights(tmp.toArray(a));
			//dc relation
			itr = doc.metadata.first().record.first().relation.iterator();
			tmp = new ArrayList<String>();
			while(itr.hasNext()){
				SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
				tmp.add(tmpElem.getValue());
			}
			a = new String[tmp.size()];
			bean.setDcRelation(tmp.toArray(a));
			//dc publisher
			itr = doc.metadata.first().record.first().publisher.iterator();
			tmp = new ArrayList<String>();
			while(itr.hasNext()){
				SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
				tmp.add(tmpElem.getValue());
			}
			a = new String[tmp.size()];
			bean.setDcPublisher(tmp.toArray(a));
			//dc language
			itr = doc.metadata.first().record.first().language.iterator();
			tmp = new ArrayList<String>();
			while(itr.hasNext()){
				SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
				tmp.add(tmpElem.getValue());
			}
			a = new String[tmp.size()];
			bean.setDcLanguage(tmp.toArray(a));
			//dc identifier
			itr = doc.metadata.first().record.first().identifier.iterator();
			tmp = new ArrayList<String>();
			while(itr.hasNext()){
				SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
				tmp.add(tmpElem.getValue());
			}
			a = new String[tmp.size()];
			bean.setDcIdentifier(tmp.toArray(a));
			//dc format
			itr = doc.metadata.first().record.first().format.iterator();
			tmp = new ArrayList<String>();
			while(itr.hasNext()){
				SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
				tmp.add(tmpElem.getValue());
			}
			a = new String[tmp.size()];
			bean.setDcFormat(tmp.toArray(a));
			//dc date
			itr = doc.metadata.first().record.first().date.iterator();
			tmp = new ArrayList<String>();
			while(itr.hasNext()){
				SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
				tmp.add(tmpElem.getValue());
			}
			a = new String[tmp.size()];
			bean.setDcDate(tmp.toArray(a));
			//dc creator
			itr = doc.metadata.first().record.first().creator.iterator();
			tmp = new ArrayList<String>();
			while(itr.hasNext()){
				SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
				tmp.add(tmpElem.getValue());
			}
			a = new String[tmp.size()];
			bean.setDcCreator(tmp.toArray(a));
			//dc contributor
			itr = doc.metadata.first().record.first().contributor.iterator();
			tmp = new ArrayList<String>();
			while(itr.hasNext()){
				SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
				tmp.add(tmpElem.getValue());
			}
			a = new String[tmp.size()];
			bean.setDcContributor(tmp.toArray(a));
			
			//dcterms references
			itr = doc.metadata.first().record.first().references.iterator();
			tmp = new ArrayList<String>();
			while(itr.hasNext()){
				SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
				tmp.add(tmpElem.getValue());
			}
			a = new String[tmp.size()];
			bean.setDctermsReferences(tmp.toArray(a));
			//dcterms replaces
			itr = doc.metadata.first().record.first().replaces.iterator();
			tmp = new ArrayList<String>();
			while(itr.hasNext()){
				SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
				tmp.add(tmpElem.getValue());
			}
			a = new String[tmp.size()];
			bean.setDctermsReplaces(tmp.toArray(a));
			//dcterms requires
			itr = doc.metadata.first().record.first().requires.iterator();
			tmp = new ArrayList<String>();
			while(itr.hasNext()){
				SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
				tmp.add(tmpElem.getValue());
			}
			a = new String[tmp.size()];
			bean.setDctermsRequires(tmp.toArray(a));
			//dcterms spatial
			itr = doc.metadata.first().record.first().spatial.iterator();
			tmp = new ArrayList<String>();
			while(itr.hasNext()){
				SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
				tmp.add(tmpElem.getValue());
			}
			a = new String[tmp.size()];
			bean.setDctermsSpatial(tmp.toArray(a));
			//dcterms tableofcontents
			itr = doc.metadata.first().record.first().tableOfContents.iterator();
			tmp = new ArrayList<String>();
			while(itr.hasNext()){
				SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
				tmp.add(tmpElem.getValue());
			}
			a = new String[tmp.size()];
			bean.setDctermsTableOfContents(tmp.toArray(a));
			//dcterms temporal
			itr = doc.metadata.first().record.first().temporal.iterator();
			tmp = new ArrayList<String>();
			while(itr.hasNext()){
				SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
				tmp.add(tmpElem.getValue());
			}
			a = new String[tmp.size()];
			bean.setDctermsTemporal(tmp.toArray(a));
			//dcterms alternative
			itr = doc.metadata.first().record.first().alternative.iterator();
			tmp = new ArrayList<String>();
			while(itr.hasNext()){
				SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
				tmp.add(tmpElem.getValue());
			}
			a = new String[tmp.size()];
			bean.setDctermsAlternative(tmp.toArray(a));
			//dcterms created
			itr = doc.metadata.first().record.first().created.iterator();
			tmp = new ArrayList<String>();
			while(itr.hasNext()){
				SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
				tmp.add(tmpElem.getValue());
			}
			a = new String[tmp.size()];
			bean.setDctermsCreated(tmp.toArray(a));
			//dcterms conformsTo
			itr = doc.metadata.first().record.first().conformsTo.iterator();
			tmp = new ArrayList<String>();
			while(itr.hasNext()){
				SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
				tmp.add(tmpElem.getValue());
			}
			a = new String[tmp.size()];
			bean.setDctermsConformsTo(tmp.toArray(a));
			//dcterms extent
			itr = doc.metadata.first().record.first().extent.iterator();
			tmp = new ArrayList<String>();
			while(itr.hasNext()){
				SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
				tmp.add(tmpElem.getValue());
			}
			a = new String[tmp.size()];
			bean.setDctermsExtent(tmp.toArray(a));
			//dcterms hasFormat
			itr = doc.metadata.first().record.first().hasFormat.iterator();
			tmp = new ArrayList<String>();
			while(itr.hasNext()){
				SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
				tmp.add(tmpElem.getValue());
			}
			a = new String[tmp.size()];
			bean.setDctermsHasFormat(tmp.toArray(a));
			//dcterms hasPart
			itr = doc.metadata.first().record.first().hasPart.iterator();
			tmp = new ArrayList<String>();
			while(itr.hasNext()){
				SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
				tmp.add(tmpElem.getValue());
			}
			a = new String[tmp.size()];
			bean.setDctermsHasPart(tmp.toArray(a));
			//dcterms hasVersion
			itr = doc.metadata.first().record.first().hasVersion.iterator();
			tmp = new ArrayList<String>();
			while(itr.hasNext()){
				SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
				tmp.add(tmpElem.getValue());
			}
			a = new String[tmp.size()];
			bean.setDctermsHasVersion(tmp.toArray(a));
			//dcterms isFormatOf
			itr = doc.metadata.first().record.first().isFormatOf.iterator();
			tmp = new ArrayList<String>();
			while(itr.hasNext()){
				SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
				tmp.add(tmpElem.getValue());
			}
			a = new String[tmp.size()];
			bean.setDctermsIsFormatOf(tmp.toArray(a));
			//dcterms isPartOf
			itr = doc.metadata.first().record.first().isPartOf.iterator();
			tmp = new ArrayList<String>();
			while(itr.hasNext()){
				SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
				tmp.add(tmpElem.getValue());
			}
			a = new String[tmp.size()];
			bean.setDctermsIsPartOf(tmp.toArray(a));
			//dcterms isReferencedBy
			itr = doc.metadata.first().record.first().isReferencedBy.iterator();
			tmp = new ArrayList<String>();
			while(itr.hasNext()){
				SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
				tmp.add(tmpElem.getValue());
			}
			a = new String[tmp.size()];
			bean.setDctermsIsReferencedBy(tmp.toArray(a));
			//dcterms isReplacesBy
			itr = doc.metadata.first().record.first().isReplacedBy.iterator();
			tmp = new ArrayList<String>();
			while(itr.hasNext()){
				SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
				tmp.add(tmpElem.getValue());
			}
			a = new String[tmp.size()];
			bean.setDctermsIsReplacedBy(tmp.toArray(a));
			//dcterms isRequiredBy
			itr = doc.metadata.first().record.first().isRequiredBy.iterator();
			tmp = new ArrayList<String>();
			while(itr.hasNext()){
				SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
				tmp.add(tmpElem.getValue());
			}
			a = new String[tmp.size()];
			bean.setDctermsIsRequiredBy(tmp.toArray(a));
			//dcterms issued
			itr = doc.metadata.first().record.first().issued.iterator();
			tmp = new ArrayList<String>();
			while(itr.hasNext()){
				SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
				tmp.add(tmpElem.getValue());
			}
			a = new String[tmp.size()];
			bean.setDctermsIssued(tmp.toArray(a));
			//dcterms isVersionOf
			itr = doc.metadata.first().record.first().isVersionOf.iterator();
			tmp = new ArrayList<String>();
			while(itr.hasNext()){
				SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
				tmp.add(tmpElem.getValue());
			}
			a = new String[tmp.size()];
			bean.setDctermsIsVersionOf(tmp.toArray(a));
			//dcterms medium
			itr = doc.metadata.first().record.first().medium.iterator();
			tmp = new ArrayList<String>();
			while(itr.hasNext()){
				SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
				tmp.add(tmpElem.getValue());
			}
			a = new String[tmp.size()];
			bean.setDctermsMedium(tmp.toArray(a));
			//dcterms provenance
			itr = doc.metadata.first().record.first().provenance.iterator();
			tmp = new ArrayList<String>();
			while(itr.hasNext()){
				SimpleLiteral tmpElem = (SimpleLiteral) itr.next();
				tmp.add(tmpElem.getValue());
			}
			a = new String[tmp.size()];
			bean.setDctermsProvenance(tmp.toArray(a));
			//europeana isShownAt
			itr = doc.metadata.first().record.first().isShownAt.iterator();
			tmp = new ArrayList<String>();
			while(itr.hasNext()){
				anyURIType tmpElem = (anyURIType) itr.next();
				tmp.add(tmpElem.getValue());
			}
			a = new String[tmp.size()];
			bean.setEuropeanaisShownAt(tmp.toArray(a));
			//europeana isShownBy
			itr = doc.metadata.first().record.first().isShownBy.iterator();
			tmp = new ArrayList<String>();
			while(itr.hasNext()){
				anyURIType tmpElem = (anyURIType) itr.next();
				tmp.add(tmpElem.getValue());
			}
			a = new String[tmp.size()];
			bean.setEuropeanaisShownBy(tmp.toArray(a));
		} catch (Exception e) {
			e.printStackTrace();
		}
		
		return bean;
	}
}
