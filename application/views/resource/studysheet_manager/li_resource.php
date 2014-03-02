<?php
foreach ($resources as $resource) {
    $random_index = rand(11111111111, 99999999999);
    ?>
    <li class="ui-widget-content">
        <input  class="array_resource"   style="width: 15%;" type="text" name="data[resources][<?php echo $random_index; ?>][resource_id]" value="<?php echo $resource['resource_id']; ?>">
        <input  class="array_resource"  style="width: 15%;"  type="text" name="data[resources][<?php echo $random_index; ?>][vspace]" value="0">
        <input class="btn-preview-me" type="button" value="preview" >
        <input class="btn-delete-me" type="button" value="X" >
    </li>
<?php } ?>