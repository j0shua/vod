<h1><?php echo $title; ?></h1>
<form class="normal-form" id="main-form" action="<?php echo $form_action; ?>" method="post" >
    <input id="resource_id" type="hidden" name="data[resource_id]" value="<?php echo $form_data['resource_id']; ?>">
    <input id="resource_id_parent" type="hidden" name="data[resource_id_parent]" value="<?php echo $form_data['resource_id_parent']; ?>">
    <input id="render_type_id" type="hidden" name="data[render_type_id]" value="<?php echo $form_data['render_type_id']; ?>">

    <div class="grid_12 dycontent-question-title">
        <p>
            <label for="title">ชื่อโจทย์</label>

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
            <label for="subj_id">วิชา</label>
            <?php echo form_dropdown('data[subj_id]', array(), '', 'id="subj_id"'); ?>
        </p>
        <p>
            <label for="chapter_title">บทเรียน</label>
            <input type="text" id="chapter_title" name="data[chapter_title]" value="<?php echo $form_data['chapter_title']; ?>"  >
        </p>
        <p>
            <input type="submit" style="width: 90px; height: 36px; padding-top: 0px;" value="บันทึก" id="btnSubmit" class="btn-a" >

            <a href="<?php echo $cancel_link; ?>" class="btn-a">กลับ</a>
        </p>
    </div>
    <div class="grid_8">


        <div class="clearfix"></div>
        <input type="button" value="ดูตัวอย่าง" id="btnpreview" class="btn-a" >
        <input type="button" value="เพิ่มโจทย์ต่อเนื่อง" id="btnaddquestion" class="btn-a" >
        <div id="debug-text"></div>
        <div class="clearfix"></div>
        <div id="content_header_wrapper">
            <h2 class="head1" style="margin-bottom: 0px;margin-top: 5px;">โจทย์นำ</h2>
            <textarea class="content-markItUp" style="height:100px;resize: none;" id="content_header" name="data[content_header]"><?php echo $form_data['data']['content_header']; ?></textarea>
        </div>

        <!--    </div>
            <div class="grid_12">-->
        <div>
            <div id="tabs" style="margin-top: 5px;">
                <ul id="tabs-link">
                    <?php
                    if (isset($content_questions)) {
                        foreach ($content_questions as $k => $v) {
                            $q_num = $k + 1;
                            echo '<li>
                    <a href="#tab-' . $v['tab_subfix'] . '">โจทย์ข้อ ' . $q_num . '</a>
                </li>';
                        }
                    }
                    ?>
                </ul>
                <?php
                if (isset($content_questions)) {
                    foreach ($content_questions as $v) {
                        echo ' <div id="tab-' . $v['tab_subfix'] . '">
                ' . $v['tab_content'] . '       </div>';
                    }
                }
                ?>
            </div>
        </div>
    </div>

</form>
<div id="dialog" title="เอกสารตัวอย่าง" style="display: none;" >
    <div id="inner-dialog"></div>
</div>

<div id="dialog-add-question" title="เพิ่มโจทย์" style="display: none;" >
    <div id="inner-dialog-add-question">
        <form>
            <fieldset>
                <label for="name">ประเภทโจทย์</label>
                <select id="dialog-content-type-id" name=""  class="text ui-widget-content ui-corner-all" >
                    <option value="2">โจทย์ตัวเลือก 1 คำตอบ</option>
                    <option value="3">โจทย์ตัวเลือกหลายคำตอบ</option>
                    <option value="4">โจทย์เติมคำตอบ</option>
                    <!--                    <option value="5">โจทย์เติมหลายคำตอบ</option>-->
                    <!--                    <option value="6">โจทย์จับคู่</option>-->
                </select>
                <div id="dialog-choice-num">
                    <label for="dialog-choice-num">จำนวนตัวเลือก</label>
                    <select id="dialog-select-choice-num" name=""  class="text ui-widget-content ui-corner-all" >
                        <?php
                        foreach (range(2, 20) as $v) {
                            echo '  <option value="' . $v . '">' . $v . '</option>';
                        }
                        ?>
                    </select>

                </div>
                <div id="dialog-answer-num">
                    <label for="dialog-answer-num">จำนวนคำตอบ</label>
                    <select id="dialog-select-answer-num" name=""  class="text ui-widget-content ui-corner-all" >
                        <?php
                        foreach (range(2, 20) as $v) {
                            echo '  <option value="' . $v . '">' . $v . '</option>';
                        }
                        ?>
                    </select>

                </div>
                <div id="dialog-pair-num">
                    <label for="dialog-pair-num">จำนวนคู่</label>
                    <select id="dialog-select-pair-num" name=""  class="text ui-widget-content ui-corner-all" >
                        <?php
                        foreach (range(2, 50) as $v) {
                            echo '  <option value="' . $v . '">' . $v . '</option>';
                        }
                        ?>
                    </select>

                </div>
            </fieldset>
        </form>

    </div>
</div>
<style>
    #dialog-add-question{display: none;}
    #dialog-add-question label, #dialog-add-question input , #dialog-add-question select{ display:block; }
    #dialog-add-question input.text { margin-bottom:12px; width:95%; padding: .4em; }
    #dialog-add-question fieldset { padding:0; border:0; margin-top:25px; }
    #dialog-add-question h1 { font-size: 1.2em; margin: .6em 0; }
    #dialog-add-question select { margin-bottom:12px; width:99%; padding: .4em;border-radius: 0px;  }

    #iframe-image-browser{
        width: 100%; 
        height: 620px;
        overflow: hidden;
    }

    /*    #main-form label{
            color: #0072C6; height: 18px; line-height: 18px; cursor: default;margin:0; text-align: left;padding-right: 10px;
        }*/

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
    .tabs_wrapper{
        height: 700px;
        overflow-y: auto;
        overflow-x: hidden;
        margin-top: 5px;
    }
    /*    
        #main-form input[type=text],#main-form input[type=password],#main-form select{
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
        #main-form input[type=checkbox]{
            height: 30px; 
            width: 30px;
            margin:0px 0; 
    
        }
        #main-form input:disabled,#main-form select:disabled{
            background: #F2F2F2;
        }
        #main-form select{
            padding-top: 5px;
            padding-bottom: 5px;
            padding-right: 5px;
            width: 302px;
            height: 34px; 
        }*/

    .ui-tabs {
        width: 770px;
    }

    .ui-tabs .ui-tabs-panel{
        padding: 1em 0;
    }
    .ui-tabs{
        padding: 0 0;
    }
    .markItUpContainer{
        border: solid 1px #E6E6E6;

    }
</style>




