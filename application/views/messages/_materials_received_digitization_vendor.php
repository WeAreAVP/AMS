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
                    $('#error_window').modal('show');
                }
                else if(result.station_list.length>0){
                    alert(1);
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