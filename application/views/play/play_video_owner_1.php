    <h1 class="main-title"><?php echo $title; ?> <?php
if ($make_money) {
    echo '[ ของตนเอง ฟรี ]';
} else {
    echo '[ ของตนเอง]';
}
?> </h1>


<div class="hr-940px grid_12 "></div>
<div class="grid_12">

    <a class="player"
       style="display:block;width:100%;height:540px;margin:10px auto"
       id="streams">
    </a>
</div>
<script>
    var netConnectionUrl = '<?php echo $netConnectionUrl; ?>';
    var video_path = '<?php echo $video_path; ?>';
</script>

