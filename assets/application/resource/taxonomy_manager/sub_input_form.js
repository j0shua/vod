$(function() {
    $(".btn-help").tooltip({
        track: true
    });
    $("#main-form").submit(function() {
        var b_valid = true;
        var error_msg = ["== โปรดตรวจสอบข้อมูล =="];
        $("#title").val($.trim($("#title").val()));
        if ($("#title").val() === '') {
            b_valid = false;
            error_msg.push("ต้องใส่ชื่อบท");
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
    $("#btn-search-resource").click(function() {
        $("#inner-dialog").html('<iframe title="โปรดรอ" id="iframe-resource-browser" src="' + iframe_video_manager_url + '">โปรดรอ</iframe>');
        $("#dialog").dialog("open");
    });
});
function add_resource_id(id, array_resource_id) {
    $("#" + id).val(array_resource_id.join(","));
    $("#dialog").dialog("close");
}