<div id="type_5">
    <div class="control-group">
        <label class="control-label" for="review_end_date">Review End Date:</label>
        <div class="controls">
            <input id="review_end_date" name="review_end_date"/>
            <span id="review_end_date_error" style="display: none;" class="message-type_error">Please Select Review End Date</span>

        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="comments">Comments:</label>
        <div class="controls">
            <input id="comments" name="comments"/>
            <span id="comments_error" style="display: none;" class="message-type_error">Please Enter Commets</span>

        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="ftp_detail">FTP Details:</label>
        <div class="controls">
            <input id="ftp_detail" name="ftp_detail"/>
            <span id="ftp_detail_error" style="display: none;" class="message-type_error">Please Enter FTP Details</span>

        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="media_list">Media List:</label>
        <div class="controls">
            <input id="media_list" name="media_list"/>
            <span id="media_list_error" style="display: none;" class="message-type_error">Please Enter Media List</span>

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
		$("#review_end_date").datepicker();
	});
	function checkFields() {
//        if($('#review_end_date').val()==''){
//            $('#review_end_date_error').show();
//            return false;
//        }
//        else {
//            $('#review_end_date_error').hide();
//        }
//            
//        if($('#comments').val()==''){
//            $('#comments_error').show();
//            return false;
//        } else {
//            $('#comments_error').hide();
//        }
//            
//        if($('#ftp_detail').val()==''){
//            $('#ftp_detail_error').show();
//            return false;
//        } else {
//            $('#ftp_detail_error').hide();
//        }
//        if($('#media_list').val()==''){
//            $('#media_list_error').show();
//            return false;
//        } else {
//            $('#media_list_error').hide();
//        }
		return true;

	}
	function confirmBody() {
		review_end_date = $('#review_end_date').val();
		comments = $('#comments').val();
		ftp_detail = $('#ftp_detail').val();
		media_list = $('#media_list').val();
		crawford_contact_details = $('#crawford_contact_details').val();

		extras = {
			review_end_date: review_end_date,
			comments: comments,
			ftp_details: ftp_detail,
			media_list: media_list,
			crawford_contact_details: crawford_contact_details,
		};


		$('#confirm_body').html('<div><strong>To: ' + to_name + '</strong></div>' +
		'<div><strong>Subject: ' + subject + '</strong></div><br/>' +
		'<div>Review End Date: ' + review_end_date + '</div>' +
		'<div>Comments: ' + comments + '</div>' +
		'<div>Crawford Contact Details: ' + crawford_contact_details + '</div>' +
		'<div>FTP Details: ' + ftp_detail + '</div>' +
		'<div>Media List: ' + media_list + '</div>');

		msg_body = 'Review End Date: ' + review_end_date + '\n' +
		'Comments: ' + comments + '\n' +
		'FTP Details: ' + ftp_detail + '\n' +
		'Media List: ' + media_list + '\n';
	}
</script>