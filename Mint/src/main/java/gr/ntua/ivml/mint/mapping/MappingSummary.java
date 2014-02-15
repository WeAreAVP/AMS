package gr.ntua.ivml.mint.mapping;

import java.util.ArrayList;
import java.util.Collection;
import java.util.HashSet;
import java.util.Hashtable;
import java.util.Iterator;
import java.util.List;
import java.util.Map;
import java.util.Set;
import java.util.Map.Entry;

import net.sf.json.JSONArray;
import net.sf.json.JSONObject;
import net.sf.json.JSONSerializer;

import gr.ntua.ivml.mint.persistent.DataUpload;
import gr.ntua.ivml.mint.persistent.Mapping;
import gr.ntua.ivml.mint.persistent.XpathHolder;
import gr.ntua.ivml.mint.util.TraversableI;

public class MappingSummary {
	private static Collection<String> explicitMandatoryElements(Mapping mapping) {
		if(mapping.getJsonString() != null) {
			return MappingSummary.explicitMandatoryElements(mapping.getJsonString());
		}
		
		return new ArrayList<String>();
	}
	
	private static Collection<String> explicitMandatoryElements(String json) {
		JSONObject object = (JSONObject) JSONSerializer.toJSON(json);
		if(object != null) {
			return MappingSummary.explicitMandatoryElements(object);
		}
		
		return new ArrayList<String>();
	}
	
	private static Collection<String> explicitMandatoryElements(JSONObject object) {
		ArrayList<String> result = new ArrayList<String>();
		if(object.has("mandatory")) {
			JSONArray array = object.getJSONArray("mandatory");
			Iterator iterator = array.iterator();
			while(iterator.hasNext()) {
				String string = (String) iterator.next();
				result.add(string);
			}
		} else {
//			explicitly add xpaths here..
//			result.add("/objectClassificationWrap/objectWorkTypeWrap/objectWorkType/term");
//			result.add("/objectIdentificationWrap/titleWrap/titleSet/appellationValue");
//			result.add("/recordWrap/recordInfoSet/recordInfoLink");
		}
		
		return result;
	}
	
	public static Collection<String> explicitMandatoryIds(JSONObject object) {
		Collection<String> result = new ArrayList<String>();
		Collection<String> mandatory = MappingSummary.explicitMandatoryElements(object);
		
		for(String m: mandatory) {
			result.addAll(MappingSummary.getIdsForPath(object, m));
		}
		
		return result;
	}
	
	public static boolean isComplete(Mapping mapping) {
		if(mapping.getJsonString() != null) {
			return MappingSummary.isComplete(mapping.getJsonString());
		}
		
		return false;
	}
	
	public static boolean isComplete(String json) {
		JSONObject object = (JSONObject) JSONSerializer.toJSON(json);
		if(object != null) {
			return MappingSummary.isComplete(object);
		}
		
		return false;
	}
	
	public static boolean isComplete(JSONObject object) {
		Collection<String> collection = MappingSummary.getMissingMappings(object);
		if(collection.isEmpty()) {
			return true;
		} else {
			return false;
		}
	}
	
	public static Map<String, String> getMappedItems(Mapping mapping) {
		if(mapping.getJsonString() != null) {
			return MappingSummary.getMappedItems(mapping.getJsonString());
		}
		
		return null;
	}
	
	public static Map<String, String> getMappedItems(String json) {
		JSONObject object = (JSONObject) JSONSerializer.toJSON(json);
		if(object != null) {
			return MappingSummary.getMappedItems(object);
		}
		
		return null;
	}
	
