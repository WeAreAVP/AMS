package gr.ntua.ivml.mint.mapping;

import java.io.BufferedReader;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.FileWriter;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;

import org.apache.log4j.Logger;

import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.persistent.XmlSchema;
import net.sf.json.JSONArray;
import net.sf.json.JSONObject;
import net.sf.json.JSONSerializer;

public class MappingConverter {
	protected static final Logger log = Logger.getLogger( MappingConverter.class);

	private XmlSchema schema = null;
	private String template = null;
	private JSONObject iNamespaces = null;
	private JSONObject oNamespaces = null;

	public static int main(String[] args) {
		System.out.println("Converting...");
		
		XmlSchema schema = null;
		List<XmlSchema> list = DB.getXmlSchemaDAO().findAll();
		for(XmlSchema s: list) {
			if(s.getName().indexOf("LIDO") >= 0) {
				schema = s;
				break;
			}
		}
		
		if(schema == null) return -1;
		MappingConverter converter = new MappingConverter(schema);
		File input = new File("test.xml");
		File output = new File("output.xml");

		try {
			JSONObject result = converter.convert(new FileInputStream(input));
			FileWriter writer = new FileWriter(output);
			writer.write(result.toString());
			writer.close();
		} catch (FileNotFoundException e) {
			e.printStackTrace();
		} catch (IOException e) {
			e.printStackTrace();
		}
		
		return 0;
	}
	
	/**
	 * Initialize a mapping converter that converts input mappings to mappings of provided schema.
	 * @param schema the provided schema.
	 */
	public MappingConverter(XmlSchema schema) {
		this.setSchema(schema);
	}
	
	/**
	 * Get the XmlSchema for this mapping converter.
	 * @return the XmlSchema.
	 */
	public XmlSchema getSchema() {
		return this.schema;
	}
	
	/**
	 * Set the schema for this mapping converter.
	 * @param schema
	 */
	public void setSchema(XmlSchema schema) {
		this.schema = schema;
		this.template = schema.getJsonTemplate();
	}
	
	/**
	 * Provide a mapping template directly.
	 * This operation will set schema to null. Use it only if template is not related to a schema.
	 * Use setSchema otherwise.
	 * @param template The template json string.
	 */
	public void setTemplate(String template) {
		this.schema = null;
		this.template = template;
	}
	
	/**
	 * Convert mapping contained in InputStream.
	 * @param stream InputStream of mapping json string.
	 * @return Converted mapping.
	 */
	public JSONObject convert(InputStream stream) {

		try {
			StringBuffer buffer = new StringBuffer();
			String line;
			BufferedReader in = new BufferedReader(new InputStreamReader(stream, "UTF8"));
			while((line = in.readLine()) != null) {
				buffer.append(line);
			}

			String mapping = buffer.toString();
			return convert(mapping);
		} catch (IOException e) {
			e.printStackTrace();
			return null;
		}		
	}

	/**
	 * Convert mapping in string.
	 * @param mapping json string of a mapping.
	 * @return Converted mapping.
	 */
	public JSONObject convert(String mapping) {
		JSONObject input = (JSONObject) JSONSerializer.toJSON(mapping);
		return convert(input);
	}
	
	/**
	 * Convert mapping in JSONOBject.
	 * @param mapping JSONObject serialization of mapping.
	 * @return Converted mapping.
	 */
	public JSONObject convert(JSONObject mapping) {
		JSONObject result = (JSONObject) JSONSerializer.toJSON(this.schema.getJsonTemplate());

		JSONMappingHandler input = new JSONMappingHandler(mapping);
		JSONMappingHandler output = new JSONMappingHandler(result);
		
		this.iNamespaces = input.getNamespaces();
		this.oNamespaces = output.getNamespaces();
		
		log.debug("Merging template");
		JSONMappingHandler iTemplate = input.getTemplate();
		JSONMappingHandler oTemplate = output.getTemplate();
		this.merge(iTemplate, oTemplate);
				
		Map<String, JSONMappingHandler> iGroups = input.getGroupHandlers();
		Map<String, JSONMappingHandler> oGroups = output.getGroupHandlers();
		Iterator<String> keys = iGroups.keySet().iterator();
		while(keys.hasNext()) {
			String key = keys.next();
			if(oGroups.containsKey(key)) {
				log.debug("Merging group " + key);
				JSONMappingHandler iGroup = iGroups.get(key);
				JSONMappingHandler oGroup = oGroups.get(key);
				
				JSONObject iContents = iGroup.getObject("contents");
				JSONObject oContents = oGroup.getObject("contents");

				if(oContents.isArray()) {
					if(iContents.isArray()) merge(iGroup.getArray("contents"), oGroup.getArray("contents"));
					else merge(iContents, oGroup.getArray("contents").getJSONObject(0));
				} else {
					if(iContents.isArray()) merge(iGroup.getArray("contents").getJSONObject(0), oContents);
					else merge(iContents, oContents);
				}
			} else {
				log.debug("Group " + key + " not found in target schema");
			}
		}
		
		return result;
	}
	
	private void merge(JSONObject iContents, JSONObject jsonObject) {
		merge(new JSONMappingHandler(iContents), new JSONMappingHandler(jsonObject));
	}

