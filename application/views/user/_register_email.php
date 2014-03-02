<!DOCTYPE html>
<html lang="th">
    <head>
        <meta charset="utf-8">
        <title></title>
    </head>
    <body>

        <h1><?php echo @$site_name; ?></h1>

        <div id="body">
            <h2>คุณได้ลงทะเบียนเข้าใช้งาน <?php echo @$site_name; ?> เรียบร้อยแล้ว</h2>
            <p>อีเมล์ : <?php echo @$email; ?></p>
            <p>รหัสผ่าน : <?php echo @$password; ?></p>
            <h3>หากคุณมีคำถามหรือปัญหาใด สามารถติดต่อมาได้ที่</h3>
            <p>email : <?php echo @$site_email; ?></p>
        </div>


    </body>
</html>

