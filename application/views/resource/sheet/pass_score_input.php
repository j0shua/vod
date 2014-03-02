<ul>
    <?php
    if ($question_num > 0) {
        if ($section_num > 1) {
            foreach ($section_score as $k => $v) {
                $random_index = str_rand(10);
                ?>
                <li>
                    คะแนนเต็มตอนที <?php echo $k + 1; ?> <input readonly style="width: 30px;text-align: center;padding: 0;" type="text" name="data[section_score][<?php echo $random_index; ?>][full_score]" value="<?php echo $v['full_score']; ?>">

                    เกณฑ์ผ่านตอนที่ <?php
                    echo form_dropdown("data[section_score][$random_index][pass_score]", array_range(0, $v['full_score']), $v['pass_score'], 'style="width: 60px;"')
                    ?>
                </li>
                <?php
            }
        } else {
            foreach ($section_score as $v) {
                $random_index = str_rand(10);
                ?>
                <li>
                    คะแนนเต็ม <input readonly style="width: 30px;text-align: center;padding: 0;" type="text" name="data[section_score][<?php echo $random_index; ?>][full_score]" value="<?php echo $v['full_score']; ?>">
                    เกณฑ์ผ่าน <?php echo form_dropdown("data[section_score][$random_index][pass_score]", array_range(1, $v['full_score']), $v['pass_score'], 'style="width: 60px;"') ?>
                </li>
                <?php
            }
        }
    }
    ?>
</ul>
<?php

function array_range($start, $limit) {
    $options = array();
    foreach (range($start, $limit) as $v) {
        $options[$v] = $v;
    }
    return $options;
}
?>