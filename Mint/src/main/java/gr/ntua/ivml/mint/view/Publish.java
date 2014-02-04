package gr.ntua.ivml.mint.view;


import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.persistent.DataUpload;

import gr.ntua.ivml.mint.persistent.Publication;


public class Publish {

   
	private long importId;
    private String statusIcon="";
    private String status="NOT DONE";
  
    private Import imp;

    private String message="";
  
    public Publication pb=null;
    
   
	

	public void setpb(){
		DataUpload du=DB.getDataUploadDAO().getById(getImp().getDbID(),false);
		
		pb = DB.getPublicationDAO().findByOrganization(du.getOrganization());
		if(pb!=null && pb.containsUpload(du)){
			//System.out.println("pb is:"+pb.getDbID()+ "status is:"+pb.getStatusCode()+" for org:"+du.getOrganization().getName());
			this.setStatus();
			this.setMessage();
			this.setStatusIcon();
		}
		else{//System.out.println("pb is:"+pb+" for org:"+du.getOrganization().getName());
		   }
		
		}
	
	public Publication getPb(){
		return this.pb;
	}

	
	public long getImportId(){
		return this.importId;
	}
	
	public long getDbID(){
		return this.pb.getDbID();
	}


	public Import getImp(){
		return this.imp;
	}
	
	public void setStatus(){
		if(pb!=null){
			 if(pb.getStatusCode()==0){
		    	status="OK";
				
			}
			else if(pb.getStatusCode()==-1){
				status="ERROR";
				
			}
			else if(pb.getStatusCode()==1){
				status="IDLE";
				
			}
			else if(pb.getStatusCode()==2){
				status="CONSOLIDATE";
				
			}
			else if(pb.getStatusCode()==3){
				status="VERSION";
				
			}
			else if(pb.getStatusCode()==4){ 
				status="PROCESS";
				
			}else if(pb.getStatusCode()==5){ 
				status="POSTPROCESS";
				
			}
		}
		
		
	}
	
	public String getStatus(){
		
		return this.status;
	}
	
	

	
	public void setMessage(){
		
		if(pb!=null){
	      this.message=pb.getStatusMessage();
	     
	      if(pb.getStatusCode()==0){
	    	  this.message="Published ";
	      }
	      
	     }
		
	}
	
	public String getMessage(){
		return this.message;
	}
	
	
	public void setStatusIcon(){


		if(pb!=null){
	    if(pb.getStatusCode()==0){
	    	
			statusIcon="images/okblue.png";
		}
		else if(pb.getStatusCode()==-1){
			
			statusIcon="images/problem.png";
		}
		else if(pb.getStatusCode()==1 || pb.getStatusCode()==2 || pb.getStatusCode()==3 || pb.getStatusCode()==4 ||  pb.getStatusCode()==5){
		
			statusIcon="images/loader.gif";
		}
		
	 	}
		
	}
	
	public String getStatusIcon(){
		return this.statusIcon;
	}
	
	
	public Publish(long id){
		
		this.importId=id;
		
		DataUpload du=DB.getDataUploadDAO().getById(id, false);
		if(du!=null){
			this.imp=new Import(du);
		    this.setpb();
		}
	}
	
	
}