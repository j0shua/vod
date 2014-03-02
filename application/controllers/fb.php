<?php

/**
 * @author lojorider  <lojorider@gmail.com>
 * @property fb_model $fb_model
 */
class fb extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('fb_model');
        $this->load->model('affiliate_model');
    }



    function connect() {
        if (!$this->input->get('code')) {
            echo("<script> top.location.href='" . $this->fb_model->get_call_back_url() . "'</script>");
        } else {
//            $this->fb_model->init_fb();
//            $u = $this->fb_model->get_user_profile();
//            
//            print_r($u);
//            echo 'fdf';
//            exit();
            if ($this->fb_model->is_register()) {
                if ($this->auth->is_login()) {
                    $data = array(
                        'time' => 30,
                        'url' => site_url(),
                        'heading' => 'บัญชี facebook ถูกลงทะเบียนไปแล้ว',
                        'message' => '<p>บัญชี facebook ที่ท่านใช้อยู่ได้เชื่อมต่อกับบัญชีของเว็บนี้อยู่แล้ว</p><p>โปรดลงชื่อเข้าใช้ facebook ในบัญชีอื่น</p>'
                    );
                    $this->load->view('refresh_page', $data);
                } else {
                    $this->fb_model->auto_login();
                    $data = array(
                        'time' => 0,
                        'url' => site_url(),
                        'heading' => 'เข้าสู่ระบบผ่าน Facebook',
                        'message' => '<p>เข้าระบบทันที</p>'
                    );
                    $this->load->view('refresh_page', $data);
                }
            } else {
                if ($this->auth->is_login()) {
                    $this->fb_model->connect();
                } else {

                    $this->fb_model->register();
                }
                $this->fb_model->auto_login();
                $data = array(
                    'time' => 0,
                    'url' => site_url(),
                    'heading' => 'เข้าสู่ระบบผ่าน Facebook',
                    'message' => '<p>เข้าระบบทันที.</p>'
                );
                $this->load->view('refresh_page', $data);
            }
        }
    }

    function share() {
        if ($this->auth->is_login()) {
            if (!$this->input->get('code')) {

                echo("<script> top.location.href='" . $this->fb_model->get_call_back_url() . "'</script>");
            } else {
                
                if ($this->fb_model->count_friend() > 20) {
                    $data['title'] = 'แชร์เว็บนี้ให้เพื่อน ๆ';
                    $data['form_action'] = site_url('fb/do_share');
                    $this->template->write_view('fb/share_form', $data);
                    $this->template->render();
                } else {
                    $data = array(
                        'time' => 10,
                        'url' => site_url(),
                        'heading' => 'ท่านมีเพื่อนน้อยมาก',
                        'message' => '<p>หาเพื่อให้เยอะๆ แล้วแชร์ ใหม่นะครับ</p>'
                    );
                    $this->load->view('refresh_page', $data);
                }
            }
        } else {
            $data = array(
                'time' => 10,
                'url' => site_url(''),
                'heading' => 'ลงชื่อเข้าใช้โดย facebook ก่อน',
                'message' => '<p>ลงชื่อเข้าใช้โดย facebook ก่อนการ share นะครับ</p>'
            );
            $this->load->view('refresh_page', $data);
        }
    }

    function do_share() {
        if (!$this->input->get('code')) {
            $this->session->set_flashdata('fb_share_message', $_POST['message']);
            echo("<script> top.location.href='" . $this->fb_model->get_call_back_url() . "'</script>");
        } else {
            $params = array(
                'message' => $this->session->flashdata('fb_share_message'),
                'name' => "www.educasy.com",
                'caption' => "เรียนออนไลน์ผ่านอินเตอร์เน็ต",
                'description' => "ติวออนไลน์ หลากหลายวิชา เพียงชั่วโมงละ 26 บาททุกบท ทุกตอน น้องๆ ไม่ต้องออกเดินทางไปเรียนพิเศษอีกต่อไป",
                'link' => base_url()
            );

            $post = $this->fb_model->post($params);
            if ($post) {
                $data = array(
                    'time' => 15,
                    'url' => site_url(),
                    'heading' => 'เรามีความยินดี !',
                    'message' => '<p>โอ้คุณได้ช่วยเราแล้ว ให้เราตอบแทนคุณ หน่อยนะครับ ลองเข้าไปเช็คใน บัญชีของคุณหน่อยก็ดี</p>
<p>มันอาจเพิ่มขึ้นอีก 50 บาทนะครับ</p>                        
'
                );
                $this->load->view('refresh_page', $data);
            } else {
                $data = array(
                    'time' => 15,
                    'url' => site_url(),
                    'heading' => 'ขอบคุณที่ได้มีส่วนร่วมในการแชร์ครั้งนี้',
                    'message' => '<p>ท่านยังสามารถกลับมาอีกที</p>'
                );
                $this->load->view('refresh_page', $data);
            }
        }
    }

    function like() {
        $result = $this->fb_model->like($_POST['uid']);
        if ($result) {
            $a = array(
                'success' => $result,
                'message' => 'คุณได้รับเงินโบนัสในบัญชี 30 บาท'
            );
        } else {
            $a = array(
                'success' => $result,
                'message' => 'คุณได้รับเงินโบนัสในบัญชี 30 บาทไปแล้ว'
            );
        }

        echo json_encode($a);
    }

    function friend_count() {

 
        if (!$this->input->get('code')) {
            echo("<script> top.location.href='" . $this->fb_model->get_call_back_url() . "'</script>");
        } else {
            $result = $this->fb_model->count_friend();

            print_r($result);
            if (count($result) > 10) {
                // proceed
            } else {
                // stop
            }
        }
    }

    function me() {
        if (!$this->input->get('code')) {
            echo("<script> top.location.href='" . $this->fb_model->get_call_back_url() . "'</script>");
        } else {
            $result = $this->fb_model->me();

            
            if (count($result) > 10) {
                // proceed
            } else {
                // stop
            }
        }
    }

    function test() {
//        $this->template->load_jquery_fancybox();
//        $app_id = $this->fb_model->get_facebook_appId();
//        $href = 'https://www.facebook.com/dialog/feed?%20%20app_id=546507505434241%20%20&display=popup&caption=An%20example%20caption%20%20%20&link=http%3A%2F%2Fwww.educasy.com%2F%20%20&redirect_uri=http://www.educasy.com/u/9';
//        $a = anchor($href, 'share');
//        $data['link'] = $href;
// 
//        $this->template->write_view('fb/share_dialog', $data);
//        $this->template->render();
        //https://www.facebook.com/100006949063650
         $result[0] = $this->fb_model->get_friend("virapong.supachai");
         $result[1] = $this->fb_model->get_friend("lojorider");
         print_r($result);
    }

    function share_link() {
        $href = 'https://www.facebook.com/dialog/feed?%20%20app_id=546507505434241%20%20&display=popup&caption=An%20example%20caption%20%20%20&link=http%3A%2F%2Fwww.educasy.com%2F%20%20&redirect_uri=http://www.educasy.com/u/9';
        $a = anchor($href, 'share');
        $data['link'] = $href;
        $this->template->write_view('fb/share_dialog', $data);
        $this->template->render();
        
    }
 

}
