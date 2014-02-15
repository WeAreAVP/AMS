<%@ include file="top.jsp"%>
<script type="text/javascript">
$("li a#registermenu").addClass("selected");
</script>
<script type="text/javascript" language="javascript" src="js/pwd_meter.js"></script>
<h1>
<p>Register</p>
</h1>
<s:form name="regform" action="Register" cssClass="athform" theme="mytheme" acceptcharset="UTF-8">
	<fieldset>
	<ol>
		<li><s:textfield name="username" label="Username" required="true" />
		</li>
		<li><table border="0" cellpadding="0" cellspacing="0"><tr><td><s:password name="password" label="Password" required="true" onkeyup="chkPass(this.value);" /></td><td><div id="scorebar" style="float: left">No password</div></td></tr></table>
		</li>
		<li><s:password name="passwordconf" label="Password Confirmation"
			required="true" /></li>
		<li><s:textfield name="firstName" label="First Name"
			required="true" /></li>
		<li><s:textfield name="lastName" label="Last Name"
			required="true" /></li>
		<li><s:textfield name="email" label="Email" required="true" /></li>
		<li><s:textfield name="tel" label="Contact phone num" /></li>
		<li><s:textfield name="jobrole" label="Job role"/>
		</li>
		<%
		String defaultOrg = Config.get("useDefaultOrganization");
		if(defaultOrg != null && defaultOrg.length() > 0) {
		%>
		<li><s:checkbox name="joinDefault" id="joinDefault" value="true" disabled="false"
			onclick='$("#Register_orgsel").attr( "disabled", $("#joinDefault").attr("checked")?true:false);' />Join default organization for test purposes</li>
		<li><s:select label="or Select Organization" name="orgsel" disabled="true"
			headerKey="0" headerValue="-- Please Select --" listKey="dbID"
			listValue="name+', '+country" list="orgs" /><br/>
			If you can't find your organisation in the list, leave blank and press submit. You can then create an organisation in the administration tab. If you select an organisation from the list, an email will be sent to its admin to assign you access rights
			</li>
		<%
		} else {
		%>
		<li><s:select id="orgsel" label="Organization" name="orgsel" disabled="false"
			headerKey="0" headerValue="-- Please Select --" listKey="dbID"
			listValue="name+', '+country" list="orgs" /><br/>
If you can't find your organisation in the list, leave blank and press submit. You can then create an organisation in the administration tab. If you select an organisation from the list, an email will be sent to its admin to assign you access rights
			</li>
		<%
		}
		%>
	</ol>

	<p align="left"><a class="button" href="#" onclick="this.blur();document.regform.submit();"><span>Submit</span></a>  
				<a class="button" href="#" onclick="this.blur();document.regform.reset();"><span>Reset</span></a>  
			</p>

	<s:if test="hasActionErrors()">
		<s:iterator value="actionErrors">
			<span class="errorMessage"><s:property escape="false" /> </span>
		</s:iterator>
	</s:if></fieldset>
</s:form>

<%@ include file="footer.jsp"%>
