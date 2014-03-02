<div class="grid_12">
    <h1>แจ้งการโอนเงิน</h1>
</div>
<div class="hr-940px grid_12 "></div>
<div class="grid_6">
    <form id="normalform" action="<?php echo $form_action; ?>" method="post" >

        <p>
            <label for="money_transfer" class="grid_2">จำนวนเงินโอน <span class="important">*</span><span class="less-important">(required)</span></label>
            <input required type="text" id="money_transfer" name="data[money_transfer]" value=""  maxlength="7" >
        </p>
        <p>
            <label for="transfer_date" class="grid_2">วันที่โอน <span class="important">*</span><span class="less-important">(required)</span></label>
            <input required  type="text" id="transfer_date" name="data[transfer_date]" value="">
        </p>
        <p>
            <label for="transfer_time" class="grid_2">เวลาที่โอน <span class="important">*</span><span class="less-important">(required)</span></label>
            <input  required type="text" id="transfer_time" name="data[transfer_time]" value="" >
        </p>
        <p>
            <label for="ref_no" class="grid_2">เลขที่อ้างอิง </label>
            <input type="text" id="ref_no" name="data[ref_no]" value="">
        </p>
        <p>
            <label for="desc" class="grid_2">หมายเหตุ </label>

            <textarea id="desc" name="data[desc]" ></textarea>
        </p>



        <input type="button" value="แจ้งโอนเงิน" id="btnSubmit" class="btn-submit" onclick="do_submit()" >

        <a href="<?php echo $cancel_link; ?>" class="btn-a">ยกเลิก</a>

    </form>

</div>


<script>
    var request_search = null;
    var error_msg = [];
    function do_submit(){
        if(check_valid_form()){
            $("#normalform").submit();
        }else{
            alert(error_msg.join("\n"));
        }
    }
    function check_valid_form(){
        error_msg = ["=== โปรดกรอกข้อมูล ==="];
        
        bvalid = true;
        if($("#money_transfer").val() < 1 ){
            bvalid &= false;
            error_msg.push("- จำนวนเงินโอน");
        }
        if($("#transfer_date").val() < 1 ){
            bvalid &= false;
            error_msg.push("- วันที่โอน");
        }
        if($("#transfer_time").val() < 1 ){
            bvalid &= false;
            error_msg.push("- เวลาที่โอน");
        }
        return bvalid;
    }
    function btn_search_click(){
            
    }
    jQuery(function(){
        // init input
        $("#money_transfer,#money").typeonly("01234567890.");
        
        $("#money_transfer").val();
        $('#transfer_time').timepicker(
        {
            showNowButton: true
        }).setMask('29:59').val('');
        
        $('#transfer_date').datepicker({
            showButtonPanel: true
        });
    });
</script>







