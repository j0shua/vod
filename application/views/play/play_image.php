<h1>รูปภาพ : <?php echo $title; ?></h1>

<div class="grid_12">
    <div style="margin-left: auto;margin-right: auto;width:<?php echo $width; ?>px;height:<?php echo $height; ?>px;  ">
        <?php
        $image_properties = array(
            'src' => $image_url,
            'alt' => $title,
            'width' => $width,
            'height' => $height,
            'title' => $title
        );

        echo img($image_properties);
        ?>
        <p><?php echo $desc; ?></p>
    </div>
</div>
<div class="clearfix"></div>
