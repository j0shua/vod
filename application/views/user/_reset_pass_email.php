<!DOCTYPE html>
<html lang="th">
    <head>
        <meta charset="utf-8">
        <title></title>
    </head>
    <body>

        <h1><?php echo @$site_name; ?></h1>

        <div id="body">
            <h2>คุณสามารถกำหนดรหัสผ่านของ <?php echo @$site_name; ?></h2>
            <p>ลิ้งสำหรับการกำหนดรหัสผ่านใหม่ : <?php echo @anchor($reset_pass_url); ?></p>
            <h3>หากคุณมีคำถามหรือปัญหาใด สามารถติดต่อมาได้ที่</h3>
            <p>email : <?php echo @$site_email; ?></p>
        </div>


    </body>
</html>

