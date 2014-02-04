<%@ include file="_include.jsp"%>
<%@page import="java.io.*"%>
<%@page import="org.apache.commons.lang.StringEscapeUtils"%>
<%@page import="gr.ntua.ivml.mint.db.*"%>
<%@page import="gr.ntua.ivml.mint.persistent.*"%>
<%@page import="gr.ntua.ivml.mint.mapping.*"%>
<%@page import="gr.ntua.ivml.mint.xml.transform.*"%>
<jsp:useBean id="fullDoc" class="gr.ntua.ivml.mint.xml.FullBean"/>
<div class="yui-skin-sam" style="width: 100%; height: 100%">
<%
String uploadId = request.getParameter("uploadId");
String nodeId = request.getParameter("nodeId");
if(uploadId == null) {
%><h1>Error: Missing uploadId parameter</h1><%
} else if(nodeId == null) {
%><h1>Error: Missing nodeId parameter</h1><%
} else {
	String error = null;
	StringWriter xmlWriter = new StringWriter();
	String input_xml = "";
	String xsl = "";
	String ese = "";
	String output_xml = "";
	String output_ese = "";
	String mess="";
	boolean truncated=false;
	DataUpload du = DB.getDataUploadDAO().findById(Long.parseLong(uploadId), false);
	boolean isLido = du.isDirect();
	java.util.List trlist=DB.getTransformationDAO().findByUpload(du);
	if(trlist.size()==0){
		error = "Transformation no longer exists";			
	}
	else{
		Transformation tr=(Transformation)trlist.get(trlist.size()-1);
		Mapping mp=tr.getMapping();
		if(!isLido)
		 mess="Transformed using Mappings " +mp.getName();
		if(mess.length()>0){%>
			<script>  
			xmlPreviewPanel.setHeader("XML Transformed"+<%=mess%>);</script>
		<%}
		String mappings=tr.getJsonMapping();
	

	if(mappings == null && (!isLido)) {
		error = "The mappings used for this transformation are no longer available.";
	} else {
		XMLNode node = DB.getXMLNodeDAO().findById(Long.parseLong(nodeId), false);
		if(node.getSize()>20000){
			truncated=true;
		}
		//XMLNode treeNode= DB.getXMLNodeDAO().getDOMTree( node );
		//treeNode.toXml(new PrintWriter(xmlWriter));
        node.toXmlWrapped(new PrintWriter(xmlWriter));
        
		input_xml = xmlWriter.toString();	
		input_xml = input_xml.replaceFirst("xmlns=\"[^\"]*\"", "");	
		input_xml = XMLFormatter.format(input_xml); 
		
		XSLTGenerator xslt = new XSLTGenerator();
		XSLTransform t = new XSLTransform();
		if(!isLido){
				xslt.setItemLevel(du.getItemXpath().getXpathWithPrefix(true));
				xslt.setTemplateMatch(node.getXpathHolder().getXpathWithPrefix(true));
				xslt.setImportNamespaces(du.getRootXpath().getNamespaces(true));
				xsl = XMLFormatter.format(xslt.generateFromString(mappings));
				
		}
		
		File eseFile = new File(this.getServletContext().getRealPath(gr.ntua.ivml.mint.util.Config.get( "lido_to_ese_xsl")));
		
		StringBuilder ese_contents = new StringBuilder();
   		try {
      		BufferedReader input =  new BufferedReader(new FileReader(eseFile));
      		try {
	    	    String line = null; //not declared within while loop
	    	    while (( line = input.readLine()) != null){	    	
					ese_contents.append(line);
					ese_contents.append(System.getProperty("line.separator"));
        		}
	   		} finally {
        		input.close();
      		}
    	}
    	catch (IOException ex){
      		ex.printStackTrace();
		}
    	
    	ese = ese_contents.toString();
					
		try {
			if(!isLido){
				output_xml = t.transform(input_xml, xsl);
				output_xml = XMLFormatter.format(output_xml);
			}else{
			    output_xml=input_xml;	
			}
			if(ese != null && ese.length() > 0) {
				output_ese = t.transform(output_xml, ese);
				fullDoc=gr.ntua.ivml.mint.xml.ESEToFullBean.getFullBean(output_ese);
			
				output_ese = XMLFormatter.format(output_ese);
				}
		} catch(Exception e) {
			//Writer result = new StringWriter();
    		//PrintWriter printWriter = new PrintWriter(result);
    		//e.printStackTrace(printWriter);
//			output_xml = result.toString();
		}
		
		/*
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
			try {
				String edmxml = transformPreview;
				byte[] bytes = edmxml.getBytes();
				InputStream inputStream = new ByteArrayInputStream(bytes); 
				gr.ntua.ivml.mint.rdf.edm.XML2RDF xml2rdf = new gr.ntua.ivml.mint.rdf.edm.XML2RDF(inputStream);
				ByteArrayOutputStream outputStream = xml2rdf.convertToRDF();
				String edmrdf = outputStream.toString();
				rdf = edmrdf;
			} catch(Exception e) {
				rdf = e.getMessage();
			}
		*/
	}
	}
	if(error != null) {
%>
<div><%=mess %></div><br/>
	<div="previewTabs" class="yui-navset">
	    <ul class="yui-nav"> 
	        <li class="selected"><a href="#tab1"><em>Error</em></a></li> 
	    </ul>
	   	<div class="yui-content"> 
	        <div><p><div style="width: 100%; height: 400px; overflow-x: auto; overflow-y: auto">
	        	<textarea class='xml' style='width: 100%' rows='25' columns='50' readonly><%=
	        		StringEscapeUtils.escapeHtml(error)
	        	%></textarea>
	        </div></p></div>
	    </div>              
	</div>
<%
	} else {
%>
	<div><%=mess %></div><br/>
	
	 <%if(truncated==true){%>
        <div style="font-color:red;">This item is too large to display and has been truncated. This could result in mappings and transformation not showing fully on this preview.
        </div>
       <%}%>  
	<div id="previewTabs" class="yui-navset"> 
	    <ul class="yui-nav"> 
	        <li class="selected"><a href="#tab1"><em>Input</em></a></li> 
	        <li><a href="#tab2"><em>XSL</em></a></li>
	        <li><a href="#tab3"><em>Output</em></a></li> 
	    </ul>             
	    <div class="yui-content"> 
	    <%if(input_xml.length()>10000){ %>
	    	<div><p><div style="width: 95%; height: 350px;">
					  			<textarea  name='code' style='width: 100%; height: 340px; background: #FFFFFF;' rows='22' columns='50' readonly><%=StringEscapeUtils.escapeHtml(input_xml)%></textarea>
				
					    </div></p>
					    </div>
	    
	    <%}else{ %>
	      <div><p><div style="width: 100%; height: 360px; overflow-x: auto; overflow-y: auto">
	        	<textarea name='code' class='xml' style='width: 100%' rows='25' columns='50' readonly><%=
	        		StringEscapeUtils.escapeHtml(input_xml)
	        	%></textarea>
	        </div></p></div>
	    
	    <%} %>
	     <%if(!isLido){ %>
	     <%if(xsl.length()>10000){ %>
	        <div><p><div style="width: 100%; height: 360px; overflow-x: auto; overflow-y: auto">
	        	<textarea name='code' style='width: 100%; height: 340px; background: #FFFFFF;' rows='25' columns='50' readonly><%=
	        		StringEscapeUtils.escapeHtml(xsl)
	        	%></textarea>
	        </div></p></div>
	       <%}else{ %>
	        <div><p><div style="width: 100%; height: 360px; overflow-x: auto; overflow-y: auto">
	        	<textarea name='code' class='xml' style='width: 100%' rows='25' columns='50' readonly><%=
	        		StringEscapeUtils.escapeHtml(xsl)
	        	%></textarea>
	        </div></p></div>
	       <%} %>
	        <%if(output_xml.length()>10000){ %>
	        <div><p><div style="width: 100%; height: 360px; overflow-x: auto; overflow-y: auto">
	        	<textarea name='code' style='width: 100%; height: 340px; background: #FFFFFF;' rows='25' columns='50' readonly><%=
	        		StringEscapeUtils.escapeHtml(output_xml)
	        	%></textarea>
	        </div></p></div>
	        <%}else{ %>
	         <div><p><div style="width: 100%; height: 360px; overflow-x: auto; overflow-y: auto">
	        	<textarea name='code' class='xml' style='width: 100%' rows='25' columns='50' readonly><%=
	        		StringEscapeUtils.escapeHtml(output_xml)
	        	%></textarea>
	        </div></p></div>
	        <%}
	        }//islIDO%>
	    </div> 
	</div> 
	
</div>

<%
	}
}
%>
