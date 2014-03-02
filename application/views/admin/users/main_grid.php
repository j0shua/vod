<?php echo $main_side_menu; ?>
<div class="grid_10">
    <h2 class="head1"><?php echo $title; ?></h2>
    <div class="clearfix">
        <label for="role_selection">ประเภทสมาชิก </label>
        <?php
        echo form_dropdown('role', $role_options, '', 'id="role_selection"');
        ?>
        <label for="active_selection">สถานะสมาชิก </label>
        <?php
        echo form_dropdown('active', $active_options, '', 'id="active_selection"');
        ?>
        <label for="search_text">ชื่อสมาชิก </label>
        <input type="text" name="search_text" id="search_text">
        <input type="button" value="กรอง" name="filter" id="btn-filter" class="btn-a-small">
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

