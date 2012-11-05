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




<div class="modal hide" id="compose_to_type" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: 700px;">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>Compose Message</h3>
    </div>
    <div class="modal-body compose" >
        <form class="form-horizontal">
            <div class="control-group">
                <label class="control-label" for="receiver_id">To:</label>
                <div class="controls">
                    <select id="receiver_id" name="receiver_id" multiple="multiple">
                        <?php foreach ($station_records as $value) { ?>
                            <option value="<?php echo $value->id; ?>"><?php echo $value->station_name; ?></option>
                            <?php
                        }
                        ?>
                    </select>

                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="msg_type">Message Type:</label>
                <div class="controls">
                    <select id="msg_type" name="msg_type" onchange="typeForm();">
                        <option value="">Select</option>
                        <?php foreach ($this->config->item('messages_type') as $key => $value) { ?>
                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>           
                        <?php } ?>

                    </select>
                    <span id="message_type_error">Please select message type</span>
                </div>
            </div>
            <?php /*?><div class="control-group" id="subject_div" style="display: none;">
                <label class="control-label" for="subject">Subject:</label>
                <div class="controls">
                    <input id="subject" name="subject"/>

                </div>
            </div><?php */?>
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
    
    $(function(){
  		 $("#receiver_id").multiselect(); 
			 $(".ui-multiselect-menu").width('400px');
			});
    function typeForm(){
       // $('#subject_div').show();
        type=$('#msg_type').val();
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
   
    function validateFormType(){
        extras=new Array();
                        
        type=$('#msg_type').val();
        if(type=='')
            $('#message_type_error').show();
        else{
            $('#message_type_error').hide();
            $('#compose_anchor').trigger('click');
            to=$('#receiver_id').val();
            to_name=$("#receiver_id option[value='"+$('#receiver_id').val()+"']").text();
            subject=$("#msg_type option[value='"+type+"']").text();//$('#subject').val();
            confirmBody();                      
            
            $('#confirm_anchor').trigger('click');
            
        }
                                    
    }
    function sentEmail(){
        $.ajax({
            type: 'POST', 
            url: '<?php echo site_url('messages/compose') ?>',
            data:{extras:extras,to:to,subject:subject,type:type,html:msg_body},
            success: function (result) { 
             window.location.reload();
            }
        });
    }
</script>