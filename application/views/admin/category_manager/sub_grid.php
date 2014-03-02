<div class="grid_12">
    <h1>System Setting</h1>
</div>
<?php echo $side_menu; ?>
<div class="container_12 grid_10">
    <div class="grid_10">
        <h2>Category Manager [หมวดย่อย : <?php echo $parent_title; ?>]</h2>

        <form id="normalform">
        </form>
        
        <a href="<?php echo $back_link; ?>" class="btn-submit">Back</a>
        <a href="<?php echo $add_link; ?>" class="btn-submit">Add</a>

        <table id="main-table" style="display: none"></table>
    </div>
</div>
<script>
var parent_id = <?php echo $parent_id; ?>;
</script>


