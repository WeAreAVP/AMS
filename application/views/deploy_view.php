<div class="title">AMS Server Reporting</div>
<div id="sphnix_loading">
	<?php echo $sphnix['waiting']; ?>
</div>
<div id="sphnix_result" style="display: none;">
	<?php echo $sphnix['msg']; ?>
</div>
<br/>
<div id="searchd_loading" style="display: none;">
	<?php echo $searchd['waiting']; ?>
</div>
<div id="searchd_result" style="display: none;">
	<?php echo $searchd['msg']; ?>
</div>
<br/>
<div id="memcached_loading" style="display: none;">
	<?php echo $memcached['waiting']; ?>
</div>
<div id="memcached_result" style="display: none;">
	<?php echo $memcached['msg']; ?>
</div>
<br/>
<div id="mem_service_loading" style="display: none;">
	<?php echo $memcached_service['waiting']; ?>
</div>
<div id="mem_service_result" style="display: none;">
	<?php echo $memcached_service['msg']; ?>
</div>
<br/>
<div id="values_loading" style="display: none;">
	<?php echo $values['waiting']; ?>
</div>
<div id="values_result" style="display: none;">
	<?php echo $values['db_name']; ?>
	<?php echo $values['url']; ?>
</div>
<br/>
<div id="reporting_loading" style="display: none;">
	<?php echo $reporting['waiting']; ?>
</div>
<div id="reporting_result" style="display: none;">
	<?php echo $reporting['errors']; ?>
	<?php echo $reporting['reporting']; ?>
</div>
<br/>



<script type="text/javascript">
	var interval;

	$(document).ready(function() {

		interval = setInterval(function() {
			$('#sphnix_loading').append('.');
		}, '1000');


		setTimeout(function() {
			clearInterval(interval);
			$('#sphnix_result').show();
			$('#searchd_loading').show();
			interval = setInterval(function() {
				$('#searchd_loading').append('.');
			}, '1000');
		}, 3000);


		setTimeout(function() {
			clearInterval(interval);
			$('#searchd_result').show();
			$('#memcached_loading').show();
			interval = setInterval(function() {
				$('#memcached_loading').append('.');
			}, '1000');
		}, 6000);

		setTimeout(function() {
			clearInterval(interval);
			$('#memcached_result').show();
			$('#mem_service_loading').show();
			interval = setInterval(function() {
				$('#mem_service_loading').append('.');
			}, '1000');
		}, 9000);

		setTimeout(function() {
			clearInterval(interval);
			$('#mem_service_result').show();
			$('#values_loading').show();
			interval = setInterval(function() {
				$('#values_loading').append('.');
			}, '1000');
		}, 12000);

		setTimeout(function() {
			clearInterval(interval);
			$('#values_result').show();
			$('#reporting_loading').show();
			interval = setInterval(function() {
				$('#reporting_loading').append('.');
			}, '1000');
		}, 15000);
		setTimeout(function() {
			clearInterval(interval);
			$('#reporting_result').show();

		}, 18000);
	});
</script>