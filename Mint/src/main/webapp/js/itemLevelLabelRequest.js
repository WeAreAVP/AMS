
/* This is needed as getElementsByClassName does not work in IE
Code/licensing: http://code.google.com/p/getelementsbyclassname/
*/
var getElementsByClassName = function (className, tag, elm){
if (document.getElementsByClassName) {
	getElementsByClassName = function (className, tag, elm) {
		elm = elm || document;
		var elements = elm.getElementsByClassName(className),
			nodeName = (tag)? new RegExp("\\b" + tag + "\\b", "i") : null,
			returnElements = [],
			current;
		for(var i=0, il=elements.length; i<il; i+=1){
			current = elements[i];
			if(!nodeName || nodeName.test(current.nodeName)) {
				returnElements.push(current);
			}
		}
		return returnElements;
	};
}
else if (document.evaluate) {
	getElementsByClassName = function (className, tag, elm) {
		tag = tag || "*";
		elm = elm || document;
		var classes = className.split(" "),
			classesToCheck = "",
			xhtmlNamespace = "http://www.w3.org/1999/xhtml",
			namespaceResolver = (document.documentElement.namespaceURI === xhtmlNamespace)? xhtmlNamespace : null,
			returnElements = [],
			elements,
			node;
		for(var j=0, jl=classes.length; j<jl; j+=1){
			classesToCheck += "[contains(concat(' ', @class, ' '), ' " + classes[j] + " ')]";
		}
		try	{
			elements = document.evaluate(".//" + tag + classesToCheck, elm, namespaceResolver, 0, null);
		}
		catch (e) {
			elements = document.evaluate(".//" + tag + classesToCheck, elm, null, 0, null);
		}
		while ((node = elements.iterateNext())) {
			returnElements.push(node);
		}
		return returnElements;
	};
}
else {
	getElementsByClassName = function (className, tag, elm) {
		tag = tag || "*";
		elm = elm || document;
		var classes = className.split(" "),
			classesToCheck = [],
			elements = (tag === "*" && elm.all)? elm.all : elm.getElementsByTagName(tag),
			current,
			returnElements = [],
			match;
		for(var k=0, kl=classes.length; k<kl; k+=1){
			classesToCheck.push(new RegExp("(^|\\s)" + classes[k] + "(\\s|$)"));
		}
		for(var l=0, ll=elements.length; l<ll; l+=1){
			current = elements[l];
			match = false;
			for(var m=0, ml=classesToCheck.length; m<ml; m+=1){
				match = classesToCheck[m].test(current.className);
				if (!match) {
					break;
				}
			}
			if (match) {
				returnElements.push(current);
			}
		}
		return returnElements;
	};
}
return getElementsByClassName(className, tag, elm);
};

var itemLevelLabelPanel = new YAHOO.widget.Panel("itemlevellabel",
	{ width: "800px",
	  height: "500px",
	  fixedcenter: true,
	  constraintoviewport: true,
	  close: true,
	  draggable: false,
	  zindex: 4,
	  modal: true,
	  visible: false
	}
);

itemLevelLabelPanel.setHeader("Define Item Level/Label XPaths");
itemLevelLabelPanel.setBody("<center>Loading...<br/><img src=\"images/rel_interstitial_loading.gif\"/></center>");
itemLevelLabelPanel.hideEvent.subscribe(itemLevelLabelPanelClose); 

tooltipPanel = new YAHOO.widget.Panel("tooltips",
		{ width:"480px", 
		  height:"440px",
		  fixedcenter:true, 
		  close:true, 
		  draggable:true, 
		  zindex:10,
          underlay: "shadow",
		  modal:true,
		  visible:false
		} 
    );
    
    tooltipPanel.setHeader("Help");
    tooltipPanel.setBody('<center><img src="js/mapping/lib/yui/carousel/assets/ajax-loader.gif" /></center>');
    


var itemLevelLabelPanelOrgId = null;
var itemLevelLabelPanelUploadId = -1;
var itemLevelLabelPanelUserId = -1;
var itemLevelLabelPanelTransformed=false;
function itemLevelLabelPanelClose() {
	
}

function itemLLPanelClose() {
	if(itemLevelLabelPanelOrgId != null && itemLevelLabelPanelOrgId != "") {
		ajaxItemPanel(0, 10, itemLevelLabelPanelOrgId,itemLevelLabelPanelUploadId,itemLevelLabelPanelUserId);
		ajaxFetchTransformStatus(itemLevelLabelPanelUploadId);
	}
	itemLevelLabelPanel.hide();
}

//for tooltip
function showTooltip(nodeId) {
	var element = YAHOO.util.Dom.get(nodeId);
	var xpath = element.getAttribute('xpath');
	
	tooltipPanel.setBody('<center>Loading help for <b>' + xpath + '</b>...<br/><img src="js/mapping/lib/yui/carousel/assets/ajax-loader.gif" /></center>');
	tooltipPanel.show();
	ajaxGetTooltip(nodeId);
}

