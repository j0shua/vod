<h1 class="main-title"><?php echo $title; ?></h1>
<?php //print_r($answer_sheet_data); ?>
<div class="grid_12">
    <?php echo anchor('study/course/course_act/' . $c_id, 'กลับไปหลักสูตรการเรียน', 'class="btn-a"'); ?>
    <table class="data" style="width: 400px;margin-top: 5px;">
        <thead>
            <tr>
                <th>ตอนที่</th><th>คะแนนเต็ม</th><th>คะแนนที่ได้</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($answer_sheet_data['section_score'] as $k => $section) { ?>
                <tr>
                    <td><?php echo $k + 1; ?></td>
                    <td><?php echo $section['full_score']; ?></td>

                    <td><?php echo $section['get_score']; ?></td>

                <tr>
                <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <td>รวม</td>
                <td><?php echo $answer_sheet_data['full_score']; ?></td>

                <td><?php echo $answer_sheet_data['get_score']; ?></td>

            </tr>

        </tfoot>
    </table>


    <?php
    foreach ($answer_sheet_data['answer_sheet'] as $k => $v) {
        $question_image_url = '';
        foreach ($v['question_render_result']['files_basename'] as $image_url) {

            $question_image_url .= img($v['question_render_result']['render_file_base_uri'] . $image_url);
        }
        $send_answer = array();
        foreach ($v['send_answer'] as $v_sa) {
            $send_answer[] = $v_sa + 1;
        }
        $send_answer = implode(',', $send_answer);


        $true_answers = array();
        foreach ($v['true_answers'] as $v_ta) {
            $true_answers[] = $v_ta + 1;
        }
        $true_answers = implode(',', $true_answers);


        $is_true = ($v['is_true']) ? '<span  class="is_true">ถูก</span>' : '<span  class="is_false">ผิด</span>';
        if ($v['resource_id_video_guide']) {
            $video_link = anchor('v/'.$v['resource_id_video_guide'], '▶ วิดีโอแนะแนว', 'class="btn-a btn_video_guide" target="_blank"');
        } else {
            $video_link = '';
        }
        echo '<div class="answer_sheet_solve_box">';
        echo '<div class="ass_num">ข้อ ' . ($k + 1) .' '. $video_link.'</div>';
        echo '<div class="ass_q">' . $question_image_url . '</div>';
        echo '<div class="ass_a"><b>คำตอบที่ส่ง</b> : ' . $send_answer . '</div>';
        echo '<div class="ass_t"><b>คำตอบที่ถูก</b> : ' . $true_answers . '</div>';
        echo '<div class="ass_s"><b>ผลการตรวจ</b> : ' . $is_true . ' | <b>ได้</b> : ' . $v['get_score'] . ' คะแนน</div>';
        echo '</div>';
    }
    ?>
    <?php if (FALSE) { ?>
        <pre>
            <?php print_r($answer_sheet_data['answer_sheet']); ?>
        </pre>
    <?php } ?>
</div>

<style>
    .answer_sheet_solve_box{
        margin-top: 10px;
        background-color: white;
        margin-bottom: 10px;
    }
    .ass_num{
        padding: 5px;
        border: solid 1px #CCCCCC;
        border-bottom: none;
        background-color: #EEEEEE;
        font-weight: bold;

    }
    .ass_q{
        padding: 5px;
        border: solid 1px #CCCCCC;
        border-bottom: none;
    }
    .ass_a{
        padding: 5px;
        border: solid 1px #CCCCCC;
        border-bottom: none;
    }
    .ass_t{
        padding: 5px;
        border: solid 1px #CCCCCC;
        border-bottom: none;
    }
    .ass_s{
        padding: 5px;
        border: solid 1px #CCCCCC;
    }
    .is_true{
        color: green;
    }
    .is_false{
        color: red;
    }
</style>
