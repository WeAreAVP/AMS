<%@ include file="top.jsp" %>  
<%@page import="java.util.List"%>
<%@page import="gr.ntua.ivml.mint.persistent.Organization"%>
<%@page import="gr.ntua.ivml.mint.persistent.DataUpload"%>
<%@page import="gr.ntua.ivml.mint.persistent.Publication"%>
<%@page import="gr.ntua.ivml.mint.util.OAIStats"%>
<link rel="stylesheet" type="text/css" href="css/stylish-select.css" />



<link rel="stylesheet" href="css/jquery.treeview2.css" />
<script src="js/jquery.cookie.js" type="text/javascript"></script>
<script src="js/jquery.treeview2.js" type="text/javascript"></script>	
<script type="text/javascript" src="js/tview.js"></script>
<script src="js/jquery.stylish-select.js" type="text/javascript"></script>


<script type="text/javascript" src="js/mapping/lib/yui/yahoo/yahoo-min.js"></script>
<script type="text/javascript" src="js/mapping/lib/yui/event/event-min.js"></script>
<script type="text/javascript" src="js/mapping/lib/yui/dom/dom-min.js"></script>
<script type="text/javascript" src="js/mapping/lib/yui/animation/animation-min.js"></script>
<script type="text/javascript">
		
		function xGetElementById(e) {
			if(typeof(e)!='string') return e;
			if(document.getElementById) e=document.getElementById(e);
			else if(document.all) e=document.all[e];
			else e=null;
			return e;
		}

		function hideSlider(e){
			 slider=xGetElementById("slider"+e);
			 container=xGetElementById("s"+e+"_container");
		     var anim = new YAHOO.util.Anim(slider,{height:{to:0}},1,YAHOO.util.Easing.easeIn);
			 container.className='container';
			 anim.animate();
			}

		function showSlider(e){
			 slider=xGetElementById("slider"+e);
			 container=xGetElementById("s"+e+"_container");
			 var anim = new YAHOO.util.Anim(slider,{height:{to:140}},1,YAHOO.util.Easing.easeOut);
			 container.className='containerb';
			 anim.animate();
			}
		
		function toggleSlider(e){
		 slider=xGetElementById("slider"+e);
		 container=xGetElementById("s"+e+"_container");
	    	var anim = new YAHOO.util.Anim(slider,{height:{to:0}},1,YAHOO.util.Easing.easeIn);
		 if(slider.style.height=="0px"){
			var anim = new YAHOO.util.Anim(slider,{height:{to:140}},1,YAHOO.util.Easing.easeOut);
			 container.className='containerb';
		 }
		 else{
			 container.className='container';
			 }
			anim.animate();
		}
		

        function hidelAllCountryOrgs(num){
              var lookli='licountry'+num;
             var elems=getElementsByClassName(document.getElementById(lookli),"div","slider");
             var conts=getElementsByClassName(document.getElementById(lookli),"div","containerb");
              var length = elems.length;
  			  for(var i=0; i<length; i++){
  	  			current = elems[i];
  	  			
  				current.style.height="0px";
  			  }
  			 var length = conts.length;
 			  for(var i=0; i<length; i++){
 				 currentcont=conts[i];
   	  			currentcont.className='container';
 			  }
        }

        function hideAllCountries(){
           var elems=getElementsByClassName(document,"div","slider");
           var conts=getElementsByClassName(document,"div","containerb");
            var length = elems.length;
			  for(var i=0; i<length; i++){
	  			current = elems[i];
	  			
				current.style.height="0px";
			  }
			 var length = conts.length;
			  for(var i=0; i<length; i++){
				 currentcont=conts[i];
 	  			currentcont.className='container';
			  }
      }

        function showAllCountries(){
            var elems=getElementsByClassName(document,"div","slider");
            var conts=getElementsByClassName(document,"div","container");
             var length = elems.length;
 			  for(var i=0; i<length; i++){
 	  			current = elems[i];
 	  			
 				current.style.height="140px";
 			  }
 			 var length = conts.length;
 			  for(var i=0; i<length; i++){
 				 currentcont=conts[i];
  	  			currentcont.className='containerb';
 			  }
       }
		
        function getElementsByClassName(oElm, strTagName, strClassName){
        	var arrElements = (strTagName == "*" && oElm.all)? oElm.all : oElm.getElementsByTagName(strTagName);
        	var arrReturnElements = new Array();
        	strClassName = strClassName.replace(/\-/g, "\\-");
        	var oRegExp = new RegExp("(^|\\s)" + strClassName + "(\\s|$)");
        	var oElement;
        	for(var i=0; i<arrElements.length; i++){
        		oElement = arrElements[i];
        		if(oRegExp.test(oElement.className)){
        			arrReturnElements.push(oElement);
        		}
        	}
        	return (arrReturnElements)
        }

   
	</script>


<style type="text/css">
.containerb {
	border:1px solid #FF9900;
	margin:0;
	padding:0;
	position:relative;
}

.container {
	border-left:1px solid #FF9900;
    border-top:1px solid #FF9900;
	border-right:1px solid #FF9900;
	border-bottom:0px;
	margin:0;
	padding:0;
	position:relative;
}

