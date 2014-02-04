package gr.ntua.ivml.mint.actions;

import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.persistent.Organization;
import gr.ntua.ivml.mint.persistent.User;
import gr.ntua.ivml.mint.util.Config;
import gr.ntua.ivml.mint.util.MailSender;

import java.util.List;
import java.util.Map;

import org.apache.log4j.Logger;
import org.apache.struts2.convention.annotation.Action;
import org.apache.struts2.convention.annotation.Result;
import org.apache.struts2.convention.annotation.Results;
import org.apache.struts2.interceptor.SessionAware;

import com.opensymphony.xwork2.ActionSupport;
import com.opensymphony.xwork2.Preparable;



@Results({
	  @Result(name="input", location="profile.jsp"),
	  @Result(name="error", location="profile.jsp"),
	  @Result(name="success", location="profile.jsp"),
	  @Result(name="redirect", location="${url}", type="redirectAction" )
	})


public class Profile extends GeneralAction implements Preparable, SessionAware{
	  
	//private static final long serialVersionUID = 1L;
	  
	  protected final Logger log = Logger.getLogger(getClass());
	
	  private List<Organization> orgs;
	
	  private String url;
	  private User current_user;
	  private String uaction;
	 
	  private List<Organization> allOrgs;
	  
      private String pass; //for password reset
      private String passconf;
      private String actionmessage;
      private String id;
      private Map session;

     
      private String orgid;
     
	  public void prepare() {
		  //current_user = DB.getUserDAO().findById(current_user.getDbID(), false );
		  if(getId()!=null){
			    
				  current_user = DB.getUserDAO().findById(Long.parseLong(getId()), false );
				  log.debug( "Prepared better current_user" );
			  }
			  
		  
	  }
	  
