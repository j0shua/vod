<video id="example_video_1" class="video-js vjs-default-skin" controls preload="none" width="640" height="264"
     
      data-setup="{}">
    <source src="<?php echo $video_path; ?>" type='video/mp4' />
    <source src="http://video-js.zencoder.com/oceans-clip.webm" type='video/webm' />
    <source src="<?php echo $video_path_iphone; ?>" type='video/mp4' />
    <track kind="captions" src="demo.captions.vtt" srclang="en" label="English"></track><!-- Tracks need an ending tag thanks to IE9 -->
    <track kind="subtitles" src="demo.captions.vtt" srclang="en" label="English"></track><!-- Tracks need an ending tag thanks to IE9 -->
  </video>

  <script>
    videojs.options.flash.swf = base_url("assets/video-js/video-js.swf");
  </script>
  <style>
     .vjs-control-content { width: 25px; right: -25px; display: none !important;}
  </style>