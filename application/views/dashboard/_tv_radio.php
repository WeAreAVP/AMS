

<div style="width: 50%;float: right;">
				<div class="my-navbar">
								<div>
												Scheduled vs. Completed
								</div>

				</div>
				<ul class="nav nav-tabs">
								<li class="active"><a href="#tv_radio" data-toggle="tab">Radio & TV</a></li>
								<li><a href="#all_formats" data-toggle="tab">All Formats</a></li>

				</ul>
				<div class="tab-content">
								<div class="tab-pane active" id="tv_radio" style="width: 600px;height: 380px; margin: 0 auto;">
												<div id="tv_graph" style="width: 300px;height: 350px;float: left;"></div>
												<div id="radio_graph" style="width: 300px;height: 350px;float: right;">
																
												</div>
								</div>
								<div class="tab-pane" id="all_formats" style="width: 605px;height: 380px; margin: 0 auto;"></div>
				</div>

</div>
<div style="clear: both;"></div>
<script type="text/javascript">
				$(function () {
								var chart;
								$(document).ready(function() {
												chart = new Highcharts.Chart({
																chart: {
																				renderTo: 'tv_graph',
																				plotBackgroundColor: 'whiteSmoke',
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
																exporting:{
																				enabled:false
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
																																return '<b>'+ this.point.name +'</b>: '+ this.percentage +' %';
																												}
																								}
																				}
																},
																series: [{
																								type: 'pie',
																								name: 'Radio',
																								data: [
																												['Digitized',   45.0],
																												['Scheduled',       26.8]

																								]
																				}]
												});
												
												chart = new Highcharts.Chart({
																chart: {
																				renderTo: 'radio_graph',
																				plotBackgroundColor: 'whiteSmoke',
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
																																return '<b>'+ this.point.name +'</b>: '+ this.percentage +' %';
																												}
																								}
																				}
																},
																series: [{
																								type: 'pie',
																								name: 'TV',
																								data: [
																												['Digitized',   75.0],
																												['Scheduled',       25.0]

																								]
																				}]
												});
												
												chart = new Highcharts.Chart({
																chart: {
																				renderTo: 'all_formats',
																				plotBackgroundColor: 'whiteSmoke',
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
																																return '<b>'+ this.point.name +'</b>: '+ this.percentage +' %';
																												}
																								}
																				}
																},
																series: [{
																								type: 'pie',
																								name: 'All Formats',
																								data: [
																												['Digitized',   75.0],
																												['Scheduled',       25.0]

																								]
																				}]
												});
								});
    
				});
</script>

