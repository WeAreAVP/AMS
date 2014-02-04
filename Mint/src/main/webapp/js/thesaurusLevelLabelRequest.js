
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


var thesaurusLevelLabelPanel = new YAHOO.widget.Panel("thesauruslevellabel",
	{ width: "800px",
	  height: "500px",
	  fixedcenter: true,
	  constraintoviewport: true,
	  close: true,
	  draggable: false,
	  modal: true,
	  visible: false
	}
);

//Perform cleanup
function thesaurusLevelLabelPanelClose() {
	
}

var optionsTabs = null;
var thesaurusMenuButton = null;

//Buttons
var newButton = null;
var editButton = null;
var deleteButton = null;

//Dialogs
var newDialog = null;
var editDialog = null;

thesaurusLevelLabelPanel.setHeader("Thesauri definition");
thesaurusLevelLabelPanel.setBody("<center>Loading...<br/><img src=\"images/rel_interstitial_loading.gif\"/></center>");
thesaurusLevelLabelPanel.hideEvent.subscribe(thesaurusLevelLabelPanelClose); 

var thesaurusLevelLabelPanelOrgId = null;
var thesaurusLevelLabelPanelUploadId = -1;
var thesaurusLevelLabelPanelUserId = -1;

var currentSelectedXpath = null;
var currentSelectedThesaurus = null;

var thesaurusTreeUploadId;
function ajaxThesaurusShowTree(uploadId, thesaurusxpath) {
	thesaurusTreeUploadId = uploadId;
	YAHOO.util.Connect.asyncRequest('POST', 'treeview.action',
        {
            success: function(o) {
				$("div[id=thesaurus_source_tree]").html( o.responseText );
				renderThesaurusLevelLabelPanel();
				
            },
            
            failure: function(o) {
            	$("div[id=thesaurus_source_tree]").html("<h1>Error</h1>");
            }
        }, "schema=2&uploadId=" + uploadId+ "&thesaurusxpath=" + thesaurusxpath);
}

function ajaxThesauriLevelLabelRequest(uploadId, orgId, userId) {
	thesaurusLevelLabelPanelOrgId = orgId;  
	thesaurusLevelLabelPanelUploadId=uploadId;
	//Next line required for tooltips!!!
	itemLevelLabelPanelUploadId=uploadId;
	thesaurusLevelLabelPanelUserId=userId;
    thesaurusLevelLabelPanel.setBody("<center>Loading...<br/><img src=\"images/rel_interstitial_loading.gif\"/></center>");
    thesaurusLevelLabelPanel.render(document.body);
    thesaurusLevelLabelPanel.show();
    thesaurusLevelLabelPanelUploadId=uploadId;

    YAHOO.util.Connect.asyncRequest('POST', 'thesaurusLevelLabelRequest.action',
        {
            success: function(o) {
                thesaurusLevelLabelPanel.setBody(o.responseText);
                ajaxThesaurusShowTree(uploadId,"1");
                initDialogs();
                //Initialize buttons
                initButtons();
                
                //Create thesaurus menu selection
                updateThesaurusList();
                
            },
            
            failure: function(o) {
                thesaurusLevelLabelPanel.setBody("<h1>Error</h1>");
            }
        }, "uploadId=" + uploadId);
}

function initButtons() {

	//Create buttons for thesaurus management and disable them
    newButton = new YAHOO.widget.Button({  label:"New",
											id:"new-button",
											container:"thesaurus_info_buttons",
											onclick: { fn: onNewButton } 
										});
    editButton = new YAHOO.widget.Button({  label:"Edit",
    										id:"edit-button",
    										container:"thesaurus_info_buttons",
    										disabled: true,
    										onclick: { fn: onEditButton } 
    									});
    deleteButton = new YAHOO.widget.Button({ label:"Delete",
											 id:"delete-button",
											 container:"thesaurus_info_buttons",
											 disabled: true,
											 onclick: { fn: onDeleteButton } 
										   });
}

