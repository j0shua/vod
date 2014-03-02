<form id="top-login-form" action="<?php echo $form_action; ?>" method="post" autocomplete="off">
    <div class="col_2">
        <p>
            <label for="username">ชื่อเข้าใช้</label><input type="text" id="username" name="form_data[username]" value="" size="16" />
        </p>
        <p>
            <label for="password">รหัสผ่าน</label><input type="password" id="Password" name="form_data[Password]" value="" size="16" />
        </p>
        <input id="btn-login-submit" type="submit" value="ลงชื่อเข้าใช้" />
    </div>
</form>