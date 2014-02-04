package gr.ntua.ivml.mint.persistent;

import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.mapping.MappingElement;
import gr.ntua.ivml.mint.util.TraversableI;

import java.io.BufferedOutputStream;
import java.io.BufferedReader;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.io.StringWriter;
import java.sql.SQLException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.Enumeration;
import java.util.HashMap;
import java.util.List;
import java.util.zip.ZipEntry;
import java.util.zip.ZipFile;
import java.util.zip.ZipInputStream;
import java.util.zip.ZipOutputStream;

import org.apache.commons.io.IOUtils;
import org.apache.commons.lang.CharSet;
import org.apache.commons.lang.CharSetUtils;
import org.apache.log4j.Logger;
import org.hibernate.Hibernate;

import de.schlichtherle.util.zip.BasicZipFile;

public class DataUpload implements Lockable, SecurityEnabled {
	
	
	public interface EntryProcessor {
		public void processEntry( de.schlichtherle.util.zip.ZipEntry ze, InputStream is ) throws Exception ;
	}
	
	private static final Logger log = Logger.getLogger( DataUpload.class );

	public static final int OK = 0;
	public static final int HARVEST = 1;
	public static final int UPLOAD = 2;
	public static final int PARSE = 3;
	public static final int INDEX = 4;
	public static final int SCHEDULED = 5;
	public static final int ERROR = -1;
	public static final int QUEUED = 6;
	public static final int DUMMY = 7;
	
	private static HashMap<Integer,String> statusText = new HashMap<Integer, String>();
	static {
		statusText.put( -1, "ERROR");
		statusText.put( 0, "OK");
		statusText.put( 1, "HARVEST");
		statusText.put( 2, "UPLOAD");
		statusText.put( 3, "PARSE");
		statusText.put( 4, "INDEX");
		statusText.put( 5, "SCHEDULED");
		statusText.put( 6, "QUEUED");
		statusText.put( 7, "DUMMY");
	}
	
	Long dbID;
	Date uploadDate;
	Organization organization;
	User uploader;
	int noOfFiles;
	String sourceURL;
	String originalFilename;
	String message;

	// resumption token for oai
	String resumptionToken;
	
	// see defined ints above
	int status;
	
	// xml or excel, maybe other formats in the future
	String structuralFormat;
	boolean httpUpload;
	boolean adminUpload;
	boolean oaiHarvest;
	boolean zippedUpload;
	
	long uploadSize;
	long nodeCount;
	
	Mapping jsonMapping;
	String xsl;
	
	BlobWrap data;
	public XpathHolder itemXpath;
	public XpathHolder itemLabelXpath;
	
	String schemaName;
	XmlSchema xmlSchema;
	
	
	// represents the parsed xml in the database 
	XmlObject xmlObject;
	
	public XmlObject getXmlObject() {
		return xmlObject;
	}


	public void setXmlObject(XmlObject xmlObject) {
		this.xmlObject = xmlObject;
	}

	// some transient stuff, not going to DB
	// tmpZip is exactly what is in the blob, so if it exists,
	// use tmpZip instead of blob
	private File tmpZip;
	private boolean unloaded = false;
	private XpathHolder rootXpath;


	
	public Mapping getJsonMapping() {
		return jsonMapping;
	}


	public void setJsonMapping(Mapping jsonMapping) {
		this.jsonMapping = jsonMapping;
	}


	public String getXsl() {
		return xsl;
	}


	public void setXsl(String xsl) {
		this.xsl = xsl;
	}


	public XpathHolder getRootXpath() {
		if( rootXpath == null ) {
			rootXpath=DB.getXpathHolderDAO().getRoot( this ); 
		}
		return rootXpath;
	}


	public XmlSchema getXmlSchema() {
		return xmlSchema;
	}


	public void setXmlSchema(XmlSchema xmlSchema) {
		this.xmlSchema = xmlSchema;
	}


