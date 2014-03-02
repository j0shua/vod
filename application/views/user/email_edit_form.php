<h1 class="main-title">แก้ไข ที่อยู่อีเมล</h1>
<div class="hr-940px grid_12 "></div>
<div class="grid_9">
    <?php
    if (is_array($this->session->flashdata('form_error'))) {
        foreach ($this->session->flashdata('form_error') as $v) {
            echo '<p>' . $v . '</p>';
        }
    }
    ?>
    <form class="normal-form" method="post" action="<?php echo $form_action; ?>" id="main-form" autocomplete="off">
        <input type="hidden" id="old_email" name="old_email" value="<?php echo $form_data['email']; ?>">
        <p>
            <label for="email" >ที่อยู่อีเมล</label>
            <input type="text" id="email" name="email" value="<?php echo $form_data['email']; ?>" maxlength="255">

        </p>

        <input type="submit" class="btn-submit" id="form_submit" name="submit" value="บันทึก">
        <a class="btn-a" href="<?php echo $cancel_url; ?>">ยกเลิก</a>
        <p class="hide important" id="response"></p>


    </form>
</div>
<script>
    $(function() {
        $("#main-form").submit(function() {
            $("#email").val($.trim($("#email").val()));
            if ($("#email").val() == $("#old_email").val()) {
                alert("คุณยังไม่ได้เปลี่ยนอีเมล์");
                return false;
            }
            return true;
        });
    });
</script>


