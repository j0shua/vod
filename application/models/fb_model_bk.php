<?php

/**
 * Facebook Connectoion
 * ระบบเชื่อมต่อ facebook
 * @author lojorider  <lojorider@gmail.com>
 * @property MY_Email $email
 */
class fb_model extends CI_Model {

    var $start_money = 0;
    var $start_time_credit = 6000;

    /**
     * ตัวแปรต่าง สำหรับ facebook lib
     * @var 
     */
    private $facebook_appId;
    private $facebook_secret;
    private $facebook; // facebook object
    private $redirect_uri;
    private $facebook_permissions;

    /**
     * ตัวแปรจำหรับ class นี้
     * @var
     */
    private $time;
    private $user_profile;

    /**
     * ตั้งค่าเริ่มต้น
     */
    public function __construct() {
        parent::__construct();
        require APPPATH . 'third_party/facebook/facebook.php';
        $this->redirect_uri = site_url($this->uri->uri_string());
        $this->facebook_appId = $this->config->item('facebook_appId');
        $this->facebook_secret = $this->config->item('facebook_secret');
        $this->facebook_permissions = $this->config->item('facebook_permissions');
        $this->facebook = new Facebook(array(
                    'appId' => $this->facebook_appId,
                    'secret' => $this->facebook_secret,
                    'cookie' => true
                ));
        $this->time = time();
        if ($this->input->get('code')) {
            $token_url = "https://graph.facebook.com/oauth/access_token?"
                    . "client_id=" . $this->facebook_appId . "&redirect_uri=" . urlencode($this->redirect_uri)
                    . "&client_secret=" . $this->facebook_secret . "&code=" . $this->input->get('code');
            $response = file_get_contents($token_url);
            $params = null;
            parse_str($response, $params);
            $this->facebook->setAccessToken($params['access_token']);
        }
    }
function getAccessToken(){
    return $this->facebook->getAccessToken();
}
function getSignedRequest(){
     $this->facebook->getSignedRequest();
    
}
// FACEBOOK SECTION ====================================================================
    /*
     * เรียกดู url ของ การ login
     */
    public function get_call_back_url() {
        return $this->facebook->getLoginUrl(array('redirect_uri' => $this->redirect_uri, 'scope' => $this->facebook_permissions,));
    }

    public function post($params) {
        try {
            $this->facebook->api("/" . $this->facebook->getUser() . "/feed", "POST", $params);
            return TRUE;
        } catch (FacebookApiException $e) {
            return FALSE;
        }
    }

    private function init_user_profile() {
        $this->user_profile = $this->facebook->api('/' . $this->facebook->getUser(), 'GET');
    }

    public function auto_login($facebook_user_id = '') {
        if ($facebook_user_id == '') {
            $facebook_user_id = $this->user_profile['id'];
        }
        $this->auth->fblogin($facebook_user_id);
    }

    public function get_facebook_appId() {
        return $this->facebook_appId;
    }

