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

    public function __construct() {
        parent::__construct();
        $this->load->model('affiliate/affiliate_model');
        $this->load->model('play/play_resource_model');
        $this->load->model('play/play_video_model');
        $this->make_money = $this->config->item('make_money');
        $this->is_rtmp = $this->config->item('is_rtmp');
    }

    
    //
    function confirm_playagain($resource_id) {
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
            'message' => '<p>คุณดู video ' . $title . ' เสร็จแล้วคุณต้องการเข้าชมใหม่อีกครั้งหรือไม่</p>'
        );
        $this->load->view('confirm_page', $data);
    }

    function confirm_playfirst($resource_id) {
        $this->play_resource_model->init_resource($resource_id);
        //$title = $this->play_resource_model->get_title();
        //$unit_price = $this->play_resource_model->get_unit_price();

        $data = array(
            'url' => site_url('v/' . $resource_id),
            'cancel_url' => site_url(),
            'heading' => 'ดูวิดีโออีกรอบไหม',
            'message' => '<p>คุณดูวิดีโอในบทนี้เสร็จเรียบร้อยแล้วคุณอยากดูซ้ำอีกหรือไม่</p>',
            'btn_cancel' => 'ไม่แล้วหละ !'
        );
        $this->load->view('confirm_page', $data);
    }

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

