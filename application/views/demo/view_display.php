<h1><?php echo $tbl_name; ?></h1>
<div class="grid_12">
    <?php
    foreach ($table_list as $k => $tbl) {
        echo anchor('demo/view_display/' . $k, $tbl, 'class="btn-a-small"');
    }
    ?>
    <?php echo $table; ?>  
</div>