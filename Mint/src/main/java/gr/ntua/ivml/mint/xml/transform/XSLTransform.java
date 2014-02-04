package gr.ntua.ivml.mint.xml.transform;

import javax.xml.parsers.*;
import javax.xml.transform.*;
import javax.xml.transform.dom.DOMSource;

import javax.xml.transform.stream.*;

import java.io.*;


import net.sf.saxon.FeatureKeys;


import org.xml.sax.*;

import org.w3c.dom.*;

public class XSLTransform implements ItemTransform {
	String xsl = null;
	
	public void setXSL(String xsl) {
		this.xsl = xsl;
	}
	
	public String getXSL() {
		return this.xsl;
	}
	
	public String transform(String xml, String xsl) throws TransformerException {
		String result = "";
		System.setProperty("javax.xml.parsers.SAXParserFactory", "org.apache.xerces.jaxp.SAXParserFactoryImpl");
		System.setProperty("javax.xml.transform.TransformerFactory", "net.sf.saxon.TransformerFactoryImpl");
	
	    StringWriter out = new StringWriter();

	    TransformerFactory tFactory = TransformerFactory.newInstance();
	   
	    tFactory.setAttribute( FeatureKeys.DTD_VALIDATION, false );
	    
	    StreamSource xmlSource = new StreamSource(new StringReader(xml));
	    StreamSource xslSource = new StreamSource(new StringReader(xsl));
	    StreamResult xmlResult = new StreamResult(out);
	    
	    tFactory.setURIResolver(new XSLURIResolver());
	    Transformer transformer = tFactory.newTransformer(xslSource);
	    transformer.transform(xmlSource, xmlResult);
	    result = out.toString();
		
		return result;
	}

	/**
	 * Alternative method of transformation. Needed for big files! Don't want to have them
	 * in Strings. 
	 * @param xml
	 * @param xsl
	 * @param out
	 * @throws TransformerException
	 */
	public void transformStream(InputStream xml, String xsl,OutputStream out) throws TransformerException {

		TransformerFactory tFactory = TransformerFactory.newInstance();
	    StreamSource xmlSource = new StreamSource(xml);
	    StreamSource xslSource = new StreamSource(new StringReader(xsl));
	    StreamResult xmlResult = new StreamResult(out);
	    
	    Transformer transformer = tFactory.newTransformer(xslSource);
	    transformer.transform(xmlSource, xmlResult);
	}
	
	//using DOM, disabling validation
	
	public void transform(InputStream xml, String xsl,OutputStream out ) throws Exception {
		System.setProperty("javax.xml.parsers.SAXParserFactory", "org.apache.xerces.jaxp.SAXParserFactoryImpl");
		System.setProperty("javax.xml.transform.TransformerFactory", "net.sf.saxon.TransformerFactoryImpl");
		
		DocumentBuilderFactory factory = DocumentBuilderFactory.newInstance();

        factory.setAttribute("http://xml.org/sax/features/namespaces", true);
        factory.setAttribute("http://xml.org/sax/features/validation", false);
        factory.setAttribute("http://apache.org/xml/features/nonvalidating/load-dtd-grammar", false);
        factory.setAttribute("http://apache.org/xml/features/nonvalidating/load-external-dtd", false);

        factory.setNamespaceAware(true);
        factory.setIgnoringElementContentWhitespace(false);
        factory.setIgnoringComments(false);
        factory.setValidating(false);
        DocumentBuilder builder = factory.newDocumentBuilder();
        Document document = builder.parse(new InputSource(xml));

        Source source = new DOMSource(document);

		TransformerFactory tFactory = TransformerFactory.newInstance();
        

	    StreamSource xslSource = new StreamSource(new StringReader(xsl));
	    StreamResult xmlResult = new StreamResult(out);
	
	    
	    Transformer transformer = tFactory.newTransformer(xslSource);
	    transformer.transform(source, xmlResult);
	}


	@Override
	public String transform(String input) {
		try {
			return transform(input, xsl);
		} catch (TransformerException e) {
			e.printStackTrace();
		}
		
		return null;
	}
	
	
	 
}
