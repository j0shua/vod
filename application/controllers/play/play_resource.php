<?php

/**
 * Description of play_resource
 *
 * @author lojorider

 * @property video_upload_model $video_upload_model
 * @property doc_manager_model $doc_manager_model 
 * @property disk_quota_service_model $disk_quota_service_model 
 * @property play_video_model $play_video_model
 * @property play_sitevideo_model $play_sitevideo_model
 * @property type $name Description
 * @property xelatex_dycontent_model $xelatex_dycontent_model
 * @property xelatex_sheet_model $xelatex_sheet_model
 * @property sheet_model $sheet_model
 * 
 * @property play_resource_model $play_resource_model
 */
class play_resource extends CI_Controller {

    var $main_resource_data, $make_money;
    var $is_rtmp;
    var $referer_url = '';

    public function __construct() {
        parent::__construct();
        $this->load->model('affiliate/affiliate_model');
        $this->load->model('play/play_resource_model');
        $this->load->model('play/play_video_model');
        $this->make_money = $this->config->item('make_money');
        $this->is_rtmp = $this->config->item('is_rtmp');
    }

// เริ่มต้นการเล่น สำหรับทุกๆ อย่าง
    function resource_id($resource_id) {
        $this->affiliate_model->set_uid_affiliate();
        if ($this->agent->browser() == 'Internet Explorer') {
            $data = array(
                'time' => 15,
                'url' => site_url(),
                'heading' => 'มีข้อผิดพลาดเกิดขึ้น',
                'message' => '<p>บราวเซอร์ ของคุณไม่รองรับการใช้งาน โปรดดาวน์โหลด และ ติดตั้งโปรแกรมบราวเซอร์ <a href="http://www.mozilla.org/th/firefox/new/" target="_blank">Firefox</a>เท่านั้น</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            if (isset($_SERVER['HTTP_REFERER'])) {
                $this->referer_url = $_SERVER['HTTP_REFERER'];
            }
// check resource type
            $init_result = $this->play_resource_model->init_resource($resource_id);
            if (!$init_result) {
                $data = array(
                    'time' => 5,
                    'url' => site_url(),
                    'heading' => ' ไม่พบหน้าที่ต้องการ',
                    'message' => '<p>ไม่พบหน้าที่ต้องการ อาจเป็นเพราะ ข้อมูลถูกลบไปแล้ว</p>'
                );
                $this->load->view('refresh_page', $data);
                return;
            } else {
//เลือกประเภทสื่อที่จะแสดง
                switch ($this->play_resource_model->get_resource_type_id()) {
// video,site_video ==============================================
                    case 1:
                        $this->sync_video_file();
                        $this->play_video();
                        break;
//เอกสาร ที่ upload  ==============================================                        
                    case 2:
                        $this->play_doc();
                        break;
//เอกสาร ที่ upload ==============================================                        
                    case 3:
                        $this->play_image($resource_id);
                        break;
//โจทย์เนื้อหา ==============================================                        
                    case 4:
                        $main_resource_data = $this->play_resource_model->get_resource_data();
                        if ($main_resource_data['uid_owner'] == $this->auth->uid()) {

                            $this->play_dycontent(TRUE);
                        } else {

                            $this->play_dycontent();
                        }

                        break;
//เอกสาร sheet ==============================================                        
                    case 5:
                        if ($this->auth->get_rid() == 3) {
                            $this->play_sheet(TRUE);
                        } else {
                            $this->play_sheet(FALSE);
                        }

                        break;
                    case 6:
                        $this->play_video();
                        break;
// video จากเว็บไซต์ Youtube ==============================================                        
                    case 7:
                        switch ($this->play_resource_model->get_videosite_id()) {
                            case 1:
                                $this->play_youtube();
                                break;
                            case 2:
                                $this->play_dailymotion();
                                break;
                            default:
                                break;
                        }
                        break;
                    case 8:
                        $this->play_flash_media();
                        break;
                    default:
                        break;
                }
            }
        }
    }

    function guide($resource_id) {
// check resource type
        $init_result = $this->play_resource_model->init_resource($resource_id);
        if (!$init_result) {
            $data = array(
                'time' => 5,
                'url' => site_url(),
                'heading' => ' ไม่พบหน้าที่ต้องการ',
                'message' => '<p>ไม่พบหน้าที่ต้องการ อาจเป็นเพราะ ข้อมูลถูกลบไปแล้ว</p>'
            );
            $this->load->view('refresh_page', $data);
            return;
        } else {
//เลือกประเภทสื่อที่จะแสดง
            $this->play_dycontent(TRUE);
        }
    }

    /**
     * เล่นอีกครั้งหรือไม่จะแสดงเมื่อเล่น   ไม่ต่อเนื่อง
     * @param type $resource_id
     */
    function confirm_playagain($resource_id) {
        $get_var = $this->input->get();

//set playlist
        $playlist_option = array(
            'playlist_type' => 'pltid',
            'playlist_value' => 0
        );

        if ($get_var) {
            if (isset($get_var['plsheet'
                    ])) {
                $playlist_option = array(
                    'playlist_type' => 'plsheet',
                    'playlist_value' => $get_var['plsheet']
                );
            }
            if (isset($get_var['pltid'])) {
                $playlist_option = array(
                    'playlist_type' => 'pltid',
                    'playlist_value' => $get_var['pltid']
                );
            }
        }

        $this->play_resource_model->init_resource($resource_id);
        $title = $this->play_resource_model->get_title();
        $unit_price = $this->play_resource_model->get_unit_price();
        if ($this->make_money) {
            if ($unit_price > 0) {
                $heading = $title . ' [' . $unit_price . ']';
            } else {
                $heading = $title . ' [ฟรี]';
            }
        } else {
            $heading = $title;
        }
        $get_str = '?' . $playlist_option['playlist_type'] . '=' . $playlist_option['playlist_value'];
        $data = array(
            'url' => site_url('v/' . $resource_id . $get_str),
            'cancel_url' => site_url(),
            'heading' => $heading,
            'message' => '<p>คุณดู video ' . $title . ' เสร็จแล้วคุณต้องการเข้าชมใหม่อีกครั้งหรือไม่</p>'
        );
        $this->load->view('confirm_page', $data);
    }

