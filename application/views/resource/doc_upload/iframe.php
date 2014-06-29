<input type="button" value="แทรก เอกสาร ที่เลือก" id="btn_add_resource" class="btn-a" style="margin-bottom: 5px;" >
<fieldset style="margin-bottom: 10px;" class="grid-fieldset">
    <?php echo form_dropdown('qtype', $qtype_options, 'title', 'id="qtype"'); ?>
    <input id="query" type="text" name="query">
    <input class="btn-a-small" type="button" id="btn_search" value="กรอง"> 
</fieldset>
<div class="flexigrid_wrap">
    <table id="main-table" style="display: none"></table>
</div>

