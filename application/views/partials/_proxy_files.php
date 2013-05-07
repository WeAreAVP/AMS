<link href="http://vjs.zencdn.net/c/video-js.css" rel="stylesheet">
<script src="http://vjs.zencdn.net/c/video.js"></script>
<div style="margin-bottom: 10px;">
	<?php
	if ($media)
	{
		?>
		<div id="jPalayer_div" style="display: none;">
			<video class="video-js vjs-default-skin" controls
				   preload="auto" width="400" height="150"
				   data-setup="{}">
				<source src="<?php echo $media['url']; ?>" type='video/mp4'>

			</video>
		</div>
		<div id="flowPlayer_div" style="display: none;">
			<div class="flowplayer">
				<video>
					<source type="video/mp4" src="<?php echo $media['url']; ?>"/>

				</video>
			</div>
		</div>


		<div class="clearfix" style="margin-bottom: 15px;"></div>



		<div style="margin-left: 20px;margin-top: 10px;"><a href="<?php echo $media['url']; ?>" target="=_blank">Open Proxy file</a></div>
		<script type="text/javascript">
			$(document).ready(function() {
				if ($.browser.chrome || $.browser.safari) {
					$('#flowPlayer_div').show();
				}
				else {
					$('#jPalayer_div').show();
				}

			});
		</script>

		<?php
	}
	?>
</div>


