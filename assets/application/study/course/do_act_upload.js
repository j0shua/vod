$(function() {
    var file_valid = true;
    $("#main-form").submit(function() {
        var error_msg = [];
        //error_msg.push("=== โปรดกรอกข้อมูล ===");
        var b_valid = true;


        if ($("#file_upload").val() === '') {
            b_valid = false;
            error_msg.push("กรุณาเลือกไฟล์ที่ต้องการอัพโหลด");
        }



        if (b_valid && file_valid) {
            return true;
        } else {
            alert(error_msg.join("\n"));
            return false;
        }
    });
    $('#file_upload').bind('change', function() {
        var file_type = this.files[0].name.split('.').pop();
        if (!inArray(file_type, send_act_allowed_types)) {
            alert("ประเภทไฟล์ " + file_type + " ไม่ได้รับอนุญาตให้ใช้งาน");
            $('#file_upload').val("");
            file_valid = false;
        } else if (this.files[0].size > send_act_max_size) {
            alert('ไฟล์นี้มีขนาด: ' + this.files[0].size / 1048576 + "MB\nขนาดไฟล์ต้องน้อยกว่า" + send_act_max_size / 1048576 + "\nโปรดเลือกไฟล์ใหม่");
            $('#file_upload').val("");
            file_valid = false;
        }

    });

});
function inArray(needle, haystack) {
    var length = haystack.length;
    for (var i = 0; i < length; i++) {
        if (haystack[i] == needle)
            return true;
    }
    return false;
}