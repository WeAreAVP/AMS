var thee = null;

function showAsEditable(e){
	YAHOO.util.Event.preventDefault(e);
	YAHOO.util.Event.stopPropagation(e);
	var target = (e.srcElement) ? e.srcElement : e.target;

	target.style.backgroundColor = "#ffffd3";
}

function showAsNotEditable(e){
	YAHOO.util.Event.preventDefault(e);
	YAHOO.util.Event.stopPropagation(e);
	var target = (e.srcElement) ? e.srcElement : e.target;

	target.style.backgroundColor = '';
}

function editHandler(e){
	YAHOO.util.Event.preventDefault(e);
	YAHOO.util.Event.stopPropagation(e);
	
	var target = (e.srcElement) ? e.srcElement : e.target;
	var form = '';
	
	var editElement = document.createElement('div');
	editElement.setAttribute('id', target.id + '_editor');
	form += '<textarea rows="1" id="' + target.id + '_edit" name="' + target.id + '" rows="4" cols="60">' + target.innerHTML + '</textarea>';
	form += '<br/><input id="' + target.id + '_save" type="button" ' + 'value="SAVE" /> OR <input id="' + target.id + '_cancel" type="button" ' + 'value="CANCEL" /></div>';
	editElement.innerHTML = form;
	
	var parent = target.parentNode;
	if(parent != null) {
		parent.appendChild(editElement);
	}

	YAHOO.util.Event.addListener(target.id + '_save', 'click', function(){
		// save value
		var e = YAHOO.util.Dom.get(target.id + '_editor');
		e.parentNode.removeChild(e);
		target.style.display = 'block';
	});

	YAHOO.util.Event.addListener(target.id + '_cancel', 'click', function()
	{
		var e = YAHOO.util.Dom.get(target.id + '_editor');
		e.parentNode.removeChild(e);
		target.style.display = 'block';
	});

	target.style.display = 'none';
}

function enableInPlaceEditing() {
	var elements = YAHOO.util.Dom.getElementsByClassName('editable');
	YAHOO.util.Event.addListener(elements, 'click', editHandler);
	YAHOO.util.Event.addListener(elements, 'mouseover', showAsEditable);
	YAHOO.util.Event.addListener(elements, 'mouseout', showAsNotEditable);
	//YAHOO.ext.EventManager.onDocumentReady(Init.init, Init, true);
}

function enableInPlaceEditingForElement(e) {
	YAHOO.util.Event.addListener(e, 'click', editHandler);
	YAHOO.util.Event.addListener(e, 'mouseover', showAsEditable);
	YAHOO.util.Event.addListener(e, 'mouseout', showAsNotEditable);
	//YAHOO.ext.EventManager.onDocumentReady(Init.init, Init, true);
}
