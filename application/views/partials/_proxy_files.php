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
		<div id="myElement">Loading the player...</div>

		<script type="text/javascript">
			jwplayer("myElement").setup({
				flashplayer: "/js/jwplayer/jwplayer.flash.swf",
				file: "<?php echo $media['url']; ?>",
				width:700,
				height:300,
//				image: "http://content.bitsontherun.com/thumbs/3XnJSIm4-640.jpg"
			});
		</script>
		<?php
	}
}
?>

