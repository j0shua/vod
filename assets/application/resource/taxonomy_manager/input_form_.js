$(function(){
     $(".btn-help").tooltip({
        track: true
    });
       $("#main-form").submit(function() {
        var b_valid = true;
        var error_msg = ["== โปรดตรวจสอบข้อมูล =="];
        $("#title").val($.trim($("#title").val()));
        if ($("#title").val() === '') {
            b_valid = false;
            error_msg.push("ต้องใส่ชื่อชุดวิดีโอ");
        }
        if (b_valid) {
            return true;
        } else {
            alert(error_msg.join("\n"));
            return false;
        }

    });
});