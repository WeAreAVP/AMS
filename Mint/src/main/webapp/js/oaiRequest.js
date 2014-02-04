


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
    
            YAHOO.example.container.wait.setHeader("Please wait...");
            YAHOO.example.container.wait.setBody("<img src=\"images/rel_interstitial_loading.gif\"/>");
            YAHOO.example.container.wait.render(document.body);

        }

      
        YAHOO.example.container.wait.show();
        
    }
    
  



function ajaxOAIValidate(oaiurl,action) {
	initwait();
    var wpanel = document.getElementById("waitpanel");
    var oaiset = document.getElementById("oaiset_span");
    var oains = document.getElementById("oains_span");
        YAHOO.util.Connect.asyncRequest('POST', 'OAIHandler',
        { success: function(o) {
    	    //wpanel.style.visibility = "visible"; 
	        YAHOO.example.container.wait.hide(); 
	        if(action=='validate')
    		$("span[id=oai_ch]").html(o.responseText);
	        else if(action=='fetchsets'){
	        	if(o.responseText.indexOf("oaiset")>-1)
	        			oaiset.innerHTML="<label style=\"display: -moz-inline-box;\" for=\"Import_oaiset\" class=\"label\"><span style=\"display: block; width: 150px;\">OAI SET:</span></label>"+(o.responseText);
	        	else
	                    oaiset.innerHTML="<label style=\"display: -moz-inline-box;\" for=\"Import_oaiset\" class=\"label\"><span style=\"display: block; width: 150px;\">OAI SET:</span></label>"+o.responseText;		
	        }
	        else if(action=='fetchns'){
	        	if(o.responseText.indexOf("oains")>-1)
	        			oains.innerHTML="<label style=\"display: -moz-inline-box;\" for=\"Import_oains\" class=\"label\"><span style=\"display: block; width: 150px;\">Namespace Prefix:</span></label>"+(o.responseText);
	        	else
	                    oains.innerHTML="<label style=\"display: -moz-inline-box;\" for=\"Import_oains\" class=\"label\"><span style=\"display: block; width: 150px;\">Namespace Prefix:</span></label>"+o.responseText;		
	        }
          },
            
          failure: function(o) {
        	  YAHOO.example.container.wait.hide(); 
        	 if(action=='validate') 
        	   $("span[id=oai_ch]").html("<font color=\"red\">Unable to test oai url</font>");
        	  else if(action=='fetchsets')
        		  oaiset.innerHTML+="<font color=\"red\">Unable to fetch OAI sets</font>";
        	  else if(action=='fetchns')
        		  oains.innerHTML+="<font color=\"red\">Unable to fetch OAI Namepsace Prefixes</font>";
          },
              
          argument: null
    }, "oai=" + oaiurl+"&action="+action);
}
