package gr.ntua.ivml.mint.actions;

import java.io.BufferedInputStream;
import java.io.BufferedReader;
import java.io.ByteArrayInputStream;
import java.io.DataInputStream;
import java.io.File;
import java.io.FileInputStream;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.util.Collection;
import java.util.Collections;
import java.util.List;
import java.util.ArrayList;
import java.util.Map;

import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.mapping.MappingConverter;
import gr.ntua.ivml.mint.mapping.MappingSummary;
import gr.ntua.ivml.mint.persistent.DataUpload;
import gr.ntua.ivml.mint.persistent.Mapping;
import gr.ntua.ivml.mint.persistent.Organization;
import gr.ntua.ivml.mint.persistent.User;
import gr.ntua.ivml.mint.persistent.XmlSchema;
import gr.ntua.ivml.mint.persistent.XpathHolder;
import gr.ntua.ivml.mint.util.StringUtils;

import net.sf.json.JSONObject;
import net.sf.json.JSONSerializer;

import org.apache.commons.io.IOUtils;
import org.apache.log4j.Logger;
import org.apache.struts2.convention.annotation.Action;
import org.apache.struts2.convention.annotation.Result;
import org.apache.struts2.convention.annotation.Results;

import java.io.FileOutputStream;


@Results( { @Result(name = "input", location = "mapselection.jsp"),
		@Result(name = "error", location = "mapselection.jsp"),
		@Result(name = "success", location = "mapselection.jsp"),
		@Result(name="download", type="stream", params={"inputName", "stream", "contentType", "application/json", 
				  "contentDisposition", "attachment; filename=${filename}"})
//@Result(name="download", type="stream", params={"inputName", "stream", "contentType", "application/x-zip-compressed", 
//							  "contentDisposition", "attachment; filename=${filename}", "contentLength", "${filesize}"})
})
public class Mapselection extends GeneralAction {

	protected final Logger log = Logger.getLogger(getClass());
	public String mapName;
	
	public String mapsel;
	private long selectedMapping;
	private long editMapping;
	private long schemaSel;
	private long uploadId;
	private Collection<String> missedMaps = new ArrayList<String>();
	private boolean noitem = false;
	private List lockedmaps = new ArrayList();
	private List<Mapping> accessibleMappings = new ArrayList<Mapping>();
	private List<Mapping> templateMappings = new ArrayList<Mapping>();
	private List<XmlSchema> schemas = new ArrayList<XmlSchema>();
    private String upfile;
	private InputStream stream;
	private String filesize;
	private String filename;
	private String upmapname;
	
	public void findTemplateMappings() {
		List<Mapping> maplist = new ArrayList();
		try {

			List<Mapping> alllist = DB.getMappingDAO().findAllOrderOrg();
			for (int i = 0; i < alllist.size(); i++) {
				// now add the shared ones if not already in list
				Mapping em = alllist.get(i);
				boolean lock = em.isLocked(getUser(), getSessionId());
				// if shared and not locked add to template list
				if (em.isShared() && !lock) {

					maplist.add(em);
				} else if (!em.isShared() && !lock) {
					// if not shared but belongs to accessible org
					Organization org = em.getOrganization();
					// need to check accessible and their parents
					List<Organization> deporgs = user
							.getAccessibleOrganizations();
					for (int j = 0; j < deporgs.size(); j++) {
						if (deporgs.get(j).getDbID() == org.getDbID()) {
							// mapping org belongs in deporgs so add
							if (!maplist.contains(em)) {
								maplist.add(em);
							}
							break;
						}
						Organization parent = deporgs.get(j)
								.getParentalOrganization();
						while (parent != null && parent.getDbID() > 0) {

							if (parent.getDbID() == org.getDbID()) {
								// mapping org belongs to parent of accessible
								// so add
								if (!maplist.contains(em)) {
									maplist.add(em);
								}
								break;
							}
							parent = parent.getParentalOrganization();
							// traverse all parents OMG
						}
					}
				}
			}
		} catch (Exception ex) {
			log.debug(" ERROR GETTING MAPPINGS:" + ex.getMessage());
		}
		templateMappings = maplist;

	}
	