    /**
     * เล่นอีกครั้งหรือไม่จะแสดงเมื่อเล่น   ต่อเนื่อง
     * @param type $resource_id
     */
    function confirm_playfirst($resource_id) {
        $get_var = $this->input->get();

//set playlist
        $playlist_option = array(
            'playlist_type'
            => 'pltid',
            'playlist_value' => 0
        );

        if ($get_var) {
            if (isset($get_var['plsheet'])) {
                $playlist_option = array(
                    'playlist_type' => 'plsheet',
                    'playlist_value' => $get_var['plsheet']
                );
            }
            if (isset($get_var['pltid'])) {
                $playlist_option = array(
                    'playlist_type' => 'pltid',
                    'playlist_value' => $get_var['pltid']
                );
            }
        }



        $this->play_resource_model->init_resource($resource_id);

        $get_str = '?' . $playlist_option['playlist_type'
                ] . '=' . $playlist_option['playlist_value'];
        $data = array(
            'url' => site_url('v/' . $resource_id . $get_str),
            'cancel_url' => site_url(),
            'heading' => 'ดูวิดีโออีกรอบไหม',
            'message' => '<p>คุณดูวิดีโอในบทนี้เสร็จเรียบร้อยแล้วคุณอยากดูซ้ำอีกหรือไม่</p>',
            'btn_cancel' => 'ไม่แล้วหละ !'
        );
        $this->load->view('confirm_page', $data);
    }

    /**
     * เล่นอีกครั้งหรือไม่จะแสดงเมื่อเล่น   Error
     * @param type $resource_id
     */
    function confirm_playonerror($resource_id) {
        $this->play_resource_model->init_resource($resource_id);
        $title = $this->play_resource_model->get_title();
        $unit_price = $this->play_resource_model->get_unit_price();
        if ($this->make_money) {
            if ($unit_price > 0) {
                $heading = $title . ' [' . $unit_price . ']';
            } else {
                $heading = $title . ' [ฟรี]';
            }
        } else {
            $heading = $title;
        }
        $data = array(
            'url' => site_url('v/' . $resource_id),
            'cancel_url' => site_url(),
            'heading' => $heading,
            'message' => '<p>เกิดข้อผิดพลาดเมื่อคุณดู video ' . $title . ' เสร็จแล้วคุณต้องการเข้าชมใหม่อีกครั้งหรือไม่</p>'
        );
        $this->load->view('confirm_page', $data);
    }

    function play_video() {
        $get_var = $this->input->get();

//set playlist
        $playlist_option = array(
            'playlist_type' => 'pltid',
            'playlist_value' => 0
        );

        if ($get_var) {
            if (isset($get_var['plsheet'])) {
                $playlist_option = array(
                    'playlist_type' => 'plsheet',
                    'playlist_value' => $get_var['plsheet']
                );
            }
            if (isset($get_var['pltid'])) {
                $playlist_option = array(
                    'playlist_type' => 'pltid',
                    'playlist_value' => $get_var['pltid']
                );
            }
            if (isset($get_var['plrid'])) {
                $playlist_option = array(
                    'playlist_type' => 'plrid',
                    'playlist_value' => $get_var['plrid']
                );
            }
        }
        $this->play_resource_model->set_playlist_option($playlist_option);
//end set playlist

        if ($this->auth->is_login()) {
            if ($this->play_resource_model->is_owner()) {
//$this->jwplay_video();
                $this->flowplay_video();
            } else if ($this->play_resource_model->is_free() || !$this->make_money || $this->auth->have_money()) { //เช็คว่าดูฟรีหรือไม่ หรือต้องมีเงิน
                $this->play_resource_model->plus_view();
//$this->jwplay_video();
                $this->flowplay_video();
            } else {
                $data = array(
                    'time' => 5,
                    'url' => site_url(),
                    'heading' => ' ไม่สามารถเข้าใช้บริการได้',
                    'message' => '<p class="error-text">คุณมีจำนวนเงินไม่เพียงพอ</p><p>บริการนี้มีการคิดค่าบริการ โปรดเติมเงิน ก่อนเพื่อเข้าใช้</p>'
                );
                $this->load->view('refresh_page', $data);
            }
        } else {
//สำหรับยังไม่ได้ Login
            if ($this->make_money) {
                if ($this->play_resource_model->is_free()) {
                    $this->play_resource_model->plus_view();
                    $this->play_video_free();
                } else {
                    $this->play_video_not_login();
                }
            } else {
                $this->play_video_not_login();
            }
        }
    }

// jwplayer =======================================================================