	public static Map<String, String> getMappedItems(JSONObject object) {
		Map<String, String> mappedItems = new Hashtable<String, String>();
		if(object.has("groups")) {
			JSONArray groups = object.getJSONArray("groups");
			Iterator gi = groups.iterator();
			while(gi.hasNext()) {
				JSONObject g = (JSONObject) gi.next();
				JSONObject contents = g.getJSONObject("contents");
				mappedItems.putAll(MappingSummary.getMappedItems(contents));
			}
			mappedItems.putAll(MappingSummary.getMappedItems(object.getJSONObject("template")));
		} else {	
			String item = "/" + object.getString("name");
	
			if(object.has("mappings") && object.getJSONArray("mappings").size() > 0) {
				JSONArray mappings = object.getJSONArray("mappings");
				
				String value = mappings.getJSONObject(0).getString("value");
				for(int i = 1; i < mappings.size(); i++) {
					value += ", " + mappings.getJSONObject(i).getString("value");
				}
				mappedItems.put(item, value);
			}
			
			if(object.has("attributes")) {
				JSONArray attributes = object.getJSONArray("attributes");
				for(int i = 0; i < attributes.size(); i++) {
					Map<String, String> result = MappingSummary.getMappedItems(attributes.getJSONObject(i));
					Set<Entry<String, String>> set = result.entrySet();
					for(Entry<String, String> e: set) {
						mappedItems.put(item + e.getKey(), e.getValue());
					}
				}
			}
			
			if(object.has("children")) {
				JSONArray children = object.getJSONArray("children");
				for(int i = 0; i < children.size(); i++) {
					Map<String, String> result = MappingSummary.getMappedItems(children.getJSONObject(i));
					Set<Entry<String, String>> set = result.entrySet();
					for(Entry<String, String> e: set) {
						mappedItems.put(item + e.getKey(), e.getValue());
					}
				}
			}
		}
		
		return mappedItems;
	}
	
	public static Collection<String> getMappedXPathList(JSONObject object) {
		Collection<String> result = new ArrayList<String>();
		Map<String, String> mappedItems = getMappedXPaths(object);
		Iterator<String> i = mappedItems.keySet().iterator();
		
		while(i.hasNext()) {
			String value = mappedItems.get(i.next());
			String tokens[] = value.split(", ");
			//System.out.println(value + " " + tokens.toString());
			for(String s: tokens) {
				if(!result.contains(s)) {
					result.add(s);
				}
			}
		}
		
		return result;
	}
	
	public static Map<String, String> getMappedXPaths(JSONObject object) {
		Map<String, String> mappedItems = new Hashtable<String, String>();
		if(object.has("groups")) {
			JSONArray groups = object.getJSONArray("groups");
			Iterator gi = groups.iterator();
			while(gi.hasNext()) {
				JSONObject g = (JSONObject) gi.next();
				JSONObject contents = g.getJSONObject("contents");
				mappedItems.putAll(MappingSummary.getMappedXPaths(contents));
			}
			mappedItems.putAll(MappingSummary.getMappedXPaths(object.getJSONObject("template")));
		} else {	
			String item = "/" + object.getString("name");
	
			if(object.has("mappings") && object.getJSONArray("mappings").size() > 0) {
				JSONArray mappings = object.getJSONArray("mappings");
				
				String value = "";
				for(int i = 0; i < mappings.size(); i++) {
					JSONObject m = mappings.getJSONObject(i);
					if(m.getString("type").equalsIgnoreCase("xpath")) {
						if(value.length() > 0) { value += ", "; }
						value += m.getString("value");
					}
				}
				
				if(value.length() > 0) {
					mappedItems.put(item, value);
				}
			}
			
			if(object.has("attributes")) {
				JSONArray attributes = object.getJSONArray("attributes");
				for(int i = 0; i < attributes.size(); i++) {
					Map<String, String> result = MappingSummary.getMappedXPaths(attributes.getJSONObject(i));
					Set<Entry<String, String>> set = result.entrySet();
					for(Entry<String, String> e: set) {
						mappedItems.put(item + e.getKey(), e.getValue());
					}
				}
			}
			
			if(object.has("children")) {
				JSONArray children = object.getJSONArray("children");
				for(int i = 0; i < children.size(); i++) {
					Map<String, String> result = MappingSummary.getMappedXPaths(children.getJSONObject(i));
					Set<Entry<String, String>> set = result.entrySet();
					for(Entry<String, String> e: set) {
						mappedItems.put(item + e.getKey(), e.getValue());
					}
				}
			}
		}
		
		return mappedItems;
	}
	
	public static Collection<String> getMissingMappings(Mapping mapping) {
		if(mapping.getJsonString() != null) {
			return MappingSummary.getMissingMappings(mapping.getJsonString());
		}
		
		return null;
	}
	
