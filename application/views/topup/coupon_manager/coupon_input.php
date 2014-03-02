<h1><?php echo $title; ?></h1>

<div class="grid_12">
    <form class="normal-form" id="normalform" action="<?php echo $form_action; ?>" method="post" >
        <input id="cid" type="hidden" name="data[cid]" value="<?php echo $form_data['cid']; ?>">
        <input id="coupon_type" type="hidden" name="data[coupon_type]" value="<?php echo $form_data['coupon_type']; ?>">

        <p>
            <label for="title">รหัสคูปอง </label>
            <input type="text" id="coupon_code" name="data[coupon_code]" value="" >
        </p>
        <p>
            <label for="money">จำนวนเงิน </label>
            <input type="text" id="money" name="data[money]" value="" >
            <input type="hidden" id="money_bonus" name="data[money_bonus]" value="" >
        </p>
        <p>
            <label for="reuse_number">ครั้งที่ใช้ได้ </label>
            <input type="text" id="reuse_number" name="data[reuse_number]" value="" >
        </p>

        <input type="submit" value="บันทึก" id="btnSubmit" class="btn-submit" >

        <a href="<?php echo $cancel_link; ?>" class="btn-a">ยกเลิก</a>

    </form>

</div>
<script>
<?php
if ($script_var) {
    foreach ($script_var as $script_k => $script_v) {
        echo 'var ' . $script_k . '="' . $script_v . '";';
    }
}
?>

</script>





