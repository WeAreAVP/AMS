<div id="type_3">
	<div class="control-group">
        <label class="control-label" for="comments">Comments:</label>
        <div class="controls">
            <textarea id="comments" name="comments"rows="4" cols="80"></textarea>
            <span id="comments_error" style="display: none;" class="message-type_error">Please Enter Comments</span>
        </div>
    </div>
    <div class="control-group" style="display: none;">
        <label class="control-label" for="crawford_contact_details">Crawford Contact Details:</label>
        <div class="controls">

            <textarea id="crawford_contact_details" name="crawford_contact_details" rows="4" cols="80"><?php echo $record->crawford_contact_detail; ?></textarea>
            <span id="crawford_contact_details_error" style="display: none;" class="message-type_error">Please Enter Crawford Contact Details</span>
        </div>
    </div>


    <div class="control-group">
        <label class="control-label" for="assets_list">Asset List:</label>
        <div class="controls" style="margin-left: 180px;">
            <div><a href="javascript://;" onclick="showRecords(1)" target="_blank">Assets awaiting digitization to Buffer Materials</a></div>
            <div><a href="javascript://;" onclick="showRecords(2)" target="_blank">Assets Failed Digitization</a></div>
            <div><a href="javascript://;" onclick="showRecords(3)" target="_blank">Assets Waiting Digitization</a></div>

		</div>
		<form method="POST" action="" id="show_record_form" action="" target="_blank">
			<input name="station_ids" id="show_record_stations" value=""/>
		</form>
	</div>
</div>
<script type="text/javascript">
				$(function() {
					if ($('#receiver_id').val() != '157')
						checkTrackingDetails();

				});

				function checkFields() {
//        if($('#comments').val()==''){
//            $('#comments_error').show();
//            return false;
//        }
//        else {
//            $('#comments_error').hide();
//        }
//        if($('#crawford_contact_details').val()==''){
//            $('#crawford_contact_details_error').show();
//            return false;
//        }
//        else {
//            $('#crawford_contact_details_error').hide();
//        }
					return true;

				}
				function showRecords(type) {

					url = 'messages/assets_list/' + type;
					$('#show_record_stations').val(to);
					$('#show_record_form').attr('action', site_url + url);
					$('#show_record_form').submit();

				}
				function confirmBody() {
					comments = $('#comments').val();
					crawford_contact_details = $('#crawford_contact_details').val();
					extras = {
						comments: comments,
						crawford_contact_details: crawford_contact_details,
					};

					$('#confirm_body').html('<div><strong>To: ' + to_name + '</strong></div>' +
					'<div><strong>Subject: ' + subject + '</strong></div><br/>' +
					'<div>Comments: ' + comments + '</div>' +
					'<div>Crawford Contact Details: ' + crawford_contact_details + '</div>'
					);
					msg_body = 'Comments: ' + comments + '\n' +
					'Crawford Contact Details: ' + crawford_contact_details + '\n';
				}

				manageView = new Array(0, 0, 0);
				function checkTrackingDetails() {
					$.ajax({
						type: 'POST',
						url: site_url + 'tracking/get_tracking_info',
						data: {"stations": to, 'type': 'ship_date'},
						dataType: 'json',
						success: function(result) {
							$('#station_name_list').html('<div id="error_message" style="display:none;color:red;">Please manage ship date(s).</div>');
							for (cnt in result) {
								record = result[cnt];
								if (record.tracking_id == '') {
									manageView[0] = 1;
									$('#station_name_list').append('<div><div><b>' + record.station_name + '</b></div><div>No Tracking Information.</div></div>');
								}
								else if (record.tracking_id != '' && record.media_received_date == '') {
									manageView[1] = 1;
									name = 'shipment_return_' + record.tracking_id;
									$('#station_name_list').append('<div><div><b>' + record.station_name + '</b></div><div><input type="text" name="' + name + '" id="' + name + '" /></div></div>');
								}
								else {
									manageView[2] = 1;
									$('#station_name_list').append('<div><div><b>' + record.station_name + '</b></div><div>Shipment Date: ' + record.ship_date + '</div></div>');
								}
							}
							if (manageView[0] == 1 && manageView[1] == 0 && manageView[2] == 0) {
								$('#next_btn').hide();
							}
							$('#message_edit_title').html('Manage Shipment Date');
							$('#compose_to_type').modal('toggle');
							$('#edit_media_window').modal({
								backdrop: 'static',
							});
							$("#station_name_list input").datepicker({dateFormat: 'yy-mm-dd'});



						}
					});
				}
				function checkDates() {
					error = 0;
					if (manageView[1] == 1) {
						$('#station_name_list input').each(function(index, object) {
							if ($('#' + object.id).val() == '') {
								$('#error_message').show();
								error = 1;
							}
						});
						if (error == 0) {
							$('#edit_media_window').modal("toggle");
							$.ajax({
								type: 'POST',
								url: site_url + 'tracking/update_tracking_info',
								data: $('#manage_dates_form').serialize(),
								dataType: 'json',
								success: function(result) {
									$('#compose_to_type').modal('toggle');


								}
							});
						}
					}
					else if (manageView[2] == 1) {
						$('#edit_media_window').modal("toggle");
						$('#compose_to_type').modal('toggle');
					}
				}
</script>