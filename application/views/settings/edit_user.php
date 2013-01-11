<?php
$email	=	array(
				'name'				=>	'email',
				'id'						=>	'email',
				'value'			=>	$user_info->email,
);
$password	=	array(
				'name'						=>	'password',
				'id'								=>	'password',
				'value'					=>	set_value('password'),
);
$first_name	=	array(
				'name'					=>	'first_name',
				'id'							=>	'first_name',
				'value'				=>	$user_info->first_name,
);
$last_name	=	array(
				'name'				=>	'last_name',
				'id'						=>	'last_name',
				'value'			=>	$user_info->last_name,
);
$phone_no	=	array(
				'name'		=>	'phone_no',
				'id'				=>	'phone_no',
				'value'	=>	$user_info->phone_no,
);

$title = array(
    'name' => 'title',
    'id' => 'title',
    'value' => $user_info->title,
);
$fax	=	array(
				'name'			=>	'fax',
				'id'					=>	'fax',
				'value'		=>	$user_info->fax,
);
$address	=	array(
				'name'		=>	'address',
				'id'				=>	'address',
				'value'	=>	$user_info->address,
);
$role	=	array(
				'name'			=>	'role',
				'id'					=>	'role',
				'value'		=>	$user_info->role_id,
);
$station	=	array(
				'name'						=>	'station',
				'id'								=>	'station',
				'value'					=>	$user_info->station_id,
);
$attributes	=	null;
if(	!	isset($profile_edit))
				$attributes	=	array('onsubmit'	=>	'return false;',	'id'							=>	'edit_from');
?>

<center>
				<?php	echo	form_open_multipart($this->uri->uri_string(),	$attributes);	?>
    <table class="table no_border">



        <tr>
            <td class="_label"><?php	echo	form_label('Email:',	$email['id']);	?></td>
            <td><?php	echo	form_input($email);	?><span style="color: red;"><?php	echo	form_error($email['name']);	?></span></td>
        </tr>


        <tr>
            <td class="_label"><?php	echo	form_label('Password:',	$password['id']);	?></td>
            <td><?php	echo	form_password($password);	?><div style="clear: both;"></div>Leave blank if you not want to change<span style="color: red;"><?php	echo	form_error($password['name']);	?></span></td>

        </tr>


        <tr>
            <td class="_label""><?php	echo	form_label('First Name:',	$first_name['id']);	?></td>
            <td><?php	echo	form_input($first_name);	?><span style="color: red;"><?php	echo	form_error($first_name['name']);	?></span></td>
        </tr>




        <tr>
            <td class="_label"><?php	echo	form_label('Last Name:',	$last_name['id']);	?></td>
            <td><?php	echo	form_input($last_name);	?><span style="color: red;"><?php	echo	form_error($last_name['name']);	?></span></td>
        </tr>


        <tr>
            <td class="_label"><?php	echo	form_label('Phone #:',	$phone_no['id']);	?></td>
            <td><?php	echo	form_input($phone_no);	?><span style="color: red;"><?php	echo	form_error($phone_no['name']);	?></span></td>
        </tr>
        <tr>
            <td class="_label"><?php	echo	form_label('Title:',	$title['id']);	?></td>
            <td><?php	echo	form_input($title);	?><span style="color: red;"><?php	echo	form_error($title['name']);	?></span></td>
        </tr>
        <tr>
            <td class="_label"><?php	echo	form_label('Fax :',	$fax['id']);	?></td>
            <td><?php	echo	form_input($fax);	?><span style="color: red;"><?php	echo	form_error($fax['name']);	?></span></td>
        </tr>
        <tr>
            <td class="_label"><?php	echo	form_label('Address:',	$address['id']);	?></td>
            <td><?php	echo	form_input($address);	?><span style="color: red;"><?php	echo	form_error($address['name']);	?></span></td>
        </tr>


								<?php
								if(	!	isset($profile_edit))
								{
												?>
												<tr>
																<td class="_label"><?php	echo	form_label('Role:',	$role['id']);	?></td>
																<td><?php	echo	form_dropdown($role['id'],	$roles,	array($user_info->role_id),	'id="role" onchange="checkRole();"');	?><span style="color: red;"><?php	echo	form_error($role['name']);	?></span></td>
												</tr>
								<?php	}	?>
        <tr id="station_row"  style="display: none;">
												<?php
												if($this->is_station_user)
												{
																?>
																<td><?php	echo	form_hidden($station['name'],	$this->station_id);	?></td>
																<?php
												}
												else
												{
																?>
																<td class="_label"><?php	echo	form_label('Station:',	$station['id']);	?></td>
																<td><?php	echo	form_dropdown($station['id'],	$stations_list,	array($user_info->station_id));	?><span style="color: red;"><?php	echo	form_error($station['name']);	?></span></td>
												<?php	}	?>
								</tr>
        <tr>
												<?php
												if(isset($profile_edit))
												{
																?>
																<td colspan="2">
																				<?php	echo	form_submit('save',	'Save',	'class="btn btn-primary"');	?>
																</td>

																<?php
												}
												else
												{
																?>
																<td colspan="2" style="text-align: right;">

																				<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>&nbsp;<?php	echo	form_submit('save',	'Update',	'class="btn btn-primary btn-custom" onclick="manageUser(\'post\',\'edit_user/'	.	$user_info->id	.	'\');" ');	?>

																</td>
												<?php	}	?>
        </tr>


    </table>

				<?php	echo	form_close();	?>
</center>