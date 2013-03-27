<div class="title">AMS Server Reporting</div>
<div id="sphnix_loading">
	<?php echo $this->user_detail->first_name.' '.$this->user_detail->last_name.': ' .$sphnix['waiting']; ?>
</div>
<div id="sphnix_result" style="display: none;">
	<?php echo $this->user_detail->first_name.' '.$this->user_detail->last_name.': '.$sphnix['msg']; ?>
</div>
<br/>
<div id="searchd_loading" style="display: none;">
	<?php echo $this->user_detail->first_name.' '.$this->user_detail->last_name.': '.$searchd['waiting']; ?>
</div>
<div id="searchd_result" style="display: none;">
	<?php echo $this->user_detail->first_name.' '.$this->user_detail->last_name.': '.$searchd['msg']; ?>
</div>
<br/>
<div id="memcached_loading" style="display: none;">
	<?php echo $this->user_detail->first_name.' '.$this->user_detail->last_name.': '.$memcached['waiting']; ?>
</div>
<div id="memcached_result" style="display: none;">
	<?php echo $this->user_detail->first_name.' '.$this->user_detail->last_name.': '.$memcached['msg']; ?>
</div>
<br/>
<div id="mem_service_loading" style="display: none;">
	<?php echo $this->user_detail->first_name.' '.$this->user_detail->last_name.': '.$memcached_service['waiting']; ?>
</div>
<div id="mem_service_result" style="display: none;">
	<?php echo $this->user_detail->first_name.' '.$this->user_detail->last_name.': '.$memcached_service['msg']; ?>
</div>
<br/>
<div id="values_loading" style="display: none;">
	<?php echo $this->user_detail->first_name.' '.$this->user_detail->last_name.': '.$values['waiting']; ?>
</div>
<div id="values_result" style="display: none;">
	<?php echo $this->user_detail->first_name.' '.$this->user_detail->last_name.': '.$values['db_name']; ?>
	<?php echo $this->user_detail->first_name.' '.$this->user_detail->last_name.': '.$values['url']; ?>
</div>
<br/>
<div id="reporting_loading" style="display: none;">
	<?php echo $this->user_detail->first_name.' '.$this->user_detail->last_name.': '.$reporting['waiting']; ?>
</div>
<div id="reporting_result" style="display: none;">
	<?php echo $this->user_detail->first_name.' '.$this->user_detail->last_name.': '.$reporting['errors']; ?>
	<?php echo $this->user_detail->first_name.' '.$this->user_detail->last_name.': '.$reporting['reporting']; ?>
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