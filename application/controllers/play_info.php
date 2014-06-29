<?php

/**
 * @property resource_video_model $resource_video_model
 * @property  play_video_model $play_video_model
 * @property board_model $board_model
 * @property house_model $house_model
 */
class page extends CI_Controller {

    var $make_money;

    public function __construct() {
        parent::__construct();
        $this->load->model('affiliate/affiliate_model');
        $this->load->library('user_agent');
        $this->make_money = $this->config->item('make_money');
    }

    /**
     * หน้าแรก
     */
    function index() {

        switch ($_SERVER['HTTP_HOST']) {
            case 'www.educasy.com':
                $this->index_educasy();

                break;
            case 'www.prokru.com':
                //$this->index_krueonline();
                $this->index_prokru_v2();

                break;
           
            default:
                //$this->index_educasy();
                //$this->index_krueonline();
                $this->index_3p(); 
                break;
        }
    }

    function index_educasy() {

        $flash_download_url = FALSE;
        $acrobat_url = 'http://get.adobe.com/reader/';
        if ($this->agent->is_android()) {
            $acrobat_url = 'https://play.google.com/store/apps/details?id=com.adobe.reader&hl=th';
            if ($this->agent->android_version() == '4.0.4') {
                $flash_download_url = base_url('files/flash_player_ics.apk');
            } else if ($this->agent->android_version() > '4.0.4') {
                $flash_download_url = base_url('files/flash_player_jb.apk');
            } else {
                $flash_download_url = base_url('files/flash_player_gb.apk');
            }
        }
        $download_browser = TRUE;
//        if ($this->agent->browser() == 'Firefox' || $this->agent->browser() == 'Chrome') {
//                        $download_browser = FALSE;
//        }
        $data = array(
            'flash_download_url' => $flash_download_url,
            'is_android' => $this->agent->is_android(),
            'acrobat_url' => $acrobat_url,
            'download_browser' => $download_browser,
            'seach_teacher_form_action' => site_url('search/teacher'),
            'is_connect_facebook' => $this->auth->is_connect_facebook(),
        );
        
        //if ($this->make_money) {
            $this->template->write_view('page/home_page_educasy', $data);
      //  } else {
       //     $this->template->write_view('page/home_page_freesys', $data);
       // }
        $script_var = array(
            'uid' => $this->auth->uid(),
            'ajax_fb_like_url' => site_url('fb/like/')
        );
        $this->template->script_var($script_var);
        //$this->template->write_view('page/home_page_demo', $data);
        $this->template->load_coin_slider();
        $this->template->render();
    }
    function index_prokru_v2() {

        $flash_download_url = FALSE;
        $acrobat_url = 'http://get.adobe.com/reader/';
        if ($this->agent->is_android()) {
            $acrobat_url = 'https://play.google.com/store/apps/details?id=com.adobe.reader&hl=th';
            if ($this->agent->android_version() == '4.0.4') {
                $flash_download_url = base_url('files/flash_player_ics.apk');
            } else if ($this->agent->android_version() > '4.0.4') {
                $flash_download_url = base_url('files/flash_player_jb.apk');
            } else {
                $flash_download_url = base_url('files/flash_player_gb.apk');
            }
        }
        $download_browser = TRUE;
//        if ($this->agent->browser() == 'Firefox' || $this->agent->browser() == 'Chrome') {
//                        $download_browser = FALSE;
//        }
        $data = array(
            'flash_download_url' => $flash_download_url,
            'is_android' => $this->agent->is_android(),
            'acrobat_url' => $acrobat_url,
            'download_browser' => $download_browser,
            'seach_teacher_form_action' => site_url('search/teacher'),
            'is_connect_facebook' => $this->auth->is_connect_facebook(),
        );
        
        //if ($this->make_money) {
            $this->template->write_view('page/home_page_prokru_v2', $data);
      //  } else {
       //     $this->template->write_view('page/home_page_freesys', $data);
       // }
        $script_var = array(
            'uid' => $this->auth->uid(),
            'ajax_fb_like_url' => site_url('fb/like/')
        );
        $this->template->script_var($script_var);
        //$this->template->write_view('page/home_page_demo', $data);
        $this->template->load_coin_slider();
        $this->template->render();
    }

