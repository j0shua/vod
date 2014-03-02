<?php echo $main_side_menu; ?>
<div class="container_12 grid_10">
    <h2 class="head1"><?php echo $title; ?></h2>
    <div>
        <?php
        foreach ($grid_menu as $btn) {
            echo anchor($btn['url'], $btn['title'], 'class="btn-a" ' . $btn['extra']);
        }
        ?>
    </div>
    <table id="main-table" style="display: none"></table>
</div>



<script>
    var ajax_grid_url = "<?php echo $ajax_grid_url; ?>";
</script>