function getTooltipResponse(response) {
    tooltipPanel.setBody(response.tooltip);
   	var columns = [
            {key:"Value",label:"Value",sortable:false,width: "300px"},
            {key:"Frequency",label:"Frequency",sortable:false, width: "150px"}
            ];
	
	var source = new YAHOO.util.DataSource(YAHOO.util.Dom.get("exampleTable"));
    source.responseType = YAHOO.util.DataSource.TYPE_HTMLTABLE;
    source.responseSchema = {
                    fields: [{key:"Value"},
                             {key:"Frequency"}
                    ]};
    
    var table = new YAHOO.widget.ScrollingDataTable("exampleTableContainer",
    	columns, source, {
    		caption:"Available value distribution for current element.",
    		width: "450px",
    		height:"20em"
    	});
}

function ajaxGetTooltip(element) {
    var command = "getTooltip";
    YAHOO.util.Connect.asyncRequest('POST', 'itemLevelLabelTooltip.action',
        { success: function(o) {
            if(o.responseText == null || o.responseText == "") {
        		alert("error executing " + command);
        		return;
        	}	
        
            response = YAHOO.lang.JSON.parse(o.responseText);
            getTooltipResponse(response);
          },
            
          failure: function(o) {
            alert("tooltip failed: " + command);
          },
              
          argument: null
    }, "command=" + command + "&element=" + element+ "&upload=" + itemLevelLabelPanelUploadId );
}

//end tooltip

function ajaxShowTree(uploadId, itemxpath) {
	YAHOO.util.Connect.asyncRequest('POST', 'treeview.action',
        {
            success: function(o) {
		     	$("div[id=source_tree]").html( o.responseText );
				renderItemLevelLabelPanel();
				
            },
            
            failure: function(o) {
            	$("div[id=source_tree]").html("<h1>Error</h1>");
            }
        }, "uploadId=" + uploadId+ "&itemxpath=" + itemxpath);
}

function ajaxItemLevelLabelRequest(uploadId, orgId, userId, transformed) {
	itemLevelLabelPanelOrgId = orgId;  
	itemLevelLabelPanelUploadId=uploadId;
	itemLevelLabelPanelUserId=userId;
	itemLevelLabelPanelTransformed=transformed;
    itemLevelLabelPanel.setBody("<center>Loading...<br/><img src=\"images/rel_interstitial_loading.gif\"/></center>");
    itemLevelLabelPanel.show();
    itemLevelLabelPanelUploadId=uploadId;
    YAHOO.util.Connect.asyncRequest('POST', 'itemLevelLabelRequest.action',
        {
            success: function(o) {
                itemLevelLabelPanel.setBody(o.responseText);
                document.getElementById("source_tree").innerHTML="<center>Loading schema...<br/><img src=\"images/rel_interstitial_loading.gif\"/></center>";
                ajaxShowTree(uploadId,"1");
                //renderItemLevelLabelPanel();
                
            },
            
            failure: function(o) {
                itemLevelLabelPanel.setBody("<h1>Error</h1>");
            }
        }, "uploadId=" + uploadId);
}

function ajaxItemLevelSet(uploadId, xpath) {
    YAHOO.util.Connect.asyncRequest('POST', 'itemLevelLabelSet.action',
        {
            success: function(o) {
                var el = YAHOO.util.Dom.get("item_level_xpath");
                el.innerHTML = o.responseText;
                if(xpath!="-1" && xpath.indexOf("@")==-1){
                 ajaxShowTree(uploadId,"1");
                 
                 document.getElementById("setlabel").style.display="block";
                 ajaxItemLabelSet(uploadId,"-1","label");
                	}
                else{document.getElementById("setlabel").style.display="none";}
            },
            
            failure: function(o) {
            }
        }, "uploadId=" + uploadId + "&xpath=" + xpath + "&type=level");
}

function resetLevelLabel(uploadId){
	 if(itemLevelLabelPanelTransformed==true){
	       alert("This import is transformed! You cannot edit the Item Level and Label on a transformed import. Please delete your transformation first.")	
	    }
	    else{
			ajaxItemLabelSet(uploadId,"-1","label");
		   //huh???
			/*ajaxItemLevelSet(uploadId,"-1","level");*/
			ajaxItemLevelSet(uploadId,"-1");
			ajaxShowTree(uploadId,null);
	    }
}

function ajaxItemLabelSet(uploadId, xpath) {
    // TODO: must be romoved. text() node should not even be here at the first place
    var xp = xpath + "/text()";
    YAHOO.util.Connect.asyncRequest('POST', 'itemLevelLabelSet.action',
        {
            success: function(o) {
                var el = YAHOO.util.Dom.get("item_label_xpath");
                var responseText = o.responseText;
                // TODO: must be romoved. text() node should not even be here at the first place
                responseText = responseText.replace("/_:text()", "");
                el.innerHTML = responseText;
            },
            
            failure: function(o) {
            }
        }, "uploadId=" + uploadId + "&xpath=" + xp + "&type=label");
}

function renderItemLevelLabelPanel()
{
    initSourceTree();
}

