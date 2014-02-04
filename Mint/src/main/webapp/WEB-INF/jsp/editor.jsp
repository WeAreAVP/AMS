<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<%@ include file="_include.jsp"%>
<%@ page language="java" errorPage="error.jsp"%>
<%@page pageEncoding="UTF-8"%>
<%@page import="gr.ntua.ivml.mint.xml.*"%>
<%@page import="javax.xml.parsers.*"%>
<%@page import="org.xml.sax.*"%>
<%@page import="java.io.File"%>


<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="images/browser_icon.ico" rel="shortcut icon" />
<link rel="stylesheet" type="text/css" media="screen" href="css/screen.css"/>
<title><%= Config.get("mint.title") %> Mapping Tool</title>
<script type="text/javascript" src="js/ddtabmenu.js"></script>

<script type="text/javascript" src="js/jquery/jquery.min.js"></script>
<!-- 
<script type="text/javascript" src="js/athform.js"></script>
<script type="text/javascript" src="js/jquery.stylish-select.js"></script>
 -->

<script type="text/javascript" src="js/esejs/results.js"></script>
<script type="text/javascript" src="js/esejs/js_utilities.js"></script>

<link rel="stylesheet" type="text/css" href="css/stylish-select.css" />


<link rel="stylesheet" type="text/css" href="js/mapping/lib/yui/reset-fonts-grids/reset-fonts-grids.css" />
<link rel="stylesheet" type="text/css" href="js/mapping/lib/yui/resize/assets/skins/sam/resize.css" />
<link rel="stylesheet" type="text/css" href="js/mapping/lib/yui/layout/assets/skins/sam/layout.css" />
<link rel="stylesheet" type="text/css" href="js/mapping/lib/yui/button/assets/skins/sam/button.css" />
<link rel="stylesheet" type="text/css" href="js/mapping/lib/yui/container/assets/skins/sam/container-skin.css" />
<link rel="stylesheet" type="text/css" href="js/mapping/lib/yui/container/assets/skins/sam/container.css" />
<link rel="stylesheet" type="text/css" href="js/mapping/lib/yui/assets/skins/sam/container.css" />
<link rel="stylesheet" type="text/css" href="js/mapping/lib/yui/container/assets/skins/sam/container2.css">
<link rel="stylesheet" type="text/css" href="js/mapping/lib/yui/tabview/assets/skins/sam/tabview.css">
<link rel="stylesheet" type="text/css" href="js/mapping/lib/yui/treeview/assets/skins/sam/treeview.css" />
<link rel="stylesheet" type="text/css" href="js/mapping/lib/yui/datatable/assets/skins/sam/datatable.css" />

<script type="text/javascript" src="js/mapping/lib/yui/yahoo/yahoo-min.js"></script>
<script type="text/javascript" src="js/mapping/lib/yui/event/event-min.js"></script>
<script type="text/javascript" src="js/mapping/lib/yui/dom/dom-min.js"></script>
<script type="text/javascript" src="js/mapping/lib/yui/treeview/treeview-min.js"></script>
<script type="text/javascript" src="js/mapping/lib/yui/element/element-min.js"></script>
<script type="text/javascript" src="js/mapping/lib/yui/dragdrop/dragdrop-min.js"></script>
<script type="text/javascript" src="js/mapping/lib/yui/resize/resize-min.js"></script>
<script type="text/javascript" src="js/mapping/lib/yui/animation/animation-min.js"></script>
<script type="text/javascript" src="js/mapping/lib/yui/layout/layout-min.js"></script>
<script type="text/javascript" src="js/mapping/lib/yui/button/button-min.js"></script>
<script type="text/javascript" src="js/mapping/lib/yui/container/container-min.js"></script>
<script type="text/javascript" src="js/mapping/lib/yui/connection/connection-min.js"></script>
<script type="text/javascript" src="js/mapping/lib/yui/yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript" src="js/mapping/lib/yui/json/json-min.js"></script>
<script type="text/javascript" src="js/mapping/lib/yui/datasource/datasource-min.js"></script>
<script type="text/javascript" src="js/mapping/lib/yui/datatable/datatable-min.js"></script>
<script type="text/javascript" src="js/mapping/lib/yui/tabview/tabview-min.js"></script> 


<script type="text/javascript" src="js/htmlEscape.js"></script>
<script type="text/javascript" src="js/inPlaceEditing.js"></script>
<script type="text/javascript" src="js/xmlPreviewRequest.js"></script>
<script type="text/javascript" src="js/valueListBrowser.js"></script>
<script type="text/javascript" src="js/mapping/DDSend.js"></script>
<script type="text/javascript" src="js/mapping/toolInit.js"></script>
<script type="text/javascript" src="js/mapping/toolelements.js"></script>
<script type="text/javascript" src="js/mapping/tool.js"></script>
<script type="text/javascript" src="js/mapping/conditioneditor.js"></script>
<script type="text/javascript" src="js/mapping/valueMapping.js"></script>
<script type="text/javascript" src="js/mapping/mappingAjax.js"></script>

