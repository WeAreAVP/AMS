package gr.ntua.ivml.mint.harvesting.io;
import java.io.File;
import java.util.Iterator;

import gr.ntua.ivml.mint.harvesting.xml.schema.GetRecordType;
import gr.ntua.ivml.mint.harvesting.xml.schema.OAIPMHtype;
import gr.ntua.ivml.mint.harvesting.xml.schema.ObjectFactory;
import gr.ntua.ivml.mint.harvesting.xml.schema.RecordType;

import javax.xml.bind.JAXBContext;
import javax.xml.bind.JAXBException;
import javax.xml.bind.Marshaller;

public class MultipleRecordsFileImporter {
	private String baseDir;
	private String providerName;
	private JAXBContext jc;
	private Marshaller m;
	private ObjectFactory fact;
	
	public MultipleRecordsFileImporter(){
		this.baseDir = "c:\\oai\\";
		
		try {
			this.jc = JAXBContext.newInstance( "gr.ntua.ivml.mint.harvesting.xml.schema" );
			fact = new ObjectFactory();
		} catch (JAXBException e) {
			e.printStackTrace();
		}
	}
	
	public void saveRecords(OAIPMHtype response, String format){
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
			Marshaller m;
			try {
				m = jc.createMarshaller();
				m.setProperty(Marshaller.JAXB_FORMATTED_OUTPUT,
						  Boolean.TRUE);
				new File(this.baseDir+this.providerName+"\\"+format).mkdirs();
				get = fact.createGetRecordType();
				oai = fact.createOAIPMHtype();
				get.setRecord(tmpRecord);
				oai.setGetRecord(get);
				m.marshal(fact.createOAIPMH(oai), new File(this.baseDir+this.providerName+"\\"+format+"\\"+itemName+".xml"));
			} catch (JAXBException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
			
		}
	}
}
