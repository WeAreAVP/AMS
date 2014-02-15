
package gr.ntua.ivml.mint.actions;

import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.persistent.DataUpload;
import gr.ntua.ivml.mint.persistent.Mapping;
import gr.ntua.ivml.mint.persistent.Organization;

import gr.ntua.ivml.mint.persistent.Transformation;
import gr.ntua.ivml.mint.persistent.XMLNode;
import gr.ntua.ivml.mint.persistent.XmlObject;
import gr.ntua.ivml.mint.persistent.XmlSchema;
import gr.ntua.ivml.mint.persistent.XpathHolder;
import gr.ntua.ivml.mint.util.Config;
import gr.ntua.ivml.mint.xml.transform.ChainTransform;
import gr.ntua.ivml.mint.xml.transform.XMLFormatter;
import gr.ntua.ivml.mint.xml.transform.XSLTGenerator;
import gr.ntua.ivml.mint.xml.transform.XSLTransform;
import gr.ntua.ivml.mint.xsd.ReportErrorHandler;
import gr.ntua.ivml.mint.xsd.SchemaValidator;

import java.io.BufferedReader;
import java.io.ByteArrayInputStream;
import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.FileReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.PrintWriter;
import java.io.StringWriter;
import java.util.ArrayList;
import java.util.Collections;
import java.util.List;

import javax.servlet.ServletContext;
import javax.xml.transform.stream.StreamSource;

import org.apache.commons.lang.StringEscapeUtils;
import org.apache.log4j.Logger;
import org.apache.struts2.convention.annotation.Action;
import org.apache.struts2.convention.annotation.Result;
import org.apache.struts2.convention.annotation.Results;
import org.apache.struts2.util.ServletContextAware;
import org.xml.sax.SAXParseException;


@Results({
	  @Result(name="input", location="xmlPreviewPanel.jsp"),
	  @Result(name="error", location="xmlPreviewPanel.jsp"),
	  @Result(name="success", location="xmlPreviewPanel.jsp" ),
	  @Result(name="previewInput", location="xmlPreviewPanel.jsp" )
	})

public class XMLPreview extends GeneralAction implements ServletContextAware {
	public static final String SCENE_INPUT = "input";
	public static final String SCENE_SELECT_MAPPING = "selectableMap";
	public static final String SCENE_FIXED_MAPPING = "fixedMap";
	public static final String SCENE_PUBLISHED_ERROR = "publishedError";
	
	public static class PreviewTab {
		public static final int LONG_LENGTH_CONTENT = 10000;

		public static final String TYPE_TEXT = "text";
		public static final String TYPE_HTML = "html";
		public static final String TYPE_XML = "xml";
		public static final String TYPE_RDF = "rdf";
		public static final String TYPE_JSP = "jsp";

		String type;
		String url;
		String content;
		String title;

		public String getType() {
			return type;
		}
		public void setType(String type) {
			this.type = type;
		}

		public String getUrl() {
			return url;
		}
		public void setUrl(String url) {
			this.url = url;
		}

		public String getTitle() {
			return title;
		}
		public void setTitle(String title) {
			this.title = title;
		}
		public String getContent() {
			return content;
		}
		public void setContent(String content) {
			this.content = content;
		}
		public boolean hasLongContent() {
				
			return content.length() > LONG_LENGTH_CONTENT;
		}

		public PreviewTab( String title, String content, String type ) {
			this.content = content;
			this.title = title;
			this.type = type;
		}
		
		public PreviewTab( String title, String content, String type, String url ) {
			this.content = content;
			this.title = title;
			this.type = type;
			this.url = url;
		}
		
		public PreviewTab( Exception e ) {
			title = "Exception";
			type = TYPE_TEXT;
			StringWriter sw = new StringWriter();
			e.printStackTrace(new PrintWriter( sw ));
			content = sw.toString();
		}
		
		public String toString() {
			String result = "";
			
			if(title != null) result += title;
			else result += "PREVIEW-TAB";
		
			if(type != null) result += "(" + type + ")";
			result += ":";
			
			result += content;
			
			return result;
		}
	}
	
	
	protected final Logger log = Logger.getLogger(getClass());
	private long selMapping=0;
	private String uploadId;
	private String nodeId;
	private Mapping mapping;
	private String error;
	private ServletContext sc;
	private boolean truncated=false;
	private List<PreviewTab> tabs;
	private ArrayList<SAXParseException> report;
	private String scene;
	private boolean mappingSelector=false;
	private String validation="";
	private boolean isValid=false;
	
	private List<Mapping> maplist= new ArrayList<Mapping>();
	
