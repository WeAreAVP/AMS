   <table width="100%" cellspacing="0" cellpadding="0">
   <tr><td>
   <div class="vertalign">
		    	 <s:if test="trans.status=='IDLE' || trans.status=='WRITING' || trans.status=='UPLOADING' || trans.status=='INDEXING'">
			 	  <br><br>Transforming...
				 </s:if>
				  <s:elseif test="trans.status=='OK'">
	        	   <div class="imgteaser">

						<a href="javascript:ajaxItemLevelLabelRequest(<s:property value="trans.dbID"/>, <s:property value="orgId"/>,<s:property value="userId"/>,true)">
							<img src="images/AddRootItem.png" width="21" style="vertical-align:middle;" alt="Set item root & label elements">
							<span class="desc">
								Item Root
							</span>
						</a>
					 </div>
				   <div class="iroot">
					 <s:if test="rootDefined">
					   <img id="root_stat<s:property value="dbID"/>" src='images/okblue.png' style="vertical-align:middle;margin-left: 0px;" title="Item root defined">
					    <script>
					    var tooltiproot<s:property value="dbID"/> = new YAHOO.widget.Tooltip("tooltiproot<s:property value="dbID"/>", { context:"root_stat<s:property value="dbID"/>" , text:"Item root defined", width:"400px"} );
					
					    </script>
					   </s:if>
					 <s:else>
					    <img id="root_stat<s:property value="dbID"/>" src='custom/images/help.png' width="20" style="vertical-align:middle;margin-left: 0px;" title="Item root not defined">
					    <script>
					    var tooltiproot<s:property value="dbID"/> = new YAHOO.widget.Tooltip("tooltiproot<s:property value="dbID"/>", { context:"root_stat<s:property value="dbID"/>" , text:"Item root undefined", width:"400px"} );
					
					    </script>
					
					  </s:else>
					</div>	 
					
	        	 </s:elseif>
				 <s:else>
				 
				 <div class="imgteaser">

						<a href="javascript:ajaxItemLevelLabelRequest(<s:property value="trans.dbID"/>, <s:property value="orgId"/>,<s:property value="userId"/>,false)">
							<img src="images/AddRootItem.png" width="21"  alt="Set item root & label elements">
							<span class="desc">
								Item Root
							</span>
						</a>
				 </div>
				  <div class="iroot">
					 <s:if test="rootDefined">
					   <img id="root_stat<s:property value="dbID"/>" src='images/okblue.png' style="vertical-align:middle;margin-left: 0px;" title="Item root defined">
					    <script>
					    var tooltiproot<s:property value="dbID"/> = new YAHOO.widget.Tooltip("tooltiproot<s:property value="dbID"/>", { context:"root_stat<s:property value="dbID"/>" , text:"Item root defined", width:"400px"} );
					
					    </script>
					   </s:if>
					 <s:else>
					    <img id="root_stat<s:property value="dbID"/>" src='custom/images/help.png' width="20" style="vertical-align:middle;margin-left: 0px;" title="Item root not defined">
					    <script>
					    var tooltiproot<s:property value="dbID"/> = new YAHOO.widget.Tooltip("tooltiproot<s:property value="dbID"/>", { context:"root_stat<s:property value="dbID"/>" , text:"Item root undefined", width:"400px"} );
					
					    </script>
					
					  </s:else>
					</div>	 
					
				 </s:else>
				 
				   
				
					<s:if test="trans.status=='IDLE' || trans.status=='WRITING' || trans.status=='UPLOADING' || trans.status=='INDEXING'"> 
					<span><img src="images/lock.gif" style="vertical-align:middle;"/></span>
					</s:if>
					<s:else>
					   <%
					    boolean hasThesauri = gr.ntua.ivml.mint.util.Config.has("hasThesauri") && gr.ntua.ivml.mint.util.Config.getBoolean("hasThesauri");
					   	if(hasThesauri) {
					   %>
					   <div class="imgteaser">    
					     <a href="javascript:ajaxThesauriLevelLabelRequest(<s:property value="trans.dbID"/>, <s:property value="orgId"/>,<s:property value="userId"/>)" title="Specify Thesauri"><img src="images/thesaurus.png">
					      <span class="desc"> 
					        Thesauri
					      </span>
					     </a>
					   </div>
					   <% } %>
					   <div class="imgteaser">			
					      <a href="javascript:ajaxMappingDefinitionRequest(<s:property value="trans.dbID"/>, <s:property value="orgId"/>,<s:property value="user.dbID"/>)" title="Open mapping editor"><img src="images/test-matching.gif" width="28">
					       <span class="desc"> 
					       Mapping
					      </span>
					      </a>
					   </div>
					  </s:else>
				
			
				
				
				 <span style="display:none">
				<s:property value="trans.status"/>
				</span>
				
				 <div class="imgteaser">	
				 <s:if test="trans.status=='OK' || trans.status=='ERROR' || trans.status=='NOT DONE' || trans.isStale==true">
				  		
				 <a href="javascript:ajaxtransformRequest(<s:property value="trans.dbID"/>, <s:property value="orgId"/>,<s:property value="userId"/>)" title="Transform import"><img src="images/xsl.png" width="28">
				 <span class="desc"> 
					      Transform
					 </span>
				 </a>
				
				  </s:if>
				 </div>
				  <div class="trans" <%
				  	boolean hasThesauri = gr.ntua.ivml.mint.util.Config.has("hasThesauri") && gr.ntua.ivml.mint.util.Config.getBoolean("hasThesauri");
				   	if(!hasThesauri) { %> style="margin-left: 90px" <% } %>>  
					 <s:if test="trans.status!='NOT DONE'">
					   
					   <s:if test="trans.hasReport()">
					       <a onclick="javascript:ajaxTransReportPreview(<s:property value="trans.dbID"/>)" href="#"><img id="context_stat<s:property value="trans.dbID"/>" src='<s:property value="trans.statusIcon"/>' style="vertical-align:middle;padding-left: 2px;" title="Transformation <s:property value="trans.status"/>" style="margin-right:0px;"></a>
					   </s:if>   
					   <s:else>
					    <img id="context_stat<s:property value="trans.dbID"/>" src='<s:property value="trans.statusIcon"/>' style="margin-right:0px;" title="TRANSFORMATION <s:property value="trans.status"/>">
					  
					    <script>
					    var tooltiptrans<s:property value="trans.dbID"/> = new YAHOO.widget.Tooltip("tooltiptrans<s:property value="trans.dbID"/>", { context:"context_stat<s:property value="dbID"/>" , text:"<s:property value="trans.message"/>", width:"400px"} );
					    </script>
					    </s:else>
					    
					   </s:if>
				  <s:if test="trans.status=='OK' && (user.getMintRole().equalsIgnoreCase('ADMIN') || user.getMintRole().equalsIgnoreCase('SUPERUSER')  || uploader==user.dbID)">
						     <a href="javascript:ajaxDeleteTransform(<s:property value="trans.dbID"/>);"><img src="custom/images/trash_can.png" width="20" style="padding-left: 0px; margin-top: -2px;" title="Delete tranformation"></a>
					</s:if>
				  
				    </div>
					 <s:if test="trans.status=='OK' && trans.approved==0">
						 <s:if test="user.getMintRole().equalsIgnoreCase('ADMIN') || user.getMintRole().equalsIgnoreCase('SUPERUSER')">
							 <div id="approvalStatus" style="padding-top: 25px;">
								 <div><a href="javascript:ajaxApprovalRequest(2,<s:property value="trans.dbID"/>)">Approve</a></div>
								 <div><a href="javascript:ajaxApprovalRequest(1,<s:property value="trans.dbID"/>)">Reject</a></div>
						</div>
						 </s:if>
						 <s:elseif test="uploader==user.dbID">
						 <div style="padding-top: 25px;color: #c09853">Waiting for Approval</div>
					 </s:elseif>
					 </s:if>
					 <s:elseif test="trans.status=='OK' && trans.approved==1">
						 <div style="padding-top: 30px;color:#b94a48;">Rejected</div>
					 </s:elseif>
					 <s:elseif test="trans.status=='OK' && trans.approved==2">
						 <div style="padding-top: 30px;color: #468847;">Approved</div>
					 </s:elseif>
					 
				 
	</div>
	</td></tr>
	
	</table>