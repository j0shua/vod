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

    /**
     * login ผ่าน facebook
     */
    function login() {
        if (!$this->input->get('code')) {
            echo("<script> top.location.href='" . $this->fb_model->get_call_back_url() . "'</script>");
        } else {
            if ($this->fb_model->is_register()) {
                $this->fb_model->auto_login();
                $data = array(
                    'time' => 0,
                    'url' => site_url(),
                    'heading' => 'เข้าสู่ระบบผ่าน Facebook',
                    'message' => '<p>เข้าระบบทันที</p>'
                );
                $this->load->view('refresh_page', $data);
            } else {

                $custom_fields = array(
                    array('name' => 'name'),
                    array('name' => 'email'),
                    array(
                        'name' => 'username',
                        'description' => 'ชื่อผู้ใช้',
                        'type' => 'text',
                        'onvalidate' => 'validate_async'
                    ),
                    array('name' => 'password',
                        'description' => 'รหัสผ่าน'
                    )
                );
                $data['custom_fields_json'] = json_encode($custom_fields);
                $data['facebook_appId'] = $this->fb_model->get_facebook_appId();
                $data['redirect_uri'] = site_url('fb/register');
                $this->template->write_view('fb/reg_form', $data);
                $this->template->render();
            }
        }
    }

    function vlogin($resource_id) {
        if (!$this->input->get('code')) {
            echo("<script> top.location.href='" . $this->fb_model->get_call_back_url() . "'</script>");
        } else {
            if ($this->fb_model->is_register()) {
                $this->fb_model->auto_login();
                $data = array(
                    'time' => 0,
                    'url' => site_url('v/' . $resource_id),
                    'heading' => 'เข้าสู่ระบบผ่าน Facebook',
                    'message' => '<p>เข้าระบบทันที</p>'
                );
                $this->load->view('refresh_page', $data);
            } else {

                $custom_fields = array(
                    array('name' => 'name'),
                    array('name' => 'email'),
                    array(
                        'name' => 'username',
                        'description' => 'ชื่อผู้ใช้',
                        'type' => 'text',
                        'onvalidate' => 'validate_async'
                    ),
                    array('name' => 'password',
                        'description' => 'รหัสผ่าน'
                    )
                );
                $data['custom_fields_json'] = json_encode($custom_fields);
                $data['facebook_appId'] = $this->fb_model->get_facebook_appId();
                $data['redirect_uri'] = site_url('fb/vregister/' . $resource_id);
                $this->template->write_view('fb/reg_form', $data);
                $this->template->render();
            }
        }
    }

    function register() {
        $this->fb_model->register();
        $data = array(
            'time' => 4,
            'url' => site_url(),
            'heading' => 'เข้าสู่ระบบผ่าน Facebook',
            'message' => '<p>กรอกข้อมูลเพียงเล็กน้อยเสร็จแล้ว เข้าสู่ระบบได้ทันที !</p>'
        );
        $this->load->view('refresh_page', $data);
    }

    function vregister($resource_id) {
        $this->fb_model->register();
        $data = array(
            'time' => 4,
            'url' => site_url('v/' . $resource_id),
            'heading' => 'เข้าสู่ระบบผ่าน Facebook',
            'message' => '<p>กรอกข้อมูลเพียงเล็กน้อยเสร็จแล้ว เข้าสู่ระบบได้ทันที !</p>'
        );
        $this->load->view('refresh_page', $data);
    }

    function ajax_check_username() {
        echo json_encode($this->fb_model->check_username($this->input->post()));
    }

    function post() {
        if (!$this->input->get('code')) {
            echo("<script> top.location.href='" . $this->fb_model->get_call_back_url() . "'</script>");
        } else {
            $params = array(
                'message' => "",
                'name' => "educasy.com",
                'caption' => "educasy",
                'description' => "ชวนมาเรียนกันนะ...",
                'link' => "http://www.educasy.com",
            );
//            $params = array(
//                'message' => "ทดสอบ POST BY NT",
//                'name' => "educasy.com",
//                'caption' => "educasy",
//                'description' => "ชวนมาเรียนกันนะ...",
//                'link' => "http://www.educasy.com",
//                'picture' => "http://i.imgur.com/VUBz8.png",
//            );
            $post = $this->fb_model->post($params);
            if ($post) {
                $data = array(
                    'time' => 5,
                    'url' => site_url(),
                    'heading' => 'เรามีความยินดี !',
                    'message' => '<p>โอ้คุณได้ช่วยเราแล้ว ให้เราตอบแทนคุณ หน่อยนะครับ ลองเข้าไปเช็คใน บัญชีของคุณหน่อยก็ดี</p>'
                );
                $this->load->view('refresh_page', $data);
            } else {
                $data = array(
                    'time' => 5,
                    'url' => site_url(),
                    'heading' => 'คุณได้ปฏิเสธการโพสแนะนำ',
                    'message' => '<p>คุณยังสามารถกัลบมาอีกที</p>'
                );
                $this->load->view('refresh_page', $data);
            }
        }
    }

    function check_login() {
        $data['fb_appId'] = $this->config->item('fb_appId');
        //$data = array();
        $this->template->write_view('fb/check_login', $data);
        $this->template->render();
    }

    function test() {
        if (!$this->input->get('code')) {
            if($this->input->get('error')){
                print_r($this->input->get());
            }else{
                  echo("<script> top.location.href='" . $this->fb_model->get_call_back_url() . "'</script>");
            }
            //echo $this->input->get('code');
          
        } else {
            print_r($this->fb_model->getSignedRequest());
            print_r($this->fb_model->test());
        }
    }

}
