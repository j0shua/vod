// example
//  $("#username_txt").typeonly("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_");
//
//
jQuery.fn.typeonly = function() {
    var str = arguments[0] || {};
    $(this).each(function() {
        $(this).keypress(function (e) {
            var key;
            var keychar;
            if (window.event)
                key = window.event.keyCode;
            else if (e)
                key = e.which;
            else
                return true;
            keychar = String.fromCharCode(key);

            if ((key==null) || (key==0) || (key==8) ||
                (key==9) || (key==13) || (key==27) )
                return true;
            else if (((str).indexOf(keychar) > -1)){
                return true;
            }
            else{
                return false;
            }
        });
    });
};