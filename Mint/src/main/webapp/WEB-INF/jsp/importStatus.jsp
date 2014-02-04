<%@ taglib prefix="s" uri="/struts-tags" %>
<%if(request.getAttribute("status")==null){
	System.out.println("STATUS IS:"+request.getAttribute("status"));%>
<div>UNKNOWN</div> 
<%} %>
<%@ include file="_includeimportstat.jsp"%>
							      