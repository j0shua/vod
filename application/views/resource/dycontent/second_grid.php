<?php echo $main_side_menu; ?>
<div class="grid_10">
    <h2 class="head1"><?php echo $title; ?></h2>
    <div class="clearfix">
        <?php
        foreach ($grid_menu as $btn) {
            echo anchor($btn['url'], $btn['title'], 'class="btn-a" ' . $btn['extra']);
        }
        ?>
    </div>
    <fieldset class="grid-fieldset"><legend>กรอง</legend>
         <?php echo form_dropdown('content_type_id', $content_type_options, '', 'id="content_type_id"'); ?>
        <?php echo form_dropdown('qtype', $qtype_options, 'title', 'id="qtype"'); ?>
        <input id="query" type="text" name="query">
        <input type="button" id="btn_search" value="กรอง"> 
    </fieldset>
    <fieldset class="grid-fieldset"><legend>กระทำกับข้อมูล</legend>
        <?php echo form_dropdown('dd_act_grid', $command_to_resource_options, '', 'id="dd_act_grid"') ?>

        <input  class="btn-a-small"  type="button" id="btn_act_grid" value="กระทำกับที่เลือก"> 
    </fieldset>
    <table id="main-table" style="display: none"></table>

</div>

<style>
    .flexigrid div.bDiv td {
        white-space: pre-wrap;
    }

</style>


