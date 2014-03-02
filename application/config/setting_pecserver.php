<?php
$system_root = '/var/www/';
$config['is_system_host'] = TRUE;
$config['make_money'] = FALSE; //เก็บเงิน
$config['is_rtmp'] = FALSE; //เก็บเงิน
$config['standard_unit_price'] = 26; //ราคาปกติ
$config['site_name'] = 'vod-researchproject.info'; // ชื่อเว็บไซต์
$config['call_center'] = '08-4422-5111'; // ชื่อเว็บไซต์
$config['cgi_upload_url'] = 'http://' . $_SERVER['HTTP_HOST'] . '/cgi-bin/fileuploader.pl'; //url for cgi
$config['cgi_upload_dir'] = 'upload_temp/';
$config['video_file_size_limit'] = 2147483648; // ขนาดของ video
$config['video_extension_whitelist'] = array("mpeg", "avi", "vob", "mp4", "mpg", "flv", "wmv", "rmvb"); // วิดีโอที่อัพโหลดได้
//ที่อยู่ของวิดีโอ
$config['full_video_dir'] = $system_root . 'video/';
//ที่เก็บ temp video video upload
$config['upload_temp_dir'] = $system_root . 'upload_temp/'; //krueonline
if ($config['make_money']) {
    $config['normal_disk_quota'] = 0; //วิดีโอที่สามารถอัพโหลดได้
} else {
    $config['normal_disk_quota'] = -1; //วิดีโอที่สามารถอัพโหลดได้
}
$config['doc_file_size_limit'] = 2147483648; //ขนาดไฟล์เอกสารที่สามารถ upload ได้
$config['doc_extension_whitelist'] = array('vsdx', 'vsd', 'accdb', 'zip', 'rar', 'pptx', "doc", "docx", "pdf", 'xlm', 'ppt', 'swf'); // ไฟล์เอกสารที่สามารถอัพโหลดได้
//ที่อยู่ของไฟล์เอกสาร
$config['full_doc_dir'] = $system_root . 'site_files/document/'; //krueonline
//ที่อยู่ของไฟล์เอกสาร
$config['full_image_dir'] = $system_root . 'site_files/image/'; //krueonline
$config['image_file_size_limit'] = 2147483648; //ขนาดไฟล์เอกสารที่สามารถ upload ได้
$config['image_extension_whitelist'] = array("eps", "png", "bmp", "jpg", 'gif', 'jpeg'); // ไฟล์เอกสารที่สามารถอัพโหลดได้
$config['flash_media_dir'] = 'flash_media/';
$config['full_flash_media_dir'] = FCPATH . 'flash_media/';
$config['flash_media_file_size_limit'] = 2147483648; //ขนาดไฟล์เอกสารที่สามารถ upload ได้
$config['flash_media_upload_extension_whitelist'] = array("zip"); // ไฟล์เอกสารที่สามารถอัพโหลดได้
$config['flash_media_extension_whitelist'] = array('css', 'js', 'png', 'gif', 'jpeg', 'jpg', 'tpl', 'html', 'swf'); // ไฟล์เอกสารที่สามารถอัพโหลดได้
//ที่อยู่ของไฟล์ภาพตัวอย่างวิดีโอ
$config['full_video_thumbnail_dir'] = $system_root . 'site_files/video_thumbnail/';
//ที่อยู๋ของไฟล์รูปภาพประจำตัว
$config['avatar_dir'] = $system_root . 'site_files/avatar/';
$config['personal_document_dir'] = $system_root . 'site_files/personal_document/';
$config['analytics'] = 'UA-30307998-2';
$config['adsense'] = '';
$config['site_email'] = 'lojorider@gmail.com'; //Email สำหรับ แสดงหน้าเว็บไซต์ เป็นอีเมล์สำหรับใช้ในการติดต่อ
$config['alert_email'] = 'lojorider@gmail.com'; //Email สำหรับการแจ้งเตือนมาจากระบบ
$config['noreply_email'] = 'noreply@prokru.com'; //Email สำหรับ ส่งจากระบบ 
$config['netConnectionUrl'] = 'rtmpe://203.146.170.199:1936/vod_researchproject'; //url สำหรับ falsh streamming
$config['request_trigger_time'] = 20; //หน่วงเวลาส่งข้อมูล player
$config['full_video_upload_ftp_dir'] = $system_root . 'upload_ftp/'; //ftp folder
//encode log folder
$config['full_encode_log_dir'] = $system_root . 'encode_log/';
//config สำหรับการเรียนการสอน
$config['send_act_upload_dir'] = $system_root . 'site_files/send_act/'; //krueonline
$config['send_act_allowed_types'] = 'docx|doc|txt|pdf';
$config['send_act_max_size'] = '2097152';
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