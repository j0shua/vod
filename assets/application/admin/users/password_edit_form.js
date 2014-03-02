$(function() {
    $("#main-form").submit(function() {
        if ($("#password").val() == '') {
            alert("กรอกข้อมูลรหัสผ่าน");
            return false;
        } else {
            return true;
        }

    });
});
