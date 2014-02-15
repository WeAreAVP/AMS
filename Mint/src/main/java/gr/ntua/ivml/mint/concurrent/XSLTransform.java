package gr.ntua.ivml.mint.concurrent;

import gr.ntua.ivml.mint.db.AsyncNodeStore;
import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.db.GlobalPrefixStore;
import gr.ntua.ivml.mint.db.LockManager;
import gr.ntua.ivml.mint.harvesting.util.XMLDbHandler;
import gr.ntua.ivml.mint.persistent.DataUpload.EntryProcessor;
import gr.ntua.ivml.mint.persistent.Lock;
import gr.ntua.ivml.mint.persistent.ReportI;
import gr.ntua.ivml.mint.persistent.Transformation;
import gr.ntua.ivml.mint.persistent.XMLNode;
import gr.ntua.ivml.mint.persistent.XmlObject;
import gr.ntua.ivml.mint.persistent.XpathHolder;
import gr.ntua.ivml.mint.util.LimitedStringBuilder;
import gr.ntua.ivml.mint.util.NodeStoreI;
import gr.ntua.ivml.mint.util.StringUtils;
import gr.ntua.ivml.mint.xml.transform.XMLFormatter;
import gr.ntua.ivml.mint.xml.transform.XSLTGenerator;

import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.io.BufferedReader;
import java.io.PipedInputStream;
import java.io.PipedOutputStream;
import java.io.PrintWriter;
import java.io.StringWriter;
import java.sql.Connection;
import java.util.Date;
import java.util.List;

import javax.xml.validation.Schema;
import javax.xml.validation.ValidatorHandler;

import org.apache.log4j.Logger;
import org.hibernate.Session;
import org.hibernate.StatelessSession;
import org.xml.sax.ErrorHandler;
import org.xml.sax.InputSource;
import org.xml.sax.SAXException;
import org.xml.sax.SAXParseException;
import org.xml.sax.XMLReader;

import de.schlichtherle.util.zip.ZipEntry;

import java.io.DataOutputStream;
import java.net.URL;
import java.net.HttpURLConnection;
import javax.swing.JOptionPane;
public class XSLTransform implements Runnable, NodeStoreI, EntryProcessor, ReportI {
	public final Logger log = Logger.getLogger(XSLTransform.class );
	private static int sessionCounter = 0;

	private Transformation tr;
	private int uniqueSession = -1;
	private boolean csvImport = false;
	
	long nodeCount, lastNodeCount, lastReport;
	int  reportCounter;
	int fileCounter, fileCount;
	
	int entryCount;
	int errorCount;
	int validCount;
	boolean isValidEntry;
	
	AsyncNodeStore ans;
	Connection c;
	XMLReader parser;
	String xsl;

	gr.ntua.ivml.mint.xml.transform.XSLTransform t = new gr.ntua.ivml.mint.xml.transform.XSLTransform();			
	LimitedStringBuilder reportString;
	String currentEntry;
	
	public XSLTransform(){}
	
	public XSLTransform(Transformation tr){
		this.tr = tr;
	}