	public static Collection<String> getMissingMappings(String json) {
		JSONObject object = (JSONObject) JSONSerializer.toJSON(json);
		if(object != null) {
			return MappingSummary.getMissingMappings(object);
		}
		
		return null;
	}
	
	public static Collection<String> getMissingMappings(JSONObject object) {
		Collection<String> mandatory = MappingSummary.explicitMandatoryElements(object);
		Collection<String> result = MappingSummary.getMissingMappings(object, mandatory);
		Map<String, String> map = MappingSummary.getMappedItems(object);
		result.addAll(MappingSummary.checkMandatory(map, mandatory));

		return result;
	}
	
	private static Collection<String> checkMandatory(Map<String, String> map, Collection<String> mandatory) {
		Collection<String> result = new ArrayList<String>();
		
		for(String m: mandatory) {
			if(!map.containsKey(m)) {
				result.add(m);
			}
		}
		
		return result;
	}
	
	private static Collection<String> getMissingMappings(JSONObject object, Collection<String> mandatory) {
		Collection<String> missingMappings = new ArrayList<String>();

		if(object.has("groups")) {
			JSONArray groups = object.getJSONArray("groups");
			Iterator gi = groups.iterator();
			while(gi.hasNext()) {
				JSONObject g = (JSONObject) gi.next();
				JSONObject contents = g.getJSONObject("contents");
				missingMappings.addAll(MappingSummary.getMissingMappings(contents, mandatory));
			}
			
			JSONObject template = object.getJSONObject("template");
			missingMappings.addAll(MappingSummary.getMissingMappings(template, mandatory));
		} else {	
			boolean hasMappings = MappingSummary.descendantHasMappings(object);
			boolean isMandatory = object.has("mandatory") || (object.has("minOccurs") && object.getInt("minOccurs") > 0);
			String item = "/" + object.getString("name");		
			if( hasMappings || isMandatory ) {
			//String item = object.getString("id");			

			//if(MappingSummary.descendantHasMappings(object)) {		
				if(object.has("attributes")) {
					JSONArray attributes = object.getJSONArray("attributes");
					for(int i = 0; i < attributes.size(); i++) {
						JSONObject child = attributes.getJSONObject(i);
						String childName = item + "/" + child.getString("name");
						boolean descendantHasMappings = MappingSummary.descendantHasMappings(child);
						//System.out.println(childName + " = " + descendantHasMappings); 
						if((child.has("mandatory") || (child.has("minOccurs") && child.getInt("minOccurs") > 0)) && !descendantHasMappings && !child.has("default")) {
							String info = "";
							if(child.has("mandatory")) { info += " (" + child.getString("mandatory") + ")"; }
							missingMappings.add(childName + info);
						}
					}
				}
				
				if(object.has("children")) {
					JSONArray children = object.getJSONArray("children");
					for(int i = 0; i < children.size(); i++) {
						JSONObject child = children.getJSONObject(i);
						String childName = item + "/" + child.getString("name");
						boolean descendantHasMappings = MappingSummary.descendantHasMappings(child);
						
						if((child.has("mandatory") || (child.has("minOccurs") && child.getInt("minOccurs") > 0)) && !descendantHasMappings) {
							String info = "";
							if(child.has("mandatory")) { info += " (" + child.getString("mandatory") + ")"; }
							missingMappings.add(childName + info);
						}
												
						Collection<String> result = MappingSummary.getMissingMappings(child, mandatory);
						for(String s: result) {
							String it = item + s;
							if(!missingMappings.contains(it)) 
								missingMappings.add(it);
						}
					}
				} else {
					if((object.has("mandatory") || (object.has("minOccurs") && object.getInt("minOccurs") > 0)) && (!object.has("mappings") || object.getJSONArray("mappings").size() == 0)) {
						String info = "";
						if(object.has("mandatory")) { info += " (" + object.getString("mandatory") + ")"; }
						//missingMappings.add(object + info);
						String it = item;
						if(!missingMappings.contains(it)) 
							missingMappings.add(it);
					}
				}
			}			
		}
		
		return missingMappings;
	}
	
	public static Map<String, String> getSummary(Mapping mapping) {
		if(mapping.getJsonString() != null) {
			return MappingSummary.getSummary(mapping.getJsonString());
		}
		
		return null;
	}
	
