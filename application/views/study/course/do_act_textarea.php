<h1><?php echo $title; ?></h1>
<div class="grid_12">
    <form id="main-form" class="normal-form" action="<?php echo $form_action; ?>" method="post"  accept-charset="utf-8">
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
                <textarea class="" style="width: 974px;height: 80px;overflow-x: hidden;overflow-y: auto;resize: vertical;"><?php echo nl2br($act_data['data']); ?></textarea>
            </p>
        <?php } ?>
        <p>
            <label for="data">งานที่ต้องการส่ง </label>
            <textarea  type="text"  style="width: 350px;height: 100px;" id="data" name="data[data]" ><?php echo $form_data['data']; ?></textarea>
        </p>
        <input type="submit" value="ส่งงาน" id="btnSubmit" class="btn-submit" >
        <a href="<?php echo $cancel_link; ?>" class="btn-a">ยกเลิก</a>
    </form>
</div>









