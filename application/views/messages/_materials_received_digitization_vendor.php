<div id="type_2">
    <div class="control-group">
        <label class="control-label" for="date_received">Media Received Date:</label>
        <div class="controls">
            <input id="date_received" name="date_received"/>
            <span id="date_received_error" style="display: none;" class="message-type_error">Please Select Media Received Date</span>
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="comments">Comments:</label>
        <div class="controls">
            <input id="comments" name="comments"/>
            <span id="comments_error" style="display: none;" class="message-type_error">Please Enter Comments</span>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="crawford_contact_details">Crawford Contact Details:</label>
        <div class="controls">
            <input id="crawford_contact_details" name="crawford_contact_details"/>
            <span id="crawford_contact_details_error" style="display: none;" class="message-type_error">Please Enter Crawford Contact Details</span>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function() {
        $( "#date_received" ).datepicker();
    });
    function checkFields(){
        if($('#date_received').val()==''){
            $('#date_received_error').show();
            return false;
        }
        else {
            $('#date_received_error').hide();
        }
            
        if($('#comments').val()==''){
            $('#comments_error').show();
            return false;
        } else {
            $('#comments_error').hide();
        }
            
        if($('#crawford_contact_details').val()==''){
            $('#crawford_contact_details_error').show();
            return false;
        } else {
            $('#crawford_contact_details_error').hide();
        }
        return true;
            
    }
    function confirmBody(){
        date_received=$('#date_received').val();
        comments=$('#comments').val();
        crawford_contact_details=$('#crawford_contact_details').val();
        
        
        extras= {
            date_received: date_received,
            comments: comments,
            crawford_contact_details: crawford_contact_details
        };
                
        msg_body='Date Received: '+date_received+'\n'+
            'Comments: '+comments+'\n'+
            'Crawford Contact Details: '+crawford_contact_details+'\n' ; 
        
        
        $('#confirm_body').html('<div><strong>To: '+to_name+'</strong></div>'+
            '<div><strong>Subject: '+subject+'</strong></div><br/>'+
            '<div>Media Received Date: '+date_received+'</div>'+
            '<div>Comments: '+comments+'</div>'+
            '<div>Crawford Contact Details: '+crawford_contact_details+'</div>');
    }
</script>