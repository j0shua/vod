<?php echo $main_side_menu; ?>
<div class="grid_10">

    <h2 class="head1">{title}</h2>
    <div class="clearfix">
        <?php if ($can_upload) { ?>
            <a href="<?php echo $upload_link; ?>" class="btn-a" style="float: left;">Upload</a>
        <?php } ?>
        <?php if ($can_upload) { ?>
            <span style="margin-top: 5px;float: left;" class="messages warning">คุณใช้พื้นที่ในการบันทึกข้อมูลไปแล้ว <?php
                echo '<b>' . $user_disk_size . '</b>';
                if ($make_money) {
                    echo ' จากขีดจำกัด <b>' . $user_disk_quota . '</b>';
                }
                ?></span>
        <?php } else { ?>
            <span style=""  class="messages error">คุณใช้พื้นที่ในการบันทึกข้อมูลไปแล้ว <?php
                echo '<b>' . $user_disk_size . '</b>';
                if ($make_money) {
                    echo ' จากขีดจำกัด <b>' . $user_disk_quota . '</b>';
                }
                ?> จึงไม่สามารถอัพโหลดได้อีก โปรดติดต่อเจ้าหน้าที่เพื่อเพิ่มเนื้อที่การอัพโหลด</span>
        <?php } ?>
    </div>
    <div style="margin-top: 5px;">
        <?php echo form_dropdown('qtype', $qtype_options, 'title', 'id="qtype"'); ?>
        <input id="query" type="text" name="query">
        <input class="btn-a-small" type="button" id="btn_search" value="กรอง"> 
    </div>
    <fieldset class="grid-fieldset"><legend>กระทำกับข้อมูล</legend>
        <?php echo form_dropdown('dd_act_grid', $command_to_resource_options, '', 'id="dd_act_grid"') ?>

        <input  class="btn-a-small"  type="button" id="btn_act_grid" value="กระทำกับที่เลือก"> 
    </fieldset>
    <div class="flexigrid_wrap">
    <table id="main-table" style="display: none"></table>
    </div>

</div>


