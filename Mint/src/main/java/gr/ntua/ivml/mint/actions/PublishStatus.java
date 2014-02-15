
package gr.ntua.ivml.mint.actions;




import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.persistent.DataUpload;
import gr.ntua.ivml.mint.view.Import;
import gr.ntua.ivml.mint.view.Publish;

import org.apache.log4j.Logger;
import org.apache.struts2.convention.annotation.Action;
import org.apache.struts2.convention.annotation.Result;
import org.apache.struts2.convention.annotation.Results;

@Results({
	@Result(name="error", location="_includeimportpub.jsp"),
	@Result(name="success", location="_includeimportpub.jsp")
})

public class PublishStatus extends GeneralAction{

	protected final Logger log = Logger.getLogger(getClass());
    
	private String importId;
    private Import imp;
    private String orgId;
    private String userId;
    
    public Publish pub=null;
	
	@Action(value="PublishStatus")
	public String execute() throws Exception {
		log.debug("PublishStatus controller");
		
		return SUCCESS;
	}

	public Publish getPub(){
		return this.pub;
	}
	
   public long getDbId(){
	   return this.pub.getDbID();
   }
	
	public String getImportId(){
		return this.importId;
	}

	public String getOrgId(){
		return this.orgId;
	}
	
	public String getUserId(){
		return this.userId;
	}
	
	public Import getImp(){
		return this.imp;
	}
	
	public String getStatus(){
		return this.pub.getStatus();
		
	}
	
	
	public boolean isLocked() {
		
		// instead check if transform is locked
		return getImp().isLocked(getUser(), getSessionId());
	}
	
 
	
	
	public String getMessage(){
		return pub.getMessage();
	}
	
	
	public String getStatusIcon(){
		
		return this.pub.getStatusIcon();
	}
	
	
	public void setImportId(String id){
		
		this.importId=id;
		
		DataUpload du=DB.getDataUploadDAO().getById(Long.parseLong(id), false);
		if(du!=null){
		this.orgId=""+(du.getOrganization().getDbID());
		this.userId=""+du.getUploader().getDbID();
		
		this.imp=new Import(du);
		pub=this.imp.getPub();
		}
	}
	
	
}