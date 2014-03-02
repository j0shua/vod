$(function() {
    $("#main-form").submit(function() {
        var error_msg = [];
       // error_msg.push("=== โปรดกรอกข้อมูล ===");
        var b_valid = true;
        $("#data").val($.trim($("#data").val()));

        if ($("#data").val() === '') {
            b_valid = false;
            error_msg.push("กรุณาพิมพ์ งานที่ต้องการส่ง");
        }
        if (b_valid) {
            return true;
        } else {
            alert(error_msg.join("\n"));
            return false;
        }
    });
});
