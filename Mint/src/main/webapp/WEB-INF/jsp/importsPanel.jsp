
<%@ taglib prefix="s" uri="/struts-tags" %>
<%@ page import="gr.ntua.ivml.mint.persistent.User" %>
<%@ page import="gr.ntua.ivml.mint.util.Config" %>
<%@ page import="gr.ntua.ivml.mint.db.DB" %>
<%@ page import="org.apache.log4j.Logger" %>

<style type="text/css">
form.athform fieldset li {
	background: url("custom/images/formdiv3.gif") left bottom repeat-x;
     }
     
     .yui-skin-sam .yui-panel{
	border-width: 1px 1px 1px 1px;
	padding:3px;
}

</style>
<%! public final Logger log = Logger.getLogger(this.getClass());%>

<script type="text/javascript">
 


function initLinkPanel( anchorId, panelId) {

    var timeout;
    var oAnchor = document.getElementById(anchorId);
    var oOverlay  = document.getElementById(panelId);
    
    // instantiate a new YUI panel object and render to the page
    
    var yuiPanel = new YAHOO.widget.Panel(panelId, {
        context: [anchorId, "bl", "tr",["beforeShow", "windowResize"], [10, 10 ]],
        draggable: false,
        visible: false,
        close: false,
    });
    
    yuiPanel.render();

    // define a function to hide the panel when the time delay is reached
    
    var timeoutFunc = function() {
        yuiPanel.hide();
    };

    /*
    When the anchor is moused over show the panel. Also clear the timeout in 
    the mouseover of the anchor or the panel if the timeout has been set in 
    a previous mouseout event.
    */
    YAHOO.util.Event.addListener(oAnchor, "mouseover", function(e) {
        yuiPanel.show();
        if (timeout) clearTimeout(timeout);
        timeout = setTimeout(timeoutFunc, 2000 );
    });
    
    YAHOO.util.Event.addListener(oAnchor, "mouseout", function(e) {
        if (timeout) clearTimeout(timeout);
        timeout = setTimeout(timeoutFunc, 500 );
    });
    
    YAHOO.util.Event.addListener(oOverlay, "mouseover", function(e) {
        if (timeout) clearTimeout(timeout);
    });


    YAHOO.util.Event.addListener(oOverlay, "mouseout", function(e) {
        if (timeout) clearTimeout(timeout);
        timeout = setTimeout(timeoutFunc, 500 );
    });
}

function toggleSliderImp(e){
	 slider=xGetElementById("sliderimpclass"+e);
	 container=xGetElementById("s"+e+"_impclass");

	 var anim = new YAHOO.util.Anim(slider,{height:{to:0}},0.2,YAHOO.util.Easing.easeIn);
	 if(slider.style.height=="0px"){
		 var anim = new YAHOO.util.Anim(slider,{height:{to:80}},0.2,YAHOO.util.Easing.easeOut);
		 container.className='impclassb';
	 } else {
		 container.className='impclass';
	 }
	 anim.animate();
}



function xGetElementById(e) {
	if(typeof(e)!='string') return e;
	if(document.getElementById) e=document.getElementById(e);
	else if(document.all) e=document.all[e];
	else e=null;
	return e;
}

function handleKeyPress(e) {  
	var code =(window.event)? event.keyCode : e.which;
	if(code==13) {return false;}
	
  }  
  document.onkeydown= handleKeyPress;  
</script>
<%int importcount=0;
User user=(User) request.getSession().getAttribute("user");
if( user != null ) {
	user = DB.getUserDAO().findById(user.getDbID(), false );
}
long oid=(Long)request.getAttribute("orgId");
%>

<table border="0"  cellpadding="0" cellspacing="0" width="398">
	<tr><td height="10px;">&nbsp;</td></tr>
		<tr><td>
		<span class="rounded_top">Imports</span>
		</td></tr>
		
		
		<tr><td style="background: url(custom/images/grey.gif);" align="left">

<form name='imports_<s:property value="userId"/>_<s:property value="orgId"/>' class="athform" style="width:396px;border: solid 1px silver;margin:0;padding:0;align:left;">
<fieldset>

