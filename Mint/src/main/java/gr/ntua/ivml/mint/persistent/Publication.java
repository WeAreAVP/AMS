package gr.ntua.ivml.mint.persistent;


import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.util.InputIterator;
import gr.ntua.ivml.mint.util.Tuple;

import java.io.BufferedOutputStream;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.InputStream;
import java.util.ArrayList;
import java.util.Date;
import java.util.Iterator;
import java.util.List;

import org.apache.commons.io.IOUtils;
import org.apache.log4j.Logger;
import org.hibernate.Hibernate;



/**
 * This class summarizes all information needed to export a selection of
 * DataUploads. Need to encapsulate special target schema logic
 * Subclass? How do I do the Hibernate stuff ??
 * 
 * @author Arne Stabenau 
 *
 */
public class Publication {
	
	public static final Logger log = Logger.getLogger( Publication.class );
	public static final int ERROR=-1;
	public static final int OK=0;
	public static final int IDLE=1;
	public static final int CONSOLIDATE=2;
	public static final int VERSION=3;
	public static final int PROCESS=4;
	public static final int POSTPROCESS=5;
	
	Long dbID;
	
	// all affected DataUpload objects
	List<DataUpload> inputUploads = new ArrayList<DataUpload>();
	
	// example stats on the this publication, more could be collected
	long itemCount;
	
	// which user did the publication
	User publishingUser;
	Organization publishingOrganization;
	
	// status information on the progress of publication
	String statusMessage;
	int statusCode;
	String report;
	
	// when the publication was initiated
	Date lastProcess;
	
	// the final output in zipped form
	// either one or many files, possible millions
	BlobWrap zippedOutput;
	
	// name of output. With this the correct Transformations are selected
	String targetSchema;

	// transient only valid while in progress
	File tmpFile;

	public Long getDbID() {
		return dbID;
	}

	public void setDbID(Long dbID) {
		this.dbID = dbID;
	}

	public List<DataUpload> getInputUploads() {
		return inputUploads;
	}

	public void setInputUploads(List<DataUpload> inputUploads) {
		this.inputUploads = inputUploads;
	}

	public long getItemCount() {
		return itemCount;
	}

	public void setItemCount(long itemCount) {
		this.itemCount = itemCount;
	}

	public User getPublishingUser() {
		return publishingUser;
	}

	public void setPublishingUser(User publishingUser) {
		this.publishingUser = publishingUser;
	}

	public Organization getPublishingOrganization() {
		return publishingOrganization;
	}

	public void setPublishingOrganization(Organization publishingOrganization) {
		this.publishingOrganization = publishingOrganization;
	}

	public String getStatusMessage() {
		return statusMessage;
	}

	public void setStatusMessage(String statusMessage) {
		this.statusMessage = statusMessage;
	}

	public int getStatusCode() {
		return statusCode;
	}

	public void setStatusCode(int statusCode) {
		this.statusCode = statusCode;
	}

	public Date getLastProcess() {
		return lastProcess;
	}

	public void setLastProcess(Date lastProcess) {
		this.lastProcess = lastProcess;
	}

	public Iterator<Tuple<DataUpload, XMLNode>> inputItemIterator() {
		return new InputIterator( getInputUploads().iterator());
	}
	
	public String getReport() {
		return report;
	}

	public void setReport(String report) {
		this.report = report;
	}

	public void appendReport( String report ) {
		this.report += report;
	}
	
	public void appendReport( String report, int limit ) {
		if( this.report.length() < limit ) {
			this.report += report;
		}
	}
	
	public BlobWrap getZippedOutput() {
		return zippedOutput;
	}

	public void setZippedOutput(BlobWrap zippedOutput) {
		this.zippedOutput = zippedOutput;
	}

	public String getTargetSchema() {
		return targetSchema;
	}

	public void setTargetSchema(String targetSchema) {
		this.targetSchema = targetSchema;
	}
	
	/**
	 * Call this to check if the publication is still valid.
	 * It should check whether changes in the input data (transformations, mappings)
	 * are not reflected here.
	 * 
	 * return true if Publication is still valid. 
	 */
	public boolean validate() {
		// go through all relevant transformations and check if any have 
		// dates after the process of this Publication.
		
		return true;
	}
	
	/**
	 * Check if the current state is still valid.
	 * Check if a new processing round has to be done.
	 * Do it (version, apply changes, pullup of changes, consolidate in one xml-object )
	 */
	public void process() {
		/*should be customized for every project */
		try {
		   setStatusCode(OK);
		   setStatusMessage("Customized message");
		   setReport("");
		
		} catch( Exception e ) {
			if( getStatusCode() != ERROR ) {
				setStatusCode(ERROR);
				setStatusMessage("Publication processing failed with: " + e.getMessage());
			}
			// didn't work, remove transformations from upload
			getInputUploads().clear();
			log.error( "processing of Publication failed.", e );
		} finally {
			
			DB.commit();
		}
	}
	
