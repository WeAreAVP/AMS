
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
		<button class="btn btn-primary" onclick="doRefine();">Yes</button>
	</div>
	<div class="modal-footer" id="already_refine_footer">
		<button class="btn btn-inverse" data-dismiss="modal" aria-hidden="true" onclick="window.location.reload();">OK</button>

	</div>
</div>


<div class="modal hide" id="refine_cancel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3>AMS Refine</h3>

	</div>
	<div class="modal-body">
		Are you sure you want to cancel refining.It will remove all your changes.
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">No</button>
		<a href="<?php echo site_url('refine/remove/' . $is_refine->id); ?>" class="btn btn-primary">Yes</a>
	</div>

</div>


<script type="text/javascript">
			var type_record;
			function refineConfirm(msg, type, record_type) {
				type_record = record_type;
				$('#refine_body').html(msg);
				if (type == 0) {
					$('#already_refine_footer').hide();
					$('#refine_footer').show();
				}
				else {
					$('#refine_footer').hide();
					$('#already_refine_footer').show();
				}

			}
			function doRefine() {
				$('#refine_body').html('<img src="/images/ajax-loader.gif" style="margin-right: 15px;" />');
				$('#refine_footer').hide();

				$.ajax({
					type: 'POST',
					url: site_url + 'refine/export/' + type_record,
					dataType: 'json',
					success: function(result) {
						$('#refine_body').html(result.msg);
						$('#already_refine_footer').show();
					}
				});
			}
</script>