	/**
	 * lock upload and mapping of the transformation or fail if U can't acquire the locks.
	 */
	public void run() {
		
		log.info( "Offline transform started");
		// this might be a used session, the thread is reused
		Session s = DB.newSession();
		StatelessSession ss = DB.getStatelessSession();
		c = ss.connection();
		reportString = new LimitedStringBuilder(20000, " ..." );
		s.beginTransaction();
		try {
			tr = DB.getTransformationDAO().getById(tr.getDbID(), false);
			
			// new version of the transformation for this session
			if( tr == null ) {
				log.error( "Total desaster, Transformation unavailable, no reporting to UI!!!");
				return;
			}
			log.info( "Transforming " + tr.getDataUpload().getOriginalFilename() + " with " + tr.getMapping().getName());
			// get some locks
			if( ! aquireLocks()) {
				releaseLocks();
				tr.setStatusCode(Transformation.ERROR);
				tr.setStatusMessage("Couldn't aquire locks" );
				DB.commit();
				return;
			}
			checkCsv();
			transform();
			readNodes();
			AsyncNodeStore.index(tr.getParsedOutput(), this, DB.getStatelessSession().connection());
			String uID= tr.getUser().getDbID().toString();
			String tID= tr.getDataUpload().getDbID().toString();
			log.debug( "UserID " + uID);
			log.debug( "TranformedID " + tID);
			tr.setIsApproved(0);
			tr.setStatusCode(Transformation.OK);
			String urlParameters = uID+"/"+tID;
			String request = "https://amsqa.avpreserve.com/mint/save_transformed_info/"+urlParameters;
			URL url = new URL(request); 
			HttpURLConnection connection = (HttpURLConnection) url.openConnection();           
			connection.setDoOutput(true);
			connection.setReadTimeout(10000);
			connection.setRequestMethod("GET"); 
			connection.setRequestProperty("charset", "utf-8");
			connection.setUseCaches (false);
			connection.connect();
			
			BufferedReader rd  = new BufferedReader(new InputStreamReader(connection.getInputStream()));
			StringBuilder sb = new StringBuilder();
        
			String line = "";
          while ((line = rd.readLine()) != null)
          {
              sb.append(line + '\n');
          }
        
          System.out.println(sb.toString());

			
			connection.disconnect();
		} catch( Exception e ) {
			// already handled, but needed to skip readNodes or index if transform or readNodes fails
		} catch( Throwable t ) {
			log.error( "uhh", t );
		} finally {
			try {
				// make sure the locks have clean session
				DB.closeStatelessSession();
				DB.getStatelessSession();
				releaseLocks();
				tr.setEndTransform(new Date());
				tr.setReport(reportString.getContent());
				tr.clearTmpFile();
				DB.commit();
				DB.closeSession();
				DB.closeStatelessSession();
//				JOptionPane.showMessageDialog(null, "Transformation will be ingest when Admin approve mapping.");
			} catch( Exception e2 ) {
				log.error( "Problem in releasing locks and closing sessions!!", e2 );
			}
		}
	}

	private void checkCsv() {
		XpathHolder itemXp = tr.getDataUpload().getItemXpath();
		if( !itemXp.getXpath().equals( "/items/item")) return;
		for( XpathHolder childXp: itemXp.getChildren()) {
			if( childXp.getChildren().size() != 1 ) return;
			if( ! childXp.getChildren().get(0).getName().equals( "text()" )) return;
		}
		csvImport = true;
	}
	
	// go through all the /items/item nodes
	private void transformCsv() throws Exception {
		
		// get all the nodes
		// for each node
		// get xml
		// transform with xsl
		// store result in transformation output

		XmlObject xo = tr.getDataUpload().getXmlObject();
		XpathHolder xp = xo.getByPathWithPrefix("/items/item", true );
		long totalCount = xp.getCount();
		List<XMLNode> l = xp.getNodes( 0, 1000 );

		// if there is more than a 1000, we need to go back for more
		while( ! l.isEmpty() ) {
			for( XMLNode node: l ) {
				StringWriter sw = new StringWriter();				
				node.toXmlWrapped(new PrintWriter(sw));
				tr.nextOutputFile();
				// transform into pipe (pos) and format from pipe (pis) into the zipped output (os)
				final OutputStream os = tr.getStreamToOutput();
				PipedOutputStream pos = new PipedOutputStream();
				final PipedInputStream pis = new PipedInputStream( pos );
				Runnable formatter = new Runnable() {
					public void run() {
						XMLFormatter.format(pis, os);
					}
				};
				try {
					Queues.queue(formatter, "now");
					PrintWriter pw = new PrintWriter( pos );
					pw.print( t.transform( sw.toString(), xsl ));
					pw.flush();
					pw.close();
					Queues.join( formatter );
					// use the queues f.get();
				} catch( Exception e ) {
					log.error( "Error during transformation", e );
					log.error( "Problem in XSLT transformation" ,e );
					tr.setStatusCode(Transformation.ERROR);
					tr.setStatusMessage(e.getMessage());
					tr.setEndTransform(new Date());
					DB.commit();
					throw e;
				} finally {
					os.close();
					pis.close();
					pos.close();
				}

			}
			l = xp.getNodes(l.get(l.size()-1), 1000 );
		}
	}
	
	
	private void transform() throws Exception {
		try {
			String mappings = tr.getMapping().getJsonString();
			XSLTGenerator xslt = new XSLTGenerator();

			xslt.setItemLevel(tr.getDataUpload().getItemXpath().getXpathWithPrefix(true));
			xslt.setTemplateMatch(tr.getDataUpload().getItemXpath().getXpathWithPrefix(true));
			xslt.setImportNamespaces(tr.getDataUpload().getRootXpath().getNamespaces(true));
			//xslt.setNamespaces(ftr.getDataUpload().getRootXpath().getNamespaces(true));
			xsl = XMLFormatter.format(xslt.generateFromString(mappings));
			log.debug( "XSL: " + xsl );
			// main item retrieval loop
			tr.startOutput();
			DB.commit();
			
			if( csvImport ) {
				transformCsv();
			} else {
				fileCount = tr.getDataUpload().getNoOfFiles();
				if( fileCount < 1) fileCount = 1;

				// the method to transform each entry in the source is this.processEntry
				tr.getDataUpload().processAllEntries(this);
			}
			tr.finishOutput();
			DB.commit();
		} catch( Exception e) {
			log.error( "Problem during XSLT phase." ,e );
			if( tr.getStatusCode() != Transformation.ERROR ) {
				tr.setStatusCode(Transformation.ERROR);
				tr.setStatusMessage(e.getMessage());
				tr.setEndTransform(new Date());
				DB.commit();
			}
			throw e;
		}
	}
		
