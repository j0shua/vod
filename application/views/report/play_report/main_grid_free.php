<?php echo $main_side_menu; ?>
<div class="grid_10">
    <h2 class="head1"><?php echo $title; ?></h2>

    <div>
        <?php echo form_dropdown('is_end', array('1' => 'เรียนเสร็จไปแล้ว', '0' => 'กำลังเรียนอยู่'), 1, 'id="query-is-end"'); ?>
        <input id="query-date-from" type="text" name="from" value="<?php echo date('d/m/Y', $date_from_stamp); ?>">
        <input id="query-date-to" type="text" name="to" value="<?php echo date('d/m/Y', $date_to_stamp); ?>">
        <input type="button" id="btn_search" value="กรอง" class="btn-a-small"> 
    </div>
    <div></div>
  
    <table id="main-table" style="display: none"></table>

</div>
<script>
    var date_query = "<?php echo $date_query; ?>";
</script>


