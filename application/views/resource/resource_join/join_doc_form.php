<div class="grid_12">
    <h1 class="main-title"><?php echo $title; ?></h1>
</div>

<div class="grid_9">
    <form id="normalform" action="<?php echo $form_action; ?>" method="post" >
        <input id="resource_id_video" type="hidden" name="data[resource_id_video]" value="<?php echo $resource_id_video; ?>" > 
        <p>
            <label for="resource_id_doc" class="grid_2">เลขที่สื่อ </label>
            <input type="text"  id="resource_id_doc" name="data[resource_id_doc]" value="<?php echo $resource_id_doc; ?>" style="width: 465px;">
        </p>

        <input type="button" value="บันทึก" id="btnSubmit" class="btn-submit" >

        <a href="<?php echo $cancel_link; ?>" class="btn-a">ยกเลิก</a>
    </form>

</div>
<script>
    $(function(){
        $("#resource_id_doc").typeonly("0123456789,");
        $("#btnSubmit").click(function(){
            $("#normalform").submit();
        });
    });
</script>

