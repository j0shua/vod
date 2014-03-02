$(function() {
    $(".btn-help").tooltip({
        track: true
    });
    $("#enroll_limit,#enroll_password").typeonly("0123456789");
    $("#start_time").datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 3,
        showButtonPanel: true,
        onClose: function(selectedDate) {
            $("#end_time").datepicker("option", "minDate", selectedDate);
        }
    });
    $("#end_time").datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 3,
        showButtonPanel: true,
        onClose: function(selectedDate) {
            $("#start_time").datepicker("option", "maxDate", selectedDate);
        }
    });
    $("#enroll_type_id").change(function() {
        if ($(this).val() === "3") {
            $("#p_enroll_password").show();
        } else {
            $("#p_enroll_password").hide();
        }
    }).change();
    // check on submit
    $("#main-form").submit(function() {
        var error_msg = [];
        error_msg.push("=== กรุณากรอกข้อมูล ===");
        var b_valid = true;
        $("#title").val($.trim($("#title").val()));
        if ($("#title").val() === '') {
            b_valid = false;
            error_msg.push("ชื่อหลักสูตร");
        }
        $("#desc").val($.trim($("#desc").val()));
        if ($("#desc").val() === '') {
            b_valid = false;
            error_msg.push("รายละเอียด");
        }

        if ($("#degree_id").val() === '') {
            b_valid = false;
            error_msg.push($('label[for="degree_id"]').html());
        }
        if ($("#la_id").val() === '') {
            b_valid = false;
            error_msg.push($('label[for="la_id"]').html());
        }
        if ($("#subj_id").val() === '') {
            b_valid = false;
            error_msg.push($('label[for="subj_id"]').html());
        }





        if ($("#start_time").val() === '') {
            b_valid = false;
            error_msg.push("วันเริ่มหลักสูตร");
        }
        if ($("#end_time").val() === '') {
            b_valid = false;
            error_msg.push("วันสิ้นสุดหลักสูตร");
        }
        if ($("#enroll_type_id").val() === "3") {
            if ($("#enroll_password").val() === '') {
                b_valid = false;
                error_msg.push("รหัสผ่านสำหรับการเข้าเรียนหลักสูตร");
            } else if ($("#enroll_password").val().length < 4) {
                b_valid = false;
                error_msg.push("รหัสผ่านต้องเป็นตัวเลข 4 ตัว");
            }
        }

        if (b_valid) {
            return true;
        } else {
            alert(error_msg.join("\n"));
            return false;
        }
    });

    // for inputform 

    $("#la_id").change(function() {
        var data = "la_id=" + $(this).val();
        $('body').showLoading();
        $.ajax({
            type: "POST",
            url: ajax_subject_options_url,
            data: data,
            dataType: "json",
            async: false
        }).done(function(json) {
            console.log(json);
            $("#subj_id").children().remove().append(json.options_render);
            $("#subj_id").append(json.options_render);
            if (subj_id != '') {
                $("#subj_id").val(subj_id);

            }

        }).fail(function(jqXHR, textStatus) {
            alert("Request failed: " + textStatus + " " + jqXHR.status + " " + jqXHR.statusText);
            console.log(jqXHR);
        }).always(function() {
            $('body').hideLoading();
        });

    }).change();

    $("#subj_id").change(function() {
        $("#chapter_title").autocomplete({
            source: ajax_chapter_autocomplete_url + "?subj_id=" + $("#subj_id").val(),
            minLength: 2,
            select: function(event, ui) {
                //            log( ui.item ?
                //                "Selected: " + ui.item.value + " aka " + ui.item.id :
                //                "Nothing selected, input was " + this.value );
            }
        });
    }).change();

});
