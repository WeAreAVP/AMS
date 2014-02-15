package gr.ntua.ivml.mint.persistent;

import gr.ntua.ivml.mint.xsd.SchemaValidator;

import java.util.Date;

import javax.xml.validation.Schema;

import net.sf.json.JSONObject;
import net.sf.json.JSONSerializer;

import org.xml.sax.SAXException;

public class XmlSchema {
	Long dbID;
	String name;
	String xsd;
	String itemLevelPath, itemLabelPath, itemIdPath;
	String jsonConfig, jsonTemplate;
	String documentation;
	Date created;
	
	JSONObject conf = null;
	
	public Long getDbID() {
		return dbID;
	}
	public void setDbID(Long dbID) {
		this.dbID = dbID;
	}
	public String getName() {
		return name;
	}
	public void setName(String name) {
		this.name = name;
	}
	public String getXsd() {
		return xsd;
	}
	public void setXsd(String xsd) {
		this.xsd = xsd;
	}
	public String getItemLevelPath() {
		return itemLevelPath;
	}
	public void setItemLevelPath(String itemLevelPath) {
		this.itemLevelPath = itemLevelPath;
	}
	public String getItemLabelPath() {
		return itemLabelPath;
	}
	public void setItemLabelPath(String itemLabelPath) {
		this.itemLabelPath = itemLabelPath;
	}
	public String getItemIdPath() {
		return itemIdPath;
	}
	public void setItemIdPath(String itemIdPath) {
		this.itemIdPath = itemIdPath;
	}
	public String getJsonConfig() {
		return jsonConfig;
	}
	public void setJsonConfig(String jsonConfig) {
		this.jsonConfig = jsonConfig;
	}
	public String getJsonTemplate() {
		return jsonTemplate;
	}
	public void setJsonTemplate(String jsonTemplate) {
		this.jsonTemplate = jsonTemplate;
	}
	public Date getCreated() {
		return created;
	}
	public void setCreated(Date created) {
		this.created = created;
	}

	public void setDocumentation(String documentation) {
		this.documentation = documentation;
	}
	
	public String getDocumentation() {
		return this.documentation;
	}
	
	public JSONObject getConfiguration() {
		if(conf == null) {
			conf = (JSONObject) JSONSerializer.toJSON(this.getJsonConfig());
		}
		
		return conf;
	}
	
	public String getItemPath() {
		String result = null;
		
		if(this.getConfiguration().has("paths")) {
			JSONObject paths = this.getConfiguration().getJSONObject("paths");
			if(paths.has("item")) result = paths.getString("item");
		}
		
		return result;
	}
	
	public String getPublicationXSL() {
		String result = null;
		
		if(this.getConfiguration().has("publication")) {
			JSONObject publication = this.getConfiguration().getJSONObject("publication");
			if(publication.has("type") && publication.getString("type").equalsIgnoreCase("xsl")) {
				if(publication.has("value")) result = publication.getString("value");
			}
		}
		
		return result;
	}
	
	public Schema getSchema() throws SAXException {
		return SchemaValidator.getSchema(this);
	}
	
	
	public String toString() {
		if(this.name != null && this.name.length() > 0) {
			return this.name;
		} else if(this.xsd != null && this.xsd.length() > 0) {
			return "[" + this.xsd + "]";
		}
		
		return "XmlSchema: " + this.dbID;
	}
}
