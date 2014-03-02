

<h1><?php echo $title; ?></h1>


<div class="grid_5">
    <form id="main-form" class="normal-form" action="<?php echo $form_action; ?>" method="post" >
        <input type="hidden" id="dq_id" name="data[dq_id]" value="<?php echo $form_data['dq_id']; ?>" >
        <h2  class="head1">รายละเอียดบริการพื้นที่</h2>
        <p>
            <label for="full_name" >ผู้รับบริการ </label>
            <input type="text" id="full_name"  value="<?php echo $form_data['user_data']['full_name']; ?>"readonly="readonly" >
            <input type="hidden" id="uid_customer" name="data[uid_customer]" value="<?php echo $form_data['uid_customer']; ?>" >

        </p>
        <p>
            <label for="price" >ค่าบริการ </label>
            <input type="text" id="price" name="data[price]" value="<?php echo $form_data['price']; ?>" >
        </p>
        <p>
            <label for="value_mb" >ขนาดพื้นที่ MB </label>
            <input type="text" id="value_mb" name="data[value_mb]" value="<?php echo $form_data['value_mb']; ?>" >
        </p>
        <?php
        $date = new DateTime();
        ?>
        <p>
            <label for="start_time" >วันที่เริมต้น </label>
            <input type="text" id="start_time" name="data[start_time]" value="<?php echo $form_data['start_time_text']; ?>">
        </p>
        <p>
            <label for="end_time" >วันที่สิ้นสุด </label>
            <input type="text" id="end_time" name="data[end_time]" value="<?php echo $form_data['end_time_text']; ?>" >
        </p>
        <p>
            <label for="days" >จำนวนวัน </label>
            <input type="text" id="days" readonly>
        </p>
         <p>
            <label for="is_active" >การให้บริการ </label>
            <?php echo form_dropdown('data[is_active]', $is_active_options, $form_data['is_active'], 'id="is_active"'); ?>
        </p>

        <input type="submit" value="บันทึกข้อมูล" id="btnSubmit" class="btn-a" >


        <a href="<?php echo $cancel_link; ?>" class="btn-a">ยกเลิก</a>

    </form>

</div>


<script>


    $(function() {

        $("#price,#value_mb").typeonly("01234567890");
        $("#main-form").submit(function() {
            $("#price").val($.trim($("#price").val()));
            if ($("#price").val() == '') {
                alert("กรอกค่าบริการ");
                return false;
            }
            return true;
        });
// date -----------------------------------------

        $("#start_time").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 3,
            showButtonPanel: true,
            onClose: function(selectedDate) {
                $("#end_time").datepicker("option", "minDate", selectedDate);
                update_days();
            }
        });
        $("#end_time").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 3,
            showButtonPanel: true,
            onClose: function(selectedDate) {
                $("#start_time").datepicker("option", "maxDate", selectedDate);
                update_days();
            }
        });

// GET TEMPLATE ---------------------------
        $("#dqt_id").change(function() {
            request_search = $.ajax({
                url: ajax_get_template_disk_quota_url,
                type: "POST",
                data: {
                    dqt_id: $("#dqt_id").val()
                },
                dataType: "json"
            });
            request_search.done(function(json) {
                console.log(json);
                $("#price").val(json.price);
                $("#value_mb").val(json.value_mb);
                $("#price").val(json.price);

                var st = $("#start_time").datepicker("getDate");
                $("#end_time").datepicker("setDate", st);
                $("#end_time").datepicker("setDate", "+" + json.days + "d");
                update_days();

            });
        });
        function update_days() {
            var st = $("#start_time").datepicker("getDate");
            var et = $("#end_time").datepicker("getDate");
            var days = (et - st) / 86400000;
            $("#days").val(days);
        }
        update_days();

    });
</script>







