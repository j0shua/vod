<div class="grid_12" style="min-height: 600px;">
    <h1>{form_title}</h1>
    <div id="center-box">
        <form class="normal-form" method="post" action="<?php echo $form_action; ?>" id="main-form" >
            <input type="hidden"  name="referer_url" value="{referer_url}">
            <p>
                <input type="text" id="username" name="username" value="" placeholder="อีเมล์">
            </p>

            <p>
                <input type="password"  id="password" name="password" value="" placeholder="รหัสผ่าน">

            </p>
            <p>

                <input type="checkbox" name="remember_me" value="ON" />
                <label for="remember_me" id="remember-me-label">จำฉันเอาไว้</label>

            </p>
            <p class="clearfix">
                <input type="submit" class="btn-a" id="btn_submit" name="submit" value="ลงชื่อเข้าใช้" >
            </p>
            <p>
                <a  href="<?php echo $forget_pass_url; ?>" >ลืมรหัสผ่าน</a>
            </p>



        </form>
    </div>

</div>
<script>
    $(function() {
        $("#email").focus();
    });
</script>
<style>
    h1{
        width: 215px;
        margin: auto;
        text-align: center;
        padding: 10px 10px;
        border: 1px solid #CCCCCC;
        margin-top: 40px;
        font-size: 18px;

    }
    #center-box{
        margin: auto;

        width: 215px;
        padding: 20px 10px 0 10px;

        border-bottom: 1px solid #CCCCCC;
        border-left: 1px solid #CCCCCC;
        border-right: 1px solid #CCCCCC;
    }
    #btn-center{
        margin-top: 20px;
        margin-left: 120px;

    }
    #btn_submit{

    }
    #remember-me-label{
        width: 150px;
        text-align: left;
    }


</style>