    function jwplay_video() {
        $resource_id = $this->play_resource_model->get_resource_id();
//ข้อมูลหน้าจอ
        $data['title'] = $this->play_resource_model->get_title();
        $data['resource_code'] = $this->play_resource_model->get_resource_code();
//        $data['resource_doc'] = $this->play_resource_model->get_join_doc();
//      print_r($data['resource_doc']);
        $unit_price = $this->play_resource_model->get_unit_price();
        if ($this->make_money) {
            if (!$this->play_resource_model->is_owner()) {
                if ($unit_price > 0) {

                    if ($this->auth->have_money_bonus()) {
                        $data['price_txt'] = '[ ' . $unit_price . ' บาท/ชั่วโมง* ]';
                    } else {
                        $data['price_txt'] = '[ ' . $unit_price . ' บาท/ชั่วโมง ]';
                    }
                } else {
                    $data['price_txt'] = '[ ฟรี ]';
                }
            } else {
                $data['price_txt'] = '[ ฟรีสำหรับเจ้าของ ]';
            }
        } else {
            $data ['price_txt'] = '';
        }
        $data['resource_id'] = $resource_id;
        $data['facebook_like_url'] = site_url('v/' . $resource_id);
        $data['facebook_url'] = site_url('v/' . $resource_id);
//affiliate
        $data['affiliate_link'] = '';
        if ($this->auth->is_login()) {
            if ($this->auth->get_affiliate_type_id() > 0) {
                $data['affiliate_link'] = site_url('v/' . $resource_id) . '/?affiliate_code=' . $this->affiliate_model->encode_affiliate_code($this->auth->uid());
            }
        }
//end affiliate
//playlist
        $playlist_option = $this->play_resource_model->get_playlist_option();
        $data['resource_playlist'] = array(
            'total' => 0
        );

//ดึงข้อมูล playlist ตามรูปแบบ playlist

        switch ($playlist_option['playlist_type']) {
            case 'pltid':
                if ($playlist_option['playlist_value'] == 0) {

                    $data['resource_playlist'] = $this->play_resource_model->get_resource_video_data_same_sub_taxonomy($resource_id);
                } else {
                    $data['resource_playlist'] = $this->play_resource_model->get_resource_video_data_in_sub_taxonomy($playlist_option['playlist_value'], $resource_id);
                }
                break;
            case 'plsheet':
                $data['resource_playlist'] = $this->play_resource_model->get_resource_video_data_in_sheet($playlist_option['playlist_value'], $resource_id);

                break;
            case 'plrid':
                $data['resource_playlist'] = $this->play_resource_model->get_resource_video_data_in_join_video($playlist_option['playlist_value'], $resource_id);
                break;
            default:
                break;
        }

        $data['join_content_link'] = FALSE;

        if ($data['resource_playlist']['resource_id']) { //resource_id ของ video
            if ($playlist_option['playlist_type'] == 'pltid') {
//  if ($playlist_option['playlist_value']) {
                if (!isset($data['resource_playlist']['playlist_value'])) {
                    $data['resource_playlist']['playlist_value'] = $playlist_option['playlist_value'];
                }
                $join_content_in_taxonomy = $this->play_resource_model->get_join_content_in_taxonomy($data['resource_playlist']['playlist_value']);
                if ($join_content_in_taxonomy) {

                    $data['join_content_link'] = site_url('ztatic/download_join_content?plopt=' . $playlist_option['playlist_type'] . '&plvalue=' . $data['resource_playlist']['playlist_value']);
                }
            } else if ($playlist_option['playlist_type'] == 'plsheet') {
                if ($playlist_option['playlist_value']) {

                    $data['join_content_link'] = site_url('ztatic/download_join_content?plopt=' . $playlist_option['playlist_type'] . '&plvalue=' . $playlist_option['playlist_value']);
                }
            }
        }

        if (isset($data['resource_playlist']['next']['url'])) {
            $continue_url = $data['resource_playlist']['next']['url']; //มีต่อเนื่อง
        } else {

            if (isset($data['resource_playlist']['rows'][0]['resource_id'])) {
                $continue_url = site_url("play/play_resource/confirm_playfirst/" . $data['resource_playlist']['rows'][0]['resource_id']);
            } else {
                $continue_url = site_url("play/play_resource/confirm_playfirst/" . $resource_id);
            }
        }
        $data['is_play_continue'] = $this->auth->is_play_continue();


// script var
        $script_var['playcontinue'] = array('value' => ( $data['is_play_continue']) ? 'true' : 'false');
        $get_str = '?' . $playlist_option['playlist_type'] . '=' . $playlist_option['playlist_value'];
        $script_var['ajax_init_url'] = site_url('play/play_resource/ajax_init_jwplay_video' . $get_str);
        $script_var['ajax_close_and_play_next_url'] = site_url('play/play_resource/ajax_close_and_jwplay_next')

        ;
        $script_var['ajax_close_play_url'] = site_url(
                'play/play_resource/ajax_close_play_video');
        $script_var['ajax_set_play_continue_url'] = site_url('play/play_resource/ajax_set_play_continue');
        if (isset($data['resource_playlist']['next']['resource_id'])) {
            $script_var['next_resource_id'] = array('value' => $data['resource_playlist'] ['next']['resource_id']);
        } else {
            $script_var['next_resource_id'] = array('value' => 0);
        }
        $script_var['resource_id'] = array('value' => $resource_id);
        $script_var['now_url'] = current_url();
        $script_var['seek_time'] = array('value' => (int) $this->input->get('t'));
        $script_var['referer_url'] = $this->referer_url;
        $this->template->script_var($script_var);
        $this->template->load_jwplayer();
        $this->template->application_script('play/jwplay_video.js');
        $this->template->title($data['title']);
        $this->template->write_view('play/jwplay_video', $data);
        $this->template->load_jquery_switch();
        $this->template->render();
    }

    function ajax_close_and_jwplay_next() {
        $this->play_video_model->update_view_log($this->input->post('view_log_id'), TRUE);
        $this->ajax_init_jwplay_video();
    }

