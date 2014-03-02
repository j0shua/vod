<h1><?php echo $title; ?></h1>
<div class="grid_12">
    <form id="main-form" class="normal-form" method="post"  accept-charset="utf-8">
        <?php if ($course_act_data['cmat_id'] == 2) { ?>
            <p>
                <label for="head_data">คำสั่ง </label>
                <?php echo anchor('v/' . $course_act_data['data'], 'เปิดดูใบงาน', 'class="btn-a-small" target="_blank"'); ?>
            </p>
        <?php } else { ?>
            <p>
                <label for="head_data">คำสั่ง </label>
                <textarea readonly class="" style="width: 974px;height: 80px;overflow-x: hidden;overflow-y: auto;resize: vertical;"><?php echo nl2br($course_act_data['data']); ?></textarea>
            </p>
        <?php } ?>
        <p>
            <label for="data">งานที่ส่ง </label>
            <textarea readonly type="text"  style="width: 350px;height: 100px;" id="data"  ><?php echo $course_act_sent_data['data']; ?></textarea>
        </p>
        <p>
            <label for="comment">comment </label>
            <textarea readonly type="text"  style="width: 350px;height: 100px;" id="data"  ><?php echo $course_act_sent_data['comment']; ?></textarea>

        </p>
        <p>
            <label for="full_score">คะแนนเต็ม </label>
            <input readonly type="text" id="full_score" value="<?php echo $course_act_sent_data['full_score']; ?>">
            

        </p>
        <p>
            <label for="get_score">คะแนนที่ได้ </label>
            <input readonly type="text" id="get_score" value="<?php echo $course_act_sent_data['get_score']; ?>">
            

        </p>

        <a href="<?php echo $cancel_link; ?>" class="btn-a">กลับ</a>
    </form>
</div>









