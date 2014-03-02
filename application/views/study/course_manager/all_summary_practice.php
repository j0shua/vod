<h1><?php echo $title; ?></h1>
<div class="grid_12">
    <table border="1" class="data">
        <thead>
            <tr>
                <th>ลำดับ</th>
                <th>ชื่อ</th>
                <th>คะแนน</th>
                 <th>เมื่อ</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($summary_data as $k => $row) { ?>
                <tr>
                    <td><?php echo $k + 1; ?></td>
                    <td><?php echo $row['user_data']['full_name']; ?></td>
                    <td><?php echo $row['get_score']; ?></td>
                    <td><?php echo thdate('d M Y เวลา H:i',$row['send_time']); ?></td>
                </tr>
            <?php } ?>

        </tbody>
    </table>
<!--    <pre>
<?php print_r($summary_data);?>
    </pre>-->
</div>