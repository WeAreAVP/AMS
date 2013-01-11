<?php
$upload_error	=	0;
$upload_success	=	0;
if(isset($this->session->userdata['upload_csv_error']))
{
				$upload_error	=	$this->session->userdata['upload_csv_error'];
				$this->session->unset_userdata('upload_csv_error');
}
if(isset($this->session->userdata['upload_success_msg']))
{
				$upload_success	=	$this->session->userdata['upload_success_msg'];
				$this->session->unset_userdata('upload_success_msg');
}
?>

<!-- Modal -->
<div id="updateStations" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
								<h3 id="myModalLabel" style="float: left;margin-right: 5px; ">Upload: </h3><div style="margin: 6px;"><b> Select CSV file<span style="color: #999;"> » Confirm</span></b> </div>
				</div>
				<div class="modal-body">
								<?php	echo	form_open_multipart('stations/import_station_contacts');	?>
								<div id="csv_file_container">
												<div><input type="file" name="csv_file" id="csv_file" size="20" /></div>
												<div style="color: #b94a48;font-weight: bold" id="error_file"><?php	echo	(isset($error))	?	$error	:	'';	?></div>
								</div>
								<div id="file_upload_confirm">
												<b>Are you sure you want to import these records? They will overwrite any existing ones.</b>
								</div>
								<?php	echo	form_close();	?>
				</div>
				<div class="modal-footer">
								<button class="btn" data-dismiss="modal" aria-hidden="true" id="btn_cancel">Cancel</button>
								<button class="btn" id="btn_back" onclick="backToFileUpload();">Back</button>
								<button class="btn btn-primary" id="btn_update_record" onclick="$('#updateStations form').submit();">Add Records</button>
				</div>
</div>


<div id="uploadErrorModal" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
								<h3 id="myErrorModalLabel">Uh Oh.</h3>
				</div>
				<div class="modal-body">
								<div style="color: #b94a48;font-weight: bold"><?php	echo	$upload_error;	?></div>
				</div>
				<div class="modal-footer">
								<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
								<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" onclick="$('#updateStations').modal('show');">Back</button>

				</div>
</div>
<div id="uploadSuccessModal" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
								<h3 id="myErrorModalLabel">Success!</h3>
				</div>
				<div class="modal-body">
								<p><?php	echo	$upload_success;	?></p>
				</div>
				<div class="modal-footer">
								<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Close</button>


				</div>
</div>
<script type="text/javascript">
				$(document).ready(function() {
								uploadError='<?php	echo	$upload_error;	?>';
								uploadSuccess='<?php	echo	$upload_success;	?>';
								if(uploadError!=0){
												$('#uploadErrorModal').modal('show');			
								}
								if(uploadSuccess!=0){
												$('#uploadSuccessModal').modal('show');			
								}
								$('#updateStations').on('show', function () {
												backToFileUpload();
								});
								$(':file').change(function(){
												var file = this.files[0];
												name = file.name;
												size = file.size;
												type = file.type;
												console.log(type);
//												if(type=='text/csv' || type=='application/vnd.ms-excel' || type=='text/x-comma-separated-values' || type=='application/x-csv' || type=='text/x-csv' || type=='text/x-csv'){
																$('#csv_file_container').hide();
																$('#btn_cancel').hide();
																$('#file_upload_confirm').show();
																$('#btn_back').show();
																$('#btn_update_record').show();
//												}
//												else{
//																$('#error_file').html('Please select a vaild csv file.');
//												}
												
												
								});

				});
				function backToFileUpload(){
								$('#csv_file').val('');
								$('#csv_file_container').show();
								$('#btn_cancel').show();
								$('#file_upload_confirm').hide();
								$('#btn_back').hide();
								$('#btn_update_record').hide();
				}
				
</script>

