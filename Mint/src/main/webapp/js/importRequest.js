


    function initwait() {

        var content = document.getElementById("waitpanel");
        
        content.innerHTML = "";

        if (!YAHOO.example.container.wait) {

            // Initialize the temporary Panel to display while waiting for external content to load

            YAHOO.example.container.wait = 
                    new YAHOO.widget.Panel("wait",  
                                                    { width: "240px", 
                                                      fixedcenter: true, 
                                                      close: false, 
                                                      draggable: false, 
                                                      zindex:4,
                                                      modal: true,
                                                      visible: false
                                                    } 
                                                );
    
            YAHOO.example.container.wait.setHeader("Deleting, please wait...");
            YAHOO.example.container.wait.setBody("<img src=\"images/rel_interstitial_loading.gif\"/>");
            YAHOO.example.container.wait.render(document.body);

        }

      
        YAHOO.example.container.wait.show();
        
    }
    
  



function ajaxImportsPanel(from, limit, userId, orgId) {
    document.getElementById("importsPanel").style.display="block";
   
    YAHOO.util.Connect.asyncRequest('POST', 'ImportsPanel',
        { success: function(o) {
    		$("div[id=importsPanel]").html( o.responseText );
          },
            
          failure: function(o) {
      		$("div[id=importsPanel]").html( "<b> Imports retrieval failed </b>" );
          },
              
          argument: null
    }, "startImport=" + from + "&maxImports=" + limit + "&userId=" +userId+"&orgId=" +orgId );
}

function ajaxFetchStatus(importId) {
	YAHOO.util.Connect.asyncRequest('POST','ImportStatus', 
	    {success:function(o){
	       
	        if(o.responseText.indexOf('OK')==-1 && o.responseText.indexOf('ERROR')==-1 && o.responseText.indexOf('UNKNOWN')==-1 ){
	          var fnt="ajaxFetchStatus("+importId+")";
          	   setTimeout(fnt, 30000);
          	  $("span[id=import_stat_"+importId+"]").html(o.responseText); 
          	 /*execute every 30 secs*/
          	 }
          	 else if(o.responseText.indexOf('OK')>-1){
          		 if(document.getElementById("import_steps_"+importId)!=null){
          		 document.getElementById("import_steps_"+importId).style.display="block";
	          	 ajaxFetchTransformStatus(importId);}
	          	  $("span[id=import_stat_"+importId+"]").html(o.responseText); 
	          	  
          	 }
          	 else if(o.responseText.indexOf('ERROR')>-1){
          	  $("span[id=import_stat_"+importId+"]").html(o.responseText); 
          	 }
          	 else if(o.responseText.indexOf('UNKNOWN')>-1){
          		 /* can no longer find import in db*/
          		 
          	 }
          
        
	    }, 
	 
	    failure:function(o){ 
	       
	        $("span[id=import_stat_"+importId+"]").html("<B>?</B>");
	        /* Failure handler*/ 
	    }, 
	 
	     argument: null 
	
	},"importId="+importId);

}


function ajaxRefreshStatus(importId) {
	YAHOO.util.Connect.asyncRequest('POST','ImportStatus', 
	    {success:function(o){
	       
	         if(o.responseText.indexOf('OK')>-1){
          		 if(document.getElementById("import_steps_"+importId)!=null){
          		 document.getElementById("import_steps_"+importId).style.display="block";
	          	 }
	          	  $("span[id=import_stat_"+importId+"]").html(o.responseText); 
	          	  
          	 }
          	 
	    }, 
	 
	    failure:function(o){ 
	       
	        $("span[id=import_stat_"+importId+"]").html("<B>?</B>");
	        /* Failure handler*/ 
	    }, 
	 
	     argument: null 
	
	},"importId="+importId);

}

