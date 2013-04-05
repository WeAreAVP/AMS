<?php
if ($media)
{
	if ($media['format'] == 'mp3')
	{
		?>

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


		<!--		<video controls="controls" width="640" height="360">
					<source src="<?php echo $media['url']; ?>" type="video/mp4" />
					<object data="http://releases.flowplayer.org/swf/flowplayer-3.2.1.swf" width="640" height="360">
						<param name="src" value="<?php echo $media['url']; ?>">
						<embed src="<?php echo $media['url']; ?>" type="video/mp4" width="640" height="360"/>
					</object> 


				</video>-->
		<!--		<div id="myElement">Loading the player...</div>

				<script type="text/javascript">
					jwplayer("myElement").setup({
						flashplayer: "/js/jwplayer/jwplayer.flash.swf",
						file: "<?php echo $media['url']; ?>",
						width:700,
						height:300,
		//				image: "http://content.bitsontherun.com/thumbs/3XnJSIm4-640.jpg"
					});
				</script>-->
		<?php
	}
}
?>

