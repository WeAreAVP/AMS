<%@ include file="top.jsp" %>  
<script type="text/javascript">
ddtabmenu.definemenu("menu", 4) //tab 1 selected

</script>
<s:form action="Createuser" cssClass="athform">
<fieldset>
     <legend>Create new user</legend>
    <s:textfield name="username" label="Username" required="true"/>
    <s:password  name="password" label="Password" required="true"/>
	<s:password  name="passwordconf" label="Password Confirmation" required="true"/>
	<s:textfield name="firstName" label="First Name" required="true"/>
	<s:textfield name="lastName" label="Last Name" required="true"/>
	<s:textfield name="email" label="Email" required="true"/>
	<s:textfield name="tel" label="Contact phone num"/>	
	<s:textfield name="jobrole" label="Job role" required="true"/>
	<tr><td>Organization:</td><td><select name="parent">
    <option value="-1">-- N/A --</option>
       <option value="org1">Org1</option>
       <option value="org2">Org2</option>
      </select>
    </td>
    </tr> 
    <tr><td>Type of user:</td><td><select name="utype">
    <option value="-1">-- please select --</option>
       <option value="u1">Admin</option>
       <option value="u2">Annotator</option>
       <option value="u3">Simple</option>
      </select>
    </td>
    </tr>       
</fieldset>
	<tr><td align="right">
    <input type="submit" value="submit" class="inputButton"/>
    </td>
    
    <td><input type="reset" value="reset" class="inputButton"/>
    </td>
    </tr>
    <tr><td colspan="2"><s:actionerror /></td></tr>
</s:form>

<%@ include file="footer.jsp" %>  
