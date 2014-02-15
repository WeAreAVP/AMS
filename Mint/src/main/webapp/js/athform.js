if( document.addEventListener ) document.addEventListener( 'DOMContentLoaded', athform, false );
  
function athform(){
  if( $( 'form.athform' ) == null) return;
  // Hide forms
  $( 'form.athform' ).hide().end();


  // Processing
  $( 'form.athform' ).find( 'label' ).not( '.noath' ).each( function( i ){
	
    var labelContent = this.innerHTML;
    var labelWidth = document.defaultView.getComputedStyle( this, '' ).getPropertyValue( 'width' );
    var labelSpan = document.createElement( 'span' );
        labelSpan.style.display = 'block';
        labelSpan.style.width = labelWidth;
        labelSpan.innerHTML = labelContent;
    this.style.display = '-moz-inline-box';
    this.innerHTML = null;
    this.appendChild( labelSpan );
  } ).end();
  
  // Show forms
  $( 'form.athform' ).show().end();
}


function ShowHideLayer(boxID) {
	/* Obtain reference for the selected boxID layer and its button */
	var box = document.getElementById("box"+boxID);
	var boxbtn = document.getElementById("btn"+boxID);
	
	/* If the selected box is currently invisible, show it */
	if(box.style.display == "none" || box.style.display=="") {
		box.style.display = "inline";
		if(boxbtn!=null){
 		boxbtn.src = "images/coll.gif";
		boxbtn.title="hide";
		boxbtn.alt="hide";}
		box.scrollIntoView( true );
	}
	/* otherwise hide it */
	else {
		box.style.display = "none";
		if(boxbtn!=null){
		boxbtn.src = "images/exp.gif";
		boxbtn.title="show";
		boxbtn.alt="show";}
	}
}

function ShowHideLayer(boxID,scroll) {
	/* Obtain reference for the selected boxID layer and its button */
	var box = document.getElementById("box"+boxID);
	var boxbtn = document.getElementById("btn"+boxID);
	
	/* If the selected box is currently invisible, show it */
	if(box.style.display == "none" || box.style.display=="") {
		box.style.display = "inline";
		if(boxbtn!=null){
 		boxbtn.src = "images/coll.gif";
		boxbtn.title="hide";
		boxbtn.alt="hide";}
		box.scrollIntoView( scroll );
	}
	/* otherwise hide it */
	else {
		box.style.display = "none";
		if(boxbtn!=null){
		boxbtn.src = "images/exp.gif";
		boxbtn.title="show";
		boxbtn.alt="show";}
	}
}


function ShowLayer(boxID) {
	/* Obtain reference for the selected boxID layer */
    var box = document.getElementById("box"+boxID);
	var boxbtn = document.getElementById("btn"+boxID);
	box.style.display = "inline";
		if(boxbtn!=null){
 		boxbtn.src = "images/coll.gif";
		boxbtn.title="hide";
		boxbtn.alt="hide";}	
}

function ShowGroupLayer(nm,id) {
	/* Obtain reference for the selected boxID layer */
	//var dvs=eval('document.getElementsByName('+nm+')');
	var dvs=document.getElementsByName(""+nm);
	for(var i=0;i<dvs.length;i++)
	{
	 dvs.item(i).style.display = "none";
	}
    var box = document.getElementById(nm+id);
	box.style.display = "inline";
	
}

function ShowGroupLayer(name,id,groupnum) {
	
	try{
	for(var i=1;i<=groupnum;i++){
	  var dvs=document.getElementById(name+i);
	  
	  if(i==id){
	    dvs.style.display = "inline";
	  }else{
	  dvs.style.display = "none";
	  } 
	}
	}catch(err){}
	
}

function HideLayer(boxID) {
	/* Obtain reference for the selected boxID layer */
	var box = document.getElementById("box"+boxID);
	box.style.display = "none";
	
}

