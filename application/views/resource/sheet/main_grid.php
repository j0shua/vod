    
<?php echo $main_side_menu; ?>
<div class="grid_10">
    <h2 class="head1"><?php echo $title; ?> <?php echo anchor('http://www.youtube.com/embed/LxrceI0QEmA?rel=0&wmode=transparent&autoplay=1', "วิดีโอสอนการใช้งาน", 'class="youtube btn-a" target="_blank"'); ?></h2>
  
    <script>
        $(".youtube").colorbox({iframe: true, innerWidth: 640, innerHeight: 390, opacity: 0.5});
    </script>

    <div>
        <?php
        foreach ($grid_menu as $btn) {
            echo anchor($btn['url'], $btn['title'], 'class="btn-a" ' . $btn['extra']);
        }
        ?>
    </div>
    <div style="margin-top: 5px;">
        <?php echo form_dropdown('qtype', $qtype_options, 'resource_id', 'id="qtype"'); ?>
        <input id="query" type="text" name="query">
        <input type="button" id="btn_search" class="btn-a-small" value="กรอง"> 
    </div>
    <fieldset class="grid-fieldset"><legend>กระทำกับข้อมูล</legend>
        <?php echo form_dropdown('dd_act_grid', $command_to_resource_options, '', 'id="dd_act_grid"') ?>

        <input  class="btn-a-small"  type="button" id="btn_act_grid" value="กระทำกับที่เลือก"> 
    </fieldset>
    <div class="flexigrid_wrap">
        <table id="main-table" style="display: none"></table>
    </div>
</div>