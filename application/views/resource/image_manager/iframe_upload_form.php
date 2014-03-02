<h1><?php echo $title; ?></h1>

<div class="grid_9">

    <form id="main-form" class="normal-form " action="<?php echo $form_action; ?>" method="post" >
        <div class="form-input clearfix">
            <div class="clearfix">
                <label for="name" style="float: left;">เลือกไฟล์</label>
                <input type="hidden" name="resume_file" id="resume_file" value=""/>
                <div id="resource_upload">		
                    <noscript>			
                    <span>Please enable JavaScript to use file uploader.</span>
                    <!-- or put a simple form for upload here -->
                    </noscript>         
                </div>
            </div>

            <p>
                <label for="title">ชื่อ</label><input type="text" required id="title" name="data[title]" value="" style="width: 465px;">
            </p>
            <p>
                <label for="desc">รายละเอียด</label><textarea id="desc" name="data[desc]" style="width: 465px;"></textarea>

            </p>
            <p>
                <label for="tags">ป้ายกำกับ <span class="btn-help"  title="เป็นคำค้นช่วยหาเอกสารภายหลัง<br>เช่น การเคลื่อนที่, ฟิสิกส์">?</span></label><input type="text" id="tags" name="data[tags]" value="" style="width: 465px;" >
            </p>
            <p>
                <label for="publish">การนำไปใช้</label>
                <?php echo form_dropdown('data[publish]', $publish_options, 1, 'id="publish"'); ?>
            </p>
            <p>
                <label for="privacy">สิทธิการใช้</label><?php echo form_dropdown('data[privacy]', $privacy_options, 1, 'id="privacy"'); ?>

            </p>
            <p>
                <label for="degree_id">ชั้นเรียน</label><?php echo form_dropdown('data[degree_id]', $degree_options, '', 'id="degree_id"'); ?>
            </p>
            <p>
                <label for="la_id">กลุ่มสาระ</label><?php echo form_dropdown('data[la_id]', $learning_area_options, '', 'id="la_id"'); ?>
            </p>
            <p>
                <label for="subj_id">วิชา <span class="btn-help"  title="<a target='_blank' href='<?php echo site_url('resource/subject_manager/'); ?>'>คลิ๊กที่นี่</a> เพื่อจัดการวิชาและบทเรียน">?</span></label>
                <?php echo form_dropdown('data[subj_id]', array(), '', 'id="subj_id"'); ?>
            </p>
            <p>
                <label for="chapter_id">บทเรียน <span class="btn-help"  title="<a target='_blank' href='<?php echo site_url('resource/subject_manager/'); ?>'>คลิ๊กที่นี่</a> เพื่อจัดการวิชาและบทเรียน">?</span></label>
                <?php echo form_dropdown('data[chapter_id]', array(), '', 'id="chapter_id"'); ?>
            </p>
            <p>
                <label for="sub_chapter_title">ตอน</label>
                <input type="text" id="sub_chapter_title" name="data[sub_chapter_title]" value=""  >
            </p>
        </div>

        <input type="button" value="บันทึก" id="btnSubmit" class="btn-a" >

        <a href="<?php echo $cancel_link; ?>" class="btn-a">ยกเลิก</a>
    </form>

</div>
<style>
    .normal-form label{
        width: 100px;
        display: block;
        float: left;
        text-align: right;
        padding-right: 5px;
        font-weight: bold;
        color: #000;
    }
    .normal-form p{
        margin-bottom: 10px;
    }
    .normal-form .form-input {
        margin-bottom: 10px;
    }

</style>