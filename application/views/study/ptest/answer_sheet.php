<?php
$section_index = -1;
foreach ($questions as $k => $v) {
    $class = '';
    if ($v['sure']) {
        $class = 'dosure';
    } else if ($v['send_time'] != '') {
        $class = 'donotsure';
    }
    if ($sheet_q_index == $k) {
        // echo $q_index;
        $class = 'current_q';
    }
    if ($section_num > 1) {
        if ($v['section_index'] != $section_index) {
            echo '<div>ตอนที่ ' . ($v['section_index'] + 1) . '</div>';
            $section_index++;
        }
    }
    echo '<div class="answer_cell ' . $class . '"><a class="btn_select_question" href="#" sheet_q_index="' . ($k) . '">' . ($k + 1) . '</a></div>';
}
?>


<script>
    $(function() {
        $(".btn_select_question").click(function() {
            q_goto($(this).attr("sheet_q_index"));
        });
    });
</script>