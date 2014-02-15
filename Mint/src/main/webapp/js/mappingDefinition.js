var mappingDefinitionPanel = new YAHOO.widget.Panel("mappingdefinition",
	{ width: "800px",
	  height: "500px",
	  fixedcenter: true,
	  constraintoviewport: true,
	  close: true,
	  draggable: false,
	  zindex: 4,
	  modal: true,
	  visible: false
	}
);

mappingDefinitionPanel.setHeader("Define/Edit Mappings");
mappingDefinitionPanel.setBody("<center>Loading...<br/><img src=\"images/rel_interstitial_loading.gif\"/></center>");
mappingDefinitionPanel.hideEvent.subscribe(mappingDefinitionPanelClose); 

var mappingDefinitionPanelOrgId = null;
var mappingDefinitionPanelUploadId = -1;
var mappingDefinitionPanelUserId = -1;
function mappingDefinitionPanelClose() {
	
}

function mappingRedirect(mapId) {
	mappingDefinitionPanelClose();
	window.document.location.href="DoMapping?uploadId="+mappingDefinitionPanelUploadId +"&mapid="+mapId;

}

function restartMapping(mapsel,mapid) {
	if(mapsel=='createtemplatenew'){
		ajaxMappingSelectionRequest('discardnewmap', '', '', 0,0,0,mapid,0,false,false);
	}
	else{
    	ajaxMappingDefinitionRequest(mappingDefinitionPanelUploadId, mappingDefinitionPanelOrgId, mappingDefinitionPanelUserId);
	}
}



function ajaxMappingDefinitionRequest(uploadId, orgId, userId) {
	mappingDefinitionPanelOrgId = orgId;  
	mappingDefinitionPanelUploadId=uploadId;
	mappingDefinitionPanelUserId=userId;
    mappingDefinitionPanel.setBody("<center>Loading...<br/><img src=\"images/rel_interstitial_loading.gif\"/></center>");
    
	//mappingDefinitionPanel.render(document.body);
	mappingDefinitionPanel.show();
   YAHOO.util.Connect.asyncRequest('POST', 'Mapselection_input.action',
        {
            success: function(o) {
    	        mappingDefinitionPanel.setBody(o.responseText);
    	    	$('select#selectedMapping').selectmenu({
    	    		style:'popup', 
    	    		menuWidth: "300px",   	
    	    		maxHeight: 400,
    	    		positionOptions: {
    	    			my: "left center",
    	    			at: "right center",
    	    			offset: "10 0"
    	    		},
    	    		
    	    	   });
    	    	
    	    	
    	    	$(".menu2 a").append("<em></em>");
    	    	
    	    	
    	    	$(".menu2 a").hover(function() {
        	    	
				 	$(this).find("em").show();
        	    	var hoverText = $(this).attr("title");

        	    	$(this).find("em").text(hoverText);
        	    	}, function() {
        	    		
        	    	$(this).find("em").css('display', 'none');
        	     	});
        	    	
    	    	

    	    	$(':[id^="action_"]').click(function () {
    	    		 $this = $(this);
    	    		 	$(this).find("em").css('display', 'none');
    	          	     
    	    		 $('div[id$="option"]').hide();
    	    		 if($this.attr("id").indexOf("add")==-1 && $this.attr("id").indexOf("upload")==-1 &&  $("select#selectedMapping").val()==0){
    	    			 
    	    				 alert("Please select a mapping first");
    	    			 
    	    		 }
    	    		 else{
	    	    		 if($this.attr("id").indexOf("download_mint")>0){
		    	    		 if($("select#selectedMapping").val()>0){
		    	    		 var url='Mapselection.action?mapsel=downloadmaps&selectedMapping='+$("select#selectedMapping").val()+'&uploadId='+uploadId; 
		    	    		 window.open(url,'Download');
		    	    		 }
		    	    		 else{ ajaxMappingSelectionRequest("downloadmaps",$("select#selectedMapping").val(),0,uploadId);}
    	    		 	 } else if($this.attr("id").indexOf("download_xsl")>0){
		    	    		 if($("select#selectedMapping").val()>0){
		    	    		 var url='Mapselection.action?mapsel=downloadxsl&selectedMapping='+$("select#selectedMapping").val()+'&uploadId='+uploadId; 
		    	    		 window.open(url,'Download');
		    	    		 }
		    	    		 else{ ajaxMappingSelectionRequest("downloadxsl",$("select#selectedMapping").val(),0,uploadId);}
		    	    	 }
	    	    		 else if($this.attr("id").indexOf("share")>0){
	    	    	
	    	    			 ajaxMappingSelectionRequest("sharemaps",$("select#selectedMapping").val(),0,uploadId);
	    	    			 
	    	    		 }
	    	    		 else if($this.attr("id").indexOf("delete")>0){
	    	    			 if (! confirm('Are you sure you want to delete the selected mappings?')){ return false;}
	    	    			 else{
	    	    			 ajaxMappingSelectionRequest("deletemaps",$("select#selectedMapping").val(),0,uploadId);}
	    	    			 
	    	    		 }
	    	    		 else if($this.attr("id").indexOf("edit")>0){
 	    	    			
	    	    			 ajaxMappingSelectionRequest("editmaps",$("select#selectedMapping").val(),0,uploadId);
	    	    			 
	    	    		 }
	    	    		 else if($this.attr("id").indexOf("copy")>0 || $this.attr("id").indexOf("add")>0 || $this.attr("id").indexOf("upload")>0){
	    	    		 
	    	    		     $("div#nameoption").show();
	    	    		     $("div#buttonoption").show();
	    	    		     $("div#schemaoption").hide();
	    	    		     if($this.attr("id").indexOf("add")>0){
	    	    		    	 $("select#selectedMapping").selectmenu("value", 0);
	    	    		    	 $("div#schemaoption").show();
	    	    		     }
	    	    		     if($this.attr("id").indexOf("upload")>0){
	    	    		        $("select#selectedMapping").selectmenu("value", 0);
    		    	    	  	 $("div#schemaoption").show();
    		    	    	  	$("div#fileoption").show();
    		    	    	  	createUploader();    
    	    		           }
	    	    		 }
    	    		 }
    	    	});
          	        
    	     },
            
            failure: function(o) {
            	mappingDefinitionPanel.setBody("<h1>Error</h1>");
            }
        }, "uploadId=" + uploadId);
    
   
}






