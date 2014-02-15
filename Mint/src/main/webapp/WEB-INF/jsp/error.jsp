<%@ page import="gr.ntua.ivml.mint.db.DB" %>
<%@ page isErrorPage="true"%>
<%@ include file="_include.jsp"%>
<%
 // on error maybe the session is screwed up, better get a new one
 DB.closeSession();
 DB.getSession(); 
%>   

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
<body>

 <table width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
        <tr> 
          <td> 
            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">

              <tr> 
                <td width="90%" height="1"></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr> 
          <td height="400" valign="top"><table width="100%" border="0" cellspacing="2" cellpadding="4" align="center">
   
    <tr> 
          <td height=10 class="digital"> 
          <%String uri = (String) request.getAttribute("javax.servlet.error.request_uri");

          if (uri == null) {
           uri = request.getRequestURI(); 
          }

          out.println("Error accessing " + uri + "<BR><BR>"); 
          %>
          </td>
        </tr>
  <tr> 
    <td class="tstyle">Sorry, an error occurred processing your 
      request. Please go back and try again. <BR>Error Details:<%=exception.getMessage()%>
    </td>
  </tr>
</table></td>
        </tr>
      </table>

    
<%@ include file="footer.jsp" %>  





