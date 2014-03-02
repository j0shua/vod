<h1><?php echo $title; ?></h1>

<div class="grid_12">
    <form class="normal-form" id="main_form" action="<?php echo $form_action; ?>" method="post" >
        <input id="coupon_type" type="hidden" name="data[coupon_type]" value="<?php echo $form_data['coupon_type']; ?>">

        <p>
            <label for="amount">จำนวนรหัสหนังสือ </label>
            <input type="text" id="amount" name="data[amount]" value="" >
        </p>

        <p>
            <label for="money">จำนวนเงิน </label>
            <input type="text" id="money_bonus" name="data[money_bonus]" value="<?php echo $form_data['money_bonus']; ?>" >
            <input type="hidden" id="money" name="data[money]" value="<?php echo $form_data['money']; ?>" >
        </p>
        <input type="submit" value="บันทึก" id="btnSubmit" class="btn-submit" >

        <a href="<?php echo $cancel_link; ?>" class="btn-a">ยกเลิก</a>

    </form>

</div>






