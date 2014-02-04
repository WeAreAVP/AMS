
package gr.ntua.ivml.mint.actions;

import java.util.List;
import java.util.Date;
import java.util.Locale;
import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.Map;


import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.persistent.Organization;
import gr.ntua.ivml.mint.persistent.User;
import gr.ntua.ivml.mint.util.Config;
import gr.ntua.ivml.mint.util.MailSender;

import org.apache.log4j.Logger;
import org.apache.struts2.convention.annotation.Action;
import org.apache.struts2.convention.annotation.InterceptorRef;
import org.apache.struts2.convention.annotation.Result;
import org.apache.struts2.convention.annotation.Results;
import org.apache.struts2.interceptor.SessionAware;

@Results({
	  @Result(name="input", location="register.jsp"),
	  @Result(name="error", location="register.jsp"),
	  @Result(name="success", location="Home.action", type="redirectAction" )
	})


public class Register extends GeneralAction implements SessionAware{

	protected final Logger log = Logger.getLogger(getClass());
	
    private String username;
    private String password;
    private String passwordconf;
    private String email;
    private String tel;
    private String firstName;
    private String lastName;
    public String jobrole;
    public long orgsel;
    private List<Organization> orgs;
    private Map session;
    public Boolean joinDefault = Boolean.FALSE;

       
    @Action(value="Register",interceptorRefs=@InterceptorRef("defaultStack"))
    public String execute() throws Exception {
    	try{
    	
    	User user=new User();
    	if(DB.getUserDAO().isLoginAvailable(getUsername())==false){
    		log.debug( "login in use" );
    		addActionError("login already in use");
    		return ERROR;
    	}
    	user.setAccountActive(true);
		user.setEmail(getEmail());
		user.setFirstName(getFirstName());
		user.setLastName(getLastName());
		user.encryptAndSetLoginPassword(getUsername(), getPassword());
        user.setJobRole(getRole());	
        java.util.Date ucreated=new java.util.Date();
        user.setAccountCreated(ucreated);
        Organization o=DB.getOrganizationDAO().findById(getOrgsel(), false);
        String result="";
        if(isDefaultOrg()) {
        		user.setRights(User.MODIFY_DATA);
        		user.setOrganization(DB.getOrganizationDAO().findById(getDefaultOrg(), false));
//    	        result="You have registered for the default test organization. Currently you have no rights for that organization. An email has been sent to the organization administrator to give you the appropriate rights.";
        } else if(getOrgsel()!=0 &&  o!=null){
        	
	        //user.setMintRole("data viewer");
        	user.setRights(User.NO_RIGHTS);
	        user.setOrganization(DB.getOrganizationDAO().findById(getOrgsel(), false));
	        MailSender ms=new MailSender();
	        String text="<BR>A user with login:<b>"+ user.getLogin()
	                    +"</b> has registered for oganization <b>"+ o.getName()+"</b>.<BR><BR> By default this user " +
	                    "has no rights within the organization. If you would like to grant him rights " +
	                    "please use the administration page from the " + Config.get("mint.title") + " webtool.";
	        String mail_to="";
	        if(DB.getOrganizationDAO().findById(getOrgsel(), false).getPrimaryContact()!=null){
	         mail_to=o.getPrimaryContact().getEmail();
		    }
		    else if(DB.getOrganizationDAO().findById(getOrgsel(), false).findAdmin()!=null)
		    {
		    	mail_to=o.findAdmin().getEmail();
		    }
		    else{
		    	addActionError("Could not find an administrator for the organization you are trying to register. Try again later or register for a different organization.");
	    		return ERROR;
		    	
		    }
	        result=ms.send(ms.adminmail, Config.get("mint.title") + " - new user registration", text, mail_to);
	        if(result.indexOf("Error")>-1){
	        	addActionError("Email could not be sent to the organization's administrator. Please try again later.");
   		        return ERROR;}
	        
	        result="You have registered for a " + Config.get("mint.title") + " organization. Currently you have no rights for that organization. An email has been sent to the organization administrator to give you the appropriate rights.";
	       
        }
        else{user.setMintRole("admin");
             log.debug( "setting role to admin with no org" );
             result="You have not registered for an organization. If you would like to create a new organization please use the administration page.";}
       	DB.getUserDAO().makePersistent(user);
        getSession().put("user", user);
        getSession().put("regresult", result);
    	}
    	catch(Exception ex){
    		log.debug( "exception thrown:"+ex.getMessage() );
    		addActionError(ex.getMessage());
    		ex.printStackTrace();
    		 return ERROR;
    	}
        return SUCCESS;
      }
    
    @Override
    @Action(value="Register_input",interceptorRefs=@InterceptorRef("defaultStack"))  
    public String input() throws Exception {
    	return super.input();
    }
    
    private Boolean isDefaultOrg() {
    		return ((this.getDefaultOrg() != 0) && (this.getOrgsel() == this.getDefaultOrg()));
    }

    public String getUsername() {
    	
        return username;
    }

    public void setUsername(String username) 
    {  
        this.username = username;
    }
    
    public String getJobrole() {
        return jobrole;
    }
    
    public Boolean getJoinDefault() {
    		return joinDefault;
    }
    
    public void setJoinDefault(Boolean b) {
    		this.joinDefault = b;
    }

    public void setJobrole(String jobrole) {
        this.jobrole = jobrole;
    }


    public String getTel() {
        return tel;
    }

    public void setTel(String tel) {
        this.tel = tel;
    }
    
    public void setOrgsel( long orgsel ) {
		this.orgsel = orgsel;
	}
	
	public long getOrgsel() {
		if(joinDefault) return getDefaultOrg();
		return orgsel;
	}
	
	public long getDefaultOrg() {
		this.getOrgs();
		String defaultOrganizationName = Config.get("useDefaultOrganization");
		if(defaultOrganizationName == null || defaultOrganizationName.length() == 0) {
			return 0;
		}
		
		for(Organization o: orgs) {
			if((o.getName() != null) && (o.getName().equalsIgnoreCase(defaultOrganizationName))) {
				return o.dbID;
			}
		}
		
		return 0;
	}
    	
    public String getEmail() {
        return email;
    }

    public void setEmail(String email) {
        this.email = email;
    }
    
    public String getFirstName() {
        return firstName;
    }

    public void setFirstName(String fname) {
        this.firstName = fname;
    }
    
    public String getLastName() {
        return lastName;
    }

    public void setLastName(String lname) {
        this.lastName = lname;
    }
    
    public String getRole() {
        return jobrole;
    }

    public void setRole(String role) {
        this.jobrole = jobrole;
    }
    
    
    public String getPassword() {
        return password;
    }

    public void setPassword(String password) {
        this.password = password;
    }
    
    public String getPasswordconf() {
        return passwordconf;
    }

    public void setPasswordconf(String passwordconf) {
    	
        this.passwordconf = passwordconf;
    }
    

    public List getOrgs() {
    	orgs =DB.getOrganizationDAO().findAll();

        return(orgs);
    }
    
    public void setSession(Map session) {
        this.session = session;
      }
      
      public Map getSession() {
        return session;
      }
}