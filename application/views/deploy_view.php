<div class="title">AMS Reporting</div>
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



<script type="text/javascript">
	var sphnixInterval;
	var searchdInterval;
	var memcachedInterval;
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
		}, 3000);


		setTimeout(function() {
			clearInterval(searchdInterval);
			$('#searchd_result').show();
			$('#memcached_loading').show();
			memcachedInterval = setInterval(function() {
				$('#memcached_loading').append('.');
			}, '1000');
		}, 6000);

		setTimeout(function() {
			clearInterval(memcachedInterval);
			$('#memcached_result').show();
			$('#mem_service_loading').show();
			memcachedInterval = setInterval(function() {
				$('#mem_service_loading').append('.');
			}, '1000');
		}, 9000);
		
		setTimeout(function() {
			clearInterval(memcachedInterval);
			$('#mem_service_result').show();
//			$('#mem_service_loading').show();
//			memcachedInterval = setInterval(function() {
//				$('#mem_service_loading').append('.');
//			}, '1000');
		}, 12000);
	});
</script>