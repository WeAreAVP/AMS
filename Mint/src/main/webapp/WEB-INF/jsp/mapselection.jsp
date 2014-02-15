<%@ include file="_include.jsp"%>
<%@page import="java.util.List"%>
<%@page import="gr.ntua.ivml.mint.persistent.XmlSchema"%>
<%@page import="gr.ntua.ivml.mint.persistent.Mapping"%>
<%@page import="gr.ntua.ivml.mint.persistent.Organization"%>
<%@page import="gr.ntua.ivml.mint.persistent.User"%>
<link rel="stylesheet" type="text/css" href="js/mapping/lib/yui/button/assets/skins/sam/button.css" />
<link rel="stylesheet" type="text/css" href="js/mapping/lib/yui/treeview/assets/skins/sam/treeview.css" />



<script type="text/javascript" src="js/mapping/lib/yui/yahoo/yahoo-min.js"></script>
<script type="text/javascript" src="js/mapping/lib/yui/event/event-min.js"></script>
<script type="text/javascript" src="js/mapping/lib/yui/dom/dom-min.js"></script>
<script type="text/javascript" src="js/mapping/lib/yui/treeview/treeview-min.js"></script>
<script type="text/javascript" src="js/mapping/lib/yui/element/element-min.js"></script>
<script type="text/javascript" src="js/mapping/lib/yui/dragdrop/dragdrop-min.js"></script>
<script type="text/javascript" src="js/mapping/lib/yui/resize/resize-min.js"></script>
<script type="text/javascript" src="js/mapping/lib/yui/animation/animation-min.js"></script>
<script type="text/javascript" src="js/mapping/lib/yui/layout/layout-min.js"></script>
<script type="text/javascript" src="js/mapping/lib/yui/button/button-min.js"></script>
<script type="text/javascript" src="js/mapping/lib/yui/container/container-min.js"></script>
<script type="text/javascript" src="js/mapping/lib/yui/connection/connection-min.js"></script>
<script type="text/javascript" src="js/mapping/lib/yui/yahoo-dom-event/yahoo-dom-event.js"></script>


<style type="text/css">

h2{margin-left:0;}

.ui-selectmenu-menu { float: left; margin-right: 10px; position:fixed; z-index: 9000;}


form.athform label {
	
		float:left;
	
		}
select { width: 200px;}

.ui-selectmenu-menu li a, .ui-selectmenu-status { padding: 0.3em 2em; }




