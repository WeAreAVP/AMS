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
String xpath = request.getParameter("xpath");
String type = request.getParameter("type");

if(uploadId == null || type == null || xpath==null) {
%><h3>Error: Missing uploadId parameter</h3><% 
} else if ( uploadId != null && type != null && xpath.indexOf("-1")<0) {
	DB.getSession().beginTransaction();

	DataUpload du = DB.getDataUploadDAO().findById(Long.parseLong(uploadId), false);
	if(type.equalsIgnoreCase("label") && xpath.indexOf("@")>-1){
		xpath=xpath.substring(0,xpath.lastIndexOf("/text()"));
	}
	XpathHolder xp = du.getXmlObject().getRoot().getByRelativePathWithPrefix(xpath,true);
		
	if(xp != null) {
		
		if(type.equalsIgnoreCase("level")) {
			if(xp.getXpathWithPrefix(true).indexOf("@")>-1){
		
				out.println("Attributes are not valid as item labels. Please select another node.");
				 
			}
			else{
			  du.setItemXpath(xp);
			  du.setItemLabelXpath(null);
			  DB.commit();
   			  out.println(xp.getXpathWithPrefix(true));

			}
		} else {
			du.setItemLabelXpath(xp);
			DB.commit();
			
			out.println(xp.getXpathWithPrefix(true));

			
		}
		
	
			} else {
		out.println("This node is not valid for item label because it contains no value. Please select another node.");
	}
		
}
else if(uploadId != null && type != null && xpath.indexOf("-1")==0){
	DB.getSession().beginTransaction();
 	DataUpload du = DB.getDataUploadDAO().findById(Long.parseLong(uploadId), false);
	XpathHolder xp=null;
	if(type.equalsIgnoreCase("level")) {
		   du.setItemXpath(xp);
			
	} else {
		   du.setItemLabelXpath(xp);
			
	}
		
	
	DB.commit();
		

	
}
%>