<input value="ลบโจทย์ข้อนี้" type="button" class="btn-delete-me btn-a" style="margin-bottom: 10px;margin-left: 4px;" >
<input id="content-type-id-<?php echo $tab_subfix; ?>"  type="hidden" class="content-question preview-me-<?php echo $tab_subfix; ?>" name="data[content_questions][<?php echo $tab_subfix; ?>][content_type_id]" value="<?php echo $content_type_id; ?>">
<input type="hidden" class="tab-subfix-val" value="<?php echo $tab_subfix; ?>">

<h2 class="head1">คำถาม</h2>
<textarea style="height: 100px;resize: none;" id="question-<?php echo $tab_subfix; ?>" name="data[content_questions][<?php echo $tab_subfix; ?>][question]" class="content-question content-markItUp preview-me-<?php echo $tab_subfix; ?>" id><?php echo $question; ?></textarea>

<h2 class="head1">คำตอบที่ถูกต้อง</h2>
<div>
    <?php foreach ($true_answers as $k => $v) { ?>
        <p>
            <label style="width: 80px;margin-top: 8px;">คู่ที่ <?php echo $k + 1; ?></label>
            <input style="width: 310px; border: solid 2px #79BBFF;margin-top: 4px;" type="text" id="choices-<?php echo $k.'-'.$tab_subfix; ?>" class="content-question preview-me-<?php echo $tab_subfix; ?>" value="<?php echo $choices[$k]; ?>" name="data[content_questions][<?php echo $tab_subfix; ?>][choices][]" >
            <input style="width: 310px; border: solid 2px #79BBFF;margin-top: 4px;" type="text" id="true-answers-1-<?php echo $tab_subfix; ?>" class="content-question preview-me-<?php echo $tab_subfix; ?>" value="<?php echo $v; ?>" name="data[content_questions][<?php echo $tab_subfix; ?>][true_answers][]" >
        </p>
    <?php } ?>
</div>
<h2 class="head1">เรียงคำตอบ</h2>
<?php
if ($answer_sort == '') {
    $answer_sort = range(1, count($true_answers));
    shuffle($answer_sort);
    $answer_sort = implode(',', $answer_sort);
}
?>
<div class="clearfix">
    <p style="float: left;display: block; margin-left: 5px;">
        <input   style="width: 740px; border: solid 2px #79BBFF;margin-top: 4px;" type="text" id="answers-sort-<?php echo $tab_subfix; ?>" class="content-question preview-me-<?php echo $tab_subfix; ?>" value="<?php echo $answer_sort; ?>" name="data[content_questions][<?php echo $tab_subfix; ?>][answers_sort]" >
    </p>
</div>
<h2 class="head1">เฉลย</h2>
<div>
    <textarea style="height: 100px;resize: none;" id="solve-<?php echo $tab_subfix; ?>" name="data[content_questions][<?php echo $tab_subfix; ?>][solve_answer]" class="content-question content-markItUp preview-me-<?php echo $tab_subfix; ?>"><?php echo $solve_answer; ?></textarea>
</div>
