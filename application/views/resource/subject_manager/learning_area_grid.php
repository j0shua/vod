<?php echo $main_side_menu; ?>
<div class="container_12 grid_10">
    <h2 class="head1"><?php echo $title; ?> <?php echo anchor('http://www.youtube.com/embed/TBDZaBefYgo?rel=0&wmode=transparent&autoplay=1', "วิดีโอสอนการใช้งาน", 'class="youtube btn-a" target="_blank"'); ?></h2>
    <script>
        $(".youtube").colorbox({iframe: true, innerWidth: 640, innerHeight: 390, opacity: 0.5});
    </script>
    <div class="clearfix">
        <?php
        foreach ($grid_menu as $btn) {
            echo anchor($btn['url'], $btn['title'], 'class="btn-a" ' . $btn['extra']);
        }
        ?>
    </div>
    <table id="main-table" style="display: none"></table>

</div>
<style>
    .flexigrid div.bDiv td {
        white-space: pre-wrap;
    }
</style>


