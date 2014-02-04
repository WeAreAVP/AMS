
package gr.ntua.ivml.mint.actions;

import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.persistent.Organization;

import java.util.ArrayList;
import java.util.List;


import org.apache.log4j.Logger;
import org.apache.struts2.convention.annotation.Action;
import org.apache.struts2.convention.annotation.Result;
import org.apache.struts2.convention.annotation.Results;

@Results({
	  @Result(name="input", location="home.jsp"),
	  @Result(name="error", location="home.jsp"),
	  @Result(name="success", location="home.jsp")
	})

public class Home extends GeneralAction{

	protected final Logger log = Logger.getLogger(getClass());
	
      private List<Organization> allOrgs;
      private List<String> countries=new ArrayList<String>();
      
      @Action(value="Home")
      public String execute() throws Exception {
    	  log.debug("Home controller");
  		return SUCCESS;
      }

      public List<Organization> getAllOrgs(){
      	allOrgs =DB.getOrganizationDAO().findAll();
      	return allOrgs;
      } 
      
      public List<String> getCountries(){
    		countries = new ArrayList<String>(java.util.Arrays.asList("Austria", "Belgium", "Bulgaria","Cyprus", "Czech Rep.", "Denmark", "Estonia",
    				"Finland", "France", "Germany", "Greece","Hungary", "Ireland", "Italy", "Israel", "Latvia",
    				  "Lithuania","Luxembourg","Malta","Netherlands","Poland","Portugal",
    				"Romania","Russia","Slovakia","Slovenia",
    				"Spain","Sweden", "Switzerland", "United Kingdom", "Europe", "International"));
    		return countries;
        }
      
  	
  	String orgId;
  	
  	public String getOrgId() {
  		return orgId;
  	}


  	public void setOrgId(String orgId) {
  		this.orgId = orgId;
  	}


  	public List<Organization> getOrganizations() {
  		return  user.getAccessibleOrganizations();
  	}
}