$(function() {
//    var old_active = $('#active').val();
//    if (old_active === '1' || old_active === '0') {
//        
//    }
    $(".number-only").typeonly("0123456789");
    $('#birthday').datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: "1953:-5Y"
    });
    $('#main-form').submit(function() {
        var bvalid = true;
        var error_message = [];
//        if (old_active === '1' || old_active === '0') {
//            if($('#active').val()==='2'){
//                bvalid = false;
//                error_message.push("ไม่สามารถ");
//            }
//        }
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