var valueMappingPanel = "";
var valueMappingBrowser = undefined;
var valueMappingOverlay = null;
var valueMappingXPath = null;

function initValueMapping(container) {
	valueMappingPanel = new YAHOO.widget.Panel("valueMapping", {
		width : "700px",
		height : "400px",
		fixedcenter : true,
		close : true,
		draggable : true,
		zindex : 1,
		underlay : "shadow",
		modal : true,
		visible : false
	});

	var valueMappingPanelBody = "";

	valueMappingPanel.setHeader("Value Mappings");
	valueMappingPanel.setBody(valueMappingPanelBody);
	valueMappingPanel.hideEvent.subscribe(valueMappingPanelClose); 
	
	valueMappingPanel.render(container);
}

/**
 * show value mappings panel for mapping [index] of element [id].
 */
function valueMappings(id, index) {
	var p = elementPanels["panel_" + id];

	if(p != undefined) {
		valueMappingPanel.elementPanel = p;
		var item = p.attachedItem;
		setupValueMappingPanel(item, index);
		valueMappingPanel.show();
	} else {
		alert("Undefined item panel for: " + id);
	}
}

/**
 * initialize value mapping panel based on mapping [index] of [item]
 */
var mm = null;
function setupValueMappingPanel(item, index) {
//	alert("item: " + item + "  index: " + index);
	valueMappingPanel.item = item;
	valueMappingPanel.itemidx = index;
	valueMappingPanel.elementPanel.attachedItem = item;
	var mapping = item.mappings[index];
	var renderOverlay = false;
	if(mapping.value != valueMappingXPath) {
		valueMappingXPath = mapping.value;
		renderOverlay = true;
	}
	var xpid = jQuery('div[xpath="' + valueMappingXPath + '"]').attr('xpid');
	
	
	var body = "";
	// value mapping input fields
	body += "<div>";
	
	body += "<div style='width: 100%; height: 50px'>"
	body += "<span style='float: left'><b>Input Value</b><br/><input id='valueMappingInput' type='text'/>";

	if(xpid != null) {
		body += "<a id='value-mapping-show-browser' href='javascript:toggleValueMappingBrowser()'><img style='width: 14px; height: 14px' src='images/test-matching.gif'/>Browse values</a>";
	}
	
	body += "</span>";
	body += "<span style='float: right'><b>Maps to</b><br/>";
	if(item.enumerations == undefined) {
		body += "<input id='valueMapping" + item.id + "'></input>";
	} else {
		body += generateItemEnumerationSelect("valueMapping", item, "");
	}
    body += "<a style='border 0px solid transparent' href='javascript:submitValueMapping()'><img src=\"images/add.png\" width='16px' height='16px'/>Add value mapping</a>";
	body += "</span>";
	body += "<br/>";	
	body += "</div>";

	// value mapping list
	body += "<div style='border: 1px solid black; padding: 10px; height: 75%; overflow: auto'>";
	body += "<table style='width:100%'>";
	body += "<tr>";
	body += "<th><b>Input</b></th><th><b>Output</b></th><th></th>";
	body += "</tr>";
	
	if(mapping.valuemap != undefined && mapping.valuemap.length > 0) {
		for(var idx in mapping.valuemap) {
			var vm = mapping.valuemap[idx];

			if((idx % 2) == 0) {
				body += "<tr style='background: #dddddd'>";
			} else {
				body += "<tr>";
			}
			
			body += "<td style='padding: 5px; width: 50%; text-overflow: ellipsis'>" + vm.input + "</td>";
			body += "<td style='padding: 5px; width: 50%'>" + vm.output + "</td>";

			body += "<td><a style='border 0px solid transparent' href='javascript:ajaxRemoveValueMapping(\"" + vm.input + "\", " + item.id + ", " + index + ")'><img src=\"images/close.png\" width='16px' height='16px'/></a></td>";
			body += "</tr>";
		}
	} else {
		body += "<tr colspan='2'><td><i>No value mappings defined</i></td></tr>";
	}
		
	body += "</table>";
	body += "</div>";
	body += "</div>";

	valueMappingPanel.setBody(body);
	
	if(renderOverlay) {
		valueMappingOverlay = new YAHOO.widget.Panel("value-mapping-overlay", { context:["value-mapping-show-browser","tl","bl",["beforeShow","windowResize"]],		 visible:false, width:"320px", height: "300px", "z-index": 1000 } );
		valueMappingOverlay.setBody("<div id='value-mapping-browser'></div>");
		valueMappingOverlay.render(valueMappingPanel.body);
	
		if(xpid != undefined) {
			valueMappingBrowser = new ValueBrowser("value-mapping-browser", xpid);
			valueMappingBrowser.setSelectCallback(valueMappingBrowserSelect);
		} else {
			valueMappingBrowser = undefined;
		}
	} else {
		valueMappingOverlay.render(valueMappingPanel.body);
		valueMappingOverlay.hide();
	}	
}

