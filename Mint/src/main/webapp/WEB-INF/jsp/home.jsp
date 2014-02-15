<%@ include file="top.jsp" %>  
<%@page import="java.util.List"%>
<%@page import="gr.ntua.ivml.mint.persistent.Organization"%>
<%@page import="gr.ntua.ivml.mint.persistent.DataUpload"%>
<%@page import="gr.ntua.ivml.mint.db.DB" %>

<script type="text/javascript" src="js/animatedcollapse.js"></script>


<script type="text/javascript">
//	var sitePath = window.location.host+'/mint-ams/';
//	window.location.href = sitePath+'OutputXSD_input.action';
	$("li a#homemenu").addClass("selected");
</script>

<% String sessionId = request.getSession().getId();
%>
<script> document.title = "<%= " " + Config.get("mint.title") + " Home" %>"</script>




<h1><p class="plaisio"><%=Config.get("mint.title")%> Ingestion Server</p></h1>
<div id="openBox" style="clear: both; width:590px; overflow: auto;padding:10px;">
	<p>You are currently logged in as user <a href="Profile"><%
	if(user!=null)
	{
		out.print(user.getLogin());
	}%></a>

		<% 
	  String role="";
	  if(user.hasRight(User.SUPER_USER)){
		  role="superuser";
	  }
	  else if(user.getOrganization()!=null){
	  
		  if(user.hasRight(User.ADMIN))
			 role="administrator";
		  else if((user.hasRight(User.PUBLISH)))
			  role+="annotator, publisher";
		  else if(user.hasRight(User.MODIFY_DATA))
			  role="annotator";
		  else if(user.hasRight(User.VIEW_DATA)){role="data viewer";}
		  else{role="no role";}
	  }%> 

		(role: <b class="mainclr"><%=role %></b>)
	</p>  
	<br/>

	<%@ include file="../custom/jsp/home.jsp" %>  

	<% int totalitems=0;
	int totallido=0;
	int cntries=0;
 
	List<String> countries=(List<String>)request.getAttribute("countries");
 	 
	for(int i=0;i<countries.size();i++){
	 
		  List<Organization> orgs=DB.getOrganizationDAO().findByCountry(countries.get(i)); 
		  boolean found=false;
		  for(int j=0;j<orgs.size();j++){ 
			   List<DataUpload> dus = orgs.get(j).getDataUploads();
			   int result=0;
			   int transformed=0;
			   int users=orgs.get(j).getUsers().size();
			   for( DataUpload du: dus ) {
				   if( du.getItemXpath() != null ) {
						   result += (int) du.getItemXpath().getCount();
						   if(DB.getTransformationDAO().findByUpload(du).size()>0){
							   transformed+=(int) du.getItemXpath().getCount();
						   }
				   }
			   }
				   totalitems+=result;
				   totallido+=transformed;
	            
	   }
		  if(orgs.size()>0){cntries++;}
		
	   }

	%>
	<br>
	<br>
	<h3>Server statistics</h3>
	<table>
		<tr><td></td></tr>
		<tr><td><%=DB.getUserDAO().count() %> registered users / <%=DB.getOrganizationDAO().count() %> organizations from <%=cntries%> countries.</td></tr>
		<tr><td><%=totalitems %> imported items</td></tr>
		<tr><td><%=totallido %> transformed items</td></tr>

	</table> 

	<h5 align="right"><i>ver. 2co90</i></h5>
</div>

<BR>

<div id="openBox" style="float: left; width:275px; overflow: auto;padding:10px;">
	<h3>
		User roles:</h3>
	<ul style="margin-left:10px;padding:10px;">
		<li>Administrator: This user can create/update/delete users and children organizations for the organization he is administering. He/she can also perform uploads and all available data handling functions provided by the system.</li>
		<li>Annotator: This user can upload data for his/her organization (and any children organizations) and perform all available data handling functions (view items, delete items, mappings etc) provided by the system, apart from final publishing of data.
		</li>
		<li>Annotator & Publisher: This user has all the righs of an annotator as well as rights to perform final publishing of data.
		</li> 
		<li>Data Viewer: This user only has viewing righs for his organization (and any of its children organizations).
		</li>
		<li>No role: A user that has registered for an organization but has not yet been assigned any rights.</li>
	</ul>
</div>

<div id="openBox" style="float: left; width:275px; overflow: auto;padding:10px;">
	<h3>
		Registered organizations:</h3>
	<div style="overflow: auto; height: 320px;padding:10px;">
		<s:iterator value="allOrgs">

			<p><s:property value="englishName"/> (<s:property value="country"/>)</p>
		</s:iterator>

	</div>
</div>


<BR>
<div style="clear: both; width:580px; overflow: auto;padding:10px;"></div>

<%@ include file="footer.jsp" %>  
