
<h1><?php echo $form_title; ?></h1>
<div id="center-box">
    <form class="normal-form" method="post" action="<?php echo $form_action; ?>" id="main-form" >
        <input type="hidden"  name="referer_url" value="<?php echo $referer_url; ?>">
        <p>
            <?php if ($username_field == 'username') { ?>
                <input type="text" id="username" name="username" value="" placeholder="ยูสเซอร์เนม">
            <?php } else { ?>
                <input type="text" id="username" name="username" value="" placeholder="อีเมล์">
            <?php } ?>
        </p>

        <p>
            <input type="password"  id="password" name="password" value="" placeholder="รหัสผ่าน">

        </p>
        <p>

            <input type="checkbox" name="remember_me" value="ON" />
            <label for="remember_me" id="remember-me-label">จำฉันเอาไว้</label>

        </p>
        <p>
            <input type="submit" class="btn-submit" id="btn_submit" name="submit" value="ลงชื่อเข้าใช้" >
        </p>
        <p>
            <a  href="<?php echo $forget_pass_link; ?>" >ลืมรหัสผ่าน</a>
        </p>



    </form>
</div>


<script>
    $(function() {
        $("#email").focus();
        $("#main-form").submit(function() {
            $("#email").val($.trim($("#email").val()));
            if ($("#email").val() == '' || $("#password").val() == '') {
                return false;
            }
            return true;
        });
    });
</script>

