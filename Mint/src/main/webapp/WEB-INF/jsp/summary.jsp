<%@ include file="top.jsp" %>  
<%
response.setHeader("Cache-Control","no-store");//HTTP 1.1
response.setHeader("Pragma","no-cache"); //HTTP 1.0
response.setDateHeader ("Expires", -1);
%>

<!-- Combo-handled YUI JS files: -->   
<script src="js/mapping/lib/yui/yahoo/yahoo-min.js"></script>
<script src="js/mapping/lib/yui/event/event-min.js"></script>
<script src="js/mapping/lib/yui/dom/dom-min.js"></script>
<script src="js/mapping/lib/yui/treeview/treeview-min.js"></script>
<script src="js/mapping/lib/yui/element/element-min.js"></script>
<script src="js/mapping/lib/yui/dragdrop/dragdrop-min.js"></script>
<script src="js/mapping/lib/yui/resize/resize-min.js"></script>
<script src="js/mapping/lib/yui/animation/animation-min.js"></script>
<script src="js/mapping/lib/yui/button/button-min.js"></script>
<script src="js/mapping/lib/yui/container/container-min.js"></script> 
<script src="js/mapping/lib/yui/json/json-min.js"></script> 
<script src="js/mapping/lib/yui/connection/connection-min.js"></script>
<script src="js/mapping/lib/yui/yahoo-dom-event/yahoo-dom-event.js"></script>
<script src="js/mapping/lib/yui/tabview/tabview-min.js"></script> 
<script type="text/javascript" src="js/mapping/lib/yui/datasource/datasource-min.js"></script>
<script type="text/javascript" src="js/mapping/lib/yui/datatable/datatable-min.js"></script>

<script type="text/javascript" src="js/esejs/results.js"></script>
<script type="text/javascript" src="js/esejs/js_utilities.js"></script>	

    
 
<!-- Combo-handled YUI CSS files: --> 
<link rel="stylesheet" type="text/css" href="js/mapping/lib/yui/container/assets/skins/sam/container2.css" />
<link rel="stylesheet" type="text/css" href="js/mapping/lib/yui/tabview/assets/skins/sam/tabview.css" />
<link rel="stylesheet" type="text/css" href="css/mapping/SyntaxHighlighter.css" />
<link rel="stylesheet" type="text/css" href="js/mapping/lib/yui/button/assets/skins/sam/button.css" />
<link rel="stylesheet" type="text/css" href="js/mapping/lib/yui/treeview/assets/skins/sam/treeview.css" />
<link rel="stylesheet" type="text/css" href="js/mapping/lib/yui/datatable/assets/skins/sam/datatable.css" />
<link rel="stylesheet" type="text/css" href="css/stylish-select.css" />

<link rel="stylesheet" type="text/css" href="css/thesaurus.css" />

<link rel="stylesheet" type="text/css" href="css/jquery/jquery.ui.selectmenu.css" />
<link type="text/css" href="custom/css/jquery/south-street/jquery-ui-1.8.13.custom.css" rel="Stylesheet" />	

  
<link rel="stylesheet" type="text/css" href="css/esecss/core.min.css"/> 
<link rel="stylesheet" type="text/css" href="css/esecss/fulldoc.min.css"/> 
  
  
<script type="text/javascript" src="js/htmlEscape.js"></script>
<script type="text/javascript" src="js/mapping/lib/shCore.js"></script>
<script type="text/javascript" src="js/mapping/lib/shBrushXml.js"></script>
<script type="text/javascript" src="js/itemRequest.js"></script>
<script type="text/javascript" src="js/importRequest.js"></script>
<script type="text/javascript" src="js/itemLevelLabelRequest.js"></script>
<script type="text/javascript" src="js/thesaurusLevelLabelRequest.js"></script>
<script type="text/javascript" src="js/mappingDefinition.js"></script>
<script type="text/javascript" src="js/transformRequest.js"></script>
<script type="text/javascript" src="js/xmlPreviewRequest.js"></script>
<script type="text/javascript" src="js/previewReport.js"></script>
<script type="text/javascript" src="js/publication.js"></script>


<script type="text/javascript" src="js/jquery/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/jquery/jquery.ui.selectmenu.js"></script>
<script src="js/jquery.stylish-select.js" type="text/javascript"></script>
<script src="js/fileuploader.js" type="text/javascript"></script>
 
	      
<link rel="stylesheet" type="text/css" href="css/fileuploader.css"/>
	      
			    <script>        
			        function createUploader(){            
			            var uploader = new qq.FileUploader({
			                element: document.getElementById('uploadFile'),
			                action: 'AjaxFileReader.action',
			                debug: true
			            });           
			        }
			        
			          
			    </script> 
		    

<%int count=1;