    function ajax_init_jwplay_video() {
//set playlist.
        $get_var = $this->input->get();
        $playlist_option = array(
            'playlist_type' => 'pltid',
            'playlist_value' => 0
        );

        if ($get_var) {
            if (isset($get_var['plsheet'])) {
                $playlist_option = array(
                    'playlist_type' => 'plsheet',
                    'playlist_value' => $get_var['plsheet']
                );
            }
            if (isset($get_var ['pltid'])) {
                $playlist_option = array(
                    'playlist_type' => 'pltid',
                    'playlist_value' => $get_var['pltid']
                );
            }
        }
        $get_str = '?' . $playlist_option['playlist_type'] . '=' . $playlist_option ['playlist_value'];
        $resource_id = $this->input->post('resource_id');
        $this->play_resource_model->init_resource($resource_id);
        $is_owner = $this->play_resource_model->is_owner();
        $this->play_video_model->init_resource($resource_id);
        $referer_url = str_replace(base_url(), '', $this->input->post('referer_url'));
        $resource_type_id = $this->play_video_model->get_resource_type_id();
        switch ($resource_type_id) {
            case 1://หลัก
                $data = $this->play_video_model->init_video_play($is_owner, $referer_url);
                $data['netConnectionUrl'] = $this->config->item('netConnectionUrl');
                break;
            case 6:
                $data = $this->play_video_model->init_video_parent_play($is_owner, $referer_url);
                $data['netConnectionUrl'] = $this->config->item('netConnectionUrl_parent');
                break;
            default:
                $data['netConnectionUrl'] = $this->config->item('netConnectionUrl');
                break;
        }
        $data['trigger_url'] = site_url('play/play_resource/ajax_trigger_play_video');
        $data['request_trigger_time'] = $this->config->item('request_trigger_time') * 1000;

//ดึงข้อมูล playlist ตามรูปแบบ playlist
        switch ($playlist_option['playlist_type']) {
            case 'pltid':
                if ($playlist_option['playlist_value'] == 0) {
                    $data['resource_playlist'] = $this->play_resource_model->get_resource_video_data_same_sub_taxonomy($resource_id);
                } else {
                    $data['resource_playlist'] = $this->play_resource_model->get_resource_video_data_in_sub_taxonomy($playlist_option['playlist_value'], $resource_id);
                }
                break;
            case 'plsheet':
                $data['resource_playlist'] = $this->play_resource_model->get_resource_video_data_in_sheet($playlist_option['playlist_value'], $resource_id);
                break;
            default:

                break;
        }

//$data['debug'] =$data['resource_playlist'];
        $data['now_resource_id'] = $resource_id;
        if (isset($data['resource_playlist']['next']['url'])) {
            $data['next_resource_id'] = $data['resource_playlist']['next']['resource_id'];
            $continue_url = $data['resource_playlist']['next']['url'];
        } else {
            $data['next_resource_id'] = 0;
            if (isset($data['resource_playlist']['rows'][0]['resource_id'])) {
                $continue_url = site_url("play/play_resource/confirm_playfirst/" . $data['resource_playlist']['rows'][0]['resource_id'] . $get_str);
            } else {
                $continue_url = site_url("play/play_resource/confirm_playfirst/" . $resource_id . $get_str);
            }
        }

        $data['continue_url'] = $continue_url;
        $data['not_continue_url'] = site_url("play/play_resource/confirm_playagain/" . $resource_id . $get_str);
        $data['now_url'] = site_url('v/' . $resource_id . '/' . $get_str);
        if ($this->is_rtmp) {
            $data['video_path'] = '/flv:' . $data['video_path'];
            $data['jwplayer_file'] = $data['netConnectionUrl'] . $data['video_path'];
        } else {
            $data['jwplayer_file'] = site_url('video/' . $data['video_path']);
        }

        echo json_encode($data);
    }

// flowplayer =======================================================================

    function flowplay_video() {
        $resource_id = $this->play_resource_model->get_resource_id();
//ข้อมูลหน้าจอ
        $data['title'] = $this->play_resource_model->get_title();
        $data['resource_code'] = $this->play_resource_model->get_resource_code();
//        $data['resource_doc'] = $this->play_resource_model->get_join_doc();
//      print_r($data['resource_doc']);
        $unit_price = $this->play_resource_model->get_unit_price();
        if ($this->make_money) {
            if (!$this->play_resource_model->is_owner()) {
                if ($unit_price > 0) {

                    if ($this->auth->have_money_bonus()) {
                        $data['price_txt'] = '[ ' . $unit_price . ' บาท/ชั่วโมง* ]';
                    } else {
                        $data['price_txt'] = '[ ' . $unit_price . ' บาท/ชั่วโมง ]';
                    }
                } else {
                    $data['price_txt'] = '[ ฟรี ]';
                }
            } else {
                $data['price_txt'] = '[ ฟรีสำหรับเจ้าของ ]';
            }
        } else {
            $data ['price_txt'] = '';
        }
        $data['resource_id'] = $resource_id;
        $data['facebook_like_url'] = site_url('v/' . $resource_id);
        $data['facebook_url'] = site_url('v/' . $resource_id);
//affiliate
        $data['affiliate_link'] = '';
        if ($this->auth->is_login()) {
            if ($this->auth->get_affiliate_type_id() > 0) {
                $data['affiliate_link'] = site_url('v/' . $resource_id) . '/?affiliate_code=' . $this->affiliate_model->encode_affiliate_code($this->auth->uid());
            }
        }
//end affiliate
//playlist
        $playlist_option = $this->play_resource_model->get_playlist_option();
        $data['resource_playlist'] = array(
            'total' => 0
        );

//ดึงข้อมูล playlist ตามรูปแบบ playlist

        switch ($playlist_option['playlist_type']) {
            case 'pltid':
                if ($playlist_option['playlist_value'] == 0) {

                    $data['resource_playlist'] = $this->play_resource_model->get_resource_video_data_same_sub_taxonomy($resource_id);
                } else {
                    $data['resource_playlist'] = $this->play_resource_model->get_resource_video_data_in_sub_taxonomy($playlist_option['playlist_value'], $resource_id);
                }
                break;
            case 'plsheet':
                $data['resource_playlist'] = $this->play_resource_model->get_resource_video_data_in_sheet($playlist_option['playlist_value'], $resource_id);

                break;
            case 'plrid':
                $data['resource_playlist'] = $this->play_resource_model->get_resource_video_data_in_join_video($playlist_option['playlist_value'], $resource_id);
                break;
            default:
                break;
        }

        $data['join_content_link'] = FALSE;

        if ($data['resource_playlist']['resource_id']) { //resource_id ของ video
            if ($playlist_option['playlist_type'] == 'pltid') {
//  if ($playlist_option['playlist_value']) {
                if (!isset($data['resource_playlist']['playlist_value'])) {
                    $data['resource_playlist']['playlist_value'] = $playlist_option['playlist_value'];
                }
                $join_content_in_taxonomy = $this->play_resource_model->get_join_content_in_taxonomy($data['resource_playlist']['playlist_value']);
                if ($join_content_in_taxonomy) {

                    $data['join_content_link'] = site_url('ztatic/download_join_content?plopt=' . $playlist_option['playlist_type'] . '&plvalue=' . $data['resource_playlist']['playlist_value']);
                }
            } else if ($playlist_option['playlist_type'] == 'plsheet') {
                if ($playlist_option['playlist_value']) {

                    $data['join_content_link'] = site_url('ztatic/download_join_content?plopt=' . $playlist_option['playlist_type'] . '&plvalue=' . $playlist_option['playlist_value']);
                }
            }
        }

        if (isset($data['resource_playlist']['next']['url'])) {
            $continue_url = $data['resource_playlist']['next']['url']; //มีต่อเนื่อง
        } else {

            if (isset($data['resource_playlist']['rows'][0]['resource_id'])) {
                $continue_url = site_url("play/play_resource/confirm_playfirst/" . $data['resource_playlist']['rows'][0]['resource_id']);
            } else {
                $continue_url = site_url("play/play_resource/confirm_playfirst/" . $resource_id);
            }
        }
        $data['is_play_continue'] = $this->auth->is_play_continue();


// script var
        $script_var['playcontinue'] = array('value' => ( $data['is_play_continue']) ? 'true' : 'false');
        $get_str = '?' . $playlist_option['playlist_type'] . '=' . $playlist_option['playlist_value'];
        $script_var['ajax_init_url'] = site_url('play/play_resource/ajax_init_jwplay_video' . $get_str);
        $script_var['ajax_close_and_play_next_url'] = site_url('play/play_resource/ajax_close_and_jwplay_next')

        ;
        $script_var['ajax_close_play_url'] = site_url(
                'play/play_resource/ajax_close_play_video');
        $script_var['ajax_set_play_continue_url'] = site_url('play/play_resource/ajax_set_play_continue');
        if (isset($data['resource_playlist']['next']['resource_id'])) {
            $script_var['next_resource_id'] = array('value' => $data['resource_playlist'] ['next']['resource_id']);
        } else {
            $script_var['next_resource_id'] = array('value' => 0);
        }
        $script_var['is_rtmp'] = array('value' => ($this->is_rtmp) ? 'true' : 'false');
        $script_var['resource_id'] = array('value' => $resource_id);
        $script_var['now_url'] = current_url();
        $script_var['seek_time'] = array('value' => (int) $this->input->get('t'));
        $script_var['referer_url'] = $this->referer_url;
        // if ($this->is_rtmp) {
        //     $script_var['flowplayer_url'] = "http://releases.flowplayer.org/swf/flowplayer-3.2.16.swf";
        // } else {
        $script_var['flowplayer_url'] = base_url('assets/flowplayer/flowplayer-3.2.18.swf');
        $script_var['flowplayer_rtmp_file'] = "flowplayer.rtmp-3.2.13.swf";
        //  }
        $this->template->script_var($script_var);
        $this->template->load_flowplayer();
        $this->template->application_script('play/flowplay_video.js');
        $this->template->title($data['title']);
        $this->template->write_view('play/flowplay_video', $data);
        $this->template->load_jquery_switch();
        $this->template->render();
    }