	public List<Mapping> getMaplist() {
		try{
	
	    
        List<Mapping> alllist= DB.getMappingDAO().findAllOrderOrg();
        for(int i=0;i<alllist.size();i++){
          //now add the shared ones if not already in list
        	Mapping em=alllist.get(i);
           
        	//if shared and not locked add to template list
        	if(em.isShared() && !em.isLocked(getUser(), getSessionId())){
        		
        		maplist.add(em);
        	}
        	else if(!em.isShared() && !em.isLocked(getUser(), getSessionId())){
        	//if not shared but belongs to accessible org
        	 Organization org=em.getOrganization();
        	//need to check accessible and their parents
        	 List<Organization> deporgs=user.getAccessibleOrganizations();
             for(int j=0;j<deporgs.size();j++){
        	    if(deporgs.get(j).getDbID()==org.getDbID()){
        	    	//mapping org belongs in deporgs so add
        	    	if(!maplist.contains(em)){
        	    	maplist.add(em);}
        	    	break;
        	    }
        	    Organization parent=deporgs.get(j).getParentalOrganization();
        	    while(parent!=null && parent.getDbID()>0){
        	    	
	        	    if(parent.getDbID()==org.getDbID()){
	        	    	//mapping org belongs to parent of accessible so add
	        	    	if(!maplist.contains(em)){
	        	    	maplist.add(em);}
	        	    	break;
	        	    }
	        	    parent=parent.getParentalOrganization();
	        	    //traverse all parents OMG
	            }
        	}
         }
		}
		}
		catch (Exception ex){
			log.debug(" ERROR GETTING MAPPINGS:"+ex.getMessage());
		}
		if( maplist.isEmpty() ) maplist=Collections.emptyList();
		
		return maplist;
	}

	public String getScene() {
		return scene;
	}

	public void setScene(String scene) {
		this.scene = scene;
		if(SCENE_SELECT_MAPPING.equals( scene )){
			this.setMappingSelector(true);
		}
	}

	public List<PreviewTab> getTabs() {
		
		return tabs;
	}
	
	public ArrayList<SAXParseException> getReport() {
		return report;
	}
	
	public void setSelMapping(long selMapping) {
		this.selMapping = selMapping;
	}

	public long getSelMapping(){
		return selMapping;
	}
	
	public String getUploadId(){
		return uploadId;
	}
	
	public void setUploadId(String uploadId){
		this.uploadId=uploadId;
	}
	
	public String getNodeId(){
		return nodeId;
	}
	
	public void setNodeId(String nodeId){
		this.nodeId=nodeId;
	}
	
	public boolean isTruncated(){
		return this.truncated;
	}
	
	public String toRDF( String xml ) throws Exception {
		byte[] bytes = xml.getBytes();
		InputStream inputStream = new ByteArrayInputStream(bytes); 
		gr.ntua.ivml.mint.rdf.edm.EDM2RDF xml2rdf = new gr.ntua.ivml.mint.rdf.edm.EDM2RDF(inputStream);
		ByteArrayOutputStream outputStream = xml2rdf.convertToRDF();
		return outputStream.toString();
	}
	
	public XMLNode getNode() {
		XMLNode result = null;
	
		if(( getNodeId() != null ) && ( getNodeId().trim().length() > 0 )) {
			try {
				long nodeId = Long.parseLong(getNodeId());
				
				// if nodeId == 0 then get the first node from the data upload
				if(nodeId == 0) {
					DataUpload du = getDataUpload();
					List<XMLNode> list = du.getItemXpath().getNodes(0, 1);
					if(list != null && !list.isEmpty()) {
						result = list.get(0);
					}
				} else {
					XmlObject obj=getDataUpload().getXmlObject();
					result = DB.getXMLNodeDAO().getByIdObject(obj,nodeId);
					//result = DB.getXMLNodeDAO().getById(nodeId,false);
					if(result.getSize()>20000){
						truncated=true;
					}
				}
			} catch( Exception e ) {
				log.error( e );
			}
		}
		return result;
	}
	
	public DataUpload getDataUpload() {
		DataUpload result = null;
		if(( getUploadId() != null ) && 
				( getUploadId().trim().length() > 0 )) {
			try {
				long uploadId = Long.parseLong(getUploadId());
				result = DB.getDataUploadDAO().getById(uploadId, false);
			} catch( Exception e ) {
				log.error( e );
			}
		}
		return result;
	}
	
	
	public Mapping getMapping() {
		if( getSelMapping() >01 ) {
			try {
				mapping = DB.getMappingDAO().getById(getSelMapping(), false);
			} catch( Exception e ) {
				log.error( e );
			}
		}
		 return mapping;
		
	}
	
