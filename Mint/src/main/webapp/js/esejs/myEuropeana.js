$(document).ready(function() {
       $("#savedItems").tabs({selected: $.cookie('ui-tabs-3')});
       $("#savedItems").tabs({ cookie: { expires: 30 } });
    });

    function removeRequest(className, id){
        $.ajax({
           type: "POST",
           url: "remove.ajax",
           data: "className="+className+"&id="+id,
           success: function(msg){
                window.location.reload();
           },
           error: function(msg) {
                alert("An error occured. The item could not be removed");
           }
         });
    };

    function addEditorItemRequest(className, id){
        $.ajax({
           type: "POST",
           url: "save-editor-item.ajax",
           data: "className="+className+"&id="+id,
           success: function(msg){
                window.location.reload();
           },
           error: function(msg) {
                alert("An error occured. The item could not be removed");
           }
         });
    };


     function showDefault(obj,iType){
        switch(iType)
        {
        case "TEXT":
          obj.src="images/item-page.gif";
          break;
        case "IMAGE":
          obj.src="images/item-image.gif";
          break;
        case "VIDEO":
          obj.src="images/item-video.gif";
          break;
        case "SOUND":
          obj.src="images/item-sound.gif";
          break;
        default:
          obj.src="images/item-page.gif";
        }
     }
