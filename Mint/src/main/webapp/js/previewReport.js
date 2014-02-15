var reportPanel = new YAHOO.widget.Panel("reportpreview",
	{ width: "800px",
	  height: "500px",
	  xy:[200,20],
	  close: true,
	  draggable: true,
	  zindex: 4,
	  modal: true,
	  visible: false
	}
);



var reportItem = new YAHOO.widget.Panel("reportItem",
		{ width:"800px", 
		  height:"520px",
		  fixedcenter:true, 
		  close:true, 
		  draggable:true, 
		  zindex:10,
	      underlay: "shadow",
		  modal:true,
	      visible:false
	} 
	);

reportPanel.setHeader("Report preview");
reportPanel.setBody("<center>Loading...<br/><img src=\"images/rel_interstitial_loading.gif\"/></center>");

reportItem.setHeader("Preview item with errors");
reportItem.setBody("<center>Loading...<br/><img src=\"images/rel_interstitial_loading.gif\"/></center>");


function ajaxReportPreview(orgId)
{   reportPanel.setHeader("Report preview");
    reportPanel.setBody("<center>Loading...<br/><img src=\"images/rel_interstitial_loading.gif\"/></center>");
    
    reportPanel.show();
    YAHOO.util.Connect.asyncRequest('POST', 'PreviewReport',
        {
            success: function(o) {
                reportPanel.setBody(o.responseText);
                dp.SyntaxHighlighter.HighlightAll('code');
            },
            
            failure: function(o) {
                alert("preview report failed");
            }
        }, "orgId=" +orgId);
}


function ajaxTransReportPreview(transId)
{   reportPanel.setHeader("Report preview");
    reportPanel.setBody("<center>Loading...<br/><img src=\"images/rel_interstitial_loading.gif\"/></center>");
    
    reportPanel.show();
    YAHOO.util.Connect.asyncRequest('POST', 'PreviewReport',
        {
            success: function(o) {
                reportPanel.setBody(o.responseText);
                dp.SyntaxHighlighter.HighlightAll('code');
            },
            
            failure: function(o) {
                alert("preview report failed");
            }
        }, "transId=" +transId);
}

function ajaxErrorPreview(transformNodeId,transformId,errSrc)
{   ajaxPublishItemError(transformNodeId);
	}
