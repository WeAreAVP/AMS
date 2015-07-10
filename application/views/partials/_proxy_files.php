<link href="http://vjs.zencdn.net/c/video-js.css" rel="stylesheet">
<script src="http://vjs.zencdn.net/c/video.js"></script>
<div style="margin-bottom: 10px;">
	<style type="text/css">

		/* custom player skin */
		.flowplayer { width: 80%; background-color: #222; background-size: cover; max-width: 800px; }
		.flowplayer .fp-controls { background-color: rgba(238, 238, 238, 1)}
		.flowplayer .fp-timeline { background-color: rgba(204, 204, 204, 1)}
		.flowplayer .fp-progress { background-color: rgba(17, 17, 17, 1)}
		.flowplayer .fp-buffer { background-color: rgba(249, 249, 249, 1)}
	</style>
        <?php
        // 20141210_kc added function endswith and used for mozilla to handle mp4 with swf and mp3 with correct mime in html5
        function endswith($string, $test) {
            $strlen = strlen($string);
            $testlen = strlen($test);
            if ($testlen > $strlen) return false;
            return substr_compare($string, $test, $strlen - $testlen, $testlen) === 0;
            }

	if ($media)
	{
		?>

		<div id="jPalayer_div" style="display: none;">
			<video class="video-js vjs-default-skin" controls
				   preload="auto" width="700" height="320"
				   data-setup="{}">
				// before 20141210 type assignment in the following line was static string 'video/mp4'
				<source src="<?php echo $media['url']; ?>" type='<?php echo endswith($media['url'], 'mp3') ? 'audio/mp3' : 'video/mp4' ;?>'>

			</video>
		</div>

		<div id="flowPlayer_div" style="display: none;">
			<div data-swf="<?php echo site_url('js/flowplayer/flowplayer.swf'); ?>"
				 class="flowplayer no-toggle play-button color-light"
				 data-ratio="0.416" style="width: 720px;">
				<video>
					<source type="video/mp4" src="<?php echo $media['url']; ?>">

				</video>

			</div>

		</div>


		<div class="clearfix" style="margin-bottom: 15px;"></div>


		<?php
		// 20140925_kc begin remove link to proxy file for public user
		if ($this->role_id == 20)
		{
		?>
		<div style="margin-left: 20px;margin-top: 10px;"></div>
		<?php
		}
		else
		{
		?>
		<div style="margin-left: 20px;margin-top: 10px;"><a href="<?php echo $media['url']; ?>" target="=_blank">Open Proxy file</a></div>
		<?php
		}
		// 20140925_kc end remove link to proxy file for public user
		?>
		<script type="text/javascript">
			$(document).ready(function() {
				if ($.browser.chrome || $.browser.safari) {
					$('.fp-help').next().hide();
					$('.fp-embed').hide();
					$('#flowPlayer_div').show();
				}
				// 20141210_kc added browser test for mozilla and php test for media to assign the right player div
				else if ($.browser.mozilla)
				{
					<?php if (endswith($media['url'], 'mp4'))
					{
					?>
						$('.fp-help').next().hide();
						$('.fp-embed').hide();
						$('#flowPlayer_div').show();
					<?php }
					else
					{
					?>
						$('#jPalayer_div').show();
					<?php } ?>
				}
				// end 20141210_kc added section
				else {
					$('#jPalayer_div').show();
				}

			});
		</script>

		<?php
	}
	?>
</div>