	/**
	 * Use this function to create the DataUpload object. Provide
	 * a file with the uploaded data.  Set the originalFilename
	 * later on the created object. As well, please provide 
	 * structural Format and  whether this was an httpUpload or an
	 * adminUpload
	 * 
	 * @param data
	 * @param uploader
	 * @return
	 */
	public static DataUpload create( File data, User uploader, String originalFilename ) 
	throws IOException {
		DataUpload upload = new DataUpload();
		upload.uploader = uploader;
		upload.originalFilename = originalFilename;

		upload.uploadDate = new Date();
		upload.organization = uploader.getOrganization();
		upload.uploadSize = data.length();
		
		int entryCount;
		if( data.isDirectory()) {
			initFromDir( upload, data );
			log.debug( "Dir upload into zipped BLOB" );
		} else if(( entryCount = countZipEntries( data )) <0 ) {
			initFromSimpleFile( upload, data );
			log.debug( "Simple file upload");
		} else {
			// its a zip, direct upload possible
			upload.noOfFiles = entryCount;
			log.debug( "Zipped file upload - " + entryCount + " entries" );
			upload.zippedUpload = true;
			FileInputStream fis = new FileInputStream( data );
			upload.data = new BlobWrap();
			upload.data.setData(Hibernate.createBlob(fis, (int) data.length()));
		}
		return upload;
	}
	
	/**
	 * Call this method when you already have the DataUpload object without data
	 * 
	 * @param data
	 */
	public void upload( File data ) throws Exception {
		try {
			int entryCount;
			if( data.isDirectory()) {
				initFromDir( this, data );
				log.debug( "Dir upload into zipped BLOB" );
			} else if(( entryCount = countZipEntries( data )) <0 ) {
				initFromSimpleFile( this, data );
				log.debug( "Simple file upload");
			} else {
				// its a zip, direct upload possible
				this.noOfFiles = entryCount;
				log.debug( "Zipped file upload - " + entryCount + " entries" );
				this.zippedUpload = true;
				this.setUploadSize(data.length());
				FileInputStream fis = new FileInputStream( data );
				this.data = new BlobWrap();
				this.data.setData(Hibernate.createBlob(fis, (int) data.length()));
			}
		}
		catch( IOException e ) {
			log.error( "Upload file failed ", e);
			updateStatus(ERROR);
			message= e.getMessage();
			throw e;
		}
	}
	

	/**
	 * Have a DataUpload object that doesnt have the data yet. This is 
	 * for uploads that run while there is no data imported to the database yet.
	 * 
	 */
	public static DataUpload create( User uploader, String originalFilename, String url ) {
		DataUpload upload = new DataUpload();
		upload.uploader = uploader;
		upload.uploadDate = new Date();
		upload.organization = uploader.getOrganization();
		upload.originalFilename = originalFilename;
		upload.setSourceURL(url);
		return upload;
	}

	
	/**
	 * Probably unused function, good for testing, uplaods a dir as zip into this DataUpload.
	 * @param upload
	 * @param data
	 */
	public static void initFromDir( DataUpload upload, File data ) {
		try {
			upload.tmpZip = File.createTempFile("MintUpload", ".zip");
			FileOutputStream fos = new FileOutputStream( upload.tmpZip );
			BufferedOutputStream bos = new BufferedOutputStream( fos, 8192 );
			upload.noOfFiles = zipDirectory( data, bos );
			upload.data = new BlobWrap();
			upload.data.setData(Hibernate.createBlob(new FileInputStream(upload.tmpZip), 	(int) upload.tmpZip.length()));
		} catch( Exception e ) {
			log.error( "Directory upload failed, tmp zip file not created.", e); 
		}
	}

	public static final int zipDirectory( File directory, OutputStream os ) throws IOException {
		ZipOutputStream zos = new ZipOutputStream( os );
		int fileCount = zip( directory, directory, zos );
		zos.close();
		return fileCount;
	}

