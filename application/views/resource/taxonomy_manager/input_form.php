<h1><?php echo $form_title; ?></h1>

<div class="grid_12">
    <form class="normal-form" id="main-form" action="<?php echo $form_action; ?>" method="post" >
        <input id="resource_id" type="hidden" name="tid" value="<?php echo $form_data['tid']; ?>">
        <p>
            <label for="title" >ชื่อชุดวิดีโอ </label>
            <input type="text" id="title" name="data[title]" value="<?php echo $form_data['title']; ?>">
        </p>
        <p>
            <label for="desc" >รายละเอียด </label>
            <textarea style="height: 100px;" id="desc" name="data[desc]" ><?php echo $form_data['desc']; ?></textarea>
        </p>
        <p>
            <label for="publish" >การแสดงบนหน้าเว็บ  </label>
            <?php echo form_dropdown('data[publish]', $publish_options, $form_data['publish'], 'id="publish"'); ?>
        </p>
<!--        <p>
            <label for="weight" >น้ำหนัก  </label>
            <?php echo form_dropdown('data[weight]', $weight_options, $form_data['weight'], 'id="weight"'); ?>
        </p>-->
        <input type="submit" value="บันทึก" id="btnSubmit" class="btn-submit" >

        <a href="<?php echo $cancel_link; ?>" class="btn-a">ยกเลิก</a>

    </form>

</div>






