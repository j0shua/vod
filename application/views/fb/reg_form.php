<div class="grid_12">
    <h1 class="main-title">กรอกข้อมูลอีกนิดหน่อย เพื่อเข้าสู่ educasy !</h1>
</div>

<div class="grid_7" style="height: 900px;">
    <fb:registration redirect-uri="<?php echo $redirect_uri; ?>"
                     fields='<?php echo $custom_fields_json; ?>' 
                     onvalidate="validate_async"
                     height="540"
                     width="540"
                     show_faces='true'
                     header='false'
                     ></fb:registration> 

</div>
<div class="grid_5">
    <h3>คำแนะนำ</h3>
    <ul>
        <li><strong>ชื่อผู้ใช้ : </strong>ชื่อที่ใช้ในการลงชื่อเข้าใช้งานเฉพาะเว็บ educasy.com เคุณั้น </li>
        <li><strong>educasy.com Password : </strong>เป็นรหัสผ่านสำหรับ educasy.com เคุณั้น </li>
        <li><strong>Re-enter Password : </strong>เป็นรหัสผ่านสำหรับ educasy.com ซึ่งต้องเหมือนกับ educasy.com Password</li>
    </ul>
    <p>** เมื่อลงชื่อเข้าใช้แล้ว ทาง educasy.com จะส่งข้อมูลลงทะเบียนไปให้ผ่านทาง email ที่คุณได้สมัครไว้กับ facebook โปรดตรวจสอบเพื่อความถูกต้องด้วยนะครับ</p>

</div>


<script> 
    var request_validate = null;
    function validate_async(form, cb) {
        console.log(form);
        request_validate= $.ajax({
            url: site_url('fb/ajax_check_username/'),        
            type: "POST",        
            data: {
                username : form.username
            },
            dataType: "json"
        });
        request_validate.done(function(msg) {
            console.log(msg);
            if(msg.can_use){
                cb();
            }
            cb({username:msg.msg});
        });
    }
    
  
</script>


<script src="http://connect.facebook.net/th_TH/all.js"></script>
<script type="text/javascript" >
    FB.init({
        appId  : '<?php echo $facebook_appId; ?>',
        status : true,
        cookie : true,
        xfbml  : true
    })
</script>
