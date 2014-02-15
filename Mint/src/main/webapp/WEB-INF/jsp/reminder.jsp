<%@ include file="top.jsp"%>


<h1>
<p>Password Reminder</p></h1>
<div id="help">Please specify your username. A new password will be sent to you via email briefly.
</div>

<s:form name="reminder" action="Reminder" cssClass="athform" theme="mytheme">
	<fieldset>
	<ol>
		<li><s:textfield name="username" label="Username" required="true" size="50"/></li>
	</ol>
	<p align="left">
	
	<a class="button" href="#" onclick="this.blur();reminder.submit()"><span>Submit</span></a>  
	</p>



	<s:if test="hasActionErrors()">
		<s:iterator value="actionErrors">
			<span class="errorMessage"><s:property escape="false" /> </span>
		</s:iterator>
	</s:if></fieldset>

</s:form>

<p style="margin-left:10px">If you have any questions about your account please contact <a href='mailto:<%= Config.get("mail.admin") %>'><%= Config.get("mail.admin") %></a>.</p>
<%@ include file="footer.jsp" %>