<h1><?php echo $title; ?></h1>

<div class="grid_7">
    <form id="main-form" class="normal-form" action="<?php echo $form_action; ?>" method="post" >
        <input id="ca_id" type="hidden" name="data[ca_id]" value="<?php echo $form_data['ca_id']; ?>">
        <input id="c_id" type="hidden" name="data[c_id]" value="<?php echo $form_data['c_id']; ?>">
        <p>
            <label for="title">ชื่องาน </label>
            <input type="text" style="width: 350px;"  id="title" name="data[title]" value="<?php echo $form_data['title']; ?>">
        </p>
        <p>
            <label for="at_id">ประเภทงาน </label>
            <?php echo form_dropdown('data[at_id]', $act_type_options, $form_data['at_id'], 'id="at_id"'); ?>
        </p>

        <p>

            <?php
            $cmat_id_title = $cmat_id_options[1] . ' : สั่งโดยกรอกคำสั่งลงใน ช่อ รายละเอียดคำสั่ง<br>';
            $cmat_id_title .= $cmat_id_options[2] . ' : สั่งโดยการเลือกใบงานในช่อง เลขที่ใบงาน ';
            ?>
            <label for="cmat_id">วิธีสั่งงาน </label>
            <?php
            if ($form_data['ca_id'] == '') {
                echo form_dropdown('data[cmat_id]', $cmat_id_options, $form_data['cmat_id'], 'id="cmat_id"');
            } else if ($can_edit) {
                echo form_dropdown('data[cmat_id]', $cmat_id_options, $form_data['cmat_id'], 'id="cmat_id"');
            } else {
                echo form_dropdown('data[cmat_id]', $cmat_id_options, $form_data['cmat_id'], 'id="cmat_id" style="display:none;"');
                echo form_dropdown('', $cmat_id_options, $form_data['cmat_id'], 'disabled ');
            }
            ?>
        </p>
        <p>
            <label for="data"><span id="label_data">รายละเอียดคำสั่ง </span></label>
            <?php if ($can_edit) { ?>
                <textarea id="data" name="data[data]" ><?php echo $form_data['data']; ?></textarea>
                <a id="btn-search-sheet" style="display: inline-block;height:22px;line-height:22px;margin-top:1px;vertical-align:top;" href="#" class="btn-a-small">ค้นหา</a>
            <?php } else { ?>
                <textarea id="data" disabled><?php echo $form_data['data']; ?></textarea>
                <input id="data_hidden" name="data[data]" type="hidden"  value="<?php echo $form_data['data']; ?>" />
            <?php } ?>

        </p>



        <p>
            <label for="st_id">วิธีส่งงาน </label>
            <?php
            if ($form_data['ca_id'] == '') {
                echo form_dropdown('data[st_id]', array(), '', 'id="st_id" class="class_st_id"');
            } else if ($can_edit) {
                echo form_dropdown('data[st_id]', array(), '', 'id="st_id" class="class_st_id"');
            } else {
                echo form_dropdown('data[st_id]', $st_id_options_sheet, '', 'id="st_id" class="class_st_id" style="display:none;"');
                echo form_dropdown('', $st_id_options_sheet, $form_data['st_id'], 'disabled ');
            }
            ?>
        </p>



        <p id="p_full_score">
            <label for="full_score">คะแนนเต็ม </label>
            <input type="text" id="full_score" name="data[full_score]" value="<?php echo $form_data['full_score']; ?>">
        </p>
        <p id="p_have_preposttest">
            <label for="have_preposttest">มี พรีเทส-โพสเทส </label>
            <?php echo form_dropdown('data[have_preposttest]', $have_preposttest_options, $form_data['have_preposttest']); ?>
        </p>

        <div id="send-time">
            <div id="p_start_time">
                <p>
                    <label for="start_time_d">วันที่เริ่มต้น </label>
                    <?php if ($can_edit_course_act_start_time) { ?>
                        <input type="text" id="start_time_d" name="data[start_time_d]" value="<?php echo $form_data['start_time_d']; ?>">
                    <?php } else { ?>
                        <input  type="text"  id="start_time_d"value="<?php echo $form_data['start_time_d']; ?>" disabled >
                        <input type="hidden"  name="data[start_time_d]" value="<?php echo $form_data['start_time_d']; ?>">
                    <?php } ?>
                </p>
                <p>
                    <label for="start_time_h">เวลาเริ่มต้น</label>
                    <?php if ($can_edit_course_act_start_time) { ?>
                        <input type="text" id="start_time_h" name="data[start_time_h]" value="<?php echo $form_data['start_time_h']; ?>">
                    <?php } else { ?>
                        <input type="text" id="start_time_h" value="<?php echo $form_data['start_time_h']; ?>" disabled>
                        <input type="hidden"  name="data[start_time_h]" value="<?php echo $form_data['start_time_h']; ?>">
                    <?php } ?>
                </p>
            </div>
            <?php if ($form_data['st_id'] === "5") {
                ?>

                <div id="p_end_time" style="display: none;">
                <?php } else {
                    ?>
                    <div id="p_end_time" style="display: none;">
                    <?php } ?>
                    <p>
                        <label for="end_time_d">วันที่สิ้นสุด</label>
                        <input type="text" id="end_time_d" name="data[end_time_d]" value="<?php echo $form_data['end_time_d']; ?>">
                    </p>
                    <p>
                        <label for="end_time_h">เวลาสิ้นสุด</label>
                        <input type="text" id="end_time_h" name="data[end_time_h]" value="<?php echo $form_data['end_time_h']; ?>">
                    </p>
                </div>

            </div>





            <input type="submit" value="บันทึก" id="btnSubmit" class="btn-submit" >

            <a href="<?php echo $cancel_url; ?>" class="btn-a">ยกเลิก</a>

    </form>

</div>

<div id="dialog" title="ค้นหาใบงาน" style="display: none;" >
    <div id="inner-dialog"></div>
</div>

<style>
    #iframe-resource-browser{
        width: 100%; 
        height: 450px;
        overflow: hidden;
    }
    #data{
        width: 350px;
        resize:vertical;
    }
    .textarea_to_text_input{
        width: 153px!important;
        height: 16px!important;
        resize: none!important;
    }
</style>