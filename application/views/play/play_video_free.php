<?php
if (@$resource_code) {
    echo '<h1 class="resource_code">รหัสวิดีโอ : ' . $resource_code . '</h1>';
}
?>




<div class="grid_9">

    <a class="player"
       style="display:block;width:100%;height:540px;margin:0px auto"
       id="streams">
    </a>
    <h1><?php echo $title; ?> ฟรี </h1>
    <fb:like href="<?php echo @$facebook_like_url; ?>" send="true" width="880" show_faces="true"></fb:like>
    <fb:comments href="<?php echo $facebook_url; ?>" num_posts="25" width="880"></fb:comments>

</div>

<div class="grid_3 right_side">
    <?php if (!empty($resource_doc)) { ?>
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
    <?php } ?>
    <?php
    if (isset($resource_same_sub_taxonomy)) {
        if ($resource_same_sub_taxonomy['total'] > 0) {
            ?>
            <h2 class="head1 no_buttom">วิดีโอในบทนี้</h2>
            <div>
                <ul class="same_taxonomy_list">
                    <?php
                    foreach ($resource_same_sub_taxonomy['rows'] as $v) {
                        if ($v['current']) {
                            echo '<li><span title="' . $v['desc'] . '">' . $v['title'] . '</span></li>';
                        } else {
                            echo '<li>' . anchor($v['url'], $v['title'], 'title="' . $v['desc'] . '"') . '</li>';
                        }
                    }
                    ?>
                </ul>
            </div>
        <?php }
    }
    ?>
</div>
<script>
    var netConnectionUrl = 'rtmpe://203.146.170.199:1936/educasy';
    var video_path = '<?php echo $video_path; ?>';
</script>


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
