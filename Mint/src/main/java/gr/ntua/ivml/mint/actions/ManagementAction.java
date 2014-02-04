package gr.ntua.ivml.mint.actions;


import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.persistent.Organization;
import gr.ntua.ivml.mint.persistent.User;
import gr.ntua.ivml.mint.util.Config;
import gr.ntua.ivml.mint.util.MailSender;

import java.util.List;
import java.util.ArrayList;
import org.apache.log4j.Logger;
import org.apache.struts2.convention.annotation.Action;
import org.apache.struts2.convention.annotation.Result;
import org.apache.struts2.convention.annotation.Results;

import com.opensymphony.xwork2.Preparable;



@Results({
	  @Result(name="input", location="management.jsp"),
	  @Result(name="error", location="management.jsp"),
	  @Result(name="success", location="management.jsp"),
	  @Result(name="redirect", location="${url}", type="redirectAction" )
	})


public class ManagementAction extends GeneralAction implements Preparable {
	  
	//private static final long serialVersionUID = 1L;
	  
	  protected final Logger log = Logger.getLogger(getClass());
	
	  private List<Organization> orgs;
	  private List<User> users;
	
	  private String url;
	  private User seluser;
	  private Organization selorg;
	  private String uaction;
	  private String id;
	  private List<Organization> allOrgs;
	  private List<Organization> connOrgs;
	  private List<User> adminusers;
      private Boolean notice;
      private String password; //for password reset
      private String passwordconf;
      private String primaryuser;
      private String parentorg;
      private String actionmessage;
     
      private String orgid;
     
	  public void prepare() {
		  if(getUaction()!=null && getUaction().indexOf("save")>-1){
			    if( seluser != null && seluser.getDbID() != null) {
				  seluser = DB.getUserDAO().findById(seluser.getDbID(), false );
				  log.debug( "Prepared better seluser" );
			  }
			    if( selorg != null && selorg.getDbID()>0) {
			    		//&& selorg.dbID() != null) {
					  selorg = DB.getOrganizationDAO().findById(selorg.getDbID(), false );
					  log.debug( "Prepared better selorg" );
				  }
		  }
	  }
	  
	   @Action(value="Management")
	    public String execute() throws Exception {
		   try{
			     if(getUaction().equalsIgnoreCase("showuser") || getUaction().equalsIgnoreCase("edituser")){
			    	
		    	  seluser=DB.getUserDAO().findById(Long.parseLong(getId()), false);
		        }
			    else if(getUaction().equalsIgnoreCase("createuser")){
			    	seluser=new User();
			     }
			    else if(getUaction().equalsIgnoreCase("createorg")){
			    	selorg=new Organization();
			     }
			    else if(getUaction().equalsIgnoreCase("saveuser")){
			    	String emailtext="";
			    	if(getOrgid()!=null && !getOrgid().equalsIgnoreCase("0")){
						seluser.setOrganization(DB.getOrganizationDAO().findById(Long.parseLong(getOrgid()), false));}
					/*if(seluser.getMintRole().equalsIgnoreCase("superuser")){
						seluser.setOrganization(null);
					}*/
					
					validateUser();
			   	if(!getFieldErrors().isEmpty()){
			    		DB.getSession().evict(seluser);
						return ERROR;			    		
			    	}
			    	if( getPassword()!= null && getPassword().length()>0) {
						seluser.setNewPassword(getPassword());
						if(getNotice()==true){
							if(seluser.getDbID()==null){
								emailtext="A new user account has been created for you. <BR>"+
								"Login:"+seluser.getLogin()+"<BR>Password:"+getPassword()+
								"<BR><BR>If you have any questions about your account please contact "+user.getEmail();
							}
							else{
								emailtext="Your account password for the " + Config.get("mint.title") + " system has been changed to "+
											getPassword()+
											".<BR><BR>If you have any questions about your account please contact "+user.getEmail();
								
							}
						}
					}
			    	if(seluser.getDbID()!=null && emailtext.length()==0 && getNotice()==true){
			    		log.debug("making email text");
						emailtext+="<BR>Your " + Config.get("mint.title") + " account was updated by an administrator of your organization."+
						"<BR><BR>If you have any questions about your account please contact "+user.getEmail();
					}
					if(emailtext.length()>0 && getNotice()==true){
						//sending email
						MailSender ms=new MailSender();
						String result=ms.send(ms.adminmail, Config.get("mint.title") + " - User account", emailtext, seluser.getEmail());
						log.debug("EMAIL RESULT:"+result);
				        if(result.indexOf("Error")>-1){
				        	addActionError("Email notice could not be sent to user. Please try again later.");
				        	DB.getSession().evict(seluser);
			   		        return ERROR;}
					}
					seluser.setAccountActive(true);	
					
				    if(seluser.getDbID()==null){
				    	seluser.setAccountCreated(new java.util.Date());
				    	
				    }
					DB.getUserDAO().makePersistent(seluser);
					DB.getSession().evict(seluser);
					setUaction("showuser");
					setActionmessage("User details successfully saved");
		       }// end save user
			    else if(getUaction().equalsIgnoreCase("deluser")){
			     	  seluser=DB.getUserDAO().findById(Long.parseLong(getId()), false);
			     	  
			 		  boolean success=DB.getUserDAO().makeTransient(seluser);
			    	  if(success){
			    			setActionmessage("User was successfully deleted");
			    	  }
			    	  else{
			    		  setActionmessage("User could not be deleted");
			    		  refreshUser();
			    	  }
			        }
			    else if(getUaction().equalsIgnoreCase("showorg") || getUaction().equalsIgnoreCase("editorg")){
			    	
			    	  selorg=DB.getOrganizationDAO().findById(Long.parseLong(getId()), false);
			    	  
			        }
			    else if(getUaction().equalsIgnoreCase("saveorg")){
			    	validateOrg();
			    	
			    	if(!getFieldErrors().isEmpty()){
			    		if(selorg.getDbID()>0){
			    		DB.getSession().evict(selorg);
			    		selorg=DB.getOrganizationDAO().findById(selorg.getDbID(), false );}
						return ERROR;
			    		
			    	}
			    	if(!parentorg.equalsIgnoreCase("0")){
						  selorg.setParentalOrganization(DB.getOrganizationDAO().findById(Long.parseLong(parentorg), false));
					  }
			    	else{selorg.setParentalOrganization(null);}
			    	if(!getPrimaryuser().equalsIgnoreCase("0")){
			    		User pu=DB.getUserDAO().findById(Long.parseLong(getPrimaryuser()), false);
						if(pu!=null){
						   selorg.setPrimaryContact(pu);
						 }
			    	}
			    	if(selorg.getUsers().size()==0){
			    		List<User> u=new ArrayList<User>();
			    		u.add(user);
			    		selorg.setUsers(u);
			    	}
			    	selorg=DB.getOrganizationDAO().makePersistent(selorg);DB.commit();
			    	if(user.getOrganization()==null && (!user.hasRight(User.SUPER_USER))){
			    		user.setOrganization(selorg);
			    		user.setMintRole("admin");
			    	}
			    	DB.commit();
			    	setUaction("showorg");
					setActionmessage("Organization details successfully saved");
		       }// end save user
			    else if(getUaction().equalsIgnoreCase("delorg")){
			     	  selorg=DB.getOrganizationDAO().findById(Long.parseLong(getId()), false);
			     	  boolean success=false;
			     	  //del org if no dependent orgs and no users attached to it
			     	  if(selorg.getDependantOrganizations().size()==0 && (selorg.getUsers().size()==0 || ( selorg.getUsers().size()==1 && user.getOrganization()==selorg))){
			     	    success=DB.getOrganizationDAO().makeTransient(selorg);}
			    	  if(success){
			    		  if(user.getOrganization()!=null && selorg==user.getOrganization()){
			    		    user.setOrganization(null);
			    		    DB.commit();}
			    			setActionmessage("Organization was successfully deleted");
			    	  }
			    	  else{
			    		  refreshUser();
			    		  setActionmessage("Organization could not be deleted. To be able to delete this organization you should first delete all its data, all the children organizations and all it's users.");
				    	    
			    	  }
			        } 
		   }catch(Exception ex){
			   log.debug(ex.getMessage());
	    		addActionError(ex.getMessage());
	    		return ERROR;
	    	}
	        return SUCCESS;
	      }


	   
	  public List<Organization> getOrgs() {
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
	
	  public List<Organization> getConnOrgs() {
			if( user.getOrganization() == null ) 
		   connOrgs =DB.getOrganizationDAO().findAll();
  		else{
  		   
  			
  		   connOrgs = user.getAccessibleOrganizations();
  		   // if the parent org is not in list change the list
  		   if(selorg.getDbID()!=0 && selorg.getParentalOrganization()!=null && (uaction.equalsIgnoreCase("showorg") || uaction.equalsIgnoreCase("editorg") || uaction.equalsIgnoreCase("saveorg")) && !connOrgs.contains(selorg.getParentalOrganization()))
		   {   Organization porg=user.getOrganization().getParentalOrganization();
			   connOrgs.removeAll(connOrgs);
			   Organization temporg=porg;
			   
			   while(temporg.getParentalOrganization()!=null){
				   temporg=temporg.getParentalOrganization();
				}
			   connOrgs.add(temporg);
			   connOrgs.addAll(temporg.getDependantRecursive());
			   
			   
		   }
  		}
  		
		  if(selorg.getDbID()!=0 && (uaction.equalsIgnoreCase("editorg") || uaction.equalsIgnoreCase("saveorg"))){
				//if an org is selected remove it from list    
		        //and it's children       
		         // connOrgs.remove(selorg.getDependantRecursive()); this does not work(?)
		         for(int i=0;i<selorg.getDependantRecursive().size();i++){
		        	 connOrgs.remove(selorg.getDependantRecursive().get(i));
		         }
		         connOrgs.remove(selorg);
			        	
	    	  }
		    
	     return(connOrgs);
	  }
	  
	  
	  public List<Organization> getAllOrgs() {
		  if(user.hasRight(User.SUPER_USER) || ( user.getOrganization() == null )){
	    	allOrgs =DB.getOrganizationDAO().findAll();
		   
	     }
	      else{
	    	 
	    	  Organization org=user.getOrganization();
	    	  allOrgs=new java.util.ArrayList();
	    	  allOrgs.add(org);
	    	 
	    	  List<Organization> depOrgs=org.getDependantRecursive();
		      allOrgs.addAll(depOrgs);
		      
	      }
		  if(uaction.equalsIgnoreCase("editorg")){
	    		//exclude org that is edited   
	    	    allOrgs.remove(getSelorg());
	    	    for(int i=0;i<selorg.getDependantRecursive().size();i++){
	    	    	allOrgs.removeAll(selorg.getDependantRecursive());
	    	    }
	      }
	        return(allOrgs);
	  }
	  
	  public String getActionmessage(){
		  return(actionmessage);
		  
	  }
	  
	  public void setActionmessage(String message){
		  this.actionmessage=message;
		  
	  }
	  
	  public String getPrimaryuser(){
		  return(primaryuser);
		  
	  }
	  
	  public void setPrimaryuser(String prid){
		 primaryuser=prid;
	  }
	  
	  public List<User> getAdminusers(){
		  adminusers=new java.util.ArrayList();
		  if(user.hasRight(User.SUPER_USER)){
			 	users=DB.getUserDAO().findAll();
			 	for(User nu:users){
			 		if(nu.hasRight(User.SUPER_USER) || nu.hasRight(User.ADMIN)){
			 			adminusers.add(nu);}
			 	}
			 	
		  }	
			  else{
		    	  Organization org=user.getOrganization();
		    	  if(org!=null){
		    		
		    	     users=org.getUsers();
		    	     
		    	     for(int i=0;i<org.getDependantOrganizations().size();i++){
		    	    	 //only put users once
		    	    	   Organization temporg=org.getDependantOrganizations().get(i);
		    	    	   for(int j=0;j<temporg.getUsers().size();j++){
		    	    		   User tempu=temporg.getUsers().get(j);
		    	    		   if(!users.contains(tempu)){users.add(tempu);}
			    	    
			    	    	
		    	    	   }
			    	    }
		    	     for(User nu:users){
					 		if(nu.hasRight(User.SUPER_USER) || nu.hasRight(User.ADMIN)){
					 			adminusers.add(nu);}
					 	}
		    	     
		    	  }
		    	  else {
		    		
		    		  users=new java.util.ArrayList();
		    		  users.add(user);
		    		  adminusers=users;
		    		 
		    	  }
			  }
		        return(adminusers);
	  }
	  
	  public List getUsers() {
		 
		  if(user.hasRight(User.SUPER_USER))
		 	users=DB.getUserDAO().findAll();
		  else{
	    	  Organization org=user.getOrganization();
	    	  if(org!=null){
	    		
	    	     users=org.getUsers();
	    	     
	    	     for(int i=0;i<org.getDependantOrganizations().size();i++){
	    	    	 //only put users once
	    	    	   Organization temporg=org.getDependantOrganizations().get(i);
	    	    	   for(int j=0;j<temporg.getUsers().size();j++){
	    	    		   User tempu=temporg.getUsers().get(j);
	    	    		   if(!users.contains(tempu)){users.add(tempu);}
		    	    
		    	    	
	    	    	   }
		    	    }
	    	     
	    	  }
	    	  else {
	    		
	    		  users=new java.util.ArrayList();
	    		  users.add(user);
	    		 
	    	  }
		  }
	        return(users);
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
	  
	  public User getSeluser()
	  {
		  return seluser;
	  }
	  
	  
	  public void setSeluser(User u) {
		   if(u!=null){
			   seluser=u;
		   }
		
		  log.debug("SETTING SELUSER to "+seluser.getLogin());
	  }

	  public void setParentorg(String parentorg)
	  {   this.parentorg=parentorg;
		  
	  }
	  
	  public Organization getSelorg()
	  {
		  return selorg;
	  }
	  
	  
	  public void setSelorg(Organization o) {
		  log.debug("SETTING SELORG");
		  if(o!=null){
		    selorg=o;
		  }
	  }

	  public Boolean getNotice() {
	        return this.notice;
	    }

	  public void setNotice(Boolean notice) {
		  log.debug("SETTING notice:"+notice);
	        this.notice=notice;
	    }

	  
	  public String getPassword() {
	        return password;
	    }

	    public void setPassword(String password) {
	        this.password = password;
	    }
	  
	  public String getUaction()
	  {
		  return uaction;
	  }
	  
	  public void setUaction(String uaction){
		  this.uaction=uaction;
		  log.debug("action set to:"+uaction);
	  }

	  public String getId()
	  {
		  return id;
	  }
	  
	  public void setId(String id){
		  this.id=id;
		  log.debug("id set to:"+this.id);
		  
	  }
	  
	  public String getPasswordconf() {
	        return passwordconf;
	    }

	    public void setPasswordconf(String passwordconf) {
	    	
	        this.passwordconf = passwordconf;
	    }
	    
	  
	  public void setUrl(String url)
	  {
		  this.url=url;
	  }

	  
	  @Action("Management_input")
		@Override
		public String input() throws Exception {
	    	if( !user.hasRight(User.ADMIN) && !user.hasRight(User.SUPER_USER) && !(user.hasRight(User.NO_RIGHTS) && user.getOrganization()==null)) {
	    		throw new Exception( "No administration rights! You have no access to this area." );
	    		
	    	}
			return super.input();
		}
		
	  
	  public void validateOrg(){
		    if(selorg.getCountry()==null || selorg.getCountry().length()==0){
				addFieldError("selorg.country","Organization country is required");
			}
			if(selorg.getOriginalName()==null || selorg.getOriginalName().length()==0){
				addFieldError("selorg.originalName","Organization name is required");
			}
			if(selorg.getEnglishName()==null || selorg.getEnglishName().length()==0){
				addFieldError("selorg.englishName","Organization english name is required");
			}
			if(selorg.getType()==null || selorg.getType().length()==0){
				addFieldError("selorg.type","Organization type name is required");
			}
			if(getPrimaryuser()==null || getPrimaryuser().equalsIgnoreCase("0")){
				addFieldError("primaryuser","Primary contact user is required");
			}
			
			
		 }

		 
		 public void validateUser(){
			 if(seluser.getLogin()==null || seluser.getLogin().length()==0){
					addFieldError("seluser.login","Login is required");
				}
	    	 if( (uaction.equalsIgnoreCase("edituser") || uaction.equalsIgnoreCase("saveuser")) && (seluser.getDbID()!=null)) {
	    		 User exi=DB.getUserDAO().findById(seluser.getDbID(), false);
	 	    	
		    	 if(!exi.getLogin().equalsIgnoreCase(seluser.getLogin())){
		        	//check if new login available
		        	if(!DB.getUserDAO().isLoginAvailable(seluser.getLogin())){
		        		
		        		addFieldError("seluser.login","login already in use");
		        	}
	        	
	            }
	    	 }
	    	 else if(uaction.equalsIgnoreCase("saveuser") && seluser.getDbID()==null) {
	    		
		        	//check if new login available
		        	if(!DB.getUserDAO().isLoginAvailable(seluser.getLogin())){
		        		
		        		addFieldError("seluser.login","login already in use");
		        	}
	        	
	            
	    	 }
	    	 if( uaction.equalsIgnoreCase("saveuser") && seluser.getDbID()==null && password.length()==0) {
	    		 
	    		 addFieldError("password","Password is required");
	    	 }
	    	if( password.length()>0) {//trying to reset password
	    		if( password.length()<5) {
		    		addFieldError("password","Password must be at least 5 characters long");
		    	
		    	}
				if( passwordconf==null || passwordconf.length()==0) {
		    		addFieldError("passwordconf","Password confirmation is required");
		    	
		    	}
				else if(!passwordconf.equalsIgnoreCase(password)) {
		    		addFieldError("passwordconf","Password confirmation and password must match");
		    	
		    	}
				
	    	}
			if(seluser.getFirstName()==null || seluser.getFirstName().length()==0){
				addFieldError("seluser.firstName","First name is required");
			}
			if(seluser.getLastName()==null || seluser.getLastName().length()==0){
				addFieldError("seluser.lastName","Last name is required");
			}
			if(seluser.getEmail()==null || seluser.getEmail().length()==0){
				addFieldError("seluser.email","Email is required");
			}
			else if(seluser.getEmail().indexOf("@")==-1 || seluser.getEmail().indexOf(".")==-1){
				addFieldError("seluser.email","Valid email is required");
			}
			if(seluser.getMintRole()==null || seluser.getMintRole().length()==0){
				addFieldError("seluser.mintRole","Specify a user role in this project");
			}
			if(getOrgid()==null || getOrgid().equalsIgnoreCase("0")){
				addFieldError("orgid","Specify the user's organization");
			}
			
		 }

}
