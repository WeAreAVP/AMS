<div style="margin-bottom: 10px;">
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
							<li><a href="javascript:;" class="jp-play" tabindex="1">play</a></li>
							<li><a href="javascript:;" class="jp-pause" tabindex="1">pause</a></li>
							<li><a href="javascript:;" class="jp-stop" tabindex="1">stop</a></li>
							<li><a href="javascript:;" class="jp-mute" tabindex="1" title="mute">mute</a></li>
							<li><a href="javascript:;" class="jp-unmute" tabindex="1" title="unmute">unmute</a></li>
							<li><a href="javascript:;" class="jp-volume-max" tabindex="1" title="max volume">max volume</a></li>
						</ul>
						<div class="jp-progress">
							<div class="jp-seek-bar">
								<div class="jp-play-bar"></div>
							</div>
						</div>
						<div class="jp-volume-bar">
							<div class="jp-volume-bar-value"></div>
						</div>
						<div class="jp-time-holder">
							<div class="jp-current-time"></div>
							<div class="jp-duration"></div>

							<ul class="jp-toggles">
								<li><a href="javascript:;" class="jp-repeat" tabindex="1" title="repeat">repeat</a></li>
								<li><a href="javascript:;" class="jp-repeat-off" tabindex="1" title="repeat off">repeat off</a></li>
							</ul>
						</div>
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
			//<![CDATA[
				$(document).ready(function() {

					$("#jquery_jplayer_1").jPlayer({
						ready: function() {
							$(this).jPlayer("setMedia", {
								mp3: "http://www.jplayer.org/audio/m4a/TSP-01-Cro_magnon_man.m4a",
								
							});
						},
						swfPath: "js",
						solution: "flash, html",
						supplied: "mp3",
						wmode: "window"
					});
				});
			//]]>
			</script>

			<?php
		}
		else
		{
			?>
			<video width="700" height="320" controls>
				<source src="<?php echo $media['url']; ?>" type="video/mp4">
				<object data="<?php echo $media['url']; ?>" width="700" height="320">
					<embed src="http://releases.flowplayer.org/swf/flowplayer-3.2.1.swf" width="700" height="320">
				</object> 
			</video>
			<?php
		}
	}
	?>
</div>
