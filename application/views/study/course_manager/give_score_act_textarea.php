




<h1><?php echo $title; ?></h1>

<div class="grid_12">


    <form id="main-form" class="normal-form" action="<?php echo $form_action; ?>" method="post"  accept-charset="utf-8">

        <input id="cas_id" type="hidden" name="form_data[cas_id]" value="<?php echo $form_data['cas_id']; ?>">
        <input id="c_id" type="hidden" name="form_data[c_id]" value="<?php echo $form_data['c_id']; ?>">
        <input id="ca_id" type="hidden" name="form_data[ca_id]" value="<?php echo $form_data['ca_id']; ?>">
        <input id="uid_sender" type="hidden" name="form_data[uid_sender]" value="<?php echo $form_data['uid_sender']; ?>">

        <?php if ($course_act_data['cmat_id'] == 2) { ?>
            <p>
                <label for="course_act_data" >รายละเอียด </label>
                <a target="_blank" class="btn-a-small" href="<?php echo site_url('v/' . $course_act_data['data']); ?>">เปิดดูใบงาน</a>        </p>


        <?php } else { ?>
            <p>
                <label for="course_act_data" >รายละเอียด </label>
                <textarea disabled type="text" id="course_act_data" style="width: 350px;height: 100px;"><?php echo $course_act_data['data']; ?></textarea>

            </p>

        <?php } ?>

        <p>
            <label for="data">ตัวงาน </label>
            <textarea disabled type="text" id="data" name="data[data]"  style="width: 350px;height: 100px;"><?php echo $form_data['data']; ?></textarea>
        </p>
        <p>
            <label for="comment">คำวิจารณ์ของครู </label>
            <textarea  type="text" id="comment" name="form_data[comment]" style="width: 350px;height: 100px;"><?php echo $form_data['comment']; ?></textarea>
        </p>
        <p>
            <label for="get_score">คะแนน </label>
            <?php echo form_dropdown('form_data[get_score]', $give_score_options, $form_data['get_score'], 'id="get_score"'); ?>
        </p>
        <input type="submit" value="ให้คะแนน" id="btnSubmit" class="btn-submit" >

        <a href="<?php echo $cancel_link; ?>" class="btn-a">ยกเลิก</a>

    </form>
</div>













