
package gr.ntua.ivml.mint.actions;
import gr.ntua.ivml.mint.concurrent.Queues;
import gr.ntua.ivml.mint.concurrent.UploadIndexer;
import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.harvesting.RepositoryValidator;
import gr.ntua.ivml.mint.persistent.DataUpload;
import gr.ntua.ivml.mint.persistent.User;
import gr.ntua.ivml.mint.persistent.XmlSchema;
import gr.ntua.ivml.mint.util.Config;

import java.io.File;
import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.net.URL;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;

import org.apache.commons.io.IOUtils;
import org.apache.commons.net.ftp.FTPClient;
import org.apache.commons.net.ftp.FTPFile;
import org.apache.log4j.Logger;
import org.apache.struts2.convention.annotation.Action;
import org.apache.struts2.convention.annotation.Result;
import org.apache.struts2.convention.annotation.Results;

@Results({
	  @Result(name="input", location="import.jsp"),
	  @Result(name="error", location="import.jsp"),
	  @Result(name="success", location="${url}", type="redirect" )
	})

public class Import extends GeneralAction  {

	protected final Logger log = Logger.getLogger(getClass());
	public File httpUp;
	public String contentType;
	public String filename;
	public String athenaFtpServer;
	public String serverFilename;
	public List<FTPFile> ftpFiles = new ArrayList<FTPFile> ();
	public String method = "httpupload";
	public String uploadUrl;
	public String oai;
	public long uploaderOrg;
	public String oaiset;
	public String oainamespace;
	public Date fromDate;
	public Date toDate;
	public String oaifromdate;
	public String oaitodate;
	public String url="ImportSummary.action";
	public long schemaId = 0;
	public Boolean isDirect=false;
	public Boolean csvhasHeader;

	private DataUpload du;
	private String csvdelimeter="";
	private String csvescChar="";
	
	private Boolean isCsv=false;
	
	@Action("Import")
    public String execute() throws Exception {
    	log.info( "Import action");
    	// check permits
    	// created DataUpload empty object
    	du = new DataUpload();
    	du.setUploader( user );
    	du.setUploadDate(new Date());
    	du.setStatus(DataUpload.QUEUED);
    	if((user.getOrganization()==null && uploaderOrg == 0) || (user.getAccessibleOrganizations().size()>1 && uploaderOrg == 0)){
    		addActionError("Choose the organization you are importing for!");
			return ERROR;
    	}
    	else if(user.getOrganization()!=null){
    	  du.setOrganization(user.getOrganization());
    	}
    	if(isDirect && schemaId==0){
    		addActionError("Choose the schema this import conforms to!");
			return ERROR;
    	}
    	UploadIndexer upI = null;
    	if( uploaderOrg > 0){
    		du.setOrganization(DB.getOrganizationDAO().findById(uploaderOrg, false));
    	}
    	if( !user.can( "change data", du.getOrganization() )) { log.debug("1");
    			throw new IllegalAccessException("Parameter manipulation");}
    	// fill in specifics
    	if( "httpupload".equals( method )) {
    		if(this.httpUp==null){
    			addActionError("Http upload cannot be empty!");
    			return ERROR;
    		}
    		if(isCsv)
    			upI = handleCsvUpload();
    		else
    			upI = handleHttpUpload();
    	}
    	else if( "ftpupload".equals( method )){
    		if(this.getFlist().equalsIgnoreCase("0")){
    			addActionError("No FTP files selected!");
    			return ERROR;
    		}
    		upI = handleFtpUpload();
    	}
    	else if( "urlupload".equals( method )){
    		if(this.getUploadUrl()==null || this.getUploadUrl().length()==0){
    			addActionError("Remote link cannot be empty!");
    			return ERROR;
    		}
    		upI = handleUrlUpload();
    	}
    	else if( "OAIurl".equals( method )){
    		if(this.getOai()==null || this.getOai().length()==0){
    			addActionError("Oai url cannot be empty!");
    			return ERROR;
    		}
    		if(!RepositoryValidator.isValid(this.getOai())){
    			addActionError("Oai url is invalid!");
    			return ERROR;
    		
    		}
    		if(this.oaifromdate!=null && this.oaifromdate.length()>0){
    			java.text.SimpleDateFormat sdf=new java.text.SimpleDateFormat("yyyy-MM-dd");
    			try {
    				this.fromDate = new Date(sdf.parse(oaifromdate).getTime());
    				
    			} catch( Exception pe ) {
    				addActionError("Please give 'From Date' in the correct format!");
    				return ERROR;
    				
    			}
    			
	    		
    		}
    		if(this.oaitodate!=null && this.oaitodate.toString().length()>0){
    			java.text.SimpleDateFormat sdf=new java.text.SimpleDateFormat("yyyy-MM-dd");
    			try {
    				this.toDate = new Date(sdf.parse(oaitodate).getTime());
    			
    			} catch( Exception pe ) {
    				addActionError("Please give 'To Date' in the correct format!");
    				return ERROR;
    			}
	    		
    		}
    		if(this.getOainamespace()==null || this.getOainamespace().length()==0){
    			addActionError("Oai namespace prefix cannot be empty!");
    			return ERROR;		
    		}
    		upI = handleOaiUpload();
    	}
    	else if( "SuperUser".equals( method )) {
    		if(this.getServerFilename()==null || this.getServerFilename().length()==0){
    			addActionError("Server filename cannot be empty!");
    			return ERROR;
    		}
    		upI = handleServerUpload();
    	}
    	
    	else {
    		log.error("Unknown method" );
    		addActionError("Specify an import method!");
    		return ERROR;
    	}
    	
		if((upI!= null) && this.isDirect && this.schemaId > 0){
			upI.getDataUpload().setDirectSchema(DB.getXmlSchemaDAO().findById(this.schemaId, false));
		}

    	DB.commit();
    	if( upI != null ) {
    		Queues.queue(upI, "net" );
    		this.url+="?orgId="+this.du.getOrganization().getDbID();
    		System.out.println("url is:"+this.url);
    		return "success";
    	} else {
    		return "error";
    	}
    	
    }

