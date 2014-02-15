package gr.ntua.ivml.mint.actions;

import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.mapping.TargetConfigurationFactory;
import gr.ntua.ivml.mint.persistent.Crosswalk;
import gr.ntua.ivml.mint.persistent.User;
import gr.ntua.ivml.mint.persistent.XmlSchema;
import gr.ntua.ivml.mint.util.Config;
import gr.ntua.ivml.mint.util.StringUtils;

import java.io.BufferedReader;
import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

import javax.servlet.ServletContext;

import net.sf.json.JSONObject;
import net.sf.json.JSONSerializer;

import org.apache.log4j.Logger;
import org.apache.struts2.convention.annotation.Action;
import org.apache.struts2.convention.annotation.Result;
import org.apache.struts2.convention.annotation.Results;
import org.apache.struts2.util.ServletContextAware;

import com.opensymphony.xwork2.Preparable;


@Results({
	  @Result(name="input", location="outputxsd.jsp"),
	  @Result(name="error", location="outputxsd.jsp"),
	  @Result(name="success", location="outputxsd.jsp"),
	  @Result(name="textdata", location="outputxsd.jsp"),
	  @Result(name="redirect", location="${url}", type="redirectAction" )
	})


public class OutputXSDAction extends GeneralAction implements Preparable, ServletContextAware {
	  
	//private static final long serialVersionUID = 1L;
	  
	protected final Logger log = Logger.getLogger(getClass());
	
	private List<XmlSchema> outputXSDs;
	
	private String id = null;
	private String uaction = "";
	private XmlSchema xmlschema;
	private Crosswalk crosswalk;

	private String selectedxsd;
	private long sourceSchemaId;
	private long targetSchemaId;
	
	private String textdata = "";
	
	private ServletContext sc;
     
	public void prepare() {
		if(getUaction().equalsIgnoreCase("import_xsd")) {
			xmlschema = new XmlSchema();
		} else if(getUaction().equalsIgnoreCase("import_crosswalk")) {
			crosswalk =  new Crosswalk();
		}
	}
	
	@Action(value="OutputXSD")
	public String execute() throws Exception {
		try{
			if(getUaction().equalsIgnoreCase("save_xsd")) {
				if(xmlschema != null) {
					log.debug("saving xml schema: " + xmlschema.getName());
							
					try {
						processSchema(xmlschema);
		            } catch(Exception ex) {
		                ex.printStackTrace();
		                log.debug(ex.getMessage());
		                addActionError(ex.getMessage());
		            }

					DB.getXmlSchemaDAO().makePersistent(xmlschema);
					DB.getSession().evict(xmlschema);
				}				
			} else if(getUaction().equalsIgnoreCase("reload")) { 
				XmlSchema xs = DB.getXmlSchemaDAO().findById(Long.parseLong(this.getId()), false);
				this.processSchema(xs);
				this.addActionError(xs + " reloaded");
			} else if(getUaction().equalsIgnoreCase("show_xsd")) {
				XmlSchema xs = DB.getXmlSchemaDAO().findById(Long.parseLong(this.getId()), false);
				String output = xs.getXsd();
				log.debug(output);
				this.setTextdata(output);
				return "textdata"; 
			} else if(getUaction().equalsIgnoreCase("show_conf")) {  
				XmlSchema xs = DB.getXmlSchemaDAO().findById(Long.parseLong(this.getId()), false);
				String output = xs.getJsonConfig();
				output = JSONSerializer.toJSON(output).toString(2);
				log.debug(output);
				this.setTextdata(output);
				return "textdata"; 
			} else if(getUaction().equalsIgnoreCase("show_template")) {  
				XmlSchema xs = DB.getXmlSchemaDAO().findById(Long.parseLong(this.getId()), false);
				String output = xs.getJsonTemplate();
				output = JSONSerializer.toJSON(output).toString(2);
				log.debug(output);
				this.setTextdata(output);
				return "textdata";
			} else if(getUaction().equalsIgnoreCase("delete")) {
				XmlSchema xs = DB.getXmlSchemaDAO().findById(Long.parseLong(this.getId()), false);
				DB.getXmlSchemaDAO().makeTransient(xs);
			} else if(getUaction().equalsIgnoreCase("save_crosswalk")) {
				if(crosswalk != null) {
					XmlSchema source = DB.getXmlSchemaDAO().findById(this.sourceSchemaId, false);
					XmlSchema target = DB.getXmlSchemaDAO().findById(this.targetSchemaId, false);
					log.debug(this.sourceSchemaId + " " + this.targetSchemaId + " " + source + " " + target);
					if(source != null && target != null) {
						crosswalk.setSourceSchema(source);
						crosswalk.setTargetSchema(target);
						log.debug("saving crosswalk: " + crosswalk.getSourceSchema() + " -> " + crosswalk.getTargetSchema());
						
						crosswalk.setCreated(new java.util.Date());

						DB.getCrosswalkDAO().makePersistent(crosswalk);
						DB.getSession().evict(crosswalk);
					}
				}
			}
		}catch(Exception ex){
			ex.printStackTrace();
			log.debug(ex.getMessage());
			addActionError(ex.getMessage());
			return ERROR;
		}
		
		return SUCCESS;
	}