<link rel="stylesheet" type="text/css" href="css/screen.css" />
<link type="text/css" rel="stylesheet" href="css/mapping/SyntaxHighlighter.css"></link>
<link rel="stylesheet" type="text/css" href="css/mapping/tool.css" />

<link rel="stylesheet" type="text/css" href="css/esecss/core.min.css"/> 
<link rel="stylesheet" type="text/css" href="css/esecss/fulldoc.min.css"/>   


<style type="text/css">
.tdLabel {
	color: #333333;
	width: 90px;
}



</style>

<script language="javascript" src="js/mapping/lib/shCore.js"></script>
<script language="javascript" src="js/mapping/lib/shBrushXml.js"></script>
<script language="javascript">
	dp.SyntaxHighlighter.HighlightAll('code');

	function show(newstring)
	{ 
	  if($('#togglel').text()=='See more'){$('#ellipsis').hide();$('#restd').slideDown('slow');$('#togglel').text('See less');$('#togglel').addClass('less');$('#togglel').removeClass('more');return;}
	  if($('#togglel').text()=='See less'){$('#restd').slideUp('slow');$('#ellipsis').css('display','inline');$('#togglel').addClass('more');$('#togglel').removeClass('less');$('#togglel').text('See more');return;}
	}
</script>
</head>
<body style="margin: 0;background: #3C3C3C;">
<div id="maincontainer" style="width:100%;">
 <h1>
<p align="left">Mappings: <font color=black><s:property value="mapname"/> <span style="color: #555555" id='lido09Notice'>(<s:property value="schemaname"/>)</span></font></p>
</h1>
<s:form name="mapform" action="Mapselection" cssClass="athform" theme="mytheme"
	enctype="multipart/form-data" style="width:100%;padding:0px; padding-top:0;">
	<fieldset>
	<p align="left">Define your mappings and when you are done click the 'Finished' button below to make them available to the rest of the users in your organization. <br/></br><i>*Mapping relations are automatically saved every time you edit, delete or create a new one.</i></p>
	<p align="left">	 
				<a class="button" href="#" onclick="javascript:ajaxReleaseLock(<s:property value="lockId"/>,<s:property value="mapid"/>);"><span>Finished</span></a> 
				<a class="button" href="#" onclick="javascript:ajaxXmlPreviewGeneral(<s:property value="uploadId"/>, '0', 'XML Preview', 'fixedMap', <s:property value="mapid"/>);"><span>Preview</span></a> 
				<a class="button" href="#" onclick="javascript:mappingSummary();"><span>Summary</span></a>
	</p>
	</fieldset>
</s:form>
<div id="editor_container" style="width:1200px; height: 600px;  position: relative; margin-top:-20px;" class="yui-skin-sam">

	<div id="left1">
		<div id="sourceTree" style="width:300px;overflow-x:auto;"></div>
	</div>

	<div id="right1">
		<div style="width: 220px; height: 100%; overflow-x:hidden; overflow-y: hidden">
			<div id="navigation_buttons_container"></div><br/>
			<div id="template_groups_container"></div><br/>
			<div id="default_groups_container"></div>
		</div>
	</div>

	<div id="center1">
		<div id="mappings_container" style="height: 500px; overflow-y: auto"></div>
	</div>

	<script type="text/javascript">
		function onLoad() {
	        var layout = new YAHOO.widget.Layout("editor_container", {
    	        units: [
//                { position: 'top', height: 50, body: 'top1', gutter: '3px', collapse: false, resize: false },
//                { position: 'bottom', header: 'Bottom', height: 100, resize: true, body: 'bottom1', gutter: '5px', collapse: true },
            	    { position: 'left', header: 'Source Schema', width: 200, gutter: '3px', resize: true, top:'20px', body: 'left1', collapse: false, scroll: true},
        	        { position: 'right', header: 'Target Schema', gutter: '5px', width: 220, resize: false, gutter: '3px', collapse: false, scroll: true, body: 'right1'},
                	{ position: 'center', header: 'Mappings <a class=\"toolbutton\" style=\"float: right\" href=\"#\" onclick=\"javascript:togglePrefixes();\"><span>Toggle namespaces</span></a>', body: 'center1', scroll: true, gutter: '5px' }
         	   ]
	        });

    	    layout.render();
			//    mappingEditorInit("<%= request.getAttribute("uploadId") %>", "<%= request.getAttribute("fileLoc") %>");
			init("<%= request.getAttribute("uploadId") %>", "<%= request.getAttribute("mapid") %>", "<%= request.getAttribute("fileLoc") %>");
		}
		
		YAHOO.util.Event.addListener(window, "load", onLoad);
	</script>
</div>

<%@ include file="footer.jsp"%>