/* select with custom icons */
	 body a.customicons { height: 2.0em;color:#CCC;}
	 body a.ui-state-active { height: 2.0em; color:#red;}
	 body .locked .ui-selectmenu-item-icon,  .shared .ui-selectmenu-item-icon, .finished .ui-selectmenu-item-icon { height: 24px; width: 24px; }
	 body .locked .ui-selectmenu-item-icon { background: url(images/locked.png) 0 0 no-repeat; }
	 body .finished .ui-selectmenu-item-icon { background: url(images/complete.png) 0 0 no-repeat; }
	 body .shared .ui-selectmenu-item-icon { background: url(images/shared.png) 0 0 no-repeat; }
	 
	 .menu2 {
	 top:0;left:auto;
    padding: 0;}
	 
	.menu2 a{
	  border: 0;
	}
	
	.menu2 a em {
    background: url("images/hover.png") no-repeat scroll 0 0 transparent;
    font-style: normal;
    font-weight: normal;
    color: black;
    height: 45px;
   
    top: 30px;
  
    padding: 20px 12px 10px;
    position: absolute;
    text-align: center;
    margin-left: -120px;
    display:none;
     float:left; 
    width: 180px;
    z-index: 2;
}

</style>
        

<div style="width: 100%; height: 100%; margin-top:-20px;">

<h2>Mappings</h2>

<s:if test="noitem==true">

	<s:iterator value="actionErrors">
				<font style="color:red;"><s:property escape="false" /> </font>
	</s:iterator>

</s:if>
<s:else>
<s:if test="editMapping!=null && editMapping>0 && mapsel!=null && !mapsel.equalsIgnoreCase('0') && missedMaps.size()>0 && hasActionErrors()">
  
	<s:iterator value="actionErrors">
				<font style="color:red;"><s:property escape="false" /> </font>
	</s:iterator>
	
	<div style="width: 100%; height: 250px; margin-top:5px; overflow: auto; background:#ffffff;">
	<%int count=0; %>
	<s:iterator value="missedMaps">
	<%count++; %>
				<span style="background: url('custom/images/formdiv3.gif') repeat-x scroll left bottom transparent;"><font style="0.9em;color:grey;"><b><%=count%>.</b>&nbsp;<s:property escape="false" /> </font></span><br/>
		</s:iterator>
	</div>		
	<p align="left">
		<a class="button" href="#" onclick="this.blur();restartMapping('<s:property value="mapsel"/>',<s:property value="editMapping"/>); "><span>Cancel</span></a> 
		
		<a class="button" href="#" onclick="this.blur();mappingRedirect(<%=request.getAttribute("editMapping") %>); "><span>Continue anyway</span></a> 
		</p>
	
</s:if>
<s:elseif test="editMapping==null || editMapping==0 || (editMapping!=null && editMapping>0 && hasActionErrors() && missedMaps.size()==0)">
<%
	    	 List<Mapping> templateMappings=(List)request.getAttribute("templateMappings");
	    	 List<Mapping> accMappings=(List)request.getAttribute("accessibleMappings");
	    	 List<Boolean> locks=(List)request.getAttribute("lockedmaps");
	    	 List<XmlSchema> schemas = (List<XmlSchema>) request.getAttribute("schemas");
	    	  String sel=""; %>	
<s:form name="mapform" action="Mapselection" cssClass="athform" theme="mytheme" enctype="multipart/form-data" style="width:100%;margin-top:-10px;">
	<fieldset style="background-image: url(../images/spacer.gif);background-repeat: none;">
	<p>&nbsp;Define new or edit existing mappings:</p>
	<ol>
	   
	    	

        <li>
        
<label for="selectedMapping">Select a mapping:</label>

			<select name="selectedMapping" id="selectedMapping"  class="customicons">
				<option value="0">-- No template --</option>
			  	<%Organization lastorg=new Organization();
			  	  int i=0;
			  	  for(Mapping tempmap:accMappings){
				    boolean lock=(Boolean)locks.get(i);
				   if(request.getAttribute("selectedMapping")!=null && (Long)request.getAttribute("selectedMapping")-tempmap.getDbID()==0.0){
					   sel="selected";
				   }
				   else{sel="";}
				   Organization current=tempmap.getOrganization();
				   if(lastorg!=null && current!=null && !lastorg.equals(current)){
					   if(i>0){%>
			    	     </optgroup>  
			           <%}
					   lastorg=current;
					   %>
				         <optgroup label="<%=lastorg.getEnglishName() %>">
				       <%
				     
				   }
				   
				   String cssclass="";
				   if(lock){
				     cssclass+="locked";
			       }
				   if(tempmap.isFinished()){
					   cssclass+="finished";
				   }
				   if(tempmap.isShared()){
					   cssclass+=" shared";
				   }
				   
				   if(tempmap.getUserID().toString().equals(user.getDbID().toString()) ){
				  %> 
				 <option value="<%=tempmap.getDbID() %>" class="<%=cssclass %>" <%=sel%>><%=tempmap.getName() %><% if(tempmap.getTargetSchema() != null) { out.print(" (" + tempmap.getTargetSchema().getName() + ")"); } %></option>
				   
				  
				
				  <% 
				   }
				  i++;
			  	  }%>
			  	  <%if(templateMappings.size()>0){ %>
			  	          </optgroup>  
			      <%} %>
			</select>
			<span class="menu2"><a href="javascript:void(0);" id="action_edit" alt="Edit mapping" title="Edit mapping"><img  height="30"  style="vertical-align:bottom;" src="images/editmaps.png"></a></span>
		    <!--<span class="menu2"><a href="javascript:void(0);" id="action_copy" title="Copy mapping" ><img  height="30"  style="vertical-align:bottom;" src="images/copymaps.png"></a></span>-->
			<!--<span class="menu2"><a href="javascript:void(0);" id="action_share" alt="Change share state" title="Change share state" ><img height="30"  style="vertical-align:bottom;" src="images/sharemaps.png"></a></span>-->
			<span class="menu2"><a href="javascript:void(0);" id="action_delete"  title="Delete mapping"><img  height="28"  style="vertical-align:bottom;" src="images/delmaps.png"></a></span>
			<span class="menu2"><a href="javascript:void(0);" id="action_download_mint" alt="Download MINT mapping" title="Download MINT mapping"><img height="29"  style="vertical-align:bottom;" src="images/downmaps.png"></a></span>
			<span class="menu2"><a href="javascript:void(0);" id="action_download_xsl" alt="Download XSL mapping" title="Download XSL"><img height="29"  style="vertical-align:bottom;" src="images/downxsl.png"></a></span>
		 	<span class="menu2"><a href="javascript:void(0);" id="action_add" alt="Add new mapping" title="Add new mapping"><img  height="30"  style="vertical-align:bottom;" src="images/addmaps.png"></a></span>
		  	<!--<span class="menu2"><a href="javascript:void(0);" id="action_upload" alt="Upload mapping" title="Upload mapping"><img  height="29"  style="vertical-align:bottom;" src="images/uploadmaps.png"></a></span>-->
		  <div id="fileoption" 
		  <s:if test="(mapsel!=null && !mapsel.equalsIgnoreCase('uploadmapping'))"><%out.print("style=\"display:none;\"");%></s:if>
		  >
		  
			<br/>First upload the mapping file:     <div id="uploadFile">  
		    <noscript>          
		        <p>Please enable JavaScript to use file uploader.</p>
		        
		    </noscript>         
		    </div>
		    <input type="hidden" id="upfile" name="upfile" value='<s:property value="upfile"/>'>
		     <input type="hidden" id="upmapname" name="upmapname" value='<s:property value="upmapname"/>'>
		    
		   
		    <s:if test="(upmapname!=null && upmapname.length()>0 && !upmapname.equalsIgnoreCase('undefined'))">
		    <div class="qq-uploader">
		    <ul class="qq-upload-list"><li class="qq-upload-success"><s:property value="upmapname"/></li></ul>
		    
		    </div>
		    </s:if>
		  </div>
		
		  <div id="nameoption" <s:if test="(mapsel!=null && (!mapsel.equalsIgnoreCase('createtemplatenew') && !mapsel.equalsIgnoreCase('createschemanew') && !mapsel.equalsIgnoreCase('uploadmapping')))"><%out.print("style=\"display:none;\"");%></s:if>><br/>
		  <s:textfield
			name="mapName" label="Mapping name" size="60px;margin-top:2px;" /> <font style="font-size: 10px;"><i>Give
		    the name of the new mapping</i></font>
		
		  </div>
		  
		  
		  <div id="schemaoption" <s:if test="(mapsel!=null && !mapsel.equalsIgnoreCase('createschemanew') && !mapsel.equalsIgnoreCase('uploadmapping') )"><%out.print("style=\"display:none;\"");%></s:if>><br/>
		  <label for="selectedMapping">Create with schema: </label>
		      <select id="schemaSel" name="schemaSel">
			  	<%
			  		String defaultSchema = Config.get("defaultSchema");
			  		for(XmlSchema schema: schemas) {
			  			String selected = "";
			  			if(defaultSchema != null && defaultSchema.equals(schema.getName())) {
			  				selected = "selected";
			  			} else {
			  				selected = "schema-name=\"" + schema.getName() + "\"";
			  			}
			  			%>
			  			<option <%= selected %> value="<%=schema.getDbID()%>"><%=schema.getName()%></option>
			  			<%
			  		}
			  	%>
			  </select>
			
		  </div>
	
		  </li></ol>
	<div id="buttonoption" <s:if test="(mapsel!=null && (!mapsel.equalsIgnoreCase('createtemplatenew') && !mapsel.equalsIgnoreCase('createschemanew') && !mapsel.equalsIgnoreCase('uploadmapping')))"><%out.print("style=\"display:none;\"");%></s:if>>
	<br/>
		<p align="left">
	
	       <a class="button" href="#" onclick="this.blur();mapselect='createtemplatenew';if($('div#schemaoption').is(':visible')){mapselect='createschemanew';}if($('div#fileoption').is(':visible')){mapselect='uploadmapping';}ajaxMappingSelectionRequest(mapselect,$('select#selectedMapping').val(),$('select#schemaSel').val(),<s:property value='uploadId'/>, document.mapform.mapName.value,document.mapform.upfile.value,document.mapform.upmapname.value); "><span>Submit</span></a>  
	    </p>
  
      
	  </div> 
	</fieldset>
	   <s:if test="hasActionErrors()"><div id="erroroption">
  <br/>
		<s:iterator value="actionErrors">
			<span class="errorMessage"><s:property escape="false" /> </span>
		</s:iterator></div>
	</s:if>
	<p><strong>Legend:</strong><br/>
	<img src="images/locked.png" style="float:left; width:16px; margin-left: -5px;margin-right: 2px;"/><font style="font-size:x-small;"><i>: Locked mappings </i></font>
		&nbsp;<img src="images/complete.png" style=" width:16px; margin-left: -5px;margin-right: 2px;"/><font  style="font-size:x-small;"><i>: Complete mappings</i></font>
	&nbsp;<img src="images/shared.png" style="float:center; width:22px; margin-left: -5px;margin-right: 2px;"/><font  style="font-size:x-small;"><i>: Mappings available to all users </i></font>
	</p>
</s:form>

</s:elseif>
<s:elseif test="editMapping!=null && editMapping>0 && mapsel!=null && !mapsel.equalsIgnoreCase('0') && missedMaps.size()==0">
	<s:if test="!hasActionErrors()">
	<div id="editredirect"><%=request.getAttribute("editMapping") %></div>	
	</s:if>

</s:elseif>
</s:else>
</div>