	public static Map<String, String> getSummary(String json) {
		JSONObject object = (JSONObject) JSONSerializer.toJSON(json);
		if(object != null) {
			return MappingSummary.getSummary(object);
		}
		
		return null;
	}
	
	public static Map<String, String> getSummary(JSONObject object) {
		Map<String, String> summary = new Hashtable<String, String>();

		if(object.has("groups")) {
			JSONArray groups = object.getJSONArray("groups");
			Iterator gi = groups.iterator();
			while(gi.hasNext()) {
				JSONObject g = (JSONObject) gi.next();
				JSONObject contents = g.getJSONObject("contents");
				summary.putAll(MappingSummary.getSummary(contents));
			}
			
			summary.putAll(MappingSummary.getSummary(object.getJSONObject("template")));
		} else {	
			String item = object.getString("name");
	
			if(object.has("mappings") && object.getJSONArray("mappings").size() > 0) {
				JSONArray mappings = object.getJSONArray("mappings");
				
				String value = mappings.getJSONObject(0).getString("value");
				for(int i = 1; i < mappings.size(); i++) {
					value += ", " + mappings.getJSONObject(i).getString("value");
				}
				summary.put(item, value);
			} else {
				summary.put(item, "");
			}
			
			if(object.has("attributes")) {
				JSONArray attributes = object.getJSONArray("attributes");
				for(int i = 0; i < attributes.size(); i++) {
					summary.putAll(MappingSummary.getSummary(attributes.getJSONObject(i)));
				}
			}
			
			if(object.has("children")) {
				JSONArray children = object.getJSONArray("children");
				for(int i = 0; i < children.size(); i++) {
					summary.putAll(MappingSummary.getSummary(children.getJSONObject(i)));
				}
			}
		}
		
		return summary;
	}
	
	public static Collection<String> getInvalidXPaths(DataUpload du, Mapping mapping) {
		if(mapping.getJsonString() != null) {
			return MappingSummary.getInvalidXPaths(du, mapping.getJsonString());
		}
		
		return null;
	}

	public static Collection<String> getInvalidXPaths(DataUpload du, String mapping) {
		JSONObject object = (JSONObject) JSONSerializer.toJSON(mapping);
		if(object != null) {
			return MappingSummary.getInvalidXPaths(du, object);
		}
		
		return null;
	}
	
	public static Collection<String> getInvalidXPaths(DataUpload du, JSONObject mapping) {
		ArrayList<String> list = new ArrayList<String>();
		ArrayList<String> uploadList = du.listOfXPaths();

		Map<String, String> mapped = MappingSummary.getMappedXPaths(mapping);
		Collection<String> values = mapped.values();
		Iterator<String> i = values.iterator();

		while(i.hasNext()) {
			String value = i.next();
			String[] tokens = value.split(", ");
			for(String s: tokens) {
				if(!uploadList.contains(s)) {
					list.add(s);
				}
			}
		}
		
		return list;
	}

	public static boolean isInvalidTemplate(DataUpload du, Mapping mapping) {
		if(mapping.getJsonString() != null) {
			return MappingSummary.isInvalidTemplate(du, mapping.getJsonString());
		}
		
		return true;
	}

	public static boolean isInvalidTemplate(DataUpload du, String mapping) {
		JSONObject object = (JSONObject) JSONSerializer.toJSON(mapping);
		if(object != null) {
			return MappingSummary.isInvalidTemplate(du, object);
		}
		
		return true;
	}
	
	public static boolean isInvalidTemplate(DataUpload du, JSONObject mapping) {
		ArrayList<String> uploadList = du.listOfXPaths();
		boolean result = true;

		Map<String, String> mapped = MappingSummary.getMappedXPaths(mapping);
		Collection<String> values = mapped.values();
		Iterator<String> i = values.iterator();

		while(i.hasNext()) {
			String value = i.next();
			String[] tokens = value.split(",");
			for(String s: tokens) {
				if(uploadList.contains(s)) {
					result = false;
				}
			}
		}
		
		return result;
	}

	public static Collection<String> mappingsWithXPath(Mapping mapping, String xpathWithPrefix) {
		return MappingSummary.mappingsWithXPath(mapping.getJsonString(), xpathWithPrefix);
	}

