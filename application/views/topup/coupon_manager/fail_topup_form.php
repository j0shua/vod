<h1><?php echo $title; ?></h1>
<div class="grid_12">
    <form class="normal-form" id="main-form" action="<?php echo $form_action; ?>" method="post" >
        <input id="cfid" type="hidden" name="form_data[cfid]" value="<?php echo $form_data['cfid']; ?>">
        <input id="uid" type="hidden" name="form_data[uid]" value="<?php echo $form_data['uid']; ?>">
        <p>
            <label for="uid">เลขที่ผู้บันทึกผิด </label>
            <input type="text" id="uid" value="<?php echo $form_data['uid']; ?>" disabled>
        </p>
        <p>
            <label for="user_fullname">ผู้บันทึกผิด </label>
            <input type="text" id="user_fullname" value="<?php echo $form_data['user_detail_data']['user_fullname']; ?>" disabled>
        </p>
         <p>
            <label for="phone_number">หมายเลขโทรศัพท์ </label>
            <input type="text" id="phone_number" value="<?php echo $form_data['user_detail_data']['phone_number']; ?>" disabled>
        </p>
        <p>
            <label for="user_fullname">บันทึกจาก </label>
            <input type="text" id="user_fullname" value="<?php echo $form_data['fail_from']; ?>" disabled>
        </p>

        <p>
            <label for="coupon_code_fail">รหัสคูปองที่ผิดพลาด </label>
            <input type="text" id="coupon_code_fail" name="form_data[coupon_code_fail]" value="<?php echo $form_data['coupon_code_fail']; ?>" >
        </p>

        <p>
            <label for="coupon_code">รหัสคูปองใหม่ </label>
            <input type="text" id="coupon_code" name="form_data[coupon_code]" value="" >
        </p>


        <input type="submit" value="บันทึก" id="btnSubmit" class="btn-submit" >

        <a href="<?php echo $cancel_link; ?>" class="btn-a">ยกเลิก</a>

    </form>

</div>





