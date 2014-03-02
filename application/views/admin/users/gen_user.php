<h1><?php echo $title; ?></h1>
<div class="grid_12">
    <form class="normal-form" id="main-form" action="<?php echo $form_action; ?>" method="post" autocomplete="off">
        <p>
            <label for="prefix" >username prefix </label>
            <input type="text" id="prefix" name="prefix" value="">
        </p>
        <p>
            <label for="total">จำนวน </label>
            <input type="text" id="total" name="total" value="0">
        </p>
         <p>
            <label for="password">password </label>
            <input type="text" id="password" name="password" value="12345">
        </p>
        <input type="submit" value="สร้างรายชื่อ" name="filter" id="btn-filter" class="btn-submit">
        <a href="<?php echo $cancel_link; ?>" class="btn-a">ยกเลิก</a>
    </form>
</div>
<script>
    $(function() {
        $("#total").typeonly("0123456789");
        $("#main-form").submit(function() {
            $("#prefix").val($.trim($("#prefix").val()));
            if ($("#prefix").val() === '') {
                alert("โปรดใส่ username prefix ");
                return false;
            }

            if ($("#total").val() < 1) {
                alert("โปรดใส่ จำนวน ที่มีค่ามากกว่า 0 ");
                return false;
            }
            return true;
        });
    });
</script>