	public static Collection<String> mappingsWithXPath(String mapping, String xpathWithPrefix) {
		return MappingSummary.mappingsWithXPath((JSONObject) JSONSerializer.toJSON(mapping), xpathWithPrefix);
	}

	public static Collection<String> mappingsWithXPath(JSONObject mapping, String xpathWithPrefix) {
		ArrayList<String> xpaths = new ArrayList<String>();
		Map<String, String> map = MappingSummary.getMappedXPaths(mapping);
		Set<Entry<String, String>> set = map.entrySet();
		Iterator<Entry<String, String>> it = set.iterator();
		while(it.hasNext()) {
			Entry<String, String> entry = it.next();
			String[] tokens = entry.getValue().split(",");
			for(String s: tokens) {
				//System.out.println(entry.getKey() + ": " + entry.getValue() + " " + xpathWithPrefix + " --- " + entry.getValue().indexOf(xpathWithPrefix));
				if(s.equalsIgnoreCase(xpathWithPrefix)) {
					xpaths.add(entry.getKey());
				}
			}
		}
		
		return xpaths;
	}
	
	private static boolean descendantHasMappings(JSONObject item) {
		return MappingSummary.descendantHasMappings(item, null);
	}

	private static boolean descendantHasMappings(JSONObject item, JSONArray groups) {
		JSONArray mappings = null;
		if(item.has("mappings")) { mappings = item.getJSONArray("mappings"); }
		
		if(item.has("type") && item.getString("type").equals("group")) {
			if(groups == null) return false;

			Iterator gi = groups.iterator();
			while(gi.hasNext()) {
				JSONObject group = (JSONObject) gi.next();
				String gname = group.getString("element");
				if(item.getString("name").equals(gname)) {
					String groupType = "element";
					if(group.has("type")) {
						groupType = group.getString("type");
					}
					
					JSONObject content = group.getJSONObject("contents");
					return MappingSummary.descendantHasMappings(content);
				}
			}
		} else if(mappings != null && mappings.size() > 0) {
			return true;
		} else {
			if(item.has("children")) {
				JSONArray children = item.getJSONArray("children");
				if(children != null && children.size() > 0) {
					Iterator ci = children.iterator();
					while(ci.hasNext()) {
						JSONObject child = (JSONObject) ci.next();
						if(MappingSummary.descendantHasMappings(child)) {
							return true;
						}
					}
				}
			}

			if(item.has("attributes")) {
				JSONArray attributes = item.getJSONArray("attributes");
				if(attributes != null && attributes.size() > 0) {
					Iterator ai = attributes.iterator();
					while(ai.hasNext()) {
						JSONObject attribute = (JSONObject) ai.next();
						if(MappingSummary.descendantHasMappings(attribute)) {
							//System.out.println(item.get("name") + "has attribute " + attribute.get("name") + " that has Mappings");
							return true;
						}
					}
				}
			}
		}
		
		return false;
	}
	
	public static Collection<String> getIdsForElementsWithMappingsInside(JSONObject object) {
		ArrayList<String> ids = new ArrayList<String>();

		if(object.has("groups")) {
			JSONArray groups = object.getJSONArray("groups");
			Iterator gi = groups.iterator();
			while(gi.hasNext()) {
				JSONObject g = (JSONObject) gi.next();
				JSONObject contents = g.getJSONObject("contents");
				ids.addAll(MappingSummary.getIdsForElementsWithMappingsInside(contents));
			}
			JSONObject template = object.getJSONObject("template"); 
			ids.addAll(MappingSummary.getIdsForElementsWithMappingsInside(template));
		} else {
			if(MappingSummary.descendantHasMappings(object)) {
				ids.add(object.getString("id"));
	
				if(object.has("children")) {
					JSONArray children = object.getJSONArray("children");
					Iterator i = children.iterator();
					while(i.hasNext()) {
						JSONObject child = (JSONObject) i.next();
						Collection<String> result = MappingSummary.getIdsForElementsWithMappingsInside(child);
						ids.addAll(result);
					}
				}
				
				if(object.has("attributes")) {
					JSONArray attributes = object.getJSONArray("attributes");
					Iterator i = attributes.iterator();
					while(i.hasNext()) {
						JSONObject attribute = (JSONObject) i.next();
						ids.addAll(MappingSummary.getIdsForElementsWithMappingsInside(attribute));
					}
				}
			}
		}
		
		return ids;
	}

