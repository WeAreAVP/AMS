<div id="type_4">
    <div class="control-group">
        <label class="control-label" for="return_date">Return Date:</label>
        <div class="controls">
            <input id="return_date" name="return_date"/>

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
    <div class="control-group">
        <label class="control-label" for="hd_list">Hard drive List:</label>
        <div class="controls">
            <input id="hd_list" name="hd_list"/>

        </div>
    </div>

</div>
<script type="text/javascript">
    $(function() {
        $( "#return_date" ).datepicker();
    });
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