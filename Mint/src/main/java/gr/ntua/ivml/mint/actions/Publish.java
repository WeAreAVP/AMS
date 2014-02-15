
package gr.ntua.ivml.mint.actions;



import gr.ntua.ivml.mint.concurrent.PublicationProcessor;
import gr.ntua.ivml.mint.concurrent.Queues;
import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.persistent.DataUpload;
import gr.ntua.ivml.mint.persistent.Organization;
import gr.ntua.ivml.mint.persistent.Publication;
import gr.ntua.ivml.mint.persistent.User;
import gr.ntua.ivml.mint.view.Import;
import gr.ntua.ivml.mint.view.Transform;

import java.util.ArrayList;
import java.util.List;

import org.apache.log4j.Logger;
import org.apache.struts2.convention.annotation.Action;
import org.apache.struts2.convention.annotation.Result;
import org.apache.struts2.convention.annotation.Results;


@Results({
	  @Result(name="input", location="publish.jsp"),
	  @Result(name="error", location="publish.jsp"),
	  @Result(name="success", location="summary.jsp" )
	})

public class Publish extends GeneralAction  {

	protected final Logger log = Logger.getLogger(getClass());
	private long uploadId;
	private long orgId;
	private ArrayList unpubarray=new ArrayList();
	private ArrayList pubarray=new ArrayList();
	private ArrayList<Import> transformed=new ArrayList<Import>();
	private ArrayList<Import> published=new ArrayList<Import>();
	
	
	public long getOrgId(){
		return orgId;
	}
	
	public void setOrgId(long orgId){
			this.orgId=orgId;
	}
	
	public long getUploadId(){
		return uploadId;
	}
	
	public void setUploadId(long uploadId){
		this.uploadId=uploadId;
	}
	
	public void setUploadId(String uploadId){
		this.uploadId=Long.parseLong(uploadId);
	}
	

	public void setUnpubarray(String unpubstring){
		unpubarray=new ArrayList();
	   if(unpubstring.trim().length()>0){
			String[] chstr=unpubstring.split(",");
			
		   java.util.Collection c=java.util.Arrays.asList(chstr);
		   this.unpubarray.addAll(c);
		}
	}
	
	public void setPubarray(String pubstring){
		pubarray=new ArrayList();
		   if(pubstring.trim().length()>0){
			String[] chstr=pubstring.split(",");
			
		   java.util.Collection c=java.util.Arrays.asList(chstr);
		   this.pubarray.addAll(c);
		}
	}
	
	@Action(value="Publish")
    public String execute() throws Exception {
	   Publication p=DB.getPublicationDAO().findByOrganization(DB.getOrganizationDAO().getById(this.orgId, false));
	   if(p!=null){
		 p.unpublish();
		 DB.getPublicationDAO().makeTransient(p);
		 DB.commit();
	   }
	   
	   p=new Publication();
	   p.setPublishingOrganization(DB.getOrganizationDAO().getById(this.orgId, false));
	   p.setPublishingUser(user);
	   boolean newp=false;
	   for(Object i:pubarray){
		   DataUpload du=DB.getDataUploadDAO().getById(Long.parseLong(i.toString()), false);
		   if(du!=null && DB.getLockManager().isLocked(du)==null){
			  p.addUpload(du);
			  newp=true;
		   }
	   }
	   if(newp==true){
		   p.setStatusCode(Publication.IDLE);
		   DB.getPublicationDAO().makePersistent(p);}
	   DB.commit();
	   
	   PublicationProcessor pp = new PublicationProcessor( p );
	   Queues.queue( pp, "db" );
	  return "success";
    }

	
	public List<Import> getTransformed() {
		return this.transformed;
	}
	
	public List<Import> getPublished() {
		return this.published;
	}
		
	public void calcPublished() {
		Organization org = DB.getOrganizationDAO().findById(this.orgId, false);
		Publication p=DB.getPublicationDAO().findByOrganization(org);
		
		if(p!=null){
			List<DataUpload> ulist=p.getInputUploads();
			for( DataUpload x: ulist ) {
				  //if not locked continue
				Import su = new Import(x);
			    published.add(su);	
		    }
		}
		
	} 
	
	public void calcTransformed() {
		Organization org = DB.getOrganizationDAO().findById(this.orgId, false);
		List<DataUpload> du= DB.getDataUploadDAO().findByOrganization(org);
		Publication p=DB.getPublicationDAO().findByOrganization(org);


		if(du!=null){
			for( DataUpload x: du ) {
				if( x.getStatus() != DataUpload.OK ) continue;
				//if not locked continue
				if((p==null || ((p!=null) && p.containsUpload(x)==false)) && DB.getLockManager().isLocked(x)==null){
					Import su = new Import(x);

					Transform tr=su.getTrans();
					if((tr.getStatus()=="OK" && !tr.isStale()) || su.isDirect())
						transformed.add(su);			
				}
			}

		}
	} 
	
	
	@Action("Publish_input")
	@Override
	public String input() throws Exception {
		if( (user.getOrganization() == null && !user.hasRight(User.SUPER_USER)) || !user.hasRight(User.PUBLISH)) {
    		log.debug("No publishing rights");
    		throw new IllegalAccessException( "No publishing rights!" );
    	}
		//check if publication is working for org
		Organization org = DB.getOrganizationDAO().findById(this.orgId, false);
		
        Publication p=DB.getPublicationDAO().findByOrganization(org);
    	if(p!=null && p.getStatusCode()>0){
			this.addActionError("A publication is currently being made for this organization. Wait for the publication to finish before you try to alter it.");
			return ERROR;
		}
		
		calcTransformed();
		calcPublished();
		return super.input();
	}	
	
}