	public static Collection<String> getIdsForElementsWithMappedAttributes(JSONObject object) {
		ArrayList<String> ids = new ArrayList<String>();

		if(object.has("groups")) {
			JSONArray groups = object.getJSONArray("groups");
			Iterator gi = groups.iterator();
			while(gi.hasNext()) {
				JSONObject g = (JSONObject) gi.next();
				JSONObject contents = g.getJSONObject("contents");
				ids.addAll(MappingSummary.getIdsForElementsWithMappedAttributes(contents));
			}
			JSONObject template = object.getJSONObject("template"); 
			ids.addAll(MappingSummary.getIdsForElementsWithMappedAttributes(template));
		} else {
			if(object.has("attributes")) {
				JSONArray attributes = object.getJSONArray("attributes");
				Iterator i = attributes.iterator();
				boolean hasMapped = false;
				boolean hasMissing = false;
				while(i.hasNext()) {
					JSONObject attribute = (JSONObject) i.next();
					if(attribute.has("mappings") && attribute.getJSONArray("mappings").size() > 0) {
						hasMapped = true;
					}
					if((attribute.has("mandatory") || (attribute.has("minOccurs") && attribute.getInt("minOccurs") > 0)) && !MappingSummary.descendantHasMappings(attribute) && !attribute.has("default")) {
						hasMissing = true;
					}
				}
				
				if(hasMapped && !hasMissing) ids.add(object.getString("id"));
			}

			if(object.has("children")) {
					JSONArray children = object.getJSONArray("children");
					Iterator i = children.iterator();
					while(i.hasNext()) {
						JSONObject child = (JSONObject) i.next();
						Collection<String> result = MappingSummary.getIdsForElementsWithMappedAttributes(child);
						ids.addAll(result);
					}
			}	
		}
		
		return ids;
	}

	public static Collection<String> getIdsForElementsWithMissingAttributes(JSONObject object) {
		ArrayList<String> ids = new ArrayList<String>();

		if(object.has("groups")) {
			JSONArray groups = object.getJSONArray("groups");
			Iterator gi = groups.iterator();
			while(gi.hasNext()) {
				JSONObject g = (JSONObject) gi.next();
				JSONObject contents = g.getJSONObject("contents");
				ids.addAll(MappingSummary.getIdsForElementsWithMissingAttributes(contents));
			}
			JSONObject template = object.getJSONObject("template"); 
			ids.addAll(MappingSummary.getIdsForElementsWithMissingAttributes(template));
		} else {
			if(MappingSummary.descendantHasMappings(object) && object.has("attributes")) {
				JSONArray attributes = object.getJSONArray("attributes");
				Iterator i = attributes.iterator();
				boolean hasMissing = false;
				while(i.hasNext()) {
					JSONObject attribute = (JSONObject) i.next();
					if((attribute.has("mandatory") || (attribute.has("minOccurs") && attribute.getInt("minOccurs") > 0)) && !MappingSummary.descendantHasMappings(attribute) && !attribute.has("default")) {
						hasMissing = true;
					}
				}
				
				if(hasMissing) ids.add(object.getString("id"));
			}

			if(object.has("children")) {
					JSONArray children = object.getJSONArray("children");
					Iterator i = children.iterator();
					while(i.hasNext()) {
						JSONObject child = (JSONObject) i.next();
						Collection<String> result = MappingSummary.getIdsForElementsWithMissingAttributes(child);
						ids.addAll(result);
					}
			}	
		}
		
		return ids;
	}

