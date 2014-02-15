package gr.ntua.ivml.mint.xml.transform;

import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.io.StringReader;
import java.io.StringWriter;
import java.io.Writer;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.parsers.ParserConfigurationException;

import org.apache.log4j.Logger;
import org.apache.xml.serialize.OutputFormat;
import org.apache.xml.serialize.XMLSerializer;
import org.w3c.dom.Document;
import org.xml.sax.InputSource;
import org.xml.sax.SAXException;
import org.xml.sax.XMLReader;

public class XMLFormatter {
	
		public static final Logger log = Logger.getLogger( XMLFormatter.class );
		
	    public XMLFormatter() {
	    }

	    static public String format(String xml) {
	        try {
	            final Document document = parseXML(xml);

	            OutputFormat format = new OutputFormat(document);
	            format.setLineWidth(65);
	            format.setIndenting(true);
	            format.setIndent(2);
	            Writer out = new StringWriter();
	            XMLSerializer serializer = new XMLSerializer(out, format);
	            serializer.serialize(document);

	            return out.toString();
	        } catch (IOException e) {
	            throw new RuntimeException(e);
	        }
	    }
	    
	    static public String formatIfPossible(String xml) {
	        try {
	            final Document document = parseXML(xml);

	            OutputFormat format = new OutputFormat(document);
	            format.setLineWidth(65);
	            format.setIndenting(true);
	            format.setIndent(2);
	            Writer out = new StringWriter();
	            XMLSerializer serializer = new XMLSerializer(out, format);
	            serializer.serialize(document);

	            return out.toString();
	        } catch (IOException e) {
	        		log.equals(e.getMessage());
	        }
	        
	        return xml;
	    }

	    static private Document parseXML(String in) {
	        try {
	            DocumentBuilderFactory dbf = DocumentBuilderFactory.newInstance();
	            DocumentBuilder db = dbf.newDocumentBuilder();
	            InputSource is = new InputSource(new StringReader(in));
	            return db.parse(is);
	        } catch (ParserConfigurationException e) {
	            throw new RuntimeException(e);
	        } catch (SAXException e) {
	            throw new RuntimeException(e);
	        } catch (IOException e) {
	            throw new RuntimeException(e);
	        }
	    }
	    
	    /**
	     * Format the stream that passes through.
	     * @param is
	     * @param os
	     */
	    static public void format( InputStream is, OutputStream os ) {
	    	try {
            OutputFormat format = new OutputFormat();
            format.setLineWidth(65);
            format.setIndenting(true);
            format.setIndent(2);
            
            XMLSerializer serializer = new XMLSerializer(os, format);
            XMLReader parser = org.xml.sax.helpers.XMLReaderFactory.createXMLReader(); 
			parser.setFeature("http://apache.org/xml/features/nonvalidating/load-external-dtd", false);
			parser.setContentHandler(serializer.asContentHandler());
			InputSource ins = new InputSource();
			ins.setByteStream(is);
			parser.parse(ins);
	    	} catch( Exception e ) {
	    		log.error( "Formatting Exception:", e);
	    	}
	    }
}