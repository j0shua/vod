<table class="tb-main">
    <thead>

        <tr>
            <th class="tb-col-title">เรื่องราว</th>   
            <th class="tb-col-reply">ดู</th>   
            <th class="tb-col-reply">ตอบ</th>    
            

        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($rows as $v) {
            ?>
            <tr>
                <td><?php echo anchor('community/board/view/' . $v['cell']['p_id'], $v['cell']['title'], 'target="_blank"'); ?></td>
                <td><?php echo $v['cell']['views']; ?></td>
                <td><?php echo $v['cell']['count_reply']; ?></td>
                
            </tr>
        <?php } ?>
    </tbody>
</table>
<div class="view-all">
    <?php echo anchor('community/board/type/' . $board_type_id, 'ดูทั้งหมด...'); ?>
</div>
<style>
    .tb-main{
        width: 100%;
        margin-bottom: 5px;
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
    .view-all{
        display: inline-block;
        margin-bottom: 15px;
    }
</style>