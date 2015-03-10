<link href="http://vjs.zencdn.net/c/video-js.css" rel="stylesheet">
<!--<script src="http://vjs.zencdn.net/c/video.js"></script>-->
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
    if ($media) {
        ?>
        <div id="jPalayer_div" style="display: none;">
            <iframe width="700" height="320" src="http://www.youtube.com/embed/hamhN3u2rM0">
            </iframe>
            <!--			<video class="video-js vjs-default-skin" controls
                                               preload="auto" width="700" height="320"
                                               data-setup="{}">
                                            <source src="https://www.youtube.com/watch?v=hamhN3u2rM0" type='video/mp4'>
            
                                    </video>-->
        </div>
        <div id="flowPlayer_div" style="display: none;">
<!--            <div data-swf="<?php// echo site_url('js/flowplayer/flowplayer.swf');   ?>"
                 class="flowplayer no-toggle play-button color-light"
                 data-ratio="0.416" style="width: 720px;">-->
                <iframe width="700" height="320"
                        src="http://www.youtube.com/embed/hamhN3u2rM0">
                </iframe>
                <!--                        <video>
                                                <source type="video/mp4" src="https://www.youtube.com/watch?v=hamhN3u2rM0">
                
                                        </video>-->

            <!--</div>-->


        </div>


        <div class="clearfix" style="margin-bottom: 15px;"></div>



        <div style="margin-left: 20px;margin-top: 10px;"><a href="https://www.youtube.com/watch?v=hamhN3u2rM0" target="=_blank">Open Proxy file</a></div>
        <script type="text/javascript">
            $(document).ready(function () {

                if ($.browser.chrome || $.browser.safari) {
                    $('.fp-help').next().hide();
                    $('.fp-embed').hide();
                    $('#flowPlayer_div').show();
                }
                else {
                    $('#jPalayer_div').show();
                }

            });
        </script>

        <?php
    }
    ?>
</div>


