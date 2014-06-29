<h1 class="main-title">ข้อมูลส่วนตัว</h1>


<div class="grid_3 clearfix" >
    <h2 class="head1">รูปประจำตัว</h2>

    <script>
        $(function() {
            $(".various").fancybox({
                maxWidth: 800,
                maxHeight: 600,
                fitToView: true,
                width: 700,
                height: 320,
                autoSize: false,
                closeClick: false,
                openEffect: 'none',
                closeEffect: 'none',
                scrolling: 'no',
                afterClose: function() {
                    location.reload();

                }
            });
        });
    </script>
    <img id="avatar-img" class="clearfix"  title="รูปประจำตัว" src="<?php echo site_url('ztatic/img_avatar_128'); ?>">
    <?php if ($make_money) { ?>
        <a class="various btn-a-small" data-fancybox-type="iframe" href="<?php echo $swf_upload_avatar_url; ?>">ถ่ายภาพ</a>
    <?php } ?>
    <input id="btn-upload-avatar-img" type="button" class="btn-a-small" value="อัพโหลดรูปใหม่" />

    <form id="form-main" action="<?php echo site_url("user/do_upload_avatar"); ?>" method="POST" enctype="multipart/form-data">

        <p>
            <input id="browse-avatar" type="file" name="userfile" value="" accept="image/*" />
        </p>
        <p>
            <input id="btn-submit-browse-avatar" type="submit" value="อัพโหลดภาพใหม่" />
        </p>
    </form>
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
        <?php if ($form_data['rid'] != 3) { ?>
            <span class="account_label">ชั้น</span> <?php echo $form_data['degree_name']; ?>
        <?php } else { ?>
            <span class="account_label">สอนชั้น</span> <?php echo $form_data['degree_name']; ?>
        <?php } ?>
    </p>
    <p class="clearfix">
        <span class="account_label">เบอร์โทรศัพท์</span> <?php echo $form_data['phone_number']; ?>
    </p>
    <p class="clearfix">
        <a href="<?php echo $edit_profile_url; ?>" class="btn-a">แก้ไขข้อมูลพื้นฐาน</a>
    </p>
    <?php if ($make_money) { ?>
        <h2 class="head1">ที่อยู่อีเมล์</h2>

        <p class="clearfix"><span class="account_label">ที่อยู่อีเมล์</span> <?php echo $form_data['email']; ?> <a href="<?php echo $edit_email_url; ?>" class="btn-a-small">แก้ไข</a></p>
    <?php } else { ?>
        <h2 class="head1">ที่อยู่อีเมล์</h2>

        <p class="clearfix"><span class="account_label">ที่อยู่อีเมล์</span> <?php echo $form_data['email']; ?> </p>
    <?php } ?>
    <h2 class="head1">รหัสผ่าน</h2>

    <p class="clearfix"><a href="<?php echo $edit_password_url; ?>" class="btn-a">เปลี่ยนรหัสผ่าน</a></p>
</div>
<?php if ($form_data['rid'] == 3) { ?>
    <div class="grid_3">
        <h2  class="head1">ข้อมูลเอกสารสำคัญ</h2>
        <p>
            <span  class="account_label" style="width: 225px;">รูปถ่ายบัตรข้าราชการ/บัตรประชาชน</span> <a href="<?php echo site_url('user/personal_document/identity_document') ?>" class="btn-a-small">เปิดดู</a>
        </p>
        <p>
            <span  class="account_label" style="width: 225px;">รูปถ่ายใบรับรองวิชาชีพครู</span> <a href="<?php echo site_url('user/personal_document/educational_document') ?>" class="btn-a-small">เปิดดู</a>
        </p>
    </div>
<?php } ?>
<?php if ($make_money) { ?>
    <div class="grid_3">
        <h2  class="head1">จำนวนเงิน</h2>
        <p> <span  class="account_label">เงินคงเหลือ</span class="account_label"> <?php echo number_format($form_data['money'] + $form_data['money_bonus'], 2); ?> <b>บาท</b> </p>


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
<script>
    $(function() {
        $("#avatar-img,#btn-upload-avatar-img").click(function() {
            $("#browse-avatar").click();
        });
        $("#browse-avatar").change(function() {
            $("#form-main").submit();
        });
    });
</script>






