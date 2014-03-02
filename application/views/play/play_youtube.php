    <h1 class="main-title"><?php echo $title; ?>  <?php if ($make_money) {
    echo'[ free from youtube ]';
} else {
    echo'[from youtube ]';
} ?></h1>

<div class="hr-940px grid_12 "></div>
<div id="player" class="grid_9"></div>

<div class="grid_3">
    <h2 class="main-title">ดาวน์โหลด เอกสาร</h2>
    <div class="border">
            <?php if (!empty($resource_doc)) { ?>
            <ol>
                <?php
                foreach ($resource_doc as $doc) {

                    echo '<li>' . anchor('v/' . $doc['resource_id'], $doc['title'], array('target' => '_blank')) . '</li>';
                }
                ?>
            </ol>
            <?php
        } else {
            echo '<p>ยังไม่มีเอกสารให้ ดาวน์โหลด</p>';
        }
        ?>

    </div>
</div>
<script>
    // 2. This code loads the IFrame Player API code asynchronously.
    var tag = document.createElement('script');
    tag.src = "//www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    // 3. This function creates an <iframe> (and YouTube player)
    //    after the API code downloads.
    var player;
    function onYouTubeIframeAPIReady() {
        player = new YT.Player('player', {
            height: '529',
            width: '940',
            videoId: '<?php echo $video_code; ?>',
            events: {
                'onReady': onPlayerReady,
                'onStateChange': onPlayerStateChange
            }
        });
    }

    // 4. The API will call this function when the video player is ready.
    function onPlayerReady(event) {
        event.target.playVideo();
    }

    // 5. The API calls this function when the player's state changes.
    //    The function indicates that when playing a video (state=1),
    //    the player should play for six seconds and then stop.
    var done = false;
    function onPlayerStateChange(event) {
        //        if (event.data == YT.PlayerState.PLAYING && !done) {
        //          setTimeout(stopVideo, 6000);
        //          done = true;
        //        }
    }
    function stopVideo() {
        player.stopVideo();
    }
</script>
<div class="grid_9">
    <fb:like href="<?php echo $facebook_like_url; ?>" send="true" width="700" show_faces="true"></fb:like>
    <fb:comments href="<?php echo $facebook_url; ?>" num_posts="25" width="700"></fb:comments>
<?php echo $affiliate_link; ?>
</div>
<div class="clearfix"></div>
