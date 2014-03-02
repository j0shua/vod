<div class="grid_12">
    <?php
    $active = '';
    $menu = array(
        array('active_name' => 'video_manager', 'title' => 'ครูจัดการหลักสูตร', 'uri' => 'study/course_manager'),
        array('active_name' => 'video_manager', 'title' => 'นักเรียนเข้าหลักสูตรตนเอง', 'uri' => 'study/course'),
    );
    ?>

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