	public void setMapping( Mapping m ) {
		mapping = m;
	}
	
	
	public void setMappingSelector( boolean mappingSelector ) {
		this.mappingSelector = mappingSelector;
	}
	
	public boolean getMappingSelector( ) {
		return(this.mappingSelector );
	}
	
	/**
	 * Returns XML for selected Node.
	 * @return
	 */
	public String getItemPreview() {
		if( ! hasItemPreview() ) return "";
		StringWriter xmlWriter = new StringWriter();

		XMLNode node = getNode();
		
		node.toXmlWrapped(new PrintWriter(xmlWriter));

		String xml = xmlWriter.toString();
		xml = XMLFormatter.format(xml); 

		return xml;
	}
	
	public boolean hasItemPreview() {
		
		return getNodeId()!=null;
	}
	
	/**
	 * Return transformed XML for selected node.
	 * Needs a selected mapping or DataUpload with
	 * finished transformation and a node.
	 * @return
	 */
   public String getValidation(String transformation){
	   try{
		byte[] bytes = transformation.getBytes("UTF-8");
		ByteArrayInputStream inputStream = new ByteArrayInputStream(bytes);
		StreamSource source = new StreamSource(inputStream);  
		
		ReportErrorHandler reportHandler = new ReportErrorHandler();
		SchemaValidator.validate( source, mapping.getTargetSchema(), reportHandler);
		validation = reportHandler.getReportMessage();
		report = reportHandler.getReport();
		this.isValid = reportHandler.isValid();
	   } catch(Exception e) {
			validation = e.getMessage();
			this.isValid = false;
		}
	   return validation;
   }
   
   
	public String getTransformPreview() {
		DataUpload du = getDataUpload();
		
		if(du.isDirect()) {
			return getItemPreview();
		} else {
			String transformedItem=null;
			if( !hasTransformPreview()) return "No transformed View available"; 
			XSLTransform t = new XSLTransform();
			try {
			transformedItem = t.transform(getItemPreview(), getSchemaXsl());
			transformedItem = XMLFormatter.format(transformedItem);
			} catch( Exception e ) {
				StringWriter sw = new StringWriter();
				PrintWriter pw = new PrintWriter( sw );
				e.printStackTrace(pw);
				transformedItem = sw.toString();
			}
			return transformedItem;
		}
	}
	
	
	public String getTransformedPreview() {
		long nid=Long.parseLong(this.getNodeId());
		
		XMLNode n = DB.getXMLNodeDAO().getById(nid, false);
		StringWriter xmlWriter = new StringWriter();
		String xml="";
		try {
			n.toXmlWrapped(new PrintWriter(xmlWriter));
	
			xml = xmlWriter.toString();
			xml = XMLFormatter.format(xml);
				
	
		} catch( Exception e ) {
			log.error( e );
		}	
			return xml;
			
	}
	
	
	public boolean hasTransformPreview() {
		if( getNode() == null ) return false;
		if( getMapping() != null ) return true;
		if( getDataUpload() == null ) return false;
		
		// maybe the upload has been successfully transformed
		List<Transformation> l = DB.getTransformationDAO().findByUpload( getDataUpload());
		for( Transformation t: l ) {
			if( t.getStatusCode() == Transformation.OK ) {
				setMapping( t.getMapping());
				return true;
			}
		}
		return false;
	
	}
	
	

	/**
	 * Provide the Lido XSL for output. Needs to be able to find
	 * the Upload.
	 * @return
	 */
	public String getSchemaXsl() {
		DataUpload du = getDataUpload();
		XSLTGenerator xslt = new XSLTGenerator();
		XpathHolder itemPath = du.getItemXpath();
		
		String result = null;
		
		if(!du.isDirect()) {
			xslt.setItemLevel(itemPath.getXpathWithPrefix(true));
			xslt.setTemplateMatch(itemPath.getXpathWithPrefix(true));
			xslt.setImportNamespaces(du.getRootXpath().getNamespaces(true));
			
			String mappings = getMapping().getJsonString();
			String xsl = XMLFormatter.format(xslt.generateFromString(mappings));
			
			result = xsl;
		}
		
		return result;
	}
	

	
	@Action(value="XMLPreview")
    public String execute() throws Exception {
		if( uploadId == null ) setError( "Missing uploadId parameter" );
		if( nodeId == null) setError( "Missing nodeId parameter" );
		if(this.hasActionErrors()){
			return ERROR;
		}
		else{buildTabs();}
		
		if(this.hasActionErrors()){
			return ERROR;
		}
		else	return SUCCESS;   	
    }
/*
	@Action(value="xmlPreviewInput")
	public String previewInput() throws Exception {
		log.debug( "Action: xmlPreviewInput");
		if( uploadId == null ) setError( "Missing uploadId parameter" );
		if( nodeId == null) setError( "Missing nodeId parameter" );
		return "previewInput";
	}
	*/	
		
