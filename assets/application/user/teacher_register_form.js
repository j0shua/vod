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
//        var bvalid = true;
//        $('#email').each(function(i) {
//            console.log($(this).val());
//            $(this).val($.trim($(this).val()));
//            if ($(this).val() === '') {
//                bvalid = false;
//            }
//        });
//        if ($('#identity_document').val() === '') {
//            bvalid = false;
//        }
//        if ($('#educational_document').val() === '') {
//            bvalid = false;
//        }
//
//        if (!bvalid) {
//            alert("กรุณากรอกข้อมูลให้ครับถ้วน");
//            return false;
//        }
        return true;
    });

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
