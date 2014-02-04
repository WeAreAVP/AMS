package gr.ntua.ivml.mint.actions;

import java.io.File;
import java.io.FileInputStream;
import java.util.ArrayList;
import java.util.Date;
import java.util.Iterator;
import java.util.List;

import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.persistent.BlobWrap;
import gr.ntua.ivml.mint.persistent.DataUpload;
import gr.ntua.ivml.mint.persistent.Mapping;
import gr.ntua.ivml.mint.persistent.Organization;
import gr.ntua.ivml.mint.persistent.Thesaurus;
import gr.ntua.ivml.mint.persistent.ThesaurusAssignment;
import gr.ntua.ivml.mint.persistent.User;
import gr.ntua.ivml.mint.persistent.XpathHolder;

import net.sf.json.JSONObject;

import org.apache.log4j.Logger;
import org.apache.struts2.convention.annotation.Action;
import org.apache.struts2.convention.annotation.Result;
import org.apache.struts2.convention.annotation.Results;
import org.hibernate.Hibernate;

import com.opensymphony.xwork2.ActionContext;

@Results( {
		@Result(name = "input", location = "editor.jsp"),
		@Result(name = "error", location = "error.jsp", type = "redirectAction"),
		@Result(name = "success", location = "ajaxthesaurus.jsp") })
public class ThesaurusAction extends GeneralAction {
	private static final int MAX_LABEL_LENGTH = 20;
	protected final Logger log = Logger.getLogger(getClass());
	private String result;
	private String uploadId;
	private String thesaurusId;
	private String action;
	private String xpath;
	private String mappingId;
	private String filename;
	private String contentType;
	
	//Used for storing/retrieving thesaurus
	private String description;
	private String title;
	private String url;
	private String contact;
	private File uploadFile;
	
	public String getUploadId() {
		return uploadId;
	}

	public void setUploadId(String uploadId) {
		this.uploadId = uploadId;
	}

	public String getAction() {
		return action;
	}

	public void setAction(String action) {
		this.action = action;
	}

	public String getResult() {
		return result;
	}

	public void setResult(String result) {
		this.result = result;
	}

	public String getXpath() {
		return xpath;
	}

	public void setXpath(String xpath) {
		log.info("*** Xpath from request set to : " + xpath); 
		this.xpath = xpath;
	}
		

	public String getThesaurusId() {
		return thesaurusId;
	}

	public void setThesaurusId(String thesaurusId) {
		this.thesaurusId = thesaurusId;
	}

	public String getMappingId() {
		return mappingId;
	}

	public void setMappingId(String mappingId) {
		this.mappingId = mappingId;
	}	
	
	
	///////////////////////////////////////////////
	public void setUploadFileContentType(String contentType) {
		this.contentType = contentType;
	}

	public void setUploadFileFileName(String filename) {
		this.filename = filename;
	}

	public String getDescription() {
		return description;
	}

	public void setDescription(String description) {
		this.description = description;
	}

	public String getTitle() {
		return title;
	}

	public void setTitle(String title) {
		this.title = title;
	}

	public String getUrl() {
		return url;
	}

	public void setUrl(String url) {
		this.url = url;
	}

	public String getContact() {
		return contact;
	}

	public void setContact(String contact) {
		this.contact = contact;
	}	

	public File getUploadFile() {
		return uploadFile;
	}

