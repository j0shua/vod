
<?php echo $main_side_menu; ?>
<div class="grid_10">
    <h2 class="head1"><?php echo $title; ?></h2>
    <fieldset class="grid-fieldset">
        <?php echo form_dropdown('qtype', $qtype_options, 'title', 'id="qtype"'); ?>
        <input id="query" type="text" name="query">
        <input class="btn-a-small" type="button" id="btn_search" value="กรอง"> 
    </fieldset>
    <div class="flexigrid_wrap">
        <table id="main-table" style="display: none"></table>
    </div>
</div>
