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
	  @Result(name="input", location="report.jsp"),
	  @Result(name="error", location="report.jsp"),
	  @Result(name="success", location="report.jsp" )
	})

public class ReportSummary extends GeneralAction {
	public static final Logger log = Logger.getLogger(ReportSummary.class );
	private List<String> countries=new ArrayList<String>();
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
	
	
	public List<String> getCountries(){
		countries = new ArrayList<String>(java.util.Arrays.asList("Austria", "Belgium", "Bulgaria","Cyprus", "Czech Rep.", "Denmark", "Estonia",
				"Finland", "France", "Germany", "Greece","Hungary", "Ireland", "Israel", "Italy", "Latvia",
				  "Lithuania","Luxembourg","Malta","Netherlands","Poland","Portugal",
				"Romania","Russia","Slovakia","Slovenia",
				"Spain","Sweden","Switzerland","United Kingdom","Europe","International"
					));
		return countries;
	}
	
	@Action("ReportSummary")
	public String execute() {
		Organization o = user.getOrganization();
		return "success";
	}
	
}
