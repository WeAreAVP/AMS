
package gr.ntua.ivml.mint.actions;


import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

import gr.ntua.ivml.mint.harvesting.RepositoryValidator;


import org.apache.log4j.Logger;
import org.apache.struts2.convention.annotation.Action;
import org.apache.struts2.convention.annotation.Result;
import org.apache.struts2.convention.annotation.Results;

@Results({
	@Result(name="error", location="oairesponse.jsp"),
	@Result(name="success", location="oairesponse.jsp")
})

public class OAIHandler extends GeneralAction{

	protected final Logger log = Logger.getLogger(getClass());
    
	
    public String oai;
	public String oaiset;
	public String action;
	public String namespace;
	public HashMap<String,String> oaiAllSets = new HashMap<String,String>();
	public List<String> ns = new ArrayList<String>();
	
	
	@Action(value="OAIHandler")
	public String execute() throws Exception {
		log.debug("OAIHandler controller for url:"+oai);	
		if(this.action.equalsIgnoreCase("validate")){
		if(this.getOai()==null || this.getOai().length()==0){
			addActionError("Oai url cannot be empty!");
			return ERROR;
		}
		if(!RepositoryValidator.isValid(this.getOai())){
			addActionError("Oai url is invalid!");
			return ERROR;
		}
		else{
			this.addActionMessage("Oai url is valid!");
			return SUCCESS;
			
		}}
		else if(this.action.equalsIgnoreCase("fetchsets")){
			log.debug("GETTING SETS for oai:"+oai);
			try{
		        oaiAllSets=RepositoryValidator.getSets(oai);
			} catch (Exception e) {
				e.printStackTrace();
			}
		  // log.debug("size of hash:"+oaiAllSets.size());
		   
		   return SUCCESS;
		}
		else if(this.action.equalsIgnoreCase("fetchns")){
			log.debug("GETTING NS for oai:"+oai);
			try{
				ns=RepositoryValidator.getNameSpaces(oai);
		        
			} catch (Exception e) {
				e.printStackTrace();
			}
		  // log.debug("size of hash:"+oaiAllSets.size());
		   
		   return SUCCESS;
		}
		return SUCCESS;
	}
	
	public void setAction(String action){
		this.action=action;
	}

	public String getAction(){
		return this.action;
	}

	public String getOai(){
		return this.oai;
	}

    public void setOai(String oai){
    	this.oai=oai;
    }
    
    public HashMap<String,String> getOaiAllSets(){
      return oaiAllSets;	
    }
	
    public List<String> getNs(){
    	return ns;
    }
	
}