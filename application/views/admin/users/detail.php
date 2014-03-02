<h1 class="main-title">ข้อมูลส่วนตัวของ <?php echo $form_data['first_name'] . ' ' . $form_data['last_name']; ?></h1>


<div class="grid_3 clearfix" >
    <h2 class="head1">รูปประจำตัว</h2>
    <img id="avatar-img" class="clearfix"  title="รูปประจำตัว" src="<?php echo site_url('ztatic/img_avatar_128/' . $form_data['uid']); ?>">


    <h2 class="head1">ข้อมูลพื้นฐาน</h2>

    <p>
        <span class="account_label">ชื่อ</span><?php echo $form_data['first_name']; ?>
    </p>
    <p>
        <span class="account_label">นามสกุล</span> <?php echo $form_data['last_name']; ?>
    </p>
    <p class="clearfix">
        <span  class="account_label">เพศ</span> <?php echo $form_data['sex']; ?>
    </p>
    <p class="clearfix">
        <span class="account_label">จังหวัด</span> <?php echo $form_data['province_name']; ?>
    </p>
    <p class="clearfix">
        <span class="account_label">โรงเรียน</span> <?php echo $form_data['school_name']; ?>
    </p>
    <p class="clearfix">
        <span class="account_label">ชั้น</span> <?php echo $form_data['degree_name']; ?>
    </p>
    <p class="clearfix">
        <span class="account_label">เบอร์โทรศัพท์</span> <?php echo $form_data['phone_number']; ?>
    </p>


    <h2 class="head1">ที่อยู่อีเมล์</h2>

    <p class="clearfix"><span class="account_label">ที่อยู่อีเมล์</span> <?php echo $form_data['email']; ?> </p>


</div>
<?php if ($make_money) { ?>
    <div class="grid_3">
        <h2  class="head1">จำนวนเงิน</h2>
        <p> <span  class="account_label">เงินคงเหลือ</span class="account_label"> <?php echo number_format($form_data['money'], 2); ?> <b>บาท</b> </p>
        <p> <span  class="account_label">เงินโบนัส</span class="account_label"> <?php echo number_format($form_data['money_bonus'], 2); ?> <b>บาท</b> </p>
        <p> <span  class="account_label">เงินรวม</span class="account_label"> <?php echo number_format($form_data['money'] + $form_data['money_bonus'], 2); ?> <b>บาท</b> </p>


    </div>
<?php } ?>
<style>
    .account_label{
        display: block;
        float: left;
        width: 90px;
        font-weight: bold;
        margin-left: 10px;

    }
    #avatar-img{
        display: block;

        width: 124px;
        height: 124px;
        border: solid 1px #CCCCCC;
        margin-bottom: 10px;
        margin-left: auto;
        margin-right: auto;
        padding: 5px;
        border-radius: 2px;
        background-color: #ffffff;
    }
    #browse-avatar{
        display: none;
    }
    #btn-submit-browse-avatar{
        display: none;
    }    
    #btn-upload-avatar-img{

    }


</style>






