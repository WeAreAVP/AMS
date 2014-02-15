<%@ include file="_include.jsp"%>
<%@page import="java.util.List"%>
<%@page import="gr.ntua.ivml.mint.persistent.Mapping"%>
<%@page import="gr.ntua.ivml.mint.persistent.Organization"%>
<%@page import="net.sf.json.JSONObject"%>

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
	 body a.customicons { height: 2.0em;}
	 body a.ui-state-active { height: 2.0em; }
	 body .locked .ui-selectmenu-item-icon,  .shared .ui-selectmenu-item-icon, .finished .ui-selectmenu-item-icon { height: 24px; width: 24px; }
	 body .locked .ui-selectmenu-item-icon { background: url(images/locked.png) 0 0 no-repeat; }
	 body .finished .ui-selectmenu-item-icon { background: url(images/complete.png) 0 0 no-repeat; }
	 body .shared .ui-selectmenu-item-icon { background: url(images/shared.png) 0 0 no-repeat; }
	 
</style>

<div style="width: 100%; height: 100%;margin-top:-20px;">
<s:if test="noitem==true">
     <h2>Transformation</h2>
	<s:iterator value="actionErrors">
				<font style="color:red;"><s:property escape="false" /> </font>
	</s:iterator>

</s:if>
<s:else>
<s:if test="selMapping!=null && selMapping>0">
 
	<s:if test="hasActionErrors()">
	  <div id="errortransform" style="margin-bottom:50px;">
	  <ul style="list-style-position:outside;margin-left:10px;margin-top:15px;">
	<s:iterator value="actionErrors">
				<li><font style="color:grey;size:0.9em;"><s:property escape="false" /> </font></li>
	</s:iterator>
	</ul>
	<%java.util.Collection<String> missing=(java.util.Collection)request.getAttribute("missing");
     java.util.Collection<String> invalid=(java.util.Collection)request.getAttribute("invalid");%>
	<p>Press 'Cancel' to go back and select different mappings for your transformation.</p>
	<p align="left">
		<a class="button" href="#" onclick="this.blur();transformPanelCancel();"><span>Cancel</span></a> 
		
		
	 <%if((missing==null || missing.size()==0) &&  (invalid!=null && invalid.size()>0)){%>
     
    	<a class="button" href="#" onclick="this.blur();ajaxBeginTransform( <%=(Long)request.getAttribute("selMapping")%>, true);"><span>Continue anyway</span></a> 
		
	 <%}%>
	 </p>
   </div>
  <% if((missing!=null && missing.size()>0) || (invalid!=null && invalid.size()>0)){
 %>
   <div id="previewTabs" class="yui-navset"> 
	    <ul class="yui-nav"> 
	        <li class="selected" title="active"><a href="#tab1"><em>Missing <%= Config.get("mint.title") %></em></a></li>
	        <li><a href="#tab2"><em>Invalid XPaths</em></a></li>  
	    </ul>             
	    <div class="yui-content"> 
	    
	         <div><p><div style="width: 100%; height: 200px; overflow-x: auto; overflow-y: auto">
	          <div style="width: 100%; overflow-y: auto" id="missingContainer">
			
					  <table id="missingTable">
				     <thead>
				     <tr>
				     <th>Missing XPaths</th>
				    </tr>
				    </thead>
				    <tbody>
				    <%java.util.Iterator<String> elIt = missing.iterator();
				       while (elIt.hasNext()) {%>
			    	     <tr>
			    	     <td> <%=elIt.next() %></td>
			    	   </tr>
			         <%} %>
				    
					</tbody>
					</table>
					</div></div>
	        </p></div>
	        <div><p><div style="width: 100%; height: 200px; overflow-x: auto; overflow-y: auto">
	          <div style="width: 100%; overflow-y: auto" id="invalidContainer">
					  <table id="invalidTable">
				     <thead>
				     <tr>
				     <th>Invalid XPaths</th>
				    </tr>
				    </thead>
				    <tbody>
				    <%elIt = invalid.iterator();
				       while (elIt.hasNext()) {%>
			    	     <tr>
			    	     <td> <%=elIt.next() %></td>
			    	   </tr>
			         <%} %>
				    
					</tbody>
					</table>
					</div>
					</div>
	        </p></div>
	    </div> 
	</div> 
	
   
   <%} %>
   
	</s:if>

</s:if>
<s:elseif test="selMapping==null || selMapping==0">
<h2>Transformation</h2>

<s:form name="Transform" action="Transform" cssClass="athform" theme="mytheme" enctype="multipart/form-data" style="width:100%;padding:0; margin-top:-10px;">
		<fieldset style="background-image: url(../images/spacer.gif);background-repeat: none;">
<p>Select the mappings that will be used for the transformation:</p>
	<ol>
	   
		<li style="background:none;">
		

		
			
			<%List accMappings=(List)request.getAttribute("accessibleMappings"); %>	
			<select name="selMapping" id="Transform_selMapping"  class="customicons">
			<option value="0">-- select mapping --</option>
			<%String sel=""; Organization lastorg=new Organization();
			  for(int i=0;i<accMappings.size();i++){
			   Mapping tempmap=(Mapping)accMappings.get(i);
			   if((Long)request.getAttribute("selMapping")-tempmap.getDbID()==0.0){
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
			   if(tempmap.isLocked(user, session.getId())){
				   cssclass+="locked";
			   }
			   if(tempmap.isFinished()){
				   cssclass+=" finished";
			   }
			   if(tempmap.isShared()){
				   cssclass+=" shared";
			   }
			  %> 
			 <option value="<%=tempmap.getDbID() %>" class="<%=cssclass %>" <%=sel%>><%=tempmap.getName() + ((tempmap.getTargetSchema() != null)?" (" + tempmap.getTargetSchema() + ")":"")%></option>
			     <% }%>
			   <%if(accMappings.size()>0){ %>
			  	          </optgroup>  
			      <%} %>
			</select>
			
		</li>
	
   <s:if test="hasActionErrors()">
   <li>
		<s:iterator value="actionErrors">
			<span class="errorMessage"><s:property escape="true" /> </span>
		</s:iterator>
	</li>
	</s:if>
	</ol>
	<div style="float:left;margin-bottom:15px;">
	<p align="left">
	
	<a class="button" href="#" onclick="this.blur();ajaxBeginTransform( document.Transform.selMapping.options[document.Transform.selMapping.selectedIndex].value,false); "><span>Submit</span></a>  
	</p>
	</div>
		</fieldset>
  <p><img src="images/locked.png" style="float:left; width:16px; margin-left: -5px;margin-right: 2px;"/><font  style="font-size:x-small;"><i>: Locked mappings </i></font>
	&nbsp;<img src="images/shared.png" style="float:center; width:22px; margin-left: -5px;margin-right: 2px;"/><font  style="font-size:x-small;"><i>: Shared mappings </i></font>
	&nbsp;<img src="images/complete.png" style=" width:16px; margin-left: -5px;margin-right: 2px;"/><font  style="font-size:x-small;"><i>: Complete mappings</i></font></p>
  <div style="font-size:0.9em"><p>&nbsp;*To select the correct mappings you can preview the transformed items per mapping by choosing the preview option <img
					src="images/webview.png" style="margin-left:-10px;vertical-align: top;padding-right:0px;"
					border="0">in the item panel</p>
  </div>
</s:form>
</s:elseif>
</s:else>
</div>

