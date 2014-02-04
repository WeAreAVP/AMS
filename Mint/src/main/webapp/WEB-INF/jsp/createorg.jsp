<%@ include file="top.jsp"%>
<script type="text/javascript">
ddtabmenu.definemenu("menu", 2) //tab 1 selected

</script>
<s:form action="Submitorg" cssClass="athform">
	<fieldset><legend>Create organization</legend> <s:textfield
		name="orgnaname" label="Organization name" required="true" /> <s:textfield
		name="address" label="Address" required="true" />
	<tr>
		<td>Country:</td>
		<td><select name="coutry">
			<option>-- please select --</option>
			<option>Austria</option>
			<option>Belgium</option>
			<option>Bulgaria</option>
			<option>Cyprus</option>
			<option>Czech Rep.</option>
			<option>Denmark</option>
			<option>Estonia</option>
			<option>Finland</option>
			<option>France</option>
			<option>Germany</option>
			<option>Greece</option>
			<option>Hungary</option>
			<option>Ireland</option>
			<option>Italy</option>
			<option>Latvia</option>
			<option>Lithuania</option>
			<option>Luxembourg</option>
			<option>Malta</option>
			<option>Netherlands</option>
			<option>Poland</option>
			<option>Portugal</option>
			<option>Romania</option>
			<option>Slovakia</option>
			<option>Slovenia</option>
			<option>Spain</option>
			<option>Sweden</option>
			<option>United Kingdom</option>
			<option>Israel</option>
		</select></td>
	</tr>
	<tr>
		<td>Type of organization:</td>
		<td><select name="orgtype">
			<option>-- please select --</option>
			<option value="museum">Museum</option>
			<option value="library">Library</option>
			<option value="archive">Archive</option>
			<option value="sarchive">Sound Archive</option>
			<option value="aggregator">Aggregator</option>
			<option value="other">Other</option>
		</select></td>
	</tr>
	<s:textfield name="orgtype2" label="Define other" required="true" />
	<tr>
		<td>Parent organization:</td>
		<td><select name="parent">
			<option value="-1">-- N/A --</option>
			<option value="org1">Org1</option>
			<option value="org2">Org2</option>
		</select></td>
	</tr>
	</fieldset>
	<tr>
		<td align="right"><input type="submit" value="submit"
			class="inputButton" /></td>

		<td><input type="reset" value="reset" class="inputButton" /></td>
	</tr>
	<tr>
		<td colspan="2"><s:actionerror /></td>
	</tr>
</s:form>

<%@ include file="footer.jsp"%>