	public void setEditMapping(long editMapping) {
		this.editMapping = editMapping;
	}
	
	public long getEditMapping() {
		return editMapping;
	}
	
	public InputStream getStream() {
		return stream;
	}

	public void setStream(InputStream stream) {
		this.stream = stream;
	}

	public String getFilesize() {
		return filesize;
	}

	public void setFilesize(String filesize) {
		this.filesize = filesize;
	}

	public String getFilename() {
		return filename;
	}

	public void setFilename(String filename) {
		this.filename = filename;
	}
	
	public String getUpmapname() {
		return upmapname;
	}

	public void setUpmapname(String upmapname) {
		this.upmapname = upmapname;
	}

	public List<Mapping> getTemplateMappings() {
		return this.templateMappings;
	}

	public boolean getNoitem() {
		return noitem;
	}

	public void findAccessibleMappings() {
		List<Mapping> maplist = new ArrayList();
		try {

			// if user is admin or superuser then get his accessibleOrgs
			if (user.getMintRole().equalsIgnoreCase("ADMIN")
					|| user.getMintRole().equalsIgnoreCase("SUPERUSER")) {
				List<Organization> deporgs = user.getAccessibleOrganizations();
				for (Organization org : deporgs) {
					maplist.addAll(DB.getMappingDAO().findByOrganization(org));
				}

			} else if (user.getMintRole().indexOf("annotator") > -1) {
				// if he is annotator then only access to his orgs mappings

				Organization uorg = user.getOrganization();
				maplist.addAll(DB.getMappingDAO().findByOrganization(uorg));

			}
		} catch (Exception ex) {
			log.debug(" ERROR GETTING MAPPINGS:" + ex.getMessage());
		}
		accessibleMappings = maplist;

	}

	public void findSchemas() {
		schemas = DB.getXmlSchemaDAO().findAll();
	}

	public List<XmlSchema> getSchemas() {
		return schemas;
	}

	public List<Mapping> getAccessibleMappings() {
		return accessibleMappings;
	}

	public List getLockedmaps() {
		return lockedmaps;
	}

	public void findlocks(List<Mapping> maplist) {
		log.debug("checking locks");
		for (int i = 0; i < maplist.size(); i++) {
			Mapping m = maplist.get(i);
			if (m.isLocked(user, sessionId)) {
				lockedmaps.add(true);
			} else {
				lockedmaps.add(false);
			}
		}
	}

	public boolean checkName(String newname) {
		List<Mapping> maplist = new ArrayList();
		boolean exists = false;
		try {
			Organization org = user.getOrganization();
			for (Mapping m : DB.getMappingDAO().findByOrganization(org)) {
				if (m.getName().equalsIgnoreCase(newname)) {
					exists = true;
					break;
				}
			}

		} catch (Exception ex) {
			log.debug(" ERROR GETTING MAPPINGS:" + ex.getMessage());
		}
		return exists;
	}
	
	/**
	 * Download either from attached transformation or an earlier
	 * Annotation content.
	 * @throws Exception
	 */
	private void downloadMapping(String name, String m) throws Exception {
		try {
			ByteArrayInputStream bais = new ByteArrayInputStream(m.getBytes());
//			setFilesize( thefilesize );
			
			setFilename( name + ".mint");
			
	
			setStream(bais);
		} catch( Exception e ) {
			log.error( "Couldn't download" ,e );
			throw e;
		}
	}

	public Collection getMissedMaps() {
		return this.missedMaps;
	}

	public void setSelectedMapping(long selectedMapping) {
		this.selectedMapping = selectedMapping;
	}

	
	public long getSelectedMapping() {
		return selectedMapping;
	}

	public void setUpfile(String upfile){
		this.upfile=upfile;
	}

	public String getUpfile(){
		return(upfile);
	}
	
	
	public long getUploadId() {
		return uploadId;
	}

	public void setUploadId(long uploadId) {
		this.uploadId = uploadId;
	}

	public void setUploadId(String uploadId) {
		this.uploadId = Long.parseLong(uploadId);
	}

	

	public long getSchemaSel() {
		return schemaSel;
	}

