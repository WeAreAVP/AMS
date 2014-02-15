<%@ include file="_include.jsp"%>
<%@page import="java.io.*"%>
<%@page import="org.apache.commons.lang.StringEscapeUtils"%>

<%@page import="gr.ntua.ivml.mint.db.*"%>
<%@page import="gr.ntua.ivml.mint.persistent.*"%>


<jsp:useBean id="fullDoc" class="gr.ntua.ivml.mint.xml.FullBean"/>


<style type="text/css">

form.athform label {
	
		float:left;
	
		}
</style>

<div class="yui-skin-sam" style="width: 100%; height: 100%">
<%
	String uploadId = (String)request.getAttribute("uploadId");
String nodeId = (String)request.getAttribute("nodeId");
if(uploadId == null) {
%><h1>Error: Missing uploadId parameter</h1><%
	} else if(nodeId == null) {
%><h1>Error: Missing nodeId parameter</h1><%
	} else {
	String error = null;
	StringWriter xmlWriter = new StringWriter();
	DataUpload du = DB.getDataUploadDAO().findById(Long.parseLong(uploadId), false);

    String output_ese=(String) request.getAttribute( "eseXml" );
	if(output_ese != null && output_ese.length() > 0) {
		fullDoc=gr.ntua.ivml.mint.xml.ESEToFullBean.getFullBean(output_ese);
	}
%>
<p><font color="red"><%  if(error != null) {%>
	
 <%=error %>
 <%} %>
   <s:if test="truncated==true">
  This item is too large to display and has been truncated. This could result in mappings and transformation not showing fully on this preview.
  </s:if>  
 </font></p>  



<%	 
   String itemXml="";
   String eseXml="";
   if(error == null){
	   itemXml=(String) request.getAttribute( "itemPreview" );
	   eseXml= (String) request.getAttribute( "eseXml" );
	   
%>
    <div> The following item from:<b>'<%=(String) request.getAttribute( "uploadName" ) %>'</b> failed during <b><%=(String) request.getAttribute( "errorSrc" ) %></b></div>
    <br/>
	<div id="previewTabs" class="yui-navset"> 
	    <ul class="yui-nav"> 
	        <li class="selected"><a href="#tab1"><em>Input</em></a></li> 
	        
	        <li><a href="#tab2"><em>Output(ESE)</em></a></li>
	        <li><a href="#tab3"><em>Europeana</em></a></li>
	      
	     
	    </ul>             
	    <div class="yui-content"> 
	        <%if(itemXml.length()>10000){%>
				
						<div><p><div style="width: 95%; height: 350px;">
					  			<textarea  name='code' style='width: 100%; height: 335px; background: #FFFFFF;' rows='22' columns='50' readonly><%=StringEscapeUtils.escapeHtml(itemXml)%></textarea>
				
					    </div></p>
					    </div>
				<%}else{ %>
				
					  <div><p><div style="width: 100%; height: 350px; overflow-x: auto; overflow-y: auto">
					        	<textarea name='code' class='xml' style='width: 100%' rows='25' columns='50' readonly><%=
					        		StringEscapeUtils.escapeHtml(itemXml)%></textarea>
					        </div></p></div>	       
					    
				<%}%>
	        
	          <div><p><div style="width: 100%; height: 350px; overflow-x: auto; overflow-y: auto">
	        	<textarea name='code' class="xml" style='width: 100%' rows='25' columns='50' readonly><%=StringEscapeUtils.escapeHtml(eseXml)%></textarea>
	        </div></p></div>
	   
	        <div><p><div style="width: 100%; height: 350px; overflow-x: auto; overflow-y: auto; background-color:#FFFFFF;">
	               <%@ include file="eseview.jsp"%>
	        </div></p></div>
	    
	     
	         </div> 
	</div> 
	
</div>

<%
	}
}
%>