<div class="grid_12">
    <h1>Edit Video</h1>
</div>
<div class="hr-940px grid_12 "></div>
<div class="grid_12">
    <form id="normalform" action="<?php echo $form_action; ?>" method="post" >
        <input id="resource_id" type="hidden" name="resource_id" value="<?php echo $form_data['id']; ?>">
        <p>
            <label for="title" class="grid_2">ชื่อ <span class="important">*</span><span class="less-important">(required)</span></label>
            <input type="text" id="title" name="data[title]" value="<?php echo $form_data['title']; ?>">

        </p>
        <p>
            <label for="desc" class="grid_2">รายละเอียด <span class="important">*</span><span class="less-important">(required)</span></label>
            <textarea id="desc" name="data[desc]" ><?php echo $form_data['desc']; ?></textarea>

        </p>
        <p>
            <label for="tags" class="grid_2">Tags <span class="important">*</span><span class="less-important">(required)</span></label>
            <input type="text" id="tags" name="data[tags]" value="<?php echo $form_data['tags']; ?>">
        </p>
        <p>
            <label for="publish" class="grid_2">การนำไปใช้  <span class="important">*</span><span class="less-important">(required)</span></label>
            <?php echo form_dropdown('data[publish]', $publish_options, $form_data['publish'], 'id="publish"'); ?>
        </p>
        <p>
            <label for="privacy" class="grid_2">การการนำไปใช้  <span class="important">*</span><span class="less-important">(required)</span></label>
            <?php echo form_dropdown('data[privacy]', $privacy_options, $form_data['privacy'], 'id="privacy"'); ?>

        </p>
        <p>
            <label for="category" class="grid_2">หมวดหมู่ <span class="important">*</span><span class="less-important">(required)</span></label>
            <?php echo form_dropdown('data[category]', $category_options,  $form_data['category'], 'id="category"'); ?>
        </p>
        <input type="submit" value="บันทึก" id="btnSubmit" class="btn-submit" >

        <a href="<?php echo $cancel_link; ?>" class="btn-a">ยกเลิก</a>
        <a href="<?php echo $delete_link; ?>" class="btn-a">ลบไฟล์</a>
    </form>

</div>






