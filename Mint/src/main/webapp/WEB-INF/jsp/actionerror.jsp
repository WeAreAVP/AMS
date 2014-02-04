<%@ page isErrorPage="true"%>
   
<%@ include file="_include.jsp"%>

<%@page pageEncoding="UTF-8"%>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="images/browser_icon.ico" rel="shortcut icon" />
<link rel="stylesheet" type="text/css" media="screen"
	href="css/screen.css">
<title><%=Config.get("mint.title")%></title>

<script type="text/javascript" src="js/jquery/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.tools.min.js"></script> 

<script type="text/javascript" src="js/athform.js"></script>

<%@ include file="../custom/jsp/ga.html" %>
</head>
<body class="yui-skin-sam">

  <h3>Error Message</h3>
    <s:actionerror/>
    <p>
      <s:if test="exception!=null"><s:property value="%{exception.message}"/></s:if>
    </p>
    <hr/>
    </body>
</html>






