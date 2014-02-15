
<%@ page import="gr.ntua.ivml.mint.persistent.Lock"%>
<%@ page import="java.util.List"%>

<%
	List<Lock> locks = (List)request.getAttribute("locks");
	if((locks != null) && ( locks.size()>0) ) { 
%>

<script type="text/javascript">
function toggleSlider(){
	 slider=xGetElementById("slider");
	 container=xGetElementById("s_container");
   	var anim = new YAHOO.util.Anim(slider,{height:{to:0}},1,YAHOO.util.Easing.easeIn);
	 if(slider.style.height=="0px"){
		var anim = new YAHOO.util.Anim(slider,{height:{to:130}},1,YAHOO.util.Easing.easeOut);
		 container.className='containerb';
	 }
	 else{
		 container.className='container';
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

</script>

<style type="text/css">
.containerb {
width:400px;
	border:1px solid #FF9900;
	margin-top:10px;
	padding:0;
	position:relative;
	z-index:1;
}

.container {
    width:400px;
	border-left:1px solid #FF9900;
    border-top:1px solid #FF9900;
	border-right:1px solid #FF9900;
	border-bottom:0px;
	padding:0;
	margin-top:10px;
	position:relative;
}

.slider{
	position:relative;
	width:400px;
	height:130px;
	overflow:hidden;
	margin:0 0 0 0px;
	padding:0;
	z-index:99;
}

.scontent{
	position:absolute;
	top:0;
	left:0;
	width:400px;
	height: 100%;
	margin:0;
	padding:0;
	color:#000;
	z-index:1;
}

</style>
<div style="margin-left:10px;">
<div style="width:400px;cursor: pointer;"><a onClick="javascript:toggleSlider();">Active locks in your account!&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<div style="float:right;position:relative;"><img src="custom/images/info.png" style="align:right;padding-right:2px;"/></div></a>
                </div>
 <div id="s_container" class="container">
					<div id="slider" class="slider" style="height: 0px;">
						<div class="scontent" style="overflow:auto;">
				
<form name="lockrelease"  class="mopform">
<table style="font-size:0.9em;height:130px;width:100%">
<tr><td height="20" bgcolor="#E3EACC">
These mappings are locked under your account and cannot be used unless they are unlocked. Make sure you unlock items when you have finished working with them.

</td></tr>
			           
<%
for( Lock lk: locks ) {
%>
<tr>
<td valign="top" bgcolor="#FFFFFF" height="10" >
<input type="checkbox" name="lockCheck" value="<%=lk.getDbID()%>">
 <%=lk.getName() %><i></i>
</td>
</tr>
<tr><td height="1" bgcolor="#E3EACC"></td></tr>
<%} %>
<tr><td></td></tr>
<tr>
<td align="center" height="20">
<a onclick="javascript:checks=getCheckedLocks('lockrelease');ajaxLockSummary(checks,'delete' );" style="font-size:bold;cursor: pointer">Release selected</a>

</td>
</tr>

</table>
</form>
</div>

</div>
</div>
</div>
<%}%>