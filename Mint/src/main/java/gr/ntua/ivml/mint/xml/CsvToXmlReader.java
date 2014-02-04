package gr.ntua.ivml.mint.xml;

import gr.ntua.ivml.mint.db.AsyncNodeStore;
import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.persistent.DataUpload;
import gr.ntua.ivml.mint.persistent.DataUpload.EntryProcessor;
import gr.ntua.ivml.mint.persistent.ReportI;
import gr.ntua.ivml.mint.persistent.XMLNode;
import gr.ntua.ivml.mint.persistent.XmlObject;
import gr.ntua.ivml.mint.persistent.XpathHolder;
import gr.ntua.ivml.mint.util.CSVParser;

import java.io.BufferedReader;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.nio.charset.Charset;
import java.sql.Connection;

import org.apache.commons.lang.StringEscapeUtils;
import org.apache.log4j.Logger;
import org.xml.sax.InputSource;
import org.xml.sax.SAXException;

/**
 * Reads the Csv Stream and prepares XMLNodes for storing.
 * @author Arne Stabenau 
 *
 */
public class CsvToXmlReader {
	private static final Logger log = Logger.getLogger( CsvToXmlReader.class );
	Connection c;
	
	boolean hasHeader;
	char delimiter, escChar;
	DataUpload du;
	XmlObject xml;
	XpathHolder root;
	AsyncNodeStore ans;
	private long currentNodeNumber=-1l;
	private long[] nodeNumbers;
	private long nodeCount = 0l;
	private int itemCount=0;
	
	/**
	 * The quote is fixed to '"'. Delimiter is free, so is escChar
	 * @param hasHeader
	 * @param delimiter
	 * @param escChar
	 * @throws Exception
	 */
	public CsvToXmlReader( DataUpload du, boolean hasHeader, String delimiter, String escChar ) throws Exception {
		this.du = du;
		this.hasHeader = hasHeader;
		this.delimiter = (delimiter!=null)?delimiter.charAt(0):'\0';
		this.escChar = (escChar!=null)?escChar.charAt(0):'\0';
	}
	
	
	public void parse() throws Exception {
	
		c = DB.getStatelessSession().connection();
		try {
		root = new XpathHolder();
		root.name = "";
		root.parent = null;
		root.xpath = "";

		xml = new XmlObject();
		root.xmlObject = xml;
		DB.getXmlObjectDAO().makePersistent(xml);
		ans = new AsyncNodeStore( xml );

		EntryProcessor ep = new EntryProcessor( ) {
			public void  processEntry(de.schlichtherle.util.zip.ZipEntry ze, InputStream is) throws Exception {
				if( ze.isDirectory()) return;
				String entryName = ze.getName();
				if( !( entryName.endsWith(".csv") || entryName.endsWith(".txt"))) return;
				// makes this process interruptible
				Thread.sleep(0);
				InputSource ins = new InputSource();
				ins.setByteStream(is);
				CSVParser parser = new CSVParser( delimiter, '\"', escChar);
				BufferedReader br = new BufferedReader( new InputStreamReader( is, "UTF8" ));
				parseEntry( parser, br );
			}
		};
		du.processAllEntries(ep);
		DB.commit();
		DB.getSession().clear();
		ans.finish();
		DB.getSession().refresh(du);
		du.setNodeCount(nodeCount);	
		du.setXmlObject(xml);
		du.setMessage("Uploaded " + itemCount + " items." );
		du.setStatus(DataUpload.OK);
		du.setItemXpath(xml.getByPathWithPrefix("/items/item", true));
		DB.getDataUploadDAO().makePersistent(du);
		} catch( Exception e ) {
			if( du.getStatus() != DataUpload.ERROR ) {
				du.setStatus(DataUpload.ERROR);
				du.setMessage( e.getMessage() );
				DB.commit();
			}
			log.error( "Problem during csv parsing", e );
			ans.abort();
			// rollback somehow .... lots of commits already in ..
			DB.getXmlObjectDAO().makeTransient(xml);
			DB.getSession().clear();
			throw e;
		}
		DB.commit();
	}
	
	
	/**
	 * Does the Job
	 * Pseudo xml is <records> <items> and then either
	 * <field_1> <field_2> ...
	 * or from the headers 
	 * <header1> <header2>
	 */
	private void parseEntry( CSVParser parser, BufferedReader reader ) throws Exception {
		XMLNode records = null;
		
		String[] header = null;
		if( hasHeader ) {
			header = readNext( parser, reader );
			if(( header == null ) || ( header.length == 0 )) throw new Exception( "No header found" );
		}
		
		String[] tokens = readNext( parser, reader );
		if(( tokens != null ) && ( tokens.length != 0 )) {
			records = new XMLNode(newNodeId());
			records.nodeType = XMLNode.ELEMENT;
			records.size = 1;
			records.setXpathHolder(root.getByNameUri("items", "" ));
			records.parentNodeId = 0l;
			records.xmlObject = xml;
		}
		
		while( tokens != null ) {
			if(( header != null ) && (tokens.length != header.length)) {
				throw new Exception( "Header and row have different length" ); 
			}
			XMLNode item = newChild( records, "item", null );
			
			for( int i=0; i<tokens.length; i++ ) {
				String tagname = "Field_"+(i+1);
				if( header != null ) {
					tagname = escTagname( header[i]);
				}
				if(( tokens[i] != null ) && (tokens[i].length()>0)) {
					XMLNode field = newChild( item, tagname, null );
					XMLNode text = newChild( field, "text()", tokens[i]);
					field.size = 2;
					item.size+=2;
					store( text );
					store( field );
				}
			}
			store( item );
			itemCount++;
			records.size += item.size;
			tokens = readNext( parser, reader );
		}
		store( records );
	}
		
