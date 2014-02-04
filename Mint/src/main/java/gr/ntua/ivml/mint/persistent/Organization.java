package gr.ntua.ivml.mint.persistent;

import gr.ntua.ivml.mint.db.DB;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

public class Organization implements SecurityEnabled {
	public long dbID;
	String originalName;
	String englishName;
	
	String shortName;
	String description;

	String urlPattern;
	String address;
	String country;
	String type;
	Organization parentalOrganization;
	User primaryContact;
	
	List<Organization> dependantOrganizations = new ArrayList<Organization>();
	List<User> users = new ArrayList<User>();
	List<DataUpload> dataUploads = new ArrayList<DataUpload>();
	
	
	// temporary getter setter for old attribute
	public String getName() {
		return getEnglishName();
	}
	
	public void setName( String name ) {
		setEnglishName(name);
	}
	
	public String getShortName() {
		return shortName;
	}
	public void setShortName(String shortName) {
		this.shortName = shortName;
	}

	public List<User> getUsers() {
		return users;
	}
	public void setUsers(List<User> users) {
		this.users = users;
	}
	public List<Organization> getDependantOrganizations() {
		return dependantOrganizations;
	}
	public void setDependantOrganizations(List<Organization> dependantOrganizations) {
		this.dependantOrganizations = dependantOrganizations;
	}
	public long getDbID() {
		return dbID;
	}
	public void setDbID(long dbID) {
		this.dbID = dbID;
	}
	public String getAddress() {
		return address;
	}
	public void setAddress(String address) {
		this.address = address;
	}
	public String getCountry() {
		return country;
	}
	public void setCountry(String country) {
		this.country = country;
	}

	public String getType() {
		return type;
	}

	public void setType(String type) {
		this.type = type;
	}

	public Organization getParentalOrganization() {
		return parentalOrganization;
	}
	public void setParentalOrganization(Organization parentalOrganization) {
		this.parentalOrganization = parentalOrganization;
	}
	public User getPrimaryContact() {
		return primaryContact;
	}
	public void setPrimaryContact(User primaryContact) {
		this.primaryContact = primaryContact;
	}


	public String getOriginalName() {
		return originalName;
	}
	public void setOriginalName(String originalName) {
		this.originalName = originalName;
	}
	public String getEnglishName() {
		return englishName;
	}
	public void setEnglishName(String englishName) {
		this.englishName = englishName;
	}
	public String getDescription() {
		return description;
	}
	public void setDescription(String description) {
		this.description = description;
	}
	
	public String getUrlPattern() {
		return urlPattern;
	}

	public void setUrlPattern(String urlPattern) {
		this.urlPattern = urlPattern; 
	}

	public List<DataUpload> getDataUploads() {
		return dataUploads;
	}

	public  List<User> getUploaders() {
		return DB.getDataUploadDAO().getUploaders(this);
	}
	
	public void setDataUploads(List<DataUpload> dataUploads) {
		this.dataUploads = dataUploads;
	}

	/**
	 * Return all the dependent organizations all the levels down.
	 * @return
	 */
	public List<Organization> getDependantRecursive() {
		Map<Long, Organization> m = new HashMap<Long,Organization>();
		List<Organization> toDo = new ArrayList<Organization>();
		toDo.addAll(getDependantOrganizations());
		
		while( !toDo.isEmpty()) {
			Organization o = toDo.remove(0);
			if( ! m.containsKey(o.getDbID())) {
				m.put( o.getDbID(), o);
				toDo.addAll( o.getDependantOrganizations());
			}
		}
		toDo.clear();
		toDo.addAll(m.values());
		return toDo;
	}
	
	/**
	 * if find, just look for one
	 * @param find
	 * @return
	 */
	private List<User> directAdmins( boolean find ) {
		List<User> res = new ArrayList<User>();
		for( User u: getUsers() )
			if( u.getMintRole().equalsIgnoreCase("admin")) {
				res.add( u );
				if( find ) break;
			}
		return res;
	}
	
	/**
	 * if find, just look for one
	 * @param find
	 * @return
	 */
	private void adminsRecursive( boolean find, List<User> result ) {
		result.addAll( directAdmins( find ));
		if( !result.isEmpty() && find ) return;
		Organization parent = getParentalOrganization();
		if( parent != null )
			parent.adminsRecursive(find, result);
	}
	
	/**
	 * Counts all admins in this organizations and all parents
	 * @return
	 */
	public int getAdmincount() {
		List<User> admins = new ArrayList<User>();
		adminsRecursive(false, admins);
		return admins.size();
	}
	
	/**
	 * Find one admin in this organization or any parent
	 * @return
	 */
	public User findAdmin(){
		List<User> admins = new ArrayList<User>();
		adminsRecursive(true, admins);
		if( admins.isEmpty() ) return null;
		else return admins.get(0);
	}
	
	/**
	 * Returns all admins in this and parent organizations
	 * @return
	 */
	public List<User> getAllAdmins() {
		List<User> admins = new ArrayList<User>();
		adminsRecursive(false, admins);
		return admins;
	}
	
	public List<Mapping> getAllMappings() {
		return DB.getMappingDAO().findByOrganization(this);
	}
	
}
