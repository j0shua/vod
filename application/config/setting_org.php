<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

//$system_root = '/var/www/clients/client2/web10/';
$system_root = '/var/www/clients/client2/web1/web/dev2013/';
$is_system_host = ($_SERVER['HTTP_HOST'] == 'www.prokru.com') ? TRUE : FALSE; //อยู่ในระบบจริงหรือไม่
$config['is_system_host'] = $is_system_host;
$config['make_money'] = FALSE; //เก็บเงิน
$config['is_rtmp'] = TRUE; //เก็บเงิน
$config['standard_unit_price'] = 26; //ราคาปกติ
$config['site_name'] = 'Prokru.com'; // ชื่อเว็บไซต์
$config['call_center'] = '08-4422-5111'; // ชื่อเว็บไซต์
$config['cgi_upload_url'] = 'http://' . $_SERVER['HTTP_HOST'] . '/cgi-bin/fileuploader.pl'; //url for cgi

$config['video_file_size_limit'] = 2147483648; // ขนาดของ video
$config['video_extension_whitelist'] = array("mpeg", "avi", "vob", "mp4", "mpg", "flv", "wmv", "rmvb"); // วิดีโอที่อัพโหลดได้
// วิดีโอที่อัพโหลดได้
//$config['video_mime_type_whitelist'] = array(
//    'video/x-flv', // flv
//    'video/mpeg',
//    'video/x-msvideo',
//    'video/x-sgi-movie',
//    'video/quicktime',
//    'audio/mpeg',
//    'video/x-ms-wmv',
//    'video/msvideo',
//    'video/mp4'
//);

//ที่อยู๋ของวิดีโอ
$config['full_video_dir'] = $system_root.'video/';
if (!$is_system_host) {
    $config['full_video_dir'] = FCPATH . 'video/';
}

//ที่เก็บ temp video video upload
$config['upload_temp_dir'] = $system_root.'upload_temp/'; //krueonline
if (!$is_system_host) {
    $config['upload_temp_dir'] = FCPATH . 'upload_temp/';
}
$config['normal_disk_quota'] = -1; //วิดีโอที่สามารถอัพโหลดได้


$config['doc_file_size_limit'] = 2147483648; //ขนาดไฟล์เอกสารที่สามารถ upload ได้
$config['doc_extension_whitelist'] = array('vsdx','vsd','accdb','zip','pptx',"doc", "docx", "pdf", 'xlm', 'ppt'); // ไฟล์เอกสารที่สามารถอัพโหลดได้
// ไฟล์เอกสารที่สามารถอัพโหลดได้
//$config['doc_mime_type_whitelist'] = array(
//    'application/msword', // flv
//    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
//);

//ที่อยู่ของไฟล์เอกสาร
$config['full_doc_dir'] = $system_root.'site_files/document/'; //krueonline
if (!$is_system_host) {
    $config['full_doc_dir'] = FCPATH . 'site_files/document/';
}

//$config['normal_doc_quota'] = 1048576; //ขีดจำกัดการอัพโหลด ไฟล์เอกสาร


// ไฟล์ภาพที่สามารถอัพโหลดได้
//$config['image_mime_type_whitelist'] = array(
//    'application/msword', // flv
//    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
//);

//ที่อยู่ของไฟล์เอกสาร
$config['full_image_dir'] = $system_root.'site_files/image/'; //krueonline
if (!$is_system_host) {
    $config['full_image_dir'] = FCPATH . 'site_files/image/';
}
$config['image_file_size_limit'] = 2147483648; //ขนาดไฟล์เอกสารที่สามารถ upload ได้
$config['image_extension_whitelist'] = array("eps","png", "bmp", "jpg", 'gif', 'jpeg'); // ไฟล์เอกสารที่สามารถอัพโหลดได้
//ที่อยู่ของไฟล์ภาพตัวอย่างวิดีโอ
$config['full_video_thumbnail_dir'] = $system_root.'site_files/video_thumbnail/';
if (!$is_system_host) {
    $config['full_video_thumbnail_dir'] = FCPATH . 'site_files/video_thumbnail/';
}

//ที่อยู๋ของไฟล์รูปภาพประจำตัว
$config['avatar_dir'] = $system_root.'site_files/avatar/';
if (!$is_system_host) {
    $config['avatar_dir'] = FCPATH . 'site_files/avatar/';
}

$config['analytics'] = 'UA-30307998-2';
$config['adsense'] = '';
$config['site_email'] = 'lojorider@gmail.com'; //Email สำหรับ แสดงหน้าเว็บไซต์ เป็นอีเมล์สำหรับใช้ในการติดต่อ
$config['alert_email'] = 'lojorider@gmail.com'; //Email สำหรับการแจ้งเตือนมาจากระบบ
$config['noreply_email'] = 'noreply@prokru.com'; //Email สำหรับ ส่งจากระบบ 
$config['netConnectionUrl'] = 'rtmpe://203.146.170.199:1936/dev2013'; //url สำหรับ falsh streamming
$config['request_trigger_time'] = 20; //หน่วงเวลาส่งข้อมูล player
$config['full_video_upload_ftp_dir'] =$system_root.'upload_ftp/'; //ftp folder
//encode log folder
$config['full_encode_log_dir'] = $system_root.'encode_log/';
if (!$is_system_host) {
    $confifg['full_encode_log_dir'] = FCPATH . 'encode_log/';
}
//config สำหรับการเรียนการสอน
$config['send_act_upload_dir'] = $system_root.'site_files/send_act/'; //krueonline
if (!$is_system_host) {
    $config['send_act_upload_dir'] = FCPATH . 'site_files/send_act/';
}
$config['send_act_allowed_types'] = 'docx|doc|txt|pdf';
$config['send_act_max_size'] = '2048';

//config สำหรับ xelatex
$config['itemize_char'] = array(
    'th' => array('ก', 'ข', 'ค', 'ง', 'จ', 'ฉ', 'ช', 'ซ', 'ฌ', 'ญ', 'ฐ', 'ฑ', 'ฒ', 'ณ', 'ด', 'ต', 'ถ', 'ท', 'ธ', 'น', 'บ', 'ป', 'ผ', 'ฝ', 'พ', 'ฟ', 'ภ', 'ม', 'ย', 'ร', 'ล', 'ว', 'ศ', 'ษ', 'ส', 'ห', 'ฬ', 'อ', 'ฮ'),
    'en' => array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't'),
     'num' => range(1, 100)
);
//config สำหรับ facebook
$config['facebook_appId'] = '159353560905785';
$config['facebook_secret'] = '0373d23ccc4f7efbdab62b86af59aed6';
$config['facebook_permissions'] = 'email';
$config['facebook_permissions_post'] = 'email,publish_stream';



