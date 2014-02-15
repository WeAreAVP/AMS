package gr.ntua.ivml.mint.util;

import gr.ntua.ivml.mint.db.AsyncNodeStore;
import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.db.GlobalPrefixStore;
import gr.ntua.ivml.mint.harvesting.util.XMLDbHandler;
import gr.ntua.ivml.mint.persistent.DataUpload;
import gr.ntua.ivml.mint.persistent.XMLNode;
import gr.ntua.ivml.mint.persistent.XmlObject;
import gr.ntua.ivml.mint.persistent.XpathHolder;
import gr.ntua.ivml.mint.persistent.DataUpload.EntryProcessor;

import java.io.InputStream;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.util.zip.ZipEntry;

import org.apache.log4j.Logger;
import org.hibernate.StatelessSession;
import org.xml.sax.InputSource;
import org.xml.sax.XMLReader;

public class NodeReader implements NodeStoreI {
	DataUpload du;
	Connection c;
	long lastReport;
	long lastNodeCount;
	long nodeCount;
	long entryCount;
	int tmpUploadId; 
	String entryName;
	AsyncNodeStore ans;
	XMLReader parser;
	StatelessSession s;
	
	
	public  final Logger log = Logger.getLogger( NodeReader.class );
	public NodeReader( DataUpload du ) {
		this.du = du;

		lastReport = System.currentTimeMillis();
		nodeCount = 0;
		entryCount = 0;
	}
	
	public void readNodes() throws Exception {
		this.s = DB.getStatelessSession();
		this.c = s.connection();
		try {
			parser = org.xml.sax.helpers.XMLReaderFactory.createXMLReader(); 
			parser.setFeature("http://apache.org/xml/features/nonvalidating/load-external-dtd", false);
			XMLDbHandler handler = new XMLDbHandler( this );
			XpathHolder root = new XpathHolder();
			root.name = "";
			root.parent = null;
			root.xpath = "";
			handler.setRoot(root);
			parser.setContentHandler(handler);
			XmlObject xml = new XmlObject();
			root.xmlObject = xml;
			// TODO: This will create orphan XML objects on failed uploads!!
			DB.getXmlObjectDAO().makePersistent(xml);
			ans = new AsyncNodeStore( xml );
			EntryProcessor ep = new EntryProcessor( ) {
				public void  processEntry(de.schlichtherle.util.zip.ZipEntry ze, InputStream is) throws Exception {
					if( ze.isDirectory()) return;
					entryName = ze.getName();
					if( !entryName.endsWith(".xml") &&  !entryName.endsWith(".XML")) return;
					// makes this process interruptible
					Thread.sleep(0);
					InputSource ins = new InputSource();
					ins.setByteStream(is);
					nextEntry();
					parser.parse( ins );
				}
			};
			du.processAllEntries(ep);
			DB.commit();
			DB.getSession().clear();
			ans.finish();
			DB.getSession().refresh(du);
			du.setNodeCount(nodeCount);	
			du.setXmlObject(xml);
			DB.getDataUploadDAO().makePersistent(du);
			DB.commit();
		} catch( Exception e ) {
			log.error( "Parsing / storing of DataUpload failed. ", e );
			if( du.getStatus() != DataUpload.ERROR) {
				du.setMessage( "Node Reader failed with: " + e.getMessage()+"\n" );
				du.setStatus(DataUpload.ERROR);
			}
			DB.commit();
			// TODO: Safe to delete the XML object here ??
			DB.getSession().clear();
			ans.abort();
			throw e;
		}
	}
	
	public void nextEntry() {
		entryCount++;
	}
	
	public void store( XMLNode n ) throws Exception {
		long currentTime = System.currentTimeMillis();
		if(( currentTime - lastReport ) > 20000 ) {
			int nodeRate = (int) ((nodeCount-lastNodeCount)*1000/(currentTime - lastReport));
			du.setNodeCount(nodeCount);
			StringBuffer msg = new StringBuffer();
			if( entryCount>1 ) msg.append(" Files: " + entryCount );
			if( nodeCount>1 ) msg.append( " Nodes: "+ nodeCount );
			msg.append( " Rate: "+nodeRate+ " nodes/sec" );
			du.setMessage(msg.toString());
			log.info( du.getOriginalFilename() + " " + msg );
			DB.getDataUploadDAO().makePersistent(du);
			DB.commit();
			lastReport = currentTime;
			lastNodeCount = nodeCount;
		}
		if( n.getXpathHolder() != null ) {
			if( !DB.getSession().contains(n.getXpathHolder())) {
				DB.getSession().save( n.getXpathHolder());
				// commit not needed to get dbID of pathHolder
				// DB.commit();
			}
			// this updates the global prefix store
			if( !StringUtils.empty( n.getXpathHolder().getUri()))
				GlobalPrefixStore.createPrefix(n.getXpathHolder().getUri(), n.getXpathHolder().getUriPrefix());
		}
		// store the node asynchronous from reading, multithreading ...
		ans.store(n);
		nodeCount++;
	}
	
	/**
	 * Allocating node ids in packs of 1000. The sequence will support this.
	 * Whoever has x000 can use ids x000 until x999.
	 * @return
	 */
	public long[] newIds() {
		return AsyncNodeStore.getIds(c);
	}
	
}
