$(function() {

    $("#main-form").submit(function() {
        $("#email").val($.trim($("#email").val()));
        if ($("#email").val() == '' || $("#password").val() == '') {
            return false;
        }
        return true;
    });

});