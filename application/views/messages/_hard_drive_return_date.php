<div id="type_4">
    <div class="control-group">
        <label class="control-label" for="return_date">Return Date:</label>
        <div class="controls">
            <input id="return_date" name="return_date"/>
            <span id="return_date_error" style="display: none;" class="message-type_error">Please Select Return Date</span>
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="comments">Comments:</label>
        <div class="controls">
            <input id="comments" name="comments"/>
            <span id="comments_error" style="display: none;" class="message-type_error">Please Enter Comments</span>
        </div>
    </div>
    <div class="control-group" style="display: none;">
        <label class="control-label" for="crawford_contact_details">Crawford Contact Details:</label>
        <div class="controls">
												
            <textarea id="crawford_contact_details" name="crawford_contact_details" rows="4" cols="80"><?php	echo	$record->crawford_contact_detail;	?></textarea>
            <span id="crawford_contact_details_error" style="display: none;" class="message-type_error">Please Enter Crawford Contact Details</span>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="hd_list">Hard drive List:</label>
        <div class="controls">
            <input id="hd_list" name="hd_list"/>
            <span id="hd_list_error" style="display: none;" class="message-type_error">Please Select Hard drive List</span>
        </div>
    </div>

</div>
<script type="text/javascript">
    $(function() {
        $( "#return_date" ).datepicker();
    });
    function checkFields(){
//        if($('#return_date').val()==''){
//            $('#return_date_error').show();
//            return false;
//        }
//        else {
//            $('#return_date_error').hide();
//        }
//            
//        if($('#comments').val()==''){
//            $('#comments_error').show();
//            return false;
//        } else {
//            $('#comments_error').hide();
//        }
//            
//        if($('#crawford_contact_details').val()==''){
//            $('#crawford_contact_details_error').show();
//            return false;
//        } else {
//            $('#crawford_contact_details_error').hide();
//        }
//        if($('#hd_list').val()==''){
//            $('#hd_list_error').show();
//            return false;
//        } else {
//            $('#hd_list_error').hide();
//        }
        return true;
            
    }
    function confirmBody(){
        return_date=$('#return_date').val();
        comments=$('#comments').val();
        hd_list=$('#hd_list').val();
        crawford_contact_details=$('#crawford_contact_details').val();
                
                
        extras= {
            return_date: return_date,
            comments: comments,
            hard_drive_list: hd_list,
            crawford_contact_details:crawford_contact_details
        };
        
        $('#confirm_body').html('<div><strong>To: '+to_name+'</strong></div>'+
            '<div><strong>Subject: '+subject+'</strong></div><br/>'+
            '<div>Return Date: '+return_date+'</div>'+
            '<div>Comments: '+comments+'</div>'+
            '<div>Crawford Contact Details: '+crawford_contact_details+'</div>'+ 
            '<div>Hard Drive List: '+hd_list+'</div>' );
        
        msg_body='Return Date: '+return_date+'\n'+
            'Comments: '+comments+'\n'+
            'Crawford Contact Details: '+crawford_contact_details+'\n'+ 
            'Hard Drive List: '+hd_list+'\n';
    }
</script>