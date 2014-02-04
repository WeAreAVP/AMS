package gr.ntua.ivml.mint.valuemapping;

import java.util.HashMap;
import java.util.Map;

import javax.servlet.http.HttpServletRequest;

import net.sf.json.JSONObject;

import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.persistent.DataUpload;
import gr.ntua.ivml.mint.util.JSTree;
import gr.ntua.ivml.mint.xml.TreeGenerationParser;

public class MintIngestionValueMappingManager extends ValueMappingManager {
	private DataUpload du;
	private HashMap<String, String[]> commandParameters;
	
	public MintIngestionValueMappingManager() {
		//define required command parameters
		
		commandParameters = new HashMap<String, String[]>();
		//commandParameters.put("init", new String[] {"uploadId", "mapId"});
	}

	public void setDataUpload(DataUpload du) {
		this.du = du;
	}

	public DataUpload getDataUpload() {
		return du;
	}
	
	public void init() {
		super.init();
	}

	public JSONObject execute(HttpServletRequest request) {
		log.debug("execute request: " + request.getParameter("command"));
		Map parameterMap = request.getParameterMap();
		return this.execute((String) request.getParameter("command"), parameterMap);
	}
	
	public JSONObject execute(String command, Map arguments) {
		log.debug("execute: " + command);
		if(command == null) {
			return errorResponse("no command specified");
		} else {
			// check required parameters
			if(commandParameters.containsKey(command)) {
				String[] parameters = commandParameters.get(command);
				if(parameters != null) {
					String missing = require(arguments, parameters);
					if(missing != null) return errorResponse("parameter " + missing + "is missing");				
				}
			}
			
			if(command.equalsIgnoreCase("init")) {
				this.init();
			} else if(command.equalsIgnoreCase("schemaTree")) {
				JSTree jstree = new JSTree();

				try {			
					return new JSONObject().element("tree", jstree.getJSON(du));
				} catch( Exception e ) {
					log.error( "Problems with the DB",e );
					return errorResponse("could not generate schema tree");
				}
			} else {
				return super.execute(command, arguments);
			}
		}

		return errorResponse("unknown command");
	}
}
