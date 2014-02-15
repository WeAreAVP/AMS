package gr.ntua.ivml.mint.view;

import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.persistent.DataUpload;
import gr.ntua.ivml.mint.persistent.Transformation;
import gr.ntua.ivml.mint.persistent.User;
import gr.ntua.ivml.mint.persistent.XpathHolder;
import gr.ntua.ivml.mint.util.StringUtils;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;

import org.apache.commons.lang.StringEscapeUtils;
import org.apache.log4j.Logger;

public class Import {
	public static final Logger log = Logger.getLogger( Import.class );
	
	private DataUpload du;
	private String message="";
	private String status="";
	private String formattedMessage="";
	private String statusIcon="";
	private Transform trans;
	private Publish pub;
  	
	public static class Download {
		String title;
		String url;
		
		public Download( String title, String url ) {
			this.title = title;
			this.url = url;
		}
		public String getTitle() {
			return title;
		}
		public String getUrl() {
			return url;
		}
	}
	
	public Import( DataUpload du ) {
			this.du = du;	
	}
		
	public List<Download> getDownloads() {
		List<Download> result = new ArrayList<Download>();
		try {
		String baseUrl = "download?dbId="+du.getDbID();
		if( du.getData() != null) 
			result.add( new Download( "Imported data", baseUrl ));
		for( Transformation tr: du.getTransformations()) {
			if( tr.getZippedOutput() != null ){
				// no extra download for direct imports
				if( tr.getZippedOutput().getDbID() != du.getData().getDbID())
					result.add( new Download( "Transformed with " + tr.getMapping().getName(), baseUrl + "&transformed=true" ));
				}
		}
		
		
		} catch( Exception e ) {
			log.error( "Getter failed", e );
		}
		return result;
	}
	
	public boolean isDirect(){
	   return du.isDirect();	
		
	}
	
	public boolean isImported() {
		return ( du.getStatus() == DataUpload.OK );
	}
	
	public boolean isDummy(){
		   return du.isDummy();	
	}
	
	public Publish getPub(){
		if(pub==null){
		   this.pub=new gr.ntua.ivml.mint.view.Publish(du.getDbID());
		}	
		return pub;
	}
	
	public Transform getTrans(){
		if(trans==null){
		  this.trans=new gr.ntua.ivml.mint.view.Transform(du.getDbID());
		}
		return trans;
	}
	
	public long getTransformationId(){
		return this.du.getTransformations().get(0).getDbID();
		
	}
	
	
	
	public long getUploader(){
		return this.du.getUploader().getDbID();
	}
	
	public String getName() {
		return du.getOriginalFilename();
		
	}
	
	public String getShortName() {
		return StringUtils.shorten( du.getOriginalFilename(),
				14,"..",14 ) ;
	}
	
	
	public String getSize() {
		if( du.getUploadSize() > 0 )
			return Long.toString( du.getUploadSize());
		else 
			return "";
	}
	
    public String getFormattedMessage(){
		
		this.formattedMessage=this.getMessage();
    	return this.formattedMessage;
		
	}
    
    public boolean canDownload( User u ) {
    	return u.can( "download", du );
    }
	
	
	public String getDate() {
		Date d = du.getUploadDate();
		if( d == null ) return "";
		else
		return new SimpleDateFormat("dd/MM/yyyy HH:mm").format(d);
	}
	
	public String getStatus() {
		 this.status=du.getStatusText();
		 return this.status;
	}
	
	public String getMessage() {
		if( du.getStatus() == DataUpload.OK && du.getItemXpath() != null ) 
			return du.getItemXpath().getCount() + " items imported!";
		else 
			return du.getMessage().replaceAll("\n", "\\\\n");
	}
	
	public String getStatusIcon(){
		if(this.getStatus().equalsIgnoreCase("OK")){
			this.statusIcon="images/ok.png";
		}
		else if(this.getStatus().equalsIgnoreCase("ERROR")){
			this.statusIcon="images/problem.png";
		}
		else{
			this.statusIcon="images/loader.gif";
		}
		return this.statusIcon;
	}
	
	
	public long getDbID() {
		return du.getDbID();
	}
	public int getNoOfFiles() {
		return du.getNoOfFiles();
	}
	
	public String getSizeDescription() {
		long size = du.getUploadSize();
		StringBuffer msg = new StringBuffer();
		
		//TODO change to byte conversion
		
		if( size > 0 ) {
			int mag = 0;
			while( size >=  1000) {
				size = size / 10;
				mag++;
			}
			char[] oMag = { 'K', 'M', 'G' };
			if( mag > 0 ) msg.append( oMag[ (mag-1)/3 ]);
			msg.insert(0, size );
			// and now the dot
			if( mag%3 != 0 ) msg.insert( mag%3, ".");
		} else {
			// no upload size .. bummer
		}
		if( du.getNoOfFiles() > 1 ) {
			if( msg.length()>0)
				msg.append( " in " );
			msg.append( du.getNoOfFiles());
			if(getOai().length()>0){
			  msg.append(" responses");
			}else{
			  msg.append(" files");
							
			}
			
		}
		return msg.toString();
	}
	
	public boolean isZip() {
		return (this.getName().endsWith("zip") || this.getName().endsWith("rar"));
	}
	
	public boolean isExcel() {
		return "xls".equals( du.getStructuralFormat());
	}
	
	public String getOai() {
		if( du.isOaiHarvest()) 
		
			return du.getSourceURL();	
		else return "";
	}
	
	public String getFullOai() {
		if( du.isOaiHarvest()) 
				return du.getSourceURL();
			
		else return "";
	}
	
	public boolean isLocked( User u, String sessionId ) {
		return !DB.getLockManager().canAccess( u, sessionId, du );
	}
	
	public boolean isRootDefined(){
		DataUpload du1=DB.getDataUploadDAO().getById(this.getDbID(), false);
		XpathHolder level_xp = du1.getItemXpath();
		if(level_xp == null || level_xp.getXpathWithPrefix(true).length()==0) 
			return false;
		else 
		 return true;
		
	}
	
}