    function ajax_close_and_flowplay_next() {
        $this->play_video_model->update_view_log($this->input->post('view_log_id'), TRUE);
        $this->ajax_init_jwplay_video();
    }

    function ajax_init_flowplay_video() {
//set playlist.
        $get_var = $this->input->get();
        $playlist_option = array(
            'playlist_type' => 'pltid',
            'playlist_value' => 0
        );

        if ($get_var) {
            if (isset($get_var['plsheet'])) {
                $playlist_option = array(
                    'playlist_type' => 'plsheet',
                    'playlist_value' => $get_var['plsheet']
                );
            }
            if (isset($get_var ['pltid'])) {
                $playlist_option = array(
                    'playlist_type' => 'pltid',
                    'playlist_value' => $get_var['pltid']
                );
            }
        }
        $get_str = '?' . $playlist_option['playlist_type'] . '=' . $playlist_option ['playlist_value'];
        $resource_id = $this->input->post('resource_id');
        $this->play_resource_model->init_resource($resource_id);
        $is_owner = $this->play_resource_model->is_owner();
        $this->play_video_model->init_resource($resource_id);
        $referer_url = str_replace(
                base_url(), '', $this->input->post('referer_url'));
        $data = $this->play_video_model->init_video_play($is_owner, $referer_url);
        switch ($this->play_video_model->get_resource_type_id()) {
            case 1://หลัก
                $data['netConnectionUrl'] = $this->config->item('netConnectionUrl');
                break;
            case 6:
                $data[
                        'netConnectionUrl'] = $this->config->item('prokru_netConnectionUrl');
                break;
            default:
                $data['netConnectionUrl'] = $this->
                        config->item('netConnectionUrl');
                break;
        }
        $data['trigger_url'] = site_url('play/play_resource/ajax_trigger_play_video');
        $data['request_trigger_time'] = $this->config->item('request_trigger_time') * 1000;

//ดึงข้อมูล playlist ตามรูปแบบ playlist
        switch ($playlist_option['playlist_type']) {
            case 'pltid':
                if ($playlist_option['playlist_value'] == 0) {
                    $data['resource_playlist'] = $this->play_resource_model->get_resource_video_data_same_sub_taxonomy($resource_id);
                } else {
                    $data['resource_playlist'] = $this->play_resource_model->get_resource_video_data_in_sub_taxonomy($playlist_option['playlist_value'], $resource_id);
                }
                break;
            case 'plsheet':
                $data['resource_playlist'] = $this->play_resource_model->get_resource_video_data_in_sheet($playlist_option['playlist_value'], $resource_id);

                break;
            default:

                break;
        }

//$data['debug'] =$data['resource_playlist'];
        $data['now_resource_id'] = $resource_id;
        if (isset($data['resource_playlist']['next']['url'])) {
            $data['next_resource_id'] = $data['resource_playlist']['next']['resource_id'];
            $continue_url = $data['resource_playlist']['next']['url'];
        } else {
            $data['next_resource_id'] = 0;
            if (isset($data['resource_playlist']['rows'][0]['resource_id'])) {
                $continue_url = site_url("play/play_resource/confirm_playfirst/" . $data['resource_playlist']['rows'][0]['resource_id'] . $get_str);
            } else {
                $continue_url = site_url("play/play_resource/confirm_playfirst/" . $resource_id . $get_str);
            }
        }

        $data['continue_url'] = $continue_url;
        $data['not_continue_url'] = site_url("play/play_resource/confirm_playagain/" . $resource_id . $get_str);
        $data ['now_url'] = site_url('v/' . $resource_id . '/' . $get_str);
        if ($this->is_rtmp) {
            $data['jwplayer_file'] = $data['netConnectionUrl'] . '/flv:' . $data['video_path'];
        } else {
            $data['jwplayer_file'] = site_url('video/' . $data['video_path']);
        }

        echo json_encode($data);
    }

// ส่งข้อมูลการเล่น video
    function ajax_trigger_play_video() {
        $value = $this->play_video_model->update_view_log($this->input->post('view_log_id'));
        echo json_encode($value);
    }

//ปิดการเล่น video
    function ajax_close_play_video() {
        $value = $this->play_video_model->update_view_log($this->input->post('view_log_id'), TRUE);
        echo json_encode($value);
    }

