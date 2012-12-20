<?php
$attributes	=	array	('onsubmit'	=>	'return false;',
				'id'							=>	'edit_from',
				'class'				=>	'form-horizontal');
?>
<div class="row-fluid">
				<?php	echo	form_open_multipart	($this->uri->uri_string	(),	$attributes);	?>
				<div class="control-group">
								<label class="control-label" for="inputEmail">Crawford Contact Detail</label>
								<div class="controls">
												<textarea type="text" id="crawford_contact_details" name="crawford_contact_details" style="width:400px;height: 100px;"></textarea>
								</div>
				</div>

				<div class="control-group">
								<div class="controls">
												<button type="submit" class="btn btn-primary">Save</button>
								</div>
				</div>
				<?php	echo	form_close	();	?>
</div>