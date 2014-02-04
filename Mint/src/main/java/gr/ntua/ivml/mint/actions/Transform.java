
package gr.ntua.ivml.mint.actions;

import gr.ntua.ivml.mint.concurrent.Queues;
import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.db.LockManager;
import gr.ntua.ivml.mint.mapping.MappingSummary;
import gr.ntua.ivml.mint.persistent.DataUpload;
import gr.ntua.ivml.mint.persistent.Mapping;
import gr.ntua.ivml.mint.persistent.Organization;
import gr.ntua.ivml.mint.persistent.Transformation;
import gr.ntua.ivml.mint.persistent.User;
import gr.ntua.ivml.mint.persistent.XpathHolder;

import java.util.ArrayList;
import java.util.Collection;

import java.util.Date;
import java.util.List;

import org.apache.log4j.Logger;
import org.apache.struts2.convention.annotation.Action;
import org.apache.struts2.convention.annotation.Result;
import org.apache.struts2.convention.annotation.Results;

@Results({
	  @Result(name="input", location="transformselection.jsp"),
	  @Result(name="error", location="transformselection.jsp"),
	  @Result(name="success", location="transformselection.jsp" )
	})

public class Transform extends GeneralAction  {

	protected final Logger log = Logger.getLogger(getClass());
	private long selMapping;
	private long uploadId;
	private Collection<String> missing=new ArrayList<String>();
	private Collection<String> invalid=new ArrayList<String>();
	private String action="";
	private boolean continueInvalid=false;
	private boolean noitem = false;
	
	public List<Mapping> getAccessibleMappings() {
		List<Mapping> maplist= new ArrayList();
        List<Organization> deporgs=user.getAccessibleOrganizations();
        for(Organization org:deporgs){
        	maplist.addAll(DB.getMappingDAO().findByOrganization(org));
        }
        
		return maplist;
	}
	
	public void setAction(String action){
		this.action=action;
	}

	public void setselMapping(long selMapping) {
		this.selMapping = selMapping;
	}

	
	public void setContinueInvalid(boolean continv){
		this.continueInvalid=continv;
	}
	
	public long getselMapping(){
		return selMapping;
	}
	
	public long getUploadId(){
		return uploadId;
	}
	
	public void setUploadId(long uploadId){
		this.uploadId=uploadId;
	}
	
	public Collection<String> getMissing(){
		return this.missing;
	}
	
	public Collection<String> getInvalid(){
		return this.invalid;
	}
	
	
	@Action(value="Transform")
    public String execute() throws Exception {
		log.debug("selMapping"+getselMapping());
            if(this.action.equalsIgnoreCase("delete")){
            	log.debug("delete transfrom request");
            	DataUpload du = DB.getDataUploadDAO().getById(getUploadId(), false);
        		
            	List<Transformation> lt = DB.getTransformationDAO().findByUpload(du);
    			for( Transformation t: lt )
    			DB.getTransformationDAO().makeTransient(t);
    			DB.commit();
    			return SUCCESS;
    			
            }
    		if(this.getselMapping()>0){
    			//do your stuff
    			log.debug("found mapping for transform");
    			
    			// shit locked ?
    			// this is just precaution, locks are checked again when taken out
    			// by offline action
    			Mapping m = DB.getMappingDAO().getById(getselMapping(), false);
    			DataUpload du = DB.getDataUploadDAO().getById(getUploadId(), false);
    			
    			
    			LockManager lm = DB.getLockManager();
    			
    			if(( m==null ) || ( du==null)) {
    				addActionError( "Error!Mapping or Upload missing" );
    				return "error";
    			}
    			
    			if(( lm.isLocked(m) != null ) || ( lm.isLocked( du ) != null )) {
    				addActionError( "Error!Mapping or Upload currently locked. Plase try to transform later." );
    				return "error";
    			}
    			
    			if(m.getJsonString()==null || m.getJsonString().isEmpty()){
    				  addActionError(" The <i>'"+m.getName()+"'</i> mappings you are trying to use for transformation are empty.");
  	      			  return "error";
    			}
    		    
    			missing = MappingSummary.getMissingMappings(m); 
    			invalid = MappingSummary.getInvalidXPaths(du, m);
	    		
    			//check if this import corresponds to mappings
    			if((missing!=null || invalid!=null) && this.continueInvalid==false){
    				//now check if LIDO complete
    				 
    				if(missing!=null && missing.size()>0){
	    				  addActionError(" The <i>'"+m.getName()+"'</i> mappings you are trying to use for transformation are <a href=\"#\" onclick=\"ChangeTabs(0);\"><font color='red'>Missing</font></a> mandatory mappings to LIDO.");
	    				  
	    			}
	    			if(invalid!=null && invalid.size()>0){
	    			    addActionError("The <i>'"+m.getName()+"'</i> mappings you are trying to use for this transformation contain <a href=\"#\" onclick=\"ChangeTabs(1);\"><font color='red'>Invalid</font></a> Xpaths that are not present in this import. ");
	    				
	    			}
	    			if(this.getActionErrors().size()>0){
	    			return "error";}
    			}
    			
    			// remove all old Transformations for the upload
    			// this will be a disaster if one is still running, maybe 
    			// I should check
    			// but the lock on dataupload wouldnt be available :-)
    			log.debug("deleting old transformations");
    			List<Transformation> lt = DB.getTransformationDAO().findByUpload(du);
    			for( Transformation t: lt )
    				DB.getTransformationDAO().makeTransient(t);
    			DB.commit();
    			
    			
    			Transformation tr = new Transformation();    			

    			tr.setBeginTransform(new Date());
    			tr.setStatusCode(Transformation.IDLE);
    			tr.setMapping(m);
    			tr.setJsonMapping(m.getJsonString());
    			tr.setDataUpload(du);
    			tr.setUser(getUser());
    			
    			DB.getTransformationDAO().makePersistent(tr);
    			
    			DB.commit();
    			log.debug("transformations for du:"+DB.getTransformationDAO().findByUpload(du).size());
    			Queues.queueTransformation(tr);
    			return "success";}
    		else{
    			log.debug("no map found");
    			addActionError("Error!Choose the mappings that will be used for this transformation.");
    			return "error";
    		}
    
    	
    }

	public boolean getNoitem() {
		return noitem;
	}	
	
	@Action("Transform_input")
	@Override
	public String input() throws Exception {
		if( (user.getOrganization() == null && !user.hasRight(User.SUPER_USER)) || !user.hasRight(User.MODIFY_DATA)) {
    		log.debug("No transformation rights");
    		throw new IllegalAccessException( "No transformation rights!" );
    	}
		DataUpload du = DB.getDataUploadDAO().getById(getUploadId(), false);
		
		XpathHolder level_xp = du.getItemXpath();
		if (level_xp == null
				|| level_xp.getXpathWithPrefix(false).length() == 0) {
			this.noitem = true;
			addActionError("You must first define the Item Level and Item Label by choosing step 1.");
			return ERROR;
		}
		
		return super.input();
	}	
	
}