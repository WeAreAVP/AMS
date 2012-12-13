<div class="modal hide" id="compose_confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>Send Message >> Confirm</h3>

    </div>
    <div class="modal-body" id="confirm_body">

    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true" onclick="$('#compose_to_type').modal('toggle');">Back</button>
        <button class="btn btn-primary"  data-dismiss="modal" onclick="sentEmail();">Send</button>
    </div>
</div>

<div class="modal hide" id="error_window" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>Tracking Information</h3>
    </div>
    <div class="modal-body">
        One or more stations don't have their tracking information and no media received date.
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" data-dismiss="modal">Close</button>

    </div>
</div>
<div class="modal hide" id="edit_media_window" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>Add Media Received Date</h3>
    </div>
    <div class="modal-body">
        <div class="control-group">
            <label class="control-label" for="crawford_contact_details">Media Received Date:</label>
            <div class="controls">
                <input type="text" name="media_date" id="media_date"/>
                <input type="text" name="tracking_id" id="tracking_id"/>
                <span id="media_date_error" style="display: none;" class="message-type_error">Please Select Media Received Date.</span>

            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true" onclick="checkMediaDate();">Save</button>
        <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" data-dismiss="modal">Close</button>

    </div>
</div>


<div class="modal hide" id="compose_to_type" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: 700px;">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>Compose Message</h3>
    </div>
    <div class="modal-body compose" >
        <form class="form-horizontal">
            <div class="control-group">
                <label class="control-label"  for="msg_type">Message Type:</label>
                <div class="controls">
                    <select id="msg_type" style="width: 237px;" name="msg_type" onchange="typeForm();">
                        <option value="">Select</option>
                        <?php
                        foreach ($this->config->item('messages_type') as $key => $value)
                        {
                            ?>
                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>           
                        <?php } ?>

                    </select>
                    <span id="message_type_error">Please select message type</span>
                </div>
            </div>

            <div id="alert_type"></div>
        </form>
    </div>

    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true"> Cancel</button>

        <button class="btn btn-primary" onclick="validateFormType();">Next</button>
    </div>
</div>


<script type="text/javascript">
    var extras=null;
    var to=null;
    var from=null;
    var subject=null;
    var type=null;
    var msg_body=null;
    var to_name=null;
    function checkStations(){
        var stations=new Array();
        var station_names=new Array();
        $('input[name="station[]"]:checked').each(function(index,a){
            stations[index]=$(this).val();
            station_names[index]=$('#station_name_'+stations[index]).html();
        });
        if(stations.length>0){
            to_name=implode(", ",station_names);
            to=stations;
            $('#compose_to_type').modal("toggle");
        }
    }
    function typeForm(){
        type=$('#msg_type').val();
        if(type=='')
        {
            $('#message_type_error').show();
            return false;
        }
        else
        {
            $('#message_type_error').hide();
            $('#alert_type').html('');
            $.ajax({
                type: 'POST', 
                url: '<?php echo site_url('messages/get_message_type') ?>',
                data:{"type":type},
                dataType: 'html',
                success: function (result) { 
                    $('#alert_type').html(result);
                }
            });
        }
    }
   
    function validateFormType(){
        extras=new Array();
        type=$('#msg_type').val();
        if(type=='')
        {
            $('#message_type_error').show();
            return false;
        }
        else
        {
            $('#message_type_error').hide();
            validateFields=checkFields();
            if(validateFields){
                subject=$("#msg_type option[value='"+type+"']").text();//$('#subject').val();
                confirmBody();
             
                $('#compose_to_type').modal("toggle");
                $('#compose_confirm').modal("toggle");
            }
            
        }
                                    
    }
    function sentEmail(){
        $.ajax({
            type: 'POST', 
            url: '<?php echo site_url('messages/compose') ?>',
            data:{"extras":extras,to:to,subject:subject,type:type,html:msg_body},
            dataType: 'json',
            success: function (result) { 
                if(result.success)
                {
                    window.location.reload();
                }
                else
                {
                    if(error_id==1)
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
    function checkMediaDate(){
        if($('#media_date').val()==''){
            $('#media_date_error').show();
            return false;
        }
        $('#edit_media_window').modal("toggle");
    }
</script>