function formValidate() {
	var emailFilter = /^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z0-9]{2,4})+$/;
	var urlFilter = /^(https?):\/\/((?:[a-z0-9.-]|%[0-9A-F]{2}){3,})(?::(\d+))?((?:\/(?:[a-z0-9-._~!$&'()*+,;=:@]|%[0-9A-F]{2})*)*)(?:\?((?:[a-z0-9-._~!$&'()*+,;=:\/?@]|%[0-9A-F]{2})*))?(?:#((?:[a-z0-9-._~!$&'()*+,;=:\/?@]|%[0-9A-F]{2})*))?$/i;
    var data = this.getData();
    if (data.title== "" || data.description == "" || data.contact == "") {
    	yuiWarning("Incorrect data", "Please enter values on 'Title', 'Description' and 'Contact email' fields!", null);
		return false;
    } else if(!emailFilter.test(data.contact)) {
    	yuiWarning("Incorrect data", "Email address is not correct!", null);
		return false;
    } else if((data.url == "") && (data.uploadFile="")) {
    	yuiWarning("Incorrect data", "You must specify either URL or file!", null);
		return false;
    } else if((data.url != "") && !urlFilter.test(data.url)) {
    	yuiWarning("Incorrect data", "Thesaurus' URL is invalid!", null);
		return false;
    } else {
    	return true;
    }
};


//Hack for handling nested modality
function markModal() {
  //YAHOO.util.Dom.addClass(this.element, "yui-modal");
}

function unmarkModal() {
  YAHOO.util.Dom.removeClass(this.element, "yui-modal");
}

function initDialogs() {
	editDialog = initDialog("editformdialog", editThesaurusSuccess, editThesaurusFailure, formValidate);
	newDialog = initDialog("addformdialog", addThesaurusSuccess, addThesaurusFailure, formValidate);

}

function initDialog(id, success_action, fail_action, validator) {
	//New thesaurus form dialog
	// Remove progressively enhanced content class, just before creating the module
    YAHOO.util.Dom.removeClass(id, "yui-pe-content");

	var dialog = new YAHOO.widget.Dialog(id, {
		width : "400px",
		visible : false,
		fixedcenter: true,
		constraintoviewport : true,
		draggable: false,
		modal: true,
		buttons : [ 
		            {text:"Submit", handler:function(){this.submit();}, isDefault:true},
		            {text:"Cancel", handler:function(){this.cancel();}}
		          ]
	});
	dialog.render(document.body);
	
	dialog.callback.success = success_action;
	dialog.callback.upload = success_action;
	dialog.callback.failure = fail_action;
	dialog.validate = validator;

	//Hack for nested modality
	thesaurusLevelLabelPanel.showMaskEvent.subscribe(markModal);
	thesaurusLevelLabelPanel.hideMaskEvent.subscribe(unmarkModal);
	//dialog.showMaskEvent.subscribe(markModal);
	//dialog.hideMaskEvent.subscribe(unmarkModal);
    
	dialog.showEvent.subscribe(function() {
    	thesaurusLevelLabelPanel.hideMask();
    	//YAHOO.util.Dom.addClass(document.body, "masked");
    });

	dialog.hideEvent.subscribe(function() {
    	thesaurusLevelLabelPanel.showMask();
    });
    
	return dialog;
}

function onEditButton(e) {
	clearAddResult();
	editDialog.show();
	
}

function onDeleteButton(e) {
	clearAddResult();
	deleteCurrentThesaurus();
}

function onNewButton(e) {
	clearAddResult();
	newDialog.show();
}

function renderThesaurusLevelLabelPanel() {
	initThesaurusSourceTree();
}

var tillddListeners = [];
var debugnode = null;

