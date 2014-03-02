    <h1 class="main-title">{form_title}</h1>

<div class="hr-940px grid_12 "></div>
<div class="grid_9">
    <form class="normal-form" method="post" action="<?php echo $form_action; ?>" id="normalform" autocomplete="off">

            <p>
                <label for="coupon_code" style="width: 160px;">  รหัสหนังสือ หรือ รหัสคูปอง</label>
                <input type="text" required id="coupon_code" name="coupon_code" value="" maxlength="10">

            </p>
            <p>
                <input type="submit" class="btn-submit" id="btn_submit" name="submit" value="ตกลง">

            </p>

    </form>
</div>

