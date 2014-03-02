    <h1 class="main-title">รายละเอียดผู้ใช้</h1>

<div class="hr-940px grid_12 "></div>
<div class="grid_3 clearfix" id="page-content">
    <h5>โปรไฟล์</h5>

    <div class="">เลขที่ผู้ใช้ : <?php echo $form_data['uid']; ?></div>
    <div class="">ชื่อ : <?php echo $form_data['first_name']; ?></div>
    <div class="">นามสกุล : <?php echo $form_data['last_name']; ?></div>
    <div class="">เพศ : <?php echo $form_data['sex']; ?></div>
    <div class="">จังหวัด : <?php echo @$form_data['province_name']; ?></div>
</div>
<div class="grid_3 clearfix" id="page-content">
    <h5>เครดิต</h5>
    <div class="">เครดิตเงินคงเหลือ : <?php echo @number_format($form_data['money'], 2); ?> บาท</div>
    <div class="">เครดิตเวลาคงเหลือ : <?php echo $form_data['time_credit']; ?> วินาที</div>
</div>

<div class="grid_3 clearfix" id="page-content">
    <h5>ที่อยู่อีเมล์ และ Social Account</h5>
    <div class="">ที่อยู่อีเมล์ : <?php echo $form_data['email']; ?></div>
    <div class="">facebook : <?php echo anchor('https://www.facebook.com/' . $form_data['facebook_user_id'],$form_data['facebook_user_id'],'target="_blank"'); ?></div>
</div>






