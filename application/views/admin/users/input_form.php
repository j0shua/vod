<h1 class="main-title">โปรไฟล์</h1>
<div class="hr-940px grid_12 "></div>
<div class="grid_9">
    <?php
    if (is_array($this->session->flashdata('form_error'))) {
        foreach ($this->session->flashdata('form_error') as $v) {
            echo '<p>' . $v . '</p>';
        }
    }
    ?>
    <form class="normal-form" method="post" action="<?php echo $form_action; ?>" id="normalform">
        <input type="hidden" id="uid" name="form_data[uid]" value="<?php echo $form_data['uid']; ?>" >
        <p>
            <label for="first_name" >ชื่อ</label>
            <input type="text" id="first_name" name="form_data[first_name]" value="<?php echo $form_data['first_name']; ?>" maxlength="50">

        </p>
        <p>
            <label for="last_name" >นามสกุล</label>
            <input type="text" id="last_name" name="form_data[last_name]" value="<?php echo $form_data['last_name']; ?>" maxlength="50">

        </p>

        <p>
            <label for="sex" >เพศ</label>
            <?php
            echo form_dropdown('form_data[sex]', $sex_options, $form_data['sex']);
            ?>


        </p>
        <p>
            <label for="sex" >จังหวัด</label>
            <?php
            echo form_dropdown('form_data[province_id]', $province_options, $form_data['province_id']);
            ?>


        </p>
        <p>
            <label for="birthday" >วันเกิด</label>

            <input id="birthday" type="text" name="form_data[birthday]" value="<?php echo $form_data['birthday']; ?>">

        </p>
        <p>
            <label for="school_name" >โรงเรียน</label>
            <input type="text" id="school_name" name="form_data[school_name]" value="<?php echo $form_data['school_name']; ?>">
        </p>
        <p>
            <label for="degree_id" >ชั้นเรียน</label>
            <?php
            echo form_dropdown('form_data[degree_id]', $degree_id_options, $form_data['degree_id'], 'id="degree_id"');
            ?>
        </p>
        <p>
            <label for="phone_number" >เบอร์โทรศัพท์</label>
            <input class="number-only" id="phone_number" type="text" name="form_data[phone_number]" value="<?php echo $form_data['phone_number']; ?>">
        </p>
        <p>
            <label for="active" >สถานะ</label>
            <?php
            echo form_dropdown('form_data[active]', $active_options, $form_data['active'],'id="active"');
            ?>
        </p>
        <input type="submit" class="btn-submit" id="form_submit" name="submit" value="บันทึก">
        <a class="btn-a" href="<?php echo $cancel_url; ?>">ยกเลิก</a>
        <p class="hide important" id="response"></p>


    </form>
</div>
<script>
<?php
if ($script_var) {
    foreach ($script_var as $script_k => $script_v) {
        echo 'var ' . $script_k . '="' . $script_v . '";';
    }
}
?>
</script>

