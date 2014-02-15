package gr.ntua.ivml.mint.test;

import gr.ntua.ivml.mint.mapping.JSONMappingHandler;
import gr.ntua.ivml.mint.util.StringUtils;
import java.io.File;
import java.util.Iterator;

import net.sf.json.JSONArray;
import net.sf.json.JSONObject;
import net.sf.json.JSONSerializer;

public class MappingMigrationTest {

	/**
	 * @param args
	 */
	public static void main(String[] args) {
		try {
			String lido09 = StringUtils.fileContents(new File("/Users/mith/Desktop/lido-0.9.json")).toString();
			String lido10 = StringUtils.fileContents(new File("/Users/mith/Desktop/lido-1.0.json")).toString();

			JSONObject json09 = (JSONObject) JSONSerializer.toJSON(lido09);
			JSONObject json10 = (JSONObject) JSONSerializer.toJSON(lido10);
			
			System.out.println("LIDO 0.9");
			displayPaths(json09);
			System.out.println("LIDO 1.0");
			displayPaths(json10);
		} catch(Exception e) {
			e.printStackTrace();
		}		
	}

	public static void displayPaths(JSONObject json) {		
			JSONArray groups = json.getJSONArray("groups");
			Iterator i = groups.iterator();
			while(i.hasNext()) {
				JSONObject group = (JSONObject) i.next();
				JSONObject contents = group.getJSONObject("contents");
				displayPaths("/", contents);
			}
	}
	
	private static void displayPaths(String root, JSONObject json) {
		String name = json.getString("name");
		
		System.out.println(root + name);

		if(json.has("attributes")) {
			JSONArray array = json.getJSONArray("attributes");
			Iterator i = array.iterator();
			while(i.hasNext()) {
				JSONObject object = (JSONObject) i.next();
				displayPaths(root + name + "/", object);
			}
		}

		if(json.has("children")) {
			JSONArray array = json.getJSONArray("children");
			Iterator i = array.iterator();
			while(i.hasNext()) {
				JSONObject object = (JSONObject) i.next();
				displayPaths(root + name + "/", object);
			}
		}
	}
}