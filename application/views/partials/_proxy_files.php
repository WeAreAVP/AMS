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
		<?php
	}
}
?>

