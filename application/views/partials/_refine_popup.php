<div class="modal hide" id="refine_confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>AMS Refine</h3>

    </div>
    <div class="modal-body" id="refine_body">
							Are you sure you want to refine data.
    </div>
    <div class="modal-footer" id="refine_footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">No</button>
        <button class="btn btn-primary"  data-dismiss="modal" onclick="sentEmail();">Yes</button>
    </div>
    <div class="modal-footer" id="already_refine_footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">OK</button>
        
    </div>
</div>




<script type="text/javascript">
   function refineConfirm(msg,type){
							$('#refine_body').html(msg);
							if(type==0){
											$('#already_refine_footer').hide();
											$('#refine_footer').show();
							}
							else{
												$('#refine_footer').hide();
											$('#already_refine_footer').show();
							}
											
			}
</script>