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
				startparam: "starttime",
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

			</video>
		</div>
		<div class="clearfix" style="margin-bottom: 15px;"></div>
		<!--			<OBJECT CLASSID="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B"
					CODEBASE="http://www.apple.com/qtactivex/qtplugin.cab" 
					WIDTH="320" HEIGHT="256" >
					<PARAM NAME="src" VALUE="<?php echo $media['url']; ?>">
					<PARAM NAME="autoplay" VALUE="false">
					<PARAM NAME="controller" value="true">
					<EMBED SRC="QTMimeType.pntg" TYPE="image/x-macpaint"
					PLUGINSPAGE="http://www.apple.com/quicktime/download" QTSRC="<?php echo $media['url']; ?>" 
					WIDTH="320" HEIGHT="256" AUTOPLAY="true" CONTROLLER="true">
					</EMBED>
					</OBJECT>-->


		<div style="margin-left: 20px;margin-top: 10px;"><a href="<?php echo $media['url']; ?>" target="=_blank">Open Proxy file</a></div>


		<?php
	}
	?>
</div>


