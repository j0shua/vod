<h1><?php echo $main_title; ?></h1>
<?php echo $main_side_menu; ?>
<div class="grid_10">
    
    <h2 class="head1"><?php echo $title; ?></h2>
    <p class="clearfix">
        <?php
        foreach ($grid_menu as $btn) {
            echo anchor($btn['url'], $btn['title'], 'class="btn-a" ' . $btn['extra']);
        }
        ?>
    </p>
    <table class="data">
        <tr>
            <th>งาน</th>
            <th>กระทำ</th>
            <th>กำหนดส่ง</th>
            <th>แก้ไข</th>
        </tr>
        <?php foreach ($course_act_data as $row) { ?>
            <tr>
                <td><?php echo anchor('study/course_manager/view_act/' . $row['ca_id'], $row['title'], 'class="btn-a-small"'); ?></td>
                <td><?php echo anchor('study/course_manager/view_act/' . $row['ca_id'], 'ให้คะแนน', 'class="btn-a-small"'); ?></td>
                <td><?php echo $row['deadline']; ?> </td>
                <td>
                    <?php echo anchor('study/course_manager/edit_course_act/' . $row['ca_id'], 'แก้ไข', 'class="btn-a-small"'); ?>
                    <?php echo anchor('study/course_manager/delete_course_act/' . $row['ca_id'], 'ลบ', 'class="btn-a-small"'); ?>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>