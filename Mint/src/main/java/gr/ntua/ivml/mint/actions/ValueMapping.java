package gr.ntua.ivml.mint.actions;

import java.util.Map;

import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.persistent.DataUpload;
import gr.ntua.ivml.mint.persistent.Lock;
import gr.ntua.ivml.mint.persistent.Mapping;
import gr.ntua.ivml.mint.persistent.User;
import gr.ntua.ivml.mint.util.Config;
import gr.ntua.ivml.mint.valuemapping.MintIngestionValueMappingManager;
import gr.ntua.ivml.mint.xml.TreeGenerationParser;

import net.sf.json.JSONObject;

import org.apache.log4j.Logger;
import org.apache.struts2.ServletActionContext;
import org.apache.struts2.convention.annotation.Action;
import org.apache.struts2.convention.annotation.Result;
import org.apache.struts2.convention.annotation.Results;
import org.apache.struts2.interceptor.SessionAware;


@Results({
	  @Result(name="input", location="valueMapping.jsp"),
	  @Result(name="error", location="ImportSummary", type="redirectAction" ),
	  @Result(name="success", location="valueMapping.jsp" ),
	  @Result(name="json", location="json.jsp" )
	})

public class ValueMapping extends GeneralAction implements SessionAware {

	public static final String SESSION_VALUE_MAPPING = "gr.ntua.ivml.mint.valueMappingManager";
	protected final Logger log = Logger.getLogger(getClass());
	private long uploadId;
	private long mapId;
	private Lock lock;
	private long lockId;
	private String mapname;
	private String schemaname;
	private String command;
	private JSONObject json;
	private Map<String, Object> session;
	
	private MintIngestionValueMappingManager manager = new MintIngestionValueMappingManager();
		
	public JSONObject getJson() {
		return this.json;
	}
	
	public void setJson(JSONObject json) {
		this.json = json;
	}
	
	public long getUploadId() {
		return uploadId;
	}

	public void setUploadId(long uploadId) {
		this.uploadId = uploadId;
	}
	
	public long getMapId() {
		return mapId;
	}
	
	public String getMapname() {
		return DB.getMappingDAO().findById(mapId, false).getName();
	}
	
	public String getSchemaname() {
		Mapping m = DB.getMappingDAO().findById(mapId, false);
		return m.getTargetSchema().getName();
	}
	
	public void setMapId(long mapId) {
		this.mapId = mapId;
	}

	public long getLockId() {
		return lockId;
	}
	
	public String getCommand() {
		return command;
	}
	
	public void setCommand(String command) {
		this.command = command;
	}
	
	@Action(value="ValueMapping")
    public String execute() throws Exception {
		log.debug("command: " + this.getCommand());
		if(this.getCommand() == null) {
			DataUpload du = DB.getDataUploadDAO().getById(getUploadId(), false);
			Mapping mp=DB.getMappingDAO().findById(getMapId(), false);

			if(du != null && mp != null) {
				this.getManager().setDataUpload(du);

				lock=DB.getLockManager().directLock(getUser(), getSessionId(), mp );
				if(lock!=null) {
					this.lockId=lock.getDbID();
					return "success";
				} else {
					return "error";
				}
			} else {
				addActionError("Couldn't acquire lock on Mapping!");
			}
		} else {
			JSONObject response = null;
			try {
				response = this.getManager().execute(ServletActionContext.getRequest());
			} catch (Exception e) {
				e.printStackTrace();
				response = new JSONObject().element("error", e.getMessage());
			}
			
			log.debug(response);
			this.setJson(response);

			return "json";
		}

		return "json";
    }

	@Action("ValueMapping_input")
	@Override
	public String input() throws Exception {
    		if( (user.getOrganization() == null && !user.hasRight(User.SUPER_USER)) || !user.hasRight(User.MODIFY_DATA)) {
    			throw new IllegalAccessException( "No mapping rights!" );
    		}

		return super.input();
	}
	
	public String getUploadSchema() {
		log.debug( "getSchema called");
		TreeGenerationParser tgp = new TreeGenerationParser();
		DataUpload du = DB.getDataUploadDAO().findById(uploadId, false);

		try {
			return tgp.parseUpload(du);
		} catch( Exception e ) {
			log.error( "Problems with the DB",e );
		}

		return "";
	}
	
	public MintIngestionValueMappingManager getManager() {
		if(this.session.containsKey(SESSION_VALUE_MAPPING)) {
			return (MintIngestionValueMappingManager) this.session.get(SESSION_VALUE_MAPPING);
		} else {
			MintIngestionValueMappingManager manager = new MintIngestionValueMappingManager();
			this.session.put(SESSION_VALUE_MAPPING, manager);
			return manager;
		}
	}

	@Override
	public void setSession(Map<String, Object> arg0) {
		this.session = arg0;
	}
}