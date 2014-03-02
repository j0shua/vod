<?php $column_width = 300 + (12 * count($studeunt_score_data['course_act_data'])); ?>
<h1><?php echo $title; ?></h1>
<div class="grid_12">
    <h2 class="head1">หลักสูตร : <?php echo $studeunt_score_data['course_data']['title']; ?></h2>
    

    <div style="margin-left: 10px;margin-right: 10px; width:<?php echo $column_width; ?>px; ">


        <?php
        if (isset($grid_menu)) {
            foreach ($grid_menu as $btn) {
                echo anchor($btn['url'], $btn['title'], 'class="btn-a" ' . $btn['extra']);
            }
        }
        ?>

        <table class="data">
            <thead>
                <tr>
                    <th style="width: 300px;  height: 130px;vertical-align: bottom;">ชื่อ-นามสกุล</th>
                    <?php foreach ($studeunt_score_data['course_act_data'] as $k_course_act_data => $v_course_act_data) { ?>
                        <th class="text_rotate_270">
                            <span ><a class="hide_on_print btn-a-small" target="_blank" href="<?php echo site_url('study/course_manager/student_act/' . $v_course_act_data['ca_id']); ?>" ># <?php echo $k_course_act_data + 1; ?> [เลขที่งาน:<?php echo $v_course_act_data['ca_id'] ?>]</a></span>
                            <span class="show_on_print" ># <?php echo $k_course_act_data + 1; ?> [เลขที่งาน:<?php echo $v_course_act_data['ca_id'] ?>]</span>


                        </th>


                    <?php } ?>

                    <th style="vertical-align: bottom;"><span>รวม</span></th>
                </tr>
                <tr>
                    <th style="text-align: right;"><b >คะแนนเต็ม ></b></th>
                    <?php foreach ($studeunt_score_data['course_act_data'] as $k_course_act_data => $v_course_act_data) { ?>
                        <th><?php echo $v_course_act_data['full_score']; ?></th>
                    <?php } ?>

                    <th><?php echo $studeunt_score_data['course_act_full_score']; ?></th>

                </tr>
            </thead>
            <tbody>
                <?php foreach ($studeunt_score_data['score_data'] as $k2 => $v2) { ?>
                    <tr>
                        <td><?php echo $v2['user_detail']['first_name'] . ' ' . $v2['user_detail']['last_name']; ?></td>
                        <?php foreach ($v2['score_data']['score'] as $score) { ?>
                            <td><?php echo $score; ?></td>
                        <?php } ?>

                        <td><?php echo $v2['score_data']['sum_score']; ?></td>


                    </tr>
                <?php } ?>

            </tbody>
        </table>

    </div>
</div>
<style>

    @media print {
        .btn-a{
            display: none;
        }

        .data,
        .data th,
        .data td {
            border-style: solid;
            border-color: #000;

        }
        .data th{
            font-weight: bold;
        }

        .data {
            border-width: 0 1px 1px;
            width: 100%;
        }

        .data caption {
            padding: 0 10px 5px;
            text-transform: uppercase;
        }

        .data th,.data td {
            border-width: 1px 1px 1px;
            padding: 5px 10px;
            color: #ffffff;

        }

        .data th {
            white-space: nowrap;
            background-color: #79BBFF;
            color: #ffffff;
        }

        .data thead th {
            background: #eee;
            font-weight: bold;
            text-shadow: #fff 0 1px 0;
            color: #45484F;
        }

        .data tbody th {
            width: 1px;
        }
        .btn-a-small{
            border: none;

        }

        .hide_on_print{
            display: none;
        }
        span.show_on_print{
            display: block;
        }
    }
    .data th{




    }
    .text_rotate_270{
        vertical-align: bottom;

        text-align: left;


    }
    .text_rotate_270 span{
        -webkit-transform: rotate(270deg);
        -moz-transform: rotate(270deg);
        -o-transform: rotate(270deg);
        writing-mode: lr-tb;
        display: block;
        text-align: right;
        width: 25px;


    }
    .data a{
        color: #45484F;
    }


    @media screen{
        span.show_on_print{
            display: none;
        }
    }


</style>