	public void setUploadFile(File uploadFile) {
		this.uploadFile = uploadFile;
	}

	
	///////////////////////////////////////////////
	
	
	@Action(value = "ThesaurusAjax")
	public String execute() throws Exception {
		log.info("********************");
		log.info("Thesaurus action - " + action);
		log.info("upload id: " + uploadId);
		log.info("Xpath: " + xpath);
		if (action.equalsIgnoreCase("STATS")) {
			//Return statistics
			handleStatsRequest();
		} else if(action.equalsIgnoreCase("LIST")) {
			//Return list of thesauri for specified tree
			handleThesaurusList();
		} else if(action.equalsIgnoreCase("LABELS")) {
			//Return list of labels for specific tree and thesauri
			handleLabelList();
		} else if(action.equalsIgnoreCase("SAVE")) {
			//Save thesaurus
			saveThesaurus();
		} else if(action.equalsIgnoreCase("EDIT")) {
			//Edit thesaurus
			editThesaurus();
		} else if(action.equalsIgnoreCase("EDIT_FORM")) {
			//Create edit form for thesaurus
			thesaurusEditForm();
		} else if(action.equalsIgnoreCase("ASSIGN")) {
			//Assign thesaurus to node
			createThesaurusAssignment();
		} else if(action.equalsIgnoreCase("UNASSIGN")) {
			//Delete assignment of thesaurus to node
			deleteThesaurusAssignment();
		} else if(action.equalsIgnoreCase("DELETE")) {
			//Delete thesaurus
			deleteThesaurus();
		} else if(action.equalsIgnoreCase("APPLY_THESAURUS")) {
			//Apply current thesaurus assignments to current mapping
			applyThesaurusToMapping();
		}
		log.info("********************");

		return "success";
	}
	
	private void deleteThesaurus() {
		try {
			result = "ERROR";
			Thesaurus t = DB.getThesaurusDAO().findById(Long.valueOf(thesaurusId), false);
			if((t != null) && (hasAccess(t.getOrganization()))) {
				boolean r = DB.getThesaurusDAO().delete(Long.valueOf(thesaurusId));
				if(r)
					result = "OK";
			}
		} catch(Exception e) {
			log.info("Error deleting thesaurus: " + e.getMessage());
			result = "Error deleting thesaurus... <!--" + e.getMessage() + " -->";
		}
	}

	private void deleteThesaurusAssignment() {
		try {
			result = "ERROR";
			log.info("deleteThesaurusAssignment -> Mapping id: " + mappingId);
			ThesaurusAssignment ta = DB.getThesaurusAssignmentDAO().findById(Long.valueOf(mappingId), false);
			if((ta != null) && (hasAccess(ta.getUser().getOrganization()))) {
				boolean r = DB.getThesaurusAssignmentDAO().delete(Long.valueOf(mappingId));
				if(r)
					result = "OK";
			}
		} catch(Exception e) {
			log.info("Error deleting thesaurus assignment: " + e.getMessage());
			result = "Error deleting thesaurus assignment... <!--" + e.getMessage() + " -->";
		}
	}
	
	private void createThesaurusAssignment() {
		try {
			log.info("Creating new thesaurus assignment...");
			log.info("\tThesaurus ID: " + thesaurusId);
			log.info("\tData Upload ID: " + uploadId);
			Thesaurus t = DB.getThesaurusDAO().findById(Long.valueOf(thesaurusId), false);
			DataUpload d = DB.getDataUploadDAO().findById(Long.valueOf(uploadId), false);
			persistThesaurusAssignment(t, d, findXpathHolder());
			result = "Succesfully added!";
		} catch(Exception e) {
			log.info("Error assigning thesaurus: " + e.getMessage());
			result = "Error assigning thesaurus... <!--" + e.getMessage() + " -->";
		}
	}
	
