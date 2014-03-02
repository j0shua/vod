<h1><?php echo $title; ?></h1>
<div class="grid_12">
    <a class="btn-a" href="<?php echo site_url('play/play_resource/pdf_sheet/' . $resource_id); ?>">ดาวน์โหลด PDF</a>
    <div class="clearfix"></div>

    <?php
    echo $render;
    ?>
</div>