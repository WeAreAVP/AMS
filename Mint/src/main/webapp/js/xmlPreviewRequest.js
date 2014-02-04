
var mheight=680;
if(mheight>(YAHOO.util.Dom.getViewportHeight() - 100)){mheight=YAHOO.util.Dom.getViewportHeight() - 100;}

var xmlPreviewPanel = new YAHOO.widget.Panel("xmlpreview",
	{ width: "800px",
	  height: (mheight) + 'px',
	  fixedcenter: true,
	  constraintoviewport: true,
	  close: true,
	  draggable: false,
	  zindex: 4,
	  modal: true,
	  visible: false
	}
);

xmlPreviewPanel.setHeader("XML Preview");
xmlPreviewPanel.setBody("<center>Loading...<br/><img src=\"images/rel_interstitial_loading.gif\"/></center>");

function ajaxXmlMapPreview(uploadId, nodeId)
{   ajaxXmlPreviewGeneral( uploadId, nodeId, "XML Preview with Mappings", "selectableMap",0 );
}

function ajaxXmlMapPreview(uploadId, nodeId, selMapp)
{	ajaxXmlPreviewGeneral( uploadId, nodeId, "XML Preview with Mappings", "selectableMap",selMapp );
}


function ajaxXmlTransform(selMapping)
{   
	xmlPreviewPanel.setHeader("XML Preview based on Mappings"); 
    xmlPreviewPanel.setBody("<center>Loading...<br/><img src=\"images/rel_interstitial_loading.gif\"/></center>");
    xmlPreviewPanel.show();
    YAHOO.util.Connect.asyncRequest('POST', 'XMLPreview.action',
        {
            success: function(o) {
                xmlPreviewPanel.setBody(o.responseText);
                $('#XMLPreview_selMapping').sSelect({ddMaxHeight: '300px'});
            	
                dp.SyntaxHighlighter.HighlightAll('code');
                
                columns = [{key:"Missing XPath",label:"Missing XPaths",sortable:false,width: "300px"}];
               	
               	source = new YAHOO.util.DataSource(YAHOO.util.Dom.get("missingTable"));
                   source.responseType = YAHOO.util.DataSource.TYPE_HTMLTABLE;
                   source.responseSchema = {fields: [{key:"Missing XPath"}]};
                   
                   table = new YAHOO.widget.ScrollingDataTable("missingContainer",columns, source, {caption:"Missing XPaths.",width: "790px"});   
               
                
            	columns = [{key:"Invalid XPath",label:"Invalid XPaths",sortable:false,width: "300px"}];
               	
               	source = new YAHOO.util.DataSource(YAHOO.util.Dom.get("invalidTable"));
                   source.responseType = YAHOO.util.DataSource.TYPE_HTMLTABLE;
                   source.responseSchema = {fields: [{key:"Invalid XPath"}]};
               	table = new YAHOO.widget.ScrollingDataTable("invalidContainer",columns, source, {caption:"Invalid XPaths.",width: "790px"});
                
                var tabs = new YAHOO.widget.TabView("previewTabs");
            },
            
            failure: function(o) {
                alert("preview transform for (uploadId: " + uploadId + ", nodeId: " + nodeId + ") failed");
            }
        }, "uploadId=" + uploadId + "&nodeId=" + nodeId+"&selMapping="+selMapping);
}

function ajaxXmlPreviewGeneral( uploadId, nodeId, header, scene,selectedMapping ) {
    xmlPreviewPanel.setHeader( header );

    xmlPreviewPanel.setBody("<center>Loading...<br/><img src=\"images/rel_interstitial_loading.gif\"/></center>");
    xmlPreviewPanel.show();
    YAHOO.util.Connect.asyncRequest('POST', 'XMLPreview.action',
        {
            success: function(o) {
                xmlPreviewPanel.setBody(o.responseText);
                if(scene=="XML Preview with Mappings"){
                $('#XMLPreview_selMapping').sSelect({ddMaxHeight: '300px'});}
                $('div.indiv').height(mheight-100);
                dp.SyntaxHighlighter.HighlightAll('code');
                var tabs = new YAHOO.widget.TabView("previewTabs");
            },
            
            failure: function(o) {
                alert("preview transform for (uploadId: " + uploadId + ", nodeId: " + nodeId + ") failed");
            }
        }, "uploadId=" + uploadId + "&nodeId=" + nodeId + "&scene="+scene+"&selMapping="+selectedMapping);
}


function ajaxXmlInput(uploadId, nodeId)
{
	ajaxXmlPreviewGeneral( uploadId, nodeId, "XML Preview - Input", "input",0 );
}	


function ajaxXmlTransformed(uploadId, nodeId)
{   
	 ajaxXmlPreviewGeneral( uploadId, nodeId, "XML Transformed", "fixedMap",0 );
	
}

function ajaxPublishItemError(nodeId)
{   
	 ajaxXmlPreviewGeneral( 0, nodeId, "Item Preview", "publishedError",0 );
	
}