<%@ include file="top.jsp"%>


<h1>
<p>Password Reminder</p>
</h1>
<div id="help">
Thank you for entering your details. You will promptly receive an email message with a new password to the email address you specified upon registration.<br /><br />

Upon receiving your new password, you'll be able to login from the <a href="Home.action">login</a> page.

If you have any questions about your account please contact <a href='mailto:<%= Config.get("mail.admin") %>'><%= Config.get("mail.admin") %></a>.
</div>
<%@ include file="footer.jsp"%>
