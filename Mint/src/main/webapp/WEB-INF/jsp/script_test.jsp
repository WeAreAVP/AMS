<%@ include file="top.jsp"%>


<div>
<h1>
<p>Script</p>
</h1>
 <%if(!user.hasRight(User.SUPER_USER)) {%>
   
    <span class="errorMessage">ACCESS DENIED.</span>
   
   <%} else{%>
   <s:form action="Script" cssClass="athform" theme="mytheme">
<s:select label="Scripts"
       name="scriptlet"
       headerKey="/" headerValue="Select scriptlet"
       list="lib"
       value="%{'/'}"
       onchange="$('form').submit()"
       
/>
<br/>

	<s:textarea name="script" spellcheck="false" rows="10" cols="60" label="Groovy script"  />

	<p align="left"><input type="submit" value="submit"
		class="inputButton" /><input type="reset" value="reset"
		class="inputButton" /></p>

</div>
</s:form>

The script output was: <br/>
<pre>
<s:property value="stdOut" />
</pre>

<s:if test="result!=null" >
The script returned: </br>
<pre>
<s:property value="result" />
</pre>
</s:if>
<%} %>
<%@ include file="footer.jsp"%>
