<?php
$attributes	=	array(
				'id'				=>	'edit_from',
				'class'	=>	'form-horizontal');
?>
<div class="row-fluid">
				<?php	echo	form_open_multipart($this->uri->uri_string(),	$attributes);	?>
				<div class="control-group">
								<label class="control-label" for="inputEmail">Crawford Contact Details</label>
								<div class="controls">
												<textarea type="text" id="crawford_contact_details" name="crawford_contact_details" style="width:400px;height: 100px;"><?php	echo	$detail->crawford_contact_detail;	?></textarea>
								</div>
				</div>

				<div class="control-group">
								<div class="controls">
												<input type="submit" class="btn btn-success" value="Save"/>
								</div>
				</div>
				<?php	echo	form_close();	?>
</div>