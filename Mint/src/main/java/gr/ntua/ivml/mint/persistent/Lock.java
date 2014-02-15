package gr.ntua.ivml.mint.persistent;

import java.util.Date;

public class Lock {
	Long dbID;
	String userLogin;
	Date aquired;
	String httpSessionId;
	String objectType;
	long objectId;
	String name;
	
	public String getName() {
		return name;
	}
	public void setName(String name) {
		this.name = name;
	}
	public Long getDbID() {
		return dbID;
	}
	public void setDbID(Long dbID) {
		this.dbID = dbID;
	}
	public String getUserLogin() {
		return userLogin;
	}
	public void setUserLogin(String userLogin) {
		this.userLogin = userLogin;
	}
	public Date getAquired() {
		return aquired;
	}
	public void setAquired(Date aquired) {
		this.aquired = aquired;
	}
	public String getHttpSessionId() {
		return httpSessionId;
	}
	public void setHttpSessionId(String httpSessionId) {
		this.httpSessionId = httpSessionId;
	}
	public String getObjectType() {
		return objectType;
	}
	public void setObjectType(String objectType) {
		this.objectType = objectType;
	}
	public long getObjectId() {
		return objectId;
	}
	public void setObjectId(long objectId) {
		this.objectId = objectId;
	}	

	/**
	 * How old in seconds is this lock ...
	 * @return
	 */
	public int getAge() {
		long ageMil = (new Date()).getTime() - aquired.getTime();
		int ageSec = (int) ageMil/1000;
		return ageSec;
	}
	
	public String toString() {
		StringBuffer sb = new StringBuffer();
		sb.append( "User: " + userLogin + "\n" );
		sb.append( "Session: " + httpSessionId + "\n" );
		sb.append( "DbID: " + objectId + "\n" );
		sb.append( "Type: " + objectType + "\n" );
		sb.append( "Name: " + name +"\n" );
		return sb.toString();
	}
}
