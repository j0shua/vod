<h1><?php echo $title; ?></h1>

<div class="grid_12">

    <form id="main-form" class="normal-form" action="<?php echo $form_action; ?>" method="post"  enctype="multipart/form-data" accept-charset="utf-8">
        <input id="ca_id" type="hidden" name="data[ca_id]" value="<?php echo $act_data['ca_id']; ?>">
        <input id="c_id" type="hidden" name="data[c_id]" value="<?php echo $act_data['c_id']; ?>">
        <input id="st_id" type="hidden" name="data[st_id]" value="<?php echo $act_data['st_id']; ?>">

        <?php if ($act_data['cmat_id'] == 2) { ?>
            <p>
                 <label for="head_data">คำสั่ง </label>
                <?php echo anchor('v/' . $act_data['data'], 'เปิดดูใบงาน', 'class="btn-a-small" target="_blank"'); ?>
            </p>
        <?php } else { ?>
            <p>
                <label for="head_data">คำสั่ง </label>
                <span class="command_description"><?php echo nl2br(auto_link($act_data['data'],'both',TRUE)); ?></span>
            </p>
        <?php } ?>
    
        <p>
            <label for="file_upload">เลือกไฟล์งานที่ต้องการส่ง </label>
            <input type="file" id="file_upload" name="userfile" size="20" />
            <br/>
            <span style="color: red;">* ขนาดไฟล์ต้องไม่เกิน <?php echo byte_format($send_act_max_size); ?>  
            <br/>
            * นามสกุลไฟล์ต้องเป็น <?php echo $send_act_allowed_types; ?> เท่านั้น 
            </span>
        </p>
        <input type="submit" value="อัพโหลด" id="btnSubmit" class="btn-submit" >

        <a href="<?php echo $cancel_link; ?>" class="btn-a">ยกเลิก</a>

    </form>
</div>
<style>
    .command_description{
        border: solid 1px #CBCBCB;
        background-color: #FFFFFF;
        padding: 5px;
        width: 1005px;
        
        display: inline-block;
        
    }
    
</style>









