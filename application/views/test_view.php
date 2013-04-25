<form id="Login" name="login" action="http://mint.avpreserve.com:8080/mint-ams/Login.action" method="post" class="athform" style="width: 350px; display: block;">

	<fieldset>
		<ol>
			<li>
				<label for="Login_username" class="label"><span style="display: block; width: 150px;">Username<span class="required">*</span>:</span></label><input type="text" name="username" value="" id="Login_username" onkeypress="return submitenter(this, event)">


			</li>
			<li>
				<label for="Login_password" class="label"><span style="display: block; width: 150px;">Password<span class="required">*</span>:</span></label><input type="password" name="password" id="Login_password" onkeypress="return submitenter(this, event)">


			</li>
		</ol>
		<p align="left">

			<input type="submit" value="mint login"/>  
		</p>



	</fieldset>
</form>