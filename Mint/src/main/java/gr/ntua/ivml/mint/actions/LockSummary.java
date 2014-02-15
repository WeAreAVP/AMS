
package gr.ntua.ivml.mint.actions;

import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.persistent.Lock;

import org.apache.log4j.Logger;
import org.apache.struts2.convention.annotation.Action;
import org.apache.struts2.convention.annotation.Result;
import org.apache.struts2.convention.annotation.Results;

import java.util.ArrayList;
import java.util.List;

@Results({
	  @Result(name="input", location="locksummary.jsp"),
	  @Result(name="error", location="locksummary.jsp"),
	  @Result(name="success", location="locksummary.jsp" )
	})

public class LockSummary extends GeneralAction {
	  
	  protected final Logger log = Logger.getLogger(getClass());

	  private List<Lock> locks;
	  private String lockaction="";
	  private ArrayList lockCheck=new ArrayList();

	public void setLockaction(String lockaction) {
		this.lockaction = lockaction;
	}
	
	
	public List getLocks(){
		
		locks = DB.getLockManager().findByUser(this.user);
		List<Lock> newlocks=new ArrayList<Lock>();
		for(Lock l: locks){
			if(l.getHttpSessionId()!=null && l.getHttpSessionId().indexOf("offlineTransformation")==0){
			}else{newlocks.add(l);}
		}
		//locks = DB.getLockManager().findBySession( this.sessionId);
		
		return newlocks;
	}
	
	public void setLockCheck(String lockCheck){
		this.lockCheck=new ArrayList();
		if(lockCheck.trim().length()>0){
			String[] chstr=lockCheck.split(",");
			
		   java.util.Collection c=java.util.Arrays.asList(chstr);
		   this.lockCheck.addAll(c);
		}
	}
	
	
	@Action(value="LockSummary")
    public String execute() throws Exception {
		if(lockaction.equalsIgnoreCase("delete")){
			try{
				
				 for(int i=0;i<lockCheck.size();i++)
				 { 
					 Lock l=DB.getLockManager().getByDbID(Long.parseLong((String)lockCheck.get(i)));
					 boolean res=DB.getLockManager().releaseLock(l);
							
				 }
			}catch (Exception e){}
		}
	    return SUCCESS;
    }

	
}