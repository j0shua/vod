<?php
foreach ($resources as $resource) {
    //$random_index = rand(11111111111, 99999999999);
    $random_index = str_rand(10);
    ?>
    <li class="ui-widget-content">
        <span class="s-number">0</span>
        <input  class="array_resource"   style="width: 390px;" type="text" name="data[resources][<?php echo $random_index; ?>][section_title]" value="<?php echo $resource['section_title']; ?>">
        เว้น
        <input  class="preview_effect array_resource"  style="width: 30px;"  type="text" name="data[resources][<?php echo $random_index; ?>][vspace]" value="<?php echo $resource['vspace']; ?>">
        บรรทัด
        <input class="btn-delete-me" type="button" value="X" >
    </li>
<?php } ?>
