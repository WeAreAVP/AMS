<%@ include file="top.jsp"%>

<script type="text/javascript">
$("li a#loginmenu").addClass("selected");

</script>
<h1>
<p class="plaisio"><%=Config.get("mint.title")%> Ingestion Server</p>
</h1>

<table width="500">
  
 
  <tr>
    <td>
<h2>
Login
</h2>

<s:form name="login" action="Login" cssClass="athform" theme="mytheme" style="width:350px;">
	<fieldset>
	<ol>
		<li><s:textfield name="username" label="Username" required="true" onkeypress="return submitenter(this,event)"/>
		<s:fielderror>
			<s:param value="%{username}" />
		</s:fielderror></li>
		<li><s:password name="password" label="Password" required="true" onkeypress="return submitenter(this,event)"/>
		<s:fielderror>
			<s:param value="%{password}" />
		</s:fielderror></li>
	</ol>
	<p align="left">
	
	<a class="button" href="#" onclick="this.blur();login.submit()"><span>Submit</span></a>  
	</p>



	<s:if test="hasActionErrors()">
		<s:iterator value="actionErrors">
			<span class="errorMessage"><s:property escape="false" /> </span>
		</s:iterator>
	</s:if></fieldset>
</s:form>

</td>
</tr>
</table>
<div style="margin-left:10px;"><a href="Reminder.action">Forgot your password?</a></div>
<%@ include file="footer.jsp"%>
