package gr.ntua.ivml.mint.rdf.edm;

import java.io.ByteArrayInputStream;
import java.io.ByteArrayOutputStream;
import java.io.InputStream;

import gr.ntua.ivml.mint.xml.transform.ItemTransform;

public class EDM2RDFTransform implements ItemTransform {

	@Override
	public String transform(String input) {
		String edmxml = input;
		String rdf = null;
		
		try {
			byte[] bytes = edmxml.getBytes();
			InputStream inputStream = new ByteArrayInputStream(bytes); 
			gr.ntua.ivml.mint.rdf.edm.EDM2RDF xml2rdf = new gr.ntua.ivml.mint.rdf.edm.EDM2RDF(inputStream);
			ByteArrayOutputStream outputStream = xml2rdf.convertToRDF();
			String edmrdf = outputStream.toString();
			rdf = edmrdf;
		} catch(Exception e) {
			rdf = e.getMessage();
		}
		
		return rdf;
	}

}
