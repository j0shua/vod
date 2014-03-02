<div class="top_10">
<div class="grid_3">
    <h2  class="head1">ห้องเรียนของ</h2>
    <div class="border">
        <p>ครู <?php echo $user['first_name'] . ' ' . $user['last_name']; ?></p>
    </div>


    <h2  class="head1" >รายชื่อหนังสืออื่นๆ</h2>
    <div class="border">

        <ul>
            <?php
            foreach ($taxonomy as $v) {

                echo '<li><a href="' . site_url('house/u/' . $uid . '/' . $v['tid']) . '">' . $v['title'] . '</a></li>';
            }
            ?>
        </ul>
    </div>
    <?php if (count($course_open) > 0) { ?>
        <h2  class="head1" >หลักสูตรที่เปิดสอน</h2>
        <div class="border">

            <ul>
                <?php
                foreach ($course_open as $v) {

                    echo '<li><a href="' . site_url('house/u/' . $uid . '/' . $v['tid']) . '">' . $v['title'] . '</a></li>';
                }
                ?>
            </ul>
        </div>
    <?php } ?>

</div>
<div class="grid_9">

    <h1  class="head1" ><?php echo $taxonomy_title; ?></h1>
    <div class="border">
        <p><?php echo $taxonomy_desc; ?></p>
    </div>
    <div class="border">

        <?php foreach ($sub_taxonomy as $v) { ?>
            <table class="tb-main">
                <thead>
                    <tr>
                        <th colspan="5" class="tbl-title"><?php echo $v['title']; ?></th>    

                    </tr>
                    <tr>
                        <th class="tb-col-resource-id">เลขที่สื่อ</th>    
                        <th class="tb-col-title">ชื่อ</th>    
                        <th class="tb-col-duration">เวลา</th>    
                        <?php if ($make_money) { ?>
                            <th class="tb-col-price">ค่าบริการ</th>    
                        <?php } ?>
                        <th class="tb-col-price">เอกสาร</th>    

                    </tr>
                </thead>
                <tbody>
                    <?php
                    $a_resource_doc = array();

                    foreach ($v['resource_set'] as $v2) {
//print_r($v2);
                        if ($v2['unit_price'] > 0) {
                            $price = $v2['unit_price'] . ' บาท/ชั่วโมง';
                        } else {
                            $price = 'free';
                        }
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $v2['resource_id']; ?></td>
                            <td><a href="<?php echo $v2['link']; ?>"><?php echo $v2['title']; ?></a> <?php echo $v2['edit_link']; ?></td>
                            <td><?php echo gmdate("H:i:s", $v2['duration']); ?></td>
                            <?php if ($make_money) { ?>
                                <td><?php echo $price ?></td>
                            <?php } ?>
                            <td><?php
                                if (!in_array($v2['resource_id_doc'], $a_resource_doc)) {
                                    if ($v2['resource_id_doc']) {
                                        echo anchor('v/' . $v2['resource_id_doc'], 'ดาวน์โหลดเอกสาร', 'target="_blank"');
                                    }
                                }
                                $a_resource_doc[$v2['resource_id']] = $v2['resource_id_doc'];
                                ?></td>



                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>
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
</style>