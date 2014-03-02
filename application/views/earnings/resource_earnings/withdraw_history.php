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
    <table id="main-table" style="display: none"></table>
</div>



