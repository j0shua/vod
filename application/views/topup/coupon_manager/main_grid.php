<?php echo $main_side_menu; ?>
<div class="grid_10">
    <h2 class="head1">{title}</h2>
    <div class="clearfix">
        <?php
        foreach ($grid_menu as $btn) {
            echo anchor($btn['url'], $btn['title'], 'class="btn-a" ' . $btn['extra']);
        }
        ?>
        <table id="main-table" style="display: none"></table>
    </div>
</div>