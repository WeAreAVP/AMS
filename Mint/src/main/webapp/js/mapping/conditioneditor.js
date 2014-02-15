function generateConditionTable(condition, path) {
	var result = "";

	if(condition.logicalop == undefined) {
		result += generateSimpleConditionTable(condition, path);
	} else {
		result += generateComplexConditionTable(condition, path);
	}
	
	return result;
}

function generateSimpleConditionTable(condition, path) {
	var result = "";
	
	result += "<table>";
	result += "<tr>";
	result += "<td>";
	result += xpathString(condition.xpath, path);
	result += "</td>";
	result += "<td>";
	result += relationalOpString(condition.relationalop, path);
	result += "</td>";
	result += "<td style='padding: 0px 10px 0px 10px'>";
	if(condition.relationalop != "EXISTS" && condition.relationalop != "NOTEXISTS") {
		result += valueString(condition.value, path);
	}
	result += "</td>";
	result += "</tr>";
	result += "</table>";
	
	return result;	
}

function generateComplexConditionTable(condition, path) {
	var result = "";
	
	result += "<table>";
	result += "<tr>";
	result += generateLogicalOperatorTableCell(condition, path);
	result += generateConditionSubclausesTableCell(condition, path);
	result += "</tr>";
	result += "</table>";
	
	return result;
}

function generateLogicalOperatorTableCell(condition, path)
{
	var result = "";
	
	result += "<td>";	
	result += logicalOpString(condition.logicalop, path); 
	result += "</td>";
	result += "<td class='conditionBracket'>&nbsp</td>";
	
	return result;
}

function generateConditionSubclausesTableCell(condition, path)
{
	var result = "";
	
	result += "<td>";
	result += generateConditionSubclauses(condition, path);
	result += "</td>";
	
	return result;
}

function generateConditionSubclauses(condition, path)
{
	var result = "";
	var item = conditionPanel.item;
	
	result += "<table>";
	var clauseidx = 0;
	for(var clause in condition.clauses) {
		var clausepath = path + clauseidx + ".";
		
		result += "<tr>";
		result += "<td style='width: 20px'>";
		result += "<a href=\"javascript:ajaxRemoveConditionClause('" + item.id + "','" + clausepath + "')\" style=\"vertical-align: center; border: 0px solid transparent\">";
	    result += "<img width='14px' height='14px' src='images/close.png'/>";
	    result += "</a>";
		result += "</td>";
		result += "<td>";
		result += generateConditionTable(condition.clauses[clause], clausepath);
		result += "</td>";
		result += "</tr>";
		clauseidx += 1;
	}
	result += "<tr>";
	result += "<td colspan='2'>";
	result += "<a href=\"javascript:ajaxAddConditionClause('" + item.id + "','" + path + "','false')\" style=\"vertical-align: center; border: 0px solid transparent\">";
    result += "<img width='14px' height='14px' src='images/add.png'/> Add Clause";
    result += "</a>&nbsp";
	result += "<a href=\"javascript:ajaxAddConditionClause('" + item.id + "','" + path + "','true')\" style=\"vertical-align: center; border: 0px solid transparent\">";
    result += "<img width='14px' height='14px' src='images/add.png'/> Add Subclause";
    result += "</a>";
	result += "</td>";
	result += "</tr>";
	result += "</table>";
	
	return result;
}

function xpathString(xpath, path) 
{
	var result = "";
	
	result += "<div id='clause." + path + "'class='mapping_value; clause_xpath'>";
	result += xpath
	result += "</div>";
	
	return result;
}

function valueString(value, path) 
{
	var result = "";
	var style = "";
	if(value == undefined) {
	}
	
	result += "<div id='clause." + path + "' class='clause_value' style='" + style + "'>";
	result += value
	result += "</div>";
	
	return result;
}

