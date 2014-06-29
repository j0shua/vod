jQuery(function(){
    $f("streams", "http://releases.flowplayer.org/swf/flowplayer-3.2.15.swf", {
        clip: {
 
            scaling:'fit',
            provider: 'cloudfront',
            // combined streams are configured in the "streams" node as follows:
            streams: [
            //{ url: 'krueonline/201103/a4931451_4e5db884.flv', start: 2, duration: 10},
            //{ url: 'krueonline/201103/a4931451_4e5db884.flv', start: 2, duration: 10}
            {
                url: video_path
            },
            ]
        },
        // our rtmp plugin is configured identically as in the first example
        plugins: {
            cloudfront: {
                url: "flowplayer.rtmp-3.2.11.swf",
                //netConnectionUrl: 'rtmpe://www.prokru.com:1936/krueonline'
                netConnectionUrl: netConnectionUrl
                
            }
        }
    });
});
