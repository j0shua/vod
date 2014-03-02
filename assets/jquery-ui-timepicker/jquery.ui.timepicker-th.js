jQuery(function($){
    $.timepicker.regional['th'] = {
        hourText: 'ชั่วโมง',
        minuteText: 'นาที',
        amPmText: ['AM', 'PM'] ,
        closeButtonText: 'ปิด',
        nowButtonText: 'ตอนนี้',
        deselectButtonText: 'ยกเลิก'
    }
    $.timepicker.setDefaults($.timepicker.regional['th']);
});