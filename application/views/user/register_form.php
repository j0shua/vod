<h1 class="main-title">{form_title}</h1>
<div class="grid_6">
    <form class="normal-form" method="post" action="<?php echo $form_action; ?>" id="main-form" autocomplete="off">
        <?php if (!$make_money) { ?>
            <input type="hidden" name="form_data[coupon_code]" value="">
        <?php } ?>
        <input type="hidden" name="form_data[rid]" value="<?php echo $form_data['rid'] ?>">
        <?php if ($username_field == 'username') { ?>
            <p>
                <label for="username" >ยูสเซอร์เนม</label>
                <input type="text" id="username" name="form_data[username]" value="<?php echo $username; ?>" maxlength="255" title="กรอก ยูสเซอร์เนม เพื่อใช้เป็นชื่อในการเข้าใช้งาน">
            </p>
        <?php } else { ?>
            <p>
                <label for="email" >อีเมล์</label>
                <input type="text" id="email" name="form_data[email]" value="<?php echo $email; ?>" maxlength="255" title="กรอกอีเมล์เพื่อใช้เป็นชื่อในการเข้าใช้งาน">
            </p>
        <?php } ?>
        <p>
            <label for="password" >รหัสผ่าน</label>
            <input type="password" id="password" name="form_data[password]" value="" maxlength="<?php echo $password_length['max']; ?>">
        </p>
        <p>
            <label for="password_confirm" >ยืนยันรหัสผ่าน</label>
            <input type="password" id="password_confirm" name="form_data[password_confirm]" value="" maxlength="<?php echo $password_length['max']; ?>">
        </p>
        <?php if ($username_field == 'username') { ?>
            <p>
                <label for="email" >อีเมล์</label>
                <input type="text" id="email" name="form_data[email]" value="<?php echo $email; ?>" maxlength="255" title="กรอกอีเมล์เพื่อใช้เป็นชื่อในการเข้าใช้งาน">
            </p>
        <?php } ?>
        <?php if ($make_money && $username_field != 'username') { ?>
            <p>
                <label for="coupon_code" >รหัสหนังสือ <a class="btn-help" href="<?php echo $btn_help['coupon_code']; ?>" target="_blank">?</a></label>
                <input type="text" id="coupon_code" class="number-only" name="form_data[coupon_code]" value="<?php echo $coupon_code; ?>" maxlength="10" >
            </p>
        <?php } ?>

        <p>
            <label for="first_name" >ชื่อ</label> 
            <input type="text" id="first_name" name="form_data[first_name]" value="<?php echo $first_name; ?>" maxlength="50">
        </p>
        <p>
            <label for="last_name" >นามสกุล</label>
            <input type="text" id="last_name" name="form_data[last_name]" value="<?php echo $last_name; ?>" maxlength="50">
        </p>
        <p>
            <label for="sex" >เพศ</label>
            <?php
            echo form_dropdown('form_data[sex]', $sex_options, $sex, 'id="sex"');
            ?>
        </p>

        <p>
            <label for="birthday" >วันเกิด</label>
            <input id="birthday" type="text" name="form_data[birthday]" value="<?php echo $birthday; ?>">
        </p>
        <p>
            <label for="sex" >จังหวัด</label>
            <?php
            echo form_dropdown('form_data[province_id]', $province_options, $form_data['province_id'], 'id="province_id"');
            ?>


        </p>
        <p>
            <label for="school_name" >โรงเรียน</label>
            <?php if (FALSE) { ?> 
                <input type="text" id="school_name" name="form_data[school_name]" value="<?php echo $school_name; ?>">
                <?php
            } else {
                echo '<span id="school_name_wraper">';
                echo form_dropdown('form_data[school_name]', $school_name_options, '', 'id="school_name"');
                echo '</span>';
                ?>
                <script>
                    $(function() {

                        function get_school_name() {
                            var data = "province_id=" + $("#province_id").val();
                            $.ajax({
                                type: "POST",
                                url: ajax_school_name_options_url,
                                data: data,
                                dataType: "json",
                                async: false
                            }).done(function(json) {
                                console.log(json);
                                $("#school_name_wraper").html(json.school_name_options);

                            }).fail(function(jqXHR, textStatus) {
                                alert("Request failed: " + textStatus + " " + jqXHR.status + " " + jqXHR.statusText);
                                console.log(jqXHR);
                            }).always(function() {

                            });
                        }
                        $("#province_id").change(function() {
                            get_school_name();
                        });
                    });

                </script>

            <?php }
            ?>

        </p>
        <?php if ($is_student) { ?>
            <p>
                <label for="degree_id" >ชั้นเรียน</label>
                <?php
                echo form_dropdown('form_data[degree_id]', $degree_id_options, $degree_id, 'id="degree_id"');
                ?>
            </p>
        <?php } else {
            ?>
            <p>
                <label for="degree_id" >สอนชั้น</label>
                <?php
                echo form_dropdown('form_data[degree_id]', $degree_id_options, $degree_id, 'id="degree_id"');
                ?>
            </p>
        <?php }
        ?>
        <p>
            <label for="phone_number" >เบอร์โทรศัพท์</label>
            <input class="number-only" id="phone_number" type="text" name="form_data[phone_number]" value="<?php echo $phone_number; ?>" maxlength="10">
        </p>
        <input type="submit" class="btn-submit" id="form_submit" name="submit" value="ลงทะเบียน">
        <p class="hide important" id="response"></p>

    </form>

</div>
<div class="grid_6">
    <?php
    if (is_array($this->session->flashdata('form_error'))) {
        foreach ($this->session->flashdata('form_error') as $v) {
            echo '<p>' . $v . '</p>';
        }
    }
    ?>
</div>