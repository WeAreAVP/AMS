<!-- 
  This page is a generic XML preview page
  It will be rendered in a panel and what tabs it will display
  should be decided by the controller.
 -->

<%@ include file="_include.jsp"%>

<%@page import="java.io.*"%>
<%@page import="org.apache.commons.lang.StringEscapeUtils"%>

<%@page import="gr.ntua.ivml.mint.db.*"%>
<%@page import="gr.ntua.ivml.mint.persistent.*"%>
<%@page import="gr.ntua.ivml.mint.mapping.*"%>
<%@page import="gr.ntua.ivml.mint.xml.transform.*"%>

<div class="yui-skin-sam" style="width: 100%; height: 100%">
	<div><s:property value="message"/></div><br/>
	
<!--  A select box for available mappings -->

<s:if test="mappingSelector==true">
 <s:form name="XMLPreview" action="XMLPreview" cssClass="athform"
	theme="mytheme" enctype="multipart/form-data"
	style="width:100%;margin-top:-10px;">
	<fieldset
		style="background-image: url(../images/spacer.gif); background-repeat: none;">
	&nbsp; Select the mappings that will be used for the
	transformation preview:
	
		<span style="display:inline;">
		<%
	    	 java.util.List templateMappings=(java.util.List)request.getAttribute("maplist");
			 String sel="";
		  	%> <select id="XMLPreview_selMapping" name="selMapping" style="width: 200px" onchange="javascript:ajaxXmlMapPreview(<s:property value="uploadId"/>,<s:property value="nodeId"/>, this.value); ">
			  	<option value="0">-- No template --</option>
				<%Organization lastorg=new Organization();
			  	  for(int i=0;i<templateMappings.size();i++){
				   Mapping tempmap=(Mapping)templateMappings.get(i);
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
				   if(tempmap.isFinished()){
					   cssclass+="finished";
				   }
				   if(tempmap.isShared()){
					   cssclass+=" shared";
				   }
				  %>
				<option value="<%=tempmap.getDbID() %>" class="<%=cssclass %>"
					<%=sel%>><%=tempmap.getName() %></option>
				<%  }%>
				<%if(templateMappings.size()>0){ %>
			</optgroup>
			<%} %>
		</select></span>
	
	</fieldset>
</s:form>
</s:if>
 <!--  end select box  -->

 <s:if test="hasActionErrors()==true">
   
		<s:iterator value="actionErrors">
			<span class="errorMessage"><s:property escape="false" /> </span>
		</s:iterator>
	</s:if>
<s:else>
	<div id="previewTabs" class="yui-navset"> 
	    <ul class="yui-nav"> 
	    	<s:iterator value="tabs" status="tabstat" >
	    		
			        <li <s:if test="#tabstat.first"><% out.println("class='selected'");%></s:if>>
			           <a href="#tab<s:property value="#tabstat.index"/>">
			             <em><s:property value="title"/></em>
			           </a></li> 
	    	</s:iterator>
	    </ul>             
	    <div class="yui-content"> 
		    <s:iterator value="tabs" status="tabstat" >
		    	<div><div class="indiv" style="width: 100%; <s:if test="!longContent"><% out.println("overflow-x: auto; overflow-y: auto");%></s:if>">
	    			<s:if test="type=='xml'">
	    				<textarea name="code" <s:if test="!longContent"><% out.println("class=\"xml\"");%></s:if> 
	    				style="width: 100%" rows="25" columns="50" readonly><s:property value="content"/></textarea>
				</s:if>	
				<s:if test="type=='text'">
				  <pre><s:property value="content"/></pre>    
				</s:if>
				<s:if test="type=='html'">
				  <s:property value="content" escape="false"/>
				</s:if>
				<s:if test="type=='jsp'">
				  <s:set var="theUrl" value="%{url}" name="theUrl" scope="request"></s:set>
				  <jsp:useBean id="theUrl" class="java.lang.String" scope="request" ></jsp:useBean>
				  <s:set var="theContent" value="%{content}" name="theContent" scope="request"></s:set>
				  <jsp:useBean id="theContent" class="java.lang.String" scope="request" ></jsp:useBean>
				  <jsp:include page="<%= theUrl %>"/>
				</s:if>  
			</div></div>		  
	    		</s:iterator>
		</div>
	</div>
</s:else>
</div>