function selectTab(tabID,maxtabs) {
	for(z=0; z<=maxtabs;z++){
	
		     var tabsel = document.getElementById("tabsel"+z);
			 var tablabel = document.getElementById("tablabel"+z);
	         var tablabelsel = document.getElementById("selectedtab"+z);
			 if(tabID==z){     
				    tabsel.style.display = "inline";
				    tabsel.focus();
					tablabel.style.display="none";
	 				tablabelsel.style.display="inline";}
			 else{  tabsel.style.display = "none";
					tablabel.style.display="inline";
	 				tablabelsel.style.display="none";}
	}
}



function alterTab(tabID,tbname,maxtabs) {

	try{
	
	for(z=0; z<=maxtabs;z++){
	
	
		     var tabsel = document.getElementById("tabsel"+tbname+z);
			 var tablabel = document.getElementById("tablabel"+tbname+z);
	         var tablabelsel = document.getElementById("selectedtab"+tbname+z);
			 if(tabID==z){     
				    tabsel.style.display = "inline";
				    tabsel.focus();
					tablabel.style.display="none";
	 				tablabelsel.style.display="inline";}
			 else{  tabsel.style.display = "none";
					tablabel.style.display="inline";
	 				tablabelsel.style.display="none";}
	 				
	}
	
	}catch(e){}
}




function anotherBlock( divId, pageComplete, count) {
    var num = document.getElementById(count).value;
   
	var protBlock = document.getElementById( divId  );
	var newBlock = protBlock.cloneNode( true );
	newBlock.style.display = "";	
  
    newBlockNum=eval(num)+1; 
	newBlock.id = divId+"_"+newBlockNum;
    
    document.getElementById(count).value=newBlockNum;
	var href1=document.createElement('a');
   
    href1.setAttribute('href',"javascript:removeBlock('"+newBlock.id+"')");
    
    var img1=document.createElement('img');
    img1.setAttribute('src','images/minus.gif');
    img1.setAttribute('width','20');
    img1.setAttribute('height','20');
    
    img1.setAttribute('border','0');
	img1.setAttribute('alt','remove');
	img1.setAttribute('title','remove');

    href1.appendChild(img1);

    newBlock.insertBefore(href1,newBlock.firstChild);
   //protBlock.parentNode.appendChild( newBlock );
	protBlock.parentNode.appendChild( newBlock );

   // var newHtml = newBlock.innerHTML.replace( /_1_/g, "_"+newBlockNum+"_" );
     var newHtml = newBlock.innerHTML.replace( /\[1\]/g, "["+newBlockNum+"]" );
    
    // var newHtml = newBlock.innerHTML.replace( new RegExp("_"+replacenum+"_","g"), "_"+newBlockNum+"_" );
    
    newBlock.innerHTML=newHtml;   
    
	if( pageComplete ) {
		//adjustColStretch();
		newBlock.scrollIntoView( true );
  }

}

function removeBlock( elemid ) {
 elem=document.getElementById(elemid);
 
  var blocknode = elem.parentNode;
  blocknode.removeChild( elem );
  
}

function addBlock( divId, pageComplete,count ) {

	var protBlock = document.getElementById( divId  );
	var newBlock = protBlock.cloneNode( true );
	newBlock.style.display = "";	
    var num=document.getElementById(count).value;
	newBlockNum=eval(num)+1; 
	newBlock.id = divId+"["+newBlockNum+"]";
    document.getElementById(count).value=newBlockNum;

	protBlock.parentNode.appendChild( newBlock );

    var newHtml = newBlock.innerHTML.replace( /\[0\]/g, "["+newBlockNum+"]" );
    
    newBlock.innerHTML=newHtml;   
   if( pageComplete ) {
		
		newBlock.scrollIntoView( true );
  }
   
}

function removeMlingual( elemid,count ) {
  elem=document.getElementById(elemid); 
  var blocknode = elem.parentNode;
  blocknode.removeChild( elem );
  var num=document.getElementById(count).value;
  document.getElementById(count).value=num-1;  
}

