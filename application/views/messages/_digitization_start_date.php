<div id="type_1">
    <div class="control-group">
        <label class="control-label" for="shipping_instructions">Shipping Instructions:</label>
        <div class="controls">
            <textarea id="shipping_instructions" name="shipping_instructions"  rows="4" cols="80"></textarea>
            <span id="shipping_instructions_error" style="display: none;" class="message-type_error">Please Enter Shipping Instructions</span>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="comments">Comments:</label>
        <div class="controls">
            <textarea id="comments" name="comments" rows="4" cols="80"></textarea>
            <span id="comments_error" style="display: none;" class="message-type_error">Please Enter Comments</span>


        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="estimated_complete_date">Estimated Complete Date:</label>
        <div class="controls">
            <input id="estimated_complete_date" name="estimated_complete_date"/>
            <span id="estimated_complete_date_error" style="display: none;" class="message-type_error">Please Enter Estimated Complete Date</span>

        </div>
    </div>
	<div class="control-group" style="display: none;">
        <label class="control-label" for="crawford_contact_details">Crawford Contact Details:</label>
        <div class="controls">

            <textarea id="crawford_contact_details" name="crawford_contact_details" rows="4" cols="80"><?php echo $record->crawford_contact_detail; ?></textarea>
            <span id="crawford_contact_details_error" style="display: none;" class="message-type_error">Please Enter Crawford Contact Details</span>
        </div>
    </div>

</div>

<script type="text/javascript">
	$(function() {
		$("#estimated_complete_date").datepicker();
		checkDSD();

	});
	function checkFields() {
//        if($('#shipping_instructions').val()==''){
//            $('#shipping_instructions_error').show();
//            return false;
//        }
//        else {
//            $('#shipping_instructions_error').hide();
//        }
//            
//        if($('#comments').val()==''){
//            $('#comments_error').show();
//            return false;
//        } else {
//            $('#comments_error').hide();
//        }
//            
//        if($('#estimated_complete_date').val()==''){
//            $('#estimated_complete_date_error').show();
//            return false;
//        } else {
//            $('#estimated_complete_date_error').hide();
//        }
		return true;

	}
	function confirmBody() {
		shipping_instructions = $('#shipping_instructions').val();
		comments = $('#comments').val();
		estimated_complete_date = $('#estimated_complete_date').val();
		crawford_contact_details = $('#crawford_contact_details').val();

		extras = {
			shipping_instructions: shipping_instructions,
			comments: comments,
			estimated_complete_date: estimated_complete_date,
			crawford_contact_details: crawford_contact_details,
		};

		msg_body = 'Shipping Instructions: ' + shipping_instructions + '\n' +
		'Comments: ' + comments + '\n'
		'Estimated Complete Date: ' + estimated_complete_date + '\n';


		$('#confirm_body').html('<div><strong>To: ' + to_name + '</strong></div>' +
		'<div><strong>Subject: ' + subject + '</strong></div><br/>' +
		'<div>Shipping Instructions: ' + shipping_instructions + '</div>' +
		'<div>Comments: ' + comments + '</div>' +
		'<div>Crawford Contact Details: ' + crawford_contact_details + '</div>' +
		'<div>Estimated Complete Date: ' + estimated_complete_date + '</div>');
	}
	function checkDSD() {
		$.ajax({
			type: 'POST',
			url: site_url + 'stations/get_stations_info',
			data: {"stations": to},
			dataType: 'json',
			success: function(result) {
				$('#station_name_list').html('<div id="error_message" style="display:none;color:red;">Please select digitization start date(s).</div>');
				for (cnt in result) {
					record = result[cnt];
					if (record.dsd == '') {
						name = 'dsd_' + record.station_id;
						$('#station_name_list').append('<div><div><b>' + record.station_name + '</b></div><div><input type="text" name="' + name + '" id="' + name + '" /></div></div>');
					}
					else {
						$('#station_name_list').append('<div><div><b>' + record.station_name + '</b></div><div>DSD: ' + record.dsd + '</div></div>');
					}
				}
				$('#message_edit_title').html('Digitization Start Date(s)');
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
		if ($('#station_name_list input').length > 0) {
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
					url: site_url + 'stations/update_dsd_station',
					data: $('#manage_dates_form').serialize(),
					dataType: 'json',
					success: function(result) {
						$('#compose_to_type').modal('toggle');


					}
				});
			}
		}
		else {
			$('#edit_media_window').modal("toggle");
			$('#compose_to_type').modal('toggle');
		}



	}
</script>