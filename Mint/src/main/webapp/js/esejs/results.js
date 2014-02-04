/* ________________BRIEF DOC_______________________*/


function showDefaultSmall(obj, iType) {
    if(obj && iType){
    	obj.src="images/item-image.gif";
        switch (iType)
                {
            case "TEXT":
                obj.src = "images/item-page.gif";
                break;
            case "IMAGE":
                obj.src = "images/item-image.gif";
                break;
            case "VIDEO":
                obj.src = "images/item-video.gif";
                break;
            case "SOUND":
                obj.src = "images/item-sound.gif";
                break;
            default:
                obj.src = "images/item-image.gif";
        }
    }
}
/*

function refineSearch(query,qf){
   $("input#query-get").val(query);
    var strqf = $("input#qf-get").val(qf.replace("&qf=",""));
    strqf = strqf.replace("&amp;","&");
    $("#form-refine-search").submit();
}*/

/* ________________FULL DOC_______________________*/
function showDefaultLarge(obj,iType){
	if(obj && iType){
		 obj.src="css/esecss/images/item-image-large.gif";
        switch(iType)
        {
        case "TEXT":
          obj.src="css/esecss/images/item-page-large.gif";
          break;
        case "IMAGE":
          obj.src="css/esecss/images/item-image-large.gif";
          break;
        case "VIDEO":
          obj.src="css/esecss/images/item-video-large.gif";
          break;
        case "SOUND":
          obj.src="css/esecss/images/item-sound-large.gif";
          break;
        default:
          obj.src="css/esecss/images/item-image-large.gif";
        }
    }
}
