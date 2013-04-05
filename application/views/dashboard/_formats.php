	<div class="dashboard-nav">
	<div>
		FORMATS
	</div>

</div>
<ul class="nav nav-tabs">
	<li class="active"><a href="#digitized" data-toggle="tab">Total Digitized Assets</a></li>
	<li><a href="#scheduled" data-toggle="tab">Total Scheduled Assets</a></li>

</ul>
<div class="tab-content">
	<div class="tab-pane active" id="digitized" style="width:960px;height: 550px; margin: 0 auto;"></div>
	<div class="tab-pane" id="scheduled" style="width: 1500px;height: 550px; margin: 0 auto;"></div>
</div>


<script type="text/javascript">
	$(document).ready(function() {
		digitized_format_name =<?php echo json_encode($digitized_format_name); ?>;
		digitized_total =<?php echo json_encode($digitized_total); ?>;
		scheduled_format_name =<?php echo json_encode($scheduled_format_name); ?>;
		scheduled_total =<?php echo json_encode($scheduled_total); ?>;



		if (digitized_format_name != null) {
			new Highcharts.Chart({
				chart: {
					renderTo: 'digitized',
					type: 'column'
				},
				title: {
					text: ''
				},
				credits: {
					enabled: false,
					href: "",
					text: "AMS"
				},
				plotOptions: {
					column: {
						borderWidth: 0,
						shadow: false
					}
				},
				xAxis: {
					categories: digitized_format_name,
					gridLineWidth: 0,
					labels: {
						rotation: -90,
						align: 'right',
						style: {
							fontSize: '11px',
							fontFamily: 'Verdana, sans-serif'
						}
					},
					lineWidth: 0,
					tickWidth: 0
				},
				yAxis: {
					//          tickInterval:100,
					gridLineWidth: 0,
					min: 0,
					//          max:1000,
					title: {
						text: ''
					}

				},
				legend: {
					enabled: false
				},
				tooltip: {
					formatter: function() {
						return '<b>' + this.x + '</b><br/>' +
								Highcharts.numberFormat(this.y, 0) + ' Digitized';
					}
				},
				series: [{
						name: 'Total Digitized Assets',
						pointWidth: 10,
						borderWidth: 0,
						data: digitized_total,
						dataLabels: {
							enabled: true,
							rotation: 0,
							color: '#000000',
							align: 'center',
							x: 0,
							y: 0,
							style: {
								fontSize: '9px',
								fontFamily: 'Verdana, sans-serif'
							}
						}
					}]
			});

		}
		else {
			$('#digitized').html('<center>No digitized format available</center>');
		}
		new Highcharts.Chart({
			chart: {
				renderTo: 'scheduled',
				type: 'column'
						//        margin: [ 50, 50, 100, 80]

			},
			title: {
				text: ''
			},
			credits: {
				enabled: false,
				href: "",
				text: "AMS"
			},
			plotOptions: {
				column: {
					borderWidth: 0,
					shadow: false
				}
			},
			xAxis: {
				categories: scheduled_format_name,
				gridLineWidth: 0,
				labels: {
					rotation: -90,
					align: 'right',
					style: {
						fontSize: '11px',
						fontFamily: 'Verdana, sans-serif'
					}
				},
				lineWidth: 0,
				tickWidth: 0
			},
			yAxis: {
				//          tickInterval:100,
				gridLineWidth: 0,
				min: 0,
				//          max:1000,
				title: {
					text: ''
				}

			},
			legend: {
				enabled: false
			},
			tooltip: {
				formatter: function() {
					return '<b>' + this.x + '</b><br/>' +
							Highcharts.numberFormat(this.y, 0) + ' Scheduled';
				}
			},
			series: [{
					name: 'Total Scheduled Assets',
					pointWidth: 10,
					borderWidth: 0,
					data: scheduled_total,
					dataLabels: {
						enabled: true,
						rotation: 270,
						color: '#000000',
						align: 'center',
						x: 4,
						y: -15,
						style: {
							fontSize: '9px',
							fontFamily: 'Verdana, sans-serif'
						}
					}
				}]
		});
	});



	function abbreviateNumber(value) {
		var newValue = value;
		if (value >= 1000) {
			var suffixes = ["", "k", "m", "b", "t"];
			var suffixNum = Math.floor(("" + value).length / 3);
			var shortValue = '';
			for (var precision = 2; precision >= 1; precision--) {
				shortValue = parseFloat((suffixNum != 0 ? (value / Math.pow(1000, suffixNum)) : value).toPrecision(precision));
				var dotLessShortValue = (shortValue + '').replace(/[^a-zA-Z 0-9]+/g, '');
				if (dotLessShortValue.length <= 2) {
					break;
				}
			}
			if (shortValue % 1 != 0)
				shortNum = shortValue.toFixed(1);
			newValue = shortValue + suffixes[suffixNum];
		}
		return newValue;
	}
</script>