	public static final int zip(File directory, File base,
			ZipOutputStream zos) throws IOException {
		File[] files = directory.listFiles();
		int fileCount = 0;
		byte[] buffer = new byte[8192];
		int read = 0;
		for (int i = 0, n = files.length; i < n; i++) {
			if (files[i].isDirectory()) {
				// maybe we need to create the directory entry as well
				fileCount += zip(files[i], base, zos);
			} else {
				FileInputStream in = new FileInputStream(files[i]);
				ZipEntry entry = new ZipEntry(files[i].getPath().substring(
						base.getPath().length() + 1));
				zos.putNextEntry(entry);
				while (-1 != (read = in.read(buffer))) {
					zos.write(buffer, 0, read);
				}
				zos.closeEntry();
				in.close();
				fileCount++;
			}
		}
		return fileCount;
	}

	private static void initFromSimpleFile( DataUpload upload, File data )
	throws IOException {
		// not a zip file, needs to be zipped up
		upload.noOfFiles = 1;
		upload.zippedUpload = false;
		upload.setUploadSize(data.length());
		// zip the file
		upload.tmpZip = File.createTempFile( "MintUpload", ".zip" );
		ZipOutputStream zos = new ZipOutputStream( new FileOutputStream( upload.tmpZip ));
		ZipEntry ze = new ZipEntry( upload.originalFilename);
		ze.setMethod(ZipEntry.DEFLATED);
		ze.setTime(data.lastModified());
		zos.putNextEntry(ze);
		byte[] buffer = new byte[4096];
		int count;
		FileInputStream fis = new FileInputStream( data );
		while(( count = fis.read(buffer, 0, 4096)) != -1 ) {
			zos.write(buffer, 0, count);
		}
		zos.closeEntry();
		zos.close();
		
		// now put in place for upload			
		fis = new FileInputStream( upload.tmpZip );
		upload.data = new BlobWrap();
		upload.data.setData(Hibernate.createBlob(fis, (int) upload.tmpZip.length()));
	}
	
	public void finalize() {
		if(  tmpZip != null ) {
			log.info( "Removing " + tmpZip.getAbsolutePath());
			tmpZip.delete();
		}
	}
	
	public boolean isOaiHarvest() {
		return oaiHarvest;
	}

	public void setOaiHarvest(boolean oaiHarvest) {
		this.oaiHarvest = oaiHarvest;
	}
	
	
	public String getSchemaName() {
		return schemaName;
	}


	public void setSchemaName(String schemaName) {
		this.schemaName = schemaName;
	}

	public boolean isDirect(){
		if(this.schemaName != null)
			return true;
		else return false;
	}
	
	public void setDirectSchema(XmlSchema schema) {
		// TODO: change this when database filed schemaName is converted to XmlSchema schema
		this.schemaName = schema.getName();
	}
	
	public XmlSchema getDirectSchema() {
		// TODO: change this when database filed schemaName is converted to XmlSchema schema
		XmlSchema schema = null;
		
		List<XmlSchema> list = DB.getXmlSchemaDAO().findAll();
		for(XmlSchema s: list) {
			if(s.getName().equalsIgnoreCase(this.schemaName)) {
				return s;
			}
		}

		return schema;
	}

