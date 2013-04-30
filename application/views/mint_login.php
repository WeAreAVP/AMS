<form id="Login" name="login" action="http://mint.avpreserve.com:8080/mint-ams/Login.action" method="post" class="athform" style="display: none;">
	<input type="text" name="username" value="" id="Login_username" onkeypress="return submitenter(this, event)">
	<input type="password" name="password" id="Login_password" onkeypress="return submitenter(this, event)">
	<input type="submit" value="mint login"/>  
</form>
<script type="text/javascript">
		$(function() {
			$.ajax({
				type: 'GET',
				url: 'http://mint.avpreserve.com/pgconnect.php',
				data: data,
				dataType: 'jsonp',
				success: function(result) {
					console.log(result);

				}
			});

		});


</script>