function addMlingual( divId, pageComplete,count ) {
 var num=document.getElementById(count).value;
 
 if(num<=1){
	var protBlock = document.getElementById( divId  );
	var newBlock = protBlock.cloneNode( true );
	newBlock.style.display = "";	
    //var num=document.getElementById(count).value;
	newBlockNum=eval(num)+1; 
	newBlock.id = divId+"["+newBlockNum+"]";
    document.getElementById(count).value=newBlockNum;

	protBlock.parentNode.appendChild( newBlock );

    var newHtml = newBlock.innerHTML.replace( /\[0\]/g, "["+newBlockNum+"]" );
    
    newBlock.innerHTML=newHtml;   
   if( pageComplete ) {
		
		newBlock.scrollIntoView( true );
  }
  }
}


function anotherLevel( divId, pageComplete, count,level) {
    var num = document.getElementById(count).value;

	var protBlock = document.getElementById( divId  );
	var newBlock = protBlock.cloneNode( true );
	newBlock.style.display = "";	
  
    newBlockNum=eval(num)+1; 
    if(level==1){
      var newdivId=divId.replace( /_0_/g, "_"+newBlockNum+"_" );
	   newBlock.id = newdivId;
    }else if (level>1){
     var numtop = document.getElementById('count_'+(level-1)).value;
  
     var newdivId=divId.replace( /_0_/g, "_"+numtop+"_" );
     newBlock.id = newdivId.replace( /_0/g, "_"+newBlockNum );
  
    }
    document.getElementById(count).value=newBlockNum;
	
	protBlock.parentNode.appendChild( newBlock );
  if(level==1){
    var newHtml = newBlock.innerHTML.replace( /_0_/g, "_"+newBlockNum+"_" );}
  else if(level>1){
      var num = document.getElementById('count_'+(level-1)).value;
      var newHtml = newBlock.innerHTML.replace( /_0_/g, "_"+num+"_" );
      newBlock.innerHTML=newHtml; 
      var newHtml = newBlock.innerHTML.replace( /_0/g, "_"+newBlockNum );
   }
    
    newBlock.innerHTML=newHtml;   
    
	if( pageComplete ) {
		//adjustColStretch();
		newBlock.scrollIntoView( true );
  }

}

function anotherTheme( pageComplete,count ) {

	var protBlock = document.getElementById( "theme0");
	var newBlock = protBlock.cloneNode( true );
	newBlock.style.display = "";	
	 var num=document.getElementById(count).value;
	newBlockNum=eval(num)+1; 
	newBlock.id = "theme"+newBlockNum;
    document.getElementById(count).value=newBlockNum;

	protBlock.parentNode.appendChild( newBlock );

     var newHtml = newBlock.innerHTML.replace( new RegExp("theme0","g"), "theme"+newBlockNum );
    
    newBlock.innerHTML=newHtml;   
   if( pageComplete ) {
		
		newBlock.scrollIntoView( true );
  }
   
}

function checkUncheckAll(theElement) {
  var theForm = theElement.form, z = 0;
  for(z=0; z<theForm.length;z++) {
    if(theForm[z].type == 'checkbox' && theForm[z].name != 'checkall') {
      theForm[z].checked = theElement.checked;
    }
  }
}

// Check if any checkbox below given element is checked
function subChecked( elem ) {
	if ((elem.tagName == "INPUT") && (elem.type == "checkbox" )) {
		if( elem.checked ) { return true };
	}
	for( var child in elem.childNodes ) {
		if( subChecked( elem.childNodes[child] )) {
		  return true;
		}
	}
	return false;  
}

