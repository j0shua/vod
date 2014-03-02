
<?php echo $main_side_menu; ?>
<div class="grid_10">
   
    <h2 class="head1"><?php echo $title; ?> <?php echo anchor('http://www.youtube.com/embed/R6zseXhg4z0?rel=0&wmode=transparent&autoplay=1', "วิดีโอสอนการใช้งาน", 'class="youtube btn-a" target="_blank"'); ?></h2>
  
    <script>
        $(".youtube").colorbox({iframe: true, innerWidth: 640, innerHeight: 390, opacity: 0.5});
    </script>
    <?php
    foreach ($grid_menu as $btn) {
        echo anchor($btn['url'], $btn['title'], 'class="btn-a" ' . $btn['extra']);
    }
    ?>
    <fieldset class="grid-fieldset">
        <?php echo form_dropdown('qtype', $qtype_options, 'title', 'id="qtype"'); ?>
        <input id="query" type="text" name="query">
        <input class="btn-a-small" type="button" id="btn_search" value="กรอง"> 
    </fieldset>
    <div class="flexigrid_wrap">
        <table id="main-table" style="display: none"></table>
    </div>
</div>