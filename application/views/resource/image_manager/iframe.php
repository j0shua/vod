<a href="<?php echo $upload_url; ?>" class="btn-a" id="btn-upload-img">อัพโหลด ภาพ</a>
<div style="margin-top: 5px;margin-bottom: 5px;">
    <?php echo form_dropdown('qtype', $qtype_options, 'title', 'id="qtype"'); ?>
    <input id="query" type="text" name="query">
    <input class="btn-a-small" type="button" id="btn_search" value="กรอง"> 
</div>
<div class="clearfix"></div>
<div class="flexigrid_wrap">
    <table id="main-table" style="display: none"></table>
</div>

