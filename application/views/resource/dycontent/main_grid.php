<?php echo $main_side_menu; ?>
<div class="grid_10">
    <h2 class="head1"><?php echo $title; ?></h2>
    <div class="clearfix">
        <?php
        foreach ($grid_menu as $btn) {
            echo anchor($btn['url'], $btn['title'], 'class="btn-a" ' . $btn['extra']);
        }
        ?>
    </div>
    <fieldset class="grid-fieldset"><legend>กรอง</legend>
        <?php echo form_dropdown('content_type_id', $content_type_options, '', 'id="content_type_id"'); ?>
        <?php echo form_dropdown('qtype', $qtype_options, 'title', 'id="qtype"'); ?>
        <input id="query" type="text" name="query">
        <input class="btn-a-small" type="button" id="btn_search" value="กรอง"> 
    </fieldset>
    <fieldset class="grid-fieldset"><legend>กระทำกับข้อมูล</legend>
        <?php echo form_dropdown('dd_act_grid', $command_to_resource_options, '', 'id="dd_act_grid"') ?>

        <input  class="btn-a-small"  type="button" id="btn_act_grid" value="กระทำกับที่เลือก"> 
    </fieldset>
    <div class="flexigrid_wrap">
        <table id="main-table" style="display: none"></table>
    </div>

</div>

<div id="dialog-add-question" title="เพิ่มโจทย์" style="display: none;" >
    <div id="inner-dialog-add-question">
        <form id="add_question_form" action="<?php echo $add_question_url; ?>" method="POST">
            <fieldset>
                <label for="name">ประเภทโจทย์</label>
                <select id="dialog-content-type-id" name="content_type_id"  class="text ui-widget-content ui-corner-all" >
                    <option value="2">โจทย์ตัวเลือก 1 คำตอบ</option>
                    <option value="3">โจทย์ตัวเลือกหลายคำตอบ</option>
                    <option value="4">โจทย์เติมคำตอบ</option>
                    <!--                    <option value="5">โจทย์เติมหลายคำตอบ</option>-->
                    <!--                    <option value="6">โจทย์จับคู่</option>-->
                </select>
                <div id="dialog-choice-num">
                    <label for="dialog-choice-num">จำนวนตัวเลือก</label>
                    <select id="dialog-select-choice-num" name="choice_num"  class="text ui-widget-content ui-corner-all" >
                        <?php
                        foreach (range(2, 20) as $v) {
                            echo '  <option value="' . $v . '">' . $v . '</option>';
                        }
                        ?>
                    </select>

                </div>
                <div id="dialog-answer-num">
                    <label for="dialog-answer-num">จำนวนคำตอบ</label>
                    <select id="dialog-select-answer-num" name="answer_num"  class="text ui-widget-content ui-corner-all" >
                        <?php
                        foreach (range(2, 20) as $v) {
                            echo '  <option value="' . $v . '">' . $v . '</option>';
                        }
                        ?>
                    </select>

                </div>
                <div id="dialog-pair-num">
                    <label for="dialog-pair-num">จำนวนคู่</label>
                    <select id="dialog-select-pair-num" name="pair_num"  class="text ui-widget-content ui-corner-all" >
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
</style>


