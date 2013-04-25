<?php
$attributes = array(
	'id' => 'edit_from',
	'class' => 'form-horizontal');
?>
<div class="row-fluid">
	<?php
	if ($is_updated)
	{
		?>
		<div class="alert alert-success notification" style="margin: 20px;">Crawford Contact Detail Updated Successfully.</div>
	<?php } echo form_open_multipart($this->uri->uri_string(), $attributes); ?>
	<div class="control-group">
		<label class="control-label" for="inputEmail">Crawford Contact Details:</label>
		<div class="controls">
			<textarea type="text" id="crawford_contact_details" name="crawford_contact_details" style="width:400px;height: 100px;"><?php echo $detail->crawford_contact_detail; ?></textarea>
			<div class="clearfix"></div>
			<span style="color: firebrick;"><?php echo form_error('crawford_contact_details'); ?></span></td>
		</div>
	</div>
	<?php if ($this->role_id != 20)
	{
		?>
		<div class="control-group">
			<div class="controls">
				<input type="submit" class="btn btn-success" value="Save"/>
			</div>
		</div>
	<?php } ?>
<?php echo form_close(); ?>
</div>
<script type="text/javascript">
	setTimeout(function() {
		$('.alert').slideUp();
	}, 3000);
</script>