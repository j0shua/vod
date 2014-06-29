<?php

/**
 * Description of demo
 *
 * @author lojoriderrefresh
 * @property demo_model $demo_model
 * @property video_upload_model $video_upload_model
 * @property doc_manager_model $doc_manager_model 
 * @property disk_quota_service_model $disk_quota_service_model 
 * @property  video_upload_model $video_upload_model
 * @property MY_Email $email
 * @property  fb_model $fb_mode
 * @property xelatex_dycontent_separate_model $xelatex_dycontent_separate_model
 * @property excel $excel
 * @property unzip $unzip
 * @property dycontent_model $dycontent_model
 */
class demo extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->backup_sql();
        //exec("Backup");
    }

    function index() {
        $this->template->write_view('demo/menu');
        $this->template->render();
    }

    function menu() {
        $this->template->render();
    }

    function make_personal_dir($uid) {
        echo $this->auth->make_personal_dir($uid);
    }

    function phpinfo() {
        echo phpinfo();
    }

    function time() {
        echo time();
    }

    function canupload() {
        $this->load->model('service/disk_quota_service_model');
        if ($this->disk_quota_service_model->can_upload())
            exit("CAN");
        exit("NOT CAN");
    }

    function backup_sql() {
        // Load the DB utility class
        $this->load->dbutil();

// Backup your entire database and assign it to a variable
        $backup = & $this->dbutil->backup();

// Load the file helper and write the file to your server
        // $this->load->helper('file');
        // write_file(FCPATH . 'database.gz', $backup);
    }

    function refresh() {
        $data = array(
            'time' => 100000,
            'url' => site_url(''),
            'heading' => 'Title',
            'message' => '<p>Title Title Title Title Title Title </p>'
        );
        $this->load->view('refresh_page', $data);
    }

    function file_ext() {
        $path_info = pathinfo('test.mp4');
        echo '<pre>';
        print_r($path_info);
        echo '</pre>';
    }

    function video_info() {

        /*      $extension = "ffmpeg";
          $extension_soname = $extension . "." . PHP_SHLIB_SUFFIX;
          $extension_fullname = PHP_EXTENSION_DIR . "/" . $extension_soname;

          load extension
          if (!extension_loaded($extension)) {
          dl($extension_soname) or die("Can't load extension $extension_fullname\n");
          } */
        $file_input = $this->config->item('full_video_dir') . 'test.mp4';
        echo $file_input;
        echo '<br>';
        $movie = new ffmpeg_movie($file_input);
        echo 'duration : ' . $movie->getDuration();
        echo '<br>';
        echo 'vcodec : ' . $movie->getVideoCodec();
        echo '<br>';
        echo 'acodec : ' . $movie->getAudioCodec();

        /* $file_input = $this->config->item('video_dir') . 'test2.flv';
          $movie = new ffmpeg_movie($file_input);
          echo 'duration' . $movie->getDuration();

          $mime = mime_content_type($this->config->item('video_dir') . 'test.mp4');
          echo $mime; */
    }

    function exec_command() {
        // 
        $command = 'ls';
        $out = array();
        //$command ="ffmpeg -i /var/www/clients/client3/web16/web/temp/video_upload_temp/8yCeOCQ0obOjvu26UJl46Q.mpg /var/www/clients/client3/web16/web/temp/video_upload_temp/xx.flv";
        exec($command, $out);
        echo $command;
        print_r($out);
    }

    function make_dir() {
        $mode = 0777;
        //mkdir($this->config->item('video_dir').'test/', 0755);
        chmod($this->config->item('video_dir') . 'test/d2', 0777);
        $dir = $this->config->item('video_dir') . 'test/d2';
//        $dir = explode('/', trim($dir, '/'));
//        $dir_str = '/';
//        foreach ($dir as $v) {
//            if (!is_dir($dir_str . $v)) {
//                mkdir($dir_str . $v, 0755);
//            } else {
//                $dir_str .=$v . '/';
//            }
//        }
        if (!is_dir($dir)) {
            mkdir($dir, $mode, TRUE);
        }
        //chown($dir,"aun");
        echo md5(microtime(true));
    }

    /**
     * make data for save to database
     * @param type $data 
     */
    private function encode_data($file_path) {
        return $file_path;
    }

    private function decode_data($data) {
        return $data;
    }

    public function ceil() {
        echo ceil(3 / 10000);
    }

    function compress() {
        $data = array(
            'test' => 'xxxxxxxxxxxxxxxxxx'
        );
        //echo $data;
        $data = serialize($data);
        //  $data = gzcompress($data);
        echo $data;
    }

    function ffmpeg() {
        $command = 'ffmpeg -i /var/www/nt/video/adele.flv /var/www/nt/video/adele.mp3';
        //$command = 'ls';
        exec($command, $output);
        var_dump($output);
    }

    function encode_video() {
        $this->load->model('resource/video_upload_model');
        $input_file = FCPATH . 'files/video/adele.flv';
        $output_file = FCPATH . 'files/video/adele.mp3';
        if ($this->video_upload_model->encode_vdo($input_file, $output_file)) {
            echo 'complete';
        } else {
            echo 'no complete';
        }
    }

    function get_video_detail($input_file) {
        $input_file = FCPATH . $input_file;
        $movie = new ffmpeg_movie($input_file);
        $video_detail = array();
        if ($movie->hasVideo()) {


            $video_detail = array(
                'VideoCodec' => $movie->getVideoCodec(),
                'VideoBitRate' => $movie->getVideoBitRate(),
                'AudioCodec' => $movie->getAudioCodec(),
                'AudioBitRate' => $movie->getAudioBitRate(),
                'AudioSampleRate' => $movie->getAudioSampleRate(),
                'FrameHeight' => $movie->getFrameHeight(),
                'FrameRate' => $movie->getFrameRate(),
                'hasVideo' => $movie->hasVideo(),
                'AudioChannels' => $movie->getAudioChannels()
            );
        }
        echo '<pre>';
        print_r($video_detail);
        echo '</pre>';
    }

    function flowplayer($type = 'rtmp') {
        //print_r($path_info);
        $this->template->load_flowplayer();
        //$data['netConnectionUrl'] = $this->config->item('netConnectionUrl');
        switch ($type) {
            case 'rtmp':
                $data['netConnectionUrl'] = 'rtmp://' . basename($_SERVER['HTTP_HOST']) . ':1936/vod_researchproject';
                //$data['netConnectionUrl'] = 'rtmp://172.20.70.10:1936/vod_researchproject';

                break;
            case 'rtmpe':
                $data['netConnectionUrl'] = 'rtmpe://' . basename($_SERVER['HTTP_HOST']) . ':1936/vod_researchproject';

                break;
            default:
                break;
        }


        //$data['netConnectionUrl'] = 'rtmpe://203.146.170.199:1936/vod_researchproject';
        $data['video_path'] = '1/2/1384938352.flv';
        //$data['video_path'] = '1/2/1384938352222222.flv';
        $this->template->write_view('demo/flowplayer_rtmp', $data);
        $this->template->render();
    }
    
     function video_js() {
        $this->template->load_video_js();
        
        $data['video_path'] = 'rtsp://www.vod-researchproject.info:1936/vod_researchproject/sample2.mp4';
        $data['video_path_iphone'] = 'http://www.vod-researchproject.info:1936/vod_researchproject/mp4:sample2.mp4/playlist.m3u8';
        $this->template->write_view('demo/video_js', $data);
        $this->template->render(); 
    }

    function youtube() {
        $url = 'http://www.youtube.com/watch?v=9bZkp7q19f0&feature=related';
        $url_string = parse_url($url, PHP_URL_QUERY);
        parse_str($url_string, $args);
        if (isset($args['v'])) {
            echo $args['v'];
        } else {
            echo 'FALSE';
        }
    }

    function dailymotion() {
        $url = 'http://www.dailymotion.com/video/xt5jyg_showbiz-news-lindsay-lohan-cleared-oprah-highest-paid-celeb_shortfilms?v=ddd';
        $url_string = parse_url($url, PHP_URL_PATH);
        print_r($url_string);
        $url_string = explode('/', trim($url_string, '/'));
        $video_id = current(explode('_', $url_string[1]));
        print_r($video_id);
    }

    function filelist() {
//        $dir = FCPATH;
//        foreach (glob($dir."*.*") as $filename) {
//            print_r(basename($filename));
//            
//            //echo "$filename size " . filesize($filename) . "\n";
//        }
        $this->load->model('resource/doc_manager_model');
        $this->doc_manager_model->clean_doc_file();
    }

    function test() {
        echo '';
    }

    function sendmail() {
        $this->load->library('email');
        $this->email->to('lojorider@gmail.com');
        $this->email->subject('มีสมาชิกเติมเงิน');
        $this->email->message('ทดสอบ' . $this->config->item('site_email'));
        $this->email->send();
        echo $this->email->print_debugger();
    }

    function make_encode_opt($filename) {
        $this->load->model('resource/video_upload_model');
        $input_file = $this->video_upload_model->full_video_upload_temp_dir . $filename;
        $output_file = $this->video_upload_model->full_video_upload_temp_dir . 'test.flv';
        $this->video_upload_model->is_video($input_file);
        $options = $this->video_upload_model->make_encode_options($input_file);
        echo "ffmpeg  -i $input_file $options $output_file 1>/dev/null 2>&1 &";
    }

    function encodevideo() {
        $input_file = FCPATH . 'files/video/org.flv';
        $output_file = FCPATH . 'files/video/new.mp4';
        $log_file = FCPATH . 'files/video/video.log';
        $options = '-vcodec flv';
        //$command = "ffmpeg  -i $input_file  $output_file 1>/dev/null 2>&1 && rm $input_file >/dev/null &";
        $command = "ffmpeg -y -i $input_file  $output_file 1>$log_file 2>&1 &";
        //$command = 'ls';
        exec($command, $rr);
        print_r($rr);
        echo $command;
        echo 'เสร็จ';
    }

    function encodecomplete() {
        $log_file = FCPATH . 'files/video/video.log';
        $this->load->helper('file');
        $string = read_file($log_file);
        //echo count(explode("\n",$string));
        $string = end(explode("\r\n", $string));
        //echo $string;
        if (stripos($string, "headers") !== false) {
            echo "True";
        } else {
            echo 'NO';
        }
        echo '-' . $string;
    }

    function facebook() {

        $app_id = "242506035878738";
        $app_secret = "f4d4a90dca6bd5a4d7004f2ffa5deb90";
        $my_url = "http://www.educasy.com/demo/facebook_reg";
//        $content = "<fb:registration redirect-uri="https://developers.facebook.com/tools/echo/" fields="[
// {'name':'name'},
// {'name':'email'},
// {'name':'location'},
// {'name':'gender'},
// {'name':'birthday'},
// {'name':'password'},
// {'name':'like',       'description':'Do you like this plugin?', 'type':'checkbox',  'default':'checked'},
// {'name':'phone',      'description':'Phone Number',             'type':'text'},
// {'name':'anniversary','description':'Anniversary',              'type':'date'},
// {'name':'captain',    'description':'Best Captain',             'type':'select',    'options':{'P':'Jean-Luc Picard','K':'James T. Kirk'}},
// {'name':'force',      'description':'Which side?',              'type':'select',    'options':{'jedi':'Jedi','sith':'Sith'}, 'default':'sith'},
// {'name':'live',       'description':'Best Place to Live',       'type':'typeahead', 'categories':['city','country','state_province']},
// {'name':'captcha'}
//]" fb-xfbml-state="rendered" class="fb_iframe_widget"><span style="height: 712px; width: 600px;"><iframe scrolling="no" id="f16d769e6463926" name="f7dc951624d33" style="border: medium none; overflow: hidden; height: 712px; width: 600px;" class="fb_ltr" src="https://www.facebook.com/plugins/registration.php?api_key=113869198637480&amp;locale=th_TH&amp;sdk=joey&amp;channel_url=https%3A%2F%2Fs-static.ak.facebook.com%2Fconnect%2Fxd_arbiter.php%3Fversion%3D11%23cb%3Df119ca907d0b904%26origin%3Dhttps%253A%252F%252Fdevelopers.facebook.com%252Ff2059a296336e3e%26domain%3Ddevelopers.facebook.com%26relation%3Dparent.parent&amp;client_id=113869198637480&amp;fb_only=false&amp;fb_register=false&amp;fields=%5B%0A%20%7B'name'%3A'name'%7D%2C%0A%20%7B'name'%3A'email'%7D%2C%0A%20%7B'name'%3A'location'%7D%2C%0A%20%7B'name'%3A'gender'%7D%2C%0A%20%7B'name'%3A'birthday'%7D%2C%0A%20%7B'name'%3A'password'%7D%2C%0A%20%7B'name'%3A'like'%2C%20%20%20%20%20%20%20'description'%3A'Do%20you%20like%20this%20plugin%3F'%2C%20'type'%3A'checkbox'%2C%20%20'default'%3A'checked'%7D%2C%0A%20%7B'name'%3A'phone'%2C%20%20%20%20%20%20'description'%3A'Phone%20Number'%2C%20%20%20%20%20%20%20%20%20%20%20%20%20'type'%3A'text'%7D%2C%0A%20%7B'name'%3A'anniversary'%2C'description'%3A'Anniversary'%2C%20%20%20%20%20%20%20%20%20%20%20%20%20%20'type'%3A'date'%7D%2C%0A%20%7B'name'%3A'captain'%2C%20%20%20%20'description'%3A'Best%20Captain'%2C%20%20%20%20%20%20%20%20%20%20%20%20%20'type'%3A'select'%2C%20%20%20%20'options'%3A%7B'P'%3A'Jean-Luc%20Picard'%2C'K'%3A'James%20T.%20Kirk'%7D%7D%2C%0A%20%7B'name'%3A'force'%2C%20%20%20%20%20%20'description'%3A'Which%20side%3F'%2C%20%20%20%20%20%20%20%20%20%20%20%20%20%20'type'%3A'select'%2C%20%20%20%20'options'%3A%7B'jedi'%3A'Jedi'%2C'sith'%3A'Sith'%7D%2C%20'default'%3A'sith'%7D%2C%0A%20%7B'name'%3A'live'%2C%20%20%20%20%20%20%20'description'%3A'Best%20Place%20to%20Live'%2C%20%20%20%20%20%20%20'type'%3A'typeahead'%2C%20'categories'%3A%5B'city'%2C'country'%2C'state_province'%5D%7D%2C%0A%20%7B'name'%3A'captcha'%7D%0A%5D&amp;redirect_uri=https%3A%2F%2Fdevelopers.facebook.com%2Ftools%2Fecho%2F&amp;width=600"></iframe></span></fb:registration>";
        $content = '<div><iframe src="https://www.facebook.com/plugins/registration?
             client_id=' . $app_id . '&
             redirect_uri=' . $my_url . '&
             fields=name,birthday,gender,location,email,username"
        scrolling="auto"
        frameborder="no"
        style="border:none"
        allowTransparency="true"
        width="100%"
        height="330">
</iframe></div>';

        //$content = "<script> top.location.href='" . $dialog_url . "'</script>";
        $this->template->write($content);
        $this->template->render();
    }

    function facebook_reg() {
        define('FACEBOOK_APP_ID', '242506035878738');
        define('FACEBOOK_SECRET', 'f4d4a90dca6bd5a4d7004f2ffa5deb90');
        if ($_REQUEST) {
            echo '<p>signed_request contents:</p>';
            $response = $this->parse_signed_request($_REQUEST['signed_request'], FACEBOOK_SECRET);
            echo '<pre>';
            print_r($response);
            echo '</pre>';
        } else {
            echo '$_REQUEST is empty';
        }
    }

    function facebook_gettoken() {
        $this->load->model('fb_model');
        //$this->fb_model->pull_acess_token();
        echo $this->fb_model->set_access_token();
        echo $this->fb_model->get_access_token();

        echo $this->fb_model->test();
    }

    function facebook_post() {
        $this->load->model('fb_model');
//print_r($_SESSION);
        //exit();
        $facebook_access_token = $this->auth->get_facebook_access_token();
        $this->fb_model->set_access_token($facebook_access_token);

        $attachment = array(
            'message' => 'this is my message',
            'name' => 'This is my demo Facebook application!',
            'caption' => "Caption of the Post",
            'link' => 'http://www.educasy.com',
            'description' => 'this is a description',
//            'picture' => 'http://mysite.com/pic.gif',
            'actions' => array(
                array(
                    'name' => 'Get Search',
                    'link' => 'http://www.google.com'
                )
            )
        );

        $this->fb_model->api('/me/feed/', 'post', $attachment);
        //$result = $facebook->api('/me/feed/', 'post', $attachment);
    }

    function parse_signed_request($signed_request, $secret) {
        list($encoded_sig, $payload) = explode('.', $signed_request, 2);

        // decode the data
        $sig = $this->base64_url_decode($encoded_sig);
        $data = json_decode($this->base64_url_decode($payload), true);

        if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
            error_log('Unknown algorithm. Expected HMAC-SHA256');
            return null;
        }

        // check sig
        $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
        if ($sig !== $expected_sig) {
            error_log('Bad Signed JSON signature!');
            return null;
        }

        return $data;
    }

    function base64_url_decode($input) {
        return base64_decode(strtr($input, '-_', '+/'));
    }

    function email() {
        $this->load->library('email');
        $this->email->to('lojorider@gmail.com');
        $this->email->subject('แจ้งการลงทะเบียน');
        $this->email->message_view('page/home_page_email');
        $this->email->send();
        echo $this->email->print_debugger();
    }

    function year() {
        echo time();
        echo '-';
        echo time() + 365 * 100 * 24 * 60 * 60;
    }

    function cron_lock_video() {
        $this->load->model('service/disk_quota_service_model');
        $this->disk_quota_service_model->cron_lock_video();
    }

    function avconv() {
        $this->load->library('phpavconv');
        $input_full_file_path = '/var/www/clients/client3/web16/site_files/upload_tmp/2bA_M8entfkY738ecpT_Bw.mkv';
        $output_full_file_path = 'test.mp4';
        echo $this->phpavconv->encode($input_full_file_path, $output_full_file_path, FALSE, TRUE);
    }

    function email_form() {
        $this->load->view('fb/_email_on_register');
    }

    function mktimestamp() {
        $this->load->helper('time');
        $date_time = '10/11/2012 22:11';
        echo $t = mktimestamp($date_time);
        //echo date('l jS \of F Y h:i:s A',$t);
    }

    function youtube_data() {
        $url = 'https://www.googleapis.com/youtube/v3/videos?id=7lCDEYXw3mM&key=AIzaSyALyDvXo3orYrEG5Rdb0ogEqH9x-R5w7HA&part=snippet,contentDetails,statistics,status';
        $file = file_get_contents($url);
        echo $file;
    }

    function clean_user($delete = 0) {
        $this->db->where('uid not in (select uid from u_user)', NULL, FALSE);
        if ($delete) {
            $q1 = $this->db->delete('u_user_detail');
        } else {
            $q1 = $this->db->get('u_user_detail');
            print_r($q1->result_array());
        }


        $this->db->where('uid not in (select uid from u_user)', NULL, FALSE);
        if ($delete) {
            $q1 = $this->db->delete('u_user_credit');
        } else {
            $q1 = $this->db->get('u_user_credit');
            print_r($q1->result_array());
        }



        // clean file
    }

    function clean_dir() {
        $this->load->helper('directory');
        $dir = FCPATH . '../site_files/document/1/';
        $map = directory_map($dir);
        // print_r($map);
        foreach ($map as $k => $v) {
            $this->db->where('uid', $k);
            if ($this->db->count_all_results('u_user') > 0) {
                echo 'HAVE<br>';
            } else {
                //clean dir
                del_tree($dir . $k);
                echo 'NO HAVE<br>';
            }
        }



        $dir = FCPATH . '../video/1/';
        $map = directory_map($dir);
        //  print_r($map);
        foreach ($map as $k => $v) {
            $this->db->where('uid', $k);
            if ($this->db->count_all_results('u_user') > 0) {
                echo 'HAVE VIDEO FOLDER <br>';
            } else {
                //clean dir
                del_tree($dir . $k);
                echo 'NO HAVE VIDEO FOLDER<br>';
            }
        }
    }

    function folder_size() {
        $command = 'du -b ' . FCPATH . '../video/1/9';
        $folder = exec($command);
        $folder = current(explode('	', $folder));
        $this->load->helper('number');
        echo byte_format($folder);
    }

    function amazon() {
        echo '<iframe src="http://rcm.amazon.com/e/cm?t=educasy-20&o=1&p=8&l=as1&asins=B003L1ZYYM&ref=tf_til&fc1=000000&IS2=1&lt1=_blank&m=amazon&lc1=203CFF&bc1=FFFFFF&bg1=FFFFFF&f=ifr" style="width:120px;height:240px;" scrolling="no" marginwidth="0" marginheight="0" frameborder="0"></iframe>';
        echo '<iframe src="http://www.blognone.com" style="width:120px;height:240px;" scrolling="no" marginwidth="0" marginheight="0" frameborder="0"></iframe>';
    }

    function xelatex() {
        
    }

    function to_xelatex() {
        $this->load->library('xelatex');
        $formula = '\documentclass[12pt]{article}
        \usepackage{fontspec}
        \usepackage{xunicode}
        \usepackage{xltxtra}
        \XeTeXlinebreaklocale "th"
        \setmainfont{TH Sarabun New}
        \title{This is the title}
        \author{Author One \\ Author Two}
        \date{29 February 2004}
        \begin{document}
        
        \maketitle
        สวัสดี ผมยินดีที่วันนี้ มีความหมาย
        \graphicspath{{/var/www/nt/document/}}
        \includegraphics{1/112/1353429277.jpg}
        This is the content of this document.
        This is the 2nd paragraph.
        Here is an inline formula:
        $   V = \frac{4 \pi r^3}{3}  $.
        And appearing immediately below
        is a displayed formula:
        $$  V = \frac{4 \pi r^3}{3}  $$
         \newpage
         \maketitle

        This is the content of this document.

        This is the 2nd paragraph.
        Here is an inline formula:
        $   V = \frac{4 \pi r^3}{3}  $.
        And appearing immediately below
        is a displayed formula:
        $$  V = \frac{4 \pi r^3}{3}  $$
        \end{document}
';
        $file_path = FCPATH . 'temp/xxx.pdf';
        $this->xelatex->formula($formula);
        $this->xelatex->set_trim(FALSE);
        //$this->xelatex->want_log();
        //$this->xelatex->not_clean_temp();
        print_r($this->xelatex->render($file_path));
    }

    function markitup() {
        $this->template->load_markitup_xelatex();
        $this->template->write_view('demo/markitup_bbcode');
        $this->template->render();
    }

    function show_loading() {
        $this->template->load_showloading();
        $this->template->write_view('demo/showloading');
        $this->template->render();
    }

    function http_host() {
        echo $_SERVER['HTTP_HOST'];
    }

    function session() {

        print_r($_SESSION);
//        echo session_cache_expire(20);
//        
    }

    function regx() {
        $subject = "aTeX Font Info:    Overwriting symbol font `operators' in version `bold'
(Font)                  EU1/THSarabunNew(0)/m/n --> EU1/THSarabunNew(0)/bx/n on
 input line 11.
LaTeX Font Info:    Overwriting math alphabet `\mathrm' in version `bold'
(Font)                  EU1/THSarabunNew(0)/m/n --> EU1/THSarabunNew(0)/bx/n on
 input line 11.
LaTeX Font Info:    Overwriting math alphabet `\mathit' in version `bold'
(Font)                  OT1/cmr/bx/it --> EU1/THSarabunNew(0)/bx/it on input li
ne 11.
LaTeX Font Info:    Overwriting math alphabet `\mathsf' in version `bold'
(Font)                  OT1/cmss/bx/n --> EU1/lmss/bx/n on input line 11.
LaTeX Font Info:    Overwriting math alphabet `\mathtt' in version `bold'
(Font)                  OT1/cmtt/m/n --> EU1/lmtt/bx/n on input line 11.
! Undefined control sequence.
l.16 \textbif
             {ss<del></del>}
?
! Emergency stop.
l.16 \textbif
             {ss<del></del>}
End of file on the terminal!


Here is how much of TeX's memory you used:
 5018 strings out of 495724
 81913 string characters out of 1189345
 150089 words of memory out of 3000000
 8154 multiletter control sequences out of 15000+50000
 4002 words of font info for 23 fonts, out of 3000000 for 9000
 28 hyphenation exceptions out of 8191
 40i,0n,27p,479b,152s stack positions out of 5000i,500n,10000p,200000b,50000s


";

//$pattern = '/^150089/';
//preg_match($pattern, $subject, $matches, PREG_OFFSET_CAPTURE, 3);
//print_r($matches);
        $subject = str_replace("\n", "<br/> ", $subject);
        preg_match_all(
                //"|<[^>]+>(.*)</[^>]+>|U",
                "/!(.+?)\?/U",
                //"<b>example: </b><div align=left>this is a test</div>",
                $subject, $out, PREG_PATTERN_ORDER);
        print_r($out);
//echo $out[0][0] . ", " . $out[0][1] . "\n";
//echo $out[1][0] . ", " . $out[1][1] . "\n";
    }

    function a4() {
        $this->template->temmplate_name('normal');
        $this->template->write_view('resource/xelatex_preview/a4_preview');
        $this->template->render();
    }

    function latexinput() {
        echo "<html><title>LatexRender Demo</title>
    <head><script language=\"JavaScript\" type=\"text/javascript\">
	function addtags() {
		if (document.selection.createRange().text!='') {
	  		document.selection.createRange().text = '[tex]'+document.selection.createRange().text+'[/tex]';
	  	}
	}//--></script></head>";
        echo "<body bgcolor='lightgrey'><center><h3>LatexRender Demo</h3>";
        echo "<font size=-1><i>Add tags around text you want to convert to an image<br>
    or press the button to add them around highlighted text</i></font>";

        echo "<form method='post'>";
        echo "<input onclick=\"addtags()\" type=\"button\" value=\"Add TeX tags\" name=\"btnCopy\"><br><br>";
        echo "<textarea name='latex_formula' rows=8 cols=50>";

        if (isset($_POST['latex_formula'])) {
            //echo stripslashes($_POST['latex_formula']);
            echo $_POST['latex_formula'];
        } else {
            echo "Example Text:\nThis is just text but [tex]\sqrt{2}[/tex] should be shown as an image and so should [tex]\frac {1}{2}[/tex].
			\nAnother formula is [tex]\frac {43}{12} \sqrt {43}[/tex]";
        }

        echo "</textarea>";
        echo "<br><br><input type='submit' value='Render'>";
        echo "</form>";

        if (isset($_POST['latex_formula'])) {
            //$text = stripslashes($_POST['latex_formula']);
            $text = $_POST['latex_formula'];
            echo "<u>Result</u><br><br>";
            // now convert and show the image
            $this->load->library('xelatex');
            //  echo nl2br($this->latex_content($text));
            echo nl2br($this->xelatex->render_bbcode($text));
        }

        echo "</center></body></html>";
    }

    function latex_content($text) {

        // --------------------------------------------------------------------------------------------------
        // adjust this to match your system configuration
        //$latexrender_path = "/home/domain_name/public_html/latexrender";
        $latexrender_path = FCPATH . "/temp/xelatex_builder";
        $latexrender_path_http = base_url() . "/temp/xelatex_builder";


        // --------------------------------------------------------------------------------------------------
        //    include_once(APPPATH . "third_party/latexrender/class.latexrender.php");

        preg_match_all('#\[tex\](.*?)\[/tex\]#si', $text, $tex_matches);


        //$latex = new LatexRender($latexrender_path, $latexrender_path_http , $latexrender_path );
        $this->load->library('xelatex');
        $this->xelatex->set_trim();
        // print_r($tex_matches);
        for ($i = 0; $i < count($tex_matches[0]); $i++) {
            $pos = strpos($text, $tex_matches[0][$i]);
            $latex_formula = $tex_matches[1][$i];
            echo $latex_formula;
            echo '|';

            // if you use htmlArea to input the text then uncomment the next 6 lines
            //	$latex_formula = str_replace("&amp;","&",$latex_formula);
            //	$latex_formula = str_replace("&#38;","&",$latex_formula);
            //	$latex_formula = str_replace("&nbsp;"," ",$latex_formula);
            //	$latex_formula = str_replace("<BR>","",$latex_formula);
            //	$latex_formula = str_replace("<P>","",$latex_formula);
            //	$latex_formula = str_replace("</P>","",$latex_formula);
            //$url = $latex->getFormulaURL($latex_formula);



            $formula[] = $this->load->view('xelatex/content_preamble_formula', array(), TRUE);
            $formula[] = "\begin{document}";
            //echo $latex_formula;
            $formula[] = $latex_formula;
            $formula[] = "\end{document}";
            $this->xelatex->formula(implode("\n", $formula));

            $target_path = FCPATH . 'temp/xelatex_temp/' . md5($this->xelatex->formula()) . '.png';
            //echo $this->xelatex->formula();
            $result = $this->xelatex->render($target_path, FALSE);
//print_r($result);        

            $url = base_url('temp/xelatex_temp/' . $result['files_basename'][0]);








            $alt_latex_formula = htmlentities($latex_formula, ENT_QUOTES);
            $alt_latex_formula = str_replace("\r", "&#13;", $alt_latex_formula);
            $alt_latex_formula = str_replace("\n", "&#10;", $alt_latex_formula);

            if ($url != false) {
                $text = substr_replace($text, "<img src='" . $url . "' title='" . $alt_latex_formula . "' alt='" . $alt_latex_formula . "' align=absmiddle>", $pos, strlen($tex_matches[0][$i]));
            } else {
                //  $text = substr_replace($text, "[Unparseable or potentially dangerous latex formula. Error $latex->_errorcode $latex->_errorextra]", $pos, strlen($tex_matches[0][$i]));
            }
        }
        return $text;
    }

    function gen_affiliate_code($uid) {
        $this->load->model('affiliate_model');
        echo $code = $this->affiliate_model->encode_affiliate_code($uid);
        //$this->affiliate_model->set_uid_affiliate();
        // echo $this->affiliate_model->get_uid_affiliate();
    }

    function degen_affiliate_code($affiliate_code) {
        $this->load->model('affiliate_model');
        echo $code = $this->affiliate_model->decode_affiliate_code($affiliate_code);
        //$this->affiliate_model->set_uid_affiliate();
        // echo $this->affiliate_model->get_uid_affiliate();
    }

    function get_cookie() {
        $this->load->model('affiliate_model');
        echo $code = $this->affiliate_model->encode_affiliate_code('153');
    }

    function sdycontent($resource_id) {
        $this->load->model('resource/xelatex_dycontent_separate_model');
        $this->xelatex_dycontent_separate_model->get_content_data($resource_id);
    }

    function am() {
        $array1 = array("color" => "red", 2, 4);
        $array2 = array("a", "b", "color" => "green", "shape" => "trapezoid", 4);
        $result = array_merge($array1, $array2);
        print_r($result);
    }

    function pad() {
        echo ceil(10 / 2);
    }

    function password($password) {
        echo $this->auth->encode_password($password);
    }

    function host() {
        echo $_SERVER['HTTP_HOST'];
    }

    function deviceinfo() {
        //echo $this->agent->browser();

        echo $this->agent->version();
//        $findme = 'Android';
//        $mystring = $this->agent->agent_string();
//        $mystring = current(explode(';', strstr($mystring, 'Android')));
//        if($mystring){
//        switch ($mystring) {
//            case 'Android 4.0.4':
//                echo 'ICS';
//                break;
//            default:
//                break;
//        }
//        }else{
//            echo 'not android';
//        }
//    
//        echo   $agent = $this->agent->mobile();
        if ($this->agent->is_android()) {
            echo 'YES';
        } else {
            echo 'NO';
        }
    }

    function avatar_img() {

        $image_file = $this->auth->get_avatar_filename();
        //echo $image_file;
        header('Content-Type: image/jpeg');
        readfile($image_file);
    }

    function java() {
        // Show whoami
        $output = shell_exec("whoami");
        echo "<strong>WHOAMI</strong>";
        echo "<hr/>";
        echo "$output<br/><br/><br/><br/>";

        // Show The Java Version Before Setting Environmental Variable
        $output = shell_exec("java -version 2>&1");
        echo "<strong>Java Version Before Setting Environmental Variable</strong>";
        echo "<hr/>";
        echo "$output<br/><br/><br/><br/>";

        // Set Enviromental Variable
        $JAVA_HOME = "C:\Program Files (x86)\Java\jre7";
        $PATH = "$JAVA_HOME/bin";
        putenv("JAVA_HOME=$JAVA_HOME");
        putenv("PATH=$PATH");

        // Show The Java Version After Setting Environmental Variable
        $output = shell_exec("java -version 2>&1");
        echo "<strong>Java Version After Setting Environmental Variable</strong>";
        echo "<hr/>";
        echo $output;
    }

    function noobthink() {
        $files = glob('/var/www/clients/client3/web16/video/1/9/*.mp4', GLOB_BRACE);
        if (is_array($files)) {
            //print_r($files);
        }
        $dest = '/var/www/clients/client2/web10/upload_temp/x.mp4';
        if (!copy($files[0], $dest)) {
            echo 'not copy';
        }
    }

    function swfobject() {

        $this->template->script_var(
                array(
                    'expressInstall_url' => base_url('files/swf/expressInstall.swf'),
                    'swf_url' => base_url('files/swf/edy_capture.swf'),
                    'save_url' => site_url('demo/saveimg')
                )
        );
        $this->template->write_view('demo/swfobject');
        $this->template->load_swfobject();

        $this->template->temmplate_name('normal');
        $this->template->render();
    }

    function saveimg() {
        if (isset($GLOBALS["HTTP_RAW_POST_DATA"])) {
            $jpg = $GLOBALS["HTTP_RAW_POST_DATA"];
            $filename = FCPATH . "temp/poza_" . mktime() . ".jpg";
            file_put_contents($filename, $jpg);
        } else {
            echo "Encoded JPEG information not received.";
        }
    }

    function fancybox() {
        $this->template->write_view('demo/fancybox');
        $this->template->load_jquery_fancybox();
        $this->template->render();
    }

    function testfn() {
        $str_init = 'function xxx(){echo "fuck";}';
        $str_run = ' xxx();';
        eval($str_init);
        eval($str_run);
    }

    function phpexcel() {
        $this->load->helper('download');
        $this->load->library("excel");


        $q = $this->db->get('u_user');
        $data = $q->result_array();
        $file_name = 'filename.xlsx';
        $this->excel->add_sheet($data);
        $this->excel->add_sheet($data);
        $this->excel->save('temp/' . $file_name);
        force_download_file($file_name, 'temp/' . $file_name);
        //$this->excel->stream('filename.xlsx', $data);
        //$this->excel->stream('filename.xls', $data, FALSE);
        //$this->excel->stream('filename.xlsx', $data, TRUE, 'สุดยอด');
    }

    function phpexcel_coupon($start, $limit) {
        $this->load->helper('download');
        $this->load->library("excel");
        $option = array('setWidth' => 12);
        $c = 8;
        $r = 23;
        //$start = 2859;
        //$start = 6559;
        $this->db->where('cid >=', $start);
        $this->db->limit($limit);
        $q = $this->db->get('b_coupon');
        $data = array();
        $j = 1;
        $i = 1;
        foreach ($q->result_array() as $row) {
            $data[$j][$i] = $row['coupon_code'];
            if ($i == $c) {
                $j++;
                $i = 1;
            } else {
                $i++;
            }
        }
        $file_name = 'filename.xlsx';
        $i = 0;

        $sheet_data = array();
        $count_data = count($data);
        $title_index = 1;
        $this->excel->getDefaultStyle()
                ->getNumberFormat()
                ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        foreach ($data as $row) {
            $sheet_data[] = $row;
            $i++;
            if ($i % $r == 0) {
                $this->excel->add_sheet($sheet_data, FALSE, 'หน้า ' . $title_index, $option);
                $title_index++;
                $sheet_data = array();
            } else if ($i == $count_data) {
                $this->excel->add_sheet($sheet_data, FALSE, 'หน้า ' . $title_index, $option);
            }
        }
        //$this->excel->add_sheet($data, FALSE, 'ต้นฉบับรวม');
        $this->excel->save('temp/' . $file_name);
        force_download_file($file_name, 'temp/' . $file_name);
    }

    function unzip() {
        $this->load->library('unzip');
        $this->unzip->allow(array('css', 'js', 'png', 'gif', 'jpeg', 'jpg', 'tpl', 'html', 'swf', 'eps', 'pdf', 'docx'));

        $str = $this->unzip->extract(FCPATH . 'test.zip', FCPATH . 'temp/' . rand(111, 999));

        if ($str) {
            echo 'OK';
        } else {
            echo $this->unzip->error_string();
        }
    }

    function move_folder() {



        function rrmdir($dir) {
            if (is_dir($dir)) {
                $files = scandir($dir);
                foreach ($files as $file)
                    if ($file != "." && $file != "..")
                        rrmdir("$dir/$file");
                rmdir($dir);
            }
            else if (file_exists($dir))
                unlink($dir);
        }

        // Function to Copy folders and files       
        function rcopy($src, $dst, $del_src = FALSE) {
            if (file_exists($dst))
                rrmdir($dst);
            if (is_dir($src)) {
                mkdir($dst);
                $files = scandir($src);
                foreach ($files as $file)
                    if ($file != "." && $file != "..")
                        rcopy("$src/$file", "$dst/$file");
            } else if (file_exists($src))
                copy($src, $dst);
            if ($del_src)
                rrmdir($src);
        }

        rcopy(FCPATH . 'temp/135', FCPATH . 'temp/135' . 'cp', TRUE);
    }

    function rotate_td() {
        $this->template->write_view('demo/rotate_td');
        $this->template->render();
    }

    function cron() {
        $this->load->model('cron_model');
        $this->cron_model->update_user_resource_count();
    }

    function cpvideo() {
        $file = '/var/www/clients/client3/web16/video/1/9/1354707261.mp4';

        $newfile = FCPATH . '1354707261.mp4';

        if (!copy($file, $newfile)) {
            echo "failed to copy $file...\n";
        }
    }

    function paypal() {
        $this->template->write_view('demo/paypal');
        $this->template->render();
    }

    function paypal2() {
        print_r($_POST);
    }

    function transferimage() {
        $this->db->where('resource_id', 713);
        $q = $this->db->get('r_resource_dycontent');
        $r = $q->row_array();
        $r['data'] = json_decode($r['data'], TRUE);
        echo $content_header = $r['data']['content_header'];
        $subject = $content_header;
        $pattern = "/includegraphics.*{(.*)}/";
        preg_match_all($pattern, $subject, $matches);
        print_r($matches);
        $a_file_path = array_unique($matches[1]);
        print_r($a_file_path);
        //print_r($r);
    }

    function view_display($tbl_name = 'รายงาน_การเข้าใช้งานเว็บ_ของนักเรียน') {
        $tbl_name = urldecode($tbl_name);
        if (!preg_match("/^v_/", $tbl_name)) {
            if (!preg_match("/^รายงาน/", $tbl_name)) {

                $tbl_name = 'รายงาน_การเข้าใช้งานเว็บ_ของนักเรียน';
            }
        }
        $data['table_list'] = array();
        $tables = $this->db->list_tables();

        foreach ($tables as $table) {
            if (preg_match("/^v_/", $table)) {
                $data['table_list'][] = $table;
            }
        }
        foreach ($tables as $table) {
            if (preg_match("/^รายงาน/", $table)) {
                $data['table_list'][] = $table;
            }
        }



        $this->load->library('table');
        $tmpl = array(
            'table_open' => '<table class="data" border="0" cellpadding="4" cellspacing="0">'
        );

        $fields = $this->db->list_fields($tbl_name);
        $heading = array();
        foreach ($fields as $field) {
            $heading[] = $field;
        }
        $this->table->set_heading($heading);
        $this->table->set_template($tmpl);
        $q = $this->db->get($tbl_name);
        $data['tbl_name'] = $tbl_name;
        $data['table'] = $this->table->generate($q->result_array());
        $this->template->write_view('demo/view_display', $data);
        $this->template->render();
    }

    function db_test() {
        $this->db_parent = $this->load->database('parent', TRUE);
        $this->db->close();
        $q = $this->db_parent->get('u_user_online_log');
        $this->db->close();
        print_r($q->row_array());
        $q = $this->db->get('u_user_online_log');
        print_r($q->row_array());
    }

    function site_map() {
        $this->db->where_in('uid_owner', array(2, 9));
        $q = $this->db->get('r_resource');
        foreach ($q->result_array() as $r) {
            echo '<url>';
            echo "\n";
            echo '<loc>http://www.educasy.com/v/' . $r['resource_id'] . '</loc>';
            echo "\n";
            echo '<changefreq>weekly</changefreq>';
            echo "\n";
            echo '<priority>0.64</priority>';
            echo "\n";
            echo '</url>';
            echo "\n";
        }
    }

