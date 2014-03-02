<h1 class="main-title">แก้ไขข้อมูลบัญชีธนาคาร</h1>


<div class="grid_4" >


    <h2  class="head1">ข้อมูลบัญชีธนาคาร</h2>
    <form class="normal-form" method="post" action="<?php echo $form_action; ?>" id="normalform">
        <input type="hidden" name="cancel_url" value="<?php echo $cancel_url; ?>">
        <p>
            <label for="bank_id" >ธนาคาร</label>
            <?php
            echo form_dropdown('form_data[bank_id]', $bank_options, $form_data['bank_id']);
            ?>
        </p>

        <p>
            <label for="bank_branch_name" >สาขา</label>
            <input id="bank_branch_name" type="text" name="form_data[bank_branch_name]" value="<?php echo $form_data['bank_branch_name']; ?>">
        </p>

        <p>
            <label for="bank_account_number" >หมายเลขบัญชี</label>
            <input id="bank_account_number" type="text" name="form_data[bank_account_number]" value="<?php echo $form_data['bank_account_number']; ?>">
        </p>


        <p>
            <label for="bank_account_name" >ชื่อบัญชี</label>
            <input id="bank_account_number" type="text" name="form_data[bank_account_name]" value="<?php echo $form_data['bank_account_name']; ?>">
        </p>
        <input type="submit" class="btn-submit" id="form_submit" name="submit" value="บันทึก">
        <a class="btn-a" href="<?php echo $cancel_url; ?>">ยกเลิก</a>
    </form>
</div>


<style>
    .account_label{
        display: block;
        float: left;
        width: 90px;
        font-weight: bold;
        margin-left: 10px;

    }
    #avatar-img{ 
        display: block;

        width: 124px;
        height: 124px;
        border: solid 1px #CCCCCC;
        margin-bottom: 10px;
        margin-left: auto;
        margin-right: auto;
        padding: 5px;
        border-radius: 2px;
        background-color: #ffffff;
    }
    #browse-avatar{
        display: none;
    }
    #btn-submit-browse-avatar{
        display: none;
    }    
    #btn-upload-avatar-img{

    }


</style>
<script>
    $(function() {
        $("#avatar-img,#btn-upload-avatar-img").click(function() {
            $("#browse-avatar").click();
        });
        $("#browse-avatar").change(function() {
            $("#form-main").submit();
        });
    });
</script>






