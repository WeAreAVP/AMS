<%@ taglib prefix="s" uri="/struts-tags" %>
<%@ page import="java.util.HashMap" %>
<%@ page import="java.util.Map" %>
<%@ page import="java.util.Set" %>
<%@ page import="java.util.Iterator" %>
<%@ page import="java.util.ArrayList" %>
<%@ page import="org.apache.log4j.Logger" %>

<%! public final Logger log = Logger.getLogger(this.getClass());%>

<s:if test="action.equalsIgnoreCase('validate')">
 <s:iterator value="actionErrors">
			<span class="errorMessage"><s:property escape="false" /> </span>
</s:iterator>
<s:iterator value="actionMessages">
			<span class="errorMessage"><s:property escape="false" /> </span>
</s:iterator>
</s:if>
<s:elseif test="action.equalsIgnoreCase('fetchsets')">
 <%HashMap<String,String> allsets=(HashMap)request.getAttribute("oaiAllSets");
  if(allsets!=null && allsets.size()>0){
   Set entries = allsets.entrySet();
   Iterator it = entries.iterator();
    %>
  <select name="oaiset" style="margin-left:23px;">
  
  <%while (it.hasNext()) {
	  Map.Entry entry = (Map.Entry) it.next();
	  %>
    <option value="<%=entry.getValue()%>"><%=entry.getKey()%></option>
  <%} %>

  </select>
  <%} else{%><font style="color:red;margin-left:253px;">No OAI sets found</font><%}%>
</s:elseif>
<s:elseif test="action.equalsIgnoreCase('fetchns')">
 <%ArrayList<String> allns=(ArrayList)request.getAttribute("ns");
  if(allns!=null && allns.size()>0){
    %>
   
  <select name="oainamespace" style="margin-left:23px;">
  
  <%for(String i:allns) { %>
    <option value="<%=i%>"><%=i%></option>
  <%} %>

  </select>
  <%} else{%><font style="color:red;margin-left:253px;">No OAI namespace prefixes found</font><% }%>
</s:elseif>
