

    <ul class="nav nav-sidebar side-nav">
        <?php
        foreach ($nav as $k => $v) {
            if ($k == $current) {
                echo '<li class="active"><a class="active-link"  href="'.site_url($v['uri']).'" title="'.(@$v['title']).'">'.$v['text'].'</a></li>';
            } else {
                echo '<li><a class="non-active-link" href="'.site_url($v['uri']).'" title="'.(@$v['title']).'">'.$v['text'].'</a></li>';
            }
        }
        ?>
    </ul>