if( user.hasRight(User.SUPER_USER)){
	  count++;
}

if( user.hasRight(User.ADMIN))
	  count++;

if( user.hasRight(User.MODIFY_DATA)){
	  count++;
}
	  count++;
%>


<script type="text/javascript">

$("li a#summarymenu").addClass("selected");

var orgItemStart=new Object(); 

YAHOO.namespace("example.container");


YAHOO.example.container.wait= new YAHOO.widget.Panel("wait",  
                                                    { width: "300px",
                                                      fixedcenter: true, 
                                                      close: false, 
                                                      draggable: false, 
                                                      zindex:4,
                                                      modal: true,
                                                      visible: false
                                                    } 
                                                );
    
YAHOO.example.container.wait.setHeader("Deleting, please wait...");
YAHOO.example.container.wait.setBody("<img src=\"images/rel_interstitial_loading.gif\"/>");

function Check(checkname,formname)
{
var setTo=false;
if(document.getElementById(checkname).checked==true){setTo=true;}
var c = document.forms[formname].getElementsByTagName('input');
    for (var i = 0; i < c.length; i++) {
        if (c[i].type == 'checkbox') {
            c[i].checked = setTo;
        }
    }
}

function getChecked(formname)
{
var checks=new Array();
var counter=0;
var c = document.forms[formname].getElementsByTagName('input');
    for (var i = 0; i < c.length; i++) {
        if (c[i].type == 'checkbox' && c[i].name=='uploadCheck') {
            if(c[i].checked){ checks[counter++]=c[i].value;}
        }
    }
   if(counter>0){
    return checks;
    }else return "";
}

function getCheckedLocks(formname)
{
var checks=new Array();
var counter=0;
var c = document.forms[formname].getElementsByTagName('input');
    for (var i = 0; i < c.length; i++) {
        if (c[i].type == 'checkbox' && c[i].name=='lockCheck') {
            if(c[i].checked){ checks[counter++]=c[i].value;}
        }
    }
   if(counter>0){
    return checks;
    }else return "";
}


function show(newstring)
{ 
  if($('#togglel').text()=='See more'){$('#ellipsis').hide();$('#restd').slideDown('slow');$('#togglel').text('See less');$('#togglel').addClass('less');$('#togglel').removeClass('more');return;}
  if($('#togglel').text()=='See less'){$('#restd').slideUp('slow');$('#ellipsis').css('display','inline');$('#togglel').addClass('more');$('#togglel').removeClass('less');$('#togglel').text('See more');return;}
}

</script>
				
<style type="text/css">

.tdLabel {
color:#333333;
width:90px;
}


</style>
   
<h1>
<p>Overview</p>
</h1>
<div id="panel_container" class="yui-skin-sam">
	<script>
		var container = YAHOO.util.Dom.get("panel_container");
		YAHOO.example.container.wait.render(container);
		itemLevelLabelPanel.render(container);
		thesaurusLevelLabelPanel.render(container);
		tooltipPanel.render(container);
		mappingDefinitionPanel.render(container);	
		transformPanel.render(container);
		xmlPreviewPanel.render(container);
		publicationPanel.render(container);
		reportPanel.render(container);
		reportItem.render(container);
	</script>
</div>

<div id="help">
<p>An overview of all the imports and items per organization and per uploader:
     </p>
     </div>
<div id="locksPanel">
<script>ajaxLockSummary("","noaction");</script>
</div>
<div id="waitpanel"></div>
<table width="100%" height="400">
<tr><td width="420" valign="top" align="center">

<input type="hidden" name="closedDivs" />


    
     <%int h=0; %>
     <div id="importsPanel"> 
		<%String orgId=(String)request.getParameter("orgId"); %>
		<%if(orgId!=null){ %>
		<script>ajaxImportsPanel(0, 5, -1,<%=orgId%>)</script>
		<%} else if(orgId==null && user.getOrganization()!=null ){ 
		       //if user is uploader
		        if(user.getOrganization().getUploaders().contains(user)){
		       %>
					<script>ajaxImportsPanel(0, 5, <%=user.getDbID()%>,<%=user.getOrganization().getDbID()%>)</script>
		        <%}else{%>
		           <script>ajaxImportsPanel(0, 5, -1,<%=user.getOrganization().getDbID()%>)</script>
		        <%} %>
		<%}else if(orgId==null && user.getOrganization()==null  && user.getMintRole().equals("superuser")){ 
	   %>
		<script>ajaxImportsPanel(0, 5, -1,1)</script>
		<%}%>
	 </div> 
    
    
    
	   

</td>
<td valign="top" align="center">

  <div id="itemPanel" style="display:none"> 
  </div>
                
</td>
</tr>
</table>

<%@ include file="footer.jsp" %>  
