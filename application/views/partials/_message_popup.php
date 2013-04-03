<div class="modal hide" id="compose_confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>Send Message >> Confirm</h3>

    </div>
    <div class="modal-body" id="confirm_body">

    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true" onclick="$('#compose_anchor').trigger('click');">Back</button>
        <button class="btn btn-primary"  data-dismiss="modal" onclick="sentEmail();">Send</button>
    </div>
</div>



<div class="modal hide" id="edit_media_window" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="message_edit_title">Add Media Received Date</h3>
    </div>
    <div class="modal-body">
        <form id="manage_dates_form">
            <div id="station_name_list"></div>
        </form>

    </div>
    <div class="modal-footer">
        <button class="btn edit-btn" data-dismiss="modal" aria-hidden="true" onclick="resetMessagesForm();">Close</button>
        <button class="btn btn-primary edit-btn" aria-hidden="true" onclick="checkDates();" id="next_btn">Next</button>
    </div>
</div>
<script>
			function sentEmail() {
				$.ajax({
					type: 'POST',
					url: '<?php echo site_url('messages/compose') ?>',
					data: {"extras": extras, to: to, subject: subject, type: type, html: msg_body},
					dataType: 'json',
					success: function(result) {
						if (result.success)
						{
							window.location.reload();
						}
						else
						{
							if (error_id == 1)
							{
								$('#message_station_error').show();
							}
							else
							{
								$('#message_type_error').show();
							}
						}
					}
				});
			}
			function resetMessagesForm() {
				$('#alert_type').html('');
				$('#msg_type').val('');
				$('.edit-btn').each(function() {
					$(this).show();
				});
			}
</script>