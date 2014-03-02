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
<div class="grid_12">
    <h1>เติมเงินจากการแจ้ง</h1>
</div>
<div class="hr-940px grid_12 "></div>
<div class="grid_12">
    <form id="normalform" action="<?php echo $form_action; ?>" method="post" >
        <h4>รายละเอียดการเติม</h4>


        <input type="hidden" id="mt_id" name="data[mt_id]" value="<?php echo $inform_data['mt_id']; ?>" >



        </p>
        <p>
            <label for="money_topup" class="grid_2">จำนวนเงินเติม <span class="important">*</span><span class="less-important">(required)</span></label>
            <input type="text" id="money" name="data[money_topup]" value="<?php echo $inform_data['money_transfer']; ?>" maxlength="7">
        </p>


        <h4>รายละเอียดการโอน</h4> 
        <p>
            <label for="fullname_use" class="grid_2">เติมเงินให้ </label>
            <input type="text" id="fullname_use" name="data[fullname_use]" value="<?php echo $inform_data['fullname_use']; ?>" readonly="readonly" >
        </p>
        <p>
            <label for="money_transfer" class="grid_2">จำนวนเงินโอน </label>
            <input disabled type="text" id="money_transfer" name="data[money_transfer]" value="<?php echo $inform_data['money_transfer']; ?>"  maxlength="7" >
        </p>
        <p>
            <label for="transfer_date" class="grid_2">วันที่โอน</label>
            <input disabled type="text" id="transfer_date" name="data[transfer_date]" value="<?php echo $inform_data['transfer_date']; ?>">
        </p>
        <p>
            <label for="transfer_time" class="grid_2">เวลาที่โอน </label>
            <input disabled type="text" id="transfer_time" name="data[transfer_time]" value="<?php echo $inform_data['transfer_time']; ?>" >
        </p>
        <p>
            <label for="ref_no" class="grid_2">เลขที่อ้างอิง </label>
            <input type="text" id="ref_no" name="data[ref_no]" value="<?php echo $inform_data['ref_no']; ?>">
        </p>
        <p>
            <label for="desc" class="grid_2">หมายเหตุ </label>

            <textarea id="desc" name="data[desc]" ><?php echo $inform_data['desc']; ?></textarea>
        </p>



        <input type="button" value="เติมเงิน" id="btnSubmit" class="btn-submit" onclick="do_submit()" >

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
            <label for="search_username">username</label>
            <input type="text" name="search_username" id="search_username" value="" class="text ui-widget-content ui-corner-all" readonly="readonly" >
            <input type="hidden" name="search_uid" id="search_uid" value="" >
            <label for="search_email">email</label>
            <input type="text" name="search_email" id="search_email" value="" class="text ui-widget-content ui-corner-all" readonly="readonly" >

        </fieldset>
    </form>
</div>

<script>
    var request_search = null;
    var ajax_search_user_url = site_url("utopup/manual_topup/ajax_search_user");
    function do_submit(){
        $("#normalform").submit();
    }
    function check_valid_form(){
        
    }
    function btn_search_click(){
            
    }
    jQuery(function(){
    
        var allFields = $( [] ).add( $("#search_query") ).add( $("#search_username")).add( $("#search_email"));
        $("#money_transfer,#money").typeonly("01234567890.");
        
        $('#transfer_time').timepicker(
        {
            showNowButton: true
        }).setMask('29:59');
        
        $('#transfer_date').datepicker({
            showButtonPanel: true
        });
        
        $( "#dialog-form" ).dialog({
            autoOpen: false,
            height: 350,
            width: 350,
            modal: true,
            buttons: {
         
                "เลือกสมาชิก": function() {
                    if($("#search_username").val() == ""){
                        alert("กรุณาค้นหาผู้ใช้");
                    }else{
                        $("#username").val($("#search_username").val());
                        $("#uid").val($("#search_uid").val());
                        $( this ).dialog( "close" );
                    }
                },
                "ยกเลิก": function() {
                    $( this ).dialog( "close" );
                    allFields.val( "" );
                }
            },
            close: function() {
                //alert("");
                allFields.val( "" );
                
            }
        });
        $("#btn_search_user").click(function(){
            $( "#dialog-form" ).dialog( "open" );
        });
        
        $('#search_query').keyup(function(e) {
            //alert(e.keyCode);
            if(e.keyCode == 13) {
                ajax_search_user();
                
            }
        });
        $("#btn_ajax_search_user").click(function(){
            ajax_search_user();
        });
        
        function ajax_search_user(){
            request_search = $.ajax({
                url: ajax_search_user_url,        
                type: "POST",        
                data: {
                    query : $("#search_query").val()
                },
                dataType: "json"
            });
            request_search.done(function(msg) {
                console.log(msg)
                allFields.val( "" );
                if(msg.detect){
                    $("#search_username").val(msg.user_data.username);
                    $("#search_uid").val(msg.user_data.uid);
                    $("#search_email").val(msg.user_data.email);
                            
                }else{
                    //$("#search_query").focusout();
                    $("#btn_ajax_search_user").focus();
                    alert("ไม่พบข้อมูล");
                    
                }
                        
            });
        }
        
      
        
       
        
    });
</script>