	public static Collection<String> getIdsForElementsWithNoMappedAttributes(JSONObject object) {
		ArrayList<String> ids = new ArrayList<String>();

		if(object.has("groups")) {
			JSONArray groups = object.getJSONArray("groups");
			Iterator gi = groups.iterator();
			while(gi.hasNext()) {
				JSONObject g = (JSONObject) gi.next();
				JSONObject contents = g.getJSONObject("contents");
				ids.addAll(MappingSummary.getIdsForElementsWithNoMappedAttributes(contents));
			}
			JSONObject template = object.getJSONObject("template"); 
			ids.addAll(MappingSummary.getIdsForElementsWithNoMappedAttributes(template));
		} else {
			if(object.has("attributes")) {
				JSONArray attributes = object.getJSONArray("attributes");
				Iterator i = attributes.iterator();
				boolean hasMapped = false;
				boolean hasMissing = false;
				while(i.hasNext()) {
					JSONObject attribute = (JSONObject) i.next();
					if(attribute.has("mappings") && attribute.getJSONArray("mappings").size() > 0) {
						hasMapped = true;
					}
					if((attribute.has("mandatory") || (attribute.has("minOccurs") && attribute.getInt("minOccurs") > 0)) && !MappingSummary.descendantHasMappings(attribute) && !attribute.has("default")) {
						hasMissing = true;
					}
				}
				
				if(!hasMapped && !hasMissing && MappingSummary.descendantHasMappings(object)) ids.add(object.getString("id"));
			}

			if(object.has("children")) {
					JSONArray children = object.getJSONArray("children");
					Iterator i = children.iterator();
					while(i.hasNext()) {
						JSONObject child = (JSONObject) i.next();
						Collection<String> result = MappingSummary.getIdsForElementsWithNoMappedAttributes(child);
						ids.addAll(result);
					}
			}	
		}
		
		return ids;
	}

	public static Collection<String> getIdsForElementsWithNoMappingsInside(JSONObject object) {
		ArrayList<String> ids = new ArrayList<String>();

		if(object.has("groups")) {
			JSONArray groups = object.getJSONArray("groups");
			Iterator gi = groups.iterator();
			while(gi.hasNext()) {
				JSONObject g = (JSONObject) gi.next();
				JSONObject contents = g.getJSONObject("contents");
				ids.addAll(MappingSummary.getIdsForElementsWithNoMappingsInside(contents));
			}
			JSONObject template = object.getJSONObject("template"); 
			ids.addAll(MappingSummary.getIdsForElementsWithNoMappingsInside(template));
		} else {
			if(!MappingSummary.descendantHasMappings(object)) {
				ids.add(object.getString("id"));
			}
	
			{
				if(object.has("children")) {
					JSONArray children = object.getJSONArray("children");
					Iterator i = children.iterator();
					while(i.hasNext()) {
						JSONObject child = (JSONObject) i.next();
						ids.addAll(MappingSummary.getIdsForElementsWithNoMappingsInside(child));
					}
				}
				
				if(object.has("attributes")) {
					JSONArray attributes = object.getJSONArray("attributes");
					Iterator i = attributes.iterator();
					while(i.hasNext()) {
						JSONObject attribute = (JSONObject) i.next();
						ids.addAll(MappingSummary.getIdsForElementsWithNoMappingsInside(attribute));
					}
				}
			}
		}
		
		return ids;
	}
	
	private static Collection<String> getIdsForPath(JSONObject object, String path) {
		Collection<String> result = new ArrayList<String>();

		if(object.has("groups")) {
			JSONArray groups = object.getJSONArray("groups");
			Iterator gi = groups.iterator();
			while(gi.hasNext()) {
				JSONObject g = (JSONObject) gi.next();
				JSONObject contents = g.getJSONObject("contents");
				result.addAll(MappingSummary.getIdsForPath(contents, path));
			}
			JSONObject template = object.getJSONObject("template");
			result.addAll(MappingSummary.getIdsForPath(template, path));
		} else {
			String[] tokens = path.split("/", 3);
			if(tokens.length > 1) {
				String element = tokens[1];
				if(object.has("name")) {
					String name = object.getString("name");
					String id = object.getString("id");
					if(name.equals(element)) {
						result.add(id);
						
						if(tokens.length > 2) {
							String tail = tokens[2];
							JSONArray children = null;
							if(tail.startsWith("@") && (object.has("attributes"))) {
								children = object.getJSONArray("attributes");
							} else {
								children = object.getJSONArray("children");
							}
							
							Iterator i = children.iterator();
							while(i.hasNext()) {
								JSONObject child = (JSONObject) i.next();
								Collection<String> childIds = MappingSummary.getIdsForPath(child, "/" + tail);
								if(!childIds.isEmpty()) {
									result.addAll(childIds);
									break;
								}
							}
						}
					}
				}
			}
		}
		
		return result;
	}
	
