jQuery(function() {

    $("#query-date-from").datepicker({
        defaultDate: "+0d",
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 1,
        onClose: function(selectedDate) {
            $("#query-date-to").datepicker("option", "minDate", selectedDate);
        }
    });
    $("#query-date-to").datepicker({
        defaultDate: "+0d",
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 1,
        maxDate: "-1d",
        onClose: function(selectedDate) {
            $("#query-date-from").datepicker("option", "maxDate", selectedDate);
        }
    });
});