function initThesaurusSourceTree() {
    var iTreeEl = YAHOO.util.Dom.get("thesaurus_source_tree");
    thesaurusInputTree = new YAHOO.widget.TreeView("treemenu_2");
    if(thesaurusInputTree != null) {
        thesaurusInputTree.render();
        thesaurusInputTree.subscribe("expandComplete", initThesaurusSourceTreeListeners);
        thesaurusInputTree.subscribe("clickEvent", function(oArgs) {
        	var node = oArgs.node;
        	node.toggle();
        	node.focus();
        	var target = YAHOO.util.Event.getTarget(oArgs.event);
        	var xpath = target.getAttribute("xpath");
        	currentSelectedXpath = xpath;
        	updateStats();
         	return false;
        });
        initThesaurusRootNodeListeners();
        tillddListeners["thesaurus_level_xpath_list"] = new TILLDDSend("thesaurus_level_xpath_list", "thesaurus_source_input");
        tillddListeners["thesaurus_level_xpath_list"].subscribe("b4MouseDownEvent", function() { return false; } );
        

    } else {
        alert("There is no input tree!");
    }
}

function initThesaurusRootNodeListeners() {
    var roots = thesaurusInputTree.getRoot().children;
    if(roots == null) return;
    
    for(var i = 0; i < roots.length; i++) {
        var contentEl = roots[i].getContentEl();
        if(contentEl != null) {
            var targets = getElementsByClassName("xmlelement");
            if(targets.length > 0) {
            	initThesaurusNodeListener(targets[0].id, "thesaurus_source_input");
            }
        }
    }
}



function yuiConfirm(title, message, yesHandler, noHandler) {
	var handleYes = function() {
		confirmDialog.destroy();
		if(yesHandler) yesHandler();
	};

	var handleNo = function() {
		confirmDialog.destroy();
		if(noHandler) noHandler();
	};


	confirmDialog = new YAHOO.widget.SimpleDialog("yuiConfirm",
	{
		width: "300px",
		fixedcenter: true,
		visible: false,
		draggable: false,
		close: false,
		modal: true,
		text: message,
		icon: YAHOO.widget.SimpleDialog.ICON_HELP,
		constraintoviewport: true,
		buttons: [ { text:"Yes", handler:handleYes, isDefault:true },
		        { text:"No",  handler:handleNo } ]
	});

	confirmDialog.setHeader(title);
	confirmDialog.render(document.body);
	confirmDialog.show();

	return confirmDialog();
}


function yuiWarning(title, message, okHandler) {
	var handleOk = function() {
		warningDialog.destroy();
		if(okHandler) okHandler();
	};


	warningDialog = new YAHOO.widget.SimpleDialog("yuiWarning",
	{
		width: "300px",
		fixedcenter: true,
		visible: false,
		draggable: false,
		close: false,
		modal: true,
		text: message,
		icon: YAHOO.widget.SimpleDialog.ICON_ALARM,
		constraintoviewport: true,
		buttons: [ { text:"Ok", handler:handleOk, isDefault:true } ]
	});

	warningDialog.setHeader(title);
	warningDialog.render(document.body);
	warningDialog.show();

	return warningDialog();
}

function clearAddResult() {
	var stats = YAHOO.util.Dom.get('addresult');
	stats.innerHTML = "";
}

function handleAddClick(e) {
	if(currentSelectedThesaurus == null) {
		var stats = YAHOO.util.Dom.get('addresult');
		stats.innerHTML = "<span class=\"error\">Please select a thesaurus.</span>";
	} else if(currentSelectedXpath == null) {
		stats.innerHTML = "<span class=\"error\">Please select a node from the list above.</span>";
	} else {
		var sURL= "ThesaurusAjax.action?action=ASSIGN&thesaurusId=" + currentSelectedThesaurus + "&xpath=" + currentSelectedXpath + "&uploadId=" + thesaurusTreeUploadId;
	
		var handleSuccess = function(o){
			if(o.responseText !== undefined){
				var stats = YAHOO.util.Dom.get('addresult');
				stats.innerHTML = o.responseText;
				updateLabelList();
			}
		}
	
		var handleFailure = function(o){
			if(o.responseText !== undefined){
				var stats = YAHOO.util.Dom.get('addresult');
				stats.innerHTML = "<div>Error loading stats for " + o.arguments.upId + " ...</div>";
			}
		}
	
	
		var callback = 
		{ 
			success:handleSuccess, 
			failure: handleFailure
		}; 
		var request = YAHOO.util.Connect.asyncRequest('GET', sURL, callback, null);
	}
}

