<div id="sphnix_loading">
	<?php echo $sphnix['waiting']; ?>
</div>
<div id="sphnix_result" style="display: none;">
	<?php echo $sphnix['msg']; ?>
</div>


<script type="text/javascript">
	$(document).ready(function() {
		setTimeout(function() {
			$('#sphnix_result').show();
		}, 5000);
	});
</script>