function radioval(){
	var val = 0;
	
	for( i = 0; i < document.mapform.mapsel.length; i++ )
	{
	if( document.mapform.mapsel[i].checked == true ){
	val = document.mapform.mapsel[i].value;
	break;
	}
	}

	return val;
	}

function continueWithErrors(editMapping){
	 mappingDefinitionPanel.hide();
 	 
     mappingRedirect(editMapping);
}

var objobj = "";
function ajaxMappingSelectionRequest(mapsel, mapNameSchema, schemaSel, uploadId,newname,filename,upmapname) {
	
	mappingDefinitionPanel.setBody("<center>Loading...<br/><img src=\"images/rel_interstitial_loading.gif\"/></center>");
	mappingDefinitionPanel.show();
		
    YAHOO.util.Connect.asyncRequest('POST', 'Mapselection.action',
        {
            success: function(o) {
    			if(o.getResponseHeader["Content-Type"].indexOf("json") > 0) {
    				
    				mappingDefinitionPanel.hide();
    			} else if(o.responseText.indexOf('editredirect')==-1){
    				
    			     mappingDefinitionPanel.setBody(o.responseText);
    			   
    			     $('select#selectedMapping').selectmenu({
    	    	    		style:'popup', 
    	    	    		menuWidth: 300,   
    	    	    		positionOptions: {
    	    	    			my: "left center",
    	    	    			at: "right center",
    	    	    			offset: "10 0"
    	    	    		},
    	    	    		
    	    	    	   });	
    			  

    			 	$(".menu2 a").append("<em></em>");

    			 	$(".menu2 a").hover(function() {
            	    	
    				 	$(this).find("em").show();
            	    	var hoverText = $(this).attr("title");

            	    	$(this).find("em").text(hoverText);
            	    	}, function() {
            	    		
            	    	$(this).find("em").css('display', 'none');
            	    	});
    			 	
    			 	   if($('ul.qq-upload-list').html()==null)
    			 		   
    			     	{createUploader();}
    			 	

    			     $(':[id^="action_"]').click(function () {
    			    	 $this = $(this);
    			    	 $(this).find("em").css('display', 'none');  
        	    		 $('div[id$="option"]').hide();
        	    		 if($this.attr("id").indexOf("add")==-1 && $this.attr("id").indexOf("upload")==-1 && $("select#selectedMapping").val()==0){
        	    			 
        	    				 alert("Please select a mapping first");
        	    			 
        	    		 }
        	    		 else{
    	    	    		 if($this.attr("id").indexOf("download")>0){
    		    	    		 if($("select#selectedMapping").val()>0){
    		    	    		 var url='Mapselection.action?mapsel=downloadmaps&selectedMapping='+$("select#selectedMapping").val()+'&uploadId='+uploadId; 
    		    	    		 window.open(url,'Download');
    		    	    		 }
    		    	    		 else{ ajaxMappingSelectionRequest("downloadmaps",$("select#selectedMapping").val(),0,uploadId);}
    		    	    	 }
    	    	    		 else if($this.attr("id").indexOf("share")>0){
    	    	    	
    	    	    			 ajaxMappingSelectionRequest("sharemaps",$("select#selectedMapping").val(),0,uploadId,"");
    	    	    			 
    	    	    		 }
    	    	    		 else if($this.attr("id").indexOf("delete")>0){
    	    	    			 if (! confirm('Are you sure you want to delete the selected mappings?')){ return false;}
    	    	    			 else{
    	    	    			 ajaxMappingSelectionRequest("deletemaps",$("select#selectedMapping").val(),0,uploadId,"");}
    	    	    			 
    	    	    		 }
    	    	    		 else if($this.attr("id").indexOf("edit")>0){
    	    	    			
    	    	    			 ajaxMappingSelectionRequest("editmaps",$("select#selectedMapping").val(),0,uploadId,"");
    	    	    			 
    	    	    		 }
    	    	    		 else if($this.attr("id").indexOf("copy")>0 || $this.attr("id").indexOf("add")>0  || $this.attr("id").indexOf("upload")>0){
    	    	    		
    	    	    		     $("div#nameoption").show();
    	    	    		     $("div#buttonoption").show();
    	    	    		     $("div#schemaoption").hide();
    	    	    		     if($this.attr("id").indexOf("add")>0){
    	    	    	    	         $("select#selectedMapping").selectmenu("value", 0);
    	    		    	    	  	 $("div#schemaoption").show();
    	    	    		     }
    	    	    		     if($this.attr("id").indexOf("upload")>0){
    	    	    		    	 
	    	    	    	         $("select#selectedMapping").selectmenu("value", 0);
	    		    	    	  	 $("div#schemaoption").show();
	    		    	    	  	$("div#fileoption").show();
	    		    	    	  	if($('ul.qq-upload-list').html()!=null){
	    		    	    	  	$('ul.qq-upload-list').remove();}
	    		    	    	  	createUploader();    
	    	    		           }
    	    	    		     
    	    	    		 }
        	    		 }
        	    	});
    			     
    			     
    		       				}
    	        else
    	        {   mappingDefinitionPanel.hide();
    			
    	        	mappingDefinitionPanel.setBody(o.responseText);
        	        mappingRedirect(document.getElementById("editredirect").innerHTML);	}
                
            },
            
            failure: function(o) {
            	mappingDefinitionPanel.setBody("<h1>Error</h1>");
            }
        }, "mapsel=" + mapsel+"&selectedMapping="+mapNameSchema+"&schemaSel="+schemaSel+"&uploadId="+uploadId+"&mapName="+newname+"&upfile="+filename+"&upmapname="+upmapname);
    
   
}