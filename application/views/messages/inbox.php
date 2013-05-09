<?php
$select[] = 'Select';
foreach ($this->config->item('messages_type') as $msg_type)
{
	$index = str_replace(' ', '_', $msg_type);
	$select[] = $msg_type;
}
if ( ! $is_ajax)
{
	$stations = array(
		'name' => 'stations',
		'id' => 'stations',
		'function' => 'onchange="filter_inbox();"',
	);
	$message_type = array(
		'name' => 'message_type',
		'id' => 'message_type',
		'function' => 'onchange="filter_inbox();"',
	);
	?>
	<br/>
	<div class="row-fluid">
		<?php
		if ($this->can_compose_alert)
		{
			?>
			<?php
			if (isset($this->session->userdata['sent']))
			{
				?><div class="alert" style="margin-bottom: 0px; margin-top: 0px;"><strong><?php echo $this->session->userdata['sent']; ?></strong></div><br/><?php } $this->session->unset_userdata('sent'); ?>
			<div class="span3" style="margin-bottom: 15px;">
				<a href="#compose_to_type" class="btn btn-large" data-toggle="modal" data-backdrop="static" id="compose_anchor">Compose Message</a>
				<a href="#compose_confirm"  data-toggle="modal" id="confirm_anchor"  data-backdrop="static" style="display: none;"></a>
			</div>
		<?php } ?>
	</div>
	<div class="row-fluid">
		<div class="span3">
			<div id="search_bar">
				<b><h4>Folders</h4></b>
				<div style="padding: 8px;background: none repeat scroll 0% 0% rgb(0, 152, 214);" >
					<a  style="color: white;" href="<?php echo site_url('messages/inbox') ?>" >Inbox</a></div>
				<?php
				if ($this->can_compose_alert)
				{
					?>
					<div style="padding: 8px;" >	<a href="<?php echo site_url('messages/sent') ?>" >Sent</a></div>

					<br/>
					<div>
						<?php echo form_label('Stations', $stations['id']); ?>
					</div>
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
				<?php } ?>
				<div>
					<?php echo form_label('Message Type', $message_type['id']); ?>
				</div>
				<div>
					<?php echo form_dropdown($message_type['id'], $select, array(), $message_type['function'] . 'id="' . $message_type['id'] . '"'); ?>
				</div>
			</div>
		</div>

		<div  class="span9">
			<div class="alert" style="margin-bottom: 0px; margin-top: 0px;display: none;" id="success_message"></div>
			<div style="overflow: auto;height: 600px;" >
				<table class="tablesorter table table-bordered" id="station_table">
					<?php
					if (count($results) > 0)
					{
						?>
						<thead>
							<tr>
								<td><span style="float:left;min-width:50px; padding:4px;font-weight:bold">From</span></td>
								<td><span style="float:left;min-width:80px; padding:4px;font-weight:bold">Subject</span></td>
								<th><span style="float:left;min-width:90px;">Date</span></th>
							</tr>
						</thead>
					<?php } ?>
					<tbody id="append_record"><?php
					}
					if (count($results) > 0)
					{
						foreach ($results as $row)
						{
							?>
							<tr id="row_<?php echo $row->id ?>" style="cursor: pointer;<?php
							if ($row->msg_status == 'unread')
							{
								?> font-weight:bold;<?php } ?>" onclick="read_inbox_msg('<?php echo $row->id ?>')">
								<td><?php echo $row->full_name; ?></td>
								<td><?php echo $row->subject; ?></td>
								<td><?php echo date("m/d/Y", strtotime($row->created_at)); ?></td>
							</tr>
							<?php
						}
					}
					else
					{
						?>
						<tr><td colspan="11" style="text-align: center;"><b>No Message Found.</b></td></tr>
					<?php } ?>
					<?php
					if ( ! $is_ajax)
					{
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<script type="text/javascript">
									function filter_inbox() {
										var stations = $('#stations').val();
										var message_type = $('#message_type').val();
										$.ajax({
											type: 'POST',
											url: site_url + 'messages/inbox',
											data: {stations: stations, message_type: message_type},
											cache: false,
											success: function(result) {
												$('#append_record').html(result);
												$("#station_table").trigger("update");
												$("[rel=tooltip]").tooltip();

											}
										});
									}
									function read_inbox_msg(id)
									{
										$('#row_' + id).css('font-weight', 'normal');
										$.ajax({
											type: 'POST',
											url: site_url + 'messages/readmessage/' + id,
											cache: false,
											datatype: 'json',
											success: function(r)
											{
												$('#row_' + id).css('font-weight', 'normal');
												result = eval('(' + r + ')');
												if (result.error == false)
												{
													if (result.reset_row)
													{
														$("#row_" + id).css('font-weight', 'normal');
													}
													$('#myGeneral_body').html(result.msg_data);
													$('#msg_text_link').html(result.total_unread_text);
													$('#myGeneral').modal('show');
												}
											}
										});
									}

	</script>

	<?php
}
else
{
	exit();
	?>
	<script type="text/javascript"> $("#station_table").tablesorter();</script>
<?php } ?>


