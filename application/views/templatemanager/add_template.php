<script src="<?php	echo	site_url	('tiny_mce/tiny_mce.js')	?>" ></script><?php
$system_id	=	array	(
				'name'			=>	'system_id',
				'id'					=>	'system_id',
				'value'		=>	set_value	('system_id'),
);
$subject	=	array	(
				'name'						=>	'subject',
				'id'								=>	'subject',
				'value'					=>	set_value	('subject'),
);
$body_plain	=	array	(
				'name'					=>	'body_plain',
				'id'							=>	'body_plain',
				'value'				=>	set_value	('body_plain'),
);
$body_html	=	array	(
				'name'											=>	'body_html',
				'id'													=>	'body_html',
				'value'										=>	set_value	('body_html'),
);
$form_attributes	=	array	('id'					=>	'add_template');
$options	=	array	('plain'	=>	'Plain',	'html'		=>	'Html');
$from	=	array	(
				'name'				=>	'email_from',
				'id'						=>	'email_from',
				'value'			=>	set_value	('email_from'),
);
$reply_to	=	array	(
				'name'							=>	'reply_to',
				'id'									=>	'reply_to',
				'value'						=>	set_value	('reply_to'),
);
$replacebale	=	array	(
				'name'																			=>	'replaceables',
				'id'																					=>	'replaceables',
				'value'																		=>	set_value	('replaceables'),
);
//$crawford_contact_detail	=	array	(
//				'name'							=>	'crawford_contact_detail',
//				'id'									=>	'crawford_contact_detail',
//				'value'						=>	set_value	('crawford_contact_detail'),
//);
//$is_crawford	=	array	(
//				'name'	=>	'is_crawford',
//				'id'			=>	'is_crawford',
//);
//$replacebale=array("{station name}","{first name}","{last name}","{start date}","{end date}");

echo	form_open_multipart	(site_url	('templatemanager/add'),	$form_attributes);
?>
<?php
if	($add_temp)
{
				?>
				<div class="alert alert-success">Template Added Successfully</div>
<?php	}	?>
<table class="table no_border">

    <tr>
								<td><?php	echo	form_label	('System id*',	$system_id['id']);	?></td>
								<td><?php	echo	form_input	($system_id);	?><span class="help-inline"><?php	echo	form_error	($system_id['id']);	?></span></td>

				</tr>
				<tr>
								<td><?php	echo	form_label	('Email From*',	$from['id']);	?></td>
								<td><?php	echo	form_input	($from);	?><span class="help-inline"><?php	echo	form_error	($from['id']);	?></span></td>

				</tr>
				<tr>
								<td><?php	echo	form_label	('Reply To*',	$reply_to['id']);	?></td>
								<td><?php	echo	form_input	($reply_to);	?><span class="help-inline"><?php	echo	form_error	($reply_to['id']);	?></span></td>

				</tr>
				<tr>
								<td><?php	echo	form_label	('Subject*',	$subject['id']);	?></td>
								<td><?php	echo	form_input	($subject);	?><span class="help-inline"><?php	echo	form_error	($subject['id']);	?></span></td>

				</tr>
				<tr>
								<td><?php	echo	form_label	('Replaceables',	$subject['id']);	?></td>
								<td><?php	echo	form_textarea	($replacebale);	?></td>

				</tr>
				<tr>
       	<td><?php	echo	form_label	('Email Type',	'email_type');	?></td>
        <td>
												<select name="email_type" id="email_type" class="span2" onchange="toggal_message_body();">
																<option value="plain" <?php	echo	set_select	('email_type',	'plain',	TRUE);	?>>Plain</option>
																<option value="html" <?php	echo	set_select	('email_type',	'html');	?>>Html</option>
												</select>
								</td>
				</tr>
				<tr>
								<td><?php	echo	form_label	('Plain Body',	$body_plain['id']);	?></td>
								<td><?php	echo	form_textarea	($body_plain);	?></td>

				</tr>
				<tr id="html_body_msg" style="display:none">
								<td><?php	echo	form_label	('Html Body',	$body_html['id']);	?></td>
								<td><?php	echo	form_textarea	($body_html);	?></td>

				</tr>
<!--				<tr>
								<td><?php	echo	form_label	('Crawford Contact Detail',	$crawford_contact_detail['id']);	?></td>
								<td><?php	echo	form_checkbox	($is_crawford,	1,	TRUE,	'onclick="toggleCrawford();"');	?>
												<div><?php	echo	form_textarea	($crawford_contact_detail);	?></div>
								</td>
				</tr>-->




				<tr>
        <td colspan="2" align="center"><?php	echo	form_submit	('addtemplate',	'Add Template',	'class="btn" ');	?></td>
    </tr>
</table>
<?php	echo	form_close	();	?>
				<script type="text/javascript"> 
								function toggleCrawford(){
												$('#crawford_contact_detail').toggle();
												if(!$('#crawford_contact_detail').is(':visible')){
																$('#crawford_contact_detail').val('');
												}
								}
				</script>