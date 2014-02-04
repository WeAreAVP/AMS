package gr.ntua.ivml.mint.persistent;

import java.util.Date;

public class ThesaurusAssignment {
	private Long dbID;
	//Date the thesaurus was assigned to the node;
	private Date assignDate;
	private User user;
	private Thesaurus thesaurus;
	private DataUpload dataUpload;
	private XpathHolder xpath;
	
	public Long getDbID() {
		return dbID;
	}
	
	public void setDbID(Long dbID) {
		this.dbID = dbID;
	}
	
	public Date getAssignDate() {
		return assignDate;
	}
	public void setAssignDate(Date assignDate) {
		this.assignDate = assignDate;
	}
	
	public User getUser() {
		return user;
	}
	
	public void setUser(User user) {
		this.user = user;
	}
	
	public Thesaurus getThesaurus() {
		return thesaurus;
	}
	
	public void setThesaurus(Thesaurus thesaurus) {
		this.thesaurus = thesaurus;
	}
	
	public XpathHolder getXpath() {
		return xpath;
	}
	
	public void setXpath(XpathHolder xpath) {
		this.xpath = xpath;
	}

	public DataUpload getDataUpload() {
		return dataUpload;
	}

	public void setDataUpload(DataUpload dataUpload) {
		this.dataUpload = dataUpload;
	}
}
