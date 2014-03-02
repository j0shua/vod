
<div class="grid_8">
    <div id="frame-topup">
        <h2 class="head1">เติมเงิน</h2>
        <h1>User ID ของท่านคือ : <span style="color: #E33737"><?php echo $user_data[$username_field] ?></span></h1>
        <iframe id="if_true" src="http://www.weloveshopping.com/game/payment/register.php?gid=<?php echo $true_gid; ?>"         width="770" height="580"></iframe>
    </div>
</div> 
<div class="grid_4 side-help">
    <h2 class="head1">สถานที่จำหน่ายบัตรเงินสดทรูมันนี่</h2>
    <ul>
        <li>7-elevent ทุกสาขา</li>
        <li>ทรูช้อป /ทรูมูฟ ช้อปทุกสาขา</li>
        <li>ร้านตัวแทนจำหน่ายทั่วประเทศ</li>
        <li>True Money Express , ตู้อัตโนมัติ 24 ชั่วโมง , Lotus</li>
    </ul>

    <h2 class="head1">วิธีเติมเงิน</h2> 
    <div class="overflow-y">
        <p class="p_how">
            1. กรอก Email ที่ใช้ลงชื่อเข้าใช้ระบบ ลงในช่องว่าง และ กดปุ่ม Game user ID
            <br>
            <img src="<?php echo base_url(); ?>files/images/true_topup001.png"  alt="true_topup001"/>


        </p>
        <p class="p_how">

            2. เลือกจำนวนเงินเติมตามราคาของบัตรเติมเงินที่ซื้อมาและกด ตกลง
            <br>
            <img src="<?php echo base_url(); ?>files/images/true_topup002.png" alt="true_topup001"/>

        </p>

        <p class="p_how">
            3. กรอกรหัสบัตรเงินสด 14 หลัก แล้ว กรอกรหัสผ่าน และกด ตกลง
            <br>
            <img src="<?php echo base_url(); ?>files/images/true_topup003.png" width="350" alt="true_topup001"/>


        </p>




        4. เมื่อเติมเงินเสร็จสิ้นผู้ใช้จะได้รับข้อความ Success
        <br>
        <img src="<?php echo base_url(); ?>files/images/true_topup004.png" alt="true_topup001"/>

        </p>
    </div>
</div>
<script>
    $(function() {
//        var a = $("#if_true .Box999999").val();
//        console.log($("#if_true"));
    });
</script>

<style>
    #frame-topup{ 

        margin-top: 10px;

    }
    #frame-topup iframe{
        overflow: hidden;


    }
    .side-help{
        margin-top: 10px;
    }
    .overflow-y{
        overflow-y: auto;
        overflow-x: no-display;
        height: 540px;
    }
    .side-help li{
        list-style-type: disc;

    }
    .p_how{
        padding-bottom: 8px;
        border-bottom: 4px #CCCCCC solid;
    }

</style>