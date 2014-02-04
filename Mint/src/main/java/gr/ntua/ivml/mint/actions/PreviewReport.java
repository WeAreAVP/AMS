
package gr.ntua.ivml.mint.actions;


import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.persistent.DataUpload;
import gr.ntua.ivml.mint.persistent.Organization;
import gr.ntua.ivml.mint.persistent.Publication;
import gr.ntua.ivml.mint.view.Transform;


import org.apache.log4j.Logger;
import org.apache.struts2.convention.annotation.Action;
import org.apache.struts2.convention.annotation.Result;
import org.apache.struts2.convention.annotation.Results;

@Results({
	@Result(name="error", location="previewReport.jsp"),
	@Result(name="success", location="${url}" )
})

public class PreviewReport extends GeneralAction{

	protected final Logger log = Logger.getLogger(getClass());

	
	private long orgId;
    private String report="";
    private Organization o=null;
    private Publication pub=null;
    private Transform trans=null;
    private String pstatus="NOT DONE";
    private String tstatus="NOT DONE";
    private long transId;
    public String url="";
  	  
		
	public Publication getPub(){
		if(o!=null){
		 pub=DB.getPublicationDAO().findByOrganization(o);
		 setPstatus();
		 setReport();
		}
		return pub;
	}
	
	
	
	public void setReport(){
		report=pub.getStatusMessage()+"<br/>";
		
		if( pub.getReport() != null ) {
			report+=pub.getReport();
			report=report.replaceAll("URL:\\(PreviewError", "<a onclick=\"javascript:ajaxErrorPreview\\(");

			report=report.replaceAll("\\?transformedNodeId=","");
	
			report=report.replaceAll("\\) had problems:","\\);\"  href=\"#\">(show Item)</a> had problems:<br/>");
			report=report.replaceAll("\\n","<br/>");
		}
	}
	
	
	public void setTReport(){
		report=trans.getStatus()+"<br/>"+trans.getMessage()+"<br/><br/>" ;
		
		if( trans.getReport() != null ) {
			report+=trans.getReport();
					}
	}
	
	public String getReport(){
		return report;
	}
	
	public String getPstatus(){
			return pstatus;
		
		
	}
	
	public String getTstatus(){
		return trans.getStatus();
    }
	
	public void setPstatus(){
		if(pub!=null){
			
			 if(pub.getStatusCode()==0){
		    	pstatus="OK";
				
			}
			else if(pub.getStatusCode()==-1){
				pstatus="ERROR";
				
			}
			else if(pub.getStatusCode()==1){
				pstatus="IDLE";
				
			}
			else if(pub.getStatusCode()==2){
				pstatus="CONSOLIDATE";
				
			}
			else if(pub.getStatusCode()==3){
				pstatus="VERSION";
				
			}
			else if(pub.getStatusCode()==4){ 
				pstatus="PROCESS";
				
			}
			else if(pub.getStatusCode()==5){ 
				pstatus="POSTPROCESS";
				
			}
		}
		
		
	}
	

	public long getOrgId() {
		return orgId;
	}

	public void setOrgId(long orgId) {
		this.orgId = orgId;
		this.o=DB.getOrganizationDAO().findById(orgId, false);
		this.getPub();
		url="previewReport.jsp";
	}

	
	public void setTransId(long transId) {
		this.transId = transId;
	    trans=new Transform(transId);  
	    setTReport();
		url="previewTransReport.jsp";		
	}
   
	public Organization getO(){
		return this.o;
	}
	
	@Action(value="PreviewReport")
	public String execute() throws Exception {
      return SUCCESS;	

	}
}