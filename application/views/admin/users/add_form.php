<h1 class="main-title"><?php echo $title; ?></h1>

<div class="grid_4">

    <form class="normal-form" method="post" action="<?php echo $form_action; ?>" id="normalform">
        <input type="hidden" id="uid" name="form_data[uid]" value="<?php echo $form_data['uid']; ?>" >
        <input type="hidden" id="rid" name="form_data[rid]" value="<?php echo $form_data['rid']; ?>" >
        <?php if ($username_field == 'username') { ?>
            <p>
                <label for="username" >username</label>
                <input type="text" id="username" name="form_data[username]" value="<?php echo $form_data['username']; ?>" maxlength="50">

            </p>
        <?php } ?>


        <p>
            <label for="email" >email</label>
            <input type="text" id="email" name="form_data[email]" value="<?php echo $form_data['email']; ?>" maxlength="50">

        </p>
          <p>
            <label for="password" >Password</label>
            <input type="text" id="password" name="form_data[password]" value="" maxlength="50">

        </p>
         <p>
            <label for="password_confirm" >ยืนยัน Password</label>
            <input type="text" id="password_confirm" name="form_data[password_confirm]" value="" maxlength="50">

        </p>
        
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
            <label for="province_id" >จังหวัด</label>
            <?php
            echo form_dropdown('form_data[province_id]', $province_options, $form_data['province_id'],'id="province_id"');
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
            <label for="degree_id" ><?php echo $label_degree; ?></label>
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
            echo form_dropdown('form_data[active]', $active_options, $form_data['active'], 'id="active"');
            ?>
        </p>
        <input type="submit" class="btn-submit" id="form_submit" name="submit" value="บันทึก">
        <a class="btn-a" href="<?php echo $cancel_url; ?>">ยกเลิก</a>
        <p class="hide important" id="response"></p>


    </form>
</div>
<div class="grid_3">
    <?php
    if (is_array($this->session->flashdata('form_error'))) {
        echo '<div class="messages error">';
        foreach ($this->session->flashdata('form_error') as $v) {
            echo '<p>' . $v . '</p>';
        }
        echo '</div>';
    }
    ?>
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

