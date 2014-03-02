<!DOCTYPE html>
<html lang="th">
    <head>
        <meta charset="utf-8">
        <title></title>
    </head>
    <body>

        <h1>ยินดีต้อนรับสู่ to <?php echo @$site_name; ?></h1>

        <div id="body">
            <h2>คุณสามารถเข้าใช้งาน <?php echo @$site_name; ?> ได้ ผ่าน <?php echo anchor('fb/login', 'Facebook'); ?> หรือ <?php echo anchor('user/login', 'login แบบปกติ'); ?></h2>
            <p>username : <?php echo @$username ?></p>
            <p>password : <?php echo @$password ?></p>
            <h3>หากคุณมีคำถามหรือปัญหาใด สามารถติดต่อมาได้ที่</h3>
            <p>email : <?php echo @$site_email; ?></p>
        </div>


    </body>
</html>

