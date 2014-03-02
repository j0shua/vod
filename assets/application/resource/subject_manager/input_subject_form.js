$(function() {
     $(".btn-help").tooltip({
        track: true
    });
    $("#main-form").submit(function() {
        $("#title").val($.trim($("#title").val()));
        if ($("#title").val() == '') {
            alert('กรุณากรอกข้อมูล');
            return false;
        }
        return true;
    });
});