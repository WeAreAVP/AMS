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
<title><%= Config.get("mint.title") %> Value Mapping Tool</title>
<script type="text/javascript" src="js/jquery/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/jquery/jquery.layout.js"></script>
<script type="text/javascript" src="js/jquery/jstree/jquery.jstree.js"></script>
<script type="text/javascript" src="js/esejs/results.js"></script>
<script type="text/javascript" src="js/esejs/js_utilities.js"></script>

<script type="text/javascript" src="js/valueMapping/core.js"></script>
<script type="text/javascript" src="js/valueMapping/schemaTree.js"></script>
<script type="text/javascript" src="js/valueMapping/valueMappings.js"></script>

<link rel="stylesheet" type="text/css" href="css/jquery/jquery.layout.default.css" />
<link rel="stylesheet" type="text/css" href="css/stylish-select.css" />
<link rel="stylesheet" type="text/css" href="css/esecss/results.css"/>
<link rel="stylesheet" type="text/css" href="css/esecss/layout-common.css"/>

<link rel="stylesheet" type="text/css" href="css/valueMapping/valueMapping.css"/>

<script>
    $(document).ready(function () {
        _editor = new ValueMapping("editor");
    });
</script>

</head>
<body>

<div id="editor" class="editor">
</div>

<%@ include file="footer.jsp"%>