	private void releaseLocks() {
		LockManager lm = DB.getLockManager();
		
		Lock l = lm.isLocked(tr.getMapping());
		if(( l!= null ) && l.getUserLogin().equals(tr.getUser().getLogin()) &&
				l.getHttpSessionId().equals(sessionId())) {
			lm.releaseLock(l);
		}
		l = lm.isLocked(tr.getDataUpload());
		if((l!=null) && l.getUserLogin().equals(tr.getUser().getLogin()) &&
				l.getHttpSessionId().equals(sessionId())) {
			lm.releaseLock(l);
		}
	}

	private boolean aquireLocks() {
		String login = tr.getUser().getLogin();
		LockManager lm = DB.getLockManager();
		if( lm.aquireLock(tr.getUser(),sessionId(), tr.getMapping()))
			if( lm.aquireLock(tr.getUser(), sessionId(), tr.getDataUpload()))
				return true;
		return false;			
	}

	private String sessionId() {
		if( uniqueSession < 0 ) uniqueSession = getUniqueSessionNumber();
		return "offlineTransformation" + uniqueSession;
	}
	
	synchronized private static int getUniqueSessionNumber() {
		sessionCounter += 1;
		return sessionCounter;
	}
	
	/**
	 * This part iterates over all transformed entries and parses them.
	 * The xmlObject is then put back into the DB.
	 */
	public void readNodes() throws Exception {
		XmlObject xml=null;
		try {
			parser = org.xml.sax.helpers.XMLReaderFactory.createXMLReader(); 
			parser.setFeature("http://apache.org/xml/features/nonvalidating/load-external-dtd", false);
			// Need a validator handler for this Transformation
			XMLDbHandler handler = new XMLDbHandler( this );
			XpathHolder root = new XpathHolder();
			root.name = "";
			root.parent = null;
			root.xpath = "";
			handler.setRoot(root);

			try {
				Schema schema = tr.getMapping().getTargetSchema().getSchema();
				ValidatorHandler validationHandler = schema.newValidatorHandler();
				ErrorHandler eh = new ErrorHandler() {

					@Override
					public void error(SAXParseException se)
							throws SAXException {
						reportString.append( "Problem in " + currentEntry + "\n" );
						reportString.append( se.getMessage() + "\n" );
						isValidEntry = false;
					}

					@Override
					public void fatalError(SAXParseException exception)
							throws SAXException {
						reportString.append( "Fatal parse problem in " + currentEntry + "\n" );
						reportString.append( exception.getMessage() + "\n" );
						throw exception;
					}

					@Override
					public void warning(SAXParseException se)
							throws SAXException {
						reportString.append( "Problem in " + currentEntry + "\n" );
						reportString.append( se.getMessage() + "\n" );
						isValidEntry = false;
					}				
				};
				
				validationHandler.setContentHandler(handler);
				validationHandler.setErrorHandler(eh);
				parser.setContentHandler(validationHandler);
			} catch( Exception e ) {
				// maybe validation not possible ?
				log.error( "No validation", e );
				parser.setContentHandler(handler);
			}
			
			xml = new XmlObject();
			root.xmlObject = xml;
			// TODO: This will create orphan XML objects on failed uploads!!
			tr.setStatusCode(Transformation.INDEXING);
			DB.getXmlObjectDAO().makePersistent(xml);
			ans = new AsyncNodeStore( xml );
			EntryProcessor ep = new EntryProcessor( ) {
				public void  processEntry(de.schlichtherle.util.zip.ZipEntry ze, InputStream is) throws Exception {
					if( ze.isDirectory()) return;
					// makes this process interruptible
					Thread.sleep(0);
					InputSource ins = new InputSource();
					ins.setByteStream(is);
					entryCount+=1;
					currentEntry = ze.getName();
					isValidEntry = true;
					parser.parse( ins );
					if( isValidEntry ) validCount++;
				}
			};
			tr.processAllEntries(ep);
			DB.commit();
			DB.getSession().clear();
			ans.finish();
			DB.getSession().refresh(tr);
			if( tr.getParsedOutput() != null ) {
				DB.getSession().delete(tr.getParsedOutput());
			}
			tr.setParsedOutput(xml);
			tr.setReport(reportString.getContent());
			DB.getTransformationDAO().makePersistent(tr);
			DB.commit();
			
			if( validCount == 0 ) {
				reportString.append( "No valid entry was found!!");
				throw new Exception( "No valid Entry");
			}
			if( nodeCount == 0 ) {
				throw new Exception( "No xml output was generated.");
			}
		} catch( Exception e ) {
			log.error( "Parsing / indexing / validating of Transformation failed. ", e );
			if( tr.getStatusCode() != Transformation.ERROR) {
				tr.setStatusMessage( "Node Reader failed with: " + e.getMessage()+"\n" );
				tr.setStatusCode(Transformation.ERROR);
			}
			DB.commit();
			// TODO: Safe to delete the XML object here ??
			tr.setParsedOutput(null);
			DB.flush();
			DB.getXmlObjectDAO().makeTransient(xml);
			ans.abort();	
			throw e;
		}
	}
	
