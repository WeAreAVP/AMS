package gr.ntua.ivml.mint.harvesting.io;

import java.io.File;

import javax.xml.bind.JAXBContext;
import javax.xml.bind.JAXBElement;
import javax.xml.bind.JAXBException;
import javax.xml.bind.Marshaller;

import gr.ntua.ivml.mint.harvesting.xml.schema.OAIPMHtype;
import gr.ntua.ivml.mint.harvesting.xml.schema.ObjectFactory;

public class FileImporter{

	private String baseDir;
	private String providerName;
	private JAXBContext jc;
	private Marshaller m;
	private ObjectFactory fact;
	public FileImporter(){
		this.baseDir = "c:\\oai\\";
		
		try {
			this.jc = JAXBContext.newInstance( "gr.ntua.ivml.mint.harvesting.xml.schema" );
			fact = new ObjectFactory();
		} catch (JAXBException e) {
			e.printStackTrace();
		}
	}
	
	public void save(OAIPMHtype item, String format) {
		try {
			String ident = item.getGetRecord().getRecord().getHeader().getIdentifier();
			String[] splits = ident.split(":");
			String itemName = splits[splits.length-1];
			providerName = splits[splits.length-2];
			System.out.println(this.baseDir+this.providerName+"\\"+format+"\\"+itemName+".xml");
			//m.marshal(item, new File(this.baseDir+this.providerName+"\\"+format+"\\"+itemName+".xml"));
			System.out.println(item.getRequest().getIdentifier());
			Marshaller m = jc.createMarshaller();
			m.setProperty(Marshaller.JAXB_FORMATTED_OUTPUT,
					  Boolean.TRUE);
			new File(this.baseDir+this.providerName+"\\"+format).mkdirs();
			
			// m.marshal(fact.createOAIPMH(item), new File(this.baseDir+this.providerName+"\\"+format+"\\"+itemName+".xml"));
		} catch (JAXBException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
	}

}