var illddListeners = [];
var debugnode = null;

function initSourceTree() {
    var iTreeEl = YAHOO.util.Dom.get("source_tree");
    inputTree = new YAHOO.widget.TreeView("treemenu_1");
    if(inputTree != null) {
        inputTree.render();
        inputTree.subscribe("expandComplete", initSourceTreeListeners);
        initRootNodeListeners();
        illddListeners["item_level_xpath"] = new ILLDDSend("item_level_xpath", "source_input");
        illddListeners["item_level_xpath"].subscribe("b4MouseDownEvent", function() { return false; } );
        illddListeners["item_label_xpath"] = new ILLDDSend("item_label_xpath", "source_input");
        illddListeners["item_label_xpath"].subscribe("b4MouseDownEvent", function() { return false; } );
    } else {
        alert("There is no input tree!");
    }
}

function initRootNodeListeners() {
    var roots = inputTree.getRoot().children;
    if(roots == null) return;
    
    for(var i = 0; i < roots.length; i++) {
        var contentEl = roots[i].getContentEl();
        if(contentEl != null) {
           // var targets = contentEl.getElementsByClassName("xmlelement","",""); fix for ie below
            var targets=getElementsByClassName("xmlelement","div",contentEl);

            if(targets.length > 0) {
                initNodeListener(targets[0].id, "source_input");
            }
        }
    }
}


function initSourceTreeListeners(node, b, c, d) {
      var i = 0;
      var n = 0;
      debugnode = node;
      for(n=0; n < node.children.length; n++) {
          var yuiId = node.children[n].contentElId;
          var yuiEl = YAHOO.util.Dom.get(yuiId);
          var nodeId = yuiEl.childNodes[0].id;
          
          initNodeListener(nodeId, "source_input");
      }
}

function initNodeListener(id, target) {
    var el = YAHOO.util.Dom.get(id);
    if(el != null) {
        illddListeners[id] = new ILLDDSend(id, target);
    }
}

ILLDDSend = function(id, sGroup, config) {
    if (id) {
        // bind this drag drop object to the
        // drag source object
        this.init(id, sGroup, config);
        this.initFrame();
    }

    var el = this.getEl();
    var dragEl = this.getDragEl();
    var s = dragEl.style;
    s.border = "1px dashed black";
    s.backgroundColor = "#f6f5e5";
    s.opacity = 0.85;
    s.filter = "alpha(opacity=85)";
    s.padding = "20px";
};

// extend proxy so we don't move the whole object around
ILLDDSend.prototype = new YAHOO.util.DDProxy();

// DEBUG: sel & tel for dd source & target
var sel;
var tel;
ILLDDSend.prototype.onDragDrop = function(e, id) {
	   if(itemLevelLabelPanelTransformed==true){
	       alert("This import is transformed! You cannot edit the Item Level and Label on a transformed import. Please delete your transformation first.")	
	    }
	    else{
		    var sourceEl = this.getEl();
		    if(id != undefined) {
		        var targetEl = YAHOO.util.Dom.get(id);
		        targetEl.style.border = "1px solid #CCCCCC";
		
		        xpath = sourceEl.getAttribute("xpath");
		        upload = targetEl.getAttribute("upload");
		        if(targetEl.id == "item_level_xpath") {
		            ajaxItemLevelSet(upload, xpath);
		        } else if(targetEl.id == "item_label_xpath") {
		            ajaxItemLabelSet(upload, xpath);
		        }
		    }
	    }
}

ILLDDSend.prototype.startDrag = function(x, y) {
    var dragEl = this.getDragEl();
    var clickEl = this.getEl();

    var content = "";
    var xpath = clickEl.getAttribute("xpath");
 
    if(xpath != undefined) {
        if(xpath.length < 50) {
            content = xpath;
        } else {
            content = xpath.substring(0, 20) + " ... " + xpath.substring(xpath.length - 25);
        }
        
        dragEl.className = clickEl.className;
        dragEl.innerHTML = content;
        dragEl.style.width = "auto";
        dragEl.style.fontSize = "75%"
    } else {
        return false;
    }
    
};

ILLDDSend.prototype.onDragEnter = function(e, id) {
	
    var el;
    
    if(id != undefined) {
        if ("string" == typeof id) {
            el = YAHOO.util.DDM.getElement(id);
        } else {
            el = YAHOO.util.DDM.getBestMatch(id).getEl();
        }
        
        if(el.className == "mappingTarget") {
            el.style.border = "1px solid red";
        }
    }
};

ILLDDSend.prototype.onDragOut = function(e, id) {
	
    var el;
    if(id != undefined) {
        if ("string" == typeof id) {
            el = YAHOO.util.DDM.getElement(id);
        } else {
            el = YAHOO.util.DDM.getBestMatch(id).getEl();
        }
        
        if(el.className == "mappingTarget") {

            el.style.border = "1px solid #CCCCCC";
        }
    }
};

ILLDDSend.prototype.endDrag = function(e) {
   // override so source object doesn't move when we are done
}