	/**
	 * Simplified node creation for this special case.
	 */
	private XMLNode newChild( XMLNode parent, String tagname, String content ) throws Exception {
		XMLNode result = new XMLNode( newNodeId());
		if( content == null ) 
			result.nodeType = XMLNode.ELEMENT;
		else {
			result.nodeType = XMLNode.TEXT;
			result.content = content;
		}
		result.setXmlObject(parent.getXmlObject());
		result.setXpathHolder(parent.getXpathHolder().getByNameUri(tagname, ""));
		result.size = 1;
		result.parentNodeId = parent.getNodeId();
		return result;
	}

	
	
    /**
     * Reads the next line from the buffer and converts to a string array.
     * Allow for empty lines.
     * Want to allow for comment lines as well...
     * 
     * @return a string array with each comma-separated element as a separate
     *         entry.
     * 
     * @throws Exception
     *             if bad things happen during the read
     */
    private String[] readNext( CSVParser parser, BufferedReader reader ) throws Exception {
    	
    	String[] result = null;
    	do {
    		
    		String nextLine;
    		// skip empty lines if they are there
    		do {
    			nextLine = reader.readLine();
    			if( nextLine == null ) break;
    			if( parser.isPending() ) break;    			
    		} while( nextLine.trim().length() == 0 );
    		
    		if( nextLine == null ) {
    			if( parser.isPending()) throw new Exception( "Quotes not matching, missing input!");
    			else return null;
    		}
    		// skip empty lines if we are not pending
    		
    		String[] r = parser.parseLineMulti(nextLine);
    		if (r.length > 0) {
    			if (result == null) {
    				result = r;
    			} else {
    				String[] t = new String[result.length+r.length];
    				System.arraycopy(result, 0, t, 0, result.length);
    				System.arraycopy(r, 0, t, result.length, r.length);
    				result = t;
    			}
    		}
    	} while (parser.isPending());
    	return result;
    }

    /**
     * Get ids from the db and give them out on request.
     * @return
     * @throws SAXException
     */
	private long newNodeId() throws SAXException  {
		if(( currentNodeNumber == -1l) || ( currentNodeNumber == nodeNumbers[1])) {
			// need to get new nodenumbers
			nodeNumbers = AsyncNodeStore.getIds(c);
			if( nodeNumbers[0] < 0 ) {
				throw new SAXException( "Couldnt aquire node ids from DB");
			} else {
				currentNodeNumber = nodeNumbers[0];
			}
		} else {
			currentNodeNumber++;
		}
		return currentNodeNumber;
	}	

	private void store( XMLNode n ) throws Exception {
		if( n.getXpathHolder() != null ) {
			if( !DB.getSession().contains(n.getXpathHolder())) {
				DB.getSession().save( n.getXpathHolder());
				// commit not needed to get dbID of pathHolder
				// DB.commit();
			}
			if( n.getXpathHolder().getDbID() == null ) 
				log.warn( "XpathHolder with no id!!");
		} else {
			log.warn( "No xpath Holder!!!");
		}
		// store the node asynchronous from reading, multithreading ...
		ans.store(n);
		nodeCount++;
	}
	
	private String escTagname( String name ) {
		StringBuilder sb = new StringBuilder();
		
		for( int i=0; i<name.length(); i++ ) {
			boolean append = false;
			char current = name.charAt(i);
			if( Character.isLetter( current )) append = true;
			else if( i>0 ) {
				append = Character.isDigit(current) ||
					( current == '-' ) || 
					( current == '.' ) ||
					( current == '_' );
			}
			if( append ) sb.append( current );
			else sb.append( "_" );
		}
		
		return sb.toString();
	}
}
