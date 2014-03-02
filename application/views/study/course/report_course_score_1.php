<?php echo $main_side_menu; ?>
<div class="grid_10">
    <h2 class="head1"><?php echo $title; ?></h2>

    <?php
    if (isset($grid_menu)) {
        foreach ($grid_menu as $btn) {
            echo anchor($btn['url'], $btn['title'], 'class="btn-a" ' . $btn['extra']);
        }
    }
    ?>
    
    <table border="1" class="data">
        <thead>
            <tr>
                <th>รายการงาน</th>
                <th>คะแนนเต็ม</th>
                <th>คะแนนที่ได้</th>
                <th>พรี/โพส/เต็ม</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($course_act_data as $act_type => $v) {
                ?>
                <tr>
                    <td colspan="4" ><span class="act_type_title"><?php echo $act_type_options[$act_type]; ?><span></td>

                                </tr> 


                                <?php
                                foreach ($v as $row) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['title']; ?></td>
                                        <td><?php echo $row['full_score_text']; ?></td>
                                        <td><?php echo $row['get_score']; ?></td>
                                        <?php
                                        $row['pre_full_score'] = ($row['pre_full_score'] == '') ? '-' : $row['pre_full_score'];
                                        $row['pre_get_score'] = ($row['pre_get_score'] == '') ? '-' : $row['pre_get_score'];
                                        $row['post_full_score'] = ($row['post_full_score'] == '') ? '-' : $row['post_full_score'];
                                        $row['post_get_score'] = ($row['post_get_score'] == '') ? '-' : $row['post_get_score'];
                                        ?>
                                        <?php if ($row['pre_full_score'] != '-') { ?>
                                            <td><?php echo $row['pre_get_score'] . '/' . $row['post_get_score'] . '/' . $row['pre_full_score']; ?></td>
                                        <?php } else { ?>
                                            <td>-</td>
                                        <?php } ?>

                                    </tr>
                                    <?php
                                }
                            }
                            ?>


                            </tbody>
                            </table>

                            </div>
                            <style>
                                .act_type_title{
                                    font-size: 16px;
                                    font-weight: bold;
                                }
                            </style>