function updateStats() {
	var stats = YAHOO.util.Dom.get('stats');
	var sURL= "ThesaurusAjax.action?action=stats&uploadId=" + thesaurusTreeUploadId + "&xpath=" + currentSelectedXpath;
	stats.innerHTML = "<div>Loading stats for " + currentSelectedXpath +" ...</div>";
	
	var handleSuccess = function(o){
		if(o.responseText !== undefined){
			var stats = YAHOO.util.Dom.get('stats');
			stats.innerHTML = o.responseText;
		}
	}
	
	var handleFailure = function(o){
		if(o.responseText !== undefined){
			var stats = YAHOO.util.Dom.get('stats');
			stats.innerHTML = "<span class=\"error\">Error loading stats for " + currentSelectedXpath+ " ...</span>";
		}
	}
	
	var callback = 
	{ 
		success:handleSuccess, 
		failure: handleFailure
	}; 
	var request = YAHOO.util.Connect.asyncRequest('GET', sURL, callback, null);
}

function selectThesaurus(t_id) {
		clearAddResult();
    	currentSelectedThesaurus = t_id;
    	updateLabelList();
    	updateThesaurusInfo();
    	enableButtons();
    	populateEditForm();
}

function enableButtons() {
	editButton.set("disabled", false); 
	deleteButton.set("disabled", false);
}

function disableButtons() {
	editButton.set("disabled", true); 
	deleteButton.set("disabled", true);
}

function applyToCurrentMapping() {
	var sURL= "ThesaurusAjax.action?action=APPLY_THESAURUS&thesaurusId=" + currentSelectedThesaurus + "&uploadId=" + thesaurusTreeUploadId;
	var handleSuccess = function(o){
		if(o.responseText !== undefined){
			if(o.responseText != 'Error') {
				updateLabelList();
			} else {
				var error_field = YAHOO.util.Dom.get('addresult');
				error_field.innerHTML = "<span class=\"error\">Error applying thesaurus to current mapping...</span>";
			}
		}
	}
	var handleFailure = function(o){
		if(o.responseText !== undefined){
			var error_field = YAHOO.util.Dom.get('addresult');
			error_field.innerHTML = "<span class=\"error\">Error applying thesaurus to current mapping...</span>";
		}
	}
	
	var callback = 
	{ 
		success:handleSuccess, 
		failure: handleFailure
	}; 
	var request = YAHOO.util.Connect.asyncRequest('GET', sURL, callback, null);
}

function updateThesaurusInfo() {
	clearAddResult();
	var sURL= "ThesaurusAjax.action?action=EDIT_FORM&thesaurusId=" + currentSelectedThesaurus;
    
	var handleSuccess = function(o){
		if(o.responseText !== undefined){
			var thesaurusInfo = YAHOO.util.Dom.get('thesaurus_info');
			if(o.responseText != 'Error') {
				//Read JSON string for thesaurus
				var thesaurus = YAHOO.lang.JSON.parse(o.responseText);
				//Set values to text
				thesaurusInfo.innerHTML = '<h2>' + thesaurus.title +'</h2>' +
				  'Description: ' + thesaurus.description +'<br />' +
				  'Contact: ' + thesaurus.contact + '<br />' +
				  'URL: <a href="' + thesaurus.url +'">'+ thesaurus.url +'</a><br /><br />';
				//Set current thesaurus label
				var current_thesaurus_label = YAHOO.util.Dom.get('current_thesaurus_label');
				current_thesaurus_label.innerHTML = thesaurus.title;
				//Set values to edit form
				populateEditForm();
			} else {
				thesaurusInfo.innerHTML = "<span class=\"error\">Error retrieving thesaurus data...</span>";
			}
		}
	}
	var handleFailure = function(o){
		if(o.responseText !== undefined){
			var thesaurusInfo = YAHOO.util.Dom.get('thesaurus_info');
			thesaurusInfo.innerHTML = "<span class=\"error\">Error retrieving thesaurus data...</span>";
		}
	}

	var callback = 
	{ 
		success:handleSuccess, 
		failure: handleFailure
	}; 
	var request = YAHOO.util.Connect.asyncRequest('GET', sURL, callback, null);
}

