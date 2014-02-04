<%@ include file="_include.jsp"%>
<%@page import="java.io.*"%>
<%@page import="javax.xml.transform.stream.StreamSource"%>
<%@page import="org.apache.commons.lang.StringEscapeUtils"%>

<%@page import="gr.ntua.ivml.mint.db.*"%>
<%@page import="gr.ntua.ivml.mint.persistent.*"%>
<%@page import="gr.ntua.ivml.mint.mapping.*"%>
<%@page import="gr.ntua.ivml.mint.xml.transform.*"%>
<%@page import="gr.ntua.ivml.mint.xsd.SchemaValidator"%>
<jsp:useBean id='mapsum' class='gr.ntua.ivml.mint.mapping.MappingSummary' scope='request'/>
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
java.util.Collection<String> missing=new java.util.ArrayList<String>();
java.util.Collection<String> invalid=new java.util.ArrayList<String>();
if(uploadId == null) {
%><h1>Error: Missing uploadId parameter</h1><%
} else if(nodeId == null) {
%><h1>Error: Missing nodeId parameter</h1><%
} else {
	String error = null;
	StringWriter xmlWriter = new StringWriter();
	DataUpload du = DB.getDataUploadDAO().findById(Long.parseLong(uploadId), false);
	Long selMapping = (Long)request.getAttribute("selMapping");
	String mappings=null;
	try{
	   Mapping maps=DB.getMappingDAO().findById(selMapping,false);
       mappings=maps.getJsonString();
	}
	catch (Exception ex){
	
	}
	if(selMapping==0.0 ) {
		
		error = "No mappings defined. First define your mappings to generate an XSL preview";
	}
	else if(selMapping>0 && (mappings==null || mappings.length()==0)){
		error = "Mappings selected are empty. Please define proper mappings.";
		 
	}
	else {
		missing=mapsum.getMissingMappings(mappings);
	    invalid=mapsum.getInvalidXPaths(du,mappings);
	    
//	    if(invalid.size()==0){ - by fxeni, removed check for invalid paths
	    String output_ese=(String) request.getAttribute( "eseXml" );
	    if(output_ese != null && output_ese.length() > 0) {
	    	try{
			 fullDoc=gr.ntua.ivml.mint.xml.ESEToFullBean.getFullBean(output_ese);
	    	}
	    	catch(Exception e) {
	    		System.out.println(e.getMessage());
	    	}
	      }
//	    }		
	}
	
%>
<s:form name="XMLPreview" action="XMLPreview" cssClass="athform" theme="mytheme" enctype="multipart/form-data" style="width:100%;margin-top:-10px;">
	<fieldset style="background-image: url(../images/spacer.gif);background-repeat: none;">
	<p>&nbsp;	Select the mappings that will be used for the transformation preview:</p>
	  		<ol><li style="background: none; margin-top:-5px;"><div style="float:left;"><%
	    	 java.util.List templateMappings=(java.util.List)request.getAttribute("maplist");
			 String sel="";
		  	%>	
			<select id="XMLPreview_selMapping" name="selMapping" onchange="javascript:ajaxXmlTransform(this.value); ">
			  	<option value="0">-- No template --</option>
			  	<%Organization lastorg=new Organization();
			  	  for(int i=0;i<templateMappings.size();i++){
				   Mapping tempmap=(Mapping)templateMappings.get(i);
				   if((Long)request.getAttribute("selMapping")-tempmap.getDbID()==0.0){
					   sel="selected";
				   }
				   else{sel="";}
				   Organization current=tempmap.getOrganization();
				   if(lastorg!=null && current!=null && !lastorg.equals(current)){
					   if(i>0){%>
			    	     </optgroup>  
			           <%}
					   lastorg=current;
					   %>
				         <optgroup label="<%=lastorg.getEnglishName() %>">
				       <%
				     
				   }
				   
				   String cssclass="";
				  
				   if(tempmap.isFinished()){
					   cssclass+="finished";
				   }
				   if(tempmap.isShared()){
					   cssclass+=" shared";
				   }
				  %> 
				 <option value="<%=tempmap.getDbID() %>" class="<%=cssclass %>" <%=sel%>><%=tempmap.getName() %></option>
				   
				  
				
				  <%  }%>
			  	  <%if(templateMappings.size()>0){ %>
			  	          </optgroup>  
			      <%} %>
			  	
                </select>
		</div></li></ol>
		</fieldset>		
		<p><font color="red"><%  if(error != null) {%>
	
 <%=error %>
 <%} %>
   <s:if test="truncated==true">
  This item is too large to display and has been truncated. This could result in mappings and transformation not showing fully on this preview.
  </s:if>  
 </font></p>  
</s:form>


<%	 
   String itemXml="";
   String lidoxsl="";
   String transformPreview="";
   String validation="";
   String rdf="";
   if(error == null){
	   itemXml=(String) request.getAttribute( "itemPreview" );
	   lidoxsl=	(String) request.getAttribute( "schemaXsl" );
	   transformPreview=(String) request.getAttribute("transformPreview");
	   
		// validate
		boolean isValid = false;
		try {
//			Long selMapping = (Long)request.getAttribute("selMapping");
			Mapping mapping = DB.getMappingDAO().findById(selMapping, false);
			String output = transformPreview;
			byte[] bytes = output.getBytes();
			ByteArrayInputStream inputStream = new ByteArrayInputStream(bytes); 
			StreamSource source = new StreamSource(inputStream);
			SchemaValidator.validate(source, mapping.getTargetSchema());
			validation = "XML is valid";
			isValid = true;
		} catch(Exception e) {
			validation = e.getMessage();
		}
		
		// covert to rdf
		if(isValid) {
			try {
				String edmxml = transformPreview;
				byte[] bytes = edmxml.getBytes();
				InputStream inputStream = new ByteArrayInputStream(bytes); 
				gr.ntua.ivml.mint.rdf.edm.EDM2RDF xml2rdf = new gr.ntua.ivml.mint.rdf.edm.EDM2RDF(inputStream);
				ByteArrayOutputStream outputStream = xml2rdf.convertToRDF();
				String edmrdf = outputStream.toString();
				rdf = edmrdf;
			} catch(Exception e) {
				rdf = e.getMessage();
			}
		}
%>
 
	<div id="previewTabs" class="yui-navset"> 
	    <ul class="yui-nav"> 
	        <li class="selected"><a href="#tab1"><em>Input</em></a></li> 
	        <li><a href="#tab2"><em>XSL</em></a></li>
	        <li><a href="#tab3"><em>Output</em></a></li> 
	        <li><a href="#tab4"><em>Validation</em></a></li> 
	        <li><a href="#tab5"><em>RDF</em></a></li> 
	        <li><a href="#tab7"><em>Europeana</em></a></li>
	      
	     
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
	        
	        
	         <%if(lidoxsl.length()>10000){%>   
	        <div><p><div style="width: 100%; height: 350px; overflow-x: auto; overflow-y: auto">
	        	<textarea name='code' style='width: 100%; height: 335px; background: #FFFFFF;' rows='25' columns='50' readonly><%=
	        		StringEscapeUtils.escapeHtml(lidoxsl)
	        	%></textarea>
	        </div></p></div>
	        <%}else{ %>
	            <div><p><div style="width: 100%; height: 350px; overflow-x: auto; overflow-y: auto">
	        	<textarea name='code' class='xml' style='width: 100%' rows='25' columns='50' readonly><%=
	        		StringEscapeUtils.escapeHtml(lidoxsl)
	        	%></textarea>
	        </div></p></div>
	        
	        <%} %>
	        
	            <%if(transformPreview.length()>10000){%>   
	   
	        <div><p><div style="width: 100%; height: 350px; overflow-x: auto; overflow-y: auto">
	        	<textarea name='code' style='width: 100%; height: 335px; background: #FFFFFF;' rows='25' columns='50' readonly><%=
	        		StringEscapeUtils.escapeHtml(transformPreview)
	        	%></textarea>
	        </div></p></div>
	        <%}else{%>
	         <div><p><div style="width: 100%; height: 350px; overflow-x: auto; overflow-y: auto">
	        	<textarea name='code' class='xml' style='width: 100%' rows='25' columns='50' readonly><%=
	        		StringEscapeUtils.escapeHtml(transformPreview)
	        	%></textarea>
	        </div></p></div>
	        <%} %>
	        <div><p><div style="width: 100%; height: 350px; overflow-x: auto; overflow-y: auto">
	        	<textarea name='code' class='xml' style='width: 100%' rows='25' columns='50' readonly><%=
	        		StringEscapeUtils.escapeHtml(validation)
	        	%></textarea>
	        </div></p></div>
	        <div><p><div style="width: 100%; height: 350px; overflow-x: auto; overflow-y: auto">
	        	<% if(isValid) { %>
	        	<textarea name='code' class='xml' style='width: 100%' rows='25' columns='50' readonly>
	        	<%=
	        		StringEscapeUtils.escapeHtml(rdf)
	        	%>
	        	</textarea>
	        	<% } %>
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