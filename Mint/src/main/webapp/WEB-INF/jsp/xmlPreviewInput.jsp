<%@ include file="_include.jsp"%>

<%@page import="java.io.*"%>
<%@page import="org.apache.commons.lang.StringEscapeUtils"%>

<%@page import="gr.ntua.ivml.mint.db.*"%>
<%@page import="gr.ntua.ivml.mint.persistent.*"%>
<%@page import="gr.ntua.ivml.mint.mapping.*"%>
<%@page import="gr.ntua.ivml.mint.xml.transform.*"%>
<%
  String itemXml="";
  String error=(String)request.getAttribute("error");
  if(error==null){
	  itemXml=(String) request.getAttribute( "itemPreview" );
  }
%>
<div class="yui-skin-sam" style="width: 100%; height: 100%">
<% if(error!=null){ %> 
	<div id="previewTabs" class="yui-navset">
	    <ul class="yui-nav"> 
	        <li class="selected"><a href="#tab1"><em>Error</em></a></li> 
	    </ul>
	   	<div class="yui-content"> 
	        <div><p><div style="width: 100%; height: 400px; overflow-x: auto; overflow-y: auto">
	        	<textarea class='xml' style='width: 100%' rows='25' columns='50' readonly><s:property value="error"/></textarea>
	        </div></p></div>
	    </div>              
	</div>
<%}else{
  if(itemXml.length()>10000){
%>
<div id="previewTabs" class="yui-navset"> 
  <s:if test="truncated==true">
  <div style="color:red;">This item is too large to display and has been truncated.</div>
  </s:if>   
	    <ul class="yui-nav"> 
	        <li class="selected"><a href="#tab1"><em>Input XML</em></a></li> 
	       
	    </ul>   
	  
	   
	    <div class="yui-content"> 
		<p><div style="width: 95%; height: 400px;">
	  			<textarea  name='code'style='width: 100%;background: #FFFFFF;' rows='22' columns='50' readonly><%=StringEscapeUtils.escapeHtml(itemXml)%></textarea>

	    </div></p>
	    </div>
</div>	    
	
<%}else{ %>

	<div id="previewTabs" class="yui-navset"> 
	    <ul class="yui-nav"> 
	        <li class="selected"><a href="#tab1"><em>Input XML</em></a></li> 
	       
	    </ul>             
	    <div class="yui-content"> 
	        <div><p><div style="width: 100%; height: 400px; overflow-x: auto; overflow-y: auto">
	        	<textarea name='code' class='xml' style='width: 100%' rows='25' columns='50' readonly><%=
	        		StringEscapeUtils.escapeHtml(itemXml)%></textarea>
	        </div></p></div>	       
	    </div> 
	</div> 
<%}
}%>

</div>
