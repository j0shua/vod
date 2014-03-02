<h1><?php echo $form_title; ?></h1>

<div class="grid_7">
    <form id="main-form" class="normal-form" action="<?php echo $form_action; ?>" method="post" >
        <input id="c_id" type="hidden" name="data[c_id]" value="<?php echo $form_data['c_id']; ?>">
        <p>
            <label for="title" >ชื่อหลักสูตร </label>
            <input type="text" id="title" name="data[title]" value="<?php echo $form_data['title']; ?>">
        </p>
        <p>
            <label for="desc" >รายละเอียด </label>
            <textarea id="desc" name="data[desc]" ><?php echo $form_data['desc']; ?></textarea>
        </p>
        <p>
            <label for="publish">การนำไปใช้ </label>
            <?php echo form_dropdown('data[publish]', $publish_options, $form_data['publish'], 'id="publish"'); ?>
        </p>

        <p>
            <label for="degree_id">ชั้นเรียน</label>
            <?php echo form_dropdown('data[degree_id]', $degree_options, $form_data['degree_id'], 'id="degree_id"'); ?>
        </p>
        <p>
            <label for="la_id">กลุ่มสาระ</label>
            <?php echo form_dropdown('data[la_id]', $learning_area_options, $form_data['la_id'], 'id="la_id"'); ?>
        </p>
        <p>
            <label for="subj_id">วิชา</label>
            <?php echo form_dropdown('data[subj_id]', array(), '', 'id="subj_id"'); ?>
        </p>
    
        <p>
            <label for="start_time" >เริ่มหลักสูตร </label>
            <input type="text" id="start_time" name="data[start_time]" value="<?php echo $form_data['start_time_form_text']; ?>">
        </p>
        <p>
            <label for="end_time" >สิ้นสุดหลักสูตร </label>
            <input type="text" id="end_time" name="data[end_time]" value="<?php echo $form_data['end_time_form_text']; ?>">
        </p>
        <p>
            <label for="enroll_type_id" >นักเรียนเข้าเรียนได้เมื่อ </label>
            <?php echo form_dropdown('data[enroll_type_id]', $enroll_type_id_options, $form_data['enroll_type_id'], 'id="enroll_type_id"'); ?>
        </p>
        <p id="p_enroll_password" style="display: none;">
            <label for="enroll_password" >รหัสเข้าเรียนหลักสูตร   <span class="btn-help"  title="รหัสผ่านที่นักเรียนต้องกรอกเมื่อสมัครเข้าหลักสูตร เป็นตัวเลข 4 ตัว">?</span></label>
            <input type="text" id="enroll_password" name="data[enroll_password]" value="<?php echo $form_data['enroll_password']; ?>" maxlength="4">
        </p>
        <p>
            <label for="enroll_limit" >จำนวนรับ  <span class="btn-help"  title="จำนวนรับถ้าใส่ 0 คือไม่จำกัดจำนวนนักเรียน">?</span></label>
            <input type="text" id="enroll_limit" name="data[enroll_limit]" value="<?php echo $form_data['enroll_limit']; ?>">
        </p>

        <input type="submit" value="บันทึก" id="btnSubmit" class="btn-submit" >

        <a href="<?php echo $cancel_link; ?>" class="btn-a">ยกเลิก</a>

    </form>

</div>