	/**
	 * Overwrite if you want specific behavior in your project.
	 * Is called when the Publication is removed ...
	 */
	public void unpublish() {
		
	}
	/**
	 * Convenience function to remove an upload. No processing is started.
	 * @param du
	 */
	public void removeUpload( DataUpload du ) {
		Iterator<DataUpload> i = getInputUploads().iterator();
		while( i.hasNext() ) {
			DataUpload du2 = i.next();
			if( du2.getDbID() == du.getDbID()) {
				i.remove();
				return;
			}
		}
	}
	
	/**
	 * 
	 * @param du
	 */
	public boolean containsUpload( DataUpload du ) {
		Iterator<DataUpload> i = getInputUploads().iterator();
		while( i.hasNext() ) {
			DataUpload du2 = i.next();
			if( du2.getDbID() == du.getDbID()) {
				return true;
				
			}
		}
		return false;
	}
	
	/**
	 * Convenience function to add an upload, no reprocessing is started.
	 * @param du
	 */
	public void addUpload( DataUpload du ) {
		getInputUploads().add( du );
	}
	
	

	
	/**
	 * Create the List of items with available newer versions.
	 * @throws Exception
	 */
	public void version() throws Exception {
		// do nothing for now
	}
	
	/**
	 * Apply the changeset to the latest version of an item.
	 * @throws Exception
	 */
	public void applyChanges() throws Exception {
		// do nothing for now
	}
	
	
	public File postProcess( File input ) throws Exception {
		return input;
	}
	
	
	public Iterator<XMLNode> itemize() throws Exception {
		return null;
	}
	
	public long sumInputItems() {
		long result = 0l;
		try {
			for( DataUpload du: getInputUploads()) {
				result += du.getItemCount();
			}
		} catch( Exception e ) {
			log.error( "Exception during item counting.", e );
			return -1l;
		}
		return result;
	}
	
	/**
	 * The given file (which needs to be a ZIP archive) is written back as 
	 * BLOB to the database.
	 * @param result
	 */
	public void writeBack( File result ) {
		try {
		zippedOutput = new BlobWrap();
		zippedOutput.data = Hibernate.createBlob( new FileInputStream( result ), (int) result.length());
		setStatusCode(OK);
		DB.commit();
		// result.delete();
		} catch( Exception e  ) {
			log.error( "Writeback failed!", e );
			try {
				setStatusCode(ERROR);
				setStatusMessage(e.getMessage());
				DB.commit();
			} catch( Exception e2 ) {
				log.error( "Status update failed as well!!", e2 );
			}
		}
	}
	
	public File getTmpFile(){
		return this.tmpFile;
	}
	
	public void unloadToTmpFile() {
		try {
			tmpFile = File.createTempFile("unloadPublication", ".zip");
			tmpFile.deleteOnExit();
			log.info( "Unloading to " + tmpFile.getAbsolutePath());
			FileOutputStream fos = new FileOutputStream( tmpFile );
			BufferedOutputStream bos = new BufferedOutputStream( fos,4096 );
			
			InputStream is = getZippedOutput().getData().getBinaryStream();
			IOUtils.copy(is, bos);
			is.close();
			bos.flush();
			bos.close();
			DB.commit();
		} catch( Exception e ) {
			log.error( "Cannot copy BLOB to tmp file", e );
		}
	}
	
	/**
	 * Returns a stream to a zip archive. Please cleanup after finished with the Stream. 
	 * @return
	 */
	public InputStream getDownloadStream() {
		InputStream is = null;		
		if( tmpFile == null )
			unloadToTmpFile();
		try {
			is = new FileInputStream(tmpFile);
		} catch( Exception e ) {
			log.error( "File unload problem", e);
		}
		return is;
	}

	/**
	 * delete the tmp file after using the Download Stream. This will be automated later.
	 */
	public void cleanup() {
		tmpFile.delete();
	}

}

/*
 * How should the process work?
 *  a) Collect all the items from the transformations, building an index of each item, which should allow for the following:
 *    - access each item 
 *    - score items against each other, the index might contain many columns with scores on certain metrics
 *      scores between items are only build from neighboring items in the index (avoid n^2 complexity)
 *    - the collection is happening as XML in files! - current approach, one ZIP archive, but this might not work for
 *      millions of items
 *    
 *  b) .. skip other steps so far ..
 *  c) post process by XSL transform to ESE
 *  d) final result is uploaded as ZIP archive to database. 
*/