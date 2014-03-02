<h1>เปลี่ยนรหัสผ่าน ได้เลย</h1>
<div class="grid_9">

    <form method="post" action="<?php echo $form_action; ?>" id="main-form" class="normal-form" autocomplete="off">
        <input type="hidden" id="uid" name="uid" value="<?php echo $uid; ?>" >
        <p>
            <label for="password" >รหัสผ่าน ใหม่:</label>
            <input type="password" id="password" name="password" value="" maxlength="255">
        </p>
        <input type="submit" class="btn-a" id="form_submit" name="submit" value="บันทึก">
        <a class="btn-a" href="<?php echo $cancel_url; ?>">ยกเลิก</a>
        <p class="hide important" id="response"></p>
    </form>
</div>


