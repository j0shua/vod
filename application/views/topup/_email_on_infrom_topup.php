<!DOCTYPE html>
<html lang="th">
    <head>
        <meta charset="utf-8">
        <title></title>
    </head>
    <body>

        <h1>คุณได้รับการเติมเงินจาก educasy แล้ว !</h1>

        <div id="body">
            <h2>คุณได้รับการเติมเงิน <?php echo @$money_topup; ?> บาท </h2>
            <p>เมื่อ : <?php echo @$topup_time; ?></p>
            <p>เข้าบัญชีชื่อ : <?php echo @$fullname_use; ?></p>
            <p>เลขที่สมาชิก : <?php echo @$uid_use; ?></p>
            <p>โอนเงินจำนวน : <?php echo @$money_transfer; ?> บาท</p>
            <p>เวลาโอน : <?php echo @$transfer_time; ?></p>
            <p>เลขที่อ้างอิง : <?php echo @$ref_no; ?></p>
            <p>หมายเหตุ : <?php echo @$desc; ?></p>
            <h3>หากคุณมีคำถามหรือปัญหาใด สามารถติดต่อมาได้ที่</h3>
            <p>email : <?php echo @$site_email; ?></p>
        </div>


    </body>
</html>

