<%@ include file="_include.jsp"%>
<%@page import="java.util.Iterator"%>
<%@page import="gr.ntua.ivml.mint.xml.Statistics" %>
<%@page import="gr.ntua.ivml.mint.persistent.DataUpload" %>
<%@page import="gr.ntua.ivml.mint.persistent.XpathHolder" %>
<%@page import="gr.ntua.ivml.mint.db.DB" %>
<%@page import="java.util.List" %>


<div id="ajaxTableContainer">
<table id="ajaxTable">
<thead> 
  <tr>
    <th> Value </th>
    <th> Frequency </th>
  </tr>
</thead>
<tbody>
<%
	// get the XpathHolder
	String xpathStringId = request.getParameter("pathId");
	try {
		XpathHolder xp = DB.getXpathHolderDAO().getById(
				Long.parseLong(xpathStringId), false);
		if (xp.getTextNode() != null)
			xp = xp.getTextNode();
		if (xp.isAttributeNode() || xp.isTextNode()) {
			List<Object[]> elements = xp.getCountByValue(30);
			for (Object[] oa : elements) {
				String value = (String) oa[0];
				Long count = (Long) oa[1];
%>
	<tr>
		<td> <%=value%></td>
		<td> <%=count%></td>
	</tr>
<%
			}
		}
	} catch (Exception e) {
		log.error("Problem", e);
	}
%>
</tbody>
</table>
</div>
		 

