<h1><?php echo $title; ?></h1>

<div class="grid_12">
    <h2 class="head1">รายการการสอน</h2>
    <p>
        <?php
        foreach ($grid_menu as $btn) {
            echo anchor($btn['url'], $btn['title'], 'class="btn-a" ' . $btn['extra']);
        }
        ?>
    </p>
    <p>
        <span class="messages detail" >
            <b>รายละเอียดหลักสูตร</b> : <?php echo nl2br($course_data['desc']); ?> |
            <b>จำนวนรับ</b> : <?php echo ($course_data['enroll_limit'] == 0) ? 'ไม่จำกัด' : $course_data['enroll_limit']; ?> คน |
            <b>ระดับชั้น</b> : <?php echo $course_data['degree_name']; ?> |
            <b>กลุ่มสาระวิชา</b> : <?php echo $course_data['learning_area_name']; ?> |
            <b>วิชา</b> : <?php echo $course_data['subject_title']; ?> |
        </span>
    </p>
    <table class="data" >
        <thead>
            <tr>
                <th>#</th>
                <th>ชื่องาน</th>
                <th>ประเภทงาน</th>
                <th>วิธีส่งงาน</th>
                <th>คะแนนเต็ม</th>


            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($course_act_data['rows'] as $k => $v) {
                $row_course_act = $v['cell'];
                if ($row_course_act['ca_id'] != '') {
                    ?>

                    <tr>
                        <td><?php echo $k + 1; ?></td>
                        <td><?php echo $row_course_act['title']; ?></td>
                        <td><?php echo $row_course_act['act_type']; ?></td>
                        <td><?php echo $row_course_act['send_type']; ?></td>
                        <td><?php echo $row_course_act['full_score']; ?></td>



                    </tr>  

                    <?php
                } else {
                    ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><b>รวม</b></td>
                        <td><b><?php echo $row_course_act['full_score']; ?></b></td>



                    </tr>  
                    <?php
                }
                ?>

            <?php } ?>

        </tbody>
    </table>
   
</div>