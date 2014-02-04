
package gr.ntua.ivml.mint.actions;



import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.mapping.MappingSummary;
import gr.ntua.ivml.mint.persistent.Lock;
import gr.ntua.ivml.mint.persistent.Mapping;


import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import org.apache.struts2.interceptor.ServletRequestAware;
import org.apache.struts2.interceptor.ServletResponseAware;
import org.apache.log4j.Logger;
import org.apache.struts2.convention.annotation.Action;




public class LockRelease extends GeneralAction  implements 
ServletRequestAware,ServletResponseAware{
	  
	  private HttpServletRequest request;
	  private HttpServletResponse response;
	  protected final Logger log = Logger.getLogger(getClass());

	  private long lockId;
		 
	  private long mapping;
			
	  
	  public void setServletRequest(HttpServletRequest request){
	    this.request = request;
	  }

	  public HttpServletRequest getServletRequest(){
	    return request;
	  }

	  public void setServletResponse(HttpServletResponse response){
	    this.response = response;
	  }

	  public HttpServletResponse getServletResponse(){
	    return response;
	  }

	
	public long getLockId() {
		return lockId;
	}

	public void setLockId(long lockId) {
		this.lockId = lockId;
	}
	
	
	public void setMapping(long mapping) {
		this.mapping = mapping;
	}

	public long getMapping(){
		return mapping;
	}
	
	

	
	@Action(value="LockRelease")
    public String execute() throws Exception {
		    boolean res=false;
		    
			Mapping em=DB.getMappingDAO().findById(getMapping(), false);
				
			if(em.getJsonString()!=null){	
				
				if(em.getJsonString().isEmpty()==false && (MappingSummary.getMissingMappings(em)==null || MappingSummary.getMissingMappings(em).size()==0)){
	    		 em.setFinished(true);
			     }
			    else{em.setFinished(false);}
			}else{em.setFinished(false);}  
			DB.getMappingDAO().makePersistent(em);
			DB.commit();
			Lock l=DB.getLockManager().getByDbID(getLockId());
			res=DB.getLockManager().releaseLock(l);
			
			return res+"";
    }

	
}