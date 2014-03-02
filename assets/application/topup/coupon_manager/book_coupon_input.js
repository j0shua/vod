$(function() {
    $("#money,#amount").typeonly("0123456789");
    $("#main_form").submit(function() {

        var bvalid = true;
        var error_msg = [];
        error_msg.push("== ผิดพลาด ==");
        if ($("#amount").val() === '') {
            $("#amount").val(0);
        }
        $("#amount").val(parseInt($("#amount").val(), 10));
        if ($("#amount").val() === '') {
            error_msg.push("ควรกรอกข้อมูล จำนวนรหัสหนังสือ");
            bvalid = false;
        }
        if ($("#amount").val() < 1) {
            error_msg.push("จำนวนรหัสหนังสือต้องมากกว่า 0");
            bvalid = false;
        }
        if ($("#amount").val() > 1000) {
            error_msg.push("จำนวนสร้างต้องน้อยกว่า 1000");
            bvalid = false;
        }
        if ($("#coupon_count").val() === '') {
            error_msg.push("ควรกรอกข้อมูล จำนวนเงิน");
            bvalid = false;
        }


        if (bvalid) {
            return TRUE;

        } else {
            alert(error_msg.join("\n"));
            return false;
        }
    });

});