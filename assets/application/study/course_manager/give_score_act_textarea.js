$(function() {
     $(".btn-help").tooltip({
        track: true
    });
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