<h1><?php echo $tbl_name; ?></h1>
<div class="grid_12">
    <div>
        <?php
        foreach ($table_list as $k => $tbl) {
            echo anchor('report/advance_report/filter/' . $k, $tbl, 'class="btn-a-small link_table"');
        }
        ?>
    </div>
    <div class="filters-wrapp">
        <form class="" id="filter-form" action="<?php echo $filter_form_action; ?>" method="get" >
            ตั้งแต่ <input class="filter_date" id="filter_date_from" type="text" name="from" value="<?php echo $from; ?>">
            ถึง <input  class="filter_date" id="filter_date_to" type="text" name="to" value="<?php echo $to; ?>">
            <input type="submit" id="btn_filter" value="ปรับปรุงช่วงเวลากรอง" class="btn-a-small"> 
        </form>
    </div>
    <?php echo $table; ?>  
</div>
<script>

    $(function() {

        $("#filter_date_from").datepicker({
            defaultDate: "+0d",
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            onClose: function(selectedDate) {
                $("#filter_date_to").datepicker("option", "minDate", selectedDate);
            }
        });
        $("#filter_date_to").datepicker({
            defaultDate: "+0d",
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            maxDate: "-1d",
            onClose: function(selectedDate) {
                $("#filter_date_from").datepicker("option", "maxDate", selectedDate);
            }
        });
    });




</script>
<style>
    .link_table{
        margin-bottom: 5px;
        margin-right: 10px;
    }    
    .filters-wrapp{
        margin: 10px;
    }
</style>
