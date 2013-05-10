<?php
$inbox_active = $sent_active = '';
if (isset($inbox) && $inbox)
	$inbox_active = ' active';
if (isset($sent) && $sent)
	$sent_active = ' active';
?>
<div class="span3" style="width: 240px;background: #ebebeb;">
	<div>
		<h6 class="filter_title" id="filter_criteria" style="font-weight: bold;">FOLDER</h6>
		<div class="detail-sidebar <?php echo $inbox_active; ?>">
			<a class="menu-anchor" href="<?php echo site_url('messages/inbox') ?>" ><h5>Inbox</h5></a>
		</div>
		<?php
		if ($this->can_compose_alert)
		{
			?>
			<div class="detail-sidebar <?php echo $sent_active; ?>">
				<a class="menu-anchor" href="<?php echo site_url('messages/sent') ?>" ><h5>Sent</h5></a>
			</div>
		<?php } ?>
		<div class="clearfix div-separater"></div>
		<h6 class="filter_title" id="filter_criteria" style="font-weight: bold;">FILTER</h6>
		<?php
		if ($this->can_compose_alert)
		{
			?>
			<div class="sidebar-fields" style="border-bottom: 1px solid #DDD;">
				<div><?php echo form_label('Station', $stations['id']); ?></div>
				<div>
					<select name="<?php echo $stations['id'] ?>" id="<?php echo $stations['id'] ?>" <?php echo $stations['function'] ?>>
						<option value="">Select</option>
						<?php
						foreach ($station_records as $value)
						{
							?>
							<option value="<?php echo $value->id; ?>"><?php echo $value->station_name; ?></option>
							<?php
						}
						?>
					</select>
				</div>
			</div>
		<?php } ?>
		<div class="sidebar-fields">
			<div><?php echo form_label('Message Type', $message_type['id']); ?></div>
			<div><?php echo form_dropdown($message_type['id'], $select, array(), $message_type['function'] . 'id="' . $message_type['id'] . '"'); ?></div>
		</div>

	</div>
</div>