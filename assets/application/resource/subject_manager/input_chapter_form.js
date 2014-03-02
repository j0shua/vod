$(function() {
     $(".btn-help").tooltip({
        track: true
    });
    $("#main-form").submit(function() {
        $("#chapter_title").val($.trim($("#chapter_title").val()));
        if ($("#chapter_title").val() === '') {
            alert('กรุณากรอกข้อมูล');
            return false;
        }
        return true;
    });
});