.slider{
	position:relative;
	width:100%;
	height:140px;
	overflow:hidden;
	margin:0 0 0 0px;
	padding:0;
	z-index:99;
}

.scontent{
	position:absolute;
	top:0;
	left:0;
	width:100%;
	height: 100%;
	margin:0;
	padding:0;
	background-color: #e2e2e2;
	color:#000;
}

</style>
<script type="text/javascript">

</script>

<%int count=2;
if( user.hasRight(User.SUPER_USER)){
	  count++;
}

if( user.hasRight(User.ADMIN))
	  count++;

if( user.hasRight(User.MODIFY_DATA)){
	  count++;
}


	  count++;
%>


<script type="text/javascript">

$("li a#reportmenu").addClass("selected");

var orgItemStart=new Object(); 
hideAllCountries();
</script>
				
<style type="text/css">

.tdLabel {
color:#333333;
width:90px;
}

 form.athform fieldset li {
	padding: 5px 10px 7px;
	background: none;
     }
</style>
 
<%  
 int totalitems=0;
 int totaltrans=0;
 int totalpub=0;
 String nodata="";
 String data="";
  %> 
<h1>
<p>Data Report</p>
</h1>


<s:if test="hasActionErrors()">
   <li>
		<s:iterator value="actionErrors">
			<span class="errorMessage"><s:property escape="false" /> </span>
		</s:iterator>
	</li>
</s:if>
<s:else>
<div id="help">
<p style="margin-left:5px;">Data report for every organization per country:
     </p>
</div>
<table width="100%" height="600" valign="top">
<tr><td width="400" valign="top">
<s:form action="ReportSummary" cssClass="athform" style="width:400px;margin-top:-15px;">
<fieldset>

<input type="hidden" name="closedDivs" />


     <tr><td bgcolor="#ffffff" width="400">
     <%int h=0; %>
     <div id="treecontrol">
		<a title="Collapse the entire list below" href="#" onclick="hideAllCountries();"><img src="images/minus.gif" /> Collapse All</a>
		<a title="Expand the entire list below" href="#" onclick="showAllCountries();"><img src="images/plus.gif"/> Expand All</a>&nbsp;&nbsp;&nbsp;
		
	</div>
     <ul id="browser" class="filetree">
     	<%List<String> countries=(List<String>)request.getAttribute("countries");
     	  for(int i=0;i<countries.size();i++){%>
     	<li id="licountry<%=i %>" class="closed"><span class="folder" onclick="if(document.getElementById('licountry<%=i %>').className=='closed collapsable'){hidelAllCountryOrgs(<%=i %>);}">    	
     	<b><%=countries.get(i) %></b>
      	</span>
     	<ul>
     	    <%List<Organization> orgs=DB.getOrganizationDAO().findByCountry(countries.get(i)); 
     	       
     	      boolean found=false;%>
            <%for(int j=0;j<orgs.size();j++){ 
            	List<DataUpload> dus = orgs.get(j).getDataUploads();
            	int result=0;
            	int transformed=0;
            	int totalorgpub=0;
            	String prcontact="";
            	if(orgs.get(j).getPrimaryContact()!=null){
            		prcontact=orgs.get(j).getPrimaryContact().getFirstName()+" "+orgs.get(j).getPrimaryContact().getLastName()+ ", "+orgs.get(j).getPrimaryContact().getEmail();
            	}
            	int users=orgs.get(j).getUsers().size();
            	for( DataUpload du: dus ) {
        			if( du.getItemXpath() != null ) {
        				    result += (int) du.getItemXpath().getCount();
		        			if(DB.getTransformationDAO().findByUpload(du).size()>0){
		    					
		    					transformed+=(int) du.getItemXpath().getCount();
		    				}
        			}
        		}
            	Publication p=DB.getPublicationDAO().findByOrganization(orgs.get(j));
            	
            	totalitems+=result;
            	if(p!=null){
            		totalorgpub=(int)p.sumInputItems();
            	  //totalese=(int)p.getItemCount();
            	}
            	totalpub+=totalorgpub;
                totaltrans+=transformed;
                if(result>0){found=true;}
                
            %>
     		   <li>
     		  
               <table width="95%" cellspacing="0" cellpadding="0" border="0">
               <tr><td class="mainclr">
               <b><%=j+1%>. <%=orgs.get(j).getEnglishName() %></b>
               </td>
               <td align="right">
               <a onClick="javascript:toggleSlider('<%=orgs.get(j).getDbID() %>');"><img src="custom/images/info.png" style="vertical-align: middle;padding-right:2px;"/></a>
                
               </td>
               
               </tr>
               <tr><td colspan="2" height="1" bgcolor="#e2e2e2"></td></tr>
               <tr><td colspan="2">
               <div id="s<%=orgs.get(j).getDbID() %>_container" class="container">
					<div id="slider<%=orgs.get(j).getDbID() %>" class="slider" style="height: 0px;">
						<div class="scontent">
						
			             <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
			                    		<tr><td style="padding-left:4px;padding-top:3px;width:100px;vertical-align:bottom;"><b>Imports:</b></td><td align="left" style="vertical-align:bottom;"><%=dus.size() %></td>
			             </tr>
			       <tr><td colspan="2" height="1" bgcolor="#ffffff"></td></tr>
			                <tr><td style="padding-left:4px;padding-top:3px;vertical-align:bottom;"><b>Total Imported Items:</b></td><td align="left" style="vertical-align:bottom;"><%=result %></td></tr>
			                <tr><td colspan="2" height="1" bgcolor="#ffffff"></td></tr>
			           <tr><td style="padding-left:4px;padding-top:3px;vertical-align:bottom;"><b>Total Transformed Items:</b></td><td align="left" style="vertical-align:bottom;"><%=transformed %></td>
			             </tr>
			             <tr><td colspan="2" height="1" bgcolor="#ffffff"></td></tr>
			            <tr><td style="padding-left:4px;padding-top:3px;vertical-align:bottom;"><b>Total Published Items:</b></td><td align="left" style="vertical-align:bottom;"><%=totalorgpub %></td>
			             </tr>
			                <tr><td colspan="2" height="1" bgcolor="#ffffff"></td></tr>
			             <tr><td style="padding-left:4px;padding-top:3px;vertical-align:bottom;"><b>Total Users:</b></td><td align="left" style="vertical-align:bottom;"><%=users %></td>
			             </tr>
			                <tr><td colspan="2" height="1" bgcolor="#ffffff"></td></tr>
			              <tr><td style="padding-left:4px;padding-top:3px;vertical-align:bottom; "><b>Primary contact:</b></td><td align="left" style="vertical-align:bottom;"><%=prcontact %></td>
			             </tr>
			             </table>   
						</div>
					</div>
				</div>
               </td></tr>
               </table> 
                </li>
     		<%} 
     		if(found){data+=countries.get(i)+", ";}
     		else{nodata+=countries.get(i)+", ";}%>
      	</ul>
     	</li>
     	<%}
     	  if(nodata.length()>0){
     		  nodata=nodata.substring(0,nodata.lastIndexOf(','));
     	  }
     	 if(data.length()>0){
    		  data=data.substring(0,data.lastIndexOf(','));
    	  }
     	  %>
     	<!-- org -->
     </ul>
     </td></tr>
    
	   
     </fieldset>
