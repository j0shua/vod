<?php echo $main_side_menu; ?>
<div class="grid_10">
    <h2 class="head1"><?php echo $title; ?></h2>

    <div>
        <form  id="search-form" action="<?php echo $form_action; ?>" method="get" >
            ตั้งแต่ <input id="query-date-from" type="text" name="from" value="<?php echo $from; ?>">
            ถึง <input id="query-date-to" type="text" name="to" value="<?php echo $to; ?>">
            <input type="submit" id="btn_search" value="ปรับปรุง" class="btn-a-small"> 
        </form>
    </div>
    <div></div>
    <div style="margin-top: 5px;" class="messages warning">
        *** ค่าตอบแทนของท่านจะคิดจาก วิดีโอของท่านเอง และ วิดีโอที่ท่านำไปใช้สอน
    </div>
    <div style="margin-top: 5px;margin-bottom: 5px;">
        <table class="data">
            <thead>
                <tr>
                    <th>
                        รายการบริการ
                    </th>
                    <th>
                        ค่าบริการ (บาท)
                    </th>
                    <th>
                        ส่วนแบ่ง %
                    </th>
                    <th>
                        ส่วนแบ่งที่ได้รับ (บาท)
                    </th>
                </tr>

            </thead>
            <tbody>
                <?php foreach ($earnings_data as $r) { ?>
                    <tr>
                        <td><?php echo $r['title']; ?></td>
                        <td><?php echo $r['money']; ?></td>
                        <td><?php echo $r['percent_earnings']; ?></td>
                        <td><?php echo $r['earnings_money']; ?></td>
                    </tr>
                <?php } ?>

            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right;font-weight: bold;">รวม</td>

                    <td><?php echo $sum_earnings; ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div>
        <a href="<?php echo $withdraw_url; ?>" class="btn-a">ถอนเงิน</a>
        
        <a href="<?php echo $account_url; ?>" class="btn-a">บัญชีธนาคาร</a>
    </div>

</div>



