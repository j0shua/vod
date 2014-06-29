<h1><?php echo $title; ?></h1>
<div class="grid_12">
    <?php if ($render_solve) { ?>
        <a class="btn-a" href="<?php echo site_url('play/play_resource/pdf_dycontent/' . $resource_id . '/1'); ?>">ดาวน์โหลด PDF</a>
    <?php } else { ?>
        <a class="btn-a" href="<?php echo site_url('play/play_resource/pdf_dycontent/' . $resource_id); ?>">ดาวน์โหลด PDF</a>s
    <?php } ?>
    <div class="clearfix"></div>

    <?php
    echo $render;
    ?>
</div>