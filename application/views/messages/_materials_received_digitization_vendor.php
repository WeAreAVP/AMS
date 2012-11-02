<div id="type_2">
    <div class="control-group">
        <label class="control-label" for="date_received">Media Received Date:</label>
        <div class="controls">
            <input id="date_received" name="date_received"/>

        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="comments">Comments:</label>
        <div class="controls">
            <input id="comments" name="comments"/>

        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="crawford_contact_details">Crawford Contact Details:</label>
        <div class="controls">
            <input id="crawford_contact_details" name="crawford_contact_details"/>

        </div>
    </div>
</div>

<script type="text/javascript">
    $(function() {
        $( "#date_received" ).datepicker();
    });
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