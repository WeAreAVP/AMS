<meta http-equiv="Content-Type" content="video/mp4" />
<div style="margin-bottom: 10px;">
	<?php
	if ($media)
	{
		?>
<!--		<div class="flowplayer">
			<video>
				<source type="video/mp4" src="<?php echo $media['url']; ?>"/>

			</video>
		</div>-->
		<div class="clearfix"></div>
		<div style="margin-left: 20px;margin-top: 10px;"><a href="<?php echo $media['url']; ?>" target="=_blank">Open Proxy file</a></div>
		<video id="my-video" controls>
			<source src="<?php echo $media['url']; ?>" type="video/mp4">
		</video>

		<script>
			(function() {
				var video = document.createElement("video");
				if (typeof(video.canPlayType) == 'undefined' || // detect browsers with no <video> support
				video.canPlayType('video/mp4') == '') { // detect the ability to play H.264/MP4

					var video = $('#my-video');
					var videoUrl = video.find('source').attr('src');
					var flashUrl = '/js/flowplayer/flowplayer.swf';

					// create flash
					var flash = '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" ' +
					'	codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" ' +
					'	width="' + video.width() + '" height="' + video.height() + '" id="fallbackplayer">' +
					'		<param name="allowfullscreen" value="true" /> ' +
					'		<param name="movie" value="' + flashUrl + '?mediaUrl=' + videoUrl + '" /> ' +
					'		<embed id="dtsplayer" width="' + video.width() + '" height="' + video.height() + '" allowfullscreen="true" allowscriptaccess="always" ' +
					'			quality="high" name="fallbackplayer" ' + '			src="' + flashUrl + '?mediaUrl=' + videoUrl + '" ' +
					'			type="application/x-shockwave-flash" /> ' +
					'</object>';

					// insert flash and remove video
					video.before(flash);
					video.detach();
				}
			})();
		</script>
		<?php
	}
	?>
	<!--	<div class="flowplayer">
			<video>
				<source type="video/mp4" src="http://url2.bollywoodmp3.se/%5BSongs.PK%5D%20Shootout%20At%20Wadala%20-%20Laila%20-%20128Kbps%20%5BFunmaza.com%5D.mp3"/>
			</video>
		</div>-->

</div>
