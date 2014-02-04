
package gr.ntua.ivml.mint.actions;

import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.persistent.DataUpload;
import gr.ntua.ivml.mint.persistent.Lock;
import gr.ntua.ivml.mint.persistent.Mapping;
import gr.ntua.ivml.mint.persistent.User;
import gr.ntua.ivml.mint.util.Config;
import gr.ntua.ivml.mint.xml.TreeGenerationParser;

import org.apache.log4j.Logger;
import org.apache.struts2.convention.annotation.Action;
import org.apache.struts2.convention.annotation.Result;
import org.apache.struts2.convention.annotation.Results;


@Results({
	  @Result(name="input", location="editor.jsp"),
	  @Result(name="error", location="ImportSummary", type="redirectAction" ),
	  @Result(name="success", location="editor.jsp" )
	})

public class DoMapping extends GeneralAction  {

	protected final Logger log = Logger.getLogger(getClass());
	public String fileLoc;
	private long uploadId;
	private long mapid;
	private Lock lock;
	private long lockId;
	private String mapname;
	private String schemaname;
	
	
	public long getUploadId() {
		return uploadId;
	}

	public void setUploadId(long uploadId) {
		this.uploadId = uploadId;
	}
	
	public long getMapid() {
		return mapid;
	}
	
	public String getMapname() {
		return DB.getMappingDAO().findById(mapid, false).getName();
	}
	
	public String getSchemaname() {
		Mapping m = DB.getMappingDAO().findById(mapid, false);
		return m.getTargetSchema().getName();
	}
	
	
	public void setMapid(long mapid) {
		this.mapid = mapid;
	}

	public long getLockId() {
		return lockId;
	}
	

	
	@Action(value="DoMapping")
    public String execute() throws Exception {
			DataUpload du = DB.getDataUploadDAO().getById(getUploadId(), false);
			Mapping mp=DB.getMappingDAO().findById(getMapid(), false);
			if( du != null  && mp!=null)
			{
					lock=DB.getLockManager().directLock(getUser(), getSessionId(), mp );
			        if(lock!=null)	{
			        	this.lockId=lock.getDbID();
			  		return "success";}
			        else return "error";
				} else {
					addActionError("Couldn't acquire lock on Mapping!");
				}
			return "error";
    }

	public String getFileLoc(){
		
		fileLoc= Config.get("targetDefinition") ;
		return fileLoc;
	}
	
	@Action("DoMapping_input")
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
		return "damn";
	}
}