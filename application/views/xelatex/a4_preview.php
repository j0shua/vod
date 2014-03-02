<div class="a4-wrapper">
    <?php foreach ($render_result['files_basename'] as $img) { ?>
        <div class="a4-page"><img src="<?php echo base_url($render_result['render_file_base_uri'] . $img) . '?v=' . time(); ?>"></div>
    <?php } ?>
</div>
<?php //print_r($image_array); ?>
<style>
  
</style>