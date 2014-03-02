<div class="grid_12">
    <h1 class="main-title"><?php echo $title; ?></h1>
</div>
<div class="hr-940px grid_12 "></div>
<div class="grid_12">
    <form id="normalform" action="<?php echo $form_action; ?>" method="post" >
        <input id="p_id" type="hidden" name="data[p_id]" value="<?php echo $form_data['p_id']; ?>">
        <p>
            <label for="post_tutor_type" class="grid_2">ประกาศ</label>
            <?php echo form_dropdown('data[post_tutor_type]', $post_tutor_type_options, 15, 'id="post_tutor_type"'); ?>
        </p>
        <p>
            <label for="title" class="grid_2">ชื่อประกาศ <span class="important">*</span><span class="less-important">(required)</span></label>
            <input autocomplete="off" type="text" id="title" name="data[title]" value="<?php echo $form_data['title']; ?>"  style="width: 450px;">
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
            <label for="category_id" class="grid_2">หมวดหมู่ <span class="important">*</span><span class="less-important">(required)</span></label>
            <?php echo form_dropdown('data[category_id]', $category_options, '', 'id="category_id"'); ?>
        </p>
        <p>
            <label for="location_type" class="grid_2">เรียนที่<span class="important">*</span><span class="less-important">(required)</span></label>
            <?php echo form_dropdown('data[location_type]', $location_type_options, '', 'id="location_type"'); ?>
        </p>
        <p>
            <label for="province_id" class="grid_2">จังหวัด <span class="important">*</span><span class="less-important">(required)</span></label>
            <?php echo form_dropdown('data[province_id]', $province_options, '', 'id="province_id"'); ?>
        </p>
        <p>
            <label for="address" class="grid_2">ที่อยู่ <span class="important">*</span><span class="less-important">(required)</span></label>
            <textarea id="address" name="data[address]" ><?php echo $form_data['address']; ?></textarea>
        </p>


        <p>
            <label for="post_tutor_time_limit" class="grid_2">ระยะเวลาประกาศ(วัน)</label>
            <?php echo form_dropdown('data[post_tutor_time_limit]', $post_tutor_time_limit_options, 15, 'id="post_tutor_time_limit"'); ?>
        </p>

        <p>
            <label for="website" class="grid_2">เว็บไซต์ <span class="important">*</span><span class="less-important">(required)</span></label>
            <input type="text" id="title" name="data[website]" value="<?php echo $form_data['website']; ?>"  style="width: 450px;">
        </p>


        <input type="submit" value="ลงประกาศทันที" id="btnSubmit" class="btn-submit" >
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