// เริ่มต้นการเล่น
    function resource_id($resource_id) {
        $this->affiliate_model->set_uid_affiliate();
        if ($this->agent->browser() == 'Internet Explorer') {
            $data = array(
                'time' => 15,
                'url' => site_url(),
                'heading' => 'มีข้อผิดพลาดเกิดขึ้น',
                'message' => '<p>บราวเซอร์ ของคุณไม่รองรับการใช้งาน โปรดดาวน์โหลด และ ติดตั้งโปรแกรมบราวเซอร์ <a href="http://www.mozilla.org/th/firefox/new/" target="_blank">Firefox</a> หรือ
                    <a href="http://www.google.com/intl/th/chrome/browser/" target="_blank">Chrome</a> เคุณั้น</p>'
            );
            $this->load->view('refresh_page', $data);
            return;
        }
// check resource type
        $init_result = $this->play_resource_model->init_resource($resource_id);
        if (!$init_result) {
            $data = array(
                'time' => 15,
                'url' => site_url(),
                'heading' => ' ไม่พบหน้าที่ต้องการ',
                'message' => '<p>ไม่พบหน้าที่ต้องการ อาจเป็นเพราะ ข้อมูลถูกลบไปแล้ว</p>'
            );
            $this->load->view('refresh_page', $data);
            return;
        }

        switch ($this->play_resource_model->get_resource_type_id()) {
            case 1:case 7: // video
                if ($this->auth->is_login()) {
                    if ($this->play_resource_model->is_owner()) {  // เช็คความเป็นเจ้าของ
                        $this->play_video_owner();
                    } else if ($this->play_resource_model->is_free() || !$this->make_money || $this->auth->have_money()) { //เช็คว่าดูฟรีหรือไม่ หรือต้องมีเงิน
                        $this->play_resource_model->plus_view();
                        $this->jwplay_video();
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

                break;
            case 2: //เอกสาร ที่ upload
                $this->play_doc($resource_id);
                break;
            case 3: //เอกสาร ที่ upload
                $this->play_image($resource_id);
                break;

            case 4: //เอกสารที่ปรับแก้ไก้
                $this->play_dycontent();
                break;
            case 5: //เอกสาร sheet
                $this->play_sheet();
                break;
            case 6: // video จากเว็บไซต์ Youtube
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
            default:
                break;
        }
    }

// Video Section ====================================================================
// เล่น video แบบหักเงินธรรมดา

    function play_video() {
        $resource_id = $this->play_resource_model->get_resource_id();
        $this->template->load_flowplayer();
        $this->template->application_script('play/play_video.js');
        $data['title'] = $this->play_resource_model->get_title();
        $data['resource_code'] = $this->play_resource_model->get_resource_code();
        $data['resource_doc'] = $this->play_resource_model->get_join_doc();
        $unit_price = $this->play_resource_model->get_unit_price();
        if ($this->make_money) {
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
            $data['price_txt'] = '';
        }
        $data['resource_id'] = $resource_id;

        $data['facebook_like_url'] = site_url('v/' . $resource_id);
        $data['facebook_url'] = site_url('v/' . $resource_id);
        $data['affiliate_link'] = '';
        if ($this->auth->is_login()) {
            if ($this->auth->get_affiliate_type_id() > 0) {
                $data['affiliate_link'] = site_url('v/' . $resource_id) . '/?affiliate_code=' . $this->affiliate_model->encode_affiliate_code($this->auth->uid());
            }
        }
        $data['resource_same_sub_taxonomy'] = $this->play_resource_model->get_resource_video_data_same_sub_taxonomy($resource_id);
        $data['is_play_continue'] = $this->auth->is_play_continue();

        if (isset($data['resource_same_sub_taxonomy']['next']['url'])) {
            $continue_url = $data['resource_same_sub_taxonomy']['next']['url'];
        } else {
            $continue_url = site_url("play/play_resource/confirm_playfirst/" . $data['resource_same_sub_taxonomy']['rows'][0]['resource_id']);
        }

        $data['on_last_second_url'] = array(
            'continue_url' => $continue_url,
            'not_continue_url' => site_url("play/play_resource/confirm_playagain/" . $resource_id)
        );




        $data['script_var']['ajax_init_url'] = site_url('play/play_resource/ajax_init_play_video');
        $data['script_var']['ajax_close_and_play_next_url'] = site_url('play/play_resource/ajax_close_and_play_next');
        $data['script_var']['ajax_close_play_url'] = site_url('play/play_resource/ajax_close_play_video');
        $data['script_var']['ajax_set_play_continue_url'] = site_url('play/play_resource/ajax_set_play_continue');
        if (isset($data['resource_same_sub_taxonomy']['next']['resource_id'])) {
            $data['script_var']['next_resource_id'] = $data['resource_same_sub_taxonomy']['next']['resource_id'];
        } else {
            $data['script_var']['next_resource_id'] = 0;
        }

        $data['script_var']['resource_id'] = $resource_id;
        $data['script_var']['now_url'] = current_url();
        $data['script_var']['seek_time'] = (int) $this->input->get('t');


        $this->template->title($data['title']);
        $this->template->write_view('play/play_video', $data);
        $this->template->load_jquery_switch();
        $this->template->render();
    }

    function jwplay_video() {
        $resource_id = $this->play_resource_model->get_resource_id();
        //ข้อมูลหน้าจอ
        $data['title'] = $this->play_resource_model->get_title();
        $data['resource_code'] = $this->play_resource_model->get_resource_code();
        $data['resource_doc'] = $this->play_resource_model->get_join_doc();
        $unit_price = $this->play_resource_model->get_unit_price();
        if ($this->make_money) {
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
            $data['price_txt'] = '';
        }
        $data['resource_id'] = $resource_id;
        $data['facebook_like_url'] = site_url('v/' . $resource_id);
        $data['facebook_url'] = site_url('v/' . $resource_id);
        $data['affiliate_link'] = '';
        if ($this->auth->is_login()) {
            if ($this->auth->get_affiliate_type_id() > 0) {
                $data['affiliate_link'] = site_url('v/' . $resource_id) . '/?affiliate_code=' . $this->affiliate_model->encode_affiliate_code($this->auth->uid());
            }
        }
        $data['resource_same_sub_taxonomy'] = $this->play_resource_model->get_resource_video_data_same_sub_taxonomy($resource_id);
        if (isset($data['resource_same_sub_taxonomy']['next']['url'])) {
            $continue_url = $data['resource_same_sub_taxonomy']['next']['url']; //มีต่อเนื่อง
        } else {
            if (isset($data['resource_same_sub_taxonomy']['rows'][0]['resource_id'])) {
                $continue_url = site_url("play/play_resource/confirm_playfirst/" . $data['resource_same_sub_taxonomy']['rows'][0]['resource_id']);
            } else {
                $continue_url = site_url("play/play_resource/confirm_playfirst/" . $resource_id);
            }
        }
        $data['is_play_continue'] = $this->auth->is_play_continue();
// script var
        $script_var['playcontinue'] = array('value' => ( $data['is_play_continue']) ? 'true' : 'false');
        //$script_var['on_last_second_url'] = array('value' => '{continue_url: "' . $continue_url . '", not_continue_url: "' . site_url("play/play_resource/confirm_playagain/" . $resource_id) . '"};');
        $script_var['ajax_init_url'] = site_url('play/play_resource/ajax_init_jwplay_video');
        $script_var['ajax_close_and_play_next_url'] = site_url('play/play_resource/ajax_close_and_jwplay_next');
        $script_var['ajax_close_play_url'] = site_url('play/play_resource/ajax_close_play_video');
        $script_var['ajax_set_play_continue_url'] = site_url('play/play_resource/ajax_set_play_continue');
        if (isset($data['resource_same_sub_taxonomy']['next']['resource_id'])) {
            $script_var['next_resource_id'] = array('value' => $data['resource_same_sub_taxonomy']['next']['resource_id']);
        } else {
            $script_var['next_resource_id'] = array('value' => 0);
        }
        $script_var['resource_id'] = array('value' => $resource_id);
        $script_var['now_url'] = current_url();
        $script_var['seek_time'] = array('value' => (int) $this->input->get('t'));
        $this->template->script_var($script_var);
        $this->template->load_jwplayer();
        $this->template->application_script('play/jwplay_video.js');
        $this->template->title($data['title']);
        $this->template->write_view('play/jwplay_video', $data);
        $this->template->load_jquery_switch();
        $this->template->render();
    }

    function ajax_close_and_play_next() {
        $this->play_video_model->update_view_log($this->input->post('view_log_id'), TRUE);
        $this->ajax_init_play_video();
    }

    function ajax_close_and_jwplay_next() {
        $this->play_video_model->update_view_log($this->input->post('view_log_id'), TRUE);
        $this->ajax_init_jwplay_video();
    }

//    function play_video_money_bonus() {
//        $resource_id = $this->play_resource_model->get_resource_id();
//        $this->template->load_flowplayer();
//        $this->template->application_script('play/play_video_money_bonus.js');
//        $data['title'] = $this->play_resource_model->get_title();
//        $data['resource_code'] = $this->play_resource_model->get_resource_code();
//
//        $data['resource_doc'] = $this->play_resource_model->get_join_doc();
//        $data['price_txt'] = $this->play_resource_model->get_unit_price();
//        $data['resource_id'] = $resource_id;
//
//        $data['facebook_like_url'] = site_url('v/' . $resource_id);
//        $data['facebook_url'] = site_url('v/' . $resource_id);
//        $data['affiliate_link'] = '';
//        if ($this->auth->is_login()) {
//            if ($this->auth->get_affiliate_type_id() > 0) {
//                $data['affiliate_link'] = site_url('v/' . $resource_id) . '/?affiliate_code=' . $this->affiliate_model->encode_affiliate_code($this->auth->uid());
//            }
//        }
//        $data['resource_same_sub_taxonomy'] = $this->play_resource_model->get_resource_video_data_same_sub_taxonomy($resource_id);
//        $this->template->title($data['title'] . ' [ใช้เงินโบนัส]');
//        $this->template->write_view('play/play_video_money_bonus', $data);
//        $this->template->load_jquery_switch();
//        $this->template->render();
//    }
//ดึงข้อมูลเริ่มต้นการเล่น video
    function ajax_init_play_video() {
        $resource_id = $this->input->post('resource_id');
        $this->play_video_model->init_resource($resource_id);
        $data = $this->play_video_model->init_video_play();
        switch ($this->play_video_model->get_resource_type_id()) {
            case 1:
                $data['netConnectionUrl'] = $this->config->item('netConnectionUrl');
                break;
            case 6:
                $data['netConnectionUrl'] = $this->config->item('prokru_netConnectionUrl');
                break;
            default:
                $data['netConnectionUrl'] = $this->config->item('netConnectionUrl');
                break;
        }
        $data['trigger_url'] = site_url('play/play_resource/ajax_trigger_play_video');
        $data['request_trigger_time'] = $this->config->item('request_trigger_time') * 1000;
        $same_sub_taxonomy = $this->play_resource_model->get_resource_video_data_same_sub_taxonomy($resource_id);

        if (isset($same_sub_taxonomy['next']['resource_id'])) {
            $data['next_resource_id'] = $same_sub_taxonomy['next']['resource_id'];
        } else {
            $data['next_resource_id'] = 0;
        }


        // $data['next_resource_id'] = $same_sub_taxonomy['next']['resource_id'];
        $data['now_resource_id'] = $resource_id;
        if (isset($same_sub_taxonomy['next']['url'])) {
            $continue_url = $same_sub_taxonomy['next']['url'];
        } else {
            $continue_url = site_url("play/play_resource/confirm_playfirst/" . $same_sub_taxonomy['rows'][0]['resource_id']);
        }
        $data['continue_url'] = $continue_url;
        $data['not_continue_url'] = site_url("play/play_resource/confirm_playagain/" . $resource_id);
        $data['now_url'] = site_url('v/' . $resource_id);
        echo json_encode($data);
    }

    function ajax_init_jwplay_video() {
        $resource_id = $this->input->post('resource_id');
        $this->play_video_model->init_resource($resource_id);
        $data = $this->play_video_model->init_video_play();
        switch ($this->play_video_model->get_resource_type_id()) {
            case 1:
                $data['netConnectionUrl'] = $this->config->item('netConnectionUrl');
                break;
            case 6:
                $data['netConnectionUrl'] = $this->config->item('prokru_netConnectionUrl');
                break;
            default:
                $data['netConnectionUrl'] = $this->config->item('netConnectionUrl');
                break;
        }
        $data['trigger_url'] = site_url('play/play_resource/ajax_trigger_play_video');
        $data['request_trigger_time'] = $this->config->item('request_trigger_time') * 1000;
        $same_sub_taxonomy = $this->play_resource_model->get_resource_video_data_same_sub_taxonomy($resource_id);

        if (isset($same_sub_taxonomy['next']['resource_id'])) {
            $data['next_resource_id'] = $same_sub_taxonomy['next']['resource_id'];
        } else {
            $data['next_resource_id'] = 0;
        }


        // $data['next_resource_id'] = $same_sub_taxonomy['next']['resource_id'];
        $data['now_resource_id'] = $resource_id;
        if (isset($same_sub_taxonomy['next']['url'])) {
            $continue_url = $same_sub_taxonomy['next']['url'];
        } else {
            if (isset($same_sub_taxonomy['rows'][0]['resource_id'])) {
                $continue_url = site_url("play/play_resource/confirm_playfirst/" . $same_sub_taxonomy['rows'][0]['resource_id']);
            } else {
                $continue_url = site_url("play/play_resource/confirm_playfirst/" . $resource_id);
            }
        }
        $data['continue_url'] = $continue_url;
        $data['not_continue_url'] = site_url("play/play_resource/confirm_playagain/" . $resource_id);
        $data['now_url'] = site_url('v/' . $resource_id);
        if ($this->is_rtmp) {
            $data['jwplayer_file'] = $data['netConnectionUrl'] . '/flv:' . $data['video_path'];
        } else {
            $data['jwplayer_file'] = site_url('video/' . $data['video_path']);
        }

        echo json_encode($data);
    }

//    function ajax_init_play_video_money_bonus() {
//        $this->play_video_model->init_resource($this->input->post('resource_id'));
//        $data = $this->play_video_model->init_video_play_money_bonus();
//        switch ($this->play_video_model->get_resource_type_id()) {
//            case 1:
//                $data['netConnectionUrl'] = $this->config->item('netConnectionUrl');
//                break;
//            case 6:
//                $data['netConnectionUrl'] = $this->config->item('prokru_netConnectionUrl');
//                break;
//            default:
//                $data['netConnectionUrl'] = $this->config->item('netConnectionUrl');
//                break;
//        }
//
//        $data['trigger_url'] = site_url('play/play_resource/ajax_trigger_play_video_money_bonus');
//        $data['request_trigger_time'] = $this->config->item('request_trigger_time') * 1000;
//        echo json_encode($data);
//    }
// ส่งข้อมูลการเล่น video
    function ajax_trigger_play_video() {
        $value = $this->play_video_model->update_view_log($this->input->post('view_log_id'));
        echo json_encode($value);
    }

//    function ajax_trigger_play_video_money_bonus() {
//        $value = $this->play_video_model->update_view_log_money_bonus($this->input->post('view_log_id'));
//        echo json_encode($value);
//    }
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
        $data['resource_doc'] = $this->play_resource_model->get_join_doc();
        $data['facebook_url'] = site_url('v/' . $resource_id);
        $data['resource_same_sub_taxonomy'] = $this->play_resource_model->get_resource_video_data_same_sub_taxonomy($resource_id);
        $this->template->description('เนื้อหา : ' . $data['title']);
        $this->template->title($data['title'] . ' [ฟรี]');
        $this->template->write_view('play/play_video_free', $data);
        $this->template->render();
    }

//เล่น video เมื่อไม่ได้ login
    private function play_video_not_login() {
        $resource_id = $this->play_resource_model->get_resource_id();
        $this->template->og_image(site_url('resource/ntimg/video_thumbnail/' . $resource_id));
        $data['title'] = $this->play_resource_model->get_title();
        $data['video_path'] = $this->play_resource_model->get_video_path();
        $data['resource_code'] = $this->play_resource_model->get_resource_code();
        $data['resource_doc'] = $this->play_resource_model->get_join_doc();
        $data['resource_id'] = $resource_id;
        $data['facebook_url'] = site_url('v/' . $resource_id);

        $login_data = array(
            'form_title' => 'โปรดลงชื่อเข้าใช้งาน',
            'form_action' => site_url('user/do_login'),
            'forget_pass_link' => site_url('user/forget_pass'),
            'referer_url' => uri_string()
        );
        $data['login_form'] = $this->load->view('play/player_login_form', $login_data, TRUE);
        $this->template->description('เนื้อหา : ' . $data['title']);
        $this->template->title($data['title']);
        $this->template->write_view('play/play_video_not_login', $data);
        $this->template->render();
    }

//เล่น video ของตนเอง
    private function play_video_owner() {
        $resource_id = $this->play_resource_model->get_resource_id();
        $this->template->load_flowplayer();
        $this->template->application_script('play/play_video_owner.js');
        $this->template->og_image(site_url('resource/ntimg/video_thumbnail/' . $resource_id));
        $data['title'] = $this->play_resource_model->get_title();


        $data['facebook_url'] = site_url('v/' . $resource_id);
        if ($this->is_rtmp) {
            $this->template->script_var(array(
                'jw_video_url' => $this->config->item('netConnectionUrl') . $this->play_resource_model->get_video_path()
            ));
        } else {
            $this->template->script_var(array(
                'jw_video_url' => $this->config->item('netConnectionUrl') . $this->play_resource_model->get_video_path()
            ));
        }

        $this->template->load_jwplayer();
        $this->template->description($data['title']);
        $this->template->title($data['title']);
        $this->template->write_view('play/play_video_owner', $data);
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
        $data['facebook_url'] = site_url('v/' . $resource_id);
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

    function play_dailymotion() {
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
        $resource_data = $this->play_resource_model->get_resource_data($resource_id);
        $resource_data['image_url'] = site_url('ztatic/resource_image/' . $resource_id);
        $this->template->write_view('play/play_image', $resource_data);
        $this->template->render();
    }

    // play_dycontent Section ====================================================================================
    public function pdf_dycontent($resource_id) {
        $this->db->where('resource_id', $resource_id);
        $q1 = $this->db->get('r_resource');
        $this->main_resource_data = $q1->row_array();
        $this->db->where('r_resource_dycontent.resource_id', $resource_id);
        $q2 = $this->db->get('r_resource_dycontent');
        $dycontent_data = $this->decode_data($q2->row()->data);
        $this->load->model('resource/xelatex_dycontent_model');
        $this->xelatex_dycontent_model->init_content($dycontent_data);
        $render_result = $this->xelatex_dycontent_model->render_pdf();
        if (is_file($render_result['files'][0])) {
            $this->load->helper('download');
            $this->load->helper('file');
            force_download(str_replace(' ', '-', $this->main_resource_data['title']) . '.pdf', read_file($render_result['files'][0]));
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
        $data['chapter_title'] = $resource_data['chapter_title'];
        $data['degree_id'] = $resource_data['degree_id'];

        if (!$this->xelatex_sheet_model->init_content($data)) {
            $value['status'] = FALSE;
            $value['render'] = implode('', $this->xelatex_sheet_model->error_msg());
            return $value;
        }
        $render_result = $this->xelatex_sheet_model->render_pdf();
        $this->load->helper('download');
        $this->load->helper('file');
        $path_parts = pathinfo($render_result['files']['0']);
        $download_filename = $resource_data['title'] . '.' . $path_parts['extension'];
        force_download($download_filename, read_file($render_result['files']['0']));
    }

    public function play_dycontent() {
        $resource_id = $this->play_resource_model->get_resource_id();

        $this->db->where('r_resource_dycontent.resource_id', $resource_id);
        $q1 = $this->db->get('r_resource_dycontent');
        $dycontent_data = $this->decode_data($q1->row()->data);

        switch ($q1->row()->render_type_id) {
            case 1: // for Latex
                //[1:เนื้อหา | 2:โจทย์ตัวเลือก | 3:โจทย์เติมคำ | 4:โจทยจับคู่]
                $this->load->model('resource/xelatex_dycontent_model');
                $this->xelatex_dycontent_model->init_content($dycontent_data);
                $render_result = $this->xelatex_dycontent_model->render();
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

    public function bk_play_sheet() {
        $resource_id = $this->play_resource_model->get_resource_id();
        $this->load->model('resource/xelatex_sheet_model');
        $this->load->model('resource/sheet_model');
        $resource_data = $this->sheet_model->get_resource_data($resource_id);
        $resource_data['data']['resources'] = $resource_data['data']['sheet_set'];
        $data['explanation'] = $resource_data['explanation'];
        $data['resources'] = $resource_data['data']['sheet_set'];

        if (!$this->xelatex_sheet_model->init_content($data)) {
            $value['status'] = FALSE;
            $value['render'] = implode('', $this->xelatex_sheet_model->error_msg());
            return $value;
        }
        $render_result = $this->xelatex_sheet_model->render();
        $value = array(
            'status' => TRUE,
            'error_msg' => ''
        );

        if (isset($render_result['error_msg'])) {
            $value['status'] = FALSE;
            $value['render'] = $render_result['error_msg'];
        } else {
            $data['render_result'] = $render_result;
            $value['render'] = $this->load->view('/xelatex/a4_preview', $data, TRUE);
        }
        print_r($value);
    }

    public function play_sheet() {
        $resource_id = $this->play_resource_model->get_resource_id();
        $this->load->model('resource/xelatex_sheet_model');
        $this->load->model('resource/sheet_model');
        $resource_data = $this->sheet_model->get_resource_data($resource_id);
        $resource_data['data']['resources'] = $resource_data['data']['sheet_set'];
        $data['explanation'] = $resource_data['explanation'];
        $data['resources'] = $resource_data['data']['sheet_set'];
        $data['title'] = $resource_data['title'];
        $data['la_id'] = $resource_data['la_id'];
        $data['subj_id'] = $resource_data['subj_id'];
        $data['chapter_title'] = $resource_data['chapter_title'];
        $data['degree_id'] = $resource_data['degree_id'];


        $this->db->where('r_resource_sheet.resource_id', $resource_id);
        $q1 = $this->db->get('r_resource_sheet');


        switch ($q1->row()->render_type_id) {
            case 1: // for Latex
                //[1:เนื้อหา | 2:โจทย์ตัวเลือก | 3:โจทย์เติมคำ | 4:โจทยจับคู่]
                $this->load->model('resource/xelatex_dycontent_model');

                if (!$this->xelatex_sheet_model->init_content($data)) {
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
        echo 'under_construction';
    }

    function decode_data($data) {

        return json_decode($data, TRUE);
    }

}