<?php echo $main_side_menu; ?>
<div class="grid_10">
    <h2 class="head1"><?php echo $title; ?></h2>
    <div class="clearfix">
        <p>
            <?php
            if (isset($grid_menu)) {
                foreach ($grid_menu as $btn) {
                    echo anchor($btn['url'], $btn['title'], 'class="btn-a" ' . $btn['extra']);
                }
            }
            ?>
        </p>
        <table id="main-table" style="display: none"></table>
    </div>

</div>

