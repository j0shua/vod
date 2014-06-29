<?php
if ($resource_code) {
    echo '<h1 class="resource_code">รหัสวิดีโอ : ' . $resource_code . '</h2>';
}
?>
<?php
if ($is_play_continue) {
    $opt1 = 'selected="selected"';
    $opt0 = '';
} else {
    $opt1 = '';
    $opt0 = 'selected="selected"';
}
?>
<div class="grid_12" style="margin-bottom: 10px;">
    <select id="btn_continue_video">
        <option value="1" <?php echo $opt1; ?>>เปิดต่อเนื่อง</option>
        <option value="0" <?php echo $opt0; ?>>ไม่เปิดต่อเนื่อง</option>
    </select>
</div>


<div class="grid_9">

    <div id='my-video'></div>

    <h1><?php echo $title; ?>  <?php echo $price_txt; ?> </h1>

    <fb:like href="<?php echo $facebook_like_url; ?>" send="true" width="880" show_faces="true"></fb:like>
    <fb:comments href="<?php echo $facebook_url; ?>" num_posts="25" width="880"></fb:comments>
    <?php echo $affiliate_link; ?>
</div>

<div class="grid_3 right_side">
    <?php if ($join_content_link) { ?>


        <div style="margin-bottom: 10px;">
            <?php echo anchor($join_content_link, 'ดาวน์โหลด เอกสาร', 'class="btn-a"'); ?>


        </div>
    <?php } ?>
    <?php if ($resource_playlist['total'] > 0) { ?>
        <h2 class="head1 no_buttom">วิดีโอในบทนี้</h2>
        <div>
            <ul class="same_taxonomy_list">
                <?php
                foreach ($resource_playlist['rows'] as $v) {
                    if ($v['current']) {
                        echo '<li><span title="' . $v['desc'] . '">' . $v['title'] . '</span></li>';
                    } else {
                        echo '<li>' . anchor($v['url'], $v['title'], 'title="' . $v['desc'] . '"') . '</li>';
                    }
                }
                ?>
            </ul>
        </div>
    <?php } ?>
</div>

<div class="clearfix"></div>

<style>

    .resource_code{
        padding: 10px;
        margin-bottom: 10px;
    }
    .same_taxonomy_list{
        border: solid 1px #CCCCCC;
        border-radius: 2px;
        height: 640px;
        overflow-y: scroll;
    }
    .same_taxonomy_list li{
        margin-left: 0px;
    }
    .same_taxonomy_list span{
        font-weight: bold;

    }
    .same_taxonomy_list span {
        background-color: #EEEEEE;
        color: #f66;
        display: block;
        font-weight: bold;
        padding: 5px 5px;
    }
    .same_taxonomy_list a {
        background-color: #ffffff;

        display: block;
        padding: 5px 5px;


    }
    .same_taxonomy_list a:hover {
        background-color: #EEEEEE;
        text-decoration: none;

    }
    .no_buttom{
        margin-bottom: 0px;
    }
</style>