	private void applyThesaurusToMapping() {
		try {
			int count = 0;
			log.info("Applying mappings of thesarus with id " + thesaurusId + " to current mapping (" + uploadId + ")...");
			Thesaurus t = DB.getThesaurusDAO().findById(Long.valueOf(thesaurusId), false);
			DataUpload du = DB.getDataUploadDAO().findById(Long.valueOf(uploadId), false);
			List l = DB.getThesaurusDAO().findDistinctXpathsByThesaurusId(t);
			log.info("Detected total of " + l.size() + " already existing mappings");
			if(l == null) {
				log.info("Empty list of assignments - cannot apply...");
			} else {
				Iterator iter = l.iterator();
				while(iter.hasNext()) {
					//Attempt to find xpath
					String xpath = (String) iter.next();
					XpathHolder xpathHolder = findXpathHolder(xpath);
					if((xpathHolder != null) && (!DB.getThesaurusAssignmentDAO().existsAssignment(xpathHolder, t, du))) {
						//Xpath exists also in this tree, apply thesaurus
						persistThesaurusAssignment(t, du, xpathHolder);
						log.info("Applied thesaurus '" + t.getTitle() + "' to " + xpath);
						count++;
					}
				}
			}
			result = "Applied thesaurus to " + count + " nodes";
			log.info(result);
		} catch(Exception e) {
			log.info("Error applying thesaurus to current mapping: " + e.getMessage());
			result = "Error";
		}
	}
	
	
	private void saveThesaurus() {
		try {
			DataUpload du = DB.getDataUploadDAO().findById(Long.valueOf(uploadId), false);
			Date now = new Date();
			Thesaurus t = new Thesaurus();
			t.setDescription(description);
			t.setTitle(title);
			t.setUploadDate(now);
			t.setUrl(url);
			t.setContactPerson(contact);
			//find current user and set him as owner of this thesaurus
			User u = getUser();
			t.setOwner(u);
			t.setOrganization(du.getOrganization());
			if(uploadFile != null) {
				log.info("Creating file: " + uploadFile.getName());
				log.info("Original filename:" + filename);
				FileInputStream fis = new FileInputStream( uploadFile );
				BlobWrap data = new BlobWrap();
				data.setData(Hibernate.createBlob(fis, (int) uploadFile.length()));
				log.info("Created file: " + uploadFile.getName());
				t.setFile(data);
				t.setFilename(filename);
				t.setContentType(contentType);
			}
			DB.getThesaurusDAO().makePersistent(t);			
			result = t.getDbID().toString();
			if(uploadFile != null) {
				log.info("BlobWrapper id:" + t.getFile().getDbID());
			}
		} catch(Exception e) {
			result = "Error";
			log.info("Error saving thesaurus: " + e.getMessage());
		}
		
	}
	
	private void editThesaurus() {
		try {
			Date now = new Date();
			Thesaurus t = DB.getThesaurusDAO().findById(Long.valueOf(thesaurusId), false);
			boolean access = hasAccess(t.getOrganization());
			if((t != null) && access) {
				t.setDescription(description);
				t.setTitle(title);
				t.setUploadDate(now);
				t.setUrl(url);
				t.setContactPerson(contact);
				DB.getThesaurusDAO().makePersistent(t);
				result = t.getDbID().toString();
			} else {
				result = "Couldn't update thesaurus";
			}
		} catch(Exception e) {
			result = "Error";
			log.info("Error saving thesaurus: " + e.getMessage());
		}
		
	}
	
	private void thesaurusEditForm() {
		try {
			Date now = new Date();
			Thesaurus t = DB.getThesaurusDAO().findById(Long.valueOf(thesaurusId), false);
			JSONObject object = new JSONObject();
			object.put("thesaurusId", t.getDbID());
			object.put("title", t.getTitle());
			object.put("url", t.getUrl());
			object.put("contact", t.getContactPerson());
			object.put("description", t.getDescription());
			result = object.toString();
			log.info(result);
		} catch(Exception e) {
			result = "Error";
			log.info("Error converting thesaurus to JSON: " + e.getMessage(), e);
		}
		
	}
	
	private void handleLabelList() {
		try {
			StringBuffer out = new StringBuffer();
			out.append("<ul id=\"active_labels\" class=\"labels\">\n");
			Thesaurus thesaurus = DB.getThesaurusDAO().getById(Long.valueOf(thesaurusId), false);
			DataUpload dataUpload = DB.getDataUploadDAO().getById(Long.valueOf(uploadId), false);
			if((thesaurus != null) && (dataUpload != null)) {
				List<ThesaurusAssignment> metadata = DB.getThesaurusAssignmentDAO().getByThesaurusAndDataUpload(thesaurus, dataUpload); 
				for( ThesaurusAssignment t: metadata) {
					log.info(" *** Is thesaurus assign null? " + (t == null));
					log.info(" *** Is thesaurus' xpath null? " + (t.getXpath() == null));
					String label = t.getXpath().getXpath();
					if(label.length() > MAX_LABEL_LENGTH )
						label = label.substring(0, 8) + "..." + label.substring(label.length()- 8);
					out.append("<li class=\"blue\"><span>" + label + "<span>" + t.getXpath().getXpath()+ "</span></span><a href=\"#\" onclick=\"javascript:deleteAssign(" + t.getDbID() + ")\"></a></li>\n");
				}
			}
			out.append("</ul>\n");
			result = out.toString();
		} catch(Exception e) {
			result = "<span class=\"error\">Error reading thesauri mappings</span>\n";
			log.info("Error reading thesauri mappings: " + e.getMessage(), e);
		}
	}
	
