<?php echo $main_side_menu; ?>
<div class="grid_10">
    <h2 class="head1"><?php echo $title; ?></h2>

    <div>

        ตั้งแต่ <input id="query-date-from" type="text" name="from" value="<?php echo date('d/m/Y', $date_from_stamp); ?>">
        ถึง <input id="query-date-to" type="text" name="to" value="<?php echo date('d/m/Y', $date_to_stamp); ?>">
        <input type="button" id="btn_search" value="ปรับปรุง" class="btn-a-small"> 
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
                <tr>
                    <td>วิดีโอของตนเอง</td>
                    <td><?php echo number_format(200, 2) ?></td>
                    <td>30</td>
                    <td><?php echo number_format(60, 2) ?></td>
                </tr>
                <tr>
                    <td>วิดีโอที่ตนนำเสนอ</td>
                    <td><?php echo number_format(200, 2) ?></td>
                    <td>10</td>
                    <td><?php echo number_format(20, 2) ?></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right;">รวม</td>

                    <td>20</td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div>
        <a href="<?php echo $withdraw_url; ?>" class="btn-a">ถอนเงิน</a>
        <a href="<?php echo $withdraw_history;  ?>" class="btn-a">ประวัติถอนเงิน</a>
    </div>

</div>



