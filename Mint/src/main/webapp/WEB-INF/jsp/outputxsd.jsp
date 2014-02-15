<%@ include file="top.jsp"%>
<%@page import="java.util.List"%>
<%@page import="gr.ntua.ivml.mint.persistent.XmlSchema"%>
<%@page import="gr.ntua.ivml.mint.persistent.Crosswalk"%>
<script type="text/javascript">
$("li a#xsdmenu").addClass("selected");
</script>

<%
	String action = (String) request.getAttribute("uaction");
	List<String> xsds = (List<String>) request.getAttribute("availablexsd");
	List<String> xsls = (List<String>) request.getAttribute("availableXSL");
	List<XmlSchema> xmlschemas = (List<XmlSchema>) request.getAttribute("xmlSchemas");
	List<Crosswalk> crosswalks = (List<Crosswalk>) request.getAttribute("crosswalks");
%>

<h1>
<p>Output XSD Configuration</p>
</h1>


    <%if(!user.hasRight(User.SUPER_USER)) {%>
   
    <span class="errorMessage">ACCESS DENIED.</span>
   
   <%} else{%>
	<s:if test="hasActionErrors()">
		<s:iterator value="actionErrors">
			<span class="errorMessage"><s:property escape="false" /> </span>
		</s:iterator>
	</s:if>
 
<!--
<div id="help">
<p>Import output XSD</p>
</div>
-->

<table>
	<tr>
		<td width="300" valign="top">
			<div style="overflow: auto; height: 150px; width: 500px; padding-left:30px;">
			<table cellspacing="0" cellpadding="0" bgcolor="#EEEEEE"
				style="background: url(images/grey2.gif) left bottom repeat-x;"
				width="100%">

				<tr>
					<td colspan="7" height="1">
					<h3>Output XSDs&nbsp;&nbsp;
					<a
					href="OutputXSD.action?uaction=import_xsd"><img
					src="images/upload2.png" width="16" height="16" border="0" alt="import xsd"
					title="edit" /> Import new output target
					</a>
					</h3>
					</td>
				</tr>

				<%
				for(int i=0; i < xmlschemas.size(); i++) {
					XmlSchema schema = (XmlSchema) xmlschemas.get(i);
			    	%>

				<!-- for each schema -->
				<tr>
					<td></td>
					<td width="20">
						<a href="javascript:confirmDelete('OutputXSD.action?uaction=delete_xsd&id=<%=schema.getDbID() %>','Are you sure you want to delete this schema?')">			
						<img src="custom/images/trash_can.png" width="20" height="20" alt="delete" title="delete" /></a>
					</td>
					<td width="20">
						<a href="javascript:confirmDelete('OutputXSD.action?uaction=reload&id=<%=schema.getDbID() %>', 'Are you sure you want to reload this schema? This might invalidate existing mappings!')">
						<img src="images/refresh.jpg" width="16" height="16" border="0" alt="reload" title="reload" /></a>
					</td>
					<td width="20">
						<a href="OutputXSD.action?uaction=show_xsd&id=<%=schema.getDbID() %>">
						<img src="images/download2.png" width="16" height="16" border="0" alt="xsd" title="xsd" /></a>
					</td>
					<td width="20">
						<a href="OutputXSD.action?uaction=show_conf&id=<%=schema.getDbID() %>">
						<img src="images/download2b.png" width="16" height="16" border="0" alt="conf" title="conf" /></a>
					</td>
					<td width="20">
						<a href="OutputXSD.action?uaction=show_template&id=<%=schema.getDbID() %>">
						<img src="images/downloadblue.png" width="16" height="16" border="0" alt="template" title="template" /></a>
					</td>
					<td style="text-align:right"><b>Name:&nbsp;&nbsp;</b></td>
					<td>
						<a href="OutputXSD.action?uaction=show_xsd&id=<%=schema.getDbID() %>"><%=schema.getName() %></a></td>
				</tr>

				<%} %>
			</table>
			</div>
		</td>
		<td width="500" align="center" rowspan="2" valign="top"><!--user details showing here if userid param was passed-->
		<%
			if(action.equalsIgnoreCase("import_xsd") || action.equalsIgnoreCase("import_crosswalk")) {
		%>
			<div>
			<table border="0"  cellpadding="0" cellspacing="0" width="372">
			<tr><td>
			
			<span class="rounded_top"><%if(action.equalsIgnoreCase("import_xsd")) {%>Import output xsd<%}
					else if(action.equalsIgnoreCase("import_crosswalk")) {%>Import crosswalk<%}
					%></span>
			
			</td></tr>
			<tr><td style="background: url(custom/images/grey.gif);" align="left">
			<s:if test="%{uaction.equals('import_xsd')}">
			<s:form name="xsd_manage" id="xsd_manage" action="OutputXSD" cssClass="athform" theme="mytheme" style="width:370px;border: solid 1px silver;margin:0;padding:0;align:left;">
				<fieldset>
				<ol>
				   	<s:if test="%{uaction.equals('import_xsd')}">
						<li><s:textfield name="xmlschema.name"  label="Name" required="true" cssStyle="width:200px;"/></li>
					</s:if><s:else>
						<li><s:textfield name="xmlschema.name"  label="Name" readonly="true" cssStyle="width:200px;"/></li>					
					</s:else>
					
					<li><s:select label="XSD" required="true" name="xmlschema.xsd" cssStyle="width:200px;"  list="availablexsd"/></li>					
				</ol>
				<p align="left">
					<a class="button" href="#" onclick="this.blur();document.xsd_manage.submit();"><span>Submit</span></a>  
					<a class="button" href="#" onclick="this.blur();document.xsd_manage.reset();"><span>Reset</span></a>  
					<input type="hidden" name="uaction" value="save_xsd"/>
					<s:if test="%{xmlschema.dbID != null}">
						<s:hidden name="xmlschema.dbID" value="%{xmlschema.dbID}"/>				
				    </s:if>
				</p>
<!--			
			    <p align="right"><a href="Management.action?uaction=edituser&id="><img
						src="images/edit.gif" width="16" height="16" border="0" alt="edit"
						title="edit" /> Edit user</a></p>
				 <p align="right"><a href="Management.action?uaction=deluser&id="><img
						src="custom/images/trash_can.png" width="20" height="20" alt="delete user"
						title="delete user" />Delete user</a></p>
-->			
				</fieldset>
			</s:form>
			</s:if>
			
			<s:if test="%{uaction.equals('import_crosswalk')}">
			<s:form name="xsl_manage" id="xsl_manage" action="OutputXSD" cssClass="athform" theme="mytheme" style="width:370px;border: solid 1px silver;margin:0;padding:0;align:left;">
				<fieldset>
				<ol>
					<li><s:select label="Input" required="true" name="sourceSchemaId" cssStyle="width:200px;" listKey="dbID" list="xmlSchemas"/></li>					
					<li><s:select label="Output" required="true" name="targetSchemaId" cssStyle="width:200px;"  listKey="dbID" list="xmlSchemas"/></li>
					<li><s:select label="XSL" required="true" name="crosswalk.xsl" cssStyle="width:200px;"  list="availableXSL"/></li>					
				</ol>
				<p align="left">
					<a class="button" href="#" onclick="this.blur();document.xsl_manage.submit();"><span>Submit</span></a>  
					<a class="button" href="#" onclick="this.blur();document.xsl_manage.reset();"><span>Reset</span></a>  
					<input type="hidden" name="uaction" value="save_crosswalk"/>
					<s:if test="%{crosswalk.dbID != null}">
						<s:hidden name="crosswalk.dbID" value="%{crosswalk.dbID}"/>				
				    </s:if>
				</p>
				</fieldset>
			</s:form>
			</s:if>
			
			</td></tr>
			</table>	
			</div>
		<% } %>
		</td>
		<td width="500">&nbsp;
		</td>
	</tr>
</table>

<s:if test="%{textdata.length() > 0}">
	<div style="width:100%; height:500px;padding:10px;"><s:textarea rows="20" cols="160" value="%{textdata}"/></div>
</s:if>			
<%} %>
<%@ include file="footer.jsp"%>