<div style="padding:5px;background: #FFFFFF;font-size:100%;">
	&nbsp;<b>Organization: </b><s:select theme="simple"  cssStyle="width:150px"  name="filterorg"  id="filterorg" list="organizations" listKey="dbID" listValue="name" value="%{o.dbID}"  onChange="javascript:ajaxImportsPanel(0,5,-1,document.getElementById('filterorg').value);"></s:select>
	
	<a  href="Import_input.action?uploaderOrg=<s:property value="orgId"/>"><img src="images/upload2.png" width="20" title="Start new Import" style="padding-left:2px;"></a>
	<a onclick="javascript:ajaxItemPanel(0, 10, <s:property value="orgId"/>, -1,<s:property value="userId"/>);" href="#" class="" title="show all items for organization">
										<img style="vertical-align: middle;padding-left:2px; " src="images/items.png"></a>
	 <%if( (user.getOrganization() == null && user.hasRight(User.SUPER_USER)) || user.hasRight(User.ADMIN)|| (user.hasRight(User.PUBLISH) && user.getOrganization().getDbID()==oid)) {%>
			      
			   <%@ include file="../custom/jsp/publish.jsp" %>                
              
			<%} %>	
	 <s:if test="pub!=null && (pstatus=='ERROR' || pstatus=='OK')">
				    <a onclick="javascript:ajaxReportPreview(<s:property value="orgId"/>)" href="#"><img id="pub_stat<s:property value="pub.dbID"/>" src='<s:property value="pstatusIcon"/>' style="vertical-align:middle;padding-left: 2px;" title="PUBLICATION <s:property value="pstatus"/>"></a>
					<script>
					var tooltiptrans<s:property value="pub.dbID"/> = new YAHOO.widget.Tooltip("tooltiptrans<s:property value="pub.dbID"/>", { context:"pub_stat<s:property value="pub.dbID"/>" , text:"<s:property value="pub.statusMessage"/> Click to view report.", width:"400px"} );
					</script> 
				
	 </s:if>
	 <%if( (user.getOrganization() == null && user.hasRight(User.SUPER_USER)) || user.hasRight(User.ADMIN)|| (user.hasRight(User.MODIFY_DATA) && user.getOrganization().getDbID()==oid)) {%>
	    
		<s:if test="pub!=null && pstatus=='OK' &&  pubzippedOutput!=null">
	     <a href="download.action?orgId=<s:property value="orgId"/>&published=true"> <img src="custom/images/down.png" width="33"   title="Download Published" style="padding-left:1px;"></a>
	     
		</s:if>
		
		  
	<%} %>
	
</div>

<div style="padding:3px;background: #e2e2e2;font-size:100%;">
	&nbsp;<b>Imports by user:</b> <s:select theme="simple"  name="filteruser"  list="%{o.uploaders}" headerKey="-1" headerValue="-- All uploaders --" listKey="dbID" listValue="name" value="%{u.dbID}"  onChange="javascript:ajaxImportsPanel(0,5,document.getElementById('filteruser').value,${orgId});"></s:select>
