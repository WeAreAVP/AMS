<?php
$select[] = 'Select';
foreach ($this->config->item('messages_type') as $msg_type)
{
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
	<div class="row-fluid">
		<?php $this->load->view('messages/_side_bar', array('stations' => $stations, 'message_type' => $message_type, 'select' => $select, 'sent' => TRUE)); ?>
		<div  class="span9" style="width: 699px;">
			<?php
			if (isset($this->session->userdata['sent']))
			{
				?>
				<div class="alert" style="margin-bottom: 0px; margin-top: 0px;"><strong><?php echo $this->session->userdata['sent']; ?></strong></div><br/>
				<?php
				$this->session->unset_userdata('sent');
			}
			?>
			<div>
				<a href="#compose_to_type" class="btn" data-toggle="modal" data-backdrop="static" id="compose_anchor">Compose Message</a>
				<a href="#compose_confirm"  data-toggle="modal" id="confirm_anchor"  data-backdrop="static" style="display: none;"></a>
			</div>
			<div class="alert" style="margin-bottom: 0px; margin-top: 0px;display: none;" id="success_message"></div>
			<div style="overflow: auto;height: 600px;" >
				<table class="tablesorter table table-bordered" id="station_table">
					<?php
					if (count($results) > 0)
					{
						?>
						<thead>
							<tr style="background: #ebebeb;">
								<th width="150">To</span></th>
								<th width="110">Subject</span></th>
								<th width="110">Message Type</span></th>
								<th width="90">Alert Status</span></th>
								<th width="90">Email Status</span></th>
								<th width="90">Send Date</span></th>
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
							<tr style="cursor: pointer;" onclick="read_msg('<?php echo $row->id ?>')">
								<td><?php echo $row->full_name; ?></td>
								<td><?php echo $row->subject; ?></td>
								<td><?php echo $select[$row->msg_type]; ?></td>
								<td><?php
									if ($row->msg_status == 'read')
									{
										?>
										<a data-placement="top" rel="tooltip" href="#" data-original-title="<?php echo "Message last read on " . date("m/d/Y", strtotime($row->read_at)); ?>"><?php echo ucfirst($row->msg_status); ?>
										</a><?php
									}
									else
									{
										echo ucfirst($row->msg_status);
									}
									?>
								</td>
								<td><?php
									if ($row->is_email_read == 2)
									{
										?>
										<a data-placement="top" rel="tooltip" href="#" data-original-title="<?php echo "Email read on " . date("m/d/Y", strtotime($row->email_read_at)); ?>">
											Email Read
										</a><?php
									}
									else
									{
										echo 'Un-read';
									}
									?>
								</td>

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
								function read_sent_msg(id)
								{
									$.ajax({
										type: 'POST',
										url: site_url + 'messages/readsentmessage/' + id,
										cache: false,
										datatype: 'json',
										success: function(r)
										{
											result = eval('(' + r + ')');
											if (result.error == false)
											{
												$('#myGeneral_body').html(result.msg_data);
												$('#myGeneral').modal('show');
											}
										}
									});
								}
								function read_msg(id)
								{
									read_sent_msg(id);
								}
								function filter_inbox() {
									var stations = $('#stations').val();
									var message_type = $('#message_type').val();
									$.ajax({
										type: 'POST',
										url: site_url + 'messages/sent',
										data: {stations: stations, message_type: message_type},
										cache: false,
										success: function(result) {
											$('#append_record').html(result);
											$("#station_table").trigger("update");
											$("[rel=tooltip]").tooltip();
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