<html>
	<head><title>Manage URI Permissions</title></head>
	<body>	
	<?php  				
		// Build drop down menu
		foreach ($roles as $role)
		{
			$options[$role->id] = $role->name;
		}

		// Change allowed uri to string to be inserted in text area
		if ( ! empty($allowed_uris))
		{
			$allowed_uris = implode("\n", $allowed_uris);
		}
		echo '<h3>Manage URI Permissions</h3>';
		// Build form
		echo form_open($this->uri->uri_string());
		echo '<div>';
		echo form_label('Role:', 'role_name_label',array('style'=>'float:left;margin: 5px 15px;'));
		echo form_dropdown('role', $options); 
                echo '</div>';
		echo form_submit('show', 'Show URI permissions','class="btn" style="margin:10px 60px;"'); 
		
		echo form_label('', 'uri_label');
				
		
		echo '<div>';		
		echo '<b>Allowed URI (One URI per line) :</b><br/><br/>';
		
		echo "Input '/' to allow role access all URI.<br/>";
		echo "Input '/controller/' to allow role access controller and it's function.<br/>";
		echo "Input '/controller/function/' to allow role access controller/function only.<br/><br/>";
//		echo 'These rules only have effect if you use check_uri_permissions() in your controller<br/><br/>.';
		
		echo form_textarea('allowed_uris', $allowed_uris); 
				
		echo '<br/>';
		echo form_submit('save', 'Save URI Permissions','class="btn"');
		echo '<div>';
		echo form_close();
	?>
	</body>
</html>