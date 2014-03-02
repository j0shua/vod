<h1><?php echo $title; ?></h1>
<?php //print_r($ranking_pre_data); ?>
<?php //print_r($ranking_post_data); ?>
<?php if($ranking_pre_data){ ?>
<h2 class="head1">พรีเทส</h2>
<table border="1" class="data">
    <thead>
        <tr>
            <th>ลำดับ</th>
            <th>ชื่อ</th>
            <th>คะแนน</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($ranking_pre_data as $k => $v) { ?>
            <tr>
                <td><?php echo $k + 1; ?></td>
                <td><?php echo $v['user_data']['full_name']; ?></td>
                <td><?php echo $v['pre_get_score']; ?></td>

            </tr>
        <?php } ?>
    </tbody>
</table>
<?php } ?>
<?php if($ranking_post_data){ ?>
<h2 class="head1">โพสเทส</h2>
<table border="1" class="data">
    <thead>
        <tr>
            <th>ลำดับ</th>
            <th>ชื่อ</th>
            <th>คะแนน</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($ranking_post_data as $k => $v) { ?>
            <tr>
                <td><?php echo $k + 1; ?></td>
                <td><?php echo $v['user_data']['full_name']; ?></td>
                <td><?php echo $v['post_get_score']; ?></td>

            </tr>
        <?php } ?>
    </tbody>
</table>
<?php } ?>