	   @Action(value="Profile")
	    public String execute() throws Exception {
		   try{
			   String result="";
			    if(getUaction()==null || getUaction().equalsIgnoreCase("edituser")){
				  // current_user=DB.getUserDAO().findById(user.getDbID(), false );;
			    	current_user = user;
		         }			
			    else if(getUaction().equalsIgnoreCase("saveuser")){
					validateUser();
					if(!getFieldErrors().isEmpty()){
						DB.getSession().evict(current_user);
						return ERROR;			    		
			    	}
					/*if(current_user.getMintRole().equalsIgnoreCase("superuser")){
						current_user.setOrganization(null);
					}
					else*/ if(user.getOrganization()!=null && user.getOrganization().getDbID()!=Long.parseLong(getOrgid())){
						Organization og=user.getOrganization();
						//user cant leave org if he is the only admin
						
						//to do: if user is admin and no admin is left for the org return error
						if(user.getMintRole().equalsIgnoreCase("admin") && user.getOrganization().getPrimaryContact()==user){
							addActionError("Error while changing the user's organization. You are the primary contact for the existing organisation.");
							DB.getSession().evict(current_user);
							
				    		return ERROR;
						}
						else if(og!=null && user.getMintRole().equalsIgnoreCase("admin") && og.getAdmincount()<=1){
							addActionError("Error while changing the user's organization. You are the only admin for the existing organization so you cant leave the organization.");
							DB.getSession().evict(current_user);
							
				    		return ERROR;
						}
						
						//if user has data uploaded he cant change his org
						if(user.getUploads().size()>0){
							addActionError("You cannot alter your organization since you have commited data items. Prior to changing your organization you must delete all your data uploads.");
							DB.getSession().evict(current_user);
							
				    		return ERROR;
				
							
						}
						Organization o=DB.getOrganizationDAO().findById(Long.parseLong(getOrgid()), false);
						
					    //send email to org admin;
					    MailSender ms=new MailSender();
				        String text="A user with login:"+ user.getLogin()
				                    +" has registered for oganization "+ o.getName()+". By default this user " +
				                    "has only viewing rights within the organization. \n\nIf you would like to alter his rights " +
				                    "please use the administration page from the " + Config.get("mint.title") + " tool to set them.";
				        String mail_to="";
				        if(o.getPrimaryContact()!=null){
				         mail_to=o.getPrimaryContact().getEmail();
					    }
					    else if(o.findAdmin()!=null)
					    {
					    	mail_to=o.findAdmin().getEmail();
					    }
					    else{
					    	addActionError("Could not find an administrator for the organization you are trying to register. Try again later or register for a different organization.");
					    	DB.getSession().evict(current_user);
							return ERROR;
					    	
					    }
				        result=ms.sendToMany(ms.adminmail, Config.get("mint.title") + " - new user registration", text, mail_to, current_user.getEmail());
					    if(result.indexOf("Error")>-1){
					    	DB.getSession().evict(current_user);
					    	addActionError("Error while changing the user's organization. Could not send email to organization's administrator. Please try again later.");
				    		return ERROR;
					    }
					    else{
					    	current_user.setOrganization(o);
						    current_user.setRights(User.NO_RIGHTS);	
					    }
					}
					else if(user.getOrganization()==null && !getOrgid().equalsIgnoreCase("0")){
						Organization o=DB.getOrganizationDAO().findById(Long.parseLong(getOrgid()), false);
					
					    //send email to org admin;
					    MailSender ms=new MailSender();
				        String text="A user with login:"+ user.getLogin()
				                    +" has registered for oganization "+ o.getName()+". By default this user " +
				                    "has only viewing rights within the organization. \n\nIf you would like to alter his rights " +
				                    "please use the administration page from the " + Config.get("mint.title") + " webtool to set them.";
				        String mail_to="";
				        if(o.getPrimaryContact()!=null){
				         mail_to=o.getPrimaryContact().getEmail();
					    }
					    else if(o.findAdmin()!=null)
					    {
					    	mail_to=o.findAdmin().getEmail();
					    }
					    else{
					    	addActionError("Could not find an administrator for the organization you are trying to register. Try again later or register for a different organization.");
					    	DB.getSession().evict(current_user);
							return ERROR;
					    	
					    }
				        
					    result=ms.sendToMany(ms.adminmail, Config.get("mint.title") + " - new user registration", text, mail_to,current_user.getEmail());
					    if(result.indexOf("Error")>-1){
					    	addActionError("Error while changing the user's organization. Please try again.");
					    	DB.getSession().evict(current_user);
							return ERROR;
					    }
					    else{
					    	current_user.setOrganization(o);
						    current_user.setRights(User.NO_RIGHTS);
					    }
					}
					else{current_user.setOrganization(DB.getOrganizationDAO().findById(Long.parseLong(getOrgid()), false));}
			        
					
					DB.getUserDAO().makePersistent(current_user);
					DB.getSession().evict(current_user);
					getSession().remove("user");
					getSession().put("user", current_user);
					setUaction("showuser");
					if(result.indexOf("Success")>-1){
						   setActionmessage("User details successfully saved. You have chosen to register for a new organization. An email" +
				    		" has been sent to the organization admin to provide you with appropriate rights.");
			     
					}else{
					 setActionmessage("User details successfully saved");
					}
		       }// end save user
			    else if(getUaction().equalsIgnoreCase("savepass")){
			    	validatePass();
					if(!getFieldErrors().isEmpty()){
						
						return ERROR;			    		
			    	}
					if( getPass()!= null && getPass().length()>0) {
						current_user.setNewPassword(getPass());
					}
					
					
					DB.getUserDAO().makePersistent(current_user);
					DB.getSession().evict(current_user);
					getSession().remove("user");
					getSession().put("user", current_user);
					setUaction("showuser");
					
					setActionmessage("User password successfully altered");
					
			    }
		   }catch(Exception ex){
			   log.debug(ex.getMessage());
	    		addActionError(ex.getMessage());
	    		return ERROR;
	    	}
	        return SUCCESS;
	      }


	   
	  public List<Organization> getOrgs() {
		  User user=(User)getSession().get("user");
			 
		  if(user.hasRight(User.SUPER_USER))
	    	orgs =DB.getOrganizationDAO().findPrimary();
	      else{
	    	  Organization org=user.getOrganization();
	    	  
	    	  if(org!=null){
	    	   orgs=new java.util.ArrayList();
	    	   orgs.add(org);
	    	  }
	      }
		   return(orgs);
	  }
	
	 
	  
	  public List<Organization> getAllOrgs() {
		
		  if(user.hasRight(User.SUPER_USER) || ( user.getOrganization() == null )){
			  allOrgs =DB.getOrganizationDAO().findAll();

		  }
		  else{

			  Organization org=user.getOrganization();
			  allOrgs=new java.util.ArrayList();
			  log.debug("found dummy org:"+ org.getName());
			  allOrgs.add(org);

			  List<Organization> depOrgs=org.getDependantRecursive();
			  allOrgs.addAll(depOrgs);
		  }
		  return(allOrgs);
	  }
	  