function populateEditForm() {
	var sURL= "ThesaurusAjax.action?action=EDIT_FORM&thesaurusId=" + currentSelectedThesaurus;
    
	var handleSuccess = function(o){
		if(o.responseText !== undefined){
			if(o.responseText != 'Error') {
				//Read JSON string for thesaurus
				var thesaurus = YAHOO.lang.JSON.parse(o.responseText);
				//Set values to components
				var thesaurusInfo = YAHOO.util.Dom.get('thesaurus_info');
				var e_thesaurusId = YAHOO.util.Dom.get('e_thesaurusId');
				var e_title = YAHOO.util.Dom.get('e_title');
				var e_description = YAHOO.util.Dom.get('e_description');
				var e_contact = YAHOO.util.Dom.get('e_contact');
				var e_url = YAHOO.util.Dom.get('e_url');
				e_thesaurusId.value = thesaurus.thesaurusId;
				e_title.value = thesaurus.title;
				e_description.value = thesaurus.description;
				e_contact.value = thesaurus.contact;
				e_url.value = thesaurus.url;
			} else {
				var error_field = YAHOO.util.Dom.get('form_edit_error');
				error_field.innerHTML = "<span class=\"error\">Error retrieving thesaurus data...</span>";
			}
		}
	}
	var handleFailure = function(o){
		if(o.responseText !== undefined){
			var error_field = YAHOO.util.Dom.get('form_edit_error');
			error_field.innerHTML = "<span class=\"error\">Error retrieving thesaurus data...</span>";
		}
	}

	var callback = 
	{ 
		success:handleSuccess, 
		failure: handleFailure
	}; 
	var request = YAHOO.util.Connect.asyncRequest('GET', sURL, callback, null);
}

function deleteAssign(mappingId) {
	var sURL= "ThesaurusAjax.action?action=UNASSIGN&mappingId=" + mappingId;
    
	var handleSuccess = function(o){
		if(o.responseText !== undefined){
			if(YAHOO.lang.trim(o.responseText) == "OK") {
				updateLabelList();
				clearAddResult();
			} else {
				var stats = YAHOO.util.Dom.get('addresult');
				stats.innerHTML = "<span class=\"error\">" + o.responseText + "</span>";
			}
		}
	}
	var handleFailure = function(o){
		if(o.responseText !== undefined){
			var stats = YAHOO.util.Dom.get('addresult');
			stats.innerHTML = "<span class=\"error\">Error sending delete request...</span>";
		}
	}

	var callback = 
	{ 
		success:handleSuccess, 
		failure: handleFailure
	}; 
	var request = YAHOO.util.Connect.asyncRequest('GET', sURL, callback, null);
}

function updateThesaurusList() {
	var sURL= "ThesaurusAjax.action?action=LIST&uploadId=" + thesaurusLevelLabelPanelUploadId;
    
	var handleSuccess = function(o){
		if(o.responseText !== undefined){
			var thesaurusDiv = YAHOO.util.Dom.get('thesaurus_filter');
			thesaurusDiv.innerHTML = o.responseText;
		}
	}
	var handleFailure = function(o){
		if(o.responseText !== undefined){
			var stats = YAHOO.util.Dom.get('addlabels');
			stats.innerHTML = "<span addlabels=\"error\">Error loading thesaurus list...</span>";
		}
	}

	var callback = 
	{ 
		success:handleSuccess, 
		failure: handleFailure
	}; 
	var request = YAHOO.util.Connect.asyncRequest('GET', sURL, callback, null);
}

