package gr.ntua.ivml.mint.valuemapping;

import java.util.HashMap;
import java.util.Iterator;
import java.util.Map;

import gr.ntua.ivml.mint.mapping.MappingManager;

import net.sf.json.JSONObject;

import org.apache.log4j.Logger;

public class ValueMappingManager {
	protected static final Logger log = Logger.getLogger( MappingManager.class);
	
	HashMap<Object, ValueMappings> mappings = new HashMap<Object, ValueMappings>();
	
	public ValueMappingManager() {
		
	}

	public void init() {
	}

	public JSONObject execute(String command, Map arguments) {
		JSONObject result = new JSONObject();
		
		if(command.equalsIgnoreCase("init")) {
		} else if(command.equalsIgnoreCase("schemaTree")) {
		} else if(command.equalsIgnoreCase("addMapping")) {
			Object key = ((Object[]) arguments.get("key"))[0];
			this.addMapping(key, ((String[]) arguments.get("input"))[0], ((String[]) arguments.get("output"))[0]);
			result = this.getMappings(key).toJSON();
		} else if(command.equalsIgnoreCase("removeMapping")) {
			Object key = ((Object[]) arguments.get("key"))[0];
			this.removeMapping(key, ((String[]) arguments.get("input"))[0]);
			result = this.getMappings(key).toJSON();
		} else if(command.equalsIgnoreCase("getMappings")) {
			Object key = ((Object[]) arguments.get("key"))[0];
			result = this.getMappings(key).toJSON();
		} else {
			return errorResponse("unknown command");
		}
		
		return result;
	}
	
	public HashMap<Object, ValueMappings> getMappings() {
		return this.mappings;
	}
	
	private ValueMappings getMappings(Object key) {
		ValueMappings list;
		
		if(mappings.containsKey(key)) {
			list = mappings.get(key);
		} else {
			list = new ValueMappings();
			mappings.put(key, list);
		}
		
		return list;
	}
	
	public void addMapping(Object key, String input, String output) {
		ValueMappings list = this.getMappings(key);
		list.put(input, output);
	}
	
	public void removeMapping(Object key, String input) {
		ValueMappings list = this.getMappings(key);
		list.remove(input);
	}
	
	public JSONObject toJSON() {
		JSONObject result = new JSONObject();
		
		Iterator<Object> i = this.mappings.keySet().iterator();
		while(i.hasNext()) {
			Object key = i.next();
			ValueMappings value = this.mappings.get(key);
			result.element(key.toString(), value.toJSON());
		}
		
		return result;
	}

	protected JSONObject errorResponse(String message) {
		return new JSONObject().element("error", message);
	}
	
	protected String require(Map arguments, String[] required) {
		for(String parameter: required) {
			if((String) (arguments.get(parameter)) == null) return parameter;
		}
		
		return null;
	}
}
