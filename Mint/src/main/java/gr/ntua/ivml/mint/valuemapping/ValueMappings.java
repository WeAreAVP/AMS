package gr.ntua.ivml.mint.valuemapping;

import java.util.HashMap;
import java.util.Iterator;

import net.sf.json.JSONObject;

public class ValueMappings extends HashMap<String, String> {
	private static final long serialVersionUID = 213252506021617313L;

	public ValueMappings() {
	}
	
	public JSONObject toJSON() {
		JSONObject result = new JSONObject();
		
		Iterator<String> i = this.keySet().iterator();
		while(i.hasNext()) {
			String input = i.next();
			String output = this.get(input);
			
			result.element(input.toString(), output.toString());
		}
		
		return result;
	}
}