function updateLabelList() {
	clearAddResult();
	var sURL= "ThesaurusAjax.action?action=LABELS&thesaurusId=" + currentSelectedThesaurus + "&uploadId=" + thesaurusLevelLabelPanelUploadId;
	
	var handleSuccess = function(o){
		if(o.responseText !== undefined){
			var stats = YAHOO.util.Dom.get('thesaurus_level_xpath_list');
			stats.innerHTML = o.responseText;
		}
	}

	var handleFailure = function(o){
		if(o.responseText !== undefined){
			var stats = YAHOO.util.Dom.get('active_labels');
			stats.innerHTML = "<div>Error loading nodes for specified thesaurus...</div>";
		}
	}


	var callback = 
	{ 
		success:handleSuccess, 
		failure: handleFailure
	}; 
	var request = YAHOO.util.Connect.asyncRequest('GET', sURL, callback, null);
}

function doDeleteCurrentThesaurus() {
	var sURL= "ThesaurusAjax.action?action=DELETE&uploadId=" + thesaurusTreeUploadId + "&thesaurusId=" + currentSelectedThesaurus;
	
	var handleSuccess = function(o){
		if(o.responseText !== undefined) {
			var result_div = YAHOO.util.Dom.get('addresult');
			result_div.innerHTML = "<span>Thesaurus deleted</span>";
			//
			updateThesaurusList();
			//Clear edit form
			var thesaurusInfo = YAHOO.util.Dom.get('thesaurus_info');
			thesaurusInfo.innerHTML = '<h2>No thesaurus selected</h2>' +
									'Description: -<br />' +
									'Contact: -<br />' + 
									'URL: -<br />' + 
									'<br />';
			
			var currentThesaurusDiv = YAHOO.util.Dom.get('current_thesaurus_label');
			currentThesaurusDiv.innerHTML = "No thesaurus selected";
			disableButtons();
			var edit_form = YAHOO.util.Dom.get('thesaurus_edit_form');
			edit_form.reset();
			//Clear selected thesaurus
			var stats = YAHOO.util.Dom.get('thesaurus_level_xpath_list');
			stats.innerHTML = "";
		}
	}
	
	var handleFailure = function(o){
		if(o.responseText !== undefined){
			var result_div = YAHOO.util.Dom.get('addresult');
			result_div.innerHTML = "<span class=\"error\">Error deleting current thesaurus ...</span>";
		}
	}
	
	var callback = 
	{ 
		success:handleSuccess, 
		failure: handleFailure
	}; 
	var request = YAHOO.util.Connect.asyncRequest('GET', sURL, callback, null);
}

function deleteCurrentThesaurus() {

	var thesaurusLabel = YAHOO.util.Dom.get('current_thesaurus_label');
	var currentThesaurus = thesaurusLabel.innerHTML;
	yuiConfirm(
		'Delete ' + currentThesaurus + '?',
		'Are you sure you want to delete the selected thesaurus (' + currentThesaurus + ')?',
		function() {
			doDeleteCurrentThesaurus();
 		}
	);
}


function addThesaurusSuccess(o) {
	currentSelectedThesaurus = o.responseText;
	//Update lists
	updateLabelList();
	updateThesaurusList();
	updateThesaurusInfo();
	//Clear form
	var form = YAHOO.util.Dom.get('thesaurus_form');
	form.reset();
	clearAddResult();
}

function addThesaurusFailure(o) {
	alert("addThesaurusFailure");
	var form_error = YAHOO.util.Dom.get('form_error');
	form_error.innerHTML = "<span class=\"error\">Error saving thesaurus...</span>";
}

function editThesaurusSuccess(o) {
	currentSelectedThesaurus = o.responseText;
	//Update lists
	updateLabelList();
	updateThesaurusList();
	updateThesaurusInfo();
}

function editThesaurusFailure(o) {
	var form_error = YAHOO.util.Dom.get('form_error');
	form_error.innerHTML = "<span class=\"error\">Error saving thesaurus...</span>";
}

function initThesaurusSourceTreeListeners(node, b, c, d) {
      var i = 0;
      var n = 0;
      debugnode = node;
      for(n=0; n < node.children.length; n++) {
          var yuiId = node.children[n].contentElId;
          var yuiEl = YAHOO.util.Dom.get(yuiId);
          var nodeId = yuiEl.childNodes[0].id;
          
          initThesaurusNodeListener(nodeId, "thesaurus_source_input");
      }
}

function initThesaurusNodeListener(id, target) {
    var el = YAHOO.util.Dom.get(id);
    if(el != null) {
        tillddListeners[id] = new TILLDDSend(id, target);
    }
}

function getTarget(x){
    x = x || window.event;
    return x.target || x.srcElement;
}

//Adds item to list
function ajaxThesaurusAddItem(uploadId, xpath) {
	if(currentSelectedThesaurus == null) {
		var stats = YAHOO.util.Dom.get('addresult');
		stats.innerHTML = "<span class=\"error\">Please select a thesaurus.</span>";
	} else {
		var sURL= "ThesaurusAjax.action?action=ASSIGN&thesaurusId=" + currentSelectedThesaurus + "&xpath=" + xpath + "&uploadId=" + thesaurusTreeUploadId;
		var handleSuccess = function(o){
			if(o.responseText !== undefined){
				var stats = YAHOO.util.Dom.get('addresult');
				stats.innerHTML = o.responseText;
				updateLabelList();
			}
		}
		var handleFailure = function(o){
			if(o.responseText !== undefined){
				var stats = YAHOO.util.Dom.get('addresult');
				stats.innerHTML = "<div>Error loading stats for " + o.arguments.upId + " ...</div>";
			}
		}
	
		var callback = 
		{ 
			success:handleSuccess, 
			failure: handleFailure
		}; 
		var request = YAHOO.util.Connect.asyncRequest('GET', sURL, callback, null);
		updateLabelList();
	}
}



TILLDDSend = function(id, sGroup, config) {
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
TILLDDSend.prototype = new YAHOO.util.DDProxy();

// DEBUG: sel & tel for dd source & target
var sel;
var tel;
TILLDDSend.prototype.onDragDrop = function(e, id) {
    var sourceEl = this.getEl();
    if(id != undefined) {
        var targetEl = YAHOO.util.Dom.get(id);
        targetEl.style.border = "1px solid black";

        xpath = sourceEl.getAttribute("xpath");
        upload = targetEl.getAttribute("upload");
        if(targetEl.id == "thesaurus_level_xpath_list") {
        	//Add to list
        	ajaxThesaurusAddItem(upload, xpath);
        }
    }
    
}

TILLDDSend.prototype.startDrag = function(x, y) {
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

TILLDDSend.prototype.onDragEnter = function(e, id) {
    var el;
    if(id != undefined) {
        if ("string" == typeof id) {
            el = YAHOO.util.DDM.getElement(id);
        } else {
            el = YAHOO.util.DDM.getBestMatch(id).getEl();
        }
        
        if(el.id == "thesaurus_level_xpath_list") {
            el.style.border = "1px solid red";
        }
    }
};

TILLDDSend.prototype.onDragOut = function(e, id) {
    var el;
    if(id != undefined) {
        if ("string" == typeof id) {
            el = YAHOO.util.DDM.getElement(id);
        } else {
            el = YAHOO.util.DDM.getBestMatch(id).getEl();
        }
        
        if(el.className == "mappingTarget") {

            el.style.border = "1px solid black";
        }
    }
};

TILLDDSend.prototype.endDrag = function(e) {
   // override so source object doesn't move when we are done
}