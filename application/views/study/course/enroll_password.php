<h1 class="main-title"><?php echo $title; ?></h1>
<div class="grid_7">
    <form id="main-form" class="normal-form" action="<?php echo $form_action; ?>" method="post" >
        <input id="c_id" type="hidden" name="data[c_id]" value="<?php echo $form_data['c_id']; ?>">
        <p>
            <label for="title">ชื่อหลักสูตร </label>
            <input disabled type="text" id="title" name="data[title]" value="<?php echo $form_data['title']; ?>">
        </p>
        <p>
            <label for="desc">รายละเอียด </label>
            <textarea  disabled id="desc" name="data[desc]" ><?php echo $form_data['desc']; ?></textarea>
        </p>
        <p>
            <label for="start_time">เริ่มหลักสูตร </label>
            <input  disabled type="text" id="start_time" name="data[start_time]" value="<?php echo $form_data['start_time_form_text']; ?>">
        </p>
        <p>
            <label for="end_time">สิ้นสุดหลักสูตร</label>
            <input  disabled type="text" id="end_time" name="data[end_time]" value="<?php echo $form_data['end_time_form_text']; ?>">
        </p>

        <p>
            <label for="enroll_password">รหัสเข้าเรียนหลักสูตร</label>
            <input  type="text" id="enroll_password" name="data[enroll_password]" value="" maxlength="4">
        </p>


        <input type="submit" value="บันทึก" id="btnSubmit" class="btn-submit" >

        <a href="<?php echo $cancel_link; ?>" class="btn-a">ยกเลิก</a>

    </form>

</div>

<script>
    $(function() {
        $("#main-form").submit(function() {
            $("#enroll_password").val($.trim($("#enroll_password").val()));
            if ($("#enroll_password").val() === '') {
                alert("โปรดกรอกรหัสผ่าน");
                return false;
            }
            return true;
        });
    });
</script>