</s:form>
</td>
<td valign="top" align="left">
<div id="showpanel" style="margin-left:10px;margin-top:35px;">
<table border="0"  cellpadding="0" cellspacing="0" width="300" style="margin-left:10px">
		<tr><td>
		 <tr><td><span class="rounded_top">Overall</span></td></tr>
			
		</td></tr>
		<tr><td bgcolor="#ededed" style=" border: solid 1px silver;" align="left">
		 
	  		<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
			                    		<tr><td style="padding-left:4px;padding-top:3px;"><b>Registered users:</b></td><td align="left" style="vertical-align:bottom;padding-left:15px;"><%=DB.getUserDAO().count() %></td>
			             </tr>
			       <tr><td colspan="2" height="1" bgcolor="#ffffff"></td></tr>
			                <tr><td style="padding-left:4px;padding-top:3px;"><b>Registered Organizations:</b></td><td align="left" style="vertical-align:bottom;padding-left:15px;"><%=DB.getOrganizationDAO().count() %></td></tr>
			                <tr><td colspan="2" height="1" bgcolor="#ffffff"></td></tr>
			           <tr><td style="padding-left:4px;padding-top:3px;"><b>Total Items:</b></td><td align="left" style="vertical-align:bottom;padding-left:15px;"><%=totalitems %></td>
			             </tr>
			                <tr><td colspan="2" height="1" bgcolor="#ffffff"></td></tr>
			             <tr><td style="padding-left:4px;padding-top:3px;"><b>Total Transformed Items:</b></td><td align="left" style="vertical-align:bottom;padding-left:15px;"><%=totaltrans %></td>
			             </tr>
			              <tr><td colspan="2" height="1" bgcolor="#ffffff"></td></tr>
			               <tr><td style="padding-left:4px;padding-top:3px;"><b>Total Published Items:</b></td><td align="left" style="vertical-align:bottom;padding-left:15px;"><%=totalpub %></td>
			             </tr>
			               <tr><td colspan="2" height="1" bgcolor="#ffffff"></td></tr>
			             <tr><td style="padding-left:4px;padding-top:3px;vertical-align:top;"><b>Countries contributing:</b></td><td align="left" style="vertical-align:bottom;padding-left:15px;"><%=data %></td>
			             </tr> 
			                  <tr><td colspan="2" height="1" bgcolor="#ffffff"></td></tr>
			             <tr><td style="padding-left:4px;padding-top:3px;vertical-align:top;"><b>Countries with no data:</b></td><td align="left" style="vertical-align:bottom;padding-left:15px;"><%=nodata %></td>
			             </tr> 
			             </table>   
   		</td></tr>
	</table>
 </div>
</td>
</tr>
</table>
</s:else>
<%@ include file="footer.jsp" %>  
