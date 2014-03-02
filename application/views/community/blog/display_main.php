<div class="grid_12">
    <h1 class="main-title"><?php echo $title; ?></h1>

</div>
<div class="hr-940px grid_12 "></div>
<div class="grid_12">
    <a class="btn-submit" href="<?php echo $write_url; ?>">ตั้งกระทู้</a>

</div>
<div class="grid_12">
    <table class="tb-main">
        <thead>
            <tr>
                <th class="tb-col-title">หัวข้อกระทู้</th>    
                <th class="tb-col-time">โดย</th>    
                <th class="tb-col-time">เวลา</th>    
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($rows as $v) {
                ?>
                <tr>
                    <td><?php echo anchor('community/board/view/' . $v['cell']['p_id'], $v['cell']['title']); ?> 
                        <?php
                        if (isset($v['cell']['delete_url'])) {
                            echo anchor($v['cell']['delete_url'], '[ลบโพสนี้]');
                        }
                        ?>
                    </td>
                    <td><?php echo $v['cell']['user_post_fullname']; ?></td>
                    <td><?php echo thdate('d-m-Y H:i:s', $v['cell']['create_time']); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<div class="grid_12">
    <a class="btn-submit" href="<?php echo $write_url; ?>">ตั้งกระทู้</a>

</div>
<style>
    .tb-main{
        width: 100%;
        margin-bottom: 15px;
    }
    .tb-main td{
        border: solid 1px #0272a7;
        padding: 3px;
    }
    .tb-main thead th{
        border: solid 1px #0272a7;
        color: #002166;
        text-align: left;
        /*        background-color: #029FEB;*/
        padding: 5px;
    }
    .tb-main .tb-col-title{ width:  60%;}
    .tb-main .tbl-title{
        background: #ffffff;
        color: #4A4A4A;
        font-weight: bold;
        text-align: left;
        font-size: 20px;
    }
</style>