//    function password($password){
//        echo $this->auth->encode_password($password);
//    }
    function get_dycontent() {
        $this->db->limit('1');
        //$this->db->where('resource_type_id', 4);
        $this->db->where('content_type_id', 1);
        $this->db->order_by('resource_id', 'random');
        $q = $this->db->get('vod_resource_dycontent');
        $r1 = $q->row_array();
        $this->load->model('resource/dycontent_model');
        //$this->dycontent_model->init_resource($r1['resource_id']);

        $r = $this->dycontent_model->get_dycontent_data($r1['resource_id'], 'vod_resource_dycontent');

        $data = $r['data'];
        $pattern = "/includegraphics.*{(.*)}/";
        preg_match_all($pattern, $data['content_header'], $matches);

        echo '<pre>';
        echo $r1['resource_id'];
        print_r($r);
        print_r($matches);
        $files = array();
        foreach ($matches[1] as $f) {
            $files[] = basename($f);
        }
        print_r($files);
        $files2 = array();
        $personal_dir = $this->auth->get_personal_dir(6);
        foreach ($files as $f) {
            $files2[] = $personal_dir . basename($f);
        }
        print_r($files2);

        $data['content_header'] = str_replace($matches[1], $files2, $data['content_header']);
        print_r($data);
        echo '</pre>';
    }

    function get_range_obj($date = '') {
        if ($date == '') {
            $now_date_obj = new DateTime();
        } else {
            $now_date_obj = new DateTime($date); //20131230 **
        }

        if ($now_date_obj->format('j') == 1) {

            if ($now_date_obj->format('n') == 1) {
                
                $from_month = 12;
                $from_year = $now_date_obj->format('Y') - 1;
            } else {
                $from_month = $now_date_obj->format('n') - 1;
                $from_year = $now_date_obj->format('Y');
            }
            
            $to_day = cal_days_in_month(CAL_GREGORIAN, $from_month, $from_year);
        } else {
            $from_month = $now_date_obj->format('n');
            $from_year = $now_date_obj->format('Y');
            $to_day = $now_date_obj->format('j') - 1;
        }
        

        $to_day = str_pad($to_day, 2, '0', STR_PAD_LEFT);
        $from_month = str_pad($from_month, 2, '0', STR_PAD_LEFT);
        $data['from'] = new DateTime($from_year . $from_month . '01');
        $data['to'] = new DateTime($from_year . $from_month . $to_day);
        return $data;
    }

    function date($d = '01/03/2014') {

//        $d=$this->input->get('d');
//        $text = implode('',array_reverse(explode('/', $d)));
//        $date = new DateTime($text);
//        $date->sub(new DateInterval('P1D'));
//        $date = new DateTime();
        $data = $this->get_range_obj('20131105');
        echo $data['from']->format('d/m/Y');
        echo '----->';
        echo $data['to']->format('d/m/Y');
    }

}
