<h1>ข้อมูลพื้นฐาน</h1>
<div class="grid_9">
    <?php
    if (is_array($this->session->flashdata('form_error'))) {
        foreach ($this->session->flashdata('form_error') as $v) {
            echo '<p>' . $v . '</p>';
        }
    }
    ?>
    <form class="normal-form" method="post" action="<?php echo $form_action; ?>" id="normalform">
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
            echo form_dropdown('form_data[province_id]', $province_options, $form_data['province_id'], 'id="province_id"');
            ?>
        </p>

        <p>
            <label for="school_name" >โรงเรียน</label>
            <?php if (FALSE) { ?>
                <input type="text" id="school_name" name="form_data[school_name]" value="<?php echo $form_data['school_name']; ?>">
                <?php
            } else {
                echo '<span id="school_name_wraper">';
                echo form_dropdown('form_data[school_name]', $school_name_options, $form_data['school_name'], 'id="school_name"');
                echo '</span>';
                ?>
                <script>
                    var school_name = '';
                    $(function() {
                        school_name = $("#school_name").val();
                        console.log(school_name);
                        var is_change_school_name = false;
                        function get_school_name() {
                            var data = "province_id=" + $("#province_id").val();
                            $.ajax({
                                type: "POST",
                                url: ajax_school_name_options_url,
                                data: data,
                                dataType: "json",
                                async: false
                            }).done(function(json) {
                                console.log(school_name);
                                $("#school_name_wraper").html(json.school_name_options);
                                if (!is_change_school_name) {
                                    $("#school_name").val(school_name);
                                    is_change_school_name = true;
                                }


                            }).fail(function(jqXHR, textStatus) {
                                alert("Request failed: " + textStatus + " " + jqXHR.status + " " + jqXHR.statusText);
                                console.log(jqXHR);
                            }).always(function() {

                            });
                        }
                        $("#province_id").change(function() {
                            get_school_name();
                        }).change();
                    });

                </script>

                <?php
            }
            ?>
        </p>
        <p>
            <label for="birthday" >วันเกิด</label>
            <input id="birthday" type="text" name="form_data[birthday]" value="<?php echo $form_data['birthday']; ?>">
        </p>
        <p>
            <?php if ($form_data['rid'] != 3) { ?>
                <label for="degree_id" >ชั้นเรียน</label>
            <?php } else { ?>
                <label for="degree_id" >สอนชั้น</label>
            <?php } ?>
            <?php
            echo form_dropdown('form_data[degree_id]', $degree_id_options, $form_data['degree_id'], 'id="degree_id"');
            ?>
        </p>
        <p>
            <label for="phone_number" >เบอร์โทรศัพท์</label>
            <input class="number-only" id="phone_number" type="text" name="form_data[phone_number]" value="<?php echo $form_data['phone_number']; ?>">
        </p>
        <p>
            <label for="about_me" >เกี่ยวกับฉัน</label>
            <textarea id="about_me" maxlength="255" name="form_data[about_me]"><?php echo $form_data['about_me']; ?></textarea>
        </p>
        <input type="submit" class="btn-submit" id="form_submit" name="submit" value="บันทึก">
        <a class="btn-a" href="<?php echo $cancel_url; ?>">ยกเลิก</a>
        <p class="hide important" id="response"></p>
    </form>
</div>