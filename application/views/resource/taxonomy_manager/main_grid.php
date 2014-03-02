<?php echo $main_side_menu; ?>
<div class="grid_10">
    <h2 class="head1"><?php echo $title; ?></h2>


    <div class="clearfix">
        <?php
        foreach ($grid_menu as $btn) {
            echo anchor($btn['url'], $btn['title'], 'class="btn-a" ' . $btn['extra']);
        }
        ?>
        <p>
            <span class="messages warning" style="margin-top: 5px;">หลังจากเพิ่มชุดวิดีโอแล้วโปรดเพิ่มบทในชุดวิดีโอนั้นโดยคลิ๊กที่ บทในชุดวิดีโอ ในตารางด้านล่างนี้</span>
        </p>
    </div>

    <table id="main-table" style="display: none"></table>
</div>
<script>
    var ajax_grid_url = "<?php echo $ajax_grid_url; ?>";
</script>


