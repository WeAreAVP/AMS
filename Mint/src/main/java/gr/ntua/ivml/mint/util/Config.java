package gr.ntua.ivml.mint.util;

import java.io.InputStream;
import java.util.Properties;

import javax.servlet.ServletContext;

import org.apache.log4j.Logger;

/**
 * Class to read and reread a property file. Is static unsynced and stupid, 
 * but easy to use.
 * 
 * @author Arne Stabenau
 *
 */
public class Config {
	public static Properties properties = new Properties( System.getProperties());
	public static Properties custom = new Properties( properties );
	private static long lastRead;
	private static final long UPDATE_INTERVAL = 2000l;
	private static final String PROPS = "mint.properties";
	private static final String CUSTOM = "custom.properties";
	public static final Logger log = Logger.getLogger( Config.class );
	public static ServletContext context;

	public static String get(String key) {
		return Config.getWithDefault(key, null);
	}

	public static String getWithDefault( String key, String defaultValue ) {
		checkAndRead();
		
		String result = defaultValue;
		
		if(custom.containsKey(key)) {
			result = custom.getProperty(key);
		} else if(properties.containsKey(key)) {			
			result = properties.getProperty(key);
		}
		
		return result;
	}
	
	public static boolean debugEnabled()
	{
		return Config.getBoolean("debug");
	}
	
	public static boolean getBoolean( String key ) {
		String result = Config.get(key);
		
		if(result != null) {
			if(result.equalsIgnoreCase("true") || result.equalsIgnoreCase("yes") || result.equalsIgnoreCase("1")) {
				return true;
			}
		}
		
		return false;
	}
	
	public static boolean has( String key ) {
		checkAndRead();
		return custom.containsKey(key) || properties.containsKey(key);
	}
	
	public static String get( String key, String defaultValue ) {
		checkAndRead();
		return properties.getProperty( key, defaultValue );
	}
	
	private static void checkAndRead() {
		if( lastRead==0l) readProps();
		else if(( System.currentTimeMillis() - lastRead ) > UPDATE_INTERVAL )
			readProps();
	}
	
	private static void readProps() {
	    try {
	    	InputStream inputStream = Config.class.getClassLoader().getResourceAsStream(PROPS);
	        properties.load(inputStream);
	        inputStream = Config.class.getClassLoader().getResourceAsStream(CUSTOM);
	        if( inputStream != null )
	        	custom.load(inputStream);
	        lastRead = System.currentTimeMillis();
	    } catch( Exception e) {
	    	log.error( "Can't read properties", e );
	    	throw new Error( "Configuration file " + PROPS + " not found in CLASSPATH", e);
	    }
	}
	
	public static void setContext( ServletContext sc ) {
		context = sc;
	}
	
	public static ServletContext getContext( ) {
		return context;
	}

	public static String getRealPath( String path  ) {
		if( context == null ) {
			log.warn("Calling getRealPath( path )  with no context set.");
			return path;
		}
		return context.getRealPath( path );
	}

	public static String getSchemaPath(String xsd) {
		return context.getRealPath(Config.getWithDefault("schemaDir", "schemas") + System.getProperty("file.separator") + xsd);
	}
	
	public static String getXSLPath(String xsl) {
		return context.getRealPath(Config.getWithDefault("xslDir", "xsl") + System.getProperty("file.separator") + xsl);
	}

	public static String getScriptPath(String script) {
		return context.getRealPath(Config.getWithDefault("scriptDir", "scripts") + System.getProperty("file.separator") + script);
	}
}
 