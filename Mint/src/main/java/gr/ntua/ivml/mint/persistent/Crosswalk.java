package gr.ntua.ivml.mint.persistent;

import java.util.Date;

public class Crosswalk {
	Long dbID;
	XmlSchema sourceSchema, targetSchema;
	String xsl;
	String jsonMappingTemplate;
	Date created;

	
	public Long getDbID() {
		return dbID;
	}
	public void setDbID(Long dbID) {
		this.dbID = dbID;
	}
	public XmlSchema getSourceSchema() {
		return sourceSchema;
	}
	public void setSourceSchema(XmlSchema sourceSchema) {
		this.sourceSchema = sourceSchema;
	}
	public XmlSchema getTargetSchema() {
		return targetSchema;
	}
	public void setTargetSchema(XmlSchema targetSchema) {
		this.targetSchema = targetSchema;
	}
	public String getXsl() {
		return xsl;
	}
	public void setXsl(String xsl) {
		this.xsl = xsl;
	}
	public String getJsonMappingTemplate() {
		return jsonMappingTemplate;
	}
	public void setJsonMappingTemplate(String jsonMappingTemplate) {
		this.jsonMappingTemplate = jsonMappingTemplate;
	}
	public Date getCreated() {
		return created;
	}
	public void setCreated(Date created) {
		this.created = created;
	}
	
	
}
