<?php echo $main_side_menu; ?>
<div class="grid_10">
    <h2 class="head1"><?php echo $title; ?></h2>
    <div>
        <form class="" id="filter-form" action="<?php echo $filter_form_action; ?>" method="get" >
            ตั้งแต่ <input class="filter_date" id="filter_date_from" type="text" disabled name="from" value="<?php echo $from; ?>">
            ถึง <input  class="filter_date" id="filter_date_to" type="text" name="to" value="<?php echo $to; ?>">
            <input type="submit" id="btn_filter" value="ปรับปรุงช่วงเวลาเบิกเงิน" class="btn-a-small"> 
        </form>
    </div>
    <div style="margin-top: 5px; margin-bottom: 5px;" class="messages warning">
        การเบิกเงินมีค่าธรรมเนียม <?php echo $withdraw_fee; ?> บาท ต่อครั้ง ขั่นตำในการเบิกเงินคือ 100 บาท
    </div>
    <?php if ($from == $to) { ?>
        <div style="margin-top: 5px; margin-bottom: 5px;" class="messages error">
            <span>ท่านยังไม่สามารถทำการเบิกเงินได้เนื่องจาก ช่วงเวลาในการคำนวณรายได้ไม่เพียงพอ</span>
        </div>
    <?php } ?>
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
        <form  class="normal-form"   id="withdraw-form" action="<?php echo $withdraw_form_action; ?>" method="get" >
            <input id="withdraw-date-from" type="hidden" name="from" value="<?php echo $from; ?>">
            <input id="withdraw-date-to" type="hidden" name="to" value="<?php echo $to; ?>">
            <?php if ($from != $to) { ?>
                <input type="submit" id="btn_withdraw" value="ทำการเบิกเงิน" class="btn-a"> 
            <?php } ?>

        </form>
    </div>

</div>



