<h1 class="main-title">ลืมรหัสผ่านใช่ไหม</h1>

<div class="hr-940px grid_12 "></div>
<div class="grid_9">
    <form class="normal-form" method="post" action="<?php echo $form_action; ?>" id="main-form" autocomplete="off">

        <p>
            <label for="email" style="width: 160px;">โปรดกรอก อีเมล์ ของคุณ</label>
            <input type="text"  id="email" name="email" value="">

        </p>
        <p>
            <input type="submit" class="btn-submit" id="btn_submit" name="submit" value="ตกลง">

        </p>

    </form>
</div>
<script>
    $(function() {
        $("#main-form").submit(function() {
            $("#email").val($.trim($("#email").val()));
            if ($("#email").val() == '') {
                alert("โปรดกรอกข้อมูลอีเมล์");
                return false;
            }
            if (!isValidEmail($("#email").val())) {
                alert("โปรดกรอกข้อมูลอีเมล์ที่ถูกต้อง");
                return false;
            }
            return true;
        });
        function isValidEmail(str) {
            var filter = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i
            return (filter.test(str));
        }
    });
</script>