	public static Collection<String> getMissingMappingsIds(JSONObject object) {
		Collection<String> missingMappings = new ArrayList<String>();

		if(object.has("groups")) {
			JSONArray groups = object.getJSONArray("groups");
			Iterator gi = groups.iterator();
			while(gi.hasNext()) {
				JSONObject g = (JSONObject) gi.next();
				JSONObject contents = g.getJSONObject("contents");
				missingMappings.addAll(MappingSummary.getMissingMappingsIds(contents));
			}
			
			JSONObject template = object.getJSONObject("template");
			missingMappings.addAll(MappingSummary.getMissingMappingsIds(template));

			Collection<String> mandatory = MappingSummary.explicitMandatoryElements(object);
		} else {
			boolean hasMappings = MappingSummary.descendantHasMappings(object);
			boolean isMandatory = object.has("mandatory") || (object.has("minOccurs") && object.getInt("minOccurs") > 0);
			if( hasMappings || isMandatory ) {
				String item = object.getString("id");			
		
				if(object.has("attributes")) {
					JSONArray attributes = object.getJSONArray("attributes");
					for(int i = 0; i < attributes.size(); i++) {
						JSONObject child = attributes.getJSONObject(i);
						
						if((child.has("mandatory") || (child.has("minOccurs") && child.getInt("minOccurs") > 0)) && !MappingSummary.descendantHasMappings(child) && !child.has("default")) {
							String it = child.getString("id");
							if(!missingMappings.contains(it)) 
								missingMappings.add(it);
						}												
					}
				}
				
				if(object.has("children")) {
					JSONArray children = object.getJSONArray("children");
					for(int i = 0; i < children.size(); i++) {
						JSONObject child = children.getJSONObject(i);
						
						Collection<String> result = MappingSummary.getMissingMappingsIds(child);
						for(String s: result) {
							String it = s;
							if(!missingMappings.contains(it))
								missingMappings.add(it);
						}

						if((child.has("mandatory") || (child.has("minOccurs") && child.getInt("minOccurs") > 0)) && !MappingSummary.descendantHasMappings(child)) {
							String it = child.getString("id");
							if(!missingMappings.contains(it)) 
								missingMappings.add(it);
						}
												
					}
				} else {
					if((object.has("mandatory") || (object.has("minOccurs") && object.getInt("minOccurs") > 0)) && (!object.has("mappings") || object.getJSONArray("mappings").size() == 0)) {
						String it = item;
						if(!missingMappings.contains(it)) 
							missingMappings.add(it);
					}
				}
				
				if(!missingMappings.isEmpty()) {
					if(!missingMappings.contains(item)) {
						missingMappings.add(item);
					}
				}
			}
		}
		
		return missingMappings;
	}
	
	public static String getGroupState(JSONObject contents, JSONArray missing, JSONArray mapped, JSONArray missing_attributes, JSONArray mapped_attributes) {
		String state = "normal";

		boolean isMissing = false;
		boolean isMapped = false;
		
		if(contents.has("children")) {
			// check children state
			JSONArray children = contents.getJSONArray("children");
			java.util.Iterator i = children.iterator();
			
			while(i.hasNext()) {
				JSONObject child = (JSONObject) i.next();
				String id = child.getString("id");
				if(missing.contains(id)) {
					isMissing = true;
				} else if(mapped.contains(id)) {
					isMapped = true;
				}
			}
		}

		if(contents.has("attributes")) {
			JSONArray attributes = contents.getJSONArray("attributes");
			java.util.Iterator a = attributes.iterator();
			
			while(a.hasNext()) {
				JSONObject attribute = (JSONObject) a.next();
				String id = attribute.getString("id");
				if(missing_attributes.contains(id)) {
					isMissing = true;
				} else if(mapped_attributes.contains(id)) {
					isMapped = true;
				}
			}
		}
			
		if(isMissing) {
			state = "missing";
		} else if(isMapped) {
			state = "mapped";
		}
		
		return state;
	}
}
