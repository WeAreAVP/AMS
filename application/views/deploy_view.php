<div><h3>AMS Reporting</h3></div>
<div id="sphnix_loading">
	<?php echo $sphnix['waiting']; ?>
</div>
<div id="sphnix_result" style="display: none;">
	<?php echo $sphnix['msg']; ?>
</div>

<div id="searchd_loading" style="display: none;">
	<?php echo $searchd['waiting']; ?>
</div>
<div id="searchd_result" style="display: none;">
	<?php echo $searchd['msg']; ?>
</div>

<!--<div id="sphnix_loading">
<?php echo $sphnix['waiting']; ?>
</div>
<div id="sphnix_result" style="display: none;">
<?php echo $sphnix['msg']; ?>
</div>

<div id="sphnix_loading">
<?php echo $sphnix['waiting']; ?>
</div>
<div id="sphnix_result" style="display: none;">
<?php echo $sphnix['msg']; ?>
</div>-->


<script type="text/javascript">
	var sphnixInterval;
	var searchdInterval;
	$(document).ready(function() {

		sphnixInterval = setInterval(function() {
			$('#sphnix_loading').append('.');
		}, '1000');
		setTimeout(function() {
			clearInterval(sphnixInterval);
			$('#sphnix_result').show();
			$('#searchd_loading').show();
			searchdInterval = setInterval(function() {
				$('#searchd_loading').append('.');
			}, '1000');
		}, 5000);
		setTimeout(function() {
			clearInterval(searchdInterval);
			$('#searchd_result').show();
//			$('#searchd_loading').show();
//			searchdInterval = setInterval(function() {
//				$('#searchd_loading').append('.');
//			}, '1000');
		}, 5000);
	});
</script>