	public Long getDbID() {
		return dbID;
	}
	public void setDbID(Long dbID) {
		this.dbID = dbID;
	}
	public Date getUploadDate() {
		return uploadDate;
	}
	public void setUploadDate(Date uploadDate) {
		this.uploadDate = uploadDate;
	}
	public Organization getOrganization() {
		return organization;
	}
	public void setOrganization(Organization organization) {
		this.organization = organization;
	}
	public User getUploader() {
		return uploader;
	}
	public void setUploader(User uploader) {
		this.uploader = uploader;
	}
	public int getNoOfFiles() {
		return noOfFiles;
	}
	public void setNoOfFiles(int noOfFiles) {
		this.noOfFiles = noOfFiles;
	}
	public String getSourceURL() {
		return sourceURL;
	}
	public void setSourceURL(String sourceURL) {
		this.sourceURL = sourceURL;
	}
	public String getOriginalFilename() {
		return originalFilename;
	}
	public void setOriginalFilename(String originalFilename) {
		this.originalFilename = originalFilename;
	}
	public String getStructuralFormat() {
		return structuralFormat;
	}
	public void setStructuralFormat(String structuralFormat) {
		this.structuralFormat = structuralFormat;
	}
	public boolean isHttpUpload() {
		return httpUpload;
	}
	public void setHttpUpload(boolean httpUpload) {
		this.httpUpload = httpUpload;
	}
	public boolean isAdminUpload() {
		return adminUpload;
	}
	public void setAdminUpload(boolean adminUpload) {
		this.adminUpload = adminUpload;
	}
	public boolean isZippedUpload() {
		return zippedUpload;
	}
	public void setZippedUpload(boolean zippedUpload) {
		this.zippedUpload = zippedUpload;
	}
	public long getUploadSize() {
		return uploadSize;
	}
	public void setUploadSize(long uploadSize) {
		this.uploadSize = uploadSize;
	}


	public String getMessage() {
		return message;
	}

	public void setMessage(String message) {
		this.message = message;
	}

	public int getStatus() {
		return status;
	}

	public void updateStatus( int status ) {
		this.status = status;
		this.uploadDate = new Date();
	}
	
	public String getResumptionToken() {
		return resumptionToken;
	}

	public void setResumptionToken(String resumptionToken) {
		this.resumptionToken = resumptionToken;
	}

	public String getStatusText() {
		String s = statusText.get( status );
		if( s == null ) return "";
		return s;
	}
	
	
	public void setStatus(int status) {
		this.status = status;
	}

	public long getNodeCount() {
		return nodeCount;
	}

	public void setNodeCount(long nodeCount) {
		this.nodeCount = nodeCount;
	}

	/**
	 * If the file was uploaded as zip file archive, this function
	 * returns entry names. Provide start and end to enable 
	 * paging through many files.
	 * 
	 * This function only works inside the session, the object needs to
	 * access the database.
	 * 
	 * If the upload was not zipped, returns empty list.
	 * @param start
	 * @param length
	 * @return
	 */
	public List<ZipEntry> listEntries( int start, int length ) {
		if( !unloaded )
			unloadToTmpFile();
		
		ArrayList<ZipEntry> l = new ArrayList<ZipEntry>();
		if( !zippedUpload ) return l;
		ZipInputStream zis = null;
		try {
			zis = new ZipInputStream(new FileInputStream( tmpZip ));
			ZipEntry ze;
			int current = 0;
			while(( ze = zis.getNextEntry()) != null ) {
				if( current>=start && current<start+length)
					l.add( ze );
				current++;
				if( current >= start+length ) break;
			}
			
		} catch( Exception e ) {
			log.error( "Error while unzipping from DB", e );
		} 
		return l;
	}
	
	/**
	 * Not used yet, just playing with the ZippedStream
	 * @param targetDir
	 * @param dataStream
	 * @throws IOException
	 */
	public static void unzipToDir( File targetDir, InputStream dataStream ) throws IOException {
		ZipInputStream zipStream = new ZipInputStream( dataStream );
		ZipEntry entry;
		
		while(( entry = zipStream.getNextEntry()) != null ) {
			int BUFFER = 2048;
			File destFile = new File( targetDir, entry.getName());
			if( entry.getName().endsWith("/")) {
				destFile.mkdirs();
				continue;
			}
			destFile.getParentFile().mkdirs();
			FileOutputStream fos = new 
			  FileOutputStream( destFile );
			BufferedOutputStream dest = new 
			  BufferedOutputStream(fos, BUFFER);	
            log.debug("Extracting: " + destFile.getAbsolutePath());
            int count;
            byte data[] = new byte[BUFFER];
            while ((count = zipStream.read(data, 0, BUFFER)) 
                    != -1) {
            	dest.write(data, 0, count);
            }
            dest.flush();
            dest.close();
		}
		zipStream.close();
	}