	/**
	 * Try to put the uploaded file into a DataUpload object
	 * @return
	 * @throws Exception
	 */
	private UploadIndexer handleHttpUpload() throws Exception {
		du.setHttpUpload(true);
		du.setOriginalFilename(filename);
		DB.getDataUploadDAO().makePersistent(du);
		File newTmpFile = File.createTempFile("MintUpload", "cpy");
		IOUtils.copy( new FileInputStream(httpUp), new FileOutputStream( newTmpFile ));
		UploadIndexer upI = new UploadIndexer( du, UploadIndexer.HTTPUPLOAD );
		upI.tmpFile = newTmpFile;
		return upI;
	}
	
	private UploadIndexer handleCsvUpload() throws Exception {
		du.setHttpUpload(true);
		du.setOriginalFilename(filename);
		DB.getDataUploadDAO().makePersistent(du);
		File newTmpFile = File.createTempFile("MintUpload", "cpy");
		IOUtils.copy( new FileInputStream(httpUp), new FileOutputStream( newTmpFile ));
		UploadIndexer upI = new UploadIndexer( du, UploadIndexer.HTTPUPLOAD );
		upI.csvDelimiter = getCsvdelimeter();
		upI.csvEscCharacter = getCsvescChar();
		upI.hasHeader = getCsvhasHeader();
		upI.isCsv = true;
		upI.tmpFile = newTmpFile;
		return upI;
	}
	
	private UploadIndexer handleServerUpload() throws Exception {
		du.setAdminUpload(true);
		du.setOriginalFilename(serverFilename);
		File tmpFile = new File( serverFilename );
		if( !tmpFile.exists() || !tmpFile.canRead() || tmpFile.length()== 0l ) {
			du.setStatus(DataUpload.ERROR);
			du.setMessage("Upload failed, file not found, not readable or empty" );
		}
		DB.getDataUploadDAO().makePersistent(du);
		UploadIndexer upI = new UploadIndexer( du, UploadIndexer.SERVERFILE );
		upI.tmpFile = tmpFile;
		return upI;
	}
	
	/**
	 * Put the filename in du.originalFilename and let the UploadIndexer Thread handle everything.
	 * @return
	 * @throws Exception
	 */
	private UploadIndexer handleFtpUpload() throws Exception {
		du.setOriginalFilename(filename);
		DB.getDataUploadDAO().makePersistent(du);
		
		UploadIndexer upI = new UploadIndexer( du, UploadIndexer.FTPSERVER );
		return upI;
		
	}
	
	/*private UploadIndexer handleCsvUpload() throws Exception {
		
		
		UploadIndexer upI;= new UploadIndexer( du,this.getHasHeader(),this.getDelimeter(),this.getEscChar(), UploadIndexer.FTPSERVER );
		return upI;
		
	}*/
	
	
	private UploadIndexer handleUrlUpload() throws Exception {
		du.setSourceURL(uploadUrl);
		URL url = new URL( uploadUrl );
		du.setOriginalFilename(url.getFile());
		DB.getDataUploadDAO().makePersistent(du);
		
		UploadIndexer upI = new UploadIndexer( du, UploadIndexer.URLUPLOAD );
		return upI;
	}
	
