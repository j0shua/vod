<input type="button" value="แทรกวิดีโอที่เลือกที่เลือก" id="btn_add_resource" class="btn-a" style="margin-bottom: 5px;" >
<div style="margin-top: 5px;">
    <?php echo form_dropdown('qtype', $qtype_options, 'resource_id', 'id="qtype"'); ?>
    <input id="query" type="text" name="query">
    <input type="button" id="btn_search" class="btn-a-small" value="กรอง"> 
</div>
<div class="">
    <table id="main-table" style="display: none"></table>
</div>
