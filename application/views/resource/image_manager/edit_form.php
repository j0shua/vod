<h1><?php echo $title; ?></h1>
<div class="grid_12">
    <form class="normal-form" id="main-form" action="<?php echo $form_action; ?>" method="post" >
        <input id="resource_id" type="hidden" name="resource_id" value="<?php echo $form_data['resource_id']; ?>">
        <p>
            <label for="title" >ชื่อ</label>
            <input type="text" id="title" name="data[title]" value="<?php echo $form_data['title']; ?>"  style="width: 465px;">

        </p>
        <p>
            <label for="desc" >รายละเอียด</label>
            <textarea id="desc" name="data[desc]" style="width: 465px;height: 150px;"><?php echo $form_data['desc']; ?></textarea>

        </p>
        <p>
            <label for="tags" >ป้ายกำกับ <span class="btn-help"  title="เป็นคำค้นช่วยหาเอกสารภายหลัง<br>เช่น การเคลื่อนที่, ฟิสิกส์">?</span></label>
            <input type="text" id="tags" name="data[tags]" value="<?php echo $form_data['tags']; ?>">
        </p>
        <p>
            <label for="publish" >การนำไปใช้ </label>
            <?php echo form_dropdown('data[publish]', $publish_options, $form_data['publish'], 'id="publish"'); ?>
        </p>
        <p>
            <label for="privacy" >สิทธิการใช้ </label>
            <?php echo form_dropdown('data[privacy]', $privacy_options, $form_data['privacy'], 'id="privacy"'); ?>

        </p>

        <p>
            <label for="degree_id" >ชั้นเรียน</label>
            <?php echo form_dropdown('data[degree_id]', $degree_options, $form_data['degree_id'], 'id="degree_id"'); ?>
        </p>
        <p>
            <label for="la_id" >กลุ่มสาระ</label>
            <?php echo form_dropdown('data[la_id]', $learning_area_options, $form_data['la_id'], 'id="la_id"'); ?>
        </p>
        <p>
            <label for="subj_id">วิชา <span class="btn-help"  title="กด เมนูหลัก > จัดการ  กลุ่มสาระ/วิชา/บท <br/> เพื่อเพิ่มวิชา">?</span></label>
            <?php echo form_dropdown('data[subj_id]', array(), '', 'id="subj_id"'); ?>
        </p>
        <p>
            <label for="chapter_id">บทเรียน <span class="btn-help"  title="กด เมนูหลัก > จัดการ  กลุ่มสาระ/วิชา/บท <br/> เพื่อเพิ่มบทเรียน">?</span></label>
            <?php echo form_dropdown('data[chapter_id]', array(), '', 'id="chapter_id"'); ?>
        </p>
        <p>
            <label for="sub_chapter_title">ตอน</label>
            <input type="text" id="sub_chapter_title" name="data[sub_chapter_title]" value="<?php echo $form_data['sub_chapter_title']; ?>"  >
        </p>

        <input type="submit" value="บันทึก" id="btnSubmit" class="btn-submit" >

        <a href="<?php echo $cancel_link; ?>" class="btn-a">ยกเลิก</a>

    </form>

</div>