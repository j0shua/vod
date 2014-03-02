<h1>{form_title}</h1>
<div class="grid_12 align_center">
    <h4>สลับโหมดการดู วิดีโอ</h4>
    <span class="mode-text">ดูวิดีโอแบบต่อเนื่อง</span>  <div class="bool-slider true"> <div class="inset"> <div class="control"></div> </div> </div><span class="mode-text">ดูวิดีโอแบบเป็นข้อๆ</span> 
</div>
<div class="grid_12">

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


        $('.bool-slider .inset .control').click(function() {
            if (!$(this).parent().parent().hasClass('disabled')) {
                if ($(this).parent().parent().hasClass('true')) {
                    $(this).parent().parent().addClass('false').removeClass('true');
                } else {
                    $(this).parent().parent().addClass('true').removeClass('false');
                }
            }
        });
    });

</script>
<style>
    .bool-slider { border: 1px solid #CCC; color: #FFF; font-size: 18px; font-weight: 800; font-family: Helvetica, Verdana, Arial, sans-serif; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); height: 35px; width: 100px; border-radius: 25px; } .bool-slider.true .inset { background-color: #51a351; *background-color: #499249; background-image: linear-gradient(top, #62c462, #51a351); background-repeat: repeat-x; border-color: #51a351 #51a351 #387038; border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25); background-image: none; box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05); } .bool-slider.true .inset .control{float: left;} .bool-slider.true .inset .control:after { content: '>'; position: relative; right: -135%; top: 20%; } .bool-slider.false .inset { background-color: #da4f49; *background-color: #bd362f; background-image: linear-gradient(top, #ee5f5b, #bd362f); background-repeat: repeat-x; border-color: #bd362f #bd362f #802420; border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25); background-image: none; box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05); } .bool-slider.false .inset .control{float: right;} .bool-slider.false .inset .control:before { content: '<'; position: relative; left: -100%; top: 20%; } .bool-slider .inset { width: 100%; height: 100%; border-radius: 20px; } .bool-slider .inset .control { background-color: #000; width: 40%; height: 100%; border-radius: 20px; background-color: #f5f5f5; *background-color: #e6e6e6; background-image: linear-gradient(top, #ffffff, #e6e6e6); background-repeat: repeat-x; } .bool-slider .inset .control:hover { cursor: pointer; } .bool-slider.disabled { color: #CCC; } .bool-slider.disabled .inset { background-color: #f5f5f5; *background-color: #e6e6e6; background-image: linear-gradient(top, #ffffff, #e6e6e6); background-repeat: repeat-x; } .bool-slider.disabled .control { cursor: default; } 
    .bool-slider{display: inline-block;}
    .mode-text{
        font-size: 18px;
        text-align: center;
        height: 30px;
        display: inline-block;
        
    }
    h4{
        margin-bottom: 15px;
    }
</style>