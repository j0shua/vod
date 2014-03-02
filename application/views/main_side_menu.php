<h1><?php echo $main_title; ?></h1>
<div class="grid_2">
    <ul class="side-nav">
        <?php
        if (!isset($active)) {
            $active = '';
        }
        foreach ($menu as $k => $v) {
            if ($active == $k) {
                //echo '<li><span class="active-link">' . $v['title'] . '</span></li>';
                echo '<li>', anchor($v['uri'], $v['title'],'class="active-link"'), '</li>';
            } else {
                echo '<li>', anchor($v['uri'], $v['title'], 'class="non-active-link"'), '</li>';
            }
        }
        ?>
    </ul>
</div>