    function index_krueonline() {
        $this->load->model('search/search_user_model');
        $this->load->helper('form');
        $this->load->model('house_model');
        $flash_download_url = FALSE;
        $acrobat_url = 'http://get.adobe.com/reader/';
        if ($this->agent->is_android()) {
            $acrobat_url = 'https://play.google.com/store/apps/details?id=com.adobe.reader&hl=th';
            if ($this->agent->android_version() == '4.0.4') {
                $flash_download_url = base_url('files/flash_player_ics.apk');
            } else if ($this->agent->android_version() > '4.0.4') {
                $flash_download_url = base_url('files/flash_player_jb.apk');
            } else {
                $flash_download_url = base_url('files/flash_player_gb.apk');
            }
        }
        $download_browser = TRUE;
//        if ($this->agent->browser() == 'Firefox' || $this->agent->browser() == 'Chrome') {
//                        $download_browser = FALSE;
//        }
        $data = array(
            'flash_download_url' => $flash_download_url,
            'is_android' => $this->agent->is_android(),
            'acrobat_url' => $acrobat_url,
            'download_browser' => $download_browser,
            'seach_teacher_form_action' => site_url('search/teacher')
        );
        $data['qtype_options'] = array(
            'full_name' => 'ชื่อครู',
            'school_name' => 'ชื่อโรงเรียน'
        );
        $data['default_qtype'] = 'full_name';
        $data['teacher_data'] = $this->search_user_model->find_all_teacher(1, '', '', 8, 'video_count', 'desc');
        //$data['teacher_data'] = $this->house_model->get_all_teacher();
        $this->template->script_var(
                array(
                    'ajax_school_name_url' => site_url('core/ajax_autocomplete/get_teacher_school_name'),
                    'ajax_teacher_full_name_url' => site_url('core/ajax_autocomplete/get_teacher_full_name')
                )
        );
        $this->template->write_view('page/home_page_krueonline', $data);
        //$this->template->temmplate_name('simple_pk');
        $this->template->load_jquery_colorbox();

        $this->template->load_coin_slider();
        $this->template->render();
    }

    function index_3p() {
        $this->load->model('search/search_user_model');
        $this->load->helper('form');
        $this->load->model('house_model');
        $flash_download_url = FALSE;
        $acrobat_url = 'http://get.adobe.com/reader/';
        if ($this->agent->is_android()) {
            $acrobat_url = 'https://play.google.com/store/apps/details?id=com.adobe.reader&hl=th';
            if ($this->agent->android_version() == '4.0.4') {
                $flash_download_url = base_url('files/flash_player_ics.apk');
            } else if ($this->agent->android_version() > '4.0.4') {
                $flash_download_url = base_url('files/flash_player_jb.apk');
            } else {
                $flash_download_url = base_url('files/flash_player_gb.apk');
            }
        }
        $download_browser = TRUE;
//        if ($this->agent->browser() == 'Firefox' || $this->agent->browser() == 'Chrome') {
//                        $download_browser = FALSE;
//        }
        $data = array(
            'flash_download_url' => $flash_download_url,
            'is_android' => $this->agent->is_android(),
            'acrobat_url' => $acrobat_url,
            'download_browser' => $download_browser,
            'seach_teacher_form_action' => site_url('search/teacher')
        );
        $data['qtype_options'] = array(
            'full_name' => 'ชื่อครู',
            'school_name' => 'ชื่อโรงเรียน'
        );
        $data['default_qtype'] = 'full_name';
        $data['teacher_data'] = $this->search_user_model->find_all_teacher(1, '', '', 8, 'video_count', 'desc');
        //$data['teacher_data'] = $this->house_model->get_all_teacher();
        $this->template->script_var(
                array(
                    'ajax_school_name_url' => site_url('core/ajax_autocomplete/get_teacher_school_name'),
                    'ajax_teacher_full_name_url' => site_url('core/ajax_autocomplete/get_teacher_full_name')
                )
        );
        $this->template->write_view('page/home_page_3p', $data);
        //$this->template->temmplate_name('simple_pk');
        $this->template->load_jquery_colorbox();

        $this->template->load_coin_slider();
        $this->template->render();
    }