	public XpathHolder getItemXpath() {
		return itemXpath;
	}

	/**
	 * Throws Exception if itemCount is not available.
	 * @return
	 * @throws Exception
	 */
	public long getItemCount() throws Exception {
		return getItemXpath().getCount();
	}
	
	public void setItemXpath(XpathHolder itemXpath) {
		this.itemXpath = itemXpath;
	}

	

	public XpathHolder getItemLabelXpath() {
		return itemLabelXpath;
	}


	public void setItemLabelXpath(XpathHolder itemLabelXpath) {
		this.itemLabelXpath = itemLabelXpath;
	}


	/**
	 * If its not a zip entry, this will just stream the file 
	 * out of the db.
	 * @return
	 */
	public InputStream getEntry() throws SQLException, IOException {
		InputStream result = null;
		if( !unloaded)
			unloadToTmpFile();
		try {
			ZipInputStream zis = new ZipInputStream( new FileInputStream( tmpZip ));
			zis.getNextEntry();
			result = zis;
		} catch( Exception e ) {
			log.error( "Zip single file return failed ", e );
		} 
		return result;
	}
	
	/**
	 * You can get a file streamed back by supplying the name.
	 * (Get the name from listEntries() 
	 * @param name
	 * @return
	 */
	public InputStream getEntry( String name  ) {
		InputStream result = null;
		if( !unloaded)
			unloadToTmpFile();
		try {
			ZipInputStream zis = new ZipInputStream( new FileInputStream( tmpZip ));
			ZipEntry ze = null;
			while(( ze = zis.getNextEntry()) != null ) {
				if( ze.getName().equals(name )) {
					result = zis;
					break;
				}
			}
		} catch( Exception e ) {
			log.error( "Reading of entry failed ", e );
		}
		return result;		
	}
	
	/**
	 * Write an entry processor to process all files in an upload.
	 * Even with one file in the upload call this, its easier!
	 * @param ep
	 */
	public void processAllEntries( EntryProcessor ep ) throws Exception {
		InputStream is = null;		
		de.schlichtherle.util.zip.ZipEntry ze = null;
		if( tmpZip == null )
			unloadToTmpFile();
		try {
			BasicZipFile bz = new BasicZipFile( tmpZip );
			Enumeration entries = bz.entries();
			while( entries.hasMoreElements() ) {
				ze = (de.schlichtherle.util.zip.ZipEntry) entries.nextElement();
				InputStream zis = bz.getInputStream(ze);
				// log.debug( "Processing " + ze.getName());
				try {
					ep.processEntry(ze, zis);
				} catch( Exception e ) {
					is = bz.getInputStream(ze);
					StringWriter sw = new StringWriter();
					int count = 0;
					int readByte;
					int[] buffer = new int[16]; 
					while(( readByte = is.read()) >= 0 ) {
						buffer[count%16] = readByte;
						count +=1;
						sw.write( String.format( "%02x ", readByte ));
						if(( count % 16) == 0 ) {
							sw.write( new String( buffer, 0, 16 ));
							sw.write( "\n" );
						}
						if( count == 160 ) break;
					}
					log.info( "Problematic file: \n" + sw.toString());
					throw e;
				}
			}
		} catch( Exception e ) {
			String msg;
			if((  e instanceof IllegalArgumentException ) && 
					 ( e.getMessage() == null ) && 
					 (ze == null)) {
				// we know this one 
				msg = "Unknown encoding in zip filename entries.";
			} else {
				String entryName = "<unknown>";
				if( ze != null ) entryName= ze.getName();
				log.error( "Reading of entry "+ entryName + " failed ", e );
				msg =  "Reading of entry " + entryName + " failed.\n" 
						+ e.getMessage();
			}
			throw new Exception( msg, e );
		} finally {
			try { is.close(); } catch( Exception e ){};
		}
	}
	
