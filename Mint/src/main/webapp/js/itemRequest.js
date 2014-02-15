

function ajaxItemPanel(from, limit, organizationId, uploadId, userId) {
if(uploadId==null){uploadId=-1};
    YAHOO.util.Connect.asyncRequest('POST', 'ItemPanel',
        { success: function(o) {
           
                document.getElementById("itemPanel").style.display="block";
    		
    		$("div[id=itemPanel]").html( o.responseText );
          },
            
          failure: function(o) {
			    // document.getElementById("showpanel").style.display="block";
    	
      		$("div[id=itemPanel]").html( "<b> Item retrieval failed </b>" );
          },
              
          argument: null
    }, "startItem=" + from + "&maxItems=" + limit + "&organizationId=" + organizationId+"&uploadId=" + uploadId +"&userId=" + userId );
}



function ajaxItemsSubmit(from, limit, organizationId, checks,faction) {
    YAHOO.util.Connect.asyncRequest('POST', 'ItemPanel',
        { success: function(o) {
    		$("div[id=itemPanel_"+organizationId+"]").html( o.responseText );
          },
            
          failure: function(o) {
      		$("div[id=itemPanel_"+organizationId+"]").html( "<b> Item retrieval failed </b>" );
          },
              
          argument: null
    }, "startItem=" + from + "&maxItems=" + limit + "&organizationId=" + organizationId +"&uploadId=" + uploadId +"&userId=" + userId+"&itemCheck="+checks+"&action="+faction );
}