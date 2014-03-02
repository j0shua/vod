<h1 class="hide_on_print"><?php echo $title; ?></h1>
<div style="margin-left: 10px;margin-right: 10px;">
    <h2 class="head1">หลักสูตร : <?php echo $course_data['title'] ?></h2>
    <p>เริ่ม <?php echo thdate('d M Y',$course_data['start_time']) ?> สิ้นสุด <?php echo thdate('d M Y',$course_data['end_time']) ?></p>
    <?php
    if (isset($grid_menu)) {
        foreach ($grid_menu as $btn) {
            echo anchor($btn['url'], $btn['title'], 'class="btn-a" ' . $btn['extra']);
        }
    }
    ?>

    <table class="data" >
        <thead>
            <tr>
                <th style="width: 15px; height: 100px;">#</th>
                <th style="width: 150px; vertical-align: bottom;">ชื่อ-นามสกุล</th>
                <?php foreach (range(1, $range) as $no) { ?>
                    <th></th>
                <?php } ?>



            </tr>

        </thead>
        <tbody>
            <?php foreach ($enroll_data as $k => $v) { ?>

                <tr>
                    <td><?php echo $k + 1; ?></td>
                    <td><?php echo $v['user_datail']['first_name'] . ' ' . $v['user_datail']['last_name']; ?></td>
                    <?php foreach (range(1, $range) as $no) { ?>
                        <td></td>
                    <?php } ?>




                </tr>
            <?php } ?>

        </tbody>
    </table>

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

</style>