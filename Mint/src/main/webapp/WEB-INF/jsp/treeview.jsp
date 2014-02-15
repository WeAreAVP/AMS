<%@page import="java.util.HashMap"%>
<%@page import="net.sf.json.*"%>
<%@page import="gr.ntua.ivml.mint.mapping.*"%>
<%@page import="gr.ntua.ivml.mint.db.*"%>
<%@page import="gr.ntua.ivml.mint.persistent.*"%>
<%@ page import="org.apache.log4j.Logger" %>


<%! public final Logger log = Logger.getLogger(this.getClass());%>
<%
out.clear();
response.setContentType("text/plain; charset=UTF-8");

String uploadId = request.getParameter("uploadId");
String itemxpath = request.getParameter("itemxpath");
String schemaId = request.getParameter("schema");
boolean hide=false;
if(uploadId == null) {
%><h1>Error: Missing uploadId parameter</h1><%
} else {
	DataUpload dataUpload = DB.getDataUploadDAO().findById(Long.parseLong(uploadId), false);
	Schema schema = null;
	if(schemaId != null) {
		schema = new Schema(schemaId);
	} else {
		schema = new Schema("1");
	}
	log.debug("itemxpath="+itemxpath);
	XpathHolder level_xp = dataUpload.getItemXpath();
	
    if(itemxpath!=null && itemxpath.equalsIgnoreCase("1") && level_xp != null && level_xp.getXpathWithPrefix(true).length()>0){
    	schema.initSubtreeFormUpload(dataUpload);
    }else{
    	schema.initFromUpload(dataUpload);	
    }
	out.println(schema.printTree(false));}
%>