	public void store( XMLNode n ) throws Exception {
		long currentTime = System.currentTimeMillis();
		if(( currentTime - lastReport ) > 20000 ) {
			int nodeRate = (int) ((nodeCount-lastNodeCount)*1000/(currentTime - lastReport));
			StringBuffer msg = new StringBuffer();
			if( entryCount>1 ) msg.append(" Files: " + entryCount );
			if( nodeCount>1 ) msg.append( " Nodes: "+ nodeCount );
			msg.append( " Rate: "+nodeRate+ " nodes/sec" );
			tr.setStatusMessage(msg.toString());
			log.info( tr.getDataUpload().getDbID() + " " + msg );
			DB.getTransformationDAO().makePersistent(tr);
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
	
	@Override
	/**
	 * Entry processing to do xsl transform on each of the input files
	 * in an upload.
	 */
	public void processEntry(ZipEntry ze, InputStream is)
	throws Exception {
		fileCounter+=1;
		// check if we want a report
		// we only generate 20 progress reports
		// but only every 10 seconds
		int report = fileCounter*20/fileCount;
		if( report > reportCounter ) {
			if( System.currentTimeMillis() - lastReport > 10000l ) {
				tr.setStatusMessage("Processed  " + fileCounter +
						" of " + fileCount + "  files.");
				DB.commit();
				lastReport = System.currentTimeMillis();
			}
			reportCounter+=1;
		}
		if( ze.isDirectory() ) return;
		if( !ze.getName().endsWith("xml")) return;
		tr.nextOutputFile();
		// transform into pipe (pos) and format from pipe (pis) into the zipped output (os)
		final OutputStream os = tr.getStreamToOutput();
		PipedOutputStream pos = new PipedOutputStream();
		final PipedInputStream pis = new PipedInputStream( pos );
		Runnable formatter = new Runnable() {
			public void run() {
				XMLFormatter.format(pis, os);
			}
		};
		
		try {
			Queues.queue(formatter, "now");
			// use the queues .. Future<?> f = threadPool.submit(formatter);
			log.debug("Zip entry size: "+ze.getSize());
			//if >10 MB 
			if(ze.getSize()>10485760)
				t.transformStream(is, xsl, pos);
			else{
			  t.transform(is, xsl, pos );}
			pos.flush();
			pos.close();
			Queues.join( formatter );
			// use the queues f.get();
		} catch( Exception e ) {
			log.error( "Error during transformation", e );
			log.error( "Problem in XSLT transformation" ,e );
			errorCount +=1;
			reportString.append( e.toString() + "\n" );
			
			
			// we abort only after 100 consecutive errors 
			if(( errorCount >= 100 ) && (errorCount == fileCounter )) {
				reportString.append( "Transform aborted after 100 consecutive failures!");
				throw e;	
			}
		} finally {
			os.close();
			pis.close();
			pos.close();
		}
	}

	@Override
	public void report(String msg) {
		tr.setStatusMessage(msg);
	}

	@Override
	public void reportError() {
		// TODO Auto-generated method stub
		tr.setStatusCode(Transformation.ERROR);
	}
}
