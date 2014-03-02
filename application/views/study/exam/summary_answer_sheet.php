<h1 class="main-title"><?php echo $title; ?></h1>
<?php //print_r($data); ?>
<div class="grid_12">
     <?php
    foreach ($grid_menu as $btn) {
        echo anchor($btn['url'], $btn['title'], 'class="btn-a" ' . $btn['extra']);
    }
    ?>
    
    <table class="data" style="width: 50%;margin-top: 5px;">
        <thead>
            <tr>
                <th>ตอนที่</th><th>คะแนนเต็ม</th><th>เกณฑ์ผ่าน</th><th>คะแนนที่ได้</th><th>สรุป</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data['section_score'] as $k => $section) { ?>
                <tr>
                    <td><?php echo $k + 1; ?></td>
                    <td><?php echo $section['full_score']; ?></td>
                    <td><?php echo $section['pass_score']; ?></td>
                    <td><?php echo $section['get_score']; ?></td>
                    <td><?php
                        if ($section['pass_score'] > $section['get_score']) {
                            echo 'ไม่ผ่าน';
                        } else {
                            echo 'ผ่าน';
                        }
                        ?></td>
                <tr>
                <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <td>รวม</td>
                <td><?php echo $data['full_score']; ?></td>
                <td></td>
                <td><?php echo $data['get_score']; ?></td>
                <td><?php echo ($is_pass) ? 'ผ่าน' : 'ไม่ผ่าน'; ?></td>
            </tr>
            <tr>
                <td>คะแนนเก็บ</td>
                <td><?php echo $full_score; ?></td>
                <td></td>
                <td><?php echo $get_score; ?></td>
                <td><?php echo ($is_pass) ? 'ผ่าน' : 'ไม่ผ่าน'; ?></td>
            </tr>
        </tfoot>
    </table>


    <?php
    if ($show_solve) {


        foreach ($data['answer_sheet'] as $k => $v) {
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

            echo '<div class="answer_sheet_solve_box">';
            echo '<div class="ass_num">ข้อ ' . ($k + 1) . '</div>';
            echo '<div class="ass_q">' . $question_image_url . '</div>';
            echo '<div class="ass_a"><b>คำตอบที่ส่ง</b> : ' . $send_answer . '</div>';
            echo '<div class="ass_t"><b>คำตอบที่ถูก</b> : ' . $true_answers . '</div>';
            echo '<div class="ass_s"><b>ผลการตรวจ</b> : ' . $is_true . ' | <b>ได้</b> : ' . $v['get_score'] . ' คะแนน</div>';
            echo '</div>';
        }
    }
    ?>
    <?php if (FALSE) { ?>
        <pre>
            <?php print_r($data['answer_sheet']); ?>
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