	private void handleThesaurusList() {
		StringBuffer out = new StringBuffer();
		out.append("<ul id=\"thesaurus_list\" class=\"thesaurus_list\">\n");
		DataUpload du = DB.getDataUploadDAO().findById(Long.valueOf(uploadId), false);
		List<Thesaurus> thesauri = DB.getThesaurusDAO().findByOrganizationAndDependants(du.getOrganization());
		log.info("Returning thesauri list for organization " + du.getOrganization().getName());
		int rowCount = 1;
		for( Thesaurus t: thesauri) {
			out.append("<li class=\"thesurus_row" + rowCount + "\" onclick=\"javascript:selectThesaurus(" + t.getDbID() + ", '" + t.getTitle() + "');\">" + t.getTitle() + " - " + t.getOrganization().getName() + "</li>\n");
			rowCount = (rowCount % 2) + 1;
		}
		out.append("</ul>\n");
		result = out.toString();
	}

	private void handleStatsRequest() {
		try {
			XpathHolder node = findXpathHolder();
			XpathHolder childNode = node.getChild("text()");
			if(childNode == null) {
				result = "No statistics for selected node...";
			} else {
				this.result = generateStatsForXpathHolder(childNode);
			}
		} catch (Exception e) {
			log.info("Error: " + e.getMessage());
			result = "Error retrieving stats...";
		}
	}

	//Utility methods
	private String generateStatsForXpathHolder(XpathHolder xp) {
		StringBuffer out = new StringBuffer();
		DB.getXMLNodeDAO().getStatsForXpaths(xp.getXmlObject());
		List<Object[]> elements = xp.getCountByValue(30);
		out.append("<table class=\"stats-table\">");
		out.append("<tr><th>Value</th><th>Frequency</th></tr>");
		for (Object[] oa : elements) {
			String value = (String) oa[0];
			Long valueCount = (Long) oa[1];
			out.append("<tr>");
			out.append("<td> " + value + "</td>");
			out.append("<td> " + valueCount + "</td>");
			out.append("</tr>");
		}
		out.append("</table>");
		return out.toString();
	}
	
	private XpathHolder findXpathHolder() {
		DataUpload du = DB.getDataUploadDAO().findById(Long.parseLong(uploadId), false);
		XpathHolder xp = du.getXmlObject().getRoot().getByRelativePathWithPrefix(xpath,true);
		if(xp != null) {
			log.info(" +++ Xpath Holder found!!!");
		} else {
			log.info(" +++ Didn't find xpath holder for " + xpath);
		}
		
		return xp;
	}
	
	private void persistThesaurusAssignment(Thesaurus t, DataUpload du, XpathHolder xph) throws Exception {
		if(hasAccess(du.getOrganization()) && hasAccess(t.getOrganization())) {
			ThesaurusAssignment ta = new ThesaurusAssignment();
			ta.setAssignDate(new Date());
			ta.setThesaurus(t);
			XpathHolder node = xph;
			ta.setXpath(node);
			ta.setDataUpload(du);
			User u = getUser();
			ta.setUser(u);
			DB.getThesaurusAssignmentDAO().makePersistent(ta);
		} else {
			throw new IllegalAccessError("User doesn't have access to this thesaurus or mapping");
		}
	}

	private XpathHolder findXpathHolder(String xpath) {
		DataUpload du = DB.getDataUploadDAO().findById(Long.parseLong(uploadId), false);
		XpathHolder xp = du.getXmlObject().getRoot().getByRelativePathWithPrefix(xpath,true);
		if(xp != null) {
			log.info(" +++ Xpath Holder found!!!");
		} else {
			log.info(" +++ Didn't find xpath holder for " + xpath);
		}
		return xp;
	}
	
	/**
	 * Checks if current user belongs to the same organization hierarchy as o
	 * 
	 * @param o 
	 * @return
	 */
	private boolean hasAccess(Organization o) {
		User u = getUser();

		return u.getAccessibleOrganizations().contains(o);		
	}
}
