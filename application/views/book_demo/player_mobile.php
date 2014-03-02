<div>

    <a class="player"
       style="display:block;width:680px;height:540px;margin:0px auto"
       id="streams">
    </a>

</div>
<script>
    var netConnectionUrl = '<?php echo $netConnectionUrl; ?>';
    var video_path = '<?php echo $video_path; ?>';
    $(function() {
        $f("streams", "http://releases.flowplayer.org/swf/flowplayer-3.2.15.swf", {
            clip: {
                scaling: 'fit',
                provider: 'cloudfront',
                // combined streams are configured in the "streams" node as follows:
                streams: [
                    {
                        url: video_path
                    },
                ]
            },
            // our rtmp plugin is configured identically as in the first example
            plugins: {
                cloudfront: {
                    url: "flowplayer.rtmp-3.2.11.swf",
                    netConnectionUrl: netConnectionUrl

                }
            }
        });
        $f.toggleFullscreen();
    });

</script>