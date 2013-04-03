<script>
	$(document).ready(function() {
		Highcharts.theme = {
			colors: [
				'#000000',
				'#7D7D7D',
			],
			chart: {
				plotBackgroundColor: 'whiteSmoke',
				plotShadow: false,
				shadow: false,
				pointBorderWidth: 0
			},
			xAxis: {
				gridLineWidth: 0,
				labels: {
					style: {
						color: '#060b10',
						fontSize: '15px'
					}
				}
			},
			yAxis: {
				lineWidth: 0,
				tickWidth: 0,
				labels: {
					style: {
						color: '#000000'
					}
				}
			},
			legend: {
				itemStyle: {
					font: 'Verdana, sans-serif',
					color: '#000000'
				},
				itemHoverStyle: {
					color: '#000000'
				},
				itemHiddenStyle: {
					color: '#000000'
				}
			},
			labels: {
				style: {
					color: '#000000'
				}
			}
		};

		var highchartsOptions = Highcharts.setOptions(Highcharts.theme);
	});
	function takeScreenShot() {
		html2canvas(document.body, {
			onrendered: function(canvas) {
				var img = canvas.toDataURL("image/png");

				console.log(img);

			}
		});
	}

</script>
<p><canvas id="canvas" style="border:2px solid black;" width="100%" height="100%"></canvas>
	<script>
		function test(){
		var canvas = document.getElementById("canvas");
		var ctx = canvas.getContext("2d");
		var data = "<svg xmlns='http://www.w3.org/2000/svg'>" +
		"<foreignObject width='100%' height='100%'>" +
		$('#abc').html()+
		"</foreignObject>" +
		"</svg>";
		var DOMURL = self.URL || self.webkitURL || self;
		var img = new Image();
		var svg = new Blob([data], {type: "image/svg+xml;charset=utf-8"});
		var url = DOMURL.createObjectURL(svg);
		img.onload = function() {
//			ctx.drawImage(img, 0, 0);
//			DOMURL.revokeObjectURL(url);
		};
//		img.src = url;
		console.log(url);
		}
	</script>
	
<div id="abc">
	<div><button onclick="test();" class="btn">Take Screen Shot</button></div>
	<div class="asset-stats">
		<div class="span4">
			<div class="assets-sum"><?php echo number_format($material_goal['total']); ?></div>
			<div class="assets-subdetail">hrs digitized</div>

		</div>
		<div class="span4">
			<div class="assets-sum"><?php echo $percentage_hours . '%'; ?></div>
			<div class="assets-subdetail">of <?php echo 'of ' . $total_hours . ' hrs'; ?> </div>

		</div>
		<div class="span3">
			<div class="assets-sum"><?php echo number_format($at_crawford); ?></div>
			<div class="assets-subdetail">hrs at Crawford</div>

		</div>
	</div>
	<div style="clear: both;"></div>
	<?php $this->load->view('dashboard/_region'); ?>

	<?php $this->load->view('dashboard/_tv_radio'); ?>
	<div class="clearfix"></div>
	<?php $this->load->view('dashboard/_formats'); ?>
</div>

