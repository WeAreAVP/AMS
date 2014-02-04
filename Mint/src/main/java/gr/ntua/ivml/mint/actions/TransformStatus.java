
package gr.ntua.ivml.mint.actions;




import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.persistent.DataUpload;
import gr.ntua.ivml.mint.persistent.XpathHolder;
import gr.ntua.ivml.mint.view.Import;
import gr.ntua.ivml.mint.view.Transform;

import org.apache.log4j.Logger;
import org.apache.struts2.convention.annotation.Action;
import org.apache.struts2.convention.annotation.Result;
import org.apache.struts2.convention.annotation.Results;

@Results({
	@Result(name="error", location="transfromStatus.jsp"),
	@Result(name="success", location="transformStatus.jsp")
})

public class TransformStatus extends GeneralAction{

	protected final Logger log = Logger.getLogger(getClass());
    
	private String importId;
    private Import imp;
    private String orgId;
    private String userId;
    
    public Transform trans=null;
	
	@Action(value="TransformStatus")
	public String execute() throws Exception {
		log.debug("TransformStatus controller");
		
		return SUCCESS;
	}

	public Transform getTrans(){
		return this.trans;
	}
	
   public long getDbId(){
	   return this.imp.getDbID();
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
		return this.trans.getStatus();
		
	}
	public int getApproved(){
		return this.trans.getApproved();
	}
	
	public boolean isLocked() {
		
		// instead check if transform is locked
		return getImp().isLocked(getUser(), getSessionId());
		
	}
	
	
	public boolean hasReport() {
		
		// check if there is a report
		System.out.println(trans.hasReport());
		return trans.hasReport();
	}
	
    public boolean isStale() {
		
		// instead check if transform is locked
		return trans.isStale();
	}
	
	
	
	public String getMessage(){
		return trans.getMessage();
	}
	
	
	public String getStatusIcon(){
		
		return this.trans.getStatusIcon();
	}
	
	
	public void setImportId(String id){
		
		this.importId=id;
		
		DataUpload du=DB.getDataUploadDAO().getById(Long.parseLong(id), false);
		if(du!=null){
		this.orgId=""+(du.getOrganization().getDbID());
		this.userId=""+du.getUploader().getDbID();
		
		this.imp=new Import(du);
		trans=this.imp.getTrans();
		}
	}
	
	public boolean isRootDefined(){
		return this.imp.isRootDefined();
		
	}
	
	
}