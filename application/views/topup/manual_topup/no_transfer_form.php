<div class="grid_12">
    <h1 class="main-title">ลบข้อมูลการโอน</h1>
</div>
<div class="hr-940px grid_12 "></div>
<div class="grid_12">
    <form id="normalform" action="<?php echo $form_action; ?>" method="post" >
        <input type="hidden" id="mt_id" name="data[mt_id]" value="<?php echo $inform_data['mt_id']; ?>" >

        <h4>รายละเอียดการโอน</h4> 
        <p>
            <label for="fullname_use" class="grid_2">ผู้แจ้ง </label>
            <input disabled  type="text" id="fullname_use" name="data[fullname_use]" value="<?php echo $inform_data['fullname_use']; ?>" readonly="readonly" >
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
            <input disabled  type="text" id="ref_no" name="data[ref_no]" value="<?php echo $inform_data['ref_no']; ?>">
        </p>
        <p>
            <label for="no_transfer_desc" class="grid_2">หมายเหตุ </label>

            <textarea id="desc" name="data[no_transfer_desc]" ></textarea>
        </p>



        <input type="button" value="ลบการโอน" id="btnSubmit" class="btn-submit" >

        <a href="<?php echo $cancel_link; ?>" class="btn-a">ยกเลิก</a>

    </form>

</div>
<script>
    $(function(){
        $("#btnSubmit").click(function(){
            $("#normalform").submit();
        });
    });
</script>