    function index_nt() {
        // affiliate_model
        $this->affiliate_model->set_uid_affiliate();
        //start index
        $this->load->helper('time');
        $this->load->model('play/play_video_model');
        $this->template->load_coin_slider();
        $data['is_login'] = $this->auth->is_login();
        $data['taxonomy_recommend'] = array(
            array('title' => 'ติวสบาย เคมี 3 weeks เข้ามหาลัย', 'uri' => 'house/u/9/104'),
            array('title' => 'คณิตศาสตร์ ม.ปลาย พื้นฐาน แบบละเอียด', 'uri' => 'house/u/9/2'),
            array('title' => 'ฟิสิกส์ ม.ปลาย พื้นฐาน แบบละเอียด', 'uri' => 'house/u/9/1'),
            array('title' => 'ฟิสิกส์ ม.ปลาย พื้นฐาน ฉบับเนื้อหากระชับ', 'uri' => 'house/u/9/49'),
            array('title' => 'ฟิสิกส์ ม.ปลาย ตลุยโจทย์โควตา', 'uri' => 'house/u/9/70'),
            array('title' => 'เคมี ม.ปลาย พื้นฐาน', 'uri' => 'house/u/9/41'),
            array('title' => 'เคมี ม.ปลาย ตลุยโจทย์โควตา', 'uri' => 'house/u/9/89'),
            array('title' => '[SIPA-KU] หลักสูตร Android', 'uri' => 'house/u/100/100'),
            array('title' => '[SIPA-KU] หลักสูตร Agile', 'uri' => 'house/u/100/72'),
            array('title' => 'foodtravel.tv สอนทำขนม', 'uri' => 'house/u/100/94')
        );
        $data['users_view'] = $this->play_video_model->get_users_view();
        // Board AND NEWS
        $this->load->model('community/board_model');
        $data['board4'] = $this->load->view('community/board/display_front', $this->board_model->find_all(1, '', '', 10, 'p_id', 'desc', 4), TRUE);
        $data['board3'] = $this->load->view('community/board/display_front', $this->board_model->find_all(1, '', '', 10, 'p_id', 'desc', 3), TRUE);
        $data['board6'] = $this->load->view('community/board/display_front', $this->board_model->find_all(1, '', '', 10, 'p_id', 'desc', 6), TRUE);


        //$this->template->title("educasy | ติวออนไลน์ ที่ดีที่สุดในขณะนี้ ไม่ต้องสมัครหลักสูตร ไม่ต้องกลัวเวลาเรียนหมด เพียงแค่เติมเงินขั้นต่ำ 50 บาท เรียนได้เลยทุกบททุกวิชา ค่าเรียนคิดเป็น วินาที");
        $this->template->title("educasy เรียนออนไลน์");
        $this->template->description("เว็บเรียนออนไลน์ มีหลากหลายวิชา คณิตศาสตร์ ฟิสิกส์ เคมี ชีวะ อื่นๆ อีกมากมาย มีทั้งสอนเนื้อหา ติวโจทย์ ติวโควตา เรียนได้ทันทีไม่ต้องสมัครหลักสูตรใดๆ ให้ยุ่งยากเหมือนเว็บอื่นๆ แถมยังมี ใบงานประกอบการเรียน ให้ download ฟรีๆ");
        if ($this->make_money) {
            $this->template->write_view('page/home_page', $data);
        } else {
            $this->template->write_view('page/home_page_free', $data);
        }
        $this->template->render();
    }

    /**
     * วิธีการใช้งาน
     */
    function h_use() {
        $this->template->write_view('page/h_use');
        $this->template->render();
    }

    /**
     * วิธีการใช้งาน
     */
    function h_use_ko() {
        $this->template->write_view('page/h_use_ko');
        $this->template->render();
    }

    /**
     * วิธีเติมเงิน
     */
    function h_topup() {
        $this->template->write_view('page/h_topup');
        $this->template->render();
    }

    /**
     * แลกเปลี่ยน link
     */
    function linkexchange() {
        $this->template->write_view('page/linkexchange_page');
        $this->template->render();
    }

    /**
     * เติมเงิน true
     */
    function truemoney_topup() {
        $this->template->write_view('page/truemoney_topup');
        $this->template->render();
    }

    /**
     * ซื้อหนังสือ
     */
    function h_buybook() {
        $this->template->write_view('page/h_buybook');
        $this->template->render();
    }

    /**
     * คำอธิบาย รหัสหนังสือ
     */
    function h_reg_coupon_code() {
        $this->template->write_view('page/h_reg_coupon_code');
        $this->template->render();
    }

    function yt($code = '') {
        if ($code == '') {
            $code = rand(1, 3);
        }
        switch ($code) {
            case 1:
                $title = '3 วันพร้อมสอบฟิสิกส์ O-NET 001 ';
                $youtube_url = 'https://www.youtube.com/watch?v=TRX91w4Hp2s';
                break;
            case 2:
                $title = '3 วันพร้อมสอบฟิสิกส์ O-NET 002 ';
                $youtube_url = 'https://www.youtube.com/watch?v=H-SmJHQZxV4';
                break;
            case 3:
                $title = '3 วันพร้อมสอบฟิสิกส์ O-NET 003 ';
                $youtube_url = 'http://www.youtube.com/watch?v=fYVD3ZuJKlU';
                break;
            default:
                $title = 'ความร้อน';
                $youtube_url = 'https://www.youtube.com/watch?v=oxcPmhi1ibs';
                break;
        }

        $this->template->load_jwplayer();
        $data = array(
            'title' => $title
        );
        $this->template->script_var(array(
            'youtube_url' => $youtube_url
        ));
        $this->template->write_view('page/youtube', $data);
        $this->template->render();
    }

    function download_sale_doc() {
        $this->load->helper('download');
        force_download_file('sale_document.pdf', FCPATH . "/files/sale_document.pdf");
    }

    function system_progress() {
        $this->template->write_view('page/system_progress');
        $this->template->render();
    }

}