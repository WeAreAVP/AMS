<script type="text/javascript">
 function handleKeyPress(e) {  
var code =(window.event)? event.keyCode : e.which;
if(code==13) {return false;}

   }  
  document.onkeydown= handleKeyPress;  
</script>

<%int itemcount=0; %>
<%@ taglib prefix="s" uri="/struts-tags" %>


	<table border="0"  cellpadding="0" cellspacing="0" width="398">
	<tr><td height="10px;">&nbsp;</td></tr>
		<tr><td>
		<span class="rounded_top">Items</span>
		</td></tr>
		<tr><td style="background: url(custom/images/grey.gif);" align="left">
			<form name='items_<s:property value="organizationId"/>' class="athform" style="width:396px;border: solid 1px silver;margin:0;padding:0;align:left;">
			<fieldset>
			<div style="font-size:100%;padding-left:3px;"><b>(<s:if test="userId>-1"><s:property value="u.firstName"/>&nbsp;<s:property value="u.lastName"/>, </s:if>
			<s:property value="o.name"/>)</b></div>	
		<% if( request.getAttribute( "actionmessage" ) != null ) {  %>
		<div id="message" style="width: 396px;height:15px;"><%=(String) request.getAttribute( "actionmessage" )%></div>
		<% }%>
	<!-- 	<table border="0" cellspacing="0" cellpadding="0">
		<tr><td> -->
	
		&nbsp;Filter by: <s:select theme="simple" label="Filter by" name="filteritems" headerKey="-1" headerValue="-- All imports --" list="imports" listKey="dbID" listValue="originalFilename" value="%{uploadId}" onChange="javascript:ajaxItemPanel(0,10,${organizationId},document.getElementById('filteritems').value,${userId});" style="width:320px;"></s:select>

        <s:if test="items.size>0">
		<ol>
       
      	<table border="0" width="396" cellspacing="0" cellpadding="3">
		<tr><td><b>Name </b></td><td align="left" width="100"><b>Created</b></td></tr>
		</table>
		<li></li>
		
		<s:iterator id="item" value="items">
          		<%itemcount++; %>
		 <li>
		   <table border="0" width="380" cellspacing="0" cellpadding="0">
		<tr><td>
		<s:if test="direct==false">
		 <a href="javascript:ajaxXmlInput('<s:property value="uploadId"/>', '<s:property value="nodeId"/>')"><img
					src="images/xmlview.png" style="vertical-align: top;padding-right:0px;margin-left:-10px;"
					title="input xml preview" border="0"></a><a href="javascript:ajaxXmlMapPreview('<s:property value="uploadId"/>', '<s:property value="nodeId"/>',0)"><img
					src="images/webview.png" style="vertical-align: top;padding-right:0px;"
					title="mappings xml preview" border="0"></a>
		</s:if>
					<s:if test="transformed==true || direct==true">
					<a href="javascript:ajaxXmlTransformed('<s:property value="uploadId"/>', '<s:property value="nodeId"/>')">
					<img src="custom/images/transformed.gif" style="vertical-align: top;padding-right:3px;" title="transformed xml preview" border="0">
					</a></s:if>
					<s:property value="name" />
		  
		</td><td align="right" width="96" style="word-wrap:break-word;">			
					<i><s:property value="importname" /><br/><s:property value="date" />
					</i>
				</td></tr></table>

			</li>
			
		</s:iterator></ol>
		</s:if>
		<s:else>
		 <div id="message" style="width: 390px;height:40px;padding:2px;"><br/>No root element has been defined yet. Choose "show imports" and define the root element and label of your items per import.
		</div>
		</s:else>
        <%if(itemcount>0){ %>
	<table border="0" width="390" cellspacing="0" cellpadding="3">
		<tr>
			<td colspan="5"><s:if test="items.size>0">
                   Displaying items <s:property
					value="(startItem+1) + \" - \" + endItem" /> of <s:property
					value="itemCount" />
			</s:if></td>
       </tr>
       <tr>
			<td width="100"><s:if test="startItem>0"><a
				href="javascript:ajaxItemPanel(<s:property value="previousPage"/>)">&lt;previous
			</a></s:if></td>
			<td width="100">&nbsp;
				<%int start=0;
			
			if(request.getAttribute("startItem")!=null){start=(Integer)request.getAttribute("startItem");}
			if(start+10<(Integer)request.getAttribute("itemCount")) {%>
		
			<a href="javascript:ajaxItemPanel(<s:property value="startItem+10"/>,10,<s:property value="organizationId"/>,<s:property value="uploadId"/>,<s:property value="userId"/> )">
			next&gt;</a><%} %></td>
			
			<td width="30%" align="right">Jump to page</td>
			<td><input type="text" name="pageJump_<s:property value="organizationId"/>" 
				id="pageJump_<s:property value="organizationId"/>" size="3"></td>
			<td>
			<a class="button" onclick="javascript:this.blur();ajaxItemPanel(document.getElementById('pageJump_<s:property value="organizationId"/>').value*10,10,<s:property value="organizationId"/>,<s:property value="uploadId"/>,<s:property value="userId"/> )" href="#"><span>go</span></a>  </td>
		</tr>

	</table>
	<%} %>
	</fieldset>		
		</form>

		</td></tr>
	<tr><td>

	</td></tr>
		</table>


