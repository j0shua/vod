<h1><?php echo $title; ?></h1>
<form class="normal-form" id="main-form" action="<?php echo $form_action; ?>" method="post" >
    <input id="resource_id" type="hidden" name="data[resource_id]" value="<?php echo $form_data['resource_id']; ?>">
    <input id="render_type_id" type="hidden" name="data[render_type_id]" value="<?php echo $form_data['render_type_id']; ?>">
    <div class="grid_12 dycontent-question-title">
        <p>
            <label for="title">ชื่อใบงาน</label>

            <input class="preview_effect" type="text"  id="title" name="data[title]" value="<?php echo $form_data['title']; ?>">

        </p>
    </div>
    <div class="grid_4 dycontent-question-input">
        <p>
            <label for="desc">รายละเอียด</label>
            <textarea style="width: 260px; height: 150px; margin-top: 10px;margin-bottom: 10px;resize: vertical;" id="desc" name="data[desc]" ><?php echo $form_data['desc']; ?></textarea>
        </p>
        <?php if ($make_money) { ?>
            <p>
                <label for="my_student_free" class="grid_2">ค่าบริการลูกศิษย์</label>
                <?php echo form_dropdown('data[my_student_free]', array(0 => 'เก็บค่าบริการ', 1 => 'ไม่เก็บค่าบริการ'), 1, 'id="my_student_free"'); ?>
            </p>
        <?php } ?>
        <p>
            <label for="tags">ป้ายกำกับ <span class="btn-help"  title="เป็นคำค้นช่วยหาเอกสารภายหลัง<br>เช่น การเคลื่อนที่, ฟิสิกส์">?</span></label>
            <input type="text" id="tags" name="data[tags]" value="<?php echo $form_data['tags']; ?>">
        </p>
        <p>
            <label for="publish">การนำไปใช้ </label>
            <?php echo form_dropdown('data[publish]', $publish_options, $form_data['publish'], 'id="publish"'); ?>
        </p>
        <p>
            <label for="privacy">สิทธิการใช้ </label>
            <?php echo form_dropdown('data[privacy]', $privacy_options, $form_data['privacy'], 'id="privacy"'); ?>

        </p>
        <p>
            <label for="degree_id">ชั้นเรียน</label>
            <?php echo form_dropdown('data[degree_id]', $degree_options,$form_data['degree_id'], 'id="degree_id"'); ?>
        </p>
        <p>
            <label for="la_id">กลุ่มสาระ</label>
            <?php echo form_dropdown('data[la_id]', $learning_area_options, $form_data['la_id'], 'id="la_id"'); ?>
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
            <input type="text" id="sub_chapter_title" name="data[sub_chapter_title]" value="<?php echo $form_data['sub_chapter_title']; ?>"  >
        </p>
        <p>
            <input type="submit" style="width: 90px; height: 36px; padding-top: 0px;" value="บันทึก" id="btnSubmit" class="btn-a" >

            <a href="<?php echo $cancel_link; ?>" class="btn-a">กลับ</a>
        </p>
    </div>
    <div class="grid_8">
        <h2 class="head1">
            คำชี้แจง</h2>

        <p>
            <textarea  class="preview_effect"  style="height: 110px;"id="explanation" name="data[explanation]"><?php echo $form_data['explanation']; ?></textarea>

        </p>
        <input type="button" value="ดูตัวอย่าง" id="btn_preview" class="btn-a" >
        <input type="button" value="เพิ่มโจทย์/เนื้อหา" id="btn_search_resource" class="btn-a" >
        <input type="button" value="เพิ่มตอน" id="btn_add_section" class="btn-a" >

        <div class="clearfix"></div>

        <ul id="sortable" style="margin-left: 0px;list-style-type: none;">
            <?php
            foreach ($render_li as $li) {
                echo $li;
            }
            ?>

        </ul>


        <div class="clearfix"></div>
        <div id="pass_score">
            <?php echo $pass_score; ?>

        </div>
        <span>คะแนนรวม <input id="total_score_pq" type="text" value="50" style="width: 30px;"> คะแนน</span>
    </div>

</form>
<div id="dialog" title="เอกสารตัวอย่าง" style="display: none;" >
    <div id="inner-dialog"></div>
</div>

<div id="dialog-add-resource" title="เพ" style="display: none;" >
    <div id="inner-dialog-add-resource">
        <from id="dialog-form">
            <p>
                <label for="dialog_resource_type_id" class="grid_2">ประเภทสื่อ </label>
                <?php echo form_dropdown('dialog_resource_type_id', array(0 => 'ตอน', 1 => 'เนื้อหา', 2 => 'โจทย์'), 2, 'id="dialog_resource_type_id"'); ?>
            </p>
            <p >
                <input type="text" id="dialog_resource_id" name="dialog_resource_id" value="">
            </p>
        </from>
    </div>
</div>

<style>
    /* CSS for sortable  */
    #sortable{
        margin-top: 10px;
    }
    #sortable li{
        margin-left: 0px;
        padding:5px;
        margin-bottom: 4px;
        box-shadow:0px 2px 2px #B0B0B0
    }
    #sortable li input[type=text],#sortable li select{
        margin: 0px;
    }


    /* CSS for sortable  */
    #iframe-resource-browser,#iframe-image-browser{
        width: 100%; 
        height: 630px;
        overflow: hidden;
    }


    #main-form .dycontent-question-title{
        margin-bottom: 20px;
    }
    #main-form .dycontent-question-title label{
        font-size: 18px;
        width: 100px;
        float: left;
        text-align: right;
        margin-top: 5px;
        margin-right: 10px;
    }
    #main-form .dycontent-question-title input[type="text"]{
        font-size: 18px;
        width: 1050px;
        float: left;
        margin-top: 0px;

    }
    #main-form .dycontent-question-input label{
        font-size: 14px;
        width: 100px;
        float: left;
        text-align: right;
        margin-top: 5px;
        margin-right: 10px;
    }
    #main-form .dycontent-question-input select{
        width: 267px;
        padding: 5px;
        line-height: 35px;
    }
    #main-form .dycontent-question-input input{
        width: 255px;
        padding: 2px 5px;
        height: 25px;
    }
</style>




