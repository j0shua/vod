<input type="button" value="แทรก โจทย์/เนื้อหา ที่เลือก" id="btn_add_resource" class="btn-a" style="margin-bottom: 5px;" >
<div id="div_owner"><label>โจทย์/เนื้อหา ของ</label> <input id="owner_full_name" type="text" value="<?php echo $owner_full_name; ?>"></div>

<fieldset style="margin-bottom: 10px;" id="grid-fieldset">

    <?php echo form_dropdown('resource_level', $resource_level_options, '', 'id="resource_level"'); ?>
    <?php echo form_dropdown('content_type_id', $content_type_options, '', 'id="content_type_id"'); ?>
    <?php echo form_dropdown('qtype', $qtype_options, 'title', 'id="qtype"'); ?>
    <input id="query" type="text" name="query">
    <input class="btn-a-small" type="button" id="btn_search" value="กรอง"> 
</fieldset>
<div class="flexigrid_wrap">
    <table id="main-table" style="display: none"></table>
</div>
<style>
    #div_owner{
        margin-bottom: 10px;
        margin-top: 10px;
        margin-left: 10px;
    }
    #grid-fieldset{
        margin-bottom: 10px;

        margin-left: 10px;  
    }
</style>

