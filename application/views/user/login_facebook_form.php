    <h1 class="main-title">ลงชื่อเข้าใช้งาน </h1>
<div class="hr-940px grid_12 "></div>
<div class="grid_6"> 
    <h2>ถ้าคุณยังไม่เคยเข้าใช้งาน คุณสามารถเข้าใช้งานผ่าน facebook ได้นะจ๊ะ !</h2>
    <a class="link-img"  href="<?php echo site_url('fb/login'); ?>"><img src="<?php echo base_url('themes/simple/img/btn-login-fb.png'); ?>"></a>
</div>
<div class="grid_6">
    <form method="post" action="<?php echo $form_action; ?>" id="normalform" >
        <fieldset>

            <p>
                <label for="username" class="grid_2">ชื่อผู้ใช้ <span class="important">*</span></label>
                <input type="text" required id="username" name="username" value="">

            </p>

            <p>
                <label for="password" class="grid_2">รหัสผ่าน <span class="important">*</span></label>
                <input type="password" required id="password" name="password" value="">

            </p>
            <p>
                <label for="remember_me" class="grid_2">อยู่ในสถานะลงชื่อเข้าใช้</label>
                <input type="checkbox" name="remember_me" value="ON" />

            </p>
            <p>
                <input type="submit" class="btn-submit" id="btn_submit" name="submit" value="ลงชื่อเข้าใช้" >
                <a  href="<?php echo $forget_pass_link; ?>" class="btn-a">ลืมรหัสผ่าน</a>
            </p>



        </fieldset>
    </form>
</div>

