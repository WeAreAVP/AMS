
<div class="modal hide" id="standalone_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3>Standalone Report</h3>

	</div>
	<div class="modal-body" id="standalone_body">

		<div>Select Date:</div>
		<div>
			<input type="text" id="migration_date" name="migration_date" />
			<span class="help-block">You can refine report by selecting date(s).</span>
		</div>

	</div>
	<div class="modal-footer" id="standalone_footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">No</button>
		<button class="btn btn-primary" onclick="generateStandaloneReport();">Generate</button>
	</div>
	<div class="modal-footer" id="close_standalone_footer" style="display: none;">
		<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Close</button>

	</div>


</div>
<script type="text/javascript">

			$('#standalone_model').on('hidden', function() {
				$('#standalone_body').html('<div>Select Date:</div><div><input type="text" id="migration_date" name="migration_date" /><span class="help-block">You can refine report by selecting date(s).</span></div>');
				$('#standalone_footer').show();
				$('#close_standalone_footer').hide();

			});
			$('#standalone_model').on('show', function() {

				$('#migration_date').daterangepicker({
					posX: null,
					posY: null,
					arrows: false,
					dateFormat: 'M d, yy',
					rangeSplitter: 'to',
					datepickerOptions: {
						changeMonth: true,
						changeYear: true,
						yearRange: '-500:+15',
						arrows: true
					},
					onOpen: function() {
						$('.ui-daterangepickercontain').css('z-index', '9999');
					}

				});
			});

			function openPopup() {
				if (!$('#standalone_btn').hasClass('disabled')) {
					$('#standalone_model').modal({
						backdrop: 'static'
					});
				}
			}
			function generateStandaloneReport() {
				date=$('#migration_date').val();
				$('#standalone_body').html('<img src="/images/ajax-loader.gif" style="margin-right: 15px;" />');
				$('#standalone_footer').hide();
				$.ajax({
					type: 'POST',
					url: site_url + 'reports/generate_report/',
					data: {date: date},
					dataType: 'json',
					success: function(result) {

						$('#standalone_body').html(result.msg);
						$('#close_standalone_footer').show();
					}
				});
			}
</script>





