<div id="div_owner"><label>ใบงานของ</label> <input id="owner_full_name" type="text" value="<?php echo $owner_full_name; ?>"></div>

<div>
    <div style="margin-top: 5px; margin-bottom:10px;">
        <?php echo form_dropdown('qtype', $qtype_options, 'resource_id', 'id="qtype"'); ?>
        <input id="query" type="text" name="query">
        <input type="button" id="btn_search" class="btn-a-small" value="กรอง"> 
    </div>
    <table id="main-table" style="display: none"></table>
</div>