package gr.ntua.ivml.mint.harvesting.io;

import gr.ntua.ivml.mint.harvesting.xml.schema.GetRecordType;
import gr.ntua.ivml.mint.harvesting.xml.schema.OAIPMHtype;
import gr.ntua.ivml.mint.harvesting.xml.schema.ObjectFactory;
import gr.ntua.ivml.mint.harvesting.xml.schema.RecordType;
import gr.ntua.ivml.mint.util.Config;

import java.io.ByteArrayOutputStream;
import java.io.FileNotFoundException;
import java.io.FilenameFilter;
import java.io.IOException;
import java.util.Iterator;


import de.schlichtherle.io.*;
import de.schlichtherle.io.File;
import de.schlichtherle.io.FileInputStream;
import de.schlichtherle.io.FileOutputStream;
import java.io.*;


import javax.xml.bind.JAXBContext;
import javax.xml.bind.JAXBException;
import javax.xml.bind.Marshaller;

import org.apache.log4j.Logger;

public class MultipleRecordsZipImporter {
	private String baseDir;
	private String providerName;
	private JAXBContext jc;
	private Marshaller m;
	private ObjectFactory fact;
	private int fileCounter;
	private int dirCounter;
	private File file;
	String zipFile;
	
	public static final Logger log = Logger.getLogger( Config.class );
	public MultipleRecordsZipImporter(){
		//this.baseDir = "c:\\oai\\";
		//log.error(Config.get("oaiIncomingDir"));
		this.baseDir = Config.get("oaiIncomingDir");
		//og.error(this.baseDir);
		this.fileCounter = 0;
		this.dirCounter = 1;
		try {
			this.jc = JAXBContext.newInstance( "gr.ntua.ivml.mint.harvesting.xml.schema" );
			fact = new ObjectFactory();
		} catch (JAXBException e) {
			e.printStackTrace();
		}
	}
	
	public void compress(){
		try {
			File.umount();
		} catch (ArchiveException e) {
			e.printStackTrace();
		}
	}
	
	private void zipXML(ByteArrayOutputStream stream, String fileName, String zipFile, String format){
		//System.out.println(stream.toByteArray().length + " " + fileName + " " + zipFile);
		file = new File(zipFile+"/"+format, new DefaultArchiveDetector( "zip" ));
		//File tmpFile = null;


		//File[] filez = (File[])file.listFiles(fileNameFilter);
		//boolean exists = false;
		//System.out.println("Dirs:" + filez[0].toString());
		//if(file.listFiles(fileNameFilter) != null){
			//this.dirCounter = ((File[])file.listFiles(fileNameFilter)).length;
			//System.out.println(this.dirCounter);
		//	exists = true;
		//}
		OutputStream out = null;
		try {
			//if(exists == true){
				//System.out.println(zipFile + "/" + format + "/" + this.dirCounter);
			//	tmpFile = new File(zipFile + "/" + format + "/" + this.dirCounter);
			//	File[] tmpFilez = (File[]) tmpFile.listFiles();
				//System.out.println("file count:" + tmpFilez.length);
			//	if(tmpFilez != null){
			//		if(tmpFilez.length >= this.fileCounter ){
			//			//File.umount();
			//			this.dirCounter++;
						//System.out.println("HaHA!");
			//		}
				//}
			//}
			//String name = zipFile + "/" + format + "/" + fileName;
			//System.out.println(name);
			//System.out.println(zipFile + "/" + format + "/" + this.dirCounter + "/" + fileName);
			out = new FileOutputStream(zipFile + "/" + format + "/"  + fileName);
			out.write(stream.toByteArray());
			out.close();
			this.fileCounter++;
			//file.update();
	
		} catch (FileNotFoundException e) {
			e.printStackTrace();
		} catch (IOException e) {
			e.printStackTrace();
		}
		
	}
	
	public String getFileName(){ return this.zipFile; }
	public int getFilecount(){ return this.fileCounter; }
	
	private void extractRecords(OAIPMHtype response, String format){
		Iterator<RecordType> itr = response.getListRecords().getRecord().iterator();
		RecordType tmpRecord = null;
		GetRecordType get = null;
		OAIPMHtype oai = null;
		
		
		while(itr.hasNext()){
			tmpRecord = itr.next();
			String ident = tmpRecord.getHeader().getIdentifier();
			String[] splits = ident.split(":");
			String itemName = splits[splits.length-1];
			itemName = itemName.replace('/', '.');
			providerName = splits[splits.length-2];
			String fileName = itemName + ".xml";
			Marshaller m;
			ByteArrayOutputStream stream = new ByteArrayOutputStream();
			this.zipFile = this.baseDir + providerName + ".zip";
			try {
				m = jc.createMarshaller();
				m.setProperty(Marshaller.JAXB_FORMATTED_OUTPUT,
						  Boolean.TRUE);
				get = fact.createGetRecordType();
				oai = fact.createOAIPMHtype();
				get.setRecord(tmpRecord);
				oai.setGetRecord(get);
				m.marshal(fact.createOAIPMH(oai), stream);
				//System.out.println(stream.toByteArray().length);
				this.zipXML(stream, fileName, zipFile, format);
			} catch (JAXBException e) {
				e.printStackTrace();
			}
			
		}
	}
	
	public void saveRecords(OAIPMHtype response, String format){
		this.extractRecords(response, format);
		/*Iterator<RecordType> itr = response.getListRecords().getRecord().iterator();
		RecordType tmpRecord = null;
		GetRecordType get = null;
		OAIPMHtype oai = null;
		//this.baseDir =  "c:\\oai\\" + providerName + ".zip";
		/*ZipOutputStream out = null;
		try {
			out = new ZipOutputStream(new FileOutputStream(this.baseDir));
		} catch (FileNotFoundException e1) {
			e1.printStackTrace();
		}
		while(itr.hasNext()){
			tmpRecord = itr.next();
			String ident = tmpRecord.getHeader().getIdentifier();
			String[] splits = ident.split(":");
			String itemName = splits[splits.length-1];
			itemName = itemName.replace('/', '.');
			providerName = splits[splits.length-2];
			this.baseDir =  "c:\\oai\\" + providerName + ".zip";
			Marshaller m;
			ZipOutputStream out = null;
			try {
				m = jc.createMarshaller();
				m.setProperty(Marshaller.JAXB_FORMATTED_OUTPUT,
						  Boolean.TRUE);
				//new File(this.baseDir+this.providerName+"\\"+format).mkdirs();
				
				out = new ZipOutputStream(new FileOutputStream(this.baseDir));
				get = fact.createGetRecordType();
				oai = fact.createOAIPMHtype();
				get.setRecord(tmpRecord);
				oai.setGetRecord(get);
				//m.marshal(fact.createOAIPMH(oai), new File(this.baseDir+this.providerName+"\\"+format+"\\"+itemName+".xml"));
				out.putNextEntry(new ZipEntry(itemName+".xml"));
				m.marshal(fact.createOAIPMH(oai), out);
				out.closeEntry();
				//out.close();
			} catch (JAXBException e) {
				e.printStackTrace();
			} catch (FileNotFoundException e) {
				e.printStackTrace();
			} catch (IOException e) {
				e.printStackTrace();
			}finally{
				try {
					out.close();
				} catch (IOException e) {
					e.printStackTrace();
				}
			}
			
		}*/
	}
}