    function ajax_set_play_continue() {
        $set = $this->input->post('play_continue');
        $this->auth->set_play_continue($set);
    }

//เล่น video แบบฟรี
    private function play_video_free() {
        $resource_id = $this->play_resource_model->get_resource_id();
        $this->template->load_flowplayer();
        $this->template->application_script('play/play_video_free.js');
        $this->template->og_image(site_url('resource/ntimg/video_thumbnail/' . $resource_id));
        $data['title'] = $this->play_resource_model->get_title();

        $data['video_path'] = $this->play_resource_model->get_video_path();
//$data['resource_doc'] = $this->play_resource_model->get_join_doc();
        $data['facebook_url'] = site_url('v/' . $resource_id);
        $data['resource_playlist'] = $this->play_resource_model->get_resource_video_data_same_sub_taxonomy($resource_id);
        $this->template->description('เนื้อหา : ' . $data['title']);
        $this->template->title($data['title'] . ' [ฟรี]');
        $this->template->write_view('play/play_video_free', $data);
        $this->template
                ->render();
    }

//เล่น video เมื่อไม่ได้ login
    private function play_video_not_login() {
        $resource_id = $this->play_resource_model->get_resource_id();
        $this->template->og_image(site_url('resource/ntimg/video_thumbnail/' . $resource_id));
        $data['title'] = $this->play_resource_model->get_title();
        $data['video_path'] = $this->play_resource_model->get_video_path();
        $data['resource_code'] = $this->play_resource_model->get_resource_code();
//$data['resource_doc'] = $this->play_resource_model->get_join_doc();
        $data['resource_id'] = $resource_id;
        $data['facebook_url'] = site_url('v/' . $resource_id);

        $login_data = array(
                    'form_title' => 'โปรดลงชื่อเข้าใช้งาน',
                    'form_action' => site_url('user/do_login'),
                    'forget_pass_link' => site_url('user/forget_pass'),
                    'referer_url' => uri_string(),
                    'username_field' => $this->config->item('username_field')
        );

        $data['login_form'] = $this->load->view('play/player_login_form', $login_data, TRUE);

        $this->template->description('เนื้อหา : ' . $data['title']);
        $this->template->title($data['title']);
        $this->template->write_view('play/play_video_not_login', $data);
        $this->template->render();
    }

// sitevideo  Section ====================================================================
    private function play_youtube() {
        $resource_id = $this->play_resource_model->get_resource_id();
        $this->load->model('play/play_sitevideo_model');
        $this->play_sitevideo_model->init_resource($resource_id);
        $data = $this->play_sitevideo_model->get_video_data();
//$data['facebook_url'] = site_url('v/' . $resource_id);
        $data['facebook_like_url'] = site_url('v/' . $resource_id);
        $data['facebook_url'] = site_url('v/' .
                $resource_id);
        $data['affiliate_link'] = '';
        if ($this->auth->is_login()) {
            if ($this->auth->get_affiliate_type_id() > 0) {
                $data['affiliate_link'] = site_url('v/' . $resource_id) . '/?affiliate_code=' . $this->affiliate_model->encode_affiliate_code($this->auth->uid());
            }
        }
        $this->template->title($data['title']);
        $this->template->description($data['desc']);
        $this->template->write_view('play/play_youtube', $data);
        $this->template->render();
    }

    function play_dailymotion
    () {
        $resource_id = $this->play_resource_model->get_resource_id();
        $this->load->model('play/play_sitevideo_model');
        $this->play_sitevideo_model->init_resource($resource_id);
        $data = $this->play_sitevideo_model->get_video_data();
        $data['facebook_url'] = site_url('v/' . $resource_id);
        $this->template->write_view('play/play_dailymotion', $data);
        $this->template->render();
    }

// doc  Section ====================================================================
    private function play_doc() {
//$resource_id = $this->play_resource_model->get_resource_id();
        switch ($this->play_resource_model->get_file_ext()) {
            case 'pdf':
                $this->play_pdf();
                break;
            case 'swf':
                $this->play_swf();
                break;
            default:
                $this->load->helper('download');
                $filename = $this->play_resource_model->get_full_file_path();
                $title = $this->play_resource_model->get_title() . '.' . $this->play_resource_model->get_file_ext();
                force_download_file($title, $filename);
                break;
        }
    }

    private function play_pdf() {
        $this->template->write_view('play/play_pdf', $this->play_resource_model->get_resource_data());
        $this->template->title('เอกสารประกอบการเรียน-' . $this->play_resource_model->get_title());
        $this->template->description($this->play_resource_model->get_desc());
        $this->template->render();
    }

    private function play_swf() {
        $resource_data = $this->play_resource_model->get_resource_data();
        $data['title'] = $resource_data['title'];

        $this->template->script_var(
                array(
                    'expressInstall_url' => base_url('files/swf/expressInstall.swf'),
                    //'swf_url' => base_url($this->config->item('flash_media_dir') . $resource_data['resource_id'] . '/' . $resource_data['file_path'])//ควรเปลี่ยนเป็น file_path
                    'swf_url' => site_url('ztatic/content_doc_swf/' . $resource_data['resource_id']))//ควรเปลี่ยนเป็น file_path
        );
        $this->template->load_swfobject();
        $this->template->write_view('play/play_swf', $data);
        $this->template->render();
    }

