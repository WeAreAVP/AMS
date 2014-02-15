package gr.ntua.ivml.mint.view;

import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.persistent.DataUpload;
import gr.ntua.ivml.mint.persistent.Mapping;
import gr.ntua.ivml.mint.persistent.Transformation;

import java.util.List;


public class Transform {

   
	private long importId;
    private String statusIcon="";
    private String status="NOT DONE";
    private int approved=0;
    private String annostatus="NOT DONE";
    private int annocode=-1;
    private boolean isStale=false;
    private Import imp;
    private Mapping mp;
    private String message="";
    private boolean locked=false;
  
    public Transformation tr=null;

	public void setTr(){
		DataUpload du=DB.getDataUploadDAO().getById(getImp().getDbID(),false);
		List<Transformation> lt = DB.getTransformationDAO().findByUpload(du);
		if(lt!=null && lt.size()!=0 && !du.isDirect()){
		 
			this.tr=lt.get(0);
			mp=tr.getMapping();
			
			this.setStatus();
			this.setMessage();
			this.setStatusIcon();
			this.setApproved();
			this.isStale=tr.isStale();
		}
		else{}
		
		}
	
	
	public long getImportId(){
		return this.importId;
	}
	
	public long getDbID(){
		return this.imp.getDbID();
	}

	public long getTransDbID(){
		return this.tr.getDbID();
	}
	
	public boolean isLocked(){
		return locked;
	}
	
	public boolean isStale(){
		return isStale;
	}
	
	public Import getImp(){
		return this.imp;
	}
	
	public void setStatus(){
		if(tr!=null){
		 if(tr.getStatusCode()==0){
		    	status="OK";
				
			}
			else if(tr.getStatusCode()==-1){
				status="ERROR";
				
			}
			else if(tr.getStatusCode()==1){
				status="IDLE";
				
			}
			else if(tr.getStatusCode()==2){
				status="WRITING";
				
			}
			else if(tr.getStatusCode()==3){
				status="UPLOADING";
				
			}
			else if(tr.getStatusCode()==4){
				status="INDEXING";
				
			}
			else if(tr.getStatusCode()==5){
				status="DUMMY";
				
			}
			else{
				status="UNKNOWN";
				
			}
		}
		
		
	}
	
	
	public String getStatus(){
		
		return this.status;
	}
	public void setApproved(){
		if(tr!=null){
			approved=tr.getIsApproved();
		}
	}
	public int getApproved(){
		
		return this.approved;
	}
	
	public void setMessage(){
		
		if(tr!=null){
	      this.message=tr.getStatusMessage();
	      //MESSAGE NEEDS TO BE FIXED IN DB
	      if(tr.getStatusCode()==0){
	    	  this.message="Transformed using mappings "+mp.getName()+".";
	      }
	      if(tr.isStale()){this.message+=" The underlying Mapping had changes since Transformation.";}
	     }
		
	}
	
	public String getMessage(){
		return this.message;
	}
	
	
	public void setStatusIcon(){
		//instead of checking import check transformation 
		if(tr!=null){
	    if(tr.getStatusCode()==0){
	    	
			statusIcon="images/okblue.png";
		}
		else if(tr.getStatusCode()==-1){
			
			statusIcon="images/problem.png";
		}
		else if(tr.getStatusCode()==1){
		
			statusIcon="images/loader.gif";
		}
		else if(tr.getStatusCode()==2){
			
			statusIcon="images/loader.gif";
		}
		else if(tr.getStatusCode()==3){
			
			statusIcon="images/loader.gif";
		}
		else if(tr.getStatusCode()==4){
			
			statusIcon="images/loader.gif";
		}
	    if(tr.isStale()){
	    	
	    	statusIcon="images/redflag.png";
	    }
		}
		
	}
	
	public String getStatusIcon(){
		return this.statusIcon;
	}
	
	
	public Transform(long id){
		
		this.importId=id;
		
		DataUpload du=DB.getDataUploadDAO().getById(id, false);
		if(du!=null){
			this.imp=new Import(du);
		    this.setTr();
		}
	}
	
	public boolean hasReport(){
		if(tr.getReport()!=null && tr.getReport().length()>0){
			return true;
		}
		else{return false;}
	}
	
	public String getReport(){
		String report = tr.getReport().replaceAll("\\n", "<br/>" );
		 return report;
	}
	
	
}