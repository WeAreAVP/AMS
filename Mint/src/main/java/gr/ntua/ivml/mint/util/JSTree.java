package gr.ntua.ivml.mint.util;

import java.util.List;
import net.sf.json.JSONArray;
import net.sf.json.JSONObject;
import gr.ntua.ivml.mint.persistent.DataUpload;
import gr.ntua.ivml.mint.persistent.XpathHolder;

public class JSTree {
	public JSTree() {
	}
	
	public JSONArray getJSON(DataUpload du) {
		JSONArray result = new JSONArray();
		
		List<? extends TraversableI> children = du.getRootXpath().getChildren();
		result = this.getJSON(children);
		
		
		return result;
	}

	public JSONArray getJSON(List<? extends TraversableI> children) {
		JSONArray result = new JSONArray();
		
		for(TraversableI t : children) {
			XpathHolder xp = (XpathHolder) t;
			JSONObject child = this.getJSON(xp);
			result.add(child);
		}

		return result;
	}

	public JSONObject getJSON(XpathHolder xp) {
		JSONObject result = new JSONObject();
		
		JSONObject data = new JSONObject();
		data.element("title", xp.getName());
		result.element("data", data);
		result.element("metadata", new JSONObject().element("xpath", xp.getXpathWithPrefix(true)));

		
		List<? extends TraversableI> children = xp.getChildren();
		result.element("children", this.getJSON(children));
		
		return result;
	}
}