</div>
<s:if test="hasActionMessages()">
		<s:iterator value="actionMessages">
			<div id="message<s:property value="userId"/>_<s:property value="orgId"/>" style="width: 390px;height:20px;padding:3px;"><font color="red"><s:property escape="false" /> </font></div>
		</s:iterator>
	</s:if>

		 <s:if test="imports.size>0">
		 <div style="padding:5px;font-size:1em;">View all available actions by <b>clicking on an import name</b></div>
         <ol>
		 <s:iterator id="impt" value="imports">
		<%
		importcount++; %>
						<li style="padding: 0px;padding-bottom: 3px;">
				 
						<table border="0" cellspacing="0" cellpadding="0" width="100%">
                             <tr background="custom/images/sprite.png"> 
			                <td width="15">
			                    <!--delete option for admin,superuser-->		
			           <%if( (user.getOrganization() == null && user.hasRight(User.SUPER_USER)) || user.hasRight(User.ADMIN)) {%>
			           <!-- published can not be deleted before unpublished -->
		      			<s:if test="pub.status=='NOT DONE'">
			            <s:checkbox label="ch_imp1" name="uploadCheck" value="aBoolean" fieldValue="%{dbID}" theme="simple"/>
			            </s:if>
			            <s:elseif test="pub.status=='OK'">
			             <img title="Published import" src="custom/images/publish.gif" style="padding-left:5px;">
			            
			            </s:elseif>
			            <%}else if(user.hasRight(User.MODIFY_DATA)){ %>
			             <s:if test="uploader==user.dbID && pub.status=='NOT DONE'">
			            <s:checkbox label="ch_imp1" name="uploadCheck" value="aBoolean" fieldValue="%{dbID}" theme="simple"/>
			            </s:if>
			            <s:elseif test="pub.status=='OK'">
			             <img title="Published import" src="custom/images/publish.gif" style="padding-left:5px;">
			            
			            </s:elseif>
			            <%} %>
			                    </td>
			                       <td width="34"> 
			                         <a onClick="javascript:toggleSliderImp(<s:property value="dbID"/>);" style="color:#000;cursor:pointer">
			                          <s:if test="oai!=''">
			                          <img src="images/oai_symbol.png" style="vertical-align:middle;" title="<s:property value="fullOai"/>">
			                          </s:if>
			                          <s:elseif test="noOfFiles>1">
									<img src="images/zipfile.png" width="34"; style="vertical-align:middle;">
									</s:elseif>
									<s:elseif test="zip" >
									<img src="images/zipfile.png" width="34" style="vertical-align:middle;">
									</s:elseif>
									<s:elseif test="excel" >
									<img src="images/excelpic.png" style="vertical-align:middle;">
									</s:elseif>
									<s:elseif test="oai!=''" >
									<img src="images/oaipic.png" style="vertical-align:middle;">
									</s:elseif>
									<s:else >
									<img src="images/xml2.png" width="34" style="vertical-align:middle;">
									</s:else>
									</a>
								</td>
								
								<td width="210" style="word-wrap:break-word;">
								<div style="word-wrap: break-word;overflow:hidden;width:210px;">
								<b>
									<a onClick="javascript:toggleSliderImp(<s:property value="dbID"/>);" 
									   style="color:#000;cursor:pointer;"
									   title="Click to expand" >
									<s:if test="oai!=''" >
								    	<s:property value="oai"/>
									</s:if>
									<s:else>
										   <s:property value="name"/>
									</s:else>
									  </a>
									</b>
									<br>
									<s:property value="sizeDescription" />, <i><s:property value="date"/></i> 
							</div>
										</td>
								<td>
								<span id="import_stat_<s:property value="dbID"/>" class="yui-skin-sam">
								  <s:if test="status!='OK' && status!='ERROR' && status!='UNKNOWN'">
								  <!-- ajax for working imports -->
								  <script>ajaxFetchStatus(<s:property value="dbID"/>);</script>
								  </s:if>
							      <s:else>
							      <!-- do html -->
							         <%@ include file="_includeimportstat.jsp"%>
							      
							      
							      
							      
							      </s:else>
								 
								
			                  	</span>
								</td>			            		
			                    </tr>
			                    <s:if test="direct==false">
			                 
			                    <tr><td colspan="4">
			                    <s:if test="status=='OK' && (pub==null || pub.status=='NOT DONE')">
			                       
			                        <% if( (user.getOrganization() == null && user.hasRight(User.SUPER_USER)) || user.hasRight(User.MODIFY_DATA)) {%>
			                        
									      <!-- what is in transformstatus -->
									      
									      <s:if test="trans.status!='OK' && trans.status!='ERROR' && trans.status!='UNKNOWN' && trans.status!='NOT DONE'">
											  <!-- ajax for working transforms -->
											<div id="s<s:property value="dbID"/>_impclass" class="impclassb">
											<div id="sliderimpclass<s:property value="dbID"/>" class="sliderimpclass" style="height: 80px;">
												<div class="scontentimpclass">
					                       
					                       			<span id="import_steps_<s:property value="dbID"/>" style="display:block;">
											          <span id="import_trans_<s:property value="dbID"/>" class="yui-skin-sam">
											 				<script> ajaxFetchTransformStatus(<s:property value="dbID"/>);</script>
											 		  </span>
											 		</span>
											 	</div>
											 </div>
											</div>
											 
										  </s:if>
									      <s:else>  
									      <div id="s<s:property value="dbID"/>_impclass" class="impclass">
											<div id="sliderimpclass<s:property value="dbID"/>" class="sliderimpclass" style="height: 0px;">
												<div class="scontentimpclass">
					                       			<span id="import_steps_<s:property value="dbID"/>" style="display:block;">
											          <span id="import_trans_<s:property value="dbID"/>" class="yui-skin-sam">
									       					<%@ include file="_includeimporttrans.jsp"%>
									       			 </span>
											 		</span>
											 	</div>
											 </div>
											</div>
							        	  </s:else>   
							      <%} %>  
			                     
			                      
			                      </s:if>
			                      <s:elseif test="status=='OK' && pub.status!='NOT DONE'">
			                         <span id="import_pub_<s:property value="dbID"/>" class="yui-skin-sam">
			                             <s:if test="pub.status!='OK' && pub.status!='ERROR' && pub.status!='NOT DONE'">
			                               <div id="s<s:property value="dbID"/>_impclass" class="impclassb">
											<div id="sliderimpclass<s:property value="dbID"/>" class="sliderimpclass" style="height: 80px;">
												<div class="scontentimpclass">
					                       
					                       			<span id="import_steps_<s:property value="dbID"/>" style="display:block;">
											          <span id="import_trans_<s:property value="dbID"/>" class="yui-skin-sam">
											 			 <script> ajaxFetchPublicationStatus(<s:property value="dbID"/>,<s:property value="startImport"/>,5,<s:property value="userId"/>,<s:property value="orgId"/>);</script>
											    	  </span>
											 		</span>
											 	</div>
											 </div>
											</div>
											  <!-- ajax for working publications -->
										  </s:if>
									      <s:else>
									       <div id="s<s:property value="dbID"/>_impclass" class="impclass">
											<div id="sliderimpclass<s:property value="dbID"/>" class="sliderimpclass" style="height: 0px;">
												<div class="scontentimpclass">
					                       			<span id="import_steps_<s:property value="dbID"/>" style="display:block;">
											          <span id="import_trans_<s:property value="dbID"/>" class="yui-skin-sam">
									       					 <%@ include file="_includeimportpub.jsp"%>
									       			 </span>
											 		</span>
											 	</div>
											 </div>
											</div>
									      </s:else>
									   </span>
			                      </s:elseif>
			                      <s:else>
			                       <div id="s<s:property value="dbID"/>_impclass" class="impclass">
									<div id="sliderimpclass<s:property value="dbID"/>" class="sliderimpclass" style="height: 0px;">
										<div class="scontentimpclass">
			                       
			                 
			                   		<span id="import_steps_<s:property value="dbID"/>" style="display:none">
			                        <% if( (user.getOrganization() == null && user.hasRight(User.SUPER_USER)) || user.hasRight(User.MODIFY_DATA)) {%>
									      <span id="import_trans_<s:property value="dbID"/>" class="yui-skin-sam">
								          </span>
			                        
			                      <%} %>  
			                      </span>
			                       </div>
							     </div>
							     </div>
			                      </s:else>
			                    
			                    </td>
			                    </tr>
			                   
			                    </s:if> 
			                    <s:elseif test="direct==true && pub.status!='NOT DONE' ">
			                     <tr><td colspan="4">
			                       <span id="import_pub_<s:property value="dbID"/>" class="yui-skin-sam">
			                             <s:if test="pub.status!='OK' && pub.status!='ERROR' && pub.status!='NOT DONE'">
			                               <div id="s<s:property value="dbID"/>_impclass" class="impclassb">
											<div id="sliderimpclass<s:property value="dbID"/>" class="sliderimpclass" style="height: 80px;">
												<div class="scontentimpclass">
					                       
					                       			<span id="import_steps_<s:property value="dbID"/>" style="display:block;">
											          <span id="import_trans_<s:property value="dbID"/>" class="yui-skin-sam">
											 			 <script> ajaxFetchPublicationStatus(<s:property value="dbID"/>,<s:property value="startImport"/>,5,<s:property value="userId"/>,<s:property value="orgId"/>);</script>
											    	  </span>
											 		</span>
											 	</div>
											 </div>
											</div>
											  <!-- ajax for working publications -->
										  </s:if>
										  <s:elseif test="pub.status=='OK'">
										   <div id="s<s:property value="dbID"/>_impclass" class="impclass">
											<div id="sliderimpclass<s:property value="dbID"/>" class="sliderimpclass" style="height: 0px;">
												<div class="scontentimpclass">
										  
										     <a href="download.action?orgId=<s:property value="orgId"/>&published=true" title="Download published items" > <img src="custom/images/publisheddown.png" width="30" height="23" style="vertical-align:middle;margin-left: -3px;">Download <%= Config.get("mint.title") %></a>
										     <br/>
										     <span style="padding-left:5px;font-size:0.9em;">*Published import. To edit this import you must first unpublish it. </span>
										  </div>
											 </div>
											</div>
										  </s:elseif>
									      <s:else>
									       <div id="s<s:property value="dbID"/>_impclass" class="impclass">
											<div id="sliderimpclass<s:property value="dbID"/>" class="sliderimpclass" style="height: 0px;">
												<div class="scontentimpclass">
												<s:if test="status=='OK'">
					                       				<div style="margin-left:5px;margin-top:10px;">No further processing required. This import can be published directly.
					                       				</div>
					                       				</s:if>
											 	</div>
											 </div>
											</div>
									      </s:else>
									   </span>
			                   </td></tr>
			                    </s:elseif>
			                    <s:elseif test="direct==true && pub.status=='NOT DONE'">
			                     <tr><td colspan="4">
			                    <div id="s<s:property value="dbID"/>_impclass" class="impclass">
									<div id="sliderimpclass<s:property value="dbID"/>" class="sliderimpclass" style="height: 0px;">
										<div class="scontentimpclass">	
										<s:if test="status=='OK'">	
										  	<div style="margin-left:5px;margin-top:10px;">No further processing required. This import can be published directly.
										  	
										   </div>
										  </s:if>
								
								</div>
								</div>
								</div></td></tr>
			                    </s:elseif>
			                </table>     
							
						
							
							
						</li>
		   			</s:iterator>
		   			
           			</ol>
           </s:if>
           <s:else>	
             <div id="message" style="width: 390px;height:40px;padding:3px;"><br/>No imports found. </div>
             
	      </s:else>
	         <%if( (user.getOrganization() == null && user.hasRight(User.SUPER_USER)) || user.hasRight(User.ADMIN)|| (user.hasRight(User.MODIFY_DATA) && user.getOrganization().getDbID()==oid)) {%>
		  	<s:if test="imports.size>0">
           			<!-- trash bin , only show if there are imports -->
           			<table border="0" cellspacing="0" cellpadding="0" width="100%">
						       <tr><td width="20" align="right">
			                      <input type="checkbox" name="import_all_check" id="import_all_check" value="true" 
			                      		onclick="javascript:Check('import_all_check','imports_<s:property value="userId"/>_<s:property value="orgId"/>');"/></td>
			                    <td colspan="2" align="left"><a href="javascript:checks=getChecked('imports_<s:property value="userId"/>_<s:property value="orgId"/>');ajaxImportsSubmit(<s:property value="startImport"/>,5,<s:property value="userId"/>,<s:property value="orgId"/>,checks,'delete' )">
			                    <img src="custom/images/trash_can.png" width="20" height="20" style="vertical-align:middle;">Delete selected</a></td>
			                     <td  colspan="2" align="left"></td>
			                    </tr> 
		
			        </table>
			        </s:if>		
			<%} %>	         
		<s:if test="importCount>5">
		<table border="0" width="100%">

		<tr>
			<td colspan="5">
                   Displaying imports <s:property
					value="(startImport+1) + \" - \" + endImport" /> of <s:property
					value="importCount" />
			</td>
		</tr><tr>
			<td width="100"><a
				href="javascript:ajaxImportsPanel(<s:property value="previousPage"/> )">&lt;previous
			</a></td>
			<td width="100">
			
			<%int start=0;
			if(request.getAttribute("startImport")!=null){start=(Integer)request.getAttribute("startImport");}
			if(start+5<(Integer)request.getAttribute("importCount")) {%><a
				href="javascript:ajaxImportsPanel(<s:property value="startImport+5"/>,5,<s:property value="userId"/>,<s:property value="orgId"/> )">
			next&gt;</a><%} %></td>
			<td width="30%"  align="right">Jump to page</td>
			<td><input type="text" name="pageJump_<s:property value="orgId"/>_<s:property value="userId"/>" 
				id="pageJump_<s:property value="orgId"/>_<s:property value="userId"/>" size="3">
			</td>
			<td><a class="button"  onclick="javascript:this.blur();ajaxImportsPanel(document.getElementById('pageJump_<s:property value="orgId"/>_<s:property value="userId"/>').value*5,5,<s:property value="userId"/>,<s:property value="orgId"/>)" href="#"><span>go</span></a></td>
		</tr>
	</table>
	
   </s:if>
    
       
      
   </fieldset>
</form>
		</td></tr>
	
		</table>
