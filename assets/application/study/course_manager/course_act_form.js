var time_mistake = false;
$(function() {
    var tmp_data = $("#data").val();
    var first_change = true;

    // ตั้งค่า options
    function add_options(o, array_options, selectedOption) {

        if (selectedOption === undefined) {
            selectedOption = $(o).val();
        }

        var select = $(o);

        if (select.prop) {
            var options = select.prop('options');
        }
        else {
            var options = select.attr('options');
        }
        $('option', select).remove();

        $.each(array_options, function(val, text) {
            options[options.length] = new Option(text, val);
        });
        select.val(selectedOption);
    }
    /**
     * 
     * @param {type} on เลือกแบบแสดงออนไลน์ หรือ ไม่ออนไลน์
     * @returns {undefined}
     */
    function set_data_sheet(on) {

        if (on) { // online

            if ($('#at_id').val() === '5') {
                add_options("#st_id", st_id_options_normal_sheet_set);
                $("#p_have_preposttest").show();
            } else {
                if ($('#at_id').val() === '2' || $('#at_id').val() === '3' || $('#at_id').val() === '4') {
                    add_options("#st_id", st_id_options_test);
                } else {
                    add_options("#st_id", st_id_options_normal);
                }

                $("#p_have_preposttest").hide();
            }

            $("#label_data").html("เลขที่ใบงาน");
            $("#data").addClass("textarea_to_text_input");
            $("#data").attr("readonly", true);
            $("#data").val("");
            $("#btn-search-sheet").show();
        } else {
            if ($('#at_id').val() === '5') {
                add_options("#st_id", st_id_options_normal_sheet_set);
            } else {
                if ($('#at_id').val() === '2' || $('#at_id').val() === '3' || $('#at_id').val() === '4') {
                    add_options("#st_id", st_id_options_test_no_online);
                } else {
                    add_options("#st_id", st_id_options_normal_no_online);
                }

            }
            $("#p_have_preposttest").hide();
            $("#label_data").html("รายละเอียดคำสั่ง");
            $("#data").removeClass("textarea_to_text_input");
            $("#data").attr("readonly", false);
            $("#data").val("");
            $("#btn-search-sheet").hide();

        }
        if(first_change){
            $("#st_id").val(st_id);
            first_change = false;
        }

    }




    //เมื่อเลือกวิธีสังงาน
    $("#cmat_id").change(function() {

        if ($(this).val() === "2") {
            set_data_sheet(true);
        } else {
            set_data_sheet(false);

        }
    }).change();

    // เมื่อเลือกวิธีส่งงาน

    $("#st_id").change(function() {

        switch ($(this).val()) {
            case '1':
            case "2"://
            case "3"://พิมพ์ 
                $("#p_full_score").show();
                $("#p_end_time").show();

                break;
            case "4"://
                if ($("#at_id").val() === '5') {
                    $("#p_have_preposttest").show();
                }
                $("#p_full_score").show();
                $("#p_end_time").show();

                break;
            case "5"://ไม่มีการส่งงาน
                $("#p_end_time").hide();
                $("#p_full_score").hide();
                $("#full_score").val(0);
                break;
            default:
                break;
        }
    }).change();




    // เมื่อเลือกประเภทงาน
    $("#at_id").change(function() {

        var tmp_data = '';
        if ($("#data").val() !== '') {
            tmp_data = $("#data").val();
        }
        if ($("#cmat_id").val() === "2") {
            set_data_sheet(true);
        } else {
            set_data_sheet(false);
        }
        $("#data").val(tmp_data);
    }).change();

    $("#data").val(tmp_data);






//init script
    $(".btn-help").tooltip({
        track: true
    });
    if (can_edit_course_act_start_time) {
        $("#start_time_d").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 3,
            showButtonPanel: true,
            onClose: function(selectedDate) {
                $("#end_time_d").datepicker("option", "minDate", selectedDate);
            }
        });
        $("#end_time_d").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 3,
            showButtonPanel: true,
            onClose: function(selectedDate) {
                $("#start_time_d").datepicker("option", "maxDate", selectedDate);
            }
        });
    } else {
        //console.log(start_time);
        // var d_end = new Date(Date.parse(start_time));
        //console.log(d_end);
        $("#end_time_d").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 3,
            showButtonPanel: true,
            minDate: new Date(Date.parse(start_time))

        });
    }
    $("#start_time_h,#end_time_h").timepicker(
            {
                showNowButton: true
            }).setMask('29:59').val();
    $("#start_time_h,#end_time_h").change(function() {
        if ($("#end_time_d").val() === $("#start_time_d").val()) {
            var str_start_time = parseInt($("#start_time_h").val().replace(":", ""));
            var str_end_time = parseInt($("#end_time_h").val().replace(":", ""));
            if (str_start_time > str_end_time) {
                alert("เวลาเริ่มต้นมากกว่าเวลาสิ้นสุดไม่ได้");
                time_mistake = true;
            } else {
                time_mistake = false;
            }

        } else {
            time_mistake = false;
        }
    });
    $("#full_score").typeonly("0123456789");
    //detech change




    $("#main-form").submit(function() {
        var error_msg = [];
        error_msg.push("=== โปรดกรอกข้อมูล ===");
        var b_valid = true;
        $("#title").val($.trim($("#title").val()));
        if ($("#title").val() === '') {
            b_valid = false;
            error_msg.push("ชื่องาน");
        }
        if ($("#data").val() === '') {
            b_valid = false;
            if ($("#cmat_id").val() !== '2') {
                error_msg.push("รายละเอียดคำสั่ง");
            } else {
                error_msg.push("เลขที่ใบงาน");
            }

        }
        if ($('#cmat_id').val() === 1) {
            $("#data").val($.trim($('#data').val()));
            if ($("#data").val() === '') {
                b_valid = false;
                error_msg.push("รายละเอียด");
            }
        }

        if ($("#st_id").val() !== '5') {

            if ($("#full_score").val() === '') {
                b_valid = false;
                error_msg.push("คะแนนเต็ม");
            }
        }
        if ($("#start_time_d").val() === '') {
            b_valid = false;
            error_msg.push("วันที่เริ่มส่ง");
        }
        if ($("#start_time_h").val() === '') {
            b_valid = false;
            error_msg.push("เวลาเริ่มส่ง");
        }

        if ($("#st_id").val() !== '5') {
            if ($("#end_time_d").val() === '') {
                b_valid = false;
                error_msg.push("วันสิ้นสุดการส่ง");
            }
            if ($("#end_time_h").val() === '') {
                b_valid = false;
                error_msg.push("เวลาสิ้นสุดการส่ง");
            }
        }
        if (time_mistake) {
            b_valid = false;
            error_msg.push("เวลาสิ้นสุดการส่งน้อยกว่าเวลาเริ่มต้นไม่ได้");
        }


        if (b_valid) {
            return true;
        } else {
            alert(error_msg.join("\n"));
            return false;
        }
    });
    $("#dialog").dialog({
        autoOpen: false,
        modal: true,
        width: 1150,
        height: 700
    });
    $("#btn-search-sheet").click(function() {
        $("#inner-dialog").html('<iframe title="โปรดรอ" id="iframe-resource-browser" src="' + sheet_browser_iframe_url + '">โปรดรอ</iframe>');
        $("#dialog").dialog("open");
    });
});
function insert_resource_id(id, resource_id) {
    $("#" + id).val(resource_id);
    $("#dialog").dialog("close");
}