
package gr.ntua.ivml.mint.actions;

import java.util.List;

import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.persistent.DataUpload;
import gr.ntua.ivml.mint.persistent.User;
import gr.ntua.ivml.mint.view.Import;

import org.apache.log4j.Logger;
import org.apache.struts2.convention.annotation.Action;
import org.apache.struts2.convention.annotation.Result;
import org.apache.struts2.convention.annotation.Results;

@Results({
	@Result(name="error", location="importStatus.jsp"),
	@Result(name="success", location="importStatus.jsp")
})

public class ImportStatus extends GeneralAction{

	protected final Logger log = Logger.getLogger(getClass());
    
	private String importId;
   
    public Import imp;
    private String orgId;
    private String userId;
    private String uploader;
    
	@Action(value="ImportStatus")
	public String execute() throws Exception {
		log.debug("ImportStatus controller for id:"+importId);		
		return "success";
	}

	public String getImportId(){
		return this.importId;
	}
	
	public long getDbID(){
		return this.imp.getDbID();
	}

	public String getOrgId(){
		return this.orgId;
	}
	
	public String getUserId(){
		return this.userId;
	}
	
	public long getUploader(){
		return this.imp.getUploader();
	}
	
	public Import getImp(){
		return this.imp;
	}
	
	public String getStatus(){
		return this.imp.getStatus();
		
	}
	
	public boolean isLocked() {
		return getImp().isLocked(getUser(), getSessionId());
	}
	
	
	
	public String getFormattedMessage(){
		
		return this.imp.getFormattedMessage();
		
	}
	
	
	public String getStatusIcon(){
		
		return this.imp.getStatusIcon();
	}
	
	public boolean isDirect(){
		return this.imp.isDirect();
	}
	
	public void setImportId(String id){
		DataUpload du=null;
		this.importId=id;
		du=DB.getDataUploadDAO().getById(Long.parseLong(id), false);
		if(du!=null){
		
		  this.orgId=""+(du.getOrganization().getDbID());
		  this.userId=""+du.getUploader().getDbID();
		  this.imp=new Import(du);
		  
		}
		
	}
	
	public List getDownloads(){
		return this.imp.getDownloads();
	}

	
}