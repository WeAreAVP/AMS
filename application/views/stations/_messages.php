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
        <button class="btn" data-dismiss="modal" aria-hidden="true" onclick="resetMessagesForm();"> Cancel</button>

        <button class="btn btn-primary" onclick="validateFormType();">Next</button>
    </div>
</div>


<script type="text/javascript">
						var extras = null;
						var to = null;
						var from = null;
						var subject = null;
						var type = null;
						var msg_body = null;
						var to_name = null;
						function checkStations() {
							var stations = new Array();
							var station_names = new Array();
							$('input[name="station[]"]:checked').each(function(index, a) {
								stations[index] = $(this).val();
								station_names[index] = $('#station_name_' + stations[index]).html();
							});
							if (stations.length > 0) {
								to_name = implode(", ", station_names);
								to = stations;
								$('#compose_to_type').modal({
									backdrop: 'static',
								});
							}
						}
						function typeForm() {
							type = $('#msg_type').val();
							if (type == '')
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
									data: {"type": type},
									dataType: 'html',
									success: function(result) {
										$('#alert_type').html(result);
									}
								});
							}
						}

						function validateFormType() {
							extras = new Array();
							type = $('#msg_type').val();
							if (type == '')
							{
								$('#message_type_error').show();
								return false;
							}
							else
							{
								$('#message_type_error').hide();
								validateFields = checkFields();
								if (validateFields) {
									subject = $("#msg_type option[value='" + type + "']").text();
									confirmBody();

									$('#compose_to_type').modal("toggle");
									$('#compose_confirm').modal({
										backdrop: 'static'
									});
								}

							}

						}
</script>


<?php echo $this->load->view('partials/_message_popup'); ?>