    function register($auto_login = TRUE) {
        $signed_request = $this->facebook->getSignedRequest();
        $registration = $signed_request['registration'];
        if (!isset($signed_request['user_id'])) {
            return FALSE;
        } else {
            $facebook_user_id = $signed_request['user_id'];
            // $facebook_access_token = $signed_request['oauth_token'];
        }
        $this->db->trans_start();
        $this->db->set('active', 1);
        $this->db->set('rid', 2);
        $this->db->set('register_time', $this->time);
        $this->db->set('username', $registration['username']);
        $this->db->set('email', $registration['email']);
        $this->db->set('facebook_email', $registration['email']);
        $this->db->set('password', $this->auth->encode_password($registration['password']));
        $this->db->set('facebook_user_id', $facebook_user_id);
        $this->db->insert('u_user');
        $uid = $this->db->insert_id();
        $this->db->set('uid', $uid);
        if (!isset($registration['first_name'])) {
            $name = $registration['name'];
            $name_tmp = $name = explode(' ', $name);
            foreach ($name_tmp as $k => $v) {
                if ($v == '') {
                    unset($name[$k]);
                }
            }
            $registration['first_name'] = $name[0];
            $registration['last_name'] = '';
            if (count($name) > 1) {
                $registration['last_name'] = $name[1];
            }
        }
        // set เงิน
        $this->db->set('first_name', $registration['first_name']);
        $this->db->set('last_name', $registration['last_name']);
        $this->db->set('uid_affiliate', $this->affiliate_model->get_uid_affiliate());
        $this->db->insert('u_user_detail');
        $this->db->set('uid', $uid);
        $this->db->set('update_time', $this->time);
        $this->db->set('money', $this->start_money);
        $this->db->set('time_credit', $this->start_time_credit);
        $this->db->insert('u_user_credit');
        $this->db->trans_complete();
        $email_data = array('username' => $registration['username'], 'password' => $registration['password']);
        $this->send_email_on_register($registration['email'], $email_data);
        if ($auto_login) {
            $this->auto_login($facebook_user_id);
        }
        return TRUE;
    }

//
//    function is_email_exists() {
//        $this->init_user_profile();
//        $this->db->where('email', $this->user_profile['email']);
//        $q1 = $this->db->get('u_user');
//        if ($q1->num_rows() > 0) {
//            $row = $q1->row_array();
//            if ($row['facebook_user_id'] == 0) { // ตรวจพบ email แต่ ยังไม่มีข้อมูล facebook_id
//                $this->db->set('facebook_user_id', $this->user_profile['id']);
//                $this->db->where('email', $this->user_profile['emai']);
//                $this->db->update('u_user');
//            }
//            return TRUE;
//        }
//        return FALSE;
//    }

    function is_register() {
        $this->init_user_profile();
        $this->db->where('facebook_user_id', $this->user_profile['id']);
        if ($this->db->count_all_results('u_user') > 0) {
            return TRUE;
        } else {
            $this->db->where('email', $this->user_profile['email']);
            if ($this->db->count_all_results('u_user') > 0) {
                $this->db->set('facebook_user_id', $this->user_profile['id']);
                $this->db->set('facebook_email', $this->user_profile['email']);
                $this->db->where('email', $this->user_profile['email']);
                $this->db->update('u_user');
                return TRUE;
            }
            return FALSE;
        }
    }

    function send_email_on_register($user_email, $data) {
        $this->load->library('email');
        $this->email->to($user_email);

        $this->email->subject('แจ้งการลงทะเบียน ผ่าน Facebook');
        $data['site_name'] = $this->config->item('site_name');
        $data['site_email'] = $this->config->item('site_email');
        $this->email->message_view('fb/_email_on_register', $data);
        $this->email->send();
    }

    function check_username($data) {
        $username = $data['username'];
        $value['can_use'] = TRUE;
        strpos($username, ' ');
        if (!(strpos($username, ' ') === false)) {
            $value['can_use'] = FALSE;
            $value['msg'] = "ไม่สามารถมีค่าว่างได้";
            return $value;
        }

        if ($username == '') {
            $value['can_use'] = FALSE;
            $value['msg'] = "โปรดกรอกข้อมูล";
            return $value;
        }

        if (!preg_match('/^[a-z0-9_]+$/i', $username)) {
            $value['can_use'] = FALSE;
            $value['msg'] = "โปรดกรอกเฉพาะ a-z และ 0-9 เคุณั้น";
            return $value;
        }
        if (strlen($username) < 4) {
            $value['can_use'] = FALSE;
            $value['msg'] = "ต้องมีจำนวน 4 ตัวอักษรขึ้นไป";
            return $value;
        }
        $this->db->where('username', $username);
        $this->db->from('u_user');
        if ($this->db->count_all_results() > 0) {
            $value['can_use'] = FALSE;
            $value['msg'] = "username ซ้ำ";
        } else {
            $value['msg'] = 'OK';
        }
        return $value;
    }
    function test(){
       return  $this->facebook->api('/' . $this->facebook->getUser(), 'GET');
    }

}