	@Action("XMLPreview_input")
	@Override
	public String input() throws Exception {
		if( uploadId == null ) setError( "Missing uploadId parameter" );
		if( nodeId == null) setError( "Missing nodeId parameter" );
		if(this.hasActionErrors()){
			return ERROR;
		}
		else{buildTabs();}
		return super.input();
	}


	public void setError(String error) {
		addActionError(error);
	}


	public String getError() {
		return StringEscapeUtils.escapeHtml(error);
	}

	public boolean hasMappingSelector() {
		return mappingSelector;
	}
	
	/**
	 * Overwrite and call super. Then modify the results as you like them.
	 */
	public void buildTabs() {
		this.tabs = new ArrayList<PreviewTab>();
		String output = null;
		Mapping mapping = getMapping();
		XmlSchema schema = null;
		DataUpload du = getDataUpload();
		if(du!=null){
		if(du.isDirect()) {
			schema = du.getDirectSchema();
		} else if(mapping != null) {
			schema = mapping.getTargetSchema();
		} else if(du.getTransformations().size() > 0){
			Transformation t = du.getTransformations().get(0);
			if(t != null) {
				schema = t.getMapping().getTargetSchema();
			}
		}
		}
		try {
			if( SCENE_INPUT.equals( scene ) ) {
				tabs.add( new PreviewTab( "Input", getItemPreview(), PreviewTab.TYPE_XML));
			} else if( SCENE_SELECT_MAPPING.equals( scene )) {
				if( selMapping ==0 ) {
					setError("Please select a mapping");
				}
				else if(selMapping!=0 && (this.mapping==null || mapping.getJsonString().length()==0)){
						setError( "Mappings selected are empty. Please define proper mappings.");
				}
				else {
					tabs.add( new PreviewTab( "Input",  getItemPreview(), PreviewTab.TYPE_XML));
					tabs.add( new PreviewTab( "XSL", getSchemaXsl(), PreviewTab.TYPE_XML));
					output=getTransformPreview();
					tabs.add( new PreviewTab( "Output", output, PreviewTab.TYPE_XML));
					tabs.add( new PreviewTab( "Validation", getValidation(output), PreviewTab.TYPE_TEXT));
				}
			} else if( SCENE_FIXED_MAPPING.equals( scene )) {
				if(du.isDirect()) {
					output = getItemPreview();
					tabs.add( new PreviewTab( "Input", output, PreviewTab.TYPE_XML));
				} else {
					tabs.add( new PreviewTab( "Input", getItemPreview(), PreviewTab.TYPE_XML));
					
					output = getTransformPreview();
					tabs.add( new PreviewTab( "XSL", getSchemaXsl(), PreviewTab.TYPE_XML));
					tabs.add( new PreviewTab( "Output", output, PreviewTab.TYPE_XML));
					tabs.add( new PreviewTab( "Validation", getValidation(output), PreviewTab.TYPE_TEXT));
				}
			}
			else if( SCENE_PUBLISHED_ERROR.equals( scene )) {
				long nid=Long.parseLong(this.getNodeId());
				
				output=this.getTransformedPreview();
				tabs.add( new PreviewTab( "Transformed Item ", output, PreviewTab.TYPE_XML));
				XmlObject xo = DB.getXmlObjectDAO().findByNodeId(nid);
				if( xo != null ) {
					Transformation tr = DB.getTransformationDAO().findByXmlObject( xo );
					schema=tr.getMapping().getTargetSchema();
					
				} 
				
						
			}
		} catch( Exception e ) {
			tabs.clear();
			tabs.add( new PreviewTab( e ));
		}
		
		
		if(output != null && schema != null) {
			ChainTransform chain = new ChainTransform();
			try{
			ArrayList<PreviewTab> more = chain.transform(output, schema);
			tabs.addAll(more);
			}catch (Exception ex){
				log.debug(" ERROR on chain transform:"+ex.getMessage());
				
			}
			//log.debug(more);
			
		}
		
		
	}

	public String exceptionTrace( Exception e ) {
		StringWriter sw = new StringWriter();
		PrintWriter pw = new PrintWriter( sw );
		e.printStackTrace(pw);
		return sw.toString();
	}
	
	@Override
	public void setServletContext(ServletContext sc) {
		this.sc = sc;
		
	}	
	
}