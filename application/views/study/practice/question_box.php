<h2>โจทย์ข้อที่ <?php echo $sheet_q_index + 1; ?></h2>
<?php
//if ($video_guide) {
if (FALSE) {
    echo anchor($video_guide, '▶ วิดีโอแนะแนว', 'class="btn-a btn_video_guide" target="_blank"');
}
?>
<form id="question_box_form">
    <div id="question_image_box">
        <?php
        foreach ($files_basename as $img) {
            echo '<img src="' . base_url($render_file_base_uri . $img) . '" >';
        }
        ?>
    </div>

    <div id="question_answer_box">
        <?php
        switch ($content_question['content_type_id']) {
            case 2:
                $select_answer = -1;
                if (count($send_answer) > 0) {
                    $select_answer = $send_answer[0];
                }
                foreach (range(0, (count($content_question['choices']) - 1)) as $v) {
                    $id = 'answer_selector_' . $v . '_' . rand(111111, 999999);
                    $checked = 'checked="checked"';
                    if ($select_answer != $v) {
                        $checked = '';
                    }
                    echo '<p><input class="choose_answer" type="radio" id="' . $id . '"  name="send_answer[' . $sheet_q_index . '][answer][]" value="' . $v . '" ' . $checked . ' /><label for="' . $id . '">ข้อ ' . ($v + 1) . '</label></p>';
                }
                break;
            case 3:

                foreach (range(0, (count($content_question['choices']) - 1)) as $v) {
                    $id = 'answer_selector_' . $v . '_' . rand(111111, 999999);
                    $checked = '';
                    if (in_array($v, $send_answer)) {
                        $checked = 'checked="checked"';
                    }
                    echo '<p><input class="choose_answer" type="checkbox" id="' . $id . '"  name="send_answer[' . $sheet_q_index . '][answer][]" value="' . $v . '" ' . $checked . ' /><label for="' . $id . '">ข้อ ' . ($v + 1) . '</label></p>';
                }

                break;
            case 4:case 5:

                if (count($send_answer) > 0) {
                    foreach (range(0, (count($content_question['true_answers']) - 1)) as $v) {
                        $id = 'answer_selector_' . $v . '_' . rand(111111, 999999);
                        echo '<p class="p_close_test"><label for="' . $id . '">ตอบ</label> <input class="choose_answer" id="' . $id . '"  type="text" name="send_answer[' . $sheet_q_index . '][answer][]" value="' . $send_answer[$v] . '" /></p>';
                    }
                } else {
                    foreach (range(0, (count($content_question['true_answers']) - 1)) as $v) {
                        $id = 'answer_selector_' . $v . '_' . rand(111111, 999999);
                        echo '<p class="p_close_test"><label for="' . $id . '">ตอบ</label> <input class="choose_answer" id="' . $id . '" type="text" name="send_answer[' . $sheet_q_index . '][answer][]" value="" /></p>';
                    }
                }

                break;
            default:
                break;
        }
        ?>
    </div>
    <div id="question_nav_box">
        <?php if ($sheet_q_index_previous != -1) { ?>
            <a class="answer_box_nav btn-a" href="#" sheet_q_index="<?php echo $sheet_q_index_previous; ?>">ก่อนหน้า</a>
            <?php
        }
        if ($sheet_q_index_next != '') {
            ?>
            <a class="answer_box_nav btn-a" href="#" sheet_q_index="<?php echo $sheet_q_index_next; ?>">ถัดไป</a>
            <?php
        }
        ?>
    </div>
    <div id="question_sure_box">
        <?php
        if ($sure == '') {
            $sure = 1;
        }
        echo form_dropdown('send_answer[' . $sheet_q_index . '][sure]', array('0' => 'ไม่แน่ใจข้อนี้', '1' => 'แน่ใจข้อนี้'), $sure, 'class="choose_answer" id="send_answer_sure"');
        ?>
        <input id="answer_box_q_index" type="hidden" name="send_answer[<?php echo $sheet_q_index; ?>][sheet_q_index]" value="<?php echo $sheet_q_index; ?>" />
    </div>
</form>
<script>
    answer_change = false;
    answer_is_text = <?php
        if ($content_question['content_type_id'] == 4 || $content_question['content_type_id'] == 5) {
            echo 'true';
        } else {
            echo 'false';
        }
        ?>;
    $(function() {

        $(".choose_answer[type=text]").blur(function() {
            answer_change = true;
        });
        $(".choose_answer[type=radio],#send_answer_sure").change(function() {
            send_answer_box();
        });
        $(".answer_box_nav").click(function() {
            if (answer_is_text) {
                if (answer_change) {
                    send_answer_box();
                }
            }
            q_goto($(this).attr("sheet_q_index"));
        });
    });
</script>
<style>
    .btn_video_guide{
        margin-bottom: 5px;
    }
</style>