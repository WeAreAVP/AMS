<%@page import="java.util.HashMap"%>
<%@page import="net.sf.json.*"%>
<%@page import="gr.ntua.ivml.mint.mapping.*"%>
<%@page import="gr.ntua.ivml.mint.db.*"%>
<%@page import="gr.ntua.ivml.mint.persistent.*"%>
<%@ page import="org.apache.log4j.Logger" %>

<%@ page import="gr.ntua.ivml.mint.db.DB" %>

<%! public final Logger log = Logger.getLogger(this.getClass());%>

<!-- 
<link rel="stylesheet" type="text/css" href="js/mapping/lib/yui/fonts/fonts-min.css" />
<link rel="stylesheet" type="text/css" href="js/mapping/lib/yui/button/assets/skins/sam/button.css" />
<link rel="stylesheet" type="text/css" href="js/mapping/lib/yui/treeview/assets/skins/sam/treeview.css" />
<link rel="stylesheet" type="text/css" href="js/mapping/lib/yui/container/assets/skins/sam/container.css" />
 -->
<script type="text/javascript" src="js/mapping/lib/yui/yahoo-dom-event/yahoo-dom-event.js"></script>

<script type="text/javascript" src="js/mapping/lib/yui/animation/animation-min.js"></script>
<script type="text/javascript" src="js/mapping/lib/yui/element/element-min.js"></script>
<script type="text/javascript" src="js/mapping/lib/yui/button/button-min.js"></script>
<script type="text/javascript" src="js/mapping/lib/yui/dragdrop/dragdrop-min.js"></script>
<script type="text/javascript" src="js/mapping/lib/yui/container/container-min.js"></script>
<script type="text/javascript" src="js/mapping/lib/yui/json/json-min.js"></script> 
<script type="text/javascript" src="js/mapping/lib/yui/resize/resize-min.js"></script>
<script type="text/javascript" src="js/mapping/lib/yui/layout/layout-min.js"></script>
<script type="text/javascript" src="js/mapping/lib/yui/treeview/treeview-min.js"></script>

<script type="text/javascript" src="js/mapping/lib/yui/connection/connection-min.js"></script>
<script type="text/javascript" src="js/mapping/lib/yui/yahoo-dom-event/yahoo-dom-event.js"></script>

<script type="text/javascript" src="js/statistics/ajax.js"></script>


<div style="width: 100%; height: 100%; background: #dddddd;">
<%

String uploadId = request.getParameter("uploadId");
boolean hide=false;
if(uploadId == null) {
%>
<h1>Error: Missing uploadId parameter</h1>
<% 
} else {
	
%>

<div id="thesaurus_source_tree"> 
<script type="text/javascript">

</script>
</div>
<input type="hidden" name="thesaurusUploadId" value="<%=uploadId%>" />
<div id="content">
		<div id="thesauri_header"><h2 style="margin-top: 0px; margin-bottom:3px; padding-top: 0px;">Thesauri list</h2>
		<span style="margin-left:3px;">Use the list below to select a thesaurus. If there is no thesaurus yet defined use the panel on the right to create a new one.</span>
		</div>
		<div id="thesaurus_filter"></div>
		<div id="thesaurus_info_panel">
			<div id="thesaurus_info">
				<h2>No thesaurus selected</h2>
				Description: -<br />
				Contact email: -<br />
				URL: -<br />
				<br />
			</div>
			<div id="thesaurus_info_buttons">
			</div>
		</div>
		<div id="data_form">
		        <div>
		        	<div style="width:100%;">
		        		<h2 id="current_thesaurus_label" style="margin-top: 0px; margin-bottom: 0px; padding-top: 10px;">No thesaurus selected</h2>
		        		<span style="margin-left:10px;">Drag and drop elements that use this thesaurus here.</span>
		        	</div>
		        	<div id="thesaurus_level_xpath_list">
		        		<ul id="active_labels" class="labels"></ul>
					</div>
					<div id="addresult" style="clear:both;"></div>
					<div style="width:100%;text-align: center;"><a href="#" onclick="javascipt:applyToCurrentMapping();">Load existing assignments of this thesaurus to current mapping.</a></div>
					<!--  Hidden div, used to load list -->
					<div id="hidden_list" style="visibility: hidden;">
					</div>
		        </div>
		</div>
</div>


<div id="addformdialog" class="yui-pe-content">
	<div class="hd">Please enter thesaurus' information</div>
	
	<div class="bd">
		<form method="POST" id="thesaurus_form" action="ThesaurusAjax.action" enctype="multipart/form-data">
			<input type="hidden" name="action" value="save" />
			<input type="hidden" name="uploadId" value="<%=uploadId %>" />
			<table border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
				<tr>
					<td>Title:</td>
					<td><input type="text" name="title" id="title" class="thesaurusinput" /></td>
				</tr>
				<tr>
					<td>Description:</td>
					<td><input type="text" name="description" id="description" class="thesaurusinput" /></td>
				</tr>
				<tr>
					<td>Contact email:</td>
					<td><input type="text" name="contact" id="contact" class="thesaurusinput"/></td>
				</tr>
				<tr>
					<td>URL:</td>
					<td><input type="text" name="url" id="url" class="thesaurusinput"/></td>
				</tr>
				<tr>
					<td>File:</td>
					<td><input type="file" name="uploadFile" id="uploadFile" class="thesaurusinput" /></td>
				</tr>
			</table>
		</form>
	</div>
</div>

<div id="editformdialog" class="yui-pe-content">
	<div class="hd">Please edit thesaurus' information</div>
	
	<div class="bd">
		<form method="POST" id="thesaurus_edit_form" action="ThesaurusAjax.action">
			<input type="hidden" name="action" value="edit" />
			<input type="hidden" name="uploadId" value="<%=uploadId %>" />
			<input type="hidden" name="thesaurusId" id="e_thesaurusId" value="-1" />
			<table border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
				<tr>
					<td>Title:</td>
					<td><input type="text" name="title" id="e_title" class="thesaurusinput"/></td>
				</tr>
				<tr>
					<td>Description:</td>
					<td><input type="text" name="description" id="e_description" class="thesaurusinput"/></td>
				</tr>
				<tr>
					<td>Contact email:</td>
					<td><input type="text" name="contact" id="e_contact" class="thesaurusinput"/></td>
				</tr>
				<tr>
					<td>URL:</td>
					<td><input type="text" name="url" id="e_url" class="thesaurusinput"/></td>
				</tr>
			</table>
		</form>
	</div>
</div>

<%
}
%>
</div>
