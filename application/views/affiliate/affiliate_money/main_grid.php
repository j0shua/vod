    <h1 class="main-title">Affiliate Programe</h1>
<div class="hr-940px grid_12 "></div>
<?php echo $site_menu; ?>
<div class="container_12 grid_10">

    <h2 class="main-title">รายชื่อ Downline</h2>
    <div class="border" style="padding-bottom: 20px;">

        <input id="query-date-from" type="text" name="from" value="<?php echo date('d/m/Y', $date_from_stamp); ?>">
        <input id="query-date-to" type="text" name="to" value="<?php echo date('d/m/Y', $date_to_stamp); ?>">
        <input type="button" id="btn_search" value="กรอง"> 
    </div>
    <table id="main-table" style="display: none"></table>

</div>
<script>
    var date_query = "<?php echo $date_query; ?>";
    var ajax_grid_url = "<?php echo $ajax_grid_url; ?>";
</script>

