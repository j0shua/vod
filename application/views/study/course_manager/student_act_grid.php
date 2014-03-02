
<?php echo $main_side_menu; ?>
<div class="grid_10">
    <h2 class="head1"><?php echo $title1; ?></h2>
    <p> <span class="messages detail" >
            <b>สั่งงานเมื่อ : </b><?php echo $act_data['create_time_text']; ?> |
            <b>ส่งโดย : </b><?php echo $act_data['send_type']; ?> 
        </span></p>
    <?php if ($open_sheet_url) { ?>
        <p>
            <?php echo anchor($open_sheet_url, 'เปิดดูใบงาน', 'class="btn-a-small" target="_blank"'); ?>
        </p>
    <?php } else { ?>
        <p>
            <span class="messages detail" >
                <b>รายละเอียดงาน</b><br>
                <?php echo nl2br($act_data['data']); ?></span>
        </p>

    <?php } ?>
    <p>
        <span class="messages warning">กำหนดส่ง : <?php echo $act_data['deadline']; ?></span>
    </p>
    <h2 class="head1"><?php echo $title2; ?></h2>
    <?php
    foreach ($grid_menu as $btn) {
        echo anchor($btn['url'], $btn['title'], 'class="btn-a" ' . $btn['extra']);
    }
    ?>
    <table id="main-table" style="display: none"></table>
</div>

