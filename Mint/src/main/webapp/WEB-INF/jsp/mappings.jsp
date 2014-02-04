<%@ include file="top.jsp"%>
<%int count=3;
if( user.hasRight(User.MODIFY_DATA))
	  count++;
if( user.hasRight(User.ADMIN))
count++;
%>

<script type="text/javascript">
ddtabmenu.definemenu("menu", <%=count%>) 


</script>

<style type="text/css">
.tdLabel {
	color: #333333;
	width: 90px;
}
</style>


<h1>
<p>Mappings</p>
</h1>
<div style="width:1200px;">

</div>

<%@ include file="footer.jsp"%>
