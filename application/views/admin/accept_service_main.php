<div class="grid_12">
    <h1>System Setting</h1>
</div>
<?php echo $side_menu; ?>

<div class="container_12 grid_10">
    <div class="grid_10">
    <form id="normalform">
        <p>
            <label for="role-selection" class="grid_2">ประเภทสมาชิก </label>
            <?php
            echo form_dropdown('role', $role_options, '', 'id="role-selection" class="styled-select"');
            ?>
        </p>
        <input type="button" value="กรอง" name="filter" id="btn-filter" class="btn-submit">
    </form>

        <div class="clearfix"></div>

    
    <a href="<?php echo $add_link; ?>" class="btn-submit">เพิ่ม</a>

    <table id="main-table" style="display: none"></table>
    </div>

</div>

