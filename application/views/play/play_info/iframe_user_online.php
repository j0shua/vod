
<table class="data_user_online" style="width: <?php echo $width; ?>px;">
    <?php foreach ($play_info as $v) { ?>
        <tr>
            <td style="width:35px; "><img src="<?php echo base_url('ztatic/img_avatar_32/' . $v['uid_view']); ?>"></td>
            <td>
                <div><?php echo $v['user_view_details']['first_name'] ?></div>
                <div><?php echo anchor('v/' . $v['resource_id'], $v['resource_details']['title']); ?></div>
            </td>
        </tr>
    <?php } ?>
</table> 