<meta http-equiv="Content-Type" content="video/mp4" />
<div style="margin-bottom: 10px;">
	<?php
	if ($media)
	{
		?>
		<div class="flowplayer">
			<video>
				<source type="video/mp4" src="<?php echo $media['url']; ?>"/>
			</video>
		</div>
		<div class="clearfix"></div>
		<div style="margin-left: 20px;margin-top: 10px;"><a href="<?php echo $media['url']; ?>" target="=_blank">Open Proxy file</a></div>
		<script type="text/javascript">
			$(document).ready(function() {
				flowplayer("flowplayer", "/path/to/file/flowplayer-3.2.6.swf");
			});
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
