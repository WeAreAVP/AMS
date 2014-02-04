var targetDefinition = null;
var configuration = null;

var templateButton = null;
var groupButtons = [];
var navigationOptions = [];
var elementPanels = [];
var ddListeners = [];

var selectedPanel = "";

var logPanel = "";
var loadingPanel = "";
var annotationsPanel = "";
var tranformPanel = "";
var summaryPanel = "";
var tooltipPanel = "";
var constantValuePanel = "";
var conditionPanel = "";
var functionPanel = "";

var inputTree;
var thesaurusInputTree;
var parent_tree_nodes = [];
var initcomplete = false;

var mappingToolDebug = false;

var getElementsByClassName = function(className, tag, elm) {
	if (document.getElementsByClassName) {
		getElementsByClassName = function(className, tag, elm) {
			elm = elm || document;
			var elements = elm.getElementsByClassName(className), nodeName = (tag) ? new RegExp(
					"\\b" + tag + "\\b", "i")
					: null, returnElements = [], current;
			for ( var i = 0, il = elements.length; i < il; i += 1) {
				current = elements[i];
				if (!nodeName || nodeName.test(current.nodeName)) {
					returnElements.push(current);
				}
			}
			return returnElements;
		};
	} else if (document.evaluate) {
		getElementsByClassName = function(className, tag, elm) {
			tag = tag || "*";
			elm = elm || document;
			var classes = className.split(" "), classesToCheck = "", xhtmlNamespace = "http://www.w3.org/1999/xhtml", namespaceResolver = (document.documentElement.namespaceURI === xhtmlNamespace) ? xhtmlNamespace
					: null, returnElements = [], elements, node;
			for ( var j = 0, jl = classes.length; j < jl; j += 1) {
				classesToCheck += "[contains(concat(' ', @class, ' '), ' "
						+ classes[j] + " ')]";
			}
			try {
				elements = document.evaluate(".//" + tag + classesToCheck, elm,
						namespaceResolver, 0, null);
			} catch (e) {
				elements = document.evaluate(".//" + tag + classesToCheck, elm,
						null, 0, null);
			}
			while ((node = elements.iterateNext())) {
				returnElements.push(node);
			}
			return returnElements;
		};
	} else {
		getElementsByClassName = function(className, tag, elm) {
			tag = tag || "*";
			elm = elm || document;
			var classes = className.split(" "), classesToCheck = [], elements = (tag === "*" && elm.all) ? elm.all
					: elm.getElementsByTagName(tag), current, returnElements = [], match;
			for ( var k = 0, kl = classes.length; k < kl; k += 1) {
				classesToCheck.push(new RegExp("(^|\\s)" + classes[k]
						+ "(\\s|$)"));
			}
			for ( var l = 0, ll = elements.length; l < ll; l += 1) {
				current = elements[l];
				match = false;
				for ( var m = 0, ml = classesToCheck.length; m < ml; m += 1) {
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

function init(upload, mapping, output) {
	initGUIPanels();
	ajaxInitMappings(upload, mapping, output);
}

function initMappingsResponse(response) {
	targetDefinition = response.targetDefinition;
	configuration = response.configuration;

	initGroupButtons();
	initElementPanels();
	initSourceTree(response.sourceTree);
	inputTree.expandAll();
	ajaxGetHighlightedElements();
}

function initGUIPanels() {	
	var ec = YAHOO.util.Dom.get("editor_container");
	if (ec == null)
		ec = document.body;

	loadingPanel = new YAHOO.widget.Panel("wait", {
		width : "240px",
		fixedcenter : true,
		close : false,
		draggable : false,
		zindex : 1,
		underlay : "shadow",
		modal : true,
		visible : false
	});

	loadingPanel.setHeader("Loading, please wait...");
	
	var loadingPanelBody = "";
	loadingPanelBody += "<div>";
	loadingPanelBody += "<center><img src='js/mapping/lib/yui/carousel/assets/ajax-loader.gif'/></center>";
	loadingPanelBody += "</div>";
	
	loadingPanel.setBody(loadingPanelBody);

	logPanel = new YAHOO.widget.Panel("log", {
		width : "600px",
		fixedcenter : true,
		close : false,
		draggable : false,
		zindex : 1,
		underlay : "shadow",
		modal : true,
		visible : false
	});

	logPanel.setHeader("Log");
	
	var logPanelBody = "";
	logPanelBody += "<div>";
	logPanelBody += "<textarea id='log-messages' style='width: 100%; height: 300px'></textarea>";
	logPanelBody += "</div>";
	
	logPanel.setBody(logPanelBody);

	annotationsPanel = new YAHOO.widget.Panel("annotations", {
		width : "480px",
		fixedcenter : true,
		close : true,
		draggable : true,
		zindex : 1,
		underlay : "shadow",
		modal : true,
		visible : false
	});

	annotationsPanel.setHeader("Annotation");
	annotationsPanel.setBody("Annotation");

	tooltipPanel = new YAHOO.widget.Panel("tooltips", {
		width : "580px",
		height : "500px",
		fixedcenter : true,
		close : true,
		draggable : true,
		zindex : 1,
		underlay : "shadow",
		modal : true,
		visible : false
	});

	tooltipPanel.setHeader("Help");
	tooltipPanel
			.setBody('<center><img src="js/mapping/lib/yui/carousel/assets/ajax-loader.gif" /></center>');

	summaryPanel = new YAHOO.widget.Panel("summary", {
		width : "900px",
		height : "500px",
		fixedcenter : true,
		close : true,
		draggable : true,
		zindex : 1,
		underlay : "shadow",
		modal : true,
		visible : false
	});

	var summaryBody = "";

	summaryBody += "Summary...";

	summaryPanel.setHeader("Summary");
	summaryPanel.setBody(summaryBody);

	constantValuePanel = new YAHOO.widget.Panel("constantValue", {
		width : "400px",
		height : "100px",
		fixedcenter : true,
		close : true,
		draggable : true,
		zindex : 1,
		underlay : "shadow",
		modal : true,
		visible : false
	});

	var constantValuePanelBody = "";

	constantValuePanelBody += '<input type="text" id="constant" name="constant"/>';
	constantValuePanelBody += '<br/><br/>';
	constantValuePanelBody += '<input id="panel_save" type="button" value="Ok" onClick="javascript:submitConstantValue()"/>';
	constantValuePanelBody += '<input id="panel_cancel" type="button" value="Cancel" onCLick="javascript:constantValuePanel.hide()"/>';

	constantValuePanel.setHeader("Set a constant value for this field");
	constantValuePanel.setBody(constantValuePanelBody);

	functionPanel = new YAHOO.widget.Panel("function", {
		width : "400px",
		height : "150px",
		fixedcenter : true,
		close : true,
		draggable : true,
		zindex : 1,
		underlay : "shadow",
		modal : true,
		visible : false
	});

	functionPanel.setHeader("Apply function to xpath value");
	functionPanel.setBody("<div/>");

	conditionPanel = new YAHOO.widget.Module("conditionModule", {
		width : "600px",
		height : "500px",
		fixedcenter : true,
		close : false,
		draggable : false,
		zindex : 1,
		modal : false,
		visible : false
	});

	var conditionPanelBody = "";
	conditionPanelBody += "<table style='width: 100%'>";
	// header
	conditionPanelBody += "<tr>";
	conditionPanelBody += "<td style='text-align: center'>";
	conditionPanelBody += "<h3>Condition Editor: <div id='condition_editor_title'></div></h3>";
	conditionPanelBody += "</td>";
	conditionPanelBody += "</tr>";
	// body
	conditionPanelBody += "<tr>";
	conditionPanelBody += "<td>";
	conditionPanelBody += "<div id='condition_editor_content'></div>";
	conditionPanelBody += "</td>";
	conditionPanelBody += "</tr>";
	// footer
	conditionPanelBody += "<tr>";
	conditionPanelBody += "<td>";
	conditionPanelBody += "<a href='javascript:hideConditionPanel()'>Back</a>";
	conditionPanelBody += "</td>";
	conditionPanelBody += "</tr>";
	conditionPanelBody += "</table>";

	conditionPanel.setHeader("");
	conditionPanel.setBody(conditionPanelBody);

	initValueMapping(ec);

	logPanel.render(ec);
	loadingPanel.render(ec);
	annotationsPanel.render(ec);
	tooltipPanel.render(ec);
	summaryPanel.render(ec);
	functionPanel.render(ec);
	constantValuePanel.render(ec);
	
	// defined in xmlPreviewRequest.js
	xmlPreviewPanel.render(ec);

	var mc = YAHOO.util.Dom.get("mappings_container");
	conditionPanel.render(mc);
}

var navi = "";
var btnGroups = [];
function initGroupButtons() {
	addLogMessage("Initialise groups");

	var navigation_buttons_container = YAHOO.util.Dom
			.get("navigation_buttons_container");
	var template_groups_container = YAHOO.util.Dom
			.get("template_groups_container");
	var default_groups_container = YAHOO.util.Dom
			.get("default_groups_container");

	if (configuration.navigation != undefined) {
		// store references for all groups
		var navgroups = [];
		for ( var g in targetDefinition.groups) {
			var group = targetDefinition.groups[g];
			navgroups[group.name] = group;
		}

		// parse navigation components
		var btnGroup = null;
		for ( var i in configuration.navigation) {
			var navigation = configuration.navigation[i];

			addLogMessage("Initialise navigation[" + i + "]");
			
			if (navigation.name != undefined) {
				navigationOptions[navigation.name] = navigation;
				if(navigation.type != undefined && navigation.type == "template") {
					navigationOptions["_template"] = navigation;
				}
			}

			if (navigation.type == "label") {
				addLogMessage("  label: " + navigation.label);
				var d = document.createElement("div");
				d.innerHTML = "<b><br/>" + navigation.label + "<br/><br/></b>";
				navigation_buttons_container.appendChild(d);
				btnGroup = null;
			} else if (navigation.type == "template") {
				addLogMessage("  template");
				var d = document.createElement("div");
				d.setAttribute("id", "button_group_container"
						+ btnGroups.length)
				navigation_buttons_container.appendChild(d);
				btnGroup = new YAHOO.widget.ButtonGroup( {
					id : "buttongroup" + btnGroups.length,
					name : "radiofield",
					container : "button_group_container" + btnGroups.length
				});
				btnGroups[btnGroups.length] = btnGroup;

				var template_lbl = "Template";
				if (navigation.label != undefined) {
					template_lbl = navigation.label;
				} else if(navigation.name != undefined) {
					template_lbl = navigation.name;
				}

				var template = targetDefinition.template;
				var button = new YAHOO.widget.Button( {
					id : "btnTemplate",
					label : template_lbl,
					type : "radio",
					checked : false
				});
				button.group = group;

				function onNavigationTemplateButtonClick(p_oEvent) {
					showTemplateElements(navigation.include);
					resetRest(this);
				}
				button.on("click", onNavigationTemplateButtonClick);
				btnGroup.addButton(button);
				templateButton = button;
				groupButtons["_template"] = button;

				btnGroup = null;
			} else if (navigation.type == "group") {
				addLogMessage("  group: " + navigation.name);
				var group = navgroups[navigation.name];
				if (group != undefined) {
					if (btnGroup == null) {
						var d = document.createElement("div");
						d.setAttribute("id", "button_group_container"
								+ btnGroups.length)
						navigation_buttons_container.appendChild(d);
						btnGroup = new YAHOO.widget.ButtonGroup( {
							id : "buttongroup" + btnGroups.length,
							name : "radiofield",
							container : "button_group_container"
									+ btnGroups.length
						});
						btnGroups[btnGroups.length] = btnGroup;
					}

					var buttonLabel = group.name;
					if(navigation.label != undefined) buttonLabel = navigation.label;
					var button = new YAHOO.widget.Button( {
						id : "btn" + buttonLabel,
						label : buttonLabel,
						type : "radio",
						checked : false
					});
					button.group = group;
					button.navigation = navigation;

					function onNavigationButtonClick(p_oEvent) {
						showGroupElements(this.group, this.navigation.include);
						resetRest(this);
					}
					button.on("click", onNavigationButtonClick);
					btnGroup.addButton(button);
					groupButtons[group.name] = button;
				}
			} else {
				addLogMessage("  undefined navigation type");
			}
		}
	} else {
		buttonGroup2 = new YAHOO.widget.ButtonGroup( {
			id : "buttongroup2",
			name : "radiofield",
			container : "default_groups_container"
		});
		btnGroups[btnGroups.length] = buttonGroup2;

		// process template button
		if (targetDefinition.template != undefined) {
			var template = targetDefinition.template;
			var templateContents = 0;
			for ( var i in template.children) {
				var child = template.children[i];
				if (child.type != "group") {
					templateContents = templateContents + 1;
				}
			}

			if (templateContents > 0) {
				buttonGroup1 = new YAHOO.widget.ButtonGroup( {
					id : "buttongroup1",
					name : "radiofield",
					container : "template_groups_container"
				});

				btnGroups[btnGroups.length] = buttonGroup1;

				var button = new YAHOO.widget.Button( {
					id : "btnTemplate",
					label : "Template",
					type : "radio",
					checked : false
				});
				button.group = group;

				function onTemplateButtonClick(p_oEvent) {
					showTemplateElements();
					resetRest(this);
				}
				button.on("click", onTemplateButtonClick);
				buttonGroup1.addButton(button);
				templateButton = button;
			}
		}

		// process group buttons
		for ( var i in targetDefinition.groups) {
			var group = targetDefinition.groups[i];
			var button = new YAHOO.widget.Button( {
				id : "btn" + group.name,
				label : group.name,
				type : "radio",
				checked : false
			});
			button.group = group;

			function onButtonClick(p_oEvent) {
				showGroupElements(this.group);
				resetRest(this);

			}
			button.on("click", onButtonClick);

			buttonGroup2.addButton(button);
			groupButtons[group.name] = button;
		}
	}
}

function initElementPanels() {
	// process template panel
	if (targetDefinition.template != undefined) {
		generateItemPanel(targetDefinition.template, "mappings_container",
				navigationOptions["_template"]);
//		lazy initialization of panel listeners
//		setPanelListeners(targetDefinition.template);
	}

	// process group panels
	for ( var i in targetDefinition.groups) {
		var group = targetDefinition.groups[i];
		var panel = null;
		var item = group.contents;

		generateItemPanel(item, "mappings_container",
				navigationOptions[group.name]);
//		lazy initialization of panel listeners
//		setPanelListeners(group.contents);
	}

	hideAllPanels();

	// enableConstantValueEditingForClass("constantValue");
	enableConstantValueEditingForClass("empty_mapping");
	enableConstantValueEditingForClass("no_mapping");
	enableConstantValueEditingForClass("constant_mapping");
}

function generateItemPanel(item, container, options) {
	if (item.type == 'group')
		return;

	if (container != "mappings_container") {
		if(item.attributes != undefined && item.attributes.length > 0) {
			generateAttributePanel(item, container + "_attributes", options);
		}
	}

	generateElementPanel(item, container, options);
}

function generateAttributePanel(item, container, options) {
	var panelid = "panel_attributes_" + item.id;
	var panel = new YAHOO.widget.Module(panelid, {
		close : false,
		visible : true,
		width : "400px",
		effect : {
			effect : YAHOO.widget.ContainerEffect.FADE,
			duration : 0.25
		},
		draggable : false
	});

	var content = "";

	content += "<table style='width: 100%; height: 100%'>";
	content += "<tr>";
	if (container != 'mappings_container') {
		content += "<td style='width: 100%'><div class='elementident'></div></td>";
	}
	content += toolEmptyButtonTD();
	content += "<td class='elementattributes'>";
	content += "<div>";
	for ( var a in item.attributes) {
		var attribute = item.attributes[a];
		content += generateAttributeContent(attribute);
	}
	content += "</div>";
	content += "</td>";
	content += toolEmptyButtonWithStyleTD('background-color: #CCDDCC');
	content += toolEmptyButtonWithStyleTD('background-color: #CCDDCC');
	content += toolEmptyButtonWithStyleTD('background-color: #CCDDCC');
	content += toolEmptyButtonWithStyleTD('background-color: #CCDDCC');
	content += "</tr>";
	content += "</table>";

	panel.setBody(content);
	panel.render(YAHOO.util.Dom.get(container));
	elementPanels[panelid] = panel;

	return panel;
}

var oo;
function generateElementPanel(item, container, options, ident) {
	var panelid = "panel_" + item.id;
	
	if(item.children != undefined && item.children.length > 0) {
	  var panel = new YAHOO.widget.Module(panelid, {
		close : false,
		visible : true,
		width : "400px",
		effect : {
			effect : YAHOO.widget.ContainerEffect.FADE,
			duration : 0.25
		},
		draggable : false
	  });

	  var content = "";

	  content += "<table style='width: 100%; height: 100%'>";
	  content += "<tr>";
	  if (container != 'mappings_container') {
		content += "<td><div class='elementident'></div></td>";
	  }
	
	  content += "<td>";
	  content += "<div>";
	  if (container == 'mappings_container') {
		for(var c in item.attributes) {
			addLogMessage("attribute: " + c);
			var child = item.attributes[c];
			if(!((options != undefined) && (options.hide != undefined) && (options.hide.indexOf(child.name) >= 0))) {
				content += generateComplexChildContent(child);
			}
		}
	  }

	  for ( var c in item.children) {
		var child = item.children[c];
		if(!((options != undefined) && (options.hide != undefined) && (options.hide.indexOf(child.name) >= 0))) {
			content += generateComplexChildContent(child);
		}
	  }
	  
	  content += "</div>";
	  content += "</td>";
	  content += "</tr>";
	  content += "</table>";

	  panel.setBody(content);
	  panel.render(YAHOO.util.Dom.get(container));
	} else {
		var panel = [];
	}
	
	panel.attachedItem = item;
	elementPanels[panelid] = panel;

	if (container == 'mappings_container') {
		for(var c in item.attributes) {
			var child = item.attributes[c];
			if(!((options != undefined) && (options.hide != undefined) && (options.hide.indexOf(child.name) >= 0))) {
				generateItemPanel(child, child.id + "_container", options);
			}
		}
	}

	for ( var c in item.children) {
		var child = item.children[c];
		if(!((options != undefined) && (options.hide != undefined) && (options.hide.indexOf(child.name) >= 0))) {
			generateItemPanel(child, child.id + "_container", options);
		}
	}

	return panel;
}

function generateComplexChildContent(child) {
	var childid = child.id;
	var content = "";

	var style = "style='border-bottom: 1px solid #cccccc'";

	/*
	if (child.type != "string") {
		style = "style=\"background-color:#dddddd\"";
	}
	*/

	content += "<div " + style + " id='" + childid
			+ "_hd' class='elementhd'><table style=\"height: 100%\"><tr>";

	// open/close panel
	if (child.type == "string" || child.children == undefined || child.children.length == 0) {
		content += toolEmptyButtonTD();
	} else {
		content += toolButtonTD("<img id='handle-" + childid + "' onclick='javascript:togglePanel(\""
				+ childid
				+ "\")' width='14px' height='14px' src='images/expand.png'/>");
	}

	// element content
	if (child.type == "string") {
		content += "<td class='elementcontent'>"
				+ generateElementContent(child) + "</td>";
	} else {
		// content += "<td style='vertical-align: top'>" + child.name + "</td>";
		content += "<td class='elementcontent'>"
				+ generateNonElementContent(child) + "</td>";
	}

	// content += "<td style='vertical-align: middle; width: 100%' ><div
	// style='float:right'>";

	// remove duplicate node
	if (child.maxOccurs == -1 && child.duplicate != undefined
			&& child.fixed == undefined) {
		var s = "";
		s += "<a href=\"javascript:removeNode('"
				+ childid
				+ "')\" style=\"vertical-align: center; border: 0px solid transparent\">";
		s += "<img title='Remove mapping' width='14px' height='14px' src='images/close.png'/>";
		s += "</a>";
		content += toolButtonTD(s);
	} else {
		content += toolEmptyButtonTD();
	}

	// duplicate button
	if (child.maxOccurs == -1 && child.fixed == undefined) { // if(child.type
																// != "string"
																// &&
																// child.maxOccurs
																// == -1) {
		var s = "";
		s += "<a href=\"javascript:duplicateNode('"
				+ childid
				+ "')\" style=\"vertical-align: center; border: 0px solid transparent\">";
		s += "<img title='Duplicate this element' width='14px' height='14px' src='images/add.png'/>";
		s += "</a>";
		content += toolButtonTD(s);
	} else {
		content += toolEmptyButtonTD();
	}

	// attributes button
	if (child.attributes != undefined && child.attributes.length > 0) {
		content += toolButtonTD("<img id='attributes_icon_" + childid + "' "
				+ "onclick='javascript:toggleAttributePanel(\"attributes_"
				+ childid
				+ "\")' width='14px' height='14px' src='images/expand_attributes.png'/>");
	} else {
		content += toolEmptyButtonTD();
	}

	content += toolButtonTD("<a style='border 0px solid transparent' href='javascript:showAnnotation(\""
			+ child.id
			+ "\")'><img title='Show annotations for this element' src=\"custom/images/help.png\" width='16px' height='16px'/></a>");

	content += "</tr></table></div>";
	content += "<div class='el' id='" + childid
			+ "_container_attributes'></div>";
	content += "<div class='el' id='" + childid + "_container'></div>";

	return content;
}

function generateElementContent(item) {
	var content = "";
	var id = item.id;

	content += "<div id='" + id + "' class='mappingTarget'>";
	content += "<table style='width: 100%; height: 100%'>";
	content += "<tr>";

	// element name
	var displayName = item.name;
	if (item.label != undefined) {
		displayName = item.label;
	}
	
	var prefix = "";
	if(item.prefix != undefined && item.prefix != "") {
		prefix = "<span class='element-prefix'>" + item.prefix + ":</span>";
	}
	
	content += "<td clafss='elementname'><div class='element' id='" + item.name
			+ "Id'>" + prefix + displayName + ":</div></td>";
	content += "<td class='elementcontent'><div class='mapping' id='"
			+ item.name + "Mapping'>";

	content += generateMappingsTable(item);

	content += "</div></td>";
	content += "</tr>";
	content += "</table>";
	content += "</div>";

	return content;
}

function generateAttributeContent(item) {
	var content = "";
	var id = item.id;
	
	// virtual attribute panel for item placeholder
	var p = [];
	p.attachedItem = item;
	elementPanels["panel_" + id] = p;

	content += "<div style='width:100%'>";
	content += "<div id='" + id + "' class='mappingTarget'>";
	content += "<table style='width: 100%; height: 100%'>";
	content += "<tr>";

	var displayName = item.name;
	if (item.label != undefined) {
		displayName = item.label;
	}

	var prefix = "";
	if(item.prefix != undefined && item.prefix != "") {
		prefix = "<span class='element-prefix'>" + item.prefix + ":</span>";
	}

	displayName = displayName.replace("@", "@" + prefix);
	
	// element name
	content += "<td class='elementname'><div class='element' id='" + item.name
			+ "Id'>" + displayName + ":</div></td>";
	content += "<td class='elementcontent'><div class='mapping' id='"
			+ item.name + "Mapping'>";

	content += generateMappingsTable(item);

	content += "</div></td>";
	content += "</tr>";
	content += "</table>";
	content += "</div>";
	content += "</div>";

	return content;
}

function generateNonElementContent(item) {
	var content = "";
	var id = item.id;

	content += "<div id='" + id + "' class='mappingTarget'>";
	content += "<table style='width: 100%; height: 100%'>";
	content += "<tr>";

	var displayName = item.name;
	if (item.label != undefined) {
		displayName = item.label;
	}
	
	var prefix = "";
	if(item.prefix != undefined && item.prefix != "") {
		prefix = "<span class='element-prefix'>" + item.prefix + ":</span>";
	}

	// content += "<td style='vertical-align: top'><div style='float:right'><a
	// style='border: 0px solid transparent' href='javascript:removeMappings(\""
	// + item.id + "\")'><img src='images/close.png' width='14px'
	// height='14px'/></a></div></td>";
	content += "<td class='elementname'><div class='element' id='" + item.name
			+ "Id'>" + prefix + displayName + ":</div></td>";
	content += "<td class='elementcontent'><div class='mapping' id='"
			+ item.name + "Mapping'>";

	content += generateMappingsTable(item);

	content += "</div></td>";
	content += "</tr>";
	content += "</table>";
	content += "</div>";

	return content;
}

var anitem = "";
// var enum = "";
function generateMappingsTable(item) {
	var content = "";
	anitem = item;

	var condition_xpath = "";
	var condition_value = "";

	if (item.condition != undefined) {
		condition_xpath = item.condition.xpath;
		condition_value = item.condition.value;
	}

	content += "<table style='width: 100%; height: 100%'>";
	content += "<tr>";

	if (item.fixed != undefined) {
		content += toolEmptyButtonTD();
		content += "<td>";
		if (item.mappings.length > 0) {
			content += "<table>";
			for ( var i in item.mappings) {
				var type = item.mappings[i].type;
				var value = item.mappings[i].value;
				content += "<tr><td>" + value + "</td></tr>";
			}
			content += "</table>";
		}
		content += "</td>";
	} else {
		// condition button
		// check used to be: (item.children == undefined || item.children.length
		// == 0) && (item.mappings.length > 0)
		// changed it to allow conditions for structural mappings (on elements
		// without children)
		if ((item.mappings.length > 0)) {
			if (item.condition == undefined) {
				content += toolButtonTD("<a style='border 0px solid transparent' href='javascript:ajaxAddCondition(\""
						+ item.id
						+ "\", 0)'><img title=\"Set condition\" src=\"images/condition_disabled.png\" width='16px' height='16px'/></a>");
			} else {
				content += toolButtonTD("<a style='border 0px solid transparent' href='javascript:ajaxRemoveCondition(\""
						+ item.id
						+ "\", 0)'><img title=\"Remove condition\" src=\"images/condition.png\" width='16px' height='16px'/></a>");
			} 
		}

		// mapping content
		content += "<td class='elementcontent'>";
		content += "<table style='width: 100%; height: 100%'>";
		if (item.mappings.length > 0) {
			// condition content
			if (item.condition != undefined) {
				content += "<tr><td colspan='3'>";
				content += "<table style='width: 100%; height: 100%'><tr>";
				if (!(item.condition.logicalop == undefined)) {
					content += "<td colspan='3'>";
					content += "if(...) - click icon on the right to see condition";
					content += "</td>";
				} else {
					// content += "<td style='vertical-align:middle; width:
					// 20px'>if &nbsp</td>";
					content += "<td>if &nbsp</td>";

					if (condition_xpath == "") {
						content += "<td><div id='"
								+ item.id
								+ ".condition.xpath' target='"
								+ item.id
								+ "' class='mapping_value'>condition input</div></td>";
					} else {
						var condition_xpath_element = condition_xpath
								.split("/").pop();
						var condition_tooltip = condition_xpath;

						content += "<td style='vertical-align: middle'><div style='float:right'><a  style='border: 0px solid transparent' href='javascript:ajaxRemoveConditionXPath(\""
								+ item.id
								+ "\")'><img src='images/close.png' width='14px' height='14px'/></a></div></td>";
						content += "<td><div>" + condition_xpath_element
								+ "</div></td>";

						if (condition_tooltip.length > 0) {
							var conditionTooltip = new YAHOO.widget.Tooltip(
									"conditionTooltip" + item.id, {
										context : "" + item.id
												+ ".condition.xpath",
										text : condition_tooltip,
										showdelay : 300
									});
						}
					}

					// content += "<td style='vertical-align: middle;
					// width:20px'>&nbsp = &nbsp</td>";
					content += "<td>&nbsp = &nbsp</td>";

					if (condition_value == "") {
						content += "<td><div id='"
								+ item.id
								+ ".condition' target='"
								+ item.id
								+ "' class='mapping_value; no_mapping'>condition value</div></td>";
					} else {
						if (condition_value.length > 15) {
							var original_condition_value = condition_value;
							condition_value = condition_value.substring(0, 12)
									+ "...";
						}

						content += "<td style='vertical-align: middle'><div style='float:right'><a  style='border: 0px solid transparent' href='javascript:ajaxRemoveConditionValue(\""
								+ item.id
								+ "\")'><img src='images/close.png' width='14px' height='14px'/></a></div></td>";
						content += "<td><div id='" + item.id
								+ ".condition' target='" + item.id
								+ "' class='mapping_value; no_mapping'>"
								+ condition_value + "</div><div id='value." + item.id
								+ ".condition' style='display: none'>"
								+ original_condition_value + "</div></td>";
					}
				}
				// expand condition
				content += "<td style='vertical-align: middle'><div style='float:right'><a  style='border: 0px solid transparent' href='javascript:showConditionPanel(\""
						+ item.id
						+ "\")'><img title=\"Set complex condition\" src='images/more.png' width='14px' height='14px'/></a></div></td>";

				content += "</tr></table>";
				content += "</td></tr>";
			}

			if (item.mappings.length > 0) {
				for ( var i in item.mappings) {
					var index = i;
					var type = item.mappings[i].type;
					var originalValue = "";
					var value = "";
					var tooltip = "";
					var class_value = "mapping_value; " + type + "_mapping";

					if (type == "xpath") {
						value = item.mappings[i].value;
						tooltip = value;
						value = value.split("/").pop();
					} else if (type == "constant") {
						value = item.mappings[i].value;
						if (value.length > 15) {
							value = value.substring(0, 12) + "...";
						}
					} else {
						value = item.mappings[i].value;
					}

					content += "<tr>";

					content += toolButtonTD("<div style='float:right'><a  style='border: 0px solid transparent' href='javascript:removeMappings(\""
							+ item.id
							+ "\", \""
							+ index
							+ "\")'><img src='images/close.png' width='14px' height='14px'/></a></div>");

					if (type == "xpath") {
						if (item.mappings[i].func == undefined) {
							content += toolButtonTD("<div style='float:right'><a  style='border: 0px solid transparent' href='javascript:setXPathFunction(\""
									+ item.id
									+ "\", \""
									+ index
									+ "\")'><img title=\"Apply function\" src='images/function-icon.png' width='20px' height='20px'/></a></div>");
						} else {
							content += toolButtonTD("<div style='float:right'><a  style='border: 0px solid transparent' href='javascript:setXPathFunction(\""
									+ item.id
									+ "\", \""
									+ index
									+ "\")'><img title=\"Apply function\" src='images/function-icon-selected.png' width='20px' height='20px'/></a></div>");
						}
					} else {
						content += toolEmptyButtonTD();
					}

					if (item.mappings[i].type == "xpath") {
						content += toolButtonTD("<div style='float:right'><a  style='border: 0px solid transparent' href='javascript:valueMappings(\""
								+ item.id
								+ "\", \""
								+ index
								+ "\")'><img title='Apply value mappings' src='images/test-matching.gif' width='14px' height='14px'/></a></div>");
					} else {
						content += toolEmptyButtonTD();
					}

					if (item.enumerations == undefined
							&& (item.type == "string" || item.name.indexOf("@") === 0)) {
						content += toolButtonTD("<div style='float:right'><a  style='border: 0px solid transparent' href='javascript:additionalMappings(\""
								+ item.id
								+ "\", \""
								+ index
								+ "\")'><img title='Add a mapping placeholder for concatenation' src='images/add.png' width='14px' height='14px'/></a></div>");
					} else {
						content += toolEmptyButtonTD();
					}
					
					/*
					if (item.enumerations == undefined
							&& (item.type == "string" || item.name.indexOf("@") === 0)) {
						content += toolButtonTD("<div style='float:right'><a  style='border: 0px solid transparent' href='javascript:additionalMappings(\""
								+ item.id
								+ "\", \""
								+ index
								+ "\")'><img title='Add a mapping placeholder for concatenation' src='images/add.png' width='14px' height='14px'/></a></div>");
					} else if (item.enumerations != undefined
							&& item.mappings[i].type == "xpath") {
						content += toolButtonTD("<div style='float:right'><a  style='border: 0px solid transparent' href='javascript:valueMappings(\""
								+ item.id
								+ "\", \""
								+ index
								+ "\")'><img title='Apply value mappings' src='images/test-matching.gif' width='14px' height='14px'/></a></div>");
					} else {
						content += toolEmptyButtonTD();
					}
					*/

					content += "<td class='elementcontent'>";
					if (type == "xpath") {
						value = item.mappings[i].value;
						tooltip = value;
						value = value.split("/").pop();

						content += "<div id='" + item.id + "." + index
								+ "' index=\"" + index + "\"  class='"
								+ class_value + "' style='padding: 2px'>"
								+ value + "</div>";
					} else if (type == "constant") {
						originalValue = item.mappings[i].value;
						value = originalValue;
						if (value.length > 15) {
							value = value.substring(0, 12) + "...";
						}
						content += "<div id='" + item.id + "." + index
								+ "' index=\"" + index + "\" class='"
								+ class_value + "' style='padding: 2px'>"
								+ value + "</div>";
						content += "<div style='display: none' id='value." + item.id + "." + index
								+ "' index=\"" + index + "\" class='"
								+ class_value + "' style='padding: 2px'>"
								+ originalValue + "</div>";
					} else if (type == "empty") {
						content += "<div id='" + item.id + "." + index
								+ "' index=\"" + index + "\" class='"
								+ class_value
								+ "' style='padding: 2px'>unmapped</div>";
					}
					content += "</td>";

					if (tooltip.length > 0) {
						var xpathMappingTooltip = new YAHOO.widget.Tooltip(
								"xpathMappingTooltip" + item.id, {
									context : "" + item.id + "." + index,
									text : tooltip,
									showdelay : 300
								});
					}

					content += "</tr>";
				}
			}
		} else {
			content += "<tr>";
			content += toolEmptyButtonTD();
			content += toolEmptyButtonTD();
			content += toolEmptyButtonTD();

			if (item.type == undefined || item.type == "string") {
				if (item.enumerations == undefined) {
					content += "<td class='elementcontent'><div id='"
							+ item.id
							+ ".default' index=\"-1\" target='"
							+ item.id
							+ "' class='mapping_value; no_mapping'>unmapped</div></td>";
				} else {
					content += "<td class='elementcontent'><div id='"
							+ item.id
							+ ".default' index=\"-1\" target='"
							+ item.id
							+ "' class='mapping_value; no_mapping'>unmapped (enumerated)</div></td>";
				}
			} else if (item.maxOccurs != 1) {
				content += "<td class='elementcontent'><div id='" + item.id
						+ ".structural' index=\"-1\" target='" + item.id
						+ "' class='mapping_value'>structural</div></td>";
			}

			content += "</tr>";
		}
		content += "</table>";

		content += "</td>";
	}

	content += "</tr>";
	content += "</table>";

	if (item.condition != undefined && item.condition.elseMapping != undefined) {
		generateMappingsTable(item.condition.elseMapping);
	}

	return content;
}

function generateItemEnumerationSelect(label, item, selectedValue) {
	var content = "";
	var name = label + item.id;

	if (selectedValue == null && item.mappings.length > 0) {
		selectedValue = item.mappings[0].value;
	}

	content += "<select id=\"" + name + "\">";

	for ( var e in item.enumerations) {
		var trunc = truncateEnumerationLabel(item.enumerations[e]);
		if (selectedValue == item.enumerations[e]) {
			content += "<option value=\"" + item.enumerations[e]
					+ "\" selected>" + trunc + "</option>";
		} else {
			content += "<option value=\"" + item.enumerations[e] + "\">"
					+ trunc + "</option>";
		}
	}

	content += "</select>";
	return content;
}

function truncateEnumerationLabel(label) {
	var truncLength = 55;
	if (label.length > truncLength) {
		var trunc = label.substring(0, truncLength - 2) + "..";
		return trunc;
	} else {
		return label;
	}
}

function generateElementId(parent, item, element) {
	return parent + "/" + item.name;
}

function registerPanelListeners(elid) {
	if(ddListeners[elid] != undefined) {
		delete ddListeners[elid];
	}

	ddListeners[elid] = new DDSend(elid, "mapping_input");
	ddListeners[elid].subscribe("b4MouseDownEvent", function() {
		return false;
	});
	
	return ddListeners[elid];
}

function setPanelListeners(item) {
	var id = item.id;
	
	if (item.mappings != null && item.mappings.length > 0) {
		for ( var m in item.mappings) {
			var elid = id + "." + m;
			registerPanelListeners(elid);
		}
	} else {
		var elid = id;
		if (item.type == "string" || item.type == undefined) {
			elid += ".default";
		} else {
			elid += ".structural";
		}

		registerPanelListeners(elid);
	}

	if (item.condition != null) {
		var elid = id + ".condition.xpath";
		registerPanelListeners(elid);
	}

	var panel = elementPanels["panel_" + id];
	if (item.attributes != undefined) {
		for ( var i in item.attributes) {
			setPanelListeners(item.attributes[i]);
		}
	}

	if (item.children != undefined) {
		for ( var i in item.children) {
			var child = item.children[i];
			var element = child.name;
			if(child.prefix != undefined && child.prefix != "") element = child.prefix + ":" + element;
			if(item.include == undefined || item.include.indexOf(element) >= 0) {
				if(panel != undefined && panel.cfg != undefined && panel.cfg.getProperty("visible")) {
					setPanelListeners(child);
				}
			}
		}
	}
}

function initSourceTree(treeDefinition) {
	var iTreeEl = YAHOO.util.Dom.get("sourceTree");
	iTreeEl.innerHTML = treeDefinition;
	inputTree = new YAHOO.widget.TreeView("treemenu_1");
	if (inputTree != null) {
		inputTree.render();
		inputTree.subscribe("expandComplete", initSourceTreeListeners);
		initRootNodeListeners();
	} else {
		alert("There is no input tree!");
	}
}

function initRootNodeListeners() {
	var roots = inputTree.getRoot().children;
	if (roots == null)
		return;
	for ( var i = 0; i < roots.length; i++) {
		var contentEl = roots[i].getContentEl();
		if (contentEl != null) {
			// var targets =
			// contentEl.getElementsByClassName("xmlelement","",""); fix for ie
			// below
			var targets = getElementsByClassName("xmlelement", "div", contentEl);
			if (targets.length > 0) {
				initNodeListener(targets[0].id, "mapping_input");
			}
		}
	}
}

function initSourceTreeListeners(node, b, c, d) {

	var i = 0;
	var n = 0;
	for (n = 0; n < node.children.length; n++) {
		var yuiId = node.children[n].contentElId;
		var yuiEl = YAHOO.util.Dom.get(yuiId);
		var nodeId = yuiEl.childNodes[0].id;
		var found = false;

		initNodeListener(nodeId, "mapping_input");
	}
}

function initNodeListener(id, target) {
	var el = YAHOO.util.Dom.get(id);
	if (el != null) {
		ddListeners[id] = new DDSend(id, target);
	}
}
