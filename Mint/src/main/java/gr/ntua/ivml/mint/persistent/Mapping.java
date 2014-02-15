package gr.ntua.ivml.mint.persistent;

import gr.ntua.ivml.mint.db.DB;

import java.util.Date;

public class Mapping implements Lockable {
	Long dbID;
	
	String name;
	Date creationDate;
	Organization organization;
	String jsonString;

	// This should be an object, but name will do
	XmlSchema targetSchema;
	Long userID;
	boolean shared;
	boolean finished;
	
	
	public boolean isShared() {
		return shared;
	}
	public void setShared(boolean shared) {
		this.shared = shared;
	}
	public boolean isFinished() {
		return finished;
	}
	public void setFinished(boolean finished) {
		this.finished = finished;
	}
	public Long getDbID() {
		return dbID;
	}
	public void setDbID(Long dbId) {
		this.dbID = dbId;
	}
	public String getName() {
		return name;
	}
	public void setName(String name) {
		this.name = name;
	}
	public Date getCreationDate() {
		return creationDate;
	}
	public void setCreationDate(Date creationDate) {
		this.creationDate = creationDate;
	}
	public Organization getOrganization() {
		return organization;
	}
	public void setOrganization(Organization organization) {
		this.organization = organization;
	}
		public Long getUserID() {
		return userID;
	}
	public void setUserID(Long userID) {
		this.userID = userID;
	}
	
	public XmlSchema getTargetSchema() {
		return targetSchema;
	}
	public void setTargetSchema(XmlSchema targetSchema) {
		this.targetSchema = targetSchema;
	}
	public String getJsonString() {
		return jsonString;
	}
	public void setJsonString(String jsonString) {
		this.jsonString = jsonString;
	}

	@Override
	public String getLockname() {
		return "Mapping " + name ;
	}
	
	//Arne check if this is correct
	public boolean isLocked( User u, String sessionId ) {
		return !DB.getLockManager().canAccess( u, sessionId, this );
	}
}