// Disable form elements below the given
// dont disable exceptions (hash of ids)
function subDisable( elem, exceptions ) {
	if( !elem ) return;
	if( elem.tagName ) {
		// alert( "Disable " + elem.tagName + " " + elem.id +"<br/>" );
	}
	if(! ( exceptions && exceptions[elem.id])) {
		if ( elem.tagName == "INPUT" ) {
			elem.disabled = true;
			return;
		}
		if ( elem.tagName == "SELECT" ) {
			elem.disabled = true;
			return;
		}
		if ( elem.tagName == "TEXTAREA" ) {
			elem.readOnly = true;
			return;
		}
		if ( elem.tagName == "A"){ 
		if(elem.href.match("javascript:openList")=="javascript:openList") {
		
		    elem.href=null;
		    elem.innerHTML="";
			
			return;
		}}
	}
	for( var child in elem.childNodes ) {
		subDisable( elem.childNodes[child], exceptions )
	}
	return;
}

function restoreMain(){
     var maindiv=document.getElementById('main');
     maindiv.style.paddingTop='0';
     }


function padMain(){
     var maindiv=document.getElementById('main');
     maindiv.style.paddingTop='4em';
     
}

function initPage(tab,subtab){

  if(tab!=null){
  document.getElementById(tab).display="inline";
  document.getElementById(tab).zIndex="100";}
  
  if(subtab!=null){
  document.getElementById(subtab).display="inline";
  
  }
 
}

//uncheck all checkboxes in div
function checkNone(divId)
{
var cBoxes = document.getElementById( divId  );
var boxes = cBoxes.getElementsByTagName("input");
for (var i = 0; i < boxes.length; i++) {
myType = boxes[i].getAttribute("type");
if ( myType == "checkbox") {
boxes[i].checked=0;
}
}
}

// Helper Functions (May need to merge similar functions)
//////////////////////////////////////////////////////////////////////////////
function ss(selectID,selectValue)
{	
	//alert(selectID);
	selObj=document.getElementById(selectID);
	if(selObj)
	{
	for (var i=0;i<selObj.options.length;i++)
	{
		if(selObj.options[i].value==selectValue)
		{
			//alert(i+"   "+selectID);
			selObj.options[i].selected=true;
			selObj.options.selectedIndex=i;
			
		}
		else
		{
			//selObj.options[i].selected=false;
		}
	}
	}
}
function srbc(rName,rValue)
{	
	for (i=0; i<document.getElementsByTagName('input').length; i++)
	{
		if (document.getElementsByTagName('input')[i].type == 'radio')
		{
			rb=document.getElementsByTagName('input')[i];
			if(rb.value==rValue) rb.checked=true;
			
		}
	}
}
function scb(blockName,value)
{	

	for (i=0; i<document.getElementsByTagName('input').length; i++)
	{
		if (document.getElementsByTagName('input')[i].type == 'checkbox')
		{
		
			e=document.getElementsByTagName('input')[i];
			eName=e.name;
			
			if(eName.substring(0,blockName.length)==blockName)
			{
				if(e.value==value) e.checked=true;
				//alert("("+value+")"+e.name+" "+e.value);
			}
		}
	}
	
}
function zs(k,v)
{
	var elem = document.getElementById(k);
	if( elem ) {
	  if( elem.options ) {
	    for (var i=0;i<elem.options.length;i++) {
		  if(elem.options[i].value==v) {
			elem.options[i].selected=true;
			elem.options.selectedIndex=i;
		  }
		}
	  } else {
		elem.value = v;
	  }
	}
}

function goToURL() {
  var i, args=goToURL.arguments; document.returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}

function confirmDelete(jspname,mess) {
     var response = confirm(mess);
     if (response) {goToURL('self',jspname);}
         
}

function confirmDel(mess) {
     var response = confirm(mess);
     
     return(response);
         
}

function submitenter(myfield,e)
{
	var keycode;
	if (window.event) keycode = window.event.keyCode;
	else if (e) keycode = e.which;
	else return true;
	
	if (keycode == 13)
	   {
	   myfield.form.submit();
	   return false;
	   }
	else
	   return true;
}

//////////////////////////////////////////////////////////////////////////////


