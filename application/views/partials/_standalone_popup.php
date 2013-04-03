
<div class="modal hide" id="standalone_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3>Report</h3>

	</div>
	<div class="modal-body" id="standalone_body">
		Are you sure you want to generate report?
	</div>
	<div class="modal-footer" id="standalone_footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">No</button>
		<button class="btn btn-primary" onclick="generateStandaloneReport();">Yes</button>
	</div>
	<div class="modal-footer" id="close_standalone_footer" style="display: none;">
		<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Close</button>

	</div>


</div>
<script type="text/javascript">
			other_filter = '<?php echo $other_filters; ?>';
			standalone = '<?php echo $standalone; ?>';
			$(document).ready(function() {
				if (other_filter == '0' && standalone == '1') {
					console.log('now fine');
				}
				else {
					console.log('isise');
				}}
			});
			$('#standalone_model').on('hidden', function() {
				$('#standalone_body').html('Are you sure you want to generate report?');
				$('#standalone_footer').show();
				$('#close_standalone_footer').hide();

			});

			function openPopup() {
				if (!$('#standalone_btn').hasClass('disabled')) {
					$('#standalone_model').modal({
						backdrop: 'static'
					});
				}
			}
			function generateStandaloneReport() {
				$('#standalone_body').html('<img src="/images/ajax-loader.gif" style="margin-right: 15px;" />');
				$('#standalone_footer').hide();
				$.ajax({
					type: 'POST',
					url: site_url + 'reports/generate_report/',
					dataType: 'json',
					success: function(result) {

						$('#standalone_body').html(result.msg);
						$('#close_standalone_footer').show();
					}
				});
			}
</script>





