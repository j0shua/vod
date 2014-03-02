$(function() {
 $(".btn-help").tooltip({
        track: true
    });
    $(".number-only").typeonly("0123456789");
    $('#birthday').datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: "1953:-5Y"
    });
    $('#main-form').submit(function() {
        return true;
    });
    $(function() {
        $("#school_name").autocomplete({
            source: ajax_school_name_url,
            minLength: 2,
            select: function(event, ui) {
//                log(ui.item ?
//                        "Selected: " + ui.item.value + " aka " + ui.item.id :
//                        "Nothing selected, input was " + this.value);
            }
        });
    });

});