	private UploadIndexer handleOaiUpload() throws Exception {
		// create the data upload object anyway and session it
		// probably redirect to the oai schedule page
		du.setSourceURL(getOai());
		du.setOaiHarvest(true);
		DB.getDataUploadDAO().makePersistent(du);
		String set = null;
		if(!this.oaiset.equals("")){
			set = this.oaiset;
		}else{
			set = null;
		}
		UploadIndexer upI = new UploadIndexer( du, UploadIndexer.OAIHARVEST, set, this.oainamespace, this.fromDate, this.toDate);
		//UploadIndexer upI = new UploadIndexer( du, UploadIndexer.OAIHARVEST );
		return upI;
	}
	
	@Action("Import_input")
	@Override
	public String input() throws Exception {
    	if( user.getOrganization() == null && !user.hasRight(User.SUPER_USER)) {
    		throw new IllegalAccessException( "No import rights!" );
    	}

		return super.input();
	}
	

	// setters for form interaction with hhtp upload
	public void setHttpup(File file) {
		log.debug( "File upload set in action");
		this.httpUp = file;
	}

	public void setHttpupContentType(String contentType) {
		this.contentType = contentType;
	}
	
	public void setHttpupFileName(String filename) {
		this.filename = filename;
	}
	// end httpupload
	
	
	public List<FTPFile> getFtpFiles() {
		log.debug( "entering getFtpFiles");
		ftpFiles.clear();
		try {
			   FTPClient f= new FTPClient();
			    f.connect(Config.get("ftp.host"));
			    f.login(Config.get("ftp.user"), Config.get("ftp.password"));
			    FTPFile[] allFiles = f.listFiles("");
			    for( FTPFile file: allFiles ) 
			    	if( !file.isDirectory()) ftpFiles.add( file );
			    	else log.debug( "Listed dir " + file.getName());
		} catch( Exception e ) {
			log.error( "FTP read Dir didnt succeed", e );
		}
		return ftpFiles;
	}
	
	public List<XmlSchema> getXmlSchemas() {
		return DB.getXmlSchemaDAO().findAll();
	}
	
		
	public String getServerFilename() {
		return serverFilename;
	}

	public void setServerFilename(String serverFilename) {
		this.serverFilename = serverFilename;
	}

	public String getOai() {
		return oai;
	}

	public void setOai(String oai) {
		this.oai = oai;
	}

	public String getOaiset() {
		return oaiset;
	}

	public void setOaiset(String oaiset) {
		this.oaiset = oaiset;
	}
	
	public String getOainamespace() {
		return oainamespace;
	}

	public void setOainamespace(String namespace) {
		this.oainamespace = namespace;
	}
	
	public String getOaifromdate() {
		return oaifromdate;
	}

	public void setOaifromdate(String fromdate) {
		this.oaifromdate=fromdate;
		
	}
	
	
	public String getOaitodate() {
		return oaitodate;
	}

	public void setOaitodate(String todate) {
		this.oaitodate=todate;
		
	}
	
	public String getMth() {
		return method;
	}
	
	public void setMth( String method ) {
		this.method = method;
	}

	public String getUploadUrl() {
		return uploadUrl;
	}

	public void setUploadUrl(String uploadUrl) {
		this.uploadUrl = uploadUrl;
		 
	}

	public String getFlist() {
		return filename;
	}

	public void setFlist( String name ) {
		filename = name;
	}
	
	public long getDirectSchema() {
		return this.schemaId;
	}
	
	public void setDirectSchema( long schema ) {
		this.schemaId = schema;
	}
	

	public Boolean getIsDirect() {
	        return this.isDirect;
	    }

	  public void setIsDirect(Boolean isDirect) {
		    this.isDirect = isDirect;
	    }
	
	  public Boolean getIsCsv() {
	        return this.isCsv;
	    }

	  public void setIsCsv(Boolean isCsv) {
		    this.isCsv = isCsv;
	    }
	  
	  public Boolean getCsvhasHeader() {
	        return this.csvhasHeader;
	    }

	  public void setCsvhasHeader(Boolean hasHeader) {
		    this.csvhasHeader = hasHeader;
	    }  
	  
	  
	  public void setCsvdelimeter( String delimeter ) {
			this.csvdelimeter = delimeter;
		}
		
	  public String getCsvdelimeter() {
			return csvdelimeter;
		}
	
		
	  public void setCsvescChar( String escchar ) {
				this.csvescChar = escchar;
			}
			
	  public String getCsvescChar() {
				return csvescChar;
			}
			
      public void setCsvUpFileName(String filename) {
				this.filename = filename;
			}
	
     
     public void setCsvUpContentType(String contentType) {
  		this.contentType = contentType;
  	}
	  
	/**
	 * Setter for the Organization for which you want to upload
	 * @param uploaderOrg
	 */
	public void setUploaderOrg( long uploaderOrg ) {
		this.uploaderOrg = uploaderOrg;
	}
	
	public long getUploaderOrg() {
		return uploaderOrg;
	}
}