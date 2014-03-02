
<div class="grid_12" style="min-height: 600px;">
    <h1>{form_title}</h1>
    <div id="center-box">
        <form class="normal-form" id="form-main" action="<?php echo $form_action ?>" method="post" autocomplete="off">
            <input type="hidden" name="form_data[UserID]" value="" />

            <p>
                <label for="username">ชื่อเข้าใช้งาน</label><input type="text" id="Username" name="username" value="" size="16" />
            </p>
            <p>
                <label for="password">รหัสผ่าน</label><input type="password" id="Password" name="password" value="" size="16" />
            </p>
            <p>
                <label for="remember">จำฉันเอาไว้</label> <input id="remember" type="checkbox" name="remember" value="ON" checked />
            </p>

            <input id="btn-center" type="submit" value="ลงชื่อเข้าใช้งาน" />
    </div>
</form>
</div>
<style>
    h1{
        width: 400px;
        margin: auto;
        text-align: center;
        padding: 10px 10px;
        border: 1px solid #CCCCCC;
        margin-top: 150px;
    }
    #center-box{
        margin: auto;
        width: 400px;
         padding: 20px 10px;
        
         border-bottom: 1px solid #CCCCCC;
        border-left: 1px solid #CCCCCC;
        border-right: 1px solid #CCCCCC;
    }
    #btn-center{
        margin-top: 20px;
        margin-left: 120px;
        
    }

</style>

