<h1><?php echo $title; ?></h1>

<div class="grid_9">
    <form id="main-form" class="normal-form" action="<?php echo $form_action; ?>" method="post" >
        <input id="resource_id" type="hidden" name="data[resource_id]" value="<?php echo $resource_id; ?>" > 
        <p>
            <label for="resource_id_video" class="grid_2">เลขที่วิดีโอ </label>
            <input type="text"  id="resource_id_video" name="data[resource_id_video]" value="<?php echo $resource_id_video; ?>" style="width: 465px;">
            <a id="btn-search-resource" href="#" class="btn-a-small">ค้นหาวิดีโอ</a>
        </p>

        <input type="submit" value="บันทึก" id="btnSubmit" class="btn-a" >

        <a href="<?php echo $cancel_link; ?>" class="btn-a">ยกเลิก</a>
    </form>

</div>
<div id="dialog" title="ค้นหาวิดีโอ" style="display: none;" >
    <div id="inner-dialog"></div>
</div>
<script>
    $(function() {
        $("#resource_id_video").typeonly("0123456789,");
        $("#main-form").submit(function() {
            return true;
        });
        $("#dialog").dialog({
            autoOpen: false,
            modal: true,
            width: 1150,
            height: 700
        });
        $("#btn-search-resource").click(function() {
            $("#inner-dialog").html('<iframe title="โปรดรอ" id="iframe-resource-browser" src="' + iframe_video_manager_url + '">โปรดรอ</iframe>');
            $("#dialog").dialog("open");
        });
    });
    function add_resource_id(id, array_resource_id) {
        $("#" + id).val(array_resource_id.join(","));
        $("#dialog").dialog("close");
    }
</script>
<style>
    #iframe-resource-browser{
        width: 100%; 
        height: 650px;
        overflow: hidden;
    }
</style>

