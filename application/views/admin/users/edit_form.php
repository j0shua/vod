<div class="grid_12">
    <h1>แก้ไขสมาชิก</h1>
    <form id="normalform" autocomplete="off" method="post" action="<?php echo $form_acction; ?>">
        <input type="hidden" name="uid" value="<?php echo $uid; ?>">
        <p>
            <label for="name" class="grid_2">Username <span class="important">*</span><span class="less-important">(required)</span></label>
            <input type="text" id="form_name" name="name" value="<?php echo $username; ?>">

        </p>

        <p>
            <label for="email" class="grid_2">Email <span class="important">*</span><span class="less-important">(required)</span></label>
            <input type="text" id="form_email" name="email" value="<?php echo $email; ?>">

        </p>
        <p>
            <label for="pass" class="grid_2">password <span class="important">*</span><span class="less-important">(required)</span></label>
            <input type="password" id="form_pass" name="pass" value="">

        </p>
        <input type="submit" value="บันทึก" name="filter" id="btn-filter" class="btn-submit">
        <a href="<?php echo $cancel_link; ?>" class="btn-a">ยกเลิก</a>
    </form>
</div>
<script>
    $(function(){
        $("#normalform").submit(function(){
            return true;
        });
    });
</script>

