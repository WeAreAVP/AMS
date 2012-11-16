
<a href="#myDSDStationModal" data-toggle="modal" id="showDSDPopUp"></a>
<div class="modal hide" id="myDSDStationModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3>Compose Message</h3>
    <p id="DSDLabel" style="font-size: 10px;"></p>
  </div>
  <div class="modal-body">
    <div id="conflict_error" style="display: none;">
      Please select stations that have same DSD and DSD is set.
    </div>
    <div id="compose_div" style="display: none;">
      <div class="control-group">
        <label class="control-label" for="shipping_instructions">Shipping Instructions:</label>
        <div class="controls">
          <textarea id="shipping_instructions" name="shipping_instructions"></textarea>

        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="comments">Comments:</label>
        <div class="controls">
          <textarea id="comments" name="comments"></textarea>

        </div>
      </div>
    </div>

  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true" id="">Cancel</button>
    <button class="btn btn-primary" id="send_message" onclick="sendDSDEmail();">Send</button>
  </div>
</div>

<script type="text/javascript">
  function sendDSDEmail(){
    shipping_instructions=$('#shipping_instructions').val();
    comments=$('#comments').val();
    extras= {
      shipping_instructions: shipping_instructions,
      comments: comments
    };
    $.ajax({
      type: 'POST', 
      url: '<?php echo site_url('stations/compose') ?>',
      data:{"extras":extras,to:to,type:type},
      dataType: 'json',
      success: function (result) { 
        if(result.success)
        {
          //           window.location.reload();
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
    
</script>

