package gr.ntua.ivml.mint.xsd;

import gr.ntua.ivml.mint.persistent.XmlSchema;
import gr.ntua.ivml.mint.util.Config;

import java.io.File;
import java.io.FileInputStream;
import java.io.IOException;
import java.util.HashMap;

import javax.xml.transform.Source;
import javax.xml.transform.stream.StreamSource;
import javax.xml.validation.Schema;
import javax.xml.validation.SchemaFactory;
//import javax.xml.validation.SchemaFactory;
import javax.xml.validation.Validator;

import org.apache.log4j.Logger;
import org.xml.sax.ErrorHandler;
import org.xml.sax.SAXException;
import org.xml.sax.SAXNotRecognizedException;
import org.xml.sax.SAXNotSupportedException;

public class SchemaValidator {	
	public static final Logger log = Logger.getLogger( SchemaValidator.class );
	private static SchemaFactory factory;
	private static HashMap<String, Schema> schemaCache = new HashMap<String, Schema>();
	
	static{
//		factory = SchemaFactory.newInstance("http://www.w3.org/2001/XMLSchema");
		factory = org.apache.xerces.jaxp.validation.XMLSchemaFactory.newInstance("http://www.w3.org/2001/XMLSchema");
		try {
			factory.setFeature("http://apache.org/xml/features/validation/schema-full-checking", false);
		} catch (Exception e) {
			e.printStackTrace();
		}
	}
	
	public static void main(String[] args) {
		try {
			File input = new File(args[0]);
			File xsd = new File(args[1]);
			
			SchemaValidator.validate(input, xsd);
		} catch(Exception e) {
			e.printStackTrace();
		}
	}
	
	public static void validate(File input, File xsd) throws SAXException, IOException {
		validate(input, xsd, null);
	}
	
	public static void validate(File input, File xsd, ErrorHandler handler) throws SAXException, IOException {
		StreamSource source = new StreamSource(new FileInputStream(input));
		SchemaValidator.validate(source, xsd, handler);
	}

	public static void validate(Source source, XmlSchema schema) throws SAXException, IOException {
		validate(source, schema, null);
	}
	
	public static void validate(Source source, XmlSchema schema, ErrorHandler handler) throws SAXException, IOException {
		String xsd = Config.getSchemaPath(schema.getXsd());
		SchemaValidator.validate(source, xsd, handler);
	}
		
	public static void validate(Source source, File schemaFile) throws SAXException, IOException {
		validate(source, schemaFile, null);
	}
	
	public static void validate(Source source, File schemaFile, ErrorHandler handler) throws SAXException, IOException {
		String schemaPath = schemaFile.getAbsolutePath();
		validate(source, schemaPath, handler);
	}
	
	public static void validate(Source source, String schemaPath) throws SAXException, IOException {
		validate(source, schemaPath, null);
	}
	
	public static void validate(Source source, String schemaPath, ErrorHandler handler) throws SAXException, IOException {
			Schema schema = getSchema(schemaPath);
			Validator validator = schema.newValidator();
			if(handler != null) {
				validator.setErrorHandler(handler);
			}
			validator.validate(source);
	}	
	
	public static synchronized Schema getSchema( XmlSchema schema ) throws SAXException  {
		String xsd = Config.getSchemaPath(schema.getXsd());
		return getSchema( xsd );
	}
	
	// factor newSchema is not thread safe
	public static synchronized Schema getSchema( String schemaPath ) throws SAXException  {
		Schema schema = schemaCache.get(schemaPath);
		
		if(schema == null) {
			schema = factory.newSchema(new File(schemaPath));
			schemaCache.put(schemaPath, schema);
		}

		return schema;
	}
}
