$(function() {
    $("#main-form").submit(function() {
        form_submit();
        return false;

    });
    function form_submit() {
        $.ajax({
            type: "POST",
            url: ajax_check_coupon_url,
            data: data,
            dataType: "json",
            async: false
        }).done(function(json) {
            console.log(jqXHR);

        }).fail(function(jqXHR, textStatus) {
            alert("Request failed: " + textStatus + " " + jqXHR.status + " " + jqXHR.statusText);
            console.log(jqXHR);
        }).always(function() {
            return false;
        });
    }
});


