package gr.ntua.ivml.mint.mapping;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;

import net.sf.json.JSONArray;
import net.sf.json.JSONObject;
import net.sf.json.JSONSerializer;

/**
 * 
 * Class wraps a mapping JSONObject and handles operations on it.
 *
 */
public class JSONMappingHandler {
	public static final String TEMPLATE_NAMESPACES = "namespaces";
	public static final String TEMPLATE_TEMPLATE = "template";
	public static final String TEMPLATE_GROUPS = "groups";
	
	public static final String ELEMENT_NAME = "name";
	public static final String ELEMENT_PREFIX = "prefix";
	public static final String ELEMENT_MINOCCURS = "minOccurs";
	public static final String ELEMENT_MAXOCCURS = "maxOccurs";	

	public static final String ELEMENT_FIXED = "fixed";
	public static final String ELEMENT_MANDATORY = "mandatory";
	public static final String ELEMENT_LABEL = "label";
	public static final String ELEMENT_CONDITION = "condition";
	public static final String ELEMENT_MAPPINGS = "mappings";
	public static final String ELEMENT_CHILDREN = "children";
	public static final String ELEMENT_ATTRIBUTES = "attributes";
	public static final String ELEMENT_ENUMERATIONS = "enumerations";
	public static final String ELEMENT_REMOVABLE = "duplicate";
	
	public static final String MAPPING_CONSTANT = "constant";
	public static final String MAPPING_XPATH = "xpath";
	
	JSONObject object = null;
	JSONMappingHandler(JSONObject mapping) {
		if(mapping == null) {
			throw new NullPointerException();
		} else {
			this.object = mapping;
		}
	}
	
	public String toString() {
		return object.toString();
	}

	/**
	 * @return true if handler handles the whole mapping object
	 */
	public boolean isTopLevelMapping()
	{
		if(object.has(TEMPLATE_TEMPLATE)) {
			return true;
		}
		return false;
	}
	
