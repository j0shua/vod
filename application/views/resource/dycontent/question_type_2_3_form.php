<input value="ลบโจทย์ข้อนี้" type="button" class="btn-delete-me btn-a" style="margin-bottom: 10px;margin-left: 4px;" >

<input id="content-type-id-<?php echo $tab_subfix; ?>"  type="hidden" class="content-question content-question preview-me-<?php echo $tab_subfix; ?>" name="data[content_questions][<?php echo $tab_subfix; ?>][content_type_id]" value="<?php echo $content_type_id; ?>">
<input type="hidden" class="tab-subfix-val" value="<?php echo $tab_subfix; ?>">
<h2 class="head1">คำถาม</h2>
<textarea style="height: 100px;resize: none;" id="question-<?php echo $tab_subfix; ?>" name="data[content_questions][<?php echo $tab_subfix; ?>][question]" class="content-question content-markItUp preview-me-<?php echo $tab_subfix; ?>" id><?php echo $question; ?></textarea>
<h2 class="head1">คำตอบที่ถูกต้อง</h2>
<div class="clearfix" style="margin-top: 10px;">
    <?php foreach ($choices as $k => $v) { ?>
        <p style="float: left;display: block; border: solid 1px #79BBFF;margin-left: 5px;padding-top: 4px;width:120px;">
            <?php
            $sl = '';
            if (in_array($k, $true_answers)) {
                $sl = 'checked="checked"';
            }
            switch ($content_type_id) {
                case 2:
                    $type = 'radio';
                    break;
                case 3:
                    $type = 'checkbox';
                    break;
                default:
                    $type = 'radio';
                    break;
            }
            ?>
            <input <?php echo $sl; ?> class="content-question preview-me-<?php echo $tab_subfix; ?>" id="true_answers_<?php echo $k + 1; ?>_<?php echo $tab_subfix; ?>" type="<?php echo $type; ?>" name="data[content_questions][<?php echo $tab_subfix; ?>][true_answers][]" value="<?php echo $k; ?>" />
            <label style="text-align: left;width: auto;" for="true_answers_<?php echo $k + 1; ?>_<?php echo $tab_subfix; ?>" >ตัวเลือกที่ <?php echo $k + 1; ?></label>
        </p>
    <?php } ?>
</div>
<?php foreach ($choices as $k => $v) { ?>
    <h2 class="head1">ตัวเลือกที่ <?php echo $k + 1; ?></h2>
    <div class>
        <textarea style="height: 100px;resize: none;" id="choice-<?php echo $k.'-'.$tab_subfix; ?>" name="data[content_questions][<?php echo $tab_subfix; ?>][choices][]" class="content-question content-markItUp preview-me-<?php echo $tab_subfix; ?>"><?php echo $v; ?></textarea>
    </div>
<?php } ?>
<h2 class="head1">เฉลย</h2>
<div>
    <textarea style="height: 100px;resize: none;" id="solve-<?php echo $tab_subfix; ?>" name="data[content_questions][<?php echo $tab_subfix; ?>][solve_answer]" class="content-question content-markItUp preview-me-<?php echo $tab_subfix; ?>"><?php echo $solve_answer; ?></textarea>
</div>
<div class="empty-tab" style="height: 100px;"></div>
<style>
    #tabs .head1{
        margin-bottom: 0px;
    }
</style>
