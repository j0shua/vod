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

<h1>เติมเงินแบบเร็ว</h1>


<div class="grid_5">
    <form id="main-form" class="normal-form" action="<?php echo $form_action; ?>" method="post" >
        <h2 class="head1">รายละเอียดการเติม</h2>

        <p>
            <label for="email" >เติมเงินให้ </label>
            <input type="text" id="email" name="data[email]" value=""readonly="readonly" >
            <input type="hidden" id="uid" name="data[uid]" value="" >

            <input type="button" value="ค้นหาสมาชิก" id="btn_search_user" class="btn-a-small"  >

        </p>
        <p>
            <label for="money_topup" >จำนวนเงินเติม </label>
            <input type="text" id="money" name="data[money_topup]" value="" maxlength="7">
        </p>


        <h2  class="head1">รายละเอียดการโอน</h2>
        <p>
            <label for="money_transfer" >จำนวนเงินโอน </label>
            <input type="text" id="money_transfer" name="data[money_transfer]" value=""  maxlength="7" >
        </p>
        <p>
            <label for="transfer_date" >วันที่โอน </label>
            <input type="text" id="transfer_date" name="data[transfer_date]" value="">
        </p>
        <p>
            <label for="transfer_time" >เวลาที่โอน </label>
            <input type="text" id="transfer_time" name="data[transfer_time]" value="" >
        </p>
        <p>
            <label for="ref_no" >เลขที่อ้างอิง </label>
            <input type="text" id="ref_no" name="data[ref_no]" value="">
        </p>
        <p>
            <label for="desc" >หมายเหตุ </label>

            <textarea id="desc" name="data[desc]" ></textarea>
        </p>



        <input type="submit" value="เติมเงิน" id="btnSubmit" class="btn-a" >

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
            <label for="search_email">email</label>
            <input type="text" name="search_email" id="search_email" value="" class="text ui-widget-content ui-corner-all" readonly="readonly" >

        </fieldset>
    </form>
</div>

<script>
    var request_search = null;
    
    $("#main-form").submit(function() {
        $("#email").val($.trim($("#email").val()));
        if ($("#email").val() == '') {
            alert("กรุณาค้นหาผู้ใช้ก่อน");
            return false;
        }
        return true;
    });

    $(function() {

        var allFields = $([]).add($("#search_query")).add($("#search_email")).add($("#search_email"));
        $("#money_transfer,#money").typeonly("01234567890.");

        $('#transfer_time').timepicker(
                {
                    showNowButton: true
                }).setMask('29:59').val('');

        $('#transfer_date').datepicker({
            showButtonPanel: true
        });

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
                        $("#email").val($("#search_email").val());
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
            request_search.done(function(msg) {
                console.log(msg)
                allFields.val("");
                if (msg.detect) {
                    $("#search_email").val(msg.user_data.email);
                    $("#search_uid").val(msg.user_data.uid);
                    $("#search_email").val(msg.user_data.email);

                } else {
                    //$("#search_query").focusout();
                    $("#btn_ajax_search_user").focus();
                    alert("ไม่พบข้อมูล");

                }

            });
        }





    });
</script>







