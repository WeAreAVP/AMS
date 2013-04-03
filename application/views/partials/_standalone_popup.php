
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
		<button class="btn btn-primary">Yes</button>
	</div>

</div>
<script type="text/javascript">
	function openPopup() {
		if (!$('#standalone_btn').hasClass('disabled')) {
			$('#standalone_model').modal({
				backdrop: 'static'
			});
		}
	}
</script>