	public void setSchemaSel(long schemaSel) {
		this.schemaSel = schemaSel;
	}

	public void setMapName(String mapName) {
		this.mapName = mapName;
	}


	public String getMapsel() {
		return mapsel;
	}

	public void setMapsel(String mapsel) {
		this.mapsel = mapsel;
	}

	@Action(value = "Mapselection")
	public String execute() throws Exception {
		if(mapsel==null){mapsel="";}
		if ("createschemanew".equals(mapsel)) {
			this.setEditMapping(0);
			if (mapName == null || mapName.length() == 0) {
				initLists();
				addActionError("Specify a mapping name!");
				return ERROR;
			}

			if (getSchemaSel() <= 0) {
				initLists();
				addActionError("No schema specified!");
				return ERROR;
			}

			Mapping mp = new Mapping();
			mp.setCreationDate(new java.util.Date());
			if (checkName(mapName) == true) {
				initLists();
				addActionError("Mapping name already exists!");
				return ERROR;

			}
			mp.setName(mapName);
			mp.setUserID(user.getDbID());
			mp.setOrganization(DB.getDataUploadDAO().findById(uploadId, false)
					.getOrganization());
			// mp.setOrganization(user.getOrganization());
			if (getSchemaSel() > 0) {
				long schemaId = getSchemaSel();
				XmlSchema schema = DB.getXmlSchemaDAO()
						.getById(schemaId, false);
				mp.setTargetSchema(schema);
				mp.setJsonString(schema.getJsonTemplate());
			}

			// save mapping name to db and commit

			DB.getMappingDAO().makePersistent(mp);
			DB.commit();

			setEditMapping(mp.getDbID());
		} 
		else if ("uploadmapping".equals(mapsel)) {
			this.setEditMapping(0);
			if (this.upfile == null || upfile.length() == 0) {
				initLists();
				addActionError("Please upload a file first!");
				return ERROR;
			}
			if (mapName == null || mapName.length() == 0) {
				initLists();
				addActionError("Specify a mapping name!");
				return ERROR;
			}

			if (getSchemaSel() <= 0) {
				initLists();
				addActionError("No schema specified!");
				return ERROR;
			}

			Mapping mp = new Mapping();
			mp.setCreationDate(new java.util.Date());
			if (checkName(mapName) == true) {
				initLists();
				addActionError("Mapping name already exists!");
				return ERROR;

			}
			mp.setName(mapName);
			
			mp.setOrganization(DB.getDataUploadDAO().findById(uploadId, false).getOrganization());
			
			String convertedMapping = null;
			if(upfile!=null){
				try{
					String dir= System.getProperty("java.io.tmpdir") + File.separator;
					File newmapping=new File(dir+upfile);
					StringBuffer contents = StringUtils.fileContents(newmapping);
					MappingConverter converter = new MappingConverter(DB.getXmlSchemaDAO()
						.getById(getSchemaSel(), false));
					JSONObject converted = converter.convert(contents.toString());
					if(converted != null) convertedMapping = converted.toString();
			    }catch (Exception e){//Catch exception if any
			    		e.printStackTrace();
			    		initLists();
	    				System.err.println("Error importing file: " + e.getMessage());
	    				addActionError("Mappings import failed: " + e.getMessage());
					return ERROR;
			   }	}
			if (getSchemaSel() > 0) {
				long schemaId = getSchemaSel();
				XmlSchema schema = DB.getXmlSchemaDAO()
						.getById(schemaId, false);
				mp.setTargetSchema(schema);
				
				if(convertedMapping != null) {
					mp.setJsonString(convertedMapping);
				} else {
					mp.setJsonString(schema.getJsonTemplate());
				}
			}
            
			// save mapping name to db and commit?
            
			DB.getMappingDAO().makePersistent(mp);
			DB.commit();
			
			initLists();
			this.setMapsel("");
			addActionError("Mappings successfully uploaded!");
			return ERROR;

		}
		else if ("createtemplatenew".equals(mapsel)) {
			this.setEditMapping(0);
			if (mapName == null || mapName.length() == 0) {
				initLists();
				addActionError("Specify a mapping name!");
				return ERROR;
			}
			Mapping mp = new Mapping();
			mp.setCreationDate(new java.util.Date());
			if (checkName(mapName) == true) {
				initLists();
				addActionError("Mapping name already exists!");
				return ERROR;

			}
			mp.setName(mapName);
			
			mp.setOrganization(DB.getDataUploadDAO().findById(uploadId, false)
					.getOrganization());
			// mp.setOrganization(user.getOrganization());
			if (this.getSelectedMapping() > 0) {
				long templateId = getSelectedMapping();
				Mapping temp = DB.getMappingDAO().getById(templateId, false);
				mp.setTargetSchema(temp.getTargetSchema());
				mp.setJsonString(temp.getJsonString());
			} else {
				initLists();
				addActionError("You must select a mapping to proceed.");

				return ERROR;
			}

			// save mapping name to db and commit

			DB.getMappingDAO().makePersistent(mp);
			DB.commit();

			setEditMapping(mp.getDbID());
			JSONObject object = (JSONObject) JSONSerializer.toJSON(mp.getJsonString());
			Map<String,String> allmaps=MappingSummary.getMappedXPaths(object );
			Collection<String> allvalues = allmaps.values();

			if (getSelectedMapping() > 0) {
				if (MappingSummary.getInvalidXPaths(DB.getDataUploadDAO()
						.findById(uploadId, false), mp) != null) {
					this.missedMaps = MappingSummary.getInvalidXPaths(DB
							.getDataUploadDAO().findById(uploadId, false), mp);
					
					
					
				}
				if (missedMaps.size() == 0) {
					return "success";
				} else {
					initLists();
					addActionError("This import does not contain the following xpaths which appear in <i>'"
							+ mp.getName()
							+ "'</i> template mappings you are trying to use. If you are sure you have chosen the correct template mappings for this import you can click on 'Continue anyway'.");
					boolean allinvalid=true;
					for(String i:allvalues){
						if(!this.missedMaps.contains(i)){
							allinvalid=false;
							break;
						}
					}
					if(allinvalid==true){
						
						addActionError("<br/><br/><b>ALL XPATHS FOUND IN THESE MAPPINGS ARE INVALID FOR THIS IMPORT!<b>");
					}

					return ERROR;
				}
			}
		} else if ("editmaps".equals(mapsel)) {
			if (this.getSelectedMapping() > 0) {
				this.setEditMapping(this.getSelectedMapping());
				// check if mapping is locked here
				Mapping em = DB.getMappingDAO().findById(getSelectedMapping(),
						false);
				// check if current user has access to mappings
				if (em.isLocked(getUser(), getSessionId())) {
					initLists();
					addActionError("The selected mappings are currently in use by another user. Please try to edit them again later");
					return ERROR;
				}
				// check if this import corresponds to mappings
				if (MappingSummary.getInvalidXPaths(DB.getDataUploadDAO()
						.findById(uploadId, false), em) != null) {
					this.missedMaps = MappingSummary.getInvalidXPaths(DB
							.getDataUploadDAO().findById(uploadId, false), em);
					
				}
				if (missedMaps.size() == 0) {
					return "success";
				} else {

					initLists();
					JSONObject object = (JSONObject) JSONSerializer.toJSON(em.getJsonString());
					Map<String,String> allmaps=MappingSummary.getMappedXPaths(object );
					Collection<String> allvalues = allmaps.values();

					addActionError("This import does not contain the following xpaths which appear in <i>'"
							+ em.getName()
							+ "'</i> mappings you are trying to use. Press 'cancel' to go back and select different mappings for edit. If you are sure you have chosen the correct mappings for this import you can click on 'Continue anyway'.");
					
					boolean allinvalid=true;
					for(String i:allvalues){
						if(!this.missedMaps.contains(i)){
							allinvalid=false;
							break;
						}
					}
					if(allinvalid==true){
						
						addActionError("<br/><br/><b>ALL XPATHS FOUND IN THESE MAPPINGS ARE INVALID FOR THIS IMPORT!<b>");
					}
					
					return ERROR;
				}
			} else {
				initLists();
				addActionError("Choose the mappings you want to edit!");
				return ERROR;
			}
		} else if ("sharemaps".equals(mapsel)) {
			this.setEditMapping(0);
			
		   if (this.getSelectedMapping() > 0) {
				// check if mapping is locked here

				Mapping em = DB.getMappingDAO().findById(getSelectedMapping(),
						false);
				// check if current user has access to mappings
				if (em.isLocked(getUser(), getSessionId())) {
					initLists();
					addActionError("The selected mappings are currently locked by another user. Please try to share them again later");
					return ERROR;
				}
				if(em.isShared()==true){
					em.setShared(false);
					
				}else{em.setShared(true);}
				DB.commit();
				refreshUser();
				initLists();
				addActionError("Mappings share state successfully altered!");
				return ERROR;

			} else {
				initLists();
				addActionError("Choose the mappings you want to share!");
				return ERROR;
			}
		} else if ("deletemaps".equals(mapsel)) {
			this.setEditMapping(0);
			
			if (this.getSelectedMapping() > 0) {
				boolean success = false;
				Mapping mp = DB.getMappingDAO().getById(getSelectedMapping(),
						true);
				if (mp.isLocked(getUser(), getSessionId())) {
					initLists();
					addActionError("The selected mappings are currently in use by another user.");
					return ERROR;
				}
				success = DB.getMappingDAO().makeTransient(mp);
				DB.commit();
				if (success) {
					initLists();
					addActionError("Mappings successfully deleted!");
					return ERROR;

				} else {
					refreshUser();
					initLists();
					addActionError("Unable to delete selected Mappings. Mappings are in use!");
					return ERROR;
				}
			}

			else {
				initLists();
				addActionError("Choose the mappings you want to delete!");
				return ERROR;
			}
		} else if ("downloadmaps".equals(mapsel)) {
			this.setEditMapping(0);
			if (this.getSelectedMapping() > 0) {
				boolean success = false;
				Mapping mp = DB.getMappingDAO().getById(getSelectedMapping(),
						true);
				if (mp.isLocked(getUser(), getSessionId())) {
					initLists();
					addActionError("The selected mappings are currently in use by another user.");
					return ERROR;
				}
				
				String json = mp.getJsonString();
				if (json != null) {
					downloadMapping(mp.getName(), json);
					return "download";
				} else {
					refreshUser();
					initLists();
					addActionError("Unable to donwload selected Mappings. Mappings are empty!");
					return ERROR;
				}
			}

			else {
				initLists();
				addActionError("Choose the mappings you want to download!");
				return ERROR;
			}
		} else if ("discardnewmap".equals(mapsel)) {
			this.setEditMapping(0);
			
			if (this.getSelectedMapping() > 0) {
				boolean success = false;
				Mapping mp = DB.getMappingDAO().getById(getSelectedMapping(),
						true);
				if (mp.isLocked(getUser(), getSessionId())) {
					return ERROR;
				} else {
					success = DB.getMappingDAO().makeTransient(mp);
					DB.commit();
					if (success) {
						initLists();
						return ERROR;
					} else {
						initLists();
						refreshUser();
						return ERROR;
					}
				}
			}
		} else {
			log.error("Unknown action");
			addActionError("Specify a mapping action!");
			initLists();
			return ERROR;
		}
		return "success";

	}

	public void initLists() {
		this.findTemplateMappings();
		this.findAccessibleMappings();
		this.findSchemas();
		this.findlocks(this.accessibleMappings);
	}

	@Action("Mapselection_input")
	@Override
	public String input() throws Exception {
		
		if ((user.getOrganization() == null && !user.hasRight(User.SUPER_USER))
				|| !user.hasRight(User.MODIFY_DATA)) {
			log.debug("No mapping rights");
			throw new IllegalAccessException("No mapping rights!");
		}
		DataUpload du = DB.getDataUploadDAO().findById(uploadId, false);
		XpathHolder level_xp = du.getItemXpath();
		if (level_xp == null
				|| level_xp.getXpathWithPrefix(false).length() == 0) {
			this.noitem = true;
			addActionError("You must first define the Item Level and Item Label by choosing step 1.");
			return ERROR;
		}
		mapsel="";
		initLists();
		return super.input();
	}

}