<style>
    #dialog-form{display: none;}
    #dialog-form label,#dialog-form input { display:block; }
    #dialog-form input.text { margin-bottom:12px; width:95%; padding: .4em; }
    #dialog-form fieldset { padding:0; border:0; margin-top:25px; }
    #dialog-form h1 { font-size: 1.2em; margin: .6em 0; }
    #dialog-form  div#users-contain { width: 350px; margin: 20px 0; }
    #dialog-form div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
    #dialog-form div#users-contain table td, #dialog-form div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
    #dialog-form .ui-dialog .ui-state-error { padding: .3em; }
    #dialog-form .validateTips { border: 1px solid transparent; padding: 0.3em; }
    #dialog-form #search_query{width: 72%;float: left;}
    #dialog-form #btn_ajax_search_user{float: left;height: 31px;margin-left: 3px;}

</style>

<h1><?php echo $title; ?></h1>


<div class="grid_5">
    <form id="main-form" class="normal-form" action="<?php echo $form_action; ?>" method="post" >
        <input type="hidden" id="dq_id" name="data[dq_id]" value="<?php ?>" >
        <h2 class="head1">แลือกแม่แบบ</h2>
        <p>
            <label for="dqt_id" >แม่แบบ </label>
            <?php echo form_dropdown('', $disk_quota_template_options, '', 'id="dqt_id"'); ?>
        </p>
        <h2  class="head1">รายละเอียดบริการพื้นที่</h2>


        <p>
            <label for="full_name" >ผู้รับบริการ </label>
            <input type="text" id="full_name"  value=""readonly="readonly" >
            <input type="hidden" id="uid_customer" name="data[uid_customer]" value="" >

            <input type="button" value="ค้นหาสมาชิก" id="btn_search_user" class="btn-a-small"  >

        </p>
        <p>
            <label for="price" >ค่าบริการ </label>
            <input type="text" id="price" name="data[price]" value="" >
        </p>
        <p>
            <label for="value_mb" >ขนาดพื้นที่ MB </label>
            <input type="text" id="value_mb" name="data[value_mb]" value="" >
        </p>
        <?php
        $date = new DateTime();
        ?>
        <p>
            <label for="start_time" >วันที่เริมต้น </label>
            <input type="text" id="start_time" name="data[start_time]" value="<?php
            echo $date->format('d/m/Y');
            $date->modify('+1 day');
            ?>">
        </p>
        <p>
            <label for="end_time" >วันที่สิ้นสุด </label>
            <input type="text" id="end_time" name="data[end_time]" value="<?php echo $date->format('d/m/Y'); ?>" >
        </p>
        <p>
            <label for="days" >จำนวนวัน </label>
            <input type="text" id="days" readonly>
        </p>
        <p>
            <label for="paymoney" >การจ่ายเงิน </label>
            <?php echo form_dropdown('data[paymoney]', $paymoney_options, '', 'id="paymoney"'); ?>
        </p>




        <input type="submit" value="บันทึกข้อมูล" id="btnSubmit" class="btn-a" >


        <a href="<?php echo $cancel_link; ?>" class="btn-a">ยกเลิก</a>

    </form>

</div>
<div id="dialog-form" title="ค้นหา สมาชิก">

    <form>
        <fieldset>
            <label for="search_query">คำค้น</label>
            <input type="text" name="search_query" id="search_query" class="text ui-widget-content ui-corner-all" >
            <button type="button" id="btn_ajax_search_user" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false"><span class="ui-button-text">ค้นหา</span></button>
            <div class="clearfix"></div>

            <input type="hidden" name="search_uid" id="search_uid" value="" >
            <label for="search_full_name">ชื่อ</label>
            <input type="text" name="search_full_name" id="search_full_name" value="" class="text ui-widget-content ui-corner-all" readonly="readonly" >

            <label for="search_email">email</label>
            <input type="text" name="search_email" id="search_email" value="" class="text ui-widget-content ui-corner-all" readonly="readonly" >

        </fieldset>
    </form>
</div>

<script>


    $(function() {

        $("#price,#value_mb").typeonly("01234567890");
        $("#main-form").submit(function() {
            $("#email").val($.trim($("#email").val()));
            if ($("#email").val() == '') {
                alert("กรุณาค้นหาผู้ใช้ก่อน");
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

// SEARCH ---------------------------
        var allFields = $([]).add($("#search_query")).add($("#search_email")).add($("#search_email"));
        $("#dialog-form").dialog({
            autoOpen: false,
            height: 350,
            width: 350,
            modal: true,
            buttons: {
                "เลือกสมาชิก": function() {
                    if ($("#search_email").val() == "") {
                        alert("กรุณาค้นหาผู้ใช้");
                    } else {
                        $("#full_name").val($("#search_full_name").val());
                        $("#uid").val($("#search_uid").val());
                        $(this).dialog("close");
                    }
                },
                "ยกเลิก": function() {
                    $(this).dialog("close");
                    allFields.val("");
                }
            },
            close: function() {
                //alert("");
                allFields.val("");

            }
        });
        $("#btn_search_user").click(function() {
            $("#dialog-form").dialog("open");
        });

        $('#search_query').keyup(function(e) {
            //alert(e.keyCode);
            if (e.keyCode == 13) {
                ajax_search_user();

            }
        });
        $("#btn_ajax_search_user").click(function() {
            ajax_search_user();
        });

        function ajax_search_user() {
            request_search = $.ajax({
                url: ajax_search_user_url,
                type: "POST",
                data: {
                    query: $("#search_query").val()
                },
                dataType: "json"
            });
            request_search.done(function(json) {

                allFields.val("");
                if (json.detect) {
                    $("#search_email").val(json.user_data.email);
                    $("#search_uid").val(json.user_data.uid);
                    $("#search_email").val(json.user_data.email);
                    $("#search_full_name").val(json.user_data.full_name);

                } else {

                    $("#btn_ajax_search_user").focus();
                    alert("ไม่พบข้อมูล");

                }

            });
        }





    });
</script>







