<?php

$system_root = '/var/www/clients/client2/web10/';
$basename = basename($_SERVER['HTTP_HOST']);
$config['is_system_host'] = TRUE;
$config['is_parent_site'] = FALSE;
$config['parent_site_url'] = 'http://www.prokru.com/v2/';
$config['make_money'] = TRUE; //เก็บเงิน 
$config['true_gid'] = 125;
$config['is_rtmp'] = TRUE; //เก็บเงิน 
$config['standard_unit_price'] = 26; //ราคาปกติ 
$config['site_name'] = 'educasy.com'; // ชื่อเว็บไซต์
$config['site_id'] = 2; // ชื่อเว็บไซต์
$config['username_field'] = 'email'; // ชื่อเว็บไซต์
$config['call_center'] = '08-4422-5111'; // ชื่อเว็บไซต์
$config['cgi_upload_url'] = 'http://' . $basename . '/cgi-bin/fileuploader.pl'; //url for cgi
$config['temp_folder'] = $system_root . 'temp/';
$config['cgi_upload_dir'] = 'upload_temp/';
$config['video_file_size_limit'] = 2147483648; // ขนาดของ video
$config['video_extension_whitelist'] = array("mpeg", "avi", "vob", "mp4", "mpg", "flv", "wmv", "rmvb"); // วิดีโอที่อัพโหลดได้
$config['full_video_dir'] = $system_root . 'video/';
$config['upload_temp_dir'] = $config['temp_folder'] . 'upload_temp/'; //ที่เก็บ temp video video upload
$config['normal_disk_quota'] = ($config['make_money']) ? 0 : -1;
$config['doc_file_size_limit'] = 2147483648; //ขนาดไฟล์เอกสารที่สามารถ upload ได้
$config['doc_extension_whitelist'] = array('vsdx', 'vsd', 'accdb', 'zip', 'rar', 'pptx', "doc", "docx", "pdf", 'xlm', 'ppt', 'swf'); // ไฟล์เอกสารที่สามารถอัพโหลดได้
$config['full_doc_dir'] = $system_root . 'site_files/document/'; //krueonline
$config['full_image_dir'] = $system_root . 'site_files/image/'; //krueonline
$config['image_file_size_limit'] = 2147483648; //ขนาดไฟล์เอกสารที่สามารถ upload ได้
$config['image_extension_whitelist'] = array("eps", "png", "bmp", "jpg", 'gif', 'jpeg', 'pdf'); // ไฟล์เอกสารที่สามารถอัพโหลดได้
$config['flash_media_dir'] = 'flash_media/';
$config['full_flash_media_dir'] = FCPATH . 'flash_media/';
$config['flash_media_file_size_limit'] = 2147483648; //ขนาดไฟล์เอกสารที่สามารถ upload ได้
$config['flash_media_upload_extension_whitelist'] = array("zip"); // ไฟล์เอกสารที่สามารถอัพโหลดได้
$config['flash_media_extension_whitelist'] = array('css', 'js', 'png', 'gif', 'jpeg', 'jpg', 'tpl', 'html', 'swf'); // ไฟล์เอกสารที่สามารถอัพโหลดได้
$config['full_video_thumbnail_dir'] = $system_root . 'site_files/video_thumbnail/';
$config['avatar_dir'] = $system_root . 'site_files/avatar/';
$config['personal_document_dir'] = $system_root . 'site_files/personal_document/';
$config['send_email'] = TRUE;
$config['site_email'] = 'lojorider@gmail.com'; //Email สำหรับ แสดงหน้าเว็บไซต์ เป็นอีเมล์สำหรับใช้ในการติดต่อ
$config['alert_email'] = 'lojorider@gmail.com'; //Email สำหรับการแจ้งเตือนมาจากระบบ
$config['noreply_email'] = 'noreply@educasy.com'; //Email สำหรับ ส่งจากระบบ 
$config['netConnectionUrl_parent'] = 'rtmpe://203.146.170.199:1936/pkv2'; //url สำหรับ falsh streamming
$config['netConnectionUrl'] = 'rtmpe://' . $basename . ':1936/educasy'; //url สำหรับ falsh streamming  
$config['request_trigger_time'] = 20; //หน่วงเวลาส่งข้อมูล player
$config['full_video_upload_ftp_dir'] = $system_root . 'upload_ftp/'; //ftp folder
$config['full_encode_log_dir'] = $config['temp_folder'] . 'encode_log/'; //encode log folder
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
$config['facebook_permissions'] = 'basic_info,email,user_about_me';
$config['facebook_permissions_post'] = 'email,publish_stream';
//config สำหรับ Google
$config['analytics'] = 'UA-30307998-2';
$config['adsense'] = '';
$config['template_name'] = 'educasy_v2';
$config['template_menu_view'] = 'user_menu_bs';