function ajaxFetchTransformStatus(importId) {
	YAHOO.util.Connect.asyncRequest('POST','TransformStatus', 
	    {success:function(o){
	   
	        if(o.responseText.indexOf('NOT DONE')==-1 && o.responseText.indexOf('OK')==-1 && o.responseText.indexOf('ERROR')==-1){
	         
	          var fnt="ajaxFetchTransformStatus("+importId+")";
          	   setTimeout(fnt, 30000);
          	 /*execute every 30 secs*/
          	 }
          	 else if(o.responseText.indexOf('OK')>-1 || o.responseText.indexOf('NOT DONE')>-1){
          	 document.getElementById("import_steps_"+importId).style.display="block"; 
          	 
          		ajaxRefreshStatus(importId);
          		 
          	 }
          	
         $("span[id=import_trans_"+importId+"]").html(o.responseText); 
         
	    }, 
	 
	    failure:function(o){ 
	       
	        $("span[id=import_trans_"+importId+"]").html("<B>?</B>");
	        // Failure handler 
	    }, 
	 
	     argument: null 
	
	},"importId="+importId);

}

function ajaxFetchPublicationStatus(importId,from, limit, userId, orgId) {
	YAHOO.util.Connect.asyncRequest('POST','PublishStatus', 
	    {success:function(o){
	        if(o.responseText.indexOf('NOT DONE')==-1 && o.responseText.indexOf('OK')==-1 && o.responseText.indexOf('ERROR')==-1){
	         
	          var fnt="ajaxFetchPublicationStatus("+importId+","+from+","+ limit+","+ userId+","+ orgId+")";
          	   setTimeout(fnt, 30000);
          	    $("span[id=import_pub_"+importId+"]").html(o.responseText); 
          	 /*execute every 30 secs*/
          	 }
	        else if(o.responseText.indexOf('ERROR')>-1 || o.responseText.indexOf('NOT DONE')>-1){
              ajaxImportsPanel(from, limit, userId, orgId);
         	 }
          	 else if(o.responseText.indexOf('OK')>-1){
         	 	 document.getElementById("import_pub_"+importId).style.display="block";
               $("span[id=import_pub_"+importId+"]").html(o.responseText); 
          	     ajaxImportsPanel(from, limit, userId, orgId);
          	 }
        }, 
	 
	    failure:function(o){ 
	       
	        $("span[id=import_pub_"+importId+"]").html("<B>?</B>");
	        // Failure handler 
	    }, 
	 
	     argument: null 
	
	},"importId="+importId);

}


function ajaxImportsSubmit(from, limit, userId, orgId, checks,faction) {
    initwait();
    var wpanel = document.getElementById("waitpanel"); 
    YAHOO.util.Connect.asyncRequest('POST', 'ImportsPanel',
        { success: function(o) {
            
	        wpanel.style.visibility = "visible"; 
	        YAHOO.example.container.wait.hide(); 
    		$("div[id=importsPanel]").html( o.responseText );
          },
            
          failure: function(o) {
            
	        wpanel.style.visibility = "visible"; 
	        wpanel.innerHTML = "<div id=\"message\" style=\"width: 500px;\">DELETION FAILED!</div>"; 
	        YAHOO.example.container.wait.hide(); 
      		$("div[id=importsPanel]").html( o.responseText );
          },
          timeout: 20000,   
          argument: null
    }, "startImport=" + from + "&maxImports=" + limit + "&userId=" +userId+"&orgId=" +orgId+"&uploadCheck="+checks+"&action="+faction );
}


function ajaxLockSummary(checks,faction) {
    YAHOO.util.Connect.asyncRequest('POST', 'LockSummary',
        { success: function(o) {
          YAHOO.example.container.wait.hide(); 
    		$("div[id=locksPanel]").html( o.responseText );
          },
            
          failure: function(o) {
            
	        wpanel.style.visibility = "visible"; 
	        wpanel.innerHTML = "<div id=\"message\" style=\"width: 500px;\">DELETION FAILED!</div>"; 
	        YAHOO.example.container.wait.hide(); 
      	  },
          timeout: 20000,   
          argument: null
    }, "&lockCheck="+checks+"&lockaction="+faction );
}