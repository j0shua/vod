    <h1 class="main-title">จัดการสื่อ</h1>
<div class="hr-940px grid_12 "></div>
<?php echo $main_side_menu; ?>
<div class="container_12 grid_10">
    <h2 class="main-title">เอกสารประกอบการเรียน</h2>
    <div>
        <a href="<?php echo $add_latex_sheet_link; ?>" class="btn-submit">สร้างใบงาน LaTex</a>
        <a href="<?php echo $add_latex_sheet_link; ?>" class="btn-submit">สร้างใบงาน HTML</a>
        <a href="<?php echo $add_latex_sheet_link; ?>" class="btn-submit">สร้างใบงาน BBcode</a>
        <a href="<?php echo $add_latex_sheet_link; ?>" class="btn-submit">สร้างใบงานจาก เอกสารที่อัพโหลด</a>
    </div>
    <table id="main-table" style="display: none"></table>
</div>
<script>
    var ajax_grid_url = "<?php echo $ajax_grid_url; ?>";
</script>

