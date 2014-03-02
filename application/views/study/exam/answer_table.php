
<?php
//echo rand(1, 5);
$cell = array();
$rows = array();
//print_r($answer_sheet);

foreach ($answer_sheet as $k => $v) {
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
    $cell[] = '<td class="answer-td ' . $class . '"><a class="answer_sheet_btn" href="#" sheet_q_index="' . ($k) . '">' . ($k + 1) . '</a></td>';
    if (count($cell) == 5) {
        $rows[] = implode(' ', $cell);
        $cell = array();
    }
}
?>

<table id="tbl_answer_sheet">
    <?php
    foreach ($rows as $row) {
        echo '<tr>' . $row . '</tr>';
    }
    ?>
</table>
<script>
    $(function(){
        $(".answer_sheet_btn").click(function(){
            q_goto($(this).attr("sheet_q_index"));
            //alert($(this).attr("title"));
        });
        $(".answer_box_answer,#answer_box_sure").change(function(){
            send_answer_box();
        });
    });
</script>