<h1 class="head1"><?php echo $form_title; ?></h1>
<form id="content-form" action="<?php echo $form_action; ?>" method="post" >
    <div class="grid_4">

        <input id="resource_id" type="hidden" name="resource_id" value="<?php echo $resource_data['resource_id']; ?>">
        <input id="resource_type_id_combined" type="hidden" name="resource_type_id_combined" value="<?php echo $resource_type_id_combined; ?>">
        <input id="content_type_id_combined" type="hidden" name="content_type_id_combined" value="<?php echo $content_type_id_combined; ?>">

        <p>
            <label for="title" class="grid_2">ชื่อ <span class="important">*</span><span class="less-important">(required)</span></label>
            <input type="text" id="title" name="data[title]" value="<?php echo $resource_data['title']; ?>">

        </p>
        <p>
            <label for="content_type_id_combined" class="grid_2">ประเภทข้อมูล </label>
            <?php echo form_dropdown('data[content_type_id_combined]', array(1 => 'latex'), 1, 'id="content_type_id_combined"'); ?>
        </p>
        <p>
            <label for="desc" class="grid_2">รายละเอียด <span class="important">*</span><span class="less-important">(required)</span></label>
            <textarea style="width: 300px; margin-top: 10px;margin-bottom: 10px;" id="desc" name="data[desc]" ><?php echo $resource_data['desc']; ?></textarea>

        </p>
        <p>
            <label for="tags" class="grid_2">Tags <span class="important">*</span><span class="less-important">(required)</span></label>
            <input type="text" id="tags" name="data[tags]" value="<?php echo $resource_data['tags']; ?>">
        </p>
        <p>
            <label for="publish" class="grid_2">การนำไปใช้  <span class="important">*</span><span class="less-important">(required)</span></label>
            <?php echo form_dropdown('data[publish]', $publish_options, $resource_data['publish'], 'id="publish"'); ?>
        </p>
        <p>
            <label for="privacy" class="grid_2">การการนำไปใช้  <span class="important">*</span><span class="less-important">(required)</span></label>
            <?php echo form_dropdown('data[privacy]', $privacy_options, $resource_data['privacy'], 'id="privacy"'); ?>

        </p>
        <p>
            <label for="category_id" class="grid_2">หมวดหมู่ </label>
            <?php echo form_dropdown('data[category_id]', $category_options, $resource_data['category_id'], 'id="category_id"'); ?>
        </p>
        <p>
            <label for="la_id" class="grid_2">กลุ่มสาระ <span class="important">*</span><span class="less-important">(required)</span></label>
            <?php echo form_dropdown('data[la_id]', $category_options, '', 'id="la_id"'); ?>
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
        <?php if ($make_money) { ?>
            <p>
                <label for="my_student_free" class="grid_2">ค่าบริการลูกศิษย์ที่อยู่ในหลักสูตรการเรียน </label>
                <?php echo form_dropdown('data[my_student_free]', array(0 => 'เก็บค่าบริการ', 1 => 'ไม่เก็บค่าบริการ'), 1, 'id="my_student_free"'); ?>
            </p>
        <?php } ?>
        <input type="button" value="บันทึก" id="btnSubmit" class="btn-a" >

        <a href="<?php echo $cancel_link; ?>" class="btn-a">กลับ</a>
    </div>
    <div class="grid_8">
        <h2>คำชี้แจง</h2>

        <p>
            <textarea style="height: 110px;"id="explanation" name="data[explanation]"><?php echo $resource_data['explanation']; ?></textarea>

        </p>
        <input type="button" value="ดูตัวอย่าง" id="btnpreview" class="btn-submit" >
        <input type="button" value="เพิ่มโจทย์ เนื้อหา" id="btnaddresource" class="btn-submit" >

        <div class="clearfix"></div>
        <div>
            <span class="list_head_30">เลขที่</span>
            <span class="list_head_30">บรรทัดว่าง</span>
        </div>
        <ul id="sortable" style="margin-left: 0px;list-style-type: none;">
            <?php
            foreach ($render_li as $li) {
                echo $li;
            }
            ?>

        </ul>
    </div>

</form>
<div id="dialog" title="เอกสารตัวอย่าง" style="display: none;" >
    <div id="inner-dialog"></div>
</div>
<div id="dialog-add-resource" title="เพิ่มเอกสาร" style="display: none;" >
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
    #sortable li{
        padding:5px;
        margin-bottom: 5px;
    }
    #sortable li input[type=text],#sortable li select{
        margin: 0px;
    }

    .list_head_20{
        display: inline-block;
        width: 20%;
    }
    .list_head_30{
        display: inline-block;
        width: 30%;
    }
    .list_head_50{
        display: inline-block;
        width: 50%;
    }
    /* CSS for sortable  */
    #iframe-image-browser{
        width: 100%; 
        height: 450px;
        overflow: hidden;
    }
    #content-form label{
        color: #0072C6; height: 18px; line-height: 18px; cursor: default;margin:0; text-align: left;padding-right: 10px;width: 200px;
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
    }
    /*    #content-form textarea {margin:10px 0; width: 220px; min-width: 220px; max-width: 688px; height: 80px; line-height: 18px; padding-top: 8px;padding-left: 10px;}*/
    #content-form input:focus, #content-form textarea:focus ,#content-form textarea:focus{border: solid 1px #5C5C5C;}
    #content-form .btn-submit,a.btn-submit{
        margin:10px 0; 
        font-weight: bold;
        line-height: 30px; 
        background-color: #0072C6;
        color: #ffffff;
        font-size: 1em; 
        float: left;
        min-width: 88px;
        max-width: 600px;
        height: 30px;
        border: none;
        margin-right: 10px;
        text-align: center;
        padding: 0px 5px ;
    }
    #content-form input.btn-submit{
        min-width: 98px;
    }
    #content-form .btn-submit:hover,a.btn-submit:hover{
        background-color: #3D94D4;
    }
    #content-form .btn-submit:disabled{
        display: none;
        background-color: #DBEAF9;
        color: #447599;

    }
    #content-form .btn-a{
        text-align: center;
        margin:10px 0; 
        font-weight: bold;
        line-height: 30px; 
        background-color: #BFBDBD;
        color: #212121;
        font-size: 1em; 
        float: left;
        width: 88px;
        height: 30px;
        border: none;
        margin-right: 10px;
    }
    #content-form .btn-a:hover{
        background-color: #D6D6D6;
    }

</style>




