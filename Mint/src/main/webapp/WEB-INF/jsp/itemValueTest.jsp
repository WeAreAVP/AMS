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
<link rel="stylesheet" type="text/css" href="css/esecss/results.css"/>
<link rel="stylesheet" type="text/css" href="css/esecss/layout-common.css"/>

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
<script type="text/javascript" src="js/valueListBrowser.js"></script> 


<script type="text/javascript" src="js/htmlEscape.js"></script>
<script type="text/javascript" src="js/inPlaceEditing.js"></script>
<script type="text/javascript" src="js/xmlPreviewRequest.js"></script>
<script type="text/javascript" src="js/mapping/DDSend.js"></script>
<script type="text/javascript" src="js/mapping/toolInit.js"></script>
<script type="text/javascript" src="js/mapping/toolelements.js"></script>
<script type="text/javascript" src="js/mapping/tool.js"></script>
<script type="text/javascript" src="js/mapping/conditioneditor.js"></script>
<script type="text/javascript" src="js/mapping/mappingAjax.js"></script>

<link rel="stylesheet" type="text/css" href="css/screen.css" />
<link type="text/css" rel="stylesheet" href="css/mapping/SyntaxHighlighter.css"></link>
<link rel="stylesheet" type="text/css" href="css/mapping/tool.css" />




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
</script>
</head>
<body>
<div id="editor_container" style="width:1200px; height: 600px; position: relative;" class="yui-skin-sam">

	<div id="center1" style="height: 500px; width: 500px">
	</div>

	<script type="text/javascript">
	var browser;
		function onLoad() {
			ValueBrowser.prototype.maxItems = 3;
	        browser = new ValueBrowser("center1", 1113);
		}
		
		YAHOO.util.Event.addListener(window, "load", onLoad);
	</script>
</div>
</span>