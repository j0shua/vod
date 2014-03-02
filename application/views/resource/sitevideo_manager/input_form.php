<div class="grid_12">
    <h1 class="main-title"><?php echo $title; ?></h1>
</div>
<div class="hr-940px grid_12 "></div>
<div class="grid_12">
    <form id="normalform" action="<?php echo $form_action; ?>" method="post" >
        <input id="resource_id" type="hidden" name="resource_id" value="<?php echo $form_data['resource_id']; ?>">
        <p>
            <label for="url_video" class="grid_2">url YouTube <span class="important">*</span><span class="less-important">(required)</span></label>
            <input type="text" required id="url_video" name="data[url_video]" value="<?php echo $form_data['url_video']; ?>" style="width: 450px;">
        </p>
        <p>
            <label for="title" class="grid_2">ชื่อ <span class="important">*</span><span class="less-important">(required)</span></label>
            <input type="text" id="title" name="data[title]" value="<?php echo $form_data['title']; ?>"  style="width: 450px;">
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
            <label for="category_id" class="grid_2">หมวดหมู่ <span class="important">*</span><span class="less-important">(required)</span></label>
            <?php echo form_dropdown('data[category_id]', $category_options, '', 'id="category_id"'); ?>
        </p>
        <input type="submit" value="บันทึก" id="btnSubmit" class="btn-submit" >
        <a href="<?php echo $cancel_link; ?>" class="btn-a">ยกเลิก</a>
    </form>
</div>
<script>
    $(function(){
        $("#normalform").submit(function(e){
            var error_msg = []
            var b_valid = true;
            $("#title").val($.trim($("#title").val()));
            if($("#title").val() == ''){
                b_valid = false;
                error_msg.push("กรุณาใส่ ชื่อ video");
            }
            if(b_valid){
                return true;
            }else{
                alert(error_msg.join("\n"));
                return false;
            }
        });
    });
</script>