<h1>เปลี่ยนรหัสผ่าน</h1>
<div class="grid_9">
    <?php
    if (is_array($this->session->flashdata('form_error'))) {
        foreach ($this->session->flashdata('form_error') as $v) {
            echo '<p>' . $v . '</p>';
        }
    }
    ?>
    <form method="post" action="<?php echo $form_action; ?>" id="main-form" class="normal-form" autocomplete="off">
        <p>
            <label for="old_password" >รหัสผ่าน เดิม:</label>
            <input type="password" id="old_password" name="old_password" value="" maxlength="255">
        </p>
        <p>
            <label for="new_password" >รหัสผ่าน ใหม่:</label>
            <input type="password" id="new_password" name="new_password" value="" maxlength="255">
        </p>
        <input type="submit" class="btn-submit" id="form_submit" name="submit" value="บันทึก">
        <a class="btn-a" href="<?php echo $cancel_url; ?>">ยกเลิก</a>
        <p class="hide important" id="response"></p>
    </form>
</div>


