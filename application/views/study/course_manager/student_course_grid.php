
<?php echo $main_side_menu; ?>
<div class="grid_10">
    <h2 class="head1"><?php echo $title; ?></h2>
    <p>
        <span class="messages detail" ><b>รายละเอียดหลักสูตร</b> : <?php echo nl2br($course_data['desc']); ?> | <b>จำนวนรับ</b> : <?php echo ($course_data['enroll_limit'] == 0) ? 'ไม่จำกัด' : $course_data['enroll_limit']; ?> คน</span>
    </p>
    <p>
        <span class="messages warning"><b>ระยะเวลาหลักสูตร</b> : <?php echo $course_data['start_time_form_text']; ?> ถึง <?php echo $course_data['end_time_form_text']; ?></span>
    </p>
    <?php
    foreach ($grid_menu as $btn) {
        echo anchor($btn['url'], $btn['title'], 'class="btn-a" ' . $btn['extra']);
    }
    ?>
    <table id="main-table" style="display: none"></table>
</div>