	public static int countZipEntries( File file ) {
		try {
			ZipFile zf = new ZipFile( file );
			return zf.size();
		} catch( Exception e ) {
			log.debug( file.getAbsolutePath() + " is no zip file!" );
		}
		return -1;
	}
	
	/**
	 * Get the uploaded data as stream from a tmp file.
	 * Its zip format, even if the upload was not zipped!!
	 *  
	 * Direct db streaming didn't work ..
	 * @return InputStream, close after reading!
	 */
	public InputStream getDownloadStream() {
		InputStream is = null;		
		if( tmpZip == null )
			unloadToTmpFile();
		try {
			is = new FileInputStream( tmpZip );
		} catch( Exception e ) {
			log.error( "File unload problem", e);
		}
		return is;
	}
	
	/**
	 * Because of trouble reopening a BLOB the blob is copied to the filesystem ..
	 */
	private void unloadToTmpFile() {
		tmpZip = getBlobTmpFile();
		unloaded = true;
	}

	public File getBlobTmpFile() {
		File tmpFile = null;
		try {
			tmpFile = File.createTempFile("unloaded", ".zip");
			log.info( "Unloading to " + tmpFile.getAbsolutePath());
			FileOutputStream fos = new FileOutputStream( tmpFile );
			BufferedOutputStream bos = new BufferedOutputStream( fos,4096 );
			
			InputStream is = data.getData().getBinaryStream();
			
			IOUtils.copy( is, bos );
			is.close();
			bos.close();
			DB.commit();
		} catch( Exception e ) {
			log.error( "Cannot copy BLOB to tmp file", e );
		}
		return tmpFile;
	}

	public BlobWrap getData() {
		return data;
	}

	public void setData(BlobWrap data) {
		this.data = data;
	}

	public String getLockname() {
		return new SimpleDateFormat("dd/MM/yyyy HH:mm").format(getUploadDate()) +
			" " + getOriginalFilename();
	}
	
	public ArrayList<String> listOfXPaths()
	{
		ArrayList<String> list = new ArrayList<String>();
		
		XpathHolder rootXpath = this.getRootXpath();
		if(rootXpath != null) {
			List<? extends TraversableI> children = rootXpath.getChildren();
			for(TraversableI t: children) {
				XpathHolder xp = (XpathHolder) t;
				list.addAll(xp.listOfXPaths(true));
			}
		}
		
		return list;
	}


	public List<Transformation> getTransformations() {
		return DB.getTransformationDAO().findByUpload(this);
	}
	
	public static DataUpload createDummy( User u ) {
		DataUpload result = new DataUpload();
		result.setUploadDate(new Date());
		result.setStatus(DataUpload.DUMMY);
		result.setUploader(u);
		result.setOrganization(u.getOrganization());
		result.setOriginalFilename("New annotation");
		return result;
	}
	
	public boolean isDummy() {
		return getStatus() == DataUpload.DUMMY;
	}
}

/*
create table data_upload (
data_upload_id int primary key,
upload_date timestamp,
organization_id int references organization,
uploader_id int references users,

-- only for zips its bigger than 1 --
no_of_files int,

-- for OAI reps here goes the URL --
source_url text,

-- http uploads should provide this --
original_filename text,

-- xml, excel and other later if we provide --	
structural_data_format text,

-- was it an http upload ? --
http_upload boolean,

-- if admin upload is true, files reach the server somehow (email.. jikes)
-- and the admin performs the upload
admin_upload boolean,

-- was the original data zipped? --
zipped_upload boolean,

-- how many bytes were uploaded ? --
-- for a zip file this will be pretty much the same as the db --
-- blob size --
upload_size bigint,

-- zipped content of whatever was uploaded in whatever way --
data oid	
);
*/