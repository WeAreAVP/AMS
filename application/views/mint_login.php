<h3>Redirecting to AMS Mint Please wait ...</h3>
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
				data: {user_id:'<?php echo $user_id; ?>',mint_id: '<?php echo $mint_id; ?>', username: '<?php echo $email; ?>',
					first_name: '<?php echo $first_name; ?>', last_name: '<?php echo $last_name; ?>',rights:'<?php echo $rights; ?>'},
				dataType: 'jsonp',
				success: function(result) {
					if (result.success == 'true') {
						$('#Login_username').val(result.result[1]);
						$('#Login_password').val('x0h0@123');
						saveMintIDToDB(result.result,result.user_id);
					}
					else {
						alert('Something went wrong. Please Refresh the page.');
					}

				}
			});

		});
		function saveMintIDToDB(result,user_id) {
			$.ajax({
				type: 'POST',
				url: site_url + 'autocomplete/update_user',
				data: {mint_id: result[0],user_id:user_id},
				dataType: 'json',
				success: function(result) {
					if (result.success == 'true') {
						$('#Login').submit();
					}

				}
			});
		}

</script>