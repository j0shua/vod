<h1><?php echo $title; ?></h1>

<div class="grid_9">
    <form id="main-form" class="normal-form" action="<?php echo $form_action; ?>" method="post" >
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
            <label for="tags">ป้ายกำกับ</label><input type="text" id="tags" name="data[tags]" value="" style="width: 465px;" >
        </p>
        <p>
            <label for="publish">เปิดใช้งาน</label>
            <?php echo form_dropdown('data[publish]', $publish_options, 1, 'id="publish"'); ?>
        </p>
        <p>
            <label for="privacy">สิทธิการใช้งาน</label><?php echo form_dropdown('data[privacy]', $privacy_options, 1, 'id="privacy"'); ?>

        </p>
        <p>
            <label for="degree_id">ชั้นเรียน</label><?php echo form_dropdown('data[degree_id]', $degree_options, '', 'id="degree_id"'); ?>
        </p>
        <p>
            <label for="la_id">กลุ่มสาระ</label><?php echo form_dropdown('data[la_id]', $learning_area_options, '', 'id="la_id"'); ?>
        </p>
        <p>
            <label for="subj_id">วิชา</label><?php echo form_dropdown('data[subj_id]', array(), '', 'id="subj_id"'); ?>
        </p>
        <p>
            <label for="chapter">บทเรียน</label><input type="text" id="chapter" name="data[chapter]" value="" style="width: 465px;" >
        </p>


        <input type="button" value="บันทึก" id="btnSubmit" class="btn-a" >

        <a href="<?php echo $cancel_link; ?>" class="btn-a">ยกเลิก</a>
    </form>

</div>