	/**
	 *  @return true if handler handles an element
	 */
	public boolean isElement()
	{
		if(object.has("name")) {
			if(!object.getString("name").startsWith("@")) {
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 *  @return true if handler handles an attribute
	 */
	public boolean isAttribute()
	{
		if(object.has("name")) {
			if(object.getString("name").startsWith("@")) {
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * @param name element name of a group from configuration's "groups" array
	 * @return JSONObject for the requested group
	 */
	public JSONObject getGroup(String name) {
		if(object.has(TEMPLATE_GROUPS)) {
			JSONArray groups = object.getJSONArray(TEMPLATE_GROUPS);
			Iterator i = groups.iterator();
			while(i.hasNext()) {
				JSONObject group = (JSONObject) i.next();
				if(group.has("element")) {
					if(group.getString("element").compareTo(name) == 0) {
						return group;
					}
				}
			}
		}

		return null;
	}

	/**
	 * @param name group name from configuration "groups" array
	 * @return handler for the requested group
	 */
	public JSONMappingHandler getGroupHandler(String name) {
		JSONObject group = this.getGroup(name);
		if(group != null) {
			JSONObject contents = group.getJSONObject("contents");
			return new JSONMappingHandler(contents);
		}
		
		return null;
	}
	
	/**
	 * Get a map of handlers for all mapping's groups.
	 * Key is the group's element.
	 * Must be called on top level mapping handler.
	 * @return
	 */
	public Map<String, JSONMappingHandler> getGroupHandlers() {
		HashMap<String, JSONMappingHandler> map = new HashMap<String, JSONMappingHandler>();

		if(this.object.has(TEMPLATE_GROUPS)) {
			JSONArray groups = this.object.getJSONArray(TEMPLATE_GROUPS);
			for(int i = 0; i < groups.size(); i ++) {
				JSONObject group = groups.getJSONObject(i);
				JSONMappingHandler handler = new JSONMappingHandler(group);
				map.put(group.getString("element"), handler);
			}
		}
		
		return map;
	}
	
	/**
	 * @return JSONArray of handler's attributes
	 */
	public JSONArray getAttributes() {
		if(object.has(ELEMENT_ATTRIBUTES)) {
			return object.getJSONArray(ELEMENT_ATTRIBUTES);
		}
		
		return null;
	}
	
	/**
	 * Get a handler for an attribute.
	 * @param attribute name (optionally starting with @)
	 * @return the attribute handler or null if no attribute was found.
	 */
	public JSONMappingHandler getAttribute(String attribute) {
		if(attribute.startsWith("@")) attribute = attribute.substring(1);
		String name = attribute;
		String prefix = "";
		
		if(attribute.contains(":")) {
			String[] tokens = attribute.split(":");
			if(tokens.length > 2) return null;
			else if(tokens.length > 1) {
				prefix = tokens[0];
				name = tokens[1];
			}
		}
		
		JSONArray attributes = this.getAttributes();
		if(attributes != null) {
			for(Object o: attributes) {
				JSONObject a = (JSONObject) o;
				if(a.has(ELEMENT_NAME) && a.getString(ELEMENT_NAME).equals("@" + name)) {
					if(a.has(ELEMENT_PREFIX) && a.getString(ELEMENT_PREFIX).equals(prefix)) {
						return new JSONMappingHandler(a);
					}
				}
			}
		}
		
		return null;
	}

	/**
	 * Get a handler for a child.
	 * @param child name 
	 * @return the child handler or null if no child was found.
	 */
	public JSONMappingHandler getChild(String child) {
		String name = child;
		String prefix = "";
		
		if(child.contains(":")) {
			String[] tokens = child.split(":");
			if(tokens.length > 2) return null;
			else if(tokens.length > 1) {
				prefix = tokens[0];
				name = tokens[1];
			}
		}
		
		JSONArray children = this.getChildren();
		if(children != null) {
			for(Object o: children) {
				JSONObject c = (JSONObject) o;
				if(c.has(ELEMENT_NAME) && c.getString(ELEMENT_NAME).equals(name)) {
					if(c.has(ELEMENT_PREFIX) && c.getString(ELEMENT_PREFIX).equals(prefix)) {
						return new JSONMappingHandler(c);
					}
				}
			}
		}
		
		return null;
	}

	/**
	 * @return JSONArray of handler's children
	 */
	public JSONArray getChildren() {
		if(object.has(ELEMENT_CHILDREN)) {
			return object.getJSONArray(ELEMENT_CHILDREN);
		}
		
		return null;
	}
	
	/**
	 * @return JSONArray of handler's mappings
	 */
	public JSONArray getMappings() {
		if(object.has(ELEMENT_MAPPINGS)) {
			return object.getJSONArray(ELEMENT_MAPPINGS);
		}
		
		return null;
	}
	
	/**
	 * Get a JSONArray of strings representing the handler's enumerations.
	 * @return JSONArray of handler's enumerations or null if no enumerations exist.
	 */
	public JSONArray getEnumerations() {
		if(object.has(ELEMENT_ENUMERATIONS)) {
			return object.getJSONArray(ELEMENT_ENUMERATIONS);
		}
		
		return null;
	}

	/**
	 * Removes all handler's enumerations.
	 */
	public void removeEnumerations() {
		if(object.has(ELEMENT_ENUMERATIONS)) {
			object.remove(ELEMENT_ENUMERATIONS);
		}
	}
	
	/**
	 * Adds an enumeration.
	 * 
	 * @param enumeration the enumeration to be added.
	 */
	public void addEnumeration(String enumeration) {
		if(!object.has(ELEMENT_ENUMERATIONS)) {
			object.element(ELEMENT_ENUMERATIONS, new JSONArray());
		}
		
		JSONArray enumerations = this.getEnumerations();
		enumerations.add(enumeration);
	}
	
	public void setString(String key, String value) {
		object.element(key, value);
	}
	public void setObject(String key, JSONObject value) {
		object.element(key, value);
	}
	public void setArray(String key, JSONArray value) {
		object.element(key, value);
	}
	public String getString(String key) {
		if(object.has(key)) {
			return object.getString(key);
		}
		
		return null;
	}
	public String getOptString(String key) {
		if(object.has(key)) {
			return object.getString(key);
		}
		
		return "";
	}
	public JSONObject getObject(String key) {
		if(object.has(key)) {
			return object.getJSONObject(key);
		}
		
		return null;
	}
	
	/**
	 * @param key mapping key
	 * @return handler for requested key
	 */
	public JSONMappingHandler getHandler(String key) {
		if(object.has(key)) {
			return new JSONMappingHandler(object.getJSONObject(key));
		}
		
		return null;
	}
	public JSONArray getArray(String key) {
		if(object.has(key)) {
			return object.getJSONArray(key);
		}
		
		return null;
	}
	
	public void addMapping(String type, String value) {
		this.getMappings().add(new JSONObject().element("type", type).element("value", value));
	}

	/**
	 * Adds a constant mapping with specified value
	 * @param value constant value
	 */
	public void addConstantMapping(String value) {
		this.addMapping(MAPPING_CONSTANT, value);
	}
	
	/**
	 * Adds an xpath mapping with specified value
	 * @param xpath the xpath mapping
	 */
	public void addXPathMapping(String xpath) {
		this.addMapping(MAPPING_XPATH, xpath);
	}
	
	/**
	 * @return true if mapping is fixed inside the mapping editor.
	 */
	public boolean isFixed() {
		return object.has(ELEMENT_FIXED);
	}

	/**
	 * Sets the fixed property of a mapping. Fixed mappings cannot change using the mapping editor.
	 * @param f fixed property
	 */
	public void setFixed(boolean f) {
		if(f) {
			object.element(ELEMENT_FIXED, "");
		} else {
			object.remove(ELEMENT_FIXED);
		}
	}

	/**
	 * @return true if mapping is forced as mandatory.
	 */
	public boolean isMandatory() {
		return object.has(ELEMENT_MANDATORY);
	}
	
	/**
	 * Sets the mandatory property of a mapping forcing the mapping editor to consider it as mandatory.
	 * @param m mandatory property
	 */
	public void setMandatory(boolean m) {
		if(m) {
			object.element(ELEMENT_MANDATORY, "");
		} else {
			object.remove(ELEMENT_MANDATORY);
		}
	}
	
	/**
	 * True if element can be removed from the mapping editor.
	 * @return true if element can be removed from the mapping editor.
	 */
	public boolean isRemovable() {
		return this.object.has(ELEMENT_REMOVABLE);
	}
	
	/**
	 * Set removable state of this element. Removable elements can be removed by the user using the mapping editor.
	 * @param r removable state.
	 */
	public void setRemovable(boolean r) {
		if(r) {
			if(!this.isRemovable()) {
				this.object.element(ELEMENT_REMOVABLE, "");
			}
		} else {
			if(this.isRemovable()) {
				this.object.remove(ELEMENT_REMOVABLE);
			}
		}
	}

	/**
	 * True if handler is repeatable (ie. maxOccurs == unbounded).
	 * @return true if handler is repeatable, false otherwise. 
	 */
	public boolean isRepeatable() {
		if(object.has(ELEMENT_MAXOCCURS)) {
			int maxOccurs = Integer.parseInt(object.getString(ELEMENT_MAXOCCURS));
			if(!this.isAttribute() && maxOccurs < 0) return true;
		}
		
		return false;
	}
	
	/**
	 * Gets a custom label set for this element.
	 * @return the custom label or null if none is set.
	 */
	public String getLabel() {
		if(object.has(ELEMENT_LABEL)) return object.getString(ELEMENT_LABEL);
		return null;
	}
	
	/**
	 * Sets a custom label for this element. Set to null to remove the custom label.
	 * @param label the custom label or null if the label is to be removed.
	 */
	public void setLabel(String label) {
		if(label == null) {
			if(object.has(ELEMENT_LABEL)) object.remove(ELEMENT_LABEL);
		} else {
			object.element(ELEMENT_LABEL, label);
		}
	}
 
	/**
	 * Gets a mapping handler for requested path.
	 * Path is relative to the mapping handler. If mapping handler is top level handler then searches
	 * are performed inside each group.
	 * Use if only one instance of this path exists or if you want the first.
	 *  
	 * @param path the requested path
	 * @return the handler or null if not found
	 */
	public JSONMappingHandler getHandlerForPath(String path) {
		ArrayList<JSONMappingHandler> handlers = this.getHandlersForPath(path);
		if(handlers.size() > 0) return handlers.get(0);
		return null;
	}
	
	/**
	 * Gets a list of mapping handlers for requested path.
	 * Path is relative to the mapping handler. If mapping handler is top level handler then searches
	 * are performed inside each group.
	 *  
	 * @param path the requested path
	 * @return the list of handlers found
	 */
	public ArrayList<JSONMappingHandler> getHandlersForPath(String path) {
		if(this.isTopLevelMapping()) {
			if(path.startsWith("/")) { path = path.replaceFirst("/", ""); }
			String[] tokens = path.split("/", 2);
			if(tokens.length > 0) {
				JSONObject group = this.getGroup(tokens[0]);
				if(group != null) {
					JSONObject contents = group.getJSONObject("contents");
					return JSONMappingHandler.getHandlersForPath(contents, path);
				}
			}
		} else {
			return JSONMappingHandler.getHandlersForPath(object, path);
		}

		return new ArrayList<JSONMappingHandler>();	
	}

	private static ArrayList<JSONMappingHandler> getHandlersForPath(JSONObject object, String path) {
		ArrayList<JSONMappingHandler> result = new ArrayList<JSONMappingHandler>();
		if(path.startsWith("/")) { path = path.replaceFirst("/", ""); }
		String[] tokens = path.split("/", 2);
		if(tokens.length > 0) {
			if(object.has("name")) {
				if(tokens[0].equals(object.getString("name"))) {
					if(tokens.length == 1) {
						result.add(new JSONMappingHandler(object));
					} else {
						String tail = tokens[1];
						if(tail.startsWith("@")) {
							if(object.has("attributes")) {
								return JSONMappingHandler.getHandlersForPath(object.getJSONArray("attributes"), tail);
							}
						} else {
							if(object.has("children")) {
								return JSONMappingHandler.getHandlersForPath(object.getJSONArray("children"), tail);
							}
						}
					}
				}
			}
		}
		
		return result;
	}
	private static ArrayList<JSONMappingHandler> getHandlersForPath(JSONArray array, String path) {
		ArrayList<JSONMappingHandler> result = new ArrayList<JSONMappingHandler>();
		Iterator i = array.iterator();
		while(i.hasNext()) {
			JSONObject o = (JSONObject) i.next();
			result.addAll(JSONMappingHandler.getHandlersForPath(o, path));
		}
		return result;
	}
	
	/**
	 * Gets a list of mapping handlers for requested element/attribute name.
	 * Searches are relative to the handler and return all requested elements/attributes regardless of path.
	 *  
	 * @param name the requested element/attribute name. Attribute names should begin with '@'.
	 * @return the list of handlers found
	 */
	public ArrayList<JSONMappingHandler> getHandlersForName(String name) {
		ArrayList<JSONMappingHandler> result = new ArrayList<JSONMappingHandler>();
		if(this.isTopLevelMapping()) {
			JSONArray groups = object.getJSONArray(TEMPLATE_GROUPS);
			Iterator i = groups.iterator();
			while(i.hasNext()) {
				JSONObject group = (JSONObject) i.next();
				JSONObject contents = group.getJSONObject("contents");
				result.addAll(JSONMappingHandler.getHandlersForName(contents, name));
			}
		} else {
			if(this.getOptString("name").compareTo(name) == 0) {
				result.add(this);
			}

			result.addAll(JSONMappingHandler.getHandlersForName(this.getAttributes(), name));
			result.addAll(JSONMappingHandler.getHandlersForName(this.getChildren(), name));
		}

		return result;	
	}
	private static ArrayList<JSONMappingHandler> getHandlersForName(JSONObject object, String name) {
		return new JSONMappingHandler(object).getHandlersForName(name);
	}
	private static ArrayList<JSONMappingHandler> getHandlersForName(JSONArray array, String name) {
		ArrayList<JSONMappingHandler> result = new ArrayList<JSONMappingHandler>();
		if(array != null) {
			Iterator i = array.iterator();
			while(i.hasNext()) {
				JSONObject o = (JSONObject) i.next();
				result.addAll(JSONMappingHandler.getHandlersForName(o, name));
			}
		}
		return result;
	}
	
	/**
	 * Duplicates an element for the given path. Duplicate element is placed after original element to preserve element order.
	 * @param path the path of the element to be duplicated, relative to the handler
	 * @return handler for the created element or null if path was not found 
	 */
	public JSONMappingHandler duplicatePath(String path) {
		if(!path.startsWith("/")) path = "/" + path;

		String[] parts = path.split("/");
		StringBuffer buffer = new StringBuffer();
		
		// if path is not a child of this handler delegate duplication to the appropriate child
		if(parts.length > 3) {
			for(int i = 1; i < parts.length - 1; i++) {
				buffer.append("/" + parts[i]);
			}
			JSONMappingHandler child = this.getHandlerForPath(buffer.toString());
			return child.duplicatePath("/" + parts[parts.length - 2] + "/" + parts[parts.length - 1]);
		// else duplicate child, add to children and return
		} else {
			JSONMappingHandler original = this.getHandlerForPath(path);
			if(!original.isAttribute()) {
				JSONObject duplicate = (JSONObject) JSONSerializer.toJSON(original.toString());
				duplicate.element("__duplicate", "");
				
				int originalIndex = -1;
				JSONArray children = this.getChildren();
				for(int i = 0; i < children.size(); i++) {
					JSONObject c = (JSONObject) children.get(i);
					if(c.has(ELEMENT_NAME) && original.has(ELEMENT_NAME) && c.getString(ELEMENT_NAME).equals(original.getString(ELEMENT_NAME))) {
						if(c.has(ELEMENT_PREFIX) && original.has(ELEMENT_PREFIX) && c.getString(ELEMENT_PREFIX).equals(original.getString(ELEMENT_PREFIX))) {
							originalIndex = i;
						}
					}
				}
				
				this.getChildren().add(originalIndex, duplicate);

				for(Object o: children) {
					JSONObject c = (JSONObject) o;
					if(c.has("__duplicate")) {
						c.remove("__duplicate");
						return new JSONMappingHandler(c);											
					}
				}
			}
		}
		
		return null;
	}

	/**
	 * Get the namespaces JSONObject.
	 * Keys of this object are the namespaces prefixes and values are the namespace URLs.
	 * 
	 * @return the namespaces JSONObject or null if it does not exist;
	 */
	public JSONObject getNamespaces() {
		if(this.object.has(TEMPLATE_NAMESPACES)) {
			return this.object.getJSONObject(TEMPLATE_NAMESPACES);
		}
		
		return null;
	}

	/**
	 * Get handler for the template group.
	 * 
	 * @return handler for the template group or null if it does not exist.
	 */
	public JSONMappingHandler getTemplate() {
		if(this.object.has(TEMPLATE_TEMPLATE)) {
			return new JSONMappingHandler(this.object.getJSONObject(TEMPLATE_TEMPLATE));
		}

		return null;
	}

	/**
	 * Check if a key exists in the handler.
	 * @param string key name.
	 * @return true if key exists.
	 */
	public boolean has(String string) {
		return this.object.has(string);
	}

	/**
	 * Get handler's mapping condition object
	 * @return the condition JSONObject or null if it does not exist.
	 */
	public JSONObject getCondition() {
		if(this.object.has(ELEMENT_CONDITION)) {
			return this.object.getJSONObject(ELEMENT_CONDITION);
		}
		return null;
	}

	/**
	 * Generic method that sets a handler's mapping condition. Condition is removed if null value is passed.
	 * @param condition the condition JSONObject
	 */
	public void setCondition(JSONObject condition) {
		if(condition != null) {
			this.object.element(ELEMENT_CONDITION, condition);
		} else {
			this.object.remove(ELEMENT_CONDITION);
		}
	}

	/**
	 * Get handler's element full name. Full name contains element's name with the element's prefix
	 * if exists. Attributes also start with '@'. 
	 * @return element full name.
	 */
	public String getFullName() {
		String name = null;
		String prefix = null;
		
		if(this.has(ELEMENT_NAME)) name = this.getString(ELEMENT_NAME).replace("@", "");
		if(this.has(ELEMENT_PREFIX)) prefix = this.getString(ELEMENT_PREFIX);
		
		String label = ((this.isAttribute())?"@":"") + ((prefix != null)?prefix+":":"") + name;

		return label;
	}

	public boolean hasMappingsRecursive() {
		if(this.has(ELEMENT_MAPPINGS) && this.getArray(ELEMENT_MAPPINGS).size() > 0) return true;
		else {

			if(this.has(ELEMENT_CHILDREN)) {
				JSONArray children = this.getChildren();
				for(int i = 0; i < children.size(); i++) {
					if(new JSONMappingHandler(children.getJSONObject(i)).hasMappingsRecursive()) return true;
				}
			}

			if(this.has(ELEMENT_ATTRIBUTES)) {
				JSONArray attributes = this.getAttributes();
				for(int i = 0; i < attributes.size(); i++) {
					if(new JSONMappingHandler(attributes.getJSONObject(i)).hasMappingsRecursive()) return true;
				}
			}
		}
		
		return false;
	}
}
