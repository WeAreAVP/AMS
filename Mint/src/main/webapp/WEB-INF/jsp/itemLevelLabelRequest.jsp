<%@page import="java.util.HashMap"%>
<%@page import="net.sf.json.*"%>
<%@page import="gr.ntua.ivml.mint.mapping.*"%>
<%@page import="gr.ntua.ivml.mint.db.*"%>
<%@page import="gr.ntua.ivml.mint.persistent.*"%>
<%@ page import="org.apache.log4j.Logger" %>

<%@ page import="gr.ntua.ivml.mint.db.DB" %>

<%! public final Logger log = Logger.getLogger(this.getClass());%>



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

<div style="width: 100%; height: 100%">
<%

String uploadId = request.getParameter("uploadId");
boolean hide=false;
if(uploadId == null) {
%><h1>Error: Missing uploadId parameter</h1><% 
} else {
	DataUpload dataUpload = DB.getDataUploadDAO().findById(Long.parseLong(uploadId), false);
	
%>

<div id="source_tree" style="float: left; width: 40%; height: 100%; overflow-y: auto; overflow-x: auto; background: #dddddd"> 
<script type="text/javascript">

</script>
</div>

<div style="width: 55%; height: 100%; float: right; overflow-y: auto; padding: 5px;">
<h2>Item Level</h2>
<div style="color: #000000; margin-top: -15px; margin-bottom: 5px; margin-left:5px;">
Define the root node of every item. Drag & drop a node from the tree to the left in the box below, to set the item level.
</div>
<div id="item_level_xpath" class="mappingTarget"  upload="<%= uploadId %>" style="word-wrap: break-word;overflow:hidden;color: #666666; padding: 3px; height:100px; font-size: 100%; border: 1px solid #CCCCCC;">
<%
	XpathHolder level_xp = dataUpload.getItemXpath();
	if(level_xp == null || level_xp.getXpathWithPrefix(true).length()==0) {
	 hide=true;

	} else {
		out.println(level_xp.getXpathWithPrefix(true));
}%>
</div>
<br/>

<hr/>
<%if(hide){ %>
<span id="setlabel" style="display:none">
<%}else{ %>
<span id="setlabel">
<%} %>
<h2>Item Label</h2>
<div style="color: #000000; margin-top: -15px; margin-bottom: 5px;margin-left:5px;">
Define the label that will be used as the Item name in the Item Overview. Drag & drop a node from the tree to the left in the box below, to set the item label.
</div>
<div id="item_label_xpath" class="mappingTarget" upload="<%= uploadId %>" style="word-wrap: break-word;overflow:hidden;color: #666666; padding: 3px; height:100px; font-size: 100%; border: 1px solid #CCCCCC">
<%
	XpathHolder label_xp = dataUpload.getItemLabelXpath();
	if(label_xp == null) {
%>

<%
	} else {
        // TODO: must be romoved. text() node should not even be here at the first place responseText = responseText.replace("/text()", ""); 
  		String removeTextThing = label_xp.getXpathWithPrefix(true);
  		removeTextThing = removeTextThing.replace("/text()", "");
		out.println(removeTextThing);
	}
%>
</div>
</span>
<br/>
<a href="javascript:resetLevelLabel(<%=uploadId %>)">Reset all</a>&nbsp;&nbsp;|&nbsp;&nbsp;</span><a href="javascript:itemLLPanelClose();">Done</a>
</div>

<%
}%>
</div>