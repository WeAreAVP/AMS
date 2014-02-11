

<div style="width: 50%;float: right;">
	<div class="dashboard-nav">
		<div>
			SCHEDULED VS. DIGITIZED
		</div>

	</div>
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tv_radio" data-toggle="tab">Radio & TV</a></li>
		<li><a href="#all_formats" data-toggle="tab">All Formats</a></li>

	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tv_radio" style="width: 480px;height: 380px; margin: 0 auto;">
			<div id="tv_graph" style="width: 240px;height: 350px;float: left;"></div>
			<div id="radio_graph" style="width: 240px;height: 350px;float: right;">

			</div>
		</div>
		<div class="tab-pane" id="all_formats" style="width: 480px;height: 380px; margin: 0 auto;"></div>
	</div>

</div>
<div style="clear: both;"></div>
<script type="text/javascript">
	$(function() {
		var chart;
		$(document).ready(function() {
			
			chart = new Highcharts.Chart({
				chart: {
					renderTo: 'tv_graph',
					plotBackgroundColor: '#F5F5F5',
					plotBorderWidth: null,
					plotShadow: false
				},
				title: {
					text: ''
				},
				tooltip: {
					pointFormat: '{series.name}: <b>{point.percentage}%</b>',
					percentageDecimals: 1
				},
				credits: {
					enabled: false,
					href: "",
					text: "AMS"
				},
				exporting: {
					enabled: true
				},
				plotOptions: {
					pie: {
						allowPointSelect: true,
						cursor: 'pointer',
						dataLabels: {
							enabled: false,
							color: '#000000',
							connectorColor: '#000000',
							formatter: function() {
								return '<b>' + this.point.name + '</b>: ' + this.percentage + ' %';
							}
						}
					}
				},
				series: [{
						type: 'pie',
						name: 'Radio',
						data: [
							['Scheduled', <?php echo $pie_total_radio_scheduled; ?>],
							['Digitized', <?php echo $pie_total_radio_completed; ?>]

						]
					}]
			});

			chart = new Highcharts.Chart({
				chart: {
					renderTo: 'radio_graph',
					plotBackgroundColor: '#F5F5F5',
					plotBorderWidth: null,
					plotShadow: false
				},
				title: {
					text: ''
				},
				tooltip: {
					pointFormat: '{series.name}: <b>{point.percentage}%</b>',
					percentageDecimals: 1
				},
				credits: {
					enabled: false,
					href: "",
					text: "AMS"
				},
				plotOptions: {
					pie: {
						allowPointSelect: true,
						cursor: 'pointer',
						dataLabels: {
							enabled: false,
							color: '#000000',
							connectorColor: '#000000',
							formatter: function() {
								return '<b>' + this.point.name + '</b>: ' + this.percentage + ' %';
							}
						}
					}
				},
				series: [{
						type: 'pie',
						name: 'TV',
						data: [
							['Scheduled', <?php echo $pie_total_tv_scheduled; ?>],
							['Digitized', <?php echo $pie_total_tv_completed; ?>]

						]
					}]
			});

			chart = new Highcharts.Chart({
				chart: {
					renderTo: 'all_formats',
					plotBackgroundColor: '#F5F5F5',
					plotBorderWidth: null,
					plotShadow: false
				},
				title: {
					text: ''
				},
				tooltip: {
					pointFormat: '{series.name}: <b>{point.percentage}%</b>',
					percentageDecimals: 1
				},
				credits: {
					enabled: false,
					href: "",
					text: "AMS"
				},
				plotOptions: {
					pie: {
						allowPointSelect: true,
						cursor: 'pointer',
						dataLabels: {
							enabled: false,
							color: '#000000',
							connectorColor: '#000000',
							formatter: function() {
								return '<b>' + this.point.name + '</b>: ' + Math.round(this.percentage) + ' %';
							}
						}
					}
				},
				series: [{
						type: 'pie',
						name: 'All Formats',
						data: [
							['Scheduled', <?php echo $pie_total_scheduled; ?>],
							['Digitized', <?php echo $pie_total_completed; ?>]

						]
					}]
			});
		});

	});
</script>