    public function download_pdf($resource_id) {
        $init_result = $this->play_resource_model->init_resource($resource_id);
        if (!$init_result) {
            return;
        }
        $filename = $this->play_resource_model->get_full_file_path();
        if ($filename) {
            $this->load->helper('download');
            $this->load->helper('file');
            force_download(str_replace(' ', '-', $this->play_resource_model->get_title()) . '.' . $this->play_resource_model->get_file_ext(), read_file($filename));
        }
    }

// play image section
    private function play_image($resource_id) {
        $this->load->helper('html');
        $resource_data = $this->play_resource_model->get_resource_data(
                $resource_id);
        $resource_data['image_url'] = site_url('ztatic/resource_image/' . $resource_id);
        $this->template->write_view('play/play_image', $resource_data);
        $this->template->render();
    }

// play_dycontent Section ====================================================================================

    public function pdf_dycontent($resource_id, $render_solve = FALSE) {
        $this->db->where('resource_id', $resource_id);
        $q1 = $this->db->get('r_resource');
        $this->main_resource_data = $q1->row_array();
        $this->db->where('r_resource_dycontent.resource_id', $resource_id);
        $q2 = $this->db->get('r_resource_dycontent');
        $dycontent_data = $this->decode_data($q2->row()->data);
        $this->load->model('resource/xelatex_dycontent_model');
        if ($this->main_resource_data['title']['uid_owner'] == $this->auth->uid()) {
            $this->xelatex_dycontent_model->init_content($dycontent_data, TRUE);
        } else {
            $this->xelatex_dycontent_model->init_content($dycontent_data, $render_solve);
        }
//        if ($this->main_resource_data['title']['uid_owner'] == $this->auth->uid()) {
//            $this->xelatex_dycontent_model->show_solve_answer(TRUE);
//        } else {
//            $this->xelatex_dycontent_model->show_solve_answer(TRUE);
//        }

        $render_result = $this->xelatex_dycontent_model->render_pdf();
//        print_r($render_result);
//        exit();
        if (is_file($render_result['files'][0])) {

            $this->load->helper('download');
            $this->load->helper('file');
            force_download_file(str_replace(' ', '-', $this->main_resource_data['title']) . '.pdf', $render_result['files'][0]);
        }
    }

    public function pdf_sheet($resource_id) {
        $this->load->model('resource/xelatex_sheet_model');
        $this->load->model('resource/sheet_model');
        $resource_data = $this->sheet_model->get_resource_data($resource_id);

        $resource_data['data']['resources'] = $resource_data['data']['sheet_set'];
        $data['explanation'] = $resource_data['explanation'];
        $data['resources'] = $resource_data['data']['sheet_set'];
        $data['title'] = $resource_data['title'];
        $data['la_id'] = $resource_data['la_id'];
        $data['subj_id'] = $resource_data['subj_id'];
        $data['chapter_id'] = $resource_data['chapter_id'];
        $data['chapter_title'] = $resource_data['chapter_title'];
        $data['degree_id'] = $resource_data['degree_id'];
        $data['uid_owner'] = $resource_data['uid_owner'];
        $show_solve = FALSE;
        if ($this->auth->get_rid() == 3) {
            $show_solve = TRUE;
        }
        if (!$this->xelatex_sheet_model->init_content($data, $show_solve)) {
            $value['status'] = FALSE;
            $value['render'] = implode('', $this->xelatex_sheet_model->error_msg());
            return $value;
        }
        $render_result = $this->xelatex_sheet_model->render_pdf();
        $this->load->helper('download');
        $this->load->helper('file');
        $path_parts = pathinfo($render_result['files']['0']);
        $download_filename = $resource_data['title'] . '.' . $path_parts['extension'];
        force_download_file($download_filename, $render_result['files']['0']);
    }

    function play_question_solve($resource_id) {
        $init_result = $this->play_resource_model->init_resource($resource_id);
        if ($init_result) {
            $this->play_dycontent(TRUE);
        }
    }

    public function play_dycontent($render_solve = FALSE) {

        $resource_id = $this->play_resource_model->get_resource_id();

        $this->db->where('r_resource_dycontent.resource_id', $resource_id);
        $q1 = $this->db->get('r_resource_dycontent');
        $dycontent_data = $this->decode_data($q1->row()->data);

        switch ($q1->row()->render_type_id) {
            case 1: // for Latex
//[1:เนื้อหา | 2:โจทย์ตัวเลือก | 3:โจทย์เติมคำ | 4:โจทยจับคู่]
                $this->load->model('resource/xelatex_dycontent_model');
                $this->xelatex_dycontent_model->init_content($dycontent_data, $render_solve);
                $render_result = $this->xelatex_dycontent_model->render();
                if (isset($render_result['error_msg'])) { // ถ้ามีข้อผิดพลาด
                    $data['render'] = $render_result['error_msg'];
                } else {

                    $data['render'] = $this->load->view('/xelatex/a4_preview', array('render_result' => $render_result), TRUE);
                }
                $data['title'] = $this->play_resource_model->get_title();
                $data['resource_id'] = $this->play_resource_model->get_resource_id();
                $data['render_solve'] = $render_solve;

                $this->template->title($data['title']);
                $this->template->description($this->main_resource_data['desc']);
                $this->template->link('assets/application/xelatex/a4_preview.css');
                $this->template->write_view('play/play_dycontent', $data);
                $this->template->render();
                break;
            case 2: //for html
                break;
            case 3://for bbcode
                break;
            default:
                break;
        }
    }

