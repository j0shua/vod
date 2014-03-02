   <h1 class="main-title"><?php echo $title; ?> <?php
if ($make_money) {
    echo'[ free from dailymotion ]';
} else {
    echo'[from dailymotion ]';
}
?></h1>

<div class="hr-940px grid_12 "></div>
<div class="grid_9">
    <iframe frameborder="0" width="700" height="420" src="http://www.dailymotion.com/embed/video/<?php echo $video_code; ?>"></iframe>
</div>

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
<div class="grid_9">
    <fb:like href="<?php echo site_url(); ?>" send="true" width="700" show_faces="true"></fb:like>
    <fb:comments href="<?php echo current_url(); ?>" num_posts="25" width="700"></fb:comments>
</div>
<div class="clearfix"></div>

