<h1>เอกสาร : <?php echo $title; ?></h1>

<div class="grid_12">
    <?php echo anchor('play/play_resource/download_pdf/' . $resource_id, 'ดาวน์โหลด', 'class="btn-a"'); ?>
    <p><?php echo $desc; ?></p>
</div>
<div class="clearfix"></div>
<script>
    var to_download = null;
    $(function() {
        window.open("<?php echo site_url('play/play_resource/download_pdf/' . $resource_id); ?>")
    });
</script>