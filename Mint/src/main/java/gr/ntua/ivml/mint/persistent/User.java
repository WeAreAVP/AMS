package gr.ntua.ivml.mint.persistent;

import gr.ntua.ivml.mint.db.DB;

import java.nio.charset.Charset;
import java.security.MessageDigest;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;

import org.apache.log4j.Logger;

public class User implements SecurityEnabled {
	  protected final Logger log = Logger.getLogger(getClass());
		
	public static final int ADMIN = 0x01;
	public static final int PUBLISH = 0x02;
	public static final int MODIFY_DATA = 0x04;
	public static final int VIEW_DATA = 0x08;
	
	// SU has all the rights (even still to come ..)
	public static final int SUPER_USER = 0x7fffffff;

	public static final int NO_RIGHTS = 0x0;
	// all normal rights
	// default for users without organization
	public static final int ALL_RIGHTS = (ADMIN|PUBLISH|MODIFY_DATA|VIEW_DATA);
	
	public Long dbID;
	public String login;
	public String email;
	public String firstName;
	public String lastName;
	public String md5Password;
	public boolean accountActive;
	public Date passwordExpires;
	public Date accountCreated;
    
	public String jobRole;
	public String workTelephone;
	
	public String company;
	public Organization organization;
	public int rights = NO_RIGHTS;
	
	public Date getAccountCreated() {
		return accountCreated;
	}
	public void setAccountCreated(Date accountCreated) {
		this.accountCreated = accountCreated;
	}
 
	public boolean isAccountActive() {
		return accountActive;
	}
	public void setAccountActive(boolean accountActive) {
		this.accountActive = accountActive;
	}
	public Date getPasswordExpires() {
		return passwordExpires;
	}
	public void setPasswordExpires(Date passwordExpires) {
		this.passwordExpires = passwordExpires;
	}
	public String getJobRole() {
		return jobRole;
	}
	public void setJobRole(String jobRole) {
		this.jobRole = jobRole;
	}
	public String getWorkTelephone() {
		return workTelephone;
	}
	public void setWorkTelephone(String workTelephone) {
		this.workTelephone = workTelephone;
	}
	public Organization getOrganization() {
		return organization;
	}
	public void setOrganization(Organization organization) {
		this.organization = organization;
	}
	public String getCompany() {
		return company;
	}
	public void setCompany(String company) {
		this.company = company;
	}
	public String getLogin() {
		return login;
	}
	private void setLogin(String login) {
		this.login = login;
	}
	public Long getDbID() {
		return dbID;
	}
	public void setDbID(Long dbID) {
		this.dbID = dbID;
	}
	private String getMd5Password() {
		return md5Password;
	}
	private void setMd5Password(String md5Password) {
		this.md5Password = md5Password;
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
	public void setFirstName(String firstName) {
		this.firstName = firstName;
	}
	public String getLastName() {
		return lastName;
	}
	public void setLastName(String lastName) {
		this.lastName = lastName;
	}
	
	public String getName() {
		return getFirstName() + " " + getLastName();
	}
	
	public boolean can( String action, SecurityEnabled se ) {
		return AccessAuthenticationLogic.can( this, se, action );
	}
	
	public boolean can( String action ) {
		return AccessAuthenticationLogic.can( this, null , action );
	}

	public String getMintRole(){
		
		String role="";
		// if(this.getOrganization()==null && !this.hasRight(User.SUPER_USER)){return role;}
		
		 if(this.hasRight(User.SUPER_USER)){
			  role="superuser";
		  }
		  else if(this.hasRight(User.ADMIN)){
			  role="admin";
		  }
		  else if(this.hasRight(User.PUBLISH)){
			 
			  role+="annotator, publisher";}
		  else if(this.hasRight(User.MODIFY_DATA)){
			  role="annotator";
			  
			
		  }
		  else if(this.hasRight(User.VIEW_DATA)){role="data viewer";}
		  else{role="no role";}
		  return role;
	}
	
	public void setMintRole(String role){
		 if(role.equalsIgnoreCase("superuser")){
			 
			  this.setRights(User.SUPER_USER);
		  }
		  else if(role.equalsIgnoreCase("admin")){
			  this.setRights(User.ADMIN|User.PUBLISH|User.MODIFY_DATA);
		  }
		  else if(role.indexOf("annotator")>=0){
			  this.setRights(User.MODIFY_DATA);
			  if(role.indexOf("publisher")>=0){
				  this.setRights( User.PUBLISH|User.MODIFY_DATA);
				 
			  }
		  }
		  else if(role.equalsIgnoreCase("data viewer")){
			  this.setRights(User.VIEW_DATA);
		  }
		  else this.setRights(User.NO_RIGHTS);
		
	}
	
	public boolean checkPassword( String password ) {
		StringBuffer sb = encrypt( login, password );
		if( md5Password.equals(sb.toString()))
			return true;
		else
			return false;
	}
	
	/**
	 * Works only when login is already set!!
	 * @param password
	 */
	public void setNewPassword( String password ) {
		//log.debug("setNewPassword called"); 
		if( login == null ) 
			throw new Error( "Need login to be set" );
		StringBuffer sb = encrypt( login, password);
		setMd5Password( sb.toString());
	}
	
	public void encryptAndSetLoginPassword( String login, String password ) {
		StringBuffer sb = encrypt( login, password);
		setMd5Password( sb.toString());
		setLogin( login );
	} 
	
	private StringBuffer encrypt( String login, String password ) {
		StringBuffer sb = new StringBuffer();
		
		try {
			MessageDigest md = MessageDigest.getInstance( "MD5");
			md.update( login.getBytes( Charset.forName( "UTF-8")));
			md.update( password.getBytes( Charset.forName( "UTF-8")));
			byte[] md5 = md.digest();
			for( byte b: md5 ) {
				int i = (b&0xff);
				if( i < 16 )
					sb.append( "0" );
				sb.append( Integer.toHexString(i));
			}
		} catch( Exception e ) {
			e.printStackTrace();
			throw new Error( "Cant recover ",e);
		}
		return sb;
	}
	/**
	 * A User without organization has ALL_RIGHTS, when he joins one, it needs to be determined
	 * what rights he has.
	 * @param rights
	 */
	public void setRights( int rights ) {
		this.rights = rights;
	}

	public int getRights() {
		return this.rights;
	}
	
	public boolean hasRight( int right) {
		return (( right & this.rights) == right);
	}
	
	/**
	 * Return for which Organizations the access rights apply.
	 * For SUPER users this returns all organizations.
	 * @return
	 */
	public List<Organization> getAccessibleOrganizations() {
		ArrayList<Organization> orgs = new ArrayList<Organization>();
		if( hasRight(User.SUPER_USER)) {
			orgs.addAll( DB.getOrganizationDAO().findAll());
		} else {
			if( getOrganization() != null ) {
				orgs.add( getOrganization());
				orgs.addAll( getOrganization().getDependantRecursive());
			}
		}
		return orgs;
	}

	public boolean isAccessibleOrganization( Organization o ) {
		for( Organization o2: getAccessibleOrganizations() ) {
			if( o2.getDbID() == o.getDbID())
				return true;
		}
		return false;
	}
	
	public List<DataUpload> getUploads() {
		return DB.getDataUploadDAO().getByUser( this );
	}
}
