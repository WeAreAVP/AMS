


<body class="yui-skin-sam">

<%@ include file="../custom/jsp/logo.html" %>
<div id="containermenu">
<div id="main-menu">
<ul>
<% if( user == null ) {  %>

<li><a href="Login_input.action"  id="loginmenu">Login</a></li>
<!--<li><a href="Register_input.action"  id="registermenu">Register</a></li>-->
<%}%>
<% if( user != null ) {  %>
<!-- <li><a href="Home" id="homemenu">Home</a></li>
<li><a href="Profile"  id="profilemenu">My Profile</a></li>-->
<% if( user.hasRight(User.SUPER_USER)) {  %>
<li><a href="OutputXSD_input.action"  id="xsdmenu"><span>Output XSD</span></a></li>
<%} %>
<% if( user.hasRight(User.SUPER_USER)) {  %>
<li><a href="Management_input.action"  id="managementmenu"><span>Administration</span></a></li>
<%} %>
<% if( user.hasRight(User.ADMIN) || user.hasRight(User.MODIFY_DATA) || user.hasRight(User.PUBLISH) ) {  %>
<li><a href="Import_input.action"  id="importmenu"><span>Import</span></a></li>
<%}if( user.hasRight(User.ADMIN) || user.hasRight(User.VIEW_DATA) || user.hasRight(User.PUBLISH) || user.hasRight(User.MODIFY_DATA)) {  %> 
<li><a href="ImportSummary"  id="summarymenu"><span>Overview</span></a></li>

<%} %> 

<% if(!Config.has("hasDataReports") || Config.getBoolean("hasDataReports")) { %>
<li><a href="ReportSummary"  id="reportmenu"><span>Data Report</span></a></li>
<li><a href="http://amsqa.avpreserve.com"  id="backtoamsmenu"><span>Back to AMS</span></a></li>
<%} else {%>
<li></li> 
<%} %>
<% if( user.hasRight(User.SUPER_USER)) {  %>
<li><a href="Logout.action"><span>Logout</span></a></li>
<%}%>

<%}%>
</ul>
</div>
</div>


<div id="maincontainer">