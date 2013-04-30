Redirecting to AMS Mint Please wait ...
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
				data: {mint_id: '<?php echo $mint_id; ?>', username: '<?php echo $email; ?>',
					first_name: '<?php echo $first_name; ?>', last_name: '<?php echo $last_name; ?>'},
				dataType: 'jsonp',
				success: function(result) {
					console.log(result);

				}
			});

		});


</script>