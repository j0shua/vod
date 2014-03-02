    <h1 class="main-title"><?php echo $title; ?></h1>
<div class="hr-940px grid_12 "></div>
<div class="clearfix">
    <?php
    foreach ($grid_menu as $btn) {
        echo anchor($btn['url'], $btn['title'], 'class="btn-submit" ' . $btn['extra']);
    }
    ?>
</div> 


<div class="grid_10">
    <h2>กิจกรรม</h2>
    <table class="tbl-normal">
        <tr>
            <th>งาน</th>
            <th>กำหนดส่ง</th>


        </tr>
        <?php foreach ($course_act_data as $row) { ?>
            <tr>
                <td><?php echo anchor('study/course/view_act/' . $row['ca_id'], $row['title']); ?></td>
                <td><?php echo $row['deadline']; ?> </td>

            </tr>
        <?php } ?>
    </table>
    <?php //print_r($course_act_data); ?>

</div>