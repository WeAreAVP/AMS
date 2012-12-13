<div id="type_2">
    <div class="control-group">
        <label class="control-label" for="comments">Comments:</label>
        <div class="controls">
            <textarea  id="comments" name="comments" rows="4" cols="80"></textarea>
            <span id="comments_error" style="display: none;" class="message-type_error">Please Enter Comments</span>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="crawford_contact_details">Crawford Contact Details:</label>
        <div class="controls">
            <textarea  id="crawford_contact_details" name="crawford_contact_details" rows="4" cols="80"></textarea>
            <span id="crawford_contact_details_error" style="display: none;" class="message-type_error">Please Enter Crawford Contact Details</span>
        </div>
    </div>
</div>
<div class="modal hide" id="error_window" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>Error</h3>

    </div>
    <div class="modal-body">
        One or more stations don't have their tracking information.
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true" data-dismiss="modal">Hide</button>
        
    </div>
</div>
<script type="text/javascript">
    $(function() {
        //        console.log(to);
        $.ajax({
            type: 'POST', 
            url: site_url+'tracking/get_tracking_info',
            data:{"stations":to},
            dataType: 'json',
            success: function (result) { 
                if(result.empty_station.length>0){
                    $('#compose_to_type').modal('toggle');
                    $('#error_window').modal('toggle');
                }
                      
                   
            }
        });
        
    });
    function checkFields(){
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
        comments=$('#comments').val();
        crawford_contact_details=$('#crawford_contact_details').val();
        
        
        extras= {
            comments: comments,
            crawford_contact_details: crawford_contact_details
        };
                
        msg_body='Comments: '+comments+'\n'+
            'Crawford Contact Details: '+crawford_contact_details+'\n' ; 
        
        
        $('#confirm_body').html('<div><strong>To: '+to_name+'</strong></div>'+
            '<div><strong>Subject: '+subject+'</strong></div><br/>'+
            '<div>Comments: '+comments+'</div>'+
            '<div>Crawford Contact Details: '+crawford_contact_details+'</div>');
    }
</script>