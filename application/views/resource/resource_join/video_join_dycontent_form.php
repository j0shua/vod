<h1><?php echo $title; ?></h1>

<div class="grid_9">
    <form id="main-form" class="normal-form" action="<?php echo $form_action; ?>" method="post" >
        <input id="resource_id_video" type="hidden" name="data[resource_id_video]" value="<?php echo $resource_id_video; ?>" > 
        <p>
            <label for="resource_id_dycontent" class="grid_2">เลขที่เอกสาร </label>
            <input type="text"  id="resource_id_dycontent" name="data[resource_id_dycontent]" value="<?php echo $resource_id_dycontent; ?>" style="width: 465px;">
        </p>

        <input type="submit" value="บันทึก" id="btnSubmit" class="btn-a" >

        <a href="<?php echo $cancel_link; ?>" class="btn-a">ยกเลิก</a>
    </form>

</div>
<script>
    $(function() {
        $("#resource_id_dycontent").typeonly("0123456789,");
        $("#main-form").submit(function() {
            return true;
        });
    });
</script>

