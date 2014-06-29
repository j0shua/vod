<h1 class="main-title"><?php echo $title; ?></h1>
<div class="grid_12">

    <div id="accordion">
        <h3>1. นักเรียนสามารถสมัครสมาชิกได้ ฟรี   </h3>
        <div>
            <p>นักเรียนสามารถสมัครสมาชิกได้ ฟรี  <a class="btn-a" href="<?php echo site_url('user/register'); ?>">โดย click ที่นี่</a> จากนั้นระบบจะส่ง Password ทาง e-mail ที่ได้แจ้งไว้ตอนสมัครสมาชิก</p>
            <p>(1 e-mail address ใช้รับ Password ได้ครั้งเดียวเท่านั้น )</p>
            <p>( หากท่านไม่พบ Password ใน mail ของท่าน โปรดค้นหาใน junk mail หรือ mail ขยะ )</p>

        </div>
        <h3>2. นักเรียนสามารถเปิดดู VDO + โหลดเอกสารการเรียน </h3>
        <div>
            <h4>  นักเรียนสามารถเปิดดู VDO + โหลดเอกสารการเรียน ( ทั้งนี้หลังจากสมัครสมาชิกเสร็จแล้ว สามารถเข้าเรียนเนื้อหาต่างๆบนเว็บได้ )</h4>

            <?php echo img('files/images/help/student_manual/st2.jpg'); ?>
        </div>
        <h3>3.ค้นหาครูผู้สอน + โรงเรียน  </h3>
        <div>
            <h4>ค้นหาครูผู้สอน + โรงเรียน ( เพื่อเข้าเรียนเนื้อหาทั้งหมดของครูท่านนั้น ) โดยเลือกได้จากหน้าแรกของเว็บไซต์ หรือจากเมนูหลักด้านบน</h4>
            <?php echo img('files/images/help/student_manual/1.png'); ?>
            <p>3.1 สมัครเรียนหลักสูตรกับคุณครู</p>
            <p>3.1.1 Click เปิดดูรายละเอียดของหลักสูตรที่ต้องการศึกษา</p>
            <p>3.1.2 Click เลือก สมัครหลักสูตรนี้ เมื่อclickแล้ว หลักสูตรจะปรากฏที่เมนู หลักสูตรการเรียนที่เรียนอยู่</p>
            <p>3.1.3 Click เลือก ชื่อหลักสูตรเพื่อทำการเรียน + ทำการบ้าน + การสอบ + ตลอดจนงานที่ได้รับมอบหมายจากคุณครู</p>
            <p><?php echo img('files/images/help/student_manual/study.png'); ?></p>
            <p>3.1.4  นักเรียนสามารถค้นหาหลักสูตรของครูท่านอื่นโดยเลือกจาก --> เมนูหลัก --> ค้นหาหลักสูตรที่เปิดสอน --> เลือกเปิดดู</p>

            <p><?php echo img('files/images/help/student_manual/study2.png'); ?></p>
            <p>- เลือกสมัครคอร์สนี้</p>
            <p><?php echo img('files/images/help/student_manual/study3.png'); ?></p>

        </div>

    </div>
</div>
<script>
    $(function() {
        $("#accordion").accordion({heightStyle: "content",collapsible: true});
        //$("#tabs2").tabs();
        //$("#tabs3").tabs();

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








