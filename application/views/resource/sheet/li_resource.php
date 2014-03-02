<?php
foreach ($resources as $resource) {
    //$random_index = rand(11111111111, 99999999999);
    $random_index = str_rand(10);
    if ($resource['content_type_id'] == 1) {
        ?>
        <li class="ui-widget-content">
            <span>เนื้อหา</span>
            <input readonly class="array_resource"   style="width: 60px;text-align: center; padding: 0;" type="text" name="data[resources][<?php echo $random_index; ?>][resource_id]" value="<?php echo $resource['resource_id']; ?>">
            เว้น
            <input  class="preview_effect array_resource array_vspace"  style="width: 30px;text-align: center;padding: 0;"  type="text" name="data[resources][<?php echo $random_index; ?>][vspace]" value="<?php echo $resource['vspace']; ?>">
            บรรทัด
            <input class="btn-a-small btn-delete-me" type="button" value="X" >
            <?php
            if ($resource['play_video_link']) {
                echo anchor($resource['play_video_link']['url'], $resource['play_video_link']['title'], 'class="btn-a btn_play_video" target="_blank"');
            }
            ?>

        </li>
        <?php
    } else {
        ?>
        <li class="ui-widget-content">
            <span class="q-number" num_questions="<?php echo $resource['num_questions']; ?>">โจทย์ข้อ 0</span> | 
            <span>เลขที่</span>
            <input readonly class="array_resource"   style="width: 60px;text-align: center; padding: 0;" type="text" name="data[resources][<?php echo $random_index; ?>][resource_id]" value="<?php echo $resource['resource_id']; ?>">
            <?php if ($resource['num_questions'] > 1) { ?>
                โจทย์กลุ่ม <?php echo $resource['num_questions']; ?> ข้อ
            <?php } else { ?>
                โจทย์ปกติ
        <?php } ?> | เว้น
            <input  class="preview_effect array_resource array_vspace"  style="width: 30px;text-align: center;padding: 0;"  type="text" name="data[resources][<?php echo $random_index; ?>][vspace]" value="<?php echo $resource['vspace']; ?>">
            บรรทัด | คะแนน
            <input type="hidden" class="array_resource" name="data[resources][<?php echo $random_index; ?>][num_questions]" value="<?php echo $resource['num_questions']; ?>" >
            <input  num_questions="<?php echo $resource['num_questions']; ?>" class="preview_effect array_resource array_score_pq"  style="width: 30px;text-align: center;padding: 0;"  type="text" name="data[resources][<?php echo $random_index; ?>][score_pq]" value="<?php echo $resource['score_pq']; ?>">
            คะเนน/ข้อ
            <input class="btn-a-small btn-delete-me" type="button" value="X" >
            <?php
            if ($resource['play_video_link']) {
                echo anchor($resource['play_video_link']['url'], $resource['play_video_link']['title'], 'class="btn-a-small btn_play_video" target="_blank"');
            }
            ?>
        </li>
        <?php
    }
}
?>
