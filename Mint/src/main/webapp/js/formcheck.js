//Javascript functions used throughout the website

//select values from lists


function selectValue(formObject, defValue)
{
	var lCurrentOptionValueStr;
       
	for (var i = 0; i < formObject.options.length; i++) {
		lCurrentOptionValueStr = "" + formObject.options[i].value;
       
		if (lCurrentOptionValueStr == defValue) {
			formObject.options[i].selected = true;
			formObject.selectedIndex = i;
		}
	}
}


function selectValues(formObject, defValues)
{
	var lCurrentOptionValueStr;
	for (var i = 0; i < formObject.options.length; i++) {
		lCurrentOptionValueStr = "" + formObject.options[i].value;
               for(var j=0;j<defValues.length;j++){
               
		if (lCurrentOptionValueStr == defValues[j]) {
			formObject.options[i].selected = true;
                        break
		
		}}
	}
}

function WithoutSelectionValue(ss) {
for(var i = 0; i < ss.length; i++) {
	if(ss[i].selected) {
		if(ss[i].value.length) { return false; }
		}
	}
return true;
}


function trim(string)
{
var re= /^\s*|\s*$/g;
return string.replace(re,"");
}

function clearElem(inputValue)
{
   
   if(inputValue.value.indexOf("<") != -1 || inputValue.value.indexOf(">") != -1)
       inputValue.value = "";
}


function toggleCheckAll(form,name,toggle) {
	var element;
	for (var i = 0; i < form.elements.length; i++) {
		element = form.elements[i];
		if (element.type == "checkbox" && element.name == name) element.checked = toggle.checked;
		}
}

function goToURL() {
  var i, args=goToURL.arguments; document.returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}

function clickElement(form,name,value) {
	var element;
	for (var i = 0; i < form.elements.length; i++) {
		element = form.elements[i];
		if (element.type == "checkbox" && element.name == name && element.value==value) element.click();
		}
}

function NoneWithCheck(ss) {
for(var i = 0; i < ss.length; i++) {
	if(ss[i].checked) { return false; }
	}
return true;
}

function WithoutCheck(ss) {
if(ss.checked) { return false; }
return true;
}

function WithoutSelectionValue(ss) {
for(var i = 0; i < ss.length; i++) {
	if(ss[i].selected) {
		if(ss[i].value.length) { return false; }
		}
	}
return true;
}

function hideloadingpage() { 
    if (document.getElementById) { // DOM3 = IE5, NS6 
        document.getElementById('hidepage').style.display='none';
        } 
    else { 
        if (document.layers) { // Netscape 4 
        document.hidepage.visibility = 'hidden'; 
        } 
    else { // IE 4 
        document.all.hidepage.style.visibility = 'hidden'; 
        } 
     } 
} 
function showloadingpage() { 
    if (document.getElementById) { // DOM3 = IE5, NS6 
        document.getElementById('showpage').style.display='none';
   //     document.getElementById('mess').style.display='none';
        document.getElementById('hidepage').style.display='';
    } 
    else { 
        if (document.layers) { // Netscape 4 
            document.showpage.visibility = 'hide'; 
       //     document.mess.visibility = 'hide'; 
            document.hidepage.visibility = 'show'; 
        } 
        else { // IE 4 
            document.all.hidepage.style.visibility = 'visible'; 
        } 
    } 
} 
function confirmDelete(jspname,message) {
     var response = confirm(message);
     if (response) {goToURL('self',jspname);}
         
}

function WithoutContent(ss) {
if(ss.length > 0) { return false; }
return true;
}

function NoneWithContent(ss) {
for(var i = 0; i < ss.length; i++) {
	if(ss[i].value.length > 0) { return false; }
	}
return true;
}

