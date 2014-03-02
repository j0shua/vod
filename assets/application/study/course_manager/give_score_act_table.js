$(function() {
    $("#send_time_d").datepicker();
    $("#send_time_h").timepicker({
        showNowButton: true
    }).setMask('29:59').val();
    $("#main-form").submit(function() {
        var bvalid = true;
        $("#comment").val($.trim($("#comment").val()));
        if ($("#comment").val() == '') {
            bvalid = false;

        }
        if ($("#get_score").val() == '') {
            bvalid = false;

        }
        if (bvalid) {
            return true;
        } else {
            alert("โปรดกรอกข้อมูลให้ครบถ้วน");
            return false;
        }


    })
});