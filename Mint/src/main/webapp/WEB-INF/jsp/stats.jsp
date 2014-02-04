<%@ include file="_include.jsp"%>
<%@ page language="java" errorPage="error.jsp"%>
<%@page pageEncoding="UTF-8"%>

<%@ taglib prefix="s" uri="/struts-tags" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<title><%= Config.get("mint.title") %> Statistics</title>
<!-- Combo-handled YUI CSS files: -->
    <link rel="stylesheet" type="text/css" href="js/mapping/lib/yui/reset-fonts-grids/reset-fonts-grids.css"/>
    <link rel="stylesheet" type="text/css" href="js/mapping/lib/yui/resize/assets/skins/sam/resize.css"/>
    <link rel="stylesheet" type="text/css" href="js/mapping/lib/yui/layout/assets/skins/sam/layout.css"/>
<!-- Combo-handled YUI JS files: -->
    
    <link rel="stylesheet" type="text/css" href="js/mapping/lib/yui/fonts/fonts-min.css" />
    <link rel="stylesheet" type="text/css" href="js/mapping/lib/yui/tabview/assets/skins/sam/tabview.css" />
    <link rel="stylesheet" type="text/css" href="js/mapping/lib/yui/datatable/assets/skins/sam/datatable.css" />
    <link rel="stylesheet" type="text/css" href="js/mapping/lib/yui/container/assets/skins/sam/container.css" />
    <script type="text/javascript" src="js/mapping/lib/yui/utilities/utilities.js"></script>
    <script type="text/javascript" src="js/mapping/lib/yui/container/container-min.js"></script>
    <script type="text/javascript" src="js/mapping/lib/yui/calendar/calendar-min.js"></script>
    <script type="text/javascript" src="js/mapping/lib/yui/resize/resize-min.js"></script>
    <script type="text/javascript" src="js/mapping/lib/yui/editor/simpleeditor-min.js"></script>
    <script type="text/javascript" src="js/mapping/lib/yui/layout/layout-min.js"></script>
    <script type="text/javascript" src="js/mapping/lib/yui/yahoo-dom-event/yahoo-dom-event.js"></script>
    <script type="text/javascript" src="js/mapping/lib/yui/element/element-min.js"></script>
    <script type="text/javascript" src="js/mapping/lib/yui/tabview/tabview-min.js"></script>
    <script type="text/javascript" src="js/mapping/lib/yui/datasource/datasource-min.js"></script>
    <script type="text/javascript" src="js/mapping/lib/yui/datatable/datatable-min.js"></script>
    <script type="text/javascript" src="js/mapping/lib/yui/connection/connection-min.js"></script>
    <script type="text/javascript" src="js/mapping/lib/yui/animation/animation-min.js"></script>
    <script type="text/javascript" src="js/mapping/lib/yui/dragdrop/dragdrop-min.js"></script>
    <script type="text/javascript" src="js/mapping/lib/yui/container/container-min.js"></script>
    <script type="text/javascript" src="js/statistics/ajax.js"></script>
    <script type="text/javascript" src="js/statistics/prototype.js"></script>
    <script type="text/javascript" src="js/statistics/ProtoChart.js"></script>
    <script type="text/javascript" src="js/statistics/excanvas-compressed.js"></script>
    <script type="text/javascript" src="js/statistics/excanvas.js"></script>
    <script type="text/javascript" src="js/statistics/data.js"></script>
    <script type="text/javascript" src="js/jquery/jquery.min.js"></script>
    
  	  <link rel="stylesheet" type="text/css" href="js/mapping/lib/yui/reset-fonts-grids/reset-fonts-grids.css" />
	<link rel="stylesheet" type="text/css" href="js/mapping/lib/yui/resize/assets/skins/sam/resize.css" />
	<link rel="stylesheet" type="text/css" href="js/mapping/lib/yui/layout/assets/skins/sam/layout.css" />
	<link rel="stylesheet" type="text/css" href="js/mapping/lib/yui/button/assets/skins/sam/button.css" />
	<link rel="stylesheet" type="text/css" href="js/mapping/lib/yui/container/assets/skins/sam/container-skin.css" />
	<link rel="stylesheet" type="text/css" href="js/mapping/lib/yui/container/assets/skins/sam/container.css" />
	<link rel="stylesheet" type="text/css" href="js/mapping/lib/yui/assets/skins/sam/container.css" />
	<link rel="stylesheet" type="text/css" href="js/mapping/lib/yui/treeview/assets/skins/sam/treeview.css" />
    <link rel="stylesheet" type="text/css" href="css/mapping/editor.css" />
	<link rel="stylesheet" type="text/css" href="css/statistics.css" />


	
<style type="text/css">
/*margin and padding on body element
  can introduce errors in determining
  element position and are not recommended;
  we turn them off as a foundation for YUI
  CSS treatments. */
body {
	margin:0;
	padding:0;
}
#toggle {
    text-align: center;
    padding: 1em;
}
#toggle a {
    padding: 0 5px;
    border-left: 1px solid black;
}
#tRight {
    border-left: none !important;
}
.yui-skin-sam .yui-navset .yui-nav a {
 border-top-width:1px;
 padding: 3px 6px 3px 6px;
}
.yui-nav {
  text-align: left;
}
</style>