function relationalOpString(relationalop, path) 
{
	var result = "";
	var relationalops = new Array();
	
	relationalops["EQ"] = "=";
	relationalops["NEQ"] = "!=";
	relationalops["EXISTS"] = "exists";
	relationalops["NOTEXISTS"] = "does not exist";
	relationalops["CONTAINS"] = "contains";
	relationalops["STARTSWITH"] = "starts with";
	relationalops["ENDSWITH"] = "ends with";
	
	result += "<select id=\"relop" + path + "\" onchange=\"submitRelationalOpChange('" + path + "')\">";
	for(var rop in relationalops) {
		if(rop == relationalop) {
			result += "<option value=\"" + rop + "\" selected>" + relationalops[rop] + "</option>";
		} else {
			result += "<option value=\"" + rop + "\">" + relationalops[rop] + "</option>";
		}
	}
	result += "</select>";	
	
	return result;
}

function logicalOpString(logicalop, path) 
{
	var result = "";
	var result = "";
	var logicalops = new Array();
	
	logicalops["AND"] = "AND";
	logicalops["OR"] = "OR";
	
	result += "<select id=\"logop" + path + "\" onchange=\"submitLogicalOpChange('" + path + "')\">";
	for(var lop in logicalops) {
		if(lop == logicalop) {
			result += "<option value=\"" + lop + "\" selected>" + logicalops[lop] + "</option>";
		} else {
			result += "<option value=\"" + lop + "\">" + logicalops[lop] + "</option>";
		}
	}
	result += "</select>";	
	
	return result;
}

//submit value changes
function submitRelationalOpChange(path)
{
	var element = YAHOO.util.Dom.get("relop" + path);
	var dropdownIndex = element.selectedIndex;
	var dropdownValue = element[dropdownIndex].value;
	if(dropdownValue == null) { dropdownValue = ""; }

	ajaxSetConditionClauseKey(conditionPanel.item.id, path, "relationalop", dropdownValue);
}

function submitLogicalOpChange(path)
{
	var element = YAHOO.util.Dom.get("logop" + path);
	var dropdownIndex = element.selectedIndex;
	var dropdownValue = element[dropdownIndex].value;
	if(dropdownValue == null) { dropdownValue = ""; }

	ajaxSetConditionClauseKey(conditionPanel.item.id, path, "logicalop", dropdownValue);
}

//
// ajax response handling
//
function addConditionClauseResponse(response) 
{
	updateConditionPanelBody(response);
}

function removeConditionClauseResponse(response) 
{
	updateConditionPanelBody(response);
}

function setConditionClauseKeyResponse(response) 
{
	constantValuePanel.hide(); // for clause right values
	updateConditionPanelBody(response);
}

function removeConditionClauseKeyResponse(response) 
{
	updateConditionPanelBody(response);
}

function enableConstantValueEditingForConditionValue(element) {
	YAHOO.util.Event.addListener(element, 'dblclick', conditionValueEditHandler);
	YAHOO.util.Event.addListener(element, 'mouseover', showAsEditable);
	YAHOO.util.Event.addListener(element, 'mouseout', showAsNotEditable);
}

function enableConstantValueEditingForConditionValueClass(className) {
    var element = YAHOO.util.Dom.get("condition_editor_content");
    var elems = getElementsByClassName(className,"",element);

    for(var e in elems) {
    		enableConstantValueEditingForConditionValue(elems[e]);
    }
}

function conditionValueEditHandler(e){
	YAHOO.util.Event.preventDefault(e);
	YAHOO.util.Event.stopPropagation(e);
	
	var target = (e.srcElement) ? e.srcElement : e.target;

    setupConditionValuePanel(target);
    constantValuePanel.show();
}

function setupConditionValuePanel(target) {
    constantValuePanel.target = target;
    YAHOO.util.Dom.get("constant").value = target.innerHTML;
}

function setClauseXPathMapping(source, target) {
	var sid = source.id;
	var tid = target.id;
	
	var path = tid.replace("clause.", "");
	
	ajaxSetConditionClauseXPath(conditionPanel.item.id, path, sid);
}