    public function open_sheet($resource_id, $uid_teacher = '') {
        $init_result = $this->play_resource_model->init_resource($resource_id);
        if (!$init_result) {
            $data = array(
                'time' => 5,
                'url' => site_url(),
                'heading' => ' ไม่พบหน้าที่ต้องการ',
                'message' => '<p>ไม่พบหน้าที่ต้องการ อาจเป็นเพราะ ข้อมูลถูกลบไปแล้ว</p>'
            );
            $this->load->view('refresh_page', $data);
            return;
        } else {

            $this->play_sheet(TRUE, $uid_teacher);
        }
    }

    public function play_sheet($show_solve = FALSE, $uid_owner = '') {
//        $show_solve = FALSE;
//        if ($this->auth->get_rid() == 3) {
//            $show_solve = TRUE;
//        }
        $resource_id = $this->play_resource_model->get_resource_id();
        $this->load->model('resource/xelatex_sheet_model');
        $this->load->model('resource/sheet_model');
        $resource_data = $this->sheet_model->get_resource_data($resource_id);
        $resource_data['data']['resources'] = $resource_data['data']['sheet_set'];
        $data['uid_owner'] = $resource_data['uid_owner'];
        if ($uid_owner != '') {
            $data['uid_owner'] = $uid_owner;
        }
        $data['explanation'] = $resource_data['explanation'];
        $data['resources'] = $resource_data['data']['sheet_set'];
        $data['title'] = $resource_data['title'];
        $data['la_id'] = $resource_data['la_id'];
        $data['subj_id'] = $resource_data['subj_id'];
        $data['chapter_id'] = $resource_data['chapter_id'];
        $data['chapter_title'] = $resource_data['chapter_title'];
        $data['degree_id'] = $resource_data['degree_id'];
        $this->db->where('r_resource_sheet.resource_id', $resource_id);
        $q1 = $this->db->get('r_resource_sheet');
        switch ($q1->row()->render_type_id) {
            case 1: // for Latex
//[1:เนื้อหา | 2:โจทย์ตัวเลือก | 3:โจทย์เติมคำ | 4:โจทยจับคู่]
                $this->load->model('resource/xelatex_dycontent_model');

                if (!$this->xelatex_sheet_model->init_content($data, $show_solve)) {
                    $value['status'] = FALSE;
                    $value['render'] = implode('', $this->xelatex_sheet_model->error_msg());
                    return $value;
                }

                $render_result = $this->xelatex_sheet_model->render();
                if (isset($render_result['error_msg'])) { // ถ้ามีข้อผิดพลาด
                    $data['render'] = $render_result['error_msg'];
                } else {

                    $data['render'] = $this->load->view('/xelatex/a4_preview', array('render_result' => $render_result), TRUE);
                }
                $data['title'] = $this->play_resource_model->get_title();
                $data['resource_id'] = $this->play_resource_model->get_resource_id();

                $this->template->title($data['title']);
                $this->template->description($this->main_resource_data['desc']);
                $this->template->link('assets/application/xelatex/a4_preview.css');
                $this->template->write_view('play/play_sheet', $data);
                $this->template->render();
                break;
            case 2: //for html
                break;
            case 3://for bbcode
                break;
            default:
                break;
        }
    }

    function play_sheet_video($resource_id_sheet) {
        $resource_id_video = $this->play_resource_model->get_first_resource_id_video_in_sheet($resource_id_sheet);
        if ($resource_id_sheet) {
            redirect('v/' . $resource_id_video . '?plsheet=' . $resource_id_sheet);
        } else {
            echo 'fail';
        }
    }

//    function play_flash_media() {
//$resource_data = $this->play_resource_model->get_resource_data();
////        $data['title'] = $resource_data['title'];
////        $this->template->script_var(
////                array(
////                    'expressInstall_url' => base_url('files/swf/expressInstall.swf'),
////                    'swf_url' => base_url($this->config->item('flash_media_dir') . $resource_data['resource_id'] . '/' . $resource_data['file_path'])//ควรเปลี่ยนเป็น file_path
////                )
////        );
////        $this->template->load_swfobject();
////        $this->template->write_view('play/play_flash_media', $data);
////        $this->template->render();
//        redirect('ztatic/flash_media/');
//    }

    function decode_data($data) {

        return json_decode($data, TRUE);
    }

    function flash_media($resource_id, $swf_file) {
        $ext = end(explode('.', $swf_file));
        if ($ext != 'play') {
            $swf_path = $this->config->item('full_flash_media_dir') . str_replace('play/play_resource/flash_media/', '', uri_string());
            header("Content-Type: application/x-shockwave-flash");
            readfile($swf_path);
        } else {

            $this->db->where('resource_id', $resource_id);
            $q1 = $this->db->get('r_resource');
            $row1 = $q1->row_array();
            $this->db->where('resource_id', $resource_id);
            $q2 = $this->db->get('r_resource_flash_media');
            $row2 = $q2->row_array();
            $resource_data = array_merge($row1, $row2);
            $data['title'] = $resource_data['title'];

            $this->template->script_var(
                    array(
                        'expressInstall_url' => base_url('files/swf/expressInstall.swf'),
                        //'swf_url' => base_url($this->config->item('flash_media_dir') . $resource_data['resource_id'] . '/' . $resource_data['file_path'])//ควรเปลี่ยนเป็น file_path
                        'swf_url' => site_url('ztatic/flash_media/' . $resource_id . '/' . $resource_data['file_path'])//ควรเปลี่ยนเป็น file_path
                    )
            );
            $this->template->load_swfobject();
            $this->template->write_view('play/play_flash_media', $data);
            $this->template->render();
        }
    }

    function sync_video_file() {

        $nt_full_video_dir = '/var/www/clients/client3/web16/video/';
        $core_full_video_dir = $this->config->item('full_video_dir');
        $temp = $this->play_resource_model->get_resource_data();
        $nt_file_path = $nt_full_video_dir . $temp['file_path'];
        $core_file_path = $core_full_video_dir . $temp['file_path'];
        if (@is_file($nt_file_path)) {

            if (!is_file($core_file_path)) {

                if (copy($nt_file_path, $core_file_path)) {
                    
                } else {
                    
                }
            }
        }
    }

}
