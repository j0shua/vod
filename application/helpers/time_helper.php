<?php

function mktimestamp($date_time) {


    //$date_time = '10/11/2012 22:11';
    if (count(explode(' ', $date_time)) < 2) {
        $date_time .= ' 00:00';
    }
    list($date, $time) = explode(' ', $date_time);
    list($day, $month, $year) = explode('/', $date);
    $time = explode(':', $time);
    if (count($time) > 2) {
        list($hour, $minute, $second) = $time;
    } else {
        $second = 0;
        list($hour, $minute) = $time;
    }
    $hour = (int) ($hour);
    $minute = (int) ($minute);
    $second = (int) ($second);
    $month = (int) ($month);
    $day = (int) ($day);
    $year = (int) ($year);
//    echo $hour, '-', $minute, '-', $second, '-', $month, '-', $day, '-', $year;
//    exit;
    return mktime($hour, $minute, $second, $month, $day, $year);
}

function thdate($format, $timestamp = '', $thai_year = FALSE) {
    if ($timestamp == '') {
        $timestamp = time();
    }
    $str_date = date($format, $timestamp);

    $month_en = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
    $month_th = array("ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");

    $month_long_en = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
    $month_long_th = array('มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', '	ตุลาคม', 'พฤศจิกายน', 'ธันวาคม');

    $day_en = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
    $day_th = array("วันอาทิตย์", "วันจันทร์", "วันอังคาร", "วันพุธ", "วันพฤหัสบดี", "วันศุกร์", "วันเสาร์");

    $d_en = array("Sun", "Mon", "Tues", "Wednes", "Thurs", "Fri", "Satur");
    $d_th = array("อาทิตย์", "จันทร์", "อังคาร", "พุธ", "พฤหัสบดี", "ศุกร์", "เสาร์");
    $search = array_merge($month_long_en, $day_en, $d_en);
    $replace = array_merge($month_long_th, $day_th, $d_th);
    $str_date = str_replace($search, $replace, $str_date);
    $str_date = str_replace($month_en, $month_th, $str_date);

    if ($thai_year) {
        $year_en = date('Y', $timestamp);
        $year_th = $year_en + 543;
        $str_date = str_replace($year_en, $year_th, $str_date);
    }

    return $str_date;
}