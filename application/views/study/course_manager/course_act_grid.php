<?php echo $main_side_menu; ?>

<div class="grid_10">
  
    <h2 class="head1"><?php echo $title; ?>   <?php echo anchor('http://www.youtube.com/embed/qQbYu8NNf4Q?rel=0&wmode=transparent&autoplay=1', "วิดีโอสอนการใช้งาน", 'class="youtube btn-a" target="_blank"'); ?></h2>
    <script>
        $(".youtube").colorbox({iframe: true, innerWidth: 640, innerHeight: 390, opacity: 0.5});
    </script>
    <p>
        <span class="messages detail" >
            <b>รายละเอียดหลักสูตร</b> : <?php echo nl2br($course_data['desc']); ?> |
            <b>จำนวนรับ</b> : <?php echo ($course_data['enroll_limit'] == 0) ? 'ไม่จำกัด' : $course_data['enroll_limit']; ?> คน |
            <b>ระดับชั้น</b> : <?php echo $course_data['degree_name']; ?> |
            <b>กลุ่มสาระวิชา</b> : <?php echo $course_data['learning_area_name']; ?> |
            <b>วิชา</b> : <?php echo $course_data['subject_title']; ?> |
        </span>
    </p>
    <p>
        <span class="messages warning"><b>ระยะเวลาหลักสูตร</b> : <?php echo $course_data['start_time_form_text']; ?> ถึง <?php echo $course_data['end_time_form_text']; ?></span>
    </p>

    <?php
    foreach ($grid_menu as $btn) {
        echo anchor($btn['url'], $btn['title'], 'class="btn-a" ' . $btn['extra']);
    }
    ?>
    <div class="flexigrid_wrap">
    <table id="main-table" style="display: none"></table>
    </div>
</div>
