<%@ include file="_include.jsp"%>

<script type="text/javascript" src="js/mapping/lib/yui/yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript" src="js/mapping/lib/yui/animation/animation-min.js"></script>
<script type="text/javascript" src="js/mapping/lib/yui/dragdrop/dragdrop-min.js"></script>

<style type="text/css">
h2{margin-left:0;}

form.athform label {
	
		float:left;
	
		}


div.workarea { padding-right:10px; float:left; }

ul.draglist { 
    position: relative;
    width: 360px; 
    height:240px; 
    overflow:auto;
    background: #fff;
    border: 1px solid gray;
    list-style: none;
    margin:0;
    padding:0;
}

ul.draglist li {
    margin: 1px;
    cursor: move;
    zoom: 1;
}

ul.draglist_alt { 
    position: relative;
    width: 300px; 
    list-style: none;
    margin:0;
    padding:0;
    /*
       The bottom padding provides the cushion that makes the empty 
       list targetable.  Alternatively, we could leave the padding 
       off by default, adding it when we detect that the list is empty.
    */
    padding-bottom:20px;
}

ul.draglist_alt li {
    margin: 1px;
    cursor: move; 
  }




#user_actions { float: right; }

</style>


        
<span id="trans" style="display:none"><s:property value="transformed.size"/></span>
<span id="publ" style="display:none"><s:property value="published.size"/></span>

<div style="width: 100%; height: 100%; margin-top:-20px;">

<h2>Publish</h2>
<s:if test="hasActionErrors()">

	<s:iterator value="actionErrors">
				<font style="color:red;"><s:property escape="false" /> </font>
	</s:iterator>
	

</s:if>
<s:else>

<s:form name="mapform" action="Mapselection" enctype="multipart/form-data" style="width:100%;margin-top:-10px;">

	<p>&nbsp;Move transformed imports between the published and unpublished lists and then click the "Done" button:</p>
	    <div class="workarea">
		   <div id="unpub">&nbsp;Unpublished</div>
		   <ul id="ul1" class="draglist">
		   <%int i=0; %>
		   <s:if test="transformed.size>0">
		   
		   <s:iterator id="imp" value="transformed">
		   <%i++;%>
		        <li class="list1" id="li1_<%=i %>" style="background: url(custom/images/grey.gif) repeat scroll 0% 0% transparent;">
		                         <span id="unpubid" style="display:none"><s:property value="dbID"/></span>
		                         <div style="float:left;position:relative;width:36px;">
		                          <s:if test="oai!=''">
			                        <img src="images/oai_symbol.png" style="vertical-align:middle;" title="<s:property value="fullOai"/>">
			                        </s:if>
			                        <s:elseif test="noOfFiles>1">
									<img src="images/zipfile.png" width="34" style="vertical-align:middle;">
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
		                         </div>
		        				<div style="position:relative;word-wrap: break-word;overflow:hidden;">
		        				<b>
									<s:if test="oai!=''" >
									<s:property value="oai"/>
									</s:if>
									<s:else>
									<s:property value="name"/>
									</s:else>
									</b>
									<br>
									<s:property value="sizeDescription" />, <i><s:property value="date"/></i> 
							</div>
		        
		        </li>
			  </s:iterator>
		   </s:if>
		  </ul>
		</div>
		
		<div class="workarea">
		 <div id="pub">&nbsp;Published</div>
		  <ul id="ul2" class="draglist">
		    <s:if test="published.size>0">
		   <%i=0; %>
		   <s:iterator id="imp" value="published">
		   <%i++;%>
		        <li class="list2" id="li2_<%=i %>" style="background: url(custom/images/sprite.png) repeat scroll 0% 0% transparent;">
		         <span id="pubid" style="display:none"><s:property value="dbID"/></span>
		                         <div style="float:left;position:relative;width:36px;">
		                          <s:if test="oai!=''">
			                        <img src="images/oai_symbol.png" style="vertical-align:middle;" title="<s:property value="fullOai"/>">
			                        </s:if>
			                        <s:elseif test="noOfFiles>1">
									<img src="images/zipfile.png" width="34" style="vertical-align:middle;">
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
		                         </div>
		        				<div style="position:relative;word-wrap: break-word;overflow:hidden;width:310px;">
		        				<b>
									<s:if test="oai!=''" >
									<s:property value="oai"/>
									</s:if>
									<s:else>
									<s:property value="name"/>
									</s:else>
									</b>
									<br>
									<s:property value="sizeDescription" />, <i><s:property value="date"/></i> 
							</div>
		        
		        </li>
			  </s:iterator>
		   </s:if>
		   
		  </ul>
		</div>  	    	
	<div style="float:left;width:100%">
	<font style="font-size:0.8em;">* Only transformed imports (that are not stale) can be published. Published items can no longer be edited. If you want to remap/edit an import you need to unpublish and republish.</font>
  
	</div>
   <s:if test="hasActionErrors()">
   <div>
		<s:iterator value="actionErrors">
			<span class="errorMessage"><s:property escape="false" /> </span>
		</s:iterator>
	</div>
	</s:if>
	<div style="float:left;width:100%">
	<p align="left">
	
	<a class="button" id="donebutton" href="#" onclick="this.blur();ajaxPublish(<s:property value="orgId" />,calcList('ul2'),calcList('ul1')); "><span>Done</span></a>  
	</p>
    </div>
    
</s:form>

</s:else>
</div>

