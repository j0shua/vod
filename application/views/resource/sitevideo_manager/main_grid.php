    <h1 class="main-title">จัดการสื่อ</h1>
<div class="hr-940px grid_12 "></div>
<?php echo $main_side_menu; ?>
<div class="container_12 grid_10">

    <h2 class="main-title">{title}</h2>

    <div class="border clearfix" >
        <a href="<?php echo $add_link; ?>" class="btn-a">Add</a>
    </div>
    <div class="border" style="padding-bottom: 20px;">
        <?php echo form_dropdown('qtype', $qtype_options, 'title', 'id="qtype"'); ?>
        <input id="query" type="text" name="query">
        <input type="button" id="btn_search" value="กรอง"> 
    </div>
    <table id="main-table" style="display: none"></table>

</div>