	  public String getActionmessage(){
		  return(actionmessage);
		  
	  }
	  
	  public void setActionmessage(String message){
		  this.actionmessage=message;
		  
	  }
	  
	 
	  public String getId()
	  {   User user=(User)getSession().get("user");
		  return ""+user.getDbID();
	  }
	  
	  public void setId(String id){
		  this.id=id;
		  
	  }
		      
	  public String getUrl()
	  {
		  return url;
	  }
	
	
      
	  public void setOrgid(String orgid){
		  this.orgid=orgid;
	  }
	  
	  public String getOrgid(){
		  return orgid;
	  }
	  
	  public User getCurrent_user()
	  {
		  return current_user;
	  }
	  
	  
	  public void setCurrent_user(User u) {
		 
		  current_user=u;
		  log.debug("SETTING current_user to "+current_user.getLogin());
	  }

	 
	  
	  public String getPass() {
	        return pass;
	    }

	    public void setPass(String pass) {
	        this.pass = pass;
	    }
	  
	  public String getUaction()
	  {
		  return uaction;
	  }
	  
	  public void setUaction(String uaction){
		  this.uaction=uaction;
	  }

	
	  
	  public String getPassconf() {
	        return passconf;
	    }

	    public void setPassconf(String passconf) {
	    	
	        this.passconf = passconf;
	    }
	    
	  
	  public void setUrl(String url)
	  {
		  this.url=url;
	  }

	  
	  @Action("Profile_input")
		@Override
		public String input() throws Exception {
		   User user=(User)getSession().get("user");
			
	       if( user==null) {
	    		throw new Exception( "You have no access to this area." );
	    		
	    	}
	       else if(!user.getMintRole().equalsIgnoreCase("superuser") && user.getOrganization()==null){
	    	   setActionmessage("You are not registered for an organization! Edit your details to join an organization or "
	    			   +"if you will be the admin for one or more organizations use the <a href='Management_input.action'>Administration</a> page to specify them. ");
	    	   
	       }
			return super.input();
		}
		
		 
		 public void validateUser(){
			 User user=(User)getSession().get("user");
			 if(current_user.getLogin()==null || current_user.getLogin().length()==0){
					addFieldError("current_user.login","Login is required");
				}
		    	 if(!user.getLogin().equalsIgnoreCase(current_user.getLogin())){
				    	
		        	//check if new login available
		        	if(!DB.getUserDAO().isLoginAvailable(current_user.getLogin())){
		        		
		        		addFieldError("current_user.login","login already in use");
		        	}
	        	
	            }
	    	
	    	
			if(current_user.getFirstName()==null || current_user.getFirstName().length()==0){
				addFieldError("current_user.firstName","First name is required");
			}
			if(current_user.getLastName()==null || current_user.getLastName().length()==0){
				addFieldError("current_user.lastName","Last name is required");
			}
			if(current_user.getEmail()==null || current_user.getEmail().length()==0){
				addFieldError("current_user.email","Email is required");
			}
			else if(current_user.getEmail().indexOf("@")==-1 || current_user.getEmail().indexOf(".")==-1){
				addFieldError("current_user.email","Valid email is required");
			}
			if(!user.getMintRole().equalsIgnoreCase("superuser") && (getOrgid()==null || getOrgid().equalsIgnoreCase("0"))){
				addFieldError("orgid","Organization is required");
			}
		
			
		 }
		 
		 public void validatePass(){
				if( pass!=null && pass.length()>0) {//trying to reset password
	    		if( pass.length()<6) {
		    		addFieldError("pass","Password must be at least 6 characters long");
		    	
		    	}
	    		if( passconf==null || passconf.length()==0) {
		    		addFieldError("passconf","Password confirmation is required");
		    	
		    	}
				else if(!passconf.equalsIgnoreCase(pass)) {
		    		addFieldError("passconf","Password confirmation and password must match");
		    	
		    	}
				
	    	}
	    	else{addFieldError("pass","Password cannot be empty");}
			
		
			
		 }
		 
		 
	    public void setSession(Map session) {
	        this.session = session;
	      }
	      
	    public Map getSession() {
	        return session;
	      }

}
