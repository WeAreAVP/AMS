<%@ include file="_include.jsp"%>
<%@page import="java.io.*"%>
<%@page import="org.apache.commons.lang.StringEscapeUtils"%>

<div class="yui-skin-sam" style="width: 100%; height: 100%">
<%
String report=(String)request.getAttribute("report");
String error="";	
String pstatus=(String)request.getAttribute("pstatus");
if(!pstatus.equalsIgnoreCase("OK") && !pstatus.equalsIgnoreCase("ERROR")){error="No report is yet available";}
%>
<%  if(error.length()>0) {%>
<p><font color="red">	
 <%=error %>
 </font></p>
 <%}else{ 
	   %>
       
       <div class="yui-content"> 
	     			  <div style="width: 100%; height: 450px; overflow-x: auto; overflow-y: auto">
	     			           <div class="dp-highlighter" style="background-color:#FFF;"><%=report%>
					        	  	
					        	</div>
					        </div>	       
	  
	 
<%} %>	
</div>

