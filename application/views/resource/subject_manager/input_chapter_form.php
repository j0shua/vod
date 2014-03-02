<h1 ><?php echo $title; ?></h1>
<div class="grid_12">
    <form id="main_form" class="normal-form" action="<?php echo $form_action; ?>" method="post" >
        <input id="chapter_id" type="hidden" name="data[chapter_id]" value="<?php echo $form_data['chapter_id']; ?>">
        <input id="subj_id" type="hidden" name="data[subj_id]" value="<?php echo $form_data['subj_id']; ?>">
        <input id="la_id" type="hidden" name="data[la_id]" value="<?php echo $form_data['la_id']; ?>">
        <input id="uid_owner" type="hidden" name="data[uid_owner]" value="<?php echo $form_data['uid_owner']; ?>">
        <p>
            <label for="chapter_title">ชื่อบท</label>
            <input type="text" id="chapter_title" name="data[chapter_title]" value="<?php echo $form_data['chapter_title']; ?>">
        </p>
        <input type="submit" value="บันทึก" id="btnSubmit" class="btn-submit" >
        <a href="<?php echo $cancel_link; ?>" class="btn-a">ยกเลิก</a>
    </form>
</div>
<script>
    $(function() {
        $("#main_form").submit(function() {
            var error_msg = [];
            error_msg.push("=== กรุณากรอกข้อมูล ===");
            var b_valid = true;
            if ($("#chapter_title").val() === '') {
                b_valid = false;
                error_msg.push($('label[for="chapter_title"]').html());
            }
            if (b_valid) {
                return true;
            } else {
                alert(error_msg.join("\n"));
                return false;
            }
        });
    });
</script>