<div class="top_10">
    <div class="grid_3">
        <h2  class="head1">ห้องเรียนของ</h2>
        <div class="room-wrapper clearfix">
            <div  class="avatar-img clearfix">  <img  class="clearfix"  title="รูปประจำตัว" src="<?php echo site_url('ztatic/img_avatar_128/' . $user['uid']); ?>"></div>
            <p>ครู<?php echo $user['first_name'] . ' ' . $user['last_name']; ?></p>

        </div>

        <h2  class="head1" >รายการชุดการเรียน</h2>


        <ul>
            <?php
            foreach ($taxonomy as $v) {

                echo '<li><a href="' . site_url('house/u/' . $uid . '/' . $v['tid']) . '">' . $v['title'] . '</a></li>';
            }
            ?>
        </ul>

        <?php if (count($course_open['rows']) > 0 && $this->auth->get_rid() != 3) { ?>
            <h2  class="head1" >หลักสูตรที่เปิดสอน</h2>
            <div>
                <table class="tb-main">
                    <thead>
                        <tr>
                            <th style="width: 80%;" >หลักสูตร</th>
                            <th>กระทำ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // print_r($course_open['rows']);
                        foreach ($course_open['rows'] as $row) {
                            $cell = $row['cell'];
                            echo ' <tr>
                                <td>' . $cell['title'] . '</td>
                                <td>' . $cell['action'] . '</td>
                            </tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>

    </div>
    <div class="grid_9">

        <h1  class="head1" ><?php echo $taxonomy_title; ?>
            <?php
            if (isset($copy_url)) {
                ?>
                <a href="<?php echo $copy_url; ?>" class="btn-a">ทำลิ้งชุดวิดีโอนี้</a>
                <?php
            }
            ?>
        </h1>
        <div class="border">
            <p><?php echo $taxonomy_desc; ?></p>
        </div>
        <div class="border">

            <?php
            foreach ($sub_taxonomy as $v) {
                if (count($v['resource_set']) > 0) {
                    ?>
                    <table class="tb-main">
                        <thead>
                            <tr>
                                <th colspan="5" class="tbl-title">
                                    <?php echo anchor($v['resource_set'][0]['link'], $v['title']); ?>
                                </th>    

                            </tr>
                            <tr>

                                <th class="tb-col-title">ชื่อวิดีโอ</th>    
                                <th class="tb-col-duration">เวลา</th>    
                                <?php if ($make_money) { ?>
                                    <th class="tb-col-price">ค่าบริการ</th>    
                                <?php } ?>
                                <th class="tb-col-price">เอกสาร</th>    

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $a_resource_join = array();

                            foreach ($v['resource_set'] as $v2) {
//print_r($v2);
                                if ($v2['unit_price'] > 0) {
                                    $price = $v2['unit_price'] . ' บาท/ชั่วโมง';
                                } else {
                                    $price = 'free';
                                }
                                ?>
                                <tr>

                                    <td><a style="float: left;" href="<?php echo $v2['link']; ?>"><?php
                                            //echo ($v2['resource_code'] != '') ? '[' . $v2['resource_code'] . '] ' . $v2['title'] : $v2['title']; 
                                            echo $v2['title'];
                                            ?></a> <span style="float: right;"><?php echo $v2['edit_link']; ?></span></td>
                                    <td><?php echo gmdate("H:i:s", $v2['duration']); ?></td>
                                    <?php if ($make_money) { ?>
                                        <td><?php echo $price ?></td>
                                    <?php } ?>
                                    <td><?php
                                        if (!in_array($v2['resource_id_join'], $a_resource_join)) {
                                            if ($v2['resource_id_join']) {
                                                echo anchor('v/' . $v2['resource_id_join'], 'ดาวน์โหลดเอกสาร', 'target="_blank"');
                                            }
                                        }
                                        $a_resource_join[$v2['resource_id']] = $v2['resource_id'];
                                        ?></td>



                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <?php
                }
            } //subtaxonony 
            ?>
        </div>
    </div>
</div>
<style>
    .tb-main{
        width: 100%;
        margin-bottom: 15px;
    }
    .tb-main td{
        border: solid 1px #0272a7;
        padding: 5px;
    }
    .tb-main thead th{
        border: solid 1px #0272a7;
        color: #ffffff;
        background-color: #029FEB;
        padding: 5px;
    }
    .tb-main .tb-col-resource-id{ width:  50px;}
    .tb-main .tb-col-title{ width:  55%;}
    .tb-main .tbl-title{
        background: #ffffff;
        color: #4A4A4A;
        font-weight: bold;
        text-align: left;
        font-size: 20px;
    }
    .text-center{
        text-align: center;
    }
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