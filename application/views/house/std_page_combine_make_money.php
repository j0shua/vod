<div class="top_10">
    <div class="grid_3">
        <h2  class="head1">+ ห้องเรียนของ +</h2>
        <div class="room-wrapper clearfix">
            <div  class="avatar-img clearfix">  <img  class="clearfix"  title="รูปประจำตัว" src="<?php echo site_url('ztatic/img_avatar_128/' . $user['uid']); ?>"></div>
            <p>ครู<?php echo $user['first_name'] . ' ' . $user['last_name']; ?></p>

        </div>
        <?php if ($taxonomy) { ?>
            <h2  class="head1" style="margin-bottom: 2px;">รายการชุดการเรียน</h2>

            <table class="data" style="margin-bottom: 15px;">
                <tbody>
                    <tr>
                        <?php
                        foreach ($taxonomy as $v) {

                            echo '<tr><td><a href="' . site_url('house/u/' . $uid . '/' . $v['tid']) . '">' . $v['title'] . '</a></td></tr>';
                        }
                        ?>
                    </tr>
                </tbody>
            </table>
        <?php } ?>

        <?php if (count($course_open['rows']) > 0) { ?>
            <h2  class="head1" style="margin-bottom: 2px;" >หลักสูตรที่เปิดสอน</h2>
            <div>
                <table class="data">

                    <tbody>
                        <?php
                        // print_r($course_open['rows']);
                        foreach ($course_open['rows'] as $row) {
                            $cell = $row['cell'];
                            echo ' <tr>';
                            echo '<td><span title="' . $cell['desc'] . '">' . $cell['title'] . '</span></td>';
                            if ($cell['action']) {
                                echo '<td style="width: fit-content;white-space: nowrap;">' . $cell['action'] . '</td>';
                            }

                            echo ' </tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>

    </div>
    <div class="grid_9">
        <?php if ($taxonomy_title) { ?>
            <h1  class="head1" ><?php echo $taxonomy_title; ?>
                <?php
                if (isset($copy_url)) {
                    ?>
                    <a href="<?php echo $copy_url; ?>" class="btn-a">ทำลิ้งชุดวิดีโอนี้</a>
                    <?php
                }
                ?>
            </h1>

            <div id="sidetree">
                <ul id="tree"> 
                    <?php
                    foreach ($sub_taxonomy as $v) {

                        if (count($v['resource_set']) > 0) {
                            echo '<li >';
                            echo '<div class="taxonomy_title_wraper">';
                            echo '<a target="_blank" class="taxonomy_title" href="' . ($v['resource_set'][0]['link'] . '?pltid=' . $v['tid']) . '"><strong>▶ ' . $v['title'] . '</strong></a>';
                            if ($v['have_join_content']) {
                                echo '<a target="_blank" class="btn-a-small" href="' . (site_url('/ztatic/download_join_content?plopt=pltid&plvalue=' . $v['tid']) ) . '">ดาวน์โหลดเอกสารประกอบ</a>';
                            } elseif ($v['tid_parent_site'] != 0) {
                                echo '<a target="_blank" class="btn-a-small" href="' . $parent_site_url . 'ztatic/download_join_content?plopt=pltid&plvalue=' . $v['tid_parent_site'] . '">ดาวน์โหลดเอกสารประกอบ</a>';
                            }
                            echo '<span>[' . gmdate("H:i:s", @$v['sum_duration']) . ']</span>';

                            echo '</div>';
                            //echo '<a class="btn-a-small" href="' . ($v['resource_set'][0]['link'] . '?pltid=' . $v['tid']) . '"><strong>▶ เปิดดู</strong></a>';
                            //echo '<a style="margin-left:10px;" href="#"><strong>ดาวโหลดเอกสาร</strong></a>';
                            echo '<ul>';
                            $a_resource_join = array();
                            foreach ($v['resource_set'] as $v2) {
                                if ($v2['unit_price'] > 0) {
                                    $price = $v2['unit_price'] . ' บาท/ชม.';
                                } else {
                                    $price = 'free';
                                }
                                echo '<li>';

                                echo '<a target="_blank"  class="taxonomy_col_1" href="' . ($v2['link'] . '?pltid=' . $v['tid']) . '">▶ ' . $v2['title'] . '</a>';

                                echo '<span class="taxonomy_col_2">' . gmdate("H:i:s", @$v2['duration']) . '</span>';
                                echo '<span class="taxonomy_col_2">' . $price . '</span>';


                                if (!in_array($v2['resource_id_join'], $a_resource_join)) {
                                    if ($v2['resource_id_join']) {
                                        echo '<span class="taxonomy_col_3">' . anchor('v/' . $v2['resource_id_join'], 'ดาวน์โหลดเอกสารย่อย', 'target="_blank" class="btn-a-small"') . '</span>';
                                    }
                                }
                                $a_resource_join[$v2['resource_id']] = $v2['resource_id'];
                                echo '</li>';
                            }
                            echo '</ul>';
                            echo '</li>';
                        }
                    }
                    ?>




                </ul>
            </div>
        <?php } ?>
    </div>
    <style>
        .taxonomy_title_wraper{
            border: solid 1px #CCCCCC;
            background-color: #ffffff;
            padding: 5px;

            padding-left: 20px;
        }
        .taxonomy_title_wraper .btn-a-small{

        }
        .taxonomy_title{
            font-size: 20px;
            margin-right: 10px;

            display: inline-block;
            margin-right: 40px;
        }
        #sidetree li li{
            padding-top: 0;
            padding-bottom: 0;
        }
        #sidetree li li{
            border-top: solid 1px #CCCCCC;
            border-right: solid 1px #CCCCCC;
            border-left: solid 1px #CCCCCC;
        }
        #sidetree li li.last{
            border-bottom: solid 1px #CCCCCC;
        }

        .taxonomy_col_1{
            width: 500px;
            display: inline-block;

            padding: 5px;
        }
        .taxonomy_col_2{
            width: 70px;
            display: inline-block;
            border-left: solid 1px #CCCCCC;
            padding: 5px;
        }
        .taxonomy_col_3{
            width: 140px;
            display: inline-block;
            border-left: solid 1px #CCCCCC;
            text-align: center;
            padding: 5px;
        }
    </style>
    <script type="text/javascript">
        $(function() {
            $("#tree").treeview({
                collapsed: true,
                animated: "medium",
                persist: "location"
            });
        })

    </script>



</div>
<style>
    .avatar-img{
        width: 128px;
        height: 128px;
        background-color: #ffffff;
        display: block;
        float: left;
        border: solid 1px #CCCCCC;
        padding: 5px;
        border-radius: 2px;
        margin-right: 10px;
    }
    .room-wrapper{
        margin-bottom: 10px;
    }
    .room-wrapper p{
        font-size: 16px;
        text-overflow: clip;
    }

</style>