	/**
	 * Merge contents of newElement into element.
	 * @param newElement a new element to be merged with element.
	 * @param element an existing mapping element. This element will be modified.
	 */
	public void merge(JSONMappingHandler newElement, JSONMappingHandler element) {
		log.debug("merging " + newElement.getFullName() + " to " + element.getFullName());

		if(this.sameName(newElement, element) && this.sameNamespace(newElement, element)) {
			// merge mappings
			log.debug("-- merge mappings");
			if(!element.isFixed() && element.has(JSONMappingHandler.ELEMENT_MAPPINGS)) {
				JSONArray iMappings = newElement.getMappings();
				JSONArray oMappings = element.getMappings();

				log.debug("input mappings : " + iMappings);
				log.debug("output mappings : " + oMappings);
				
				if(iMappings != null) oMappings.addAll(iMappings);
			}
			
			// merge conditions
			log.debug("-- merge conditions");
			if(element.has(JSONMappingHandler.ELEMENT_CONDITION)) {
				JSONObject iCondition = newElement.getCondition();
				if(iCondition != null) {
					element.setCondition(iCondition);
				}
			}
			
			// apply to children
			log.debug("-- merge children");
			this.merge(newElement.getChildren(), element.getChildren());
			
			// apply to attributes
			log.debug("-- merge attributes");
			this.merge(newElement.getAttributes(), element.getAttributes());
		}
	}
	
	private HashMap<String, List<JSONMappingHandler>> elementMap(JSONArray elements) {
		HashMap<String, List<JSONMappingHandler>> map = new HashMap<String, List<JSONMappingHandler>>();
		
		for(int i = 0; i < elements.size(); i++) {
			JSONObject element = elements.getJSONObject(i);
			JSONMappingHandler handler = new JSONMappingHandler(element);
			String label = handler.getFullName();
			List<JSONMappingHandler> list = null;
			if(map.containsKey(label)) {
				list = map.get(label);
			} else {
				list = new ArrayList<JSONMappingHandler>();
				map.put(label, list);
			}
			
			list.add(handler);
		}
		
		return map;
	}
	
	public void merge(JSONArray newElements, JSONArray elements) {
		if(elements == null || newElements == null) return;
		
		HashMap<String, List<JSONMappingHandler>> iElements = elementMap(newElements);
		HashMap<String, List<JSONMappingHandler>> oElements = elementMap(elements);
		List<JSONObject> createdElements = new ArrayList<JSONObject>();
		
		List<JSONMappingHandler> oUsed = new ArrayList<JSONMappingHandler>();
		
		Iterator<String> iKeys = iElements.keySet().iterator();
		while(iKeys.hasNext()) {
			String key = iKeys.next();
			if(oElements.containsKey(key)) {
				List<JSONMappingHandler> iList = iElements.get(key);
				List<JSONMappingHandler> oList = oElements.get(key);
				JSONMappingHandler oFirst = oList.get(0);
				if(oFirst.isRepeatable()) {
					log.debug("  " + oFirst.getFullName() + " is repeatable");
					for(int i = 0; i < iList.size(); i++) {
						JSONMappingHandler iElement = iList.get(i);
						if(iElement.hasMappingsRecursive()) {
							JSONMappingHandler oElement = null;
							
							for(int j = 0; j < oList.size(); j++) {
								JSONMappingHandler oe = oList.get(j);
								
								if(iElement.has(JSONMappingHandler.ELEMENT_LABEL)) {
									if(!oe.has(JSONMappingHandler.ELEMENT_LABEL) || !iElement.getLabel().equals(oe.getLabel())) continue;
								}
								
								if(!oUsed.contains(oe)) {
									oUsed.add(oe);
									oElement = oe;
									break;
								}
							}
							
							if(oElement == null && !iElement.has(JSONMappingHandler.ELEMENT_LABEL)) {
								JSONObject duplicate = (JSONObject) JSONSerializer.toJSON(oFirst.object.toString());
								createdElements.add(duplicate);
								oElement = new JSONMappingHandler(duplicate);
							}
							
							this.merge(iElement, oElement);
						}
					}
				} else {
					log.debug("  " + oFirst.getFullName() + " is unique");
					this.merge(iList.get(0), oList.get(0));
				}
			} else {
				//TODO: notify element was not found in output schema
			}
		}
		
		elements.addAll(createdElements);
	}
	
	private boolean sameName(JSONMappingHandler one, JSONMappingHandler other) {
		return (one.has("name") && other.has("name") &&
				one.getString("name").equalsIgnoreCase(other.getString("name")));
	}

	private boolean sameNamespace(JSONMappingHandler input, JSONMappingHandler output) {
		String iPrefix = input.getString("prefix");
		String oPrefix = output.getString("prefix");

		if(iPrefix == null && oPrefix == null) return true;
		if(iPrefix.length() == 0 && oPrefix.length() == 0) return true;

		if(!iNamespaces.has(iPrefix) && !oNamespaces.has(oPrefix) && iPrefix.equalsIgnoreCase(oPrefix)) return true;
		else if(iNamespaces.has(iPrefix) && oNamespaces.has(oPrefix)) {
			String iURL = iNamespaces.getString(iPrefix);
			String oURL = oNamespaces.getString(oPrefix);
			if(iURL.equalsIgnoreCase(oURL)) return true;
		}
		
		return false;
	}
}
