<link href="http://vjs.zencdn.net/c/video-js.css" rel="stylesheet">
<script src="http://vjs.zencdn.net/c/video.js"></script>
<div style="margin-bottom: 10px;">
	<?php
	if ($media)
	{
		?>
		<video class="video-js vjs-default-skin" controls
			   preload="auto" width="400" height="150"
			   data-setup="{}">
			<source src="<?php echo $media['url']; ?>" type='video/mp4'>

		</video>
	<div class="clearfix" style="margin-bottom: 15px;"></div>
		<object width="400" height="150"> <param name="movie" value="http://fpdownload.adobe.com/strobe/FlashMediaPlayback.swf"></param>
			<param name="flashvars" value="src=<?php echo $media['url']; ?>"></param>
			<param name="allowFullScreen" value="true"></param>
			<param name="allowscriptaccess" value="always"></param>
			<embed src="http://fpdownload.adobe.com/strobe/FlashMediaPlayback.swf" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="400" height="150" flashvars="src=<?php echo $media['url']; ?>"></embed></object>
		<div class="clearfix" style="margin-bottom: 15px;"></div>	
		<div id="myElement">Loading the player ...</div>
		<script type="text/javascript">
			jwplayer("myElement").setup({
				file: "<?php echo $media['url']; ?>",
				height: 150,
				//        image: "/uploads/example.jpg",
				width: 400,
				'modes': [
					{type: 'html5'},
					{type: 'flash', src: "/js/jwplayer/jwplayer.flash.swf"},
					{type: 'download'}
				]
			});
		</script>
		<div class="clearfix" style="margin-bottom: 15px;"></div>
		<div class="flowplayer">
			<video>
				<source type="video/mp4" src="<?php echo $media['url']; ?>"/>

			</video><!--
		</div>-->
			<div class="clearfix" style="margin-bottom: 15px;"></div>
			<!--<div style="margin-left: 20px;margin-top: 10px;"><a href="<?php echo $media['url']; ?>" target="=_blank">Open Proxy file</a></div>-->
					<video controls  width="400" height="150">
						<source src="<?php echo $media['url']; ?>" type="video/mp4">
			
						<object type="application/x-shockwave-flash" data="http://releases.flowplayer.org/swf/flowplayer.content-3.2.8.swf"
								width="700" height="320">
							<param name="allowfullscreen" value="true">
							<param name="allowscriptaccess" value="always">
							<param name="flashvars" value="<?php echo $media['url']; ?>">
							<!-- [if IE]><param name="movie" value="http://releases.flowplayer.org/swf/flowplayer.content-3.2.8.swf"><![endif]-->
							<img src="video.jpg" width="854" height="480" alt="Video">
							<p>Your browser can’t play HTML5 video. <a href="video.webm">
									Download it</a> instead.</p>
						</object>
					</video>
			<!--		<video id="my-video" controls height="320" width="700">
						<source src="<?php echo $media['url']; ?>" type="video/mp4">
					</video>
			
					<script>
						$(function() {
							var video = document.createElement("video");
							if (typeof(video.canPlayType) == 'undefined' || // detect browsers with no <video> support
							video.canPlayType('video/mp4') == '') { // detect the ability to play H.264/MP4
			
								var video = $('#my-video');
								var videoUrl = video.find('source').attr('src');
								var flashUrl = 'http://releases.flowplayer.org/swf/flowplayer.content-3.2.8.swf';
								
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
						});
					</script>-->
			<?php
		}
		?>
		<!--	<div class="flowplayer">
				<video>
					<source type="video/mp4" src="http://url2.bollywoodmp3.se/%5BSongs.PK%5D%20Shootout%20At%20Wadala%20-%20Laila%20-%20128Kbps%20%5BFunmaza.com%5D.mp3"/>
				</video>
			</div>-->

	</div>
