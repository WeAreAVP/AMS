function imgSetup(){
}
/**
 * Custom button state handler for enabling/disabling button state.
 * Called when the carousel has determined that the previous button
 * state should be changed.
 * Specified to the carousel as the configuration
 * parameter: prevButtonStateHandler
 **/
var handlePrevButtonState = function(type, args) {

	var enabling = args[0];
	var leftImage = args[1];
	if(enabling) {
		leftImage.src = "images/left-enabled.gif";
	} else {
		leftImage.src = "images/left-disabled.gif";
	}

};

/**
 * Custom button state handler for enabling/disabling button state.
 * Called when the carousel has determined that the next button
 * state should be changed.
 * Specified to the carousel as the configuration
 * parameter: nextButtonStateHandler
 **/
var handleNextButtonState = function(type, args) {

	var enabling = args[0];
	var rightImage = args[1];

	if(enabling) {
		rightImage.src = "images/right-enabled.gif";
	} else {
		rightImage.src = "images/right-disabled.gif";
	}

};



function showCaption(myID) {
	document.getElementById(myID).style.display = 'block';
};

function hideCaption(myID) {
	document.getElementById(myID).style.display = 'none';
};

function setCarouselHeights() {

    var carouselLength = document.getElementById("carousel-list").childNodes.length;
    var ver = navigator.appVersion;
    var msie = (ver.indexOf("MSIE") != -1);

    for (i = 1; i < carouselLength; i++) {
        var y;
        var img = document.getElementById("item_" + i);
        y = img.height;
        if (y < 180){ y = 100;}
        document.getElementById("itemlb_" + i).style.marginTop = (100 - y) + "px";
    }
};

function getImageHeightIE(f) {
     var img = new Image();
     var height = 0;
     img.onload = function() {
            height = this.height;
            }
     img.src = f; // Starts the browser performing the operation which will trigger the onload
     return height;
};

function showRandomLogo(){
    var theImages = new Array();
    var theLogos = new Array("1","2","3","4","5","6","7");
    for (i = 0; i < theLogos.length; i++){
       theImages[i] = "think_culture_logo_top_"+theLogos[i]+".jpg";
    }
    var j = 0
    var p = theImages.length;
    var whichImage = Math.round(Math.random()*(p-1));
    document.write('<img src="images/'+theImages[whichImage]+'">');
};

function showRandomSlogan(size){
    var theImages = new Array();
    var theLanguages = new Array("cs","da","de","el","en","es","et","fi","fr","ga","hu","is","it","lt","mt","nl","en","fr","de","pl","pt","ro","sk","sl","sp","sv","en","fr","de","en","fr","de");
    for (i = 0; i < theLanguages.length; i++){
       theImages[i] = "think_culture_"+theLanguages[i]+".gif";
    }
    var j = 0
    var p = theImages.length;
    var whichImage = Math.round(Math.random()*(p-1));
    document.write('<img src="images/'+theImages[whichImage]+'">');
};

function showDefault(obj,iType){
    switch(iType)
    {
    case "TEXT":
      obj.src="images/icon-carousel-page.gif";
      break;
    case "IMAGE":
      obj.src="images/icon-carousel-image.gif";
      break;
    case "VIDEO":
      obj.src="images/icon-carousel-video.gif";
      break;
    case "SOUND":
      obj.src="images/icon-carousel-sound.gif";
      break;
    default:
      obj.src="images/icon-carousel-page.gif";
    }
};

$(document).ready(function() {
    if($.browser.msie) {
     $('#mycarousel').jcarousel({offset:8,wrap:'last'});
    } else {
      $('#mycarousel').jcarousel({wrap:'last'});
    }
});
