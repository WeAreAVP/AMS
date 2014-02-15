package gr.ntua.ivml.mint.actions;

import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.persistent.DataUpload;
import gr.ntua.ivml.mint.persistent.Organization;
import gr.ntua.ivml.mint.persistent.User;

import java.util.ArrayList;
import java.util.List;

import org.apache.log4j.Logger;
import org.apache.struts2.convention.annotation.Action;
import org.apache.struts2.convention.annotation.Result;
import org.apache.struts2.convention.annotation.Results;

@Results({
	  @Result(name="input", location="summary.jsp"),
	  @Result(name="error", location="summary.jsp"),
	  @Result(name="success", location="summary.jsp" )
	})

public class ImportSummary extends GeneralAction {
	public static final Logger log = Logger.getLogger(ImportSummary.class );
	
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
	
	
	@Action("ImportSummary")
	public String execute() {
		Organization o = user.getOrganization();
		// you are allowed to view nothing
		if( o == null ) return "success";
		
		if( user.can( "view data", user.getOrganization() ))
			return "success";
		else 
			throw new IllegalAccessError( "No rights" );
	}
	
}
