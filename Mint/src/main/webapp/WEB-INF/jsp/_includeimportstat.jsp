

<table border="0" cellspacing="0" cellpadding="0" width="100%">
										<tr>
										<td align="right">
										<s:set var="tclass" value="%{'tooltipsmall'}" />
										
										<s:if test="lido==true && status=='OK'">
											<img id="context<s:property value="dbID"/>" src="custom/images/transformed.gif" width="16" height="18" style="vertical-align:middle;" title="<s:property value="status"/>" onMouseOver="this.style.cursor='pointer';">
										</s:if>
										<s:else>
										<img id="context<s:property value="dbID"/>" src='<s:property value="statusIcon"/>' style="vertical-align:middle;" onMouseOver="this.style.cursor='pointer';">
										 <s:if test="status=='ERROR'">
										  <s:set var="tclass" value="%{'tooltip'}" />
										   </s:if>
									
										<div class="<s:property value="#tclass"/>">
										 <a class="close-tooltip"><img src="images/closetp.png"></a>
										 <div class="label"><s:property value="status"/></div>
										  <div class="c1">
										   <div class="c2" id="c2<s:property value="dbID"/>">
											</div>
											</div>
										</div>
										<script>
										$("#c2<s:property value="dbID"/>").html("<s:property value="formattedMessage"/>".replace(/\n/g,'<br/>'));
										$("#context<s:property value="dbID"/>").tooltip({
											   offset: [10, 2],	
											   events: {
												   def:     ",",    // default show/hide events for an element
											      tooltip: "click,mouseleave"     // the tooltip element
											    },

									            
											   // use the "slide" effect
											   effect: 'slide',
											// add dynamic plugin with optional configuration for bottom edge
										}).dynamic({ bottom: { direction: 'down', bounce: true } });

								
								        	
									    $('a.close-tooltip').click(function() { 
									    	
									        $(this).parent().hide();   
									       
									         
									    });

									    $("#context<s:property value="dbID"/>").click(function() {
									        var tip = $(this).data("tooltip");
											
									        if (tip.isShown(true))
									            tip.hide();
									        else{
									            tip.show();}
											tip.show();			
									    });
									    									  				

									    
									  
									   
											
											</script>
										</s:else>
										
										</td> 
										<td width="20"><s:if test="status=='OK'">
										<a onclick="javascript:ajaxItemPanel(0, 10, <s:property value="orgId"/>, <s:property value="dbID"/>,<s:property value="userId"/>);" href="#" class="" title="show items">
										<img style="vertical-align: middle; " src="images/items.png"></a></s:if>
										</td>
										
										
										<td width="20"><s:if test="status=='OK'">
											<a onclick="javascript:window.open('Stats?uploadId=<s:property value="dbID"/>','mywin','left=20,top=20,width=600,height=700,toolbar=0,resizable=1')">
											<img id="stats<s:property value="dbID"/>" title="Import statistics" src="images/stats2.png" width="18" style="vertical-align:middle;"></a>
											   </s:if>
										</td>
										<!--  Download action  -->
										
										<s:if test="(downloads.size>0 && (canDownload(user)==true || imp.canDownload(user)==true))" >
										<td width="20" id="download_<s:property value="dbID"/>" >
										  <s:if test="downloads.size==1"> 
											<a href="<s:property value="downloads[0].url"/>" title="<s:property value="downloads[0].title" />" > 
											<img src="images/download2.png" width="18" style="vertical-align:middle;"></a></s:if>
										  <s:else> 
										  <img src="images/download2.png" width="18" style="vertical-align:middle;">
										  </s:else>	
										</td>
											</s:if>
										</tr>
										</table>
<s:if test="downloads.size>1"> 
<div id="link_panel_test<s:property value="dbID"/>">
 Available Downloads
 <s:iterator value="downloads" >
 	<table> <tr><td>
 	<a href="<s:property value="url"/>"><s:property value="title"/></a>
 	</td></tr></table>
 </s:iterator>
 </div>

<script>

YAHOO.util.Event.onDOMReady(function() {
    initLinkPanel("download_<s:property value="dbID"/>", "link_panel_test<s:property value="dbID"/>");
}); 

 </script>
 </s:if>