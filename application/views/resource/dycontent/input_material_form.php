<h1><?php echo $title; ?> 
    <?php echo anchor('http://www.youtube.com/embed/yQ6H-zToVng?rel=0&wmode=transparent&autoplay=1', "วิดีโอสอนการใช้งาน", 'class="youtube btn-a" target="_blank"'); ?>
    <?php echo anchor('http://www.codecogs.com/latex/eqneditor.php', "เครื่องมือช่วยสมการคณิต", 'class="youtube btn-a" target="_blank"'); ?>
    <?php echo anchor('http://www.tablesgenerator.com/latex_tables', "เครื่องมือช่วยทำตาราง", 'class="btn-a" target="_blank"'); ?>
</h1>
<script>
    $(".youtube").colorbox({iframe: true, innerWidth: 640, innerHeight: 390, opacity: 0.5});
</script>

<form class="normal-form" id="main-form" action="<?php echo $form_action; ?>" method="post" >


    <input id="resource_id" type="hidden" name="data[resource_id]" value="<?php echo $form_data['resource_id']; ?>">
    <input id="content_type_id" type="hidden" name="data[content_type_id]" value="<?php echo $content_type_id; ?>">
    <input id="render_type_id" type="hidden" name="data[render_type_id]" value="<?php echo $content_type_id; ?>">
    <input id="resource_id_parent" type="hidden" name="data[resource_id_parent]" value="0">

    <div class="grid_12 dycontent-question-title">
        <p>
            <label for="title">ชื่อเนื้อหา</label>

            <input type="text"  id="title" name="data[title]" value="<?php echo $form_data['title']; ?>">

        </p>
    </div>
    <div class="grid_4 dycontent-question-input">
        <p>
            <label for="desc">รายละเอียด</label>
            <textarea style="width: 260px; height: 150px; margin-top: 10px;margin-bottom: 10px;resize: vertical;" id="desc" name="data[desc]" ><?php echo $form_data['desc']; ?></textarea>
        </p>
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
            <?php echo form_dropdown('data[degree_id]', $degree_options, $form_data['degree_id'], 'id="degree_id"'); ?>
        </p>
        <p>
            <label for="la_id">กลุ่มสาระ</label>
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
        <p>
            <input type="submit" style="width: 90px; height: 36px; padding-top: 0px;" value="บันทึก" id="btnSubmit" class="btn-a" >

            <a href="<?php echo $cancel_link; ?>" class="btn-a">กลับ</a>
        </p>
    </div>
    <div class="grid_8">
        <h2 class="head1">ข้อมูลเนื้อหา</h2>


        <input type="button" value="ดูตัวอย่าง" id="btnpreview" class="btn-a" style="margin-bottom: 5px;">

        <div class="clearfix"></div>
        <textarea  class="content-markItUp" id="content_header" name="data[content_header]"><?php echo $form_data['data']['content_header']; ?></textarea>



    </div>

</form>
<div id="dialog" title="เอกสารตัวอย่าง" style="display: none;" >
    <div id="inner-dialog"></div>
</div>


<style>
    #iframe-image-browser{
        width: 100%; 
        height: 620px;
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
    /*    #content-form label{
            color: #0072C6; height: 18px; line-height: 18px; cursor: default;margin:0; text-align: left;padding-right: 10px;
        }
        #content-form input[type=text],#content-form input[type=password],#content-form select{
            width: 290px;
            height: 30px; 
            background: #fff;
            border: none; 
            outline: none; 
            margin:10px 0; 
            color: #212121; 
            font-size: 1em; 
            line-height: 30px; 
            padding-left: 10px;
            border: solid 1px #BABABA;
        }
        #content-form input[type=checkbox]{
            height: 30px; 
            width: 30px;
            margin:0px 0; 
    
        }
        #content-form input:disabled,#content-form select:disabled{
            background: #F2F2F2;
        }
        #content-form select{
            padding-top: 5px;
            padding-bottom: 5px;
            padding-right: 5px;
            width: 302px;
            height: 34px; 
        }*/

</style>