function ajaxSetValueMapping(input, output, target, index) {
	var command = "setValueMapping";
	YAHOO.util.Connect.asyncRequest('POST', 'mappingAjax.action', {
		success : function(o) {
			if (o.responseText == null || o.responseText == "") {
				alert("error executing " + command);
				return;
			}

			try {
				response = YAHOO.lang.JSON.parse(o.responseText);
				setValueMappingResponse(response);
			} catch (e) {
				alert("Error executing " + command + "\n" + e.name + ":"
						+ e.message);
			}
		},

		failure : function(o) {
			alert("mapping async request failed: " + command);
		},

		argument : null
	}, "command=" + command + "&input=" + input + "&output=" + output
			+ "&target=" + target + "&index=" + index);
}

function ajaxRemoveValueMapping(input, target, index) {
	var command = "removeValueMapping";
	YAHOO.util.Connect.asyncRequest('POST', 'mappingAjax.action', {
		success : function(o) {
			if (o.responseText == null || o.responseText == "") {
				alert("error executing " + command);
				return;
			}

			try {
				response = YAHOO.lang.JSON.parse(o.responseText);
				removeValueMappingResponse(response);
			} catch (e) {
				alert("Error executing " + command + "\n" + e.name + ":"
						+ e.message);
			}
		},

		failure : function(o) {
			alert("mapping async request failed: " + command);
		},

		argument : null
	}, "command=" + command + "&input=" + input + "&target=" + target
			+ "&index=" + index);
}

function submitValueMapping()
{
	var item = valueMappingPanel.item;
	var id = item.id;
	
	var element = YAHOO.util.Dom.get("valueMapping" + id);
	if(item.enumerations == undefined) {
		dropdownValue = element.value;
	} else {
		var dropdownIndex = element.selectedIndex;
		var dropdownValue = element[dropdownIndex].value;
		if(dropdownValue == null) { dropdownValue = ""; }
	}

	var input = encodeURIComponent(YAHOO.util.Dom.get("valueMappingInput").value);
	var output = encodeURIComponent(dropdownValue);
	ajaxSetValueMapping(input, output, valueMappingPanel.item.id, valueMappingPanel.itemidx);
}

function setValueMappingResponse(response) {
	setupValueMappingPanel(response, valueMappingPanel.itemidx);
}

function removeValueMappingResponse(response) {
	setupValueMappingPanel(response, valueMappingPanel.itemidx);
}

function toggleValueMappingBrowser() {
	if(valueMappingOverlay.cfg.getProperty("visible")) {
		valueMappingOverlay.hide();
	} else {
		valueMappingOverlay.show();
	}
}

function valueMappingPanelClose() {
	if(valueMappingOverlay != undefined) {
		valueMappingOverlay.hide();
	}
}

function valueMappingBrowserSelect(args) {
	var target = args.target;
	var record = valueMappingBrowser.table.getRecord(target);
	var data = "";
	if(record != undefined) {
		data = record.getData().Value;
	}

	var input = YAHOO.util.Dom.get("valueMappingInput");
	input.value = data;
	
	valueMappingOverlay.hide();
}