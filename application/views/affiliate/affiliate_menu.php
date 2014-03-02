<?php
$menu = array(
    array('active_name' => 'play_report', 'title' => 'รายชื่อ Downline', 'uri' => 'affiliate/affiliate_money'),
);
?>
<div class="container_12 grid_2">
    <ul class="side-nav">
        <?php
        foreach ($menu as $v) {
            if ($active == $v['active_name']) {
                echo '<li>', $v['title'], '</li>';
            } else {
                echo '<li>', anchor($v['uri'], $v['title']), '</li>';
            }
        }
        ?>

    </ul>

</div>