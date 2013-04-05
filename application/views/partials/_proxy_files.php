<?php
if ($media)
{
	if ($media['format'] == 'mp3')
	{
		?>
		<div id="jquery_jplayer_1" class="jp-jplayer"></div>

		<div id="jp_container_1" class="jp-audio">
			<div class="jp-type-single">
				<div class="jp-gui jp-interface">
					<ul class="jp-controls">

						<!-- comment out any of the following <li>s to remove these buttons -->

						<li><a href="javascript:;" class="jp-play" tabindex="1">play</a></li>
						<li><a href="javascript:;" class="jp-pause" tabindex="1">pause</a></li>
						<li><a href="javascript:;" class="jp-stop" tabindex="1">stop</a></li>
						<li><a href="javascript:;" class="jp-mute" tabindex="1" title="mute">mute</a></li>
						<li><a href="javascript:;" class="jp-unmute" tabindex="1" title="unmute">unmute</a></li>
						<li><a href="javascript:;" class="jp-volume-max" tabindex="1" title="max volume">max volume</a></li>
					</ul>

					<!-- you can comment out any of the following <div>s too -->

					<div class="jp-progress">
						<div class="jp-seek-bar">
							<div class="jp-play-bar"></div>
						</div>
					</div>
					<div class="jp-volume-bar">
						<div class="jp-volume-bar-value"></div>
					</div>
					<div class="jp-current-time"></div>
					<div class="jp-duration"></div>                   
				</div>
				<div class="jp-title">
					<ul>
						<li>Cro Magnon Man</li>
					</ul>
				</div>
				<div class="jp-no-solution">
					<span>Update Required</span>
					To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
				</div>
			</div>
		</div>
		<script type="text/javascript">
			$(document).ready(function() {

				$("#jquery_jplayer_1").jPlayer({
					ready: function(event) {
						$(this).jPlayer("setMedia", {
							mp3: "http://www.jplayer.org/audio/mp3/TSP-01-Cro_magnon_man.mp3",
							oga: "http://www.jplayer.org/audio/ogg/TSP-01-Cro_magnon_man.ogg"
						});
					},
					swfPath: "http://www.jplayer.org/2.1.0/js",
					supplied: "mp3, oga",
					solution: "flash, html",
					wmode: "window"

				});
			});
		</script>
		<?php
	}
	else
	{
		?>
		<div id="jp_container_1" class="jp-video jp-video-360p">
			<div class="jp-type-single">
				<div id="jquery_jplayer_1" class="jp-jplayer"></div>
				<div class="jp-gui">
					<div class="jp-video-play">
						<a href="javascript:;" class="jp-video-play-icon" tabindex="1">play</a>
					</div>
					<div class="jp-interface">
						<div class="jp-progress">
							<div class="jp-seek-bar">
								<div class="jp-play-bar"></div>
							</div>
						</div>
						<div class="jp-current-time"></div>
						<div class="jp-duration"></div>
						<div class="jp-controls-holder">
							<ul class="jp-controls">
								<li><a href="javascript:;" class="jp-play" tabindex="1">play</a></li>
								<li><a href="javascript:;" class="jp-pause" tabindex="1">pause</a></li>
								<li><a href="javascript:;" class="jp-stop" tabindex="1">stop</a></li>
								<li><a href="javascript:;" class="jp-mute" tabindex="1" title="mute">mute</a></li>
								<li><a href="javascript:;" class="jp-unmute" tabindex="1" title="unmute">unmute</a></li>
								<li><a href="javascript:;" class="jp-volume-max" tabindex="1" title="max volume">max volume</a></li>
							</ul>
							<div class="jp-volume-bar">
								<div class="jp-volume-bar-value"></div>
							</div>
							<ul class="jp-toggles">
								<li><a href="javascript:;" class="jp-full-screen" tabindex="1" title="full screen">full screen</a></li>
								<li><a href="javascript:;" class="jp-restore-screen" tabindex="1" title="restore screen">restore screen</a></li>
								<li><a href="javascript:;" class="jp-repeat" tabindex="1" title="repeat">repeat</a></li>
								<li><a href="javascript:;" class="jp-repeat-off" tabindex="1" title="repeat off">repeat off</a></li>
							</ul>
						</div>
						<div class="jp-title">
							<ul>
								<li>Big Buck Bunny Trailer</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="jp-no-solution">
					<span>Update Required</span>
					To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
				</div>
			</div>
		</div>

		<script type="text/javascript">
			$(document).ready(function() {
				$("#jquery_jplayer_1").jPlayer({
					ready: function() {
						$(this).jPlayer("setMedia", {
							m4v: "http://www.jplayer.org/video/m4v/Big_Buck_Bunny_Trailer.m4v",
							ogv: "http://www.jplayer.org/video/ogv/Big_Buck_Bunny_Trailer.ogv",
							webmv: "http://www.jplayer.org/video/webm/Big_Buck_Bunny_Trailer.webm",
							poster: "http://www.jplayer.org/video/poster/Big_Buck_Bunny_Trailer_480x270.png"
						});
					},
					swfPath: "http://www.jplayer.org/2.1.0/js",
					solution: "flash, html",
					supplied: "webmv, ogv, m4v",
					size: {
						width: "640px",
						height: "360px",
						cssClass: "jp-video-360p"
					}
				});
			});
		</script>
		<?php
	}
}
?>

