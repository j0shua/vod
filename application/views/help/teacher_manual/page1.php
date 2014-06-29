<h1 class="main-title"><?php echo $title; ?></h1>
<div class="grid_12">

    <div id="accordion">
        <h3>1. ครูสามารถสมัครสมาชิกได้ ฟรี </h3>
        <div>

            <p>
                ครูสามารถสมัครสมาชิกได้ ฟรี <a class="btn-a" href="<?php echo site_url('user/registerteacher'); ?>">โดย click ที่นี่</a> จากนั้นระบบจะส่ง Password ทาง e-mail ที่ได้แจ้งไว้ตอนสมัครสมาชิก     
            </p>
            <p>(1 e-mail address ใช้รับ Password ได้ครั้งเดียวเท่านั้น )</p><p>
                ( หากท่านไม่พบ Password ใน mail ของท่าน โปรดค้นหาใน junk mail หรือ mail ขยะ )</p>

        </div>
        <h3>2. ครูสามารถ Upload VDO + เอกสารการสอนได้ง่ายๆ</h3>
        <div>
            <h4> เลือกเมนูหลักจากหน้าแรก แล้วเลือกเมนู ( จัดการ Content )</h4>

            <div id="tabs2" class="tabs">
                <ul>
                    <li><a href="#tabs2-1">จัดการ โจทย์เนื้อหา</a></li>
                    <li><a href="#tabs2-2">จัดการ VDO</span></a></li>
                    <li><a href="#tabs2-3">จัดการ แฟ้มเอกสาร</a></li>
                    <li><a href="#tabs2-4">จัดการ รูปภาพ</a></li>
                    <li><a href="#tabs2-5">จัดการ จัดการบทเรียน Flash</a></li>
                </ul>
                <div id="tabs2-1">
                    <h5>วิธีใช้งานเบื้องต้น เมนูจัดการ โจทย์และเนื้อหา</h5>
                    <?php echo img('files/images/help/teacher_manual/add_exam.png'); ?>
                </div>
                <div id="tabs2-2">
                    <h5> วิธีใช้งานเบื้องต้น เมนูจัดการ VDO</h5>
                    <?php echo img('files/images/help/teacher_manual/add_vdo.png'); ?>
                </div>
                <div id="tabs2-3">
                    <h5>วิธีใช้งานเบื้องต้น เมนูจัดการ แฟ้มเอกสาร</h5>
                    <?php echo img('files/images/help/teacher_manual/add_sheet.png'); ?>
                </div>
                <div id="tabs2-4">
                    <h5>วิธีใช้งานเบื้องต้น เมนูจัดการ รูปภาพ</h5>

                </div>
                <div id="tabs2-5">
                    <h5>วิธีใช้งานเบื้องต้น เมนูจัดการ บทเรียน FLASH</h5>

                </div>
            </div>
        </div>
        <h3>3. ครูสามารถ จัดการเรียนการสอนได้ดังต่อไปนี้ </h3>
        <div>
            <h4>เลือกเมนูหลักจากหน้าแรก แล้วเลือกเมนู ( จัดการ Content )</h4>
            <div id="tabs3" class="tabs">
                <ul>
                    <li><a href="#tabs3-1">กลุ่มสาระ / วิชา / บท</a></li>
                    <li><a href="#tabs3-2">ใบงาน</a></li>
                    <li><a href="#tabs3-3">หลักสูตรการสอน</a></li>
                    <li><a href="#tabs3-4">คัดลอกหลักสูตรการสอนมาใช้</a></li>
                    <li><a href="#tabs3-5">ชุดวีดีโอการสอนหน้าเพจ</a></li>
                </ul>
                <div id="tabs3-1">
                    <h5>วิธีใช้งานเบื้องต้น</h5>
                    <?php echo img('files/images/help/teacher_manual/add_subject.png'); ?>
                </div>
                <div id="tabs3-2">
                    <h5>วิธีใช้งานเบื้องต้น</h5>
                    <?php echo img('files/images/help/teacher_manual/add_work.png'); ?>
                </div>
                <div id="tabs3-3">
                    <h5>วิธีใช้งานเบื้องต้น</h5>
                    <?php echo img('files/images/help/teacher_manual/add_cross.png'); ?>

                </div>
                <div id="tabs3-4">
                    <h5>วิธีใช้งานเบื้องต้น</h5>
                    <?php echo img('files/images/help/teacher_manual/copy_cross.png'); ?>

                </div>
                <div id="tabs3-5">
                    <h5>วิธีใช้งานเบื้องต้น</h5>
                </div>
            </div>
        </div>

    </div>
</div>
<script>
    $(function() {
        $("#accordion").accordion({heightStyle: "content",collapsible: true});
        $("#tabs2").tabs();
        $("#tabs3").tabs();

    });
</script>
<style>
    #accordion h3{
        font-size: 28px;
        padding: 5px 5px 5px 40px;
    }
    #accordion h4{
        font-size: 26px;
        margin-bottom: 15px;
    }
    #accordion h5{
        font-size: 24px;
        margin-bottom: 15px;
    }

</style>








