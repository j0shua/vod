<div class="grid_12">
    <div id="player" style="width:600px;height:300px;margin:0 auto;text-align:center">

    </div>

    <!-- this will install flowplayer inside previous A- tag. -->
    <script>
        $f("player", "http://releases.flowplayer.org/swf/flowplayer-3.2.18.swf", {
            clip: {
                url: 'flv:<?php echo $video_path; ?>',
                scaling: 'fit',
                // configure clip to use hddn as our provider, referring to our rtmp plugin
                provider: 'hddn'
            },
            // streaming plugins are configured under the plugins node
            plugins: {
                // here is our rtmp plugin configuration
                hddn: {
                    url: "flowplayer.rtmp-3.2.13.swf",
                    // netConnectionUrl defines where the streams are found
                    netConnectionUrl: '<?php echo $netConnectionUrl; ?>'
                }
            },
            canvas: {
                backgroundGradient: 'none'
            }, debug: true
        });
    </script>
    <a href="javascript:seek(5);">link</a>
    <a href="javascript:seek(10);">link</a>
    <a href="javascript:seek(15);">link</a>
    <a href="javascript:seek(20);">link</a>
</div> 