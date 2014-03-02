function check_idcard_format(id) {
    var id_state = false;
    if(id.length != 13) return false;
    for(i=0, sum=0; i < 12; i++)
        sum += parseFloat(id.charAt(i))*(13-i);
    if((11-sum%11)%10!=parseFloat(id.charAt(12))) id_state = false;
    else id_state = true;
    return id_state;
}

function checkEmail(email) {
    return checkRegexp(email, /^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z0-9]{2,4})+$/);
}
function checkRegexp(email, regexp) {
    if (!(regexp.test(email))) {
        return false;
    } else {
        return true;
    }

}
//function typeOnly(e,str)
//{
//    var key;
//    var keychar;
//
//    if (window.event)
//        key = window.event.keyCode;
//    else if (e)
//        key = e.which;
//    else
//        return true;
//    keychar = String.fromCharCode(key);
//
//    if ((key==null) || (key==0) || (key==8) ||
//        (key==9) || (key==13) || (key==27) )
//        return true;
//
//    else if (((str).indexOf(keychar) > -1)){
//        return true;
//    }
//    else{
//        return false;
//    }
//}

function check_radio(n){
    if($("input[name="+n+"]:checked").val()==undefined){
        return false;
    }else{
        return true;
    }
}