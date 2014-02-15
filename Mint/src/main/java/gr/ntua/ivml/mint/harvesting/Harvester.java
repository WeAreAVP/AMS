package gr.ntua.ivml.mint.harvesting;


import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.harvesting.io.MultipleRecordsZipImporter;
import gr.ntua.ivml.mint.harvesting.xml.schema.HeaderType;
import gr.ntua.ivml.mint.harvesting.xml.schema.ListIdentifiersType;
import gr.ntua.ivml.mint.harvesting.xml.schema.ListMetadataFormatsType;
import gr.ntua.ivml.mint.harvesting.xml.schema.MetadataFormatType;
import gr.ntua.ivml.mint.harvesting.xml.schema.OAIPMHtype;
import gr.ntua.ivml.mint.persistent.DataUpload;
import gr.ntua.ivml.mint.persistent.User;

import java.io.ByteArrayInputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.UnsupportedEncodingException;
import java.util.ArrayList;
import java.util.Iterator;

import javax.xml.bind.JAXBContext;
import javax.xml.bind.JAXBElement;
import javax.xml.bind.JAXBException;
import javax.xml.bind.Unmarshaller;
import javax.xml.parsers.ParserConfigurationException;
import javax.xml.transform.TransformerException;

import org.w3c.dom.Node;
import org.w3c.dom.NodeList;
import org.xml.sax.SAXException;

import ORG.oclc.oai.harvester2.verb.GetRecord;
import ORG.oclc.oai.harvester2.verb.ListIdentifiers;
import ORG.oclc.oai.harvester2.verb.ListMetadataFormats;
import ORG.oclc.oai.harvester2.verb.ListRecords;
import de.schlichtherle.io.File;


public class Harvester implements Runnable{
	
	private String baseURL = null;
	private JAXBContext jc;
	private String resumptionToken = null;
	private ArrayList<String> identifiers = null;
	private User user = null;
	private DataUpload dataUpload;
	
	public Harvester(String url){
		this.baseURL = url;
		try {
			jc = JAXBContext.newInstance( "gr.ntua.ivml.mint.harvesting.xml.schema" );
		} catch (JAXBException e) {
			e.printStackTrace();
		}
	}
	
	public Harvester( DataUpload du ) {
		this.dataUpload = du;
	}
	
	public Harvester(String url, User user){
		this.baseURL = url;
		this.user = user;
		
		try {
			jc = JAXBContext.newInstance( "gr.ntua.ivml.mint.harvesting.xml.schema" );
		} catch (JAXBException e) {
			e.printStackTrace();
		}
	}
	
	public String getURL(){
		return this.baseURL;
	}
	
	public void run(){
		ListRecords records = null;
		ListMetadataFormatsType formats = this.getMatadataFormats(baseURL);
		MultipleRecordsZipImporter importer = new MultipleRecordsZipImporter();
		DataUpload upload = DataUpload.create(user, "", baseURL);
		upload.setOaiHarvest(true);
		upload.setStatus(1);
		boolean errorFound = false;
		if(formats != null){
			Iterator<MetadataFormatType> itr = formats.getMetadataFormat().iterator();
			MetadataFormatType type = null;
			while(itr.hasNext()){
				type = itr.next();
				try {
					records = new ListRecords(baseURL, null, null, null, type.getMetadataPrefix());
					
					while(records != null){
						NodeList errors = records.getErrors();
						if (errors != null && errors.getLength() > 0) {
			                String oaiError = "";
							System.out.println("Found errors");
			                int length = errors.getLength();
			                for (int i=0; i<length; ++i) {
			                    Node item = errors.item(i);
			                    System.out.println(item);
			                    oaiError += item;
			                }
			                System.out.println("Error record: " + records.toString());
			                upload.setMessage(oaiError);
			                upload.setStatus(-1);
			                errorFound = true;
			                DB.getDataUploadDAO().makePersistent(upload);
			                break;
			            }
						importer.saveRecords(this.getRecordsXML(records.toString()), type.getMetadataPrefix());
						upload.setOriginalFilename(importer.getFileName());
						
						resumptionToken = records.getResumptionToken();
			            if (resumptionToken == null || resumptionToken.length() == 0) {
			                records = null;
			            } else {
			                records = new ListRecords(baseURL, resumptionToken);
			                upload.setResumptionToken(resumptionToken);
			                upload.setNoOfFiles(importer.getFilecount());
			                DB.getDataUploadDAO().makePersistent(upload);
			            }
					}
				}catch (IOException e) {
					e.printStackTrace();
				} catch (ParserConfigurationException e) {
					e.printStackTrace();
				} catch (SAXException e) {
					e.printStackTrace();
				} catch (TransformerException e) {
					e.printStackTrace();
				} catch (NoSuchFieldException e) {
					e.printStackTrace();
				}
				
			}
		}else{
			System.out.println("Error while getting metadata formats from repository");
			upload.setMessage("Error while getting metadata formats from repository");
			upload.setStatus(-1);
			errorFound = true;
			DB.getDataUploadDAO().makePersistent(upload);
		}
		if(errorFound == false){
			importer.compress();
			upload.setStatus(2);
			DB.getDataUploadDAO().makePersistent(upload);	
			java.io.File tmpFile = new java.io.File(importer.getFileName());
			try {
				upload.upload(tmpFile);
			} catch (Exception e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
			upload.setStatus(0);
			DB.getDataUploadDAO().makePersistent(upload);
			tmpFile.delete();
		}
	}
	
	public File harvests() {
		run();
		// return tmpFile;
		return null;
	}
	
	@SuppressWarnings("unchecked")
	private ListMetadataFormatsType getMatadataFormats(String baseURL){
		ListMetadataFormats formats = null;
		ListMetadataFormatsType result = null;
		try {
			formats = new ListMetadataFormats(baseURL);
			Unmarshaller u = jc.createUnmarshaller();
			String xmlRec = formats.toString();
			if(xmlRec.startsWith("<?")){
				int offset = xmlRec.indexOf("?>");
				xmlRec = xmlRec.substring(offset+2);
			}
			InputStream is = new ByteArrayInputStream(xmlRec.getBytes("UTF-8"));
			JAXBElement<OAIPMHtype> oai = (JAXBElement<OAIPMHtype>)u.unmarshal(is);
			OAIPMHtype response = oai.getValue();
			result = response.getListMetadataFormats();
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
		return result;
	}
	
	@SuppressWarnings({ "unchecked", "unused" })
	private ListMetadataFormatsType getMetadataFormats(String identifier, String baseURL){
		ListMetadataFormats formats = null;
		ListMetadataFormatsType result = null;
		try {
			formats = new ListMetadataFormats(baseURL, identifier);
			Unmarshaller u = jc.createUnmarshaller();
			String xmlRec = formats.toString();
			if(xmlRec.startsWith("<?")){
				int offset = xmlRec.indexOf("?>");
				xmlRec = xmlRec.substring(offset+2);
			}
			InputStream is = new ByteArrayInputStream(xmlRec.getBytes("UTF-8"));
			JAXBElement<OAIPMHtype> oai = (JAXBElement<OAIPMHtype>)u.unmarshal(is);
			OAIPMHtype response = oai.getValue();
			result = response.getListMetadataFormats();
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
		return result;
	}
	
	@SuppressWarnings({ "unchecked" })
	private OAIPMHtype getRecordsXML(String response){
		OAIPMHtype result = null;
		Unmarshaller u;
		try {
			u = jc.createUnmarshaller();
			String xmlRec = response.toString();
			if(xmlRec.startsWith("<?")){
				int offset = xmlRec.indexOf("?>");
				xmlRec = xmlRec.substring(offset+2);
			}
			InputStream is = new ByteArrayInputStream(xmlRec.getBytes("UTF-8"));
			JAXBElement<OAIPMHtype> oai = (JAXBElement<OAIPMHtype>)u.unmarshal(is);
			result = oai.getValue();
		} catch (JAXBException e) {
			e.printStackTrace();
		} catch (UnsupportedEncodingException e) {
			e.printStackTrace();
		}		
		return result;
	}
	
	@SuppressWarnings({ "unchecked", "unused" })
	private OAIPMHtype getRecordData(String baseURL, String identifier, String format){
		OAIPMHtype result = null;
		try {
			GetRecord record = new GetRecord(baseURL, identifier, format);
			Unmarshaller u = jc.createUnmarshaller();
			String xmlRec = record.toString();
			if(xmlRec.startsWith("<?")){
				int offset = xmlRec.indexOf("?>");
				xmlRec = xmlRec.substring(offset+2);
			}
			InputStream is = new ByteArrayInputStream(xmlRec.getBytes("UTF-8"));
			JAXBElement<OAIPMHtype> oai = (JAXBElement<OAIPMHtype>)u.unmarshal(is);
			result = oai.getValue();
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
		
		return result;
	}
	
	@SuppressWarnings({ "unchecked", "unused" })
	private ArrayList<String> extractIdentifiers(ListIdentifiers idents){
		ArrayList<String> results = new ArrayList<String>();
		try {
			Unmarshaller u = jc.createUnmarshaller();
			String xmlRec = idents.toString();
			if(xmlRec.startsWith("<?")){
				int offset = xmlRec.indexOf("?>");
				xmlRec = xmlRec.substring(offset+2);
			}
			InputStream is = new ByteArrayInputStream(xmlRec.getBytes("UTF-8"));
			JAXBElement<OAIPMHtype> oai = (JAXBElement<OAIPMHtype>)u.unmarshal(is);
			OAIPMHtype response = oai.getValue();
			ListIdentifiersType identifiers = response.getListIdentifiers();
			Iterator<HeaderType> itr = identifiers.getHeader().iterator();
			HeaderType recType = null;
			int count = 0;
			while(itr.hasNext()){
				recType = itr.next();
				results.add(recType.getIdentifier());
			}
		} catch (JAXBException e) {
			e.printStackTrace();
		}catch (UnsupportedEncodingException e) {
			e.printStackTrace();
		}
		return results;
	}
}