</head>
<body class="yui-skin-sam" >
        
        <div id="pageContent" class="yui-navset">
			  <ul class="yui-nav">
			   <s:iterator value="views" >
			     <li > <a href= "view_<s:property value="xmlObjectId"/>" ><p><s:property value="name"/></p></a></li>
				</s:iterator>
   			 </ul>                    
            <div class="yui-content" id="tablesContent">
			   <s:iterator value="views" >
			   <div id="view_<s:property value="xmlObjectId"/>">
			     <div id="tab_<s:property value="xmlObjectId"/>" > </div>
			     <div id="namespaces_<s:property value="xmlObjectId"/>"> 
			     	<table id="nsTable_<s:property value="xmlObjectId"/>">
			     	   <s:iterator value="namespaces">
                    		<tr>
                    			<td><s:property value="prefix"/></td>
                    			<td><a href="<s:property value="uri"/>"> <s:property value="uri"/></a></td>
                    		</tr>
                       </s:iterator>
			     	</table>
			     </div>
			   </div>
		     </s:iterator>
           </div>


            <div id="panel">
                <div class="hd" id="headerTitle">Element Value Statistics</div>
                <div id ="rawData" class="bd" style="overflow:auto">
                    <table>
                     <tr>
                    <td><div id="matrix" style="width:550px;height:300px"> </div></td>
                    <td></td>
                    </tr>
                    </table>
                </div>
                <div class="ft"><%= Config.get("mint.title") %></div>
            </div>
       </div>
	       <script>

           // get the xmlObjectIds from the tabs
			var objIds = $("#tablesContent").children().map( function() { 
				return this.id.match( /\d+/ );
			} ).get();
	       	
 			var myTabs = new YAHOO.widget.TabView("pageContent");
			myTabs.selectTab( 0 );
            
			for( objIdsKey in objIds ) {
				var xmlObjId = objIds[objIdsKey];

				var myDataSource = new YAHOO.util.DataSource(YAHOO.util.Dom.get("nsTable_"+xmlObjId ));
	            myDataSource.responseType = YAHOO.util.DataSource.TYPE_HTMLTABLE;
	            myDataSource.responseSchema = {fields: [{key:"prefix"},{key:"namespace", formatter:YAHOO.widget.DataTable.formatLink}]};
				var namespaceColumnDefs = [{key:"prefix",label:"prefix",sortable:false},{key:"namespace",label:"namespace",sortable:false}];
	            var myDataTable = new YAHOO.widget.DataTable("namespaces_"+xmlObjId, namespaceColumnDefs, myDataSource,{caption:"Namespaces"});

	            var columnDefs = [{key:"element",label:"element",sortable:false,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC},resizeable:true},
	                              {key:"frequency",label:"count",sortable:false,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC},resizeable:true},
	                              {key:"unique",label:"distinct",sortable:false,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC},resizeable:true},
	                              {key:"length",label:"length",sortable:false,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC},resizeable:true}];

	            var dataSource= new YAHOO.util.DataSource("stats_table?xmlObjectId="+xmlObjId );
	            dataSource.responseType = YAHOO.util.DataSource.TYPE_HTMLTABLE;
	            dataSource.responseSchema = {fields: [{key:"element"},
	                             {key:"unique"},
	                             {key:"frequency"},
	                             {key:"length"},
	                             {key:"pathId" },
	                             {key:"parentPathId" }]};

	            var dataTable = new YAHOO.widget.ScrollingDataTable("tab_"+xmlObjId, columnDefs, dataSource, {height:"31em", caption:"Element counts and distinct counts"});
                
	            dataTable.subscribe("rowMouseoverEvent", dataTable.onEventHighlightRow);
	            dataTable.subscribe("rowMouseoutEvent", dataTable.onEventUnhighlightRow);
	            dataTable.subscribe("rowClickEvent", this.dataTable.onEventSelectRow);
	            dataTable.subscribe("rowDblclickEvent", 

	            function (args){
	                var record = this.getRecord( args.target );
	                
	                temp=record.getData("frequency");
	                if(temp.trim().length==0) return;
	    			document.getElementById("headerTitle").innerHTML = "Statistics for the Element " + record.getData( "element" ).replace( /&nbsp;/g, "" );
	                var callback = function( o ) {
	                    document.getElementById("matrix").innerHTML = o.responseText;
	                   	var columnDefs = [{key:"Value",label:"Value",sortable:false},{key:"Frequency",label:"Frequency",sortable:false}];
	                    		
	                   	var dataSource = new YAHOO.util.DataSource(YAHOO.util.Dom.get("ajaxTable"));
	                   	    dataSource.responseType = YAHOO.util.DataSource.TYPE_HTMLTABLE;
	                   	    dataSource.responseSchema = {fields: [{key:"Value"},{key:"Frequency"}]};

	                   	var dataTable = new YAHOO.widget.ScrollingDataTable("ajaxTableContainer", 
	                    columnDefs, dataSource,{caption:"Available value distribution for the current element.", height:"20em"});
	                 	oPanel.show();    
	                };
	                ajaxSimple( "elementStats", "pathId="+record.getData( "pathId" ), callback );
	            } );


			}	// end of loop through tabs


            oPanel = new YAHOO.widget.Panel("panel",
                    { width:"550px", height:"380px",visible:false, 
					  constraintoviewport:true, modal:true, fixedcenter:true, 
					  effect:{effect:YAHOO.widget.ContainerEffect.FADE,duration:0.25} });
	        oPanel.render();
                
 			
            
        </script>
<%@ include file="footer.jsp"%>