	public List<XmlSchema> getXmlSchemas() {
		List<XmlSchema> result = DB.getXmlSchemaDAO().findAll();		
		return result;
	}

	public List<Crosswalk> getCrosswalks() {
		List<Crosswalk> result = DB.getCrosswalkDAO().findAll();		
		return result;
	}
	
	public List<String> getAvailablexsd() {
		List<String> result = new ArrayList<String>();

		try {
			File schemaDir = new File(sc.getRealPath(Config.get("schemaDir")));
			String[] contents = schemaDir.list();
			for(int i = 0; i < contents.length; i++) {
				String filename = contents[i];
				if(filename.toLowerCase().endsWith(".xsd")) {
					result.add(filename);
				}
			}
		} catch(Exception ex) {
			ex.printStackTrace();
		}

		return result;
	}
	
	public List<String> getAvailableXSL()
	{
		List<String> result = new ArrayList<String>();

		try {
			File schemaDir = new File(sc.getRealPath(Config.get("schemaDir")));
			String[] contents = schemaDir.list();
			for(int i = 0; i < contents.length; i++) {
				String filename = contents[i];
				if(filename.toLowerCase().endsWith(".xsl")) {
					result.add(filename);
				}
			}
		} catch(Exception ex) {
			ex.printStackTrace();
		}

		return result;
	}

	@Action("OutputXSD_input")
	@Override
	public String input() throws Exception {
	    	if(!user.hasRight(User.SUPER_USER)) {
	    		throw new Exception( "No super user rights! You have no access to this area." );
	    	}
	
	    	return super.input();
	}
	
	public String getUaction()
	{
		return uaction;
	}
  
	public void setUaction(String uaction){
		this.uaction = uaction;
		log.debug("action set to: " + uaction);
	}
	
	public String getId()
	{
		return id;
	}
	
	public void setId(String id)
	{
		this.id = id;
	}
	
	public XmlSchema getXmlschema()
	{
		return xmlschema;
	}
	
	public void setXmlschema(XmlSchema xmlschema) {
		this.xmlschema = xmlschema;
	}
	
	public Crosswalk getCrosswalk()
	{
		return crosswalk;
	}
	
	public void setCrosswalk(Crosswalk crosswalk) {
		this.crosswalk = crosswalk;
	}
	
	public void setSourceSchemaId(long id) {
		this.sourceSchemaId = id;
	}
	
	public void setTargetSchemaId(long id) {
		this.targetSchemaId = id;
	}
	
	private void processSchema(XmlSchema schema) throws IOException {
		log.debug("Processing schema: " + schema);
		String confFilename = Config.getSchemaPath(schema.getXsd()) + ".conf";
		File confFile = new File(confFilename);
		if(confFile.exists()) {
			log.debug("Found configuration: " + confFilename);
			StringBuffer confcontents = StringUtils.fileContents(confFile);
			schema.setJsonConfig(confcontents.toString());
		} else {
			schema.setJsonConfig(null);
		}

		String xsd = Config.getSchemaPath(schema.getXsd());
		TargetConfigurationFactory factory = null;
		
		try {
			factory = new TargetConfigurationFactory(xsd);
		} catch(Throwable ex) {
			ex.printStackTrace();
			return;
		}
		
		log.debug("Build schema factory for: " + schema.getXsd());
		
		// create configuration or use one provided if it exists
		JSONObject configuration = null;
		if(schema.getJsonConfig() == null || schema.getJsonConfig().length() == 0) {
			configuration = factory.getConfiguration(true);
			configuration.element("xsd", schema.getXsd());
			schema.setJsonConfig(configuration.toString());
			log.debug("Generating default configuration");
		} else {
			configuration = (JSONObject) JSONSerializer.toJSON(schema.getJsonConfig());
			factory.setConfiguration(schema.getJsonConfig());
			log.debug("Using provided configuration");
		}
		
		// generate mapping template
		schema.setJsonTemplate(factory.getMappingTemplate().toString());
		log.debug("Generating mapping template");

		// parse annotations
		// TODO: add documentation db field
		schema.setDocumentation(factory.getDocumentation().toString());
		log.debug("Generating documentation");
		
		schema.setCreated(new java.util.Date());

		// extract item level, label & id if they exist
		if(configuration.has("paths")) {
			JSONObject paths = configuration.getJSONObject("paths");

			if(paths.has("item")) {
				schema.setItemLevelPath(paths.getString("item"));
			}
			
			if(paths.has("label")) {
				schema.setItemLabelPath(paths.getString("label"));				
			}
			
			if(paths.has("id")) {
				schema.setItemIdPath(paths.getString("id"));				
			}
		}
	}
	
	public void setTextdata(String s) {
		this.textdata = s;
	}
	
	public String getTextdata() {
		return this.textdata;
	}

	@Override
	public void setServletContext(ServletContext sc) {
		this.sc = sc;
	}
}
