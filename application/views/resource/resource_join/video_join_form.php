<h1><?php echo $title; ?></h1>

<div class="grid_9">
    <form id="main-form" class="normal-form" action="<?php echo $form_action; ?>" method="post" >
        <input id="resource_id_video" type="hidden" name="data[resource_id_video]" value="<?php echo $resource_id_video; ?>" > 
        <p>
            <label for="resource_id" style="width: 250px;" >เลขที่เอกสาร/โจทย์/เนื้อหา</label>
            <input type="text"  id="resource_id" name="data[resource_id]" value="<?php echo $resource_id; ?>" style="width: 250px;">
            <a id="btn_search_resource_doc" href="#" class="btn-a-small">ค้นหาเอกสาร</a>
            <a id="btn_search_resource_dycontent" href="#" class="btn-a-small">ค้นหาโจทย์/เนื้อหา</a>
        </p>


        <input type="submit" value="บันทึก" id="btnSubmit" class="btn-a" >

        <a href="<?php echo $cancel_link; ?>" class="btn-a">ยกเลิก</a>
    </form>

</div>
<div id="dialog" title="ค้นหาโจทย์/เนิ้อหา หรือ เอกสาร" style="display: none;" >
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
        $("#btn_search_resource_doc").click(function() {

            $("#inner-dialog").html('<iframe title="โปรดรอ" id="iframe-resource-browser" src="' + iframe_doc_manager_url + '">โปรดรอ</iframe>');
            $("#dialog").dialog({
            title: 'ค้นหา เอกสาร'
        });
            $("#dialog").dialog("open");
        });
        $("#btn_search_resource_dycontent").click(function() {
            $("#inner-dialog").html('<iframe title="โปรดรอ" id="iframe-resource-browser" src="' + iframe_dycontent_manager_url + '">โปรดรอ</iframe>');
             $("#dialog").dialog({
            title: 'ค้นหา โจทย์/เนื้อหา'
        });
            $("#dialog").dialog("open");
        });
    });
    function add_resource(array_resource_id) {
        $("#resource_id").val(array_resource_id.join(","));
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


