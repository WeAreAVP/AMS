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
<div id="wowza" style="width:644px;height:276px;margin:0 auto;text-align:center">
    <img src="/media/img/player/splash_black.jpg" height="276" width="548" />
</div>
		<script>
		$f("wowza", "http://releases.flowplayer.org/swf/flowplayer-3.2.16.swf", {
 
    clip: {
        url: 'mp4:<?php echo $media['url']; ?>',
        scaling: 'fit',
        // configure clip to use hddn as our provider, referring to our rtmp plugin
        provider: 'hddn'
    },
 
    // streaming plugins are configured under the plugins node
    plugins: {
 
        // here is our rtmp plugin configuration
        hddn: {
            url: "flowplayer.rtmp-3.2.12.swf",
 
            // netConnectionUrl defines where the streams are found
            netConnectionUrl: 'rtmp://rtmp01.hddn.com/play'
        }
    },
    canvas: {
        backgroundGradient: 'none'
    }
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
