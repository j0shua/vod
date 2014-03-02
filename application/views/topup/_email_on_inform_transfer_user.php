<!DOCTYPE html>
<html lang="th">
    <head>
        <meta charset="utf-8">
        <title></title>
    </head>
    <body>

        <h1>คุณได้แจ้งการโอนเงินเป็นที่เรียบร้อยแล้ว !</h1>

        <div id="body">
            <h2>เมื่อทางเว็บไซต์ตรวจสอบการโอนเงินของคุณเสร็จสิ้นทางเว็บไซต์จะเติมเงินให้กับคุณในทันที</h2>
            <h2>รายละเอียดการโอนเงินดังนี้ </h2>
            <p>ชื่อ : <?php echo @$fullname_use; ?></p>
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

