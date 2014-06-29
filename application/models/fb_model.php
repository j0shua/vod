<?php

/**
 * Facebook Connectoion
 * ระบบเชื่อมต่อ fb
 * @author lojorider  <lojorider@gmail.com>
 * @property MY_Email $email
 */
class fb_model extends CI_Model {

    var $start_money = 26;
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
    private $is_init_fb = FALSE;

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
        /* ถ้าได้รับ code ให้ทำการ set AccessToken เพื่อสามารถเข้าใช้งานได้
         * ถ้าไม่ได้รับก็ไม่สามารุทำอะไรได้เลย
         */
    }

    function init_fb() {
        if (!$this->is_init_fb) {
            if ($this->input->get('code')) {
                $token_url = "https://graph.facebook.com/oauth/access_token?"
                        . "client_id=" . $this->facebook_appId . "&redirect_uri=" . urlencode($this->redirect_uri)
                        . "&client_secret=" . $this->facebook_secret . "&code=" . $this->input->get('code');
                $response = file_get_contents($token_url);
                $params = null;
                parse_str($response, $params);
                $this->facebook->setAccessToken($params['access_token']);

                //เมื่อได้ใบผ่านก็สามารถดึง user profile ได้แล้ว
                $this->set_user_profile();
            }
            $this->is_init_fb = TRUE;
        }
    }

// FACEBOOK SECTION ====================================================================

    /**
     * หากยังไม่ได้รับ code ก็ให้สร้าง link เพื่อไปขอ code 
     * การขอ code เหมือนกับไปขอการผ่านทาง เมื่อได้ใบผ่านทางแล้วจะไปหน้าไหนของ เว็บก็ได้แล้วแต่เราจะกำหนด
     * @param type $redirec_url
     * @return type
     */
    public function get_call_back_url($redirec_url = '') {
        if ($redirec_url != '') {
            $this->redirect_uri = $redirec_url;
        }
        return $this->facebook->getLoginUrl(array('redirect_uri' => $this->redirect_uri, 'scope' => $this->facebook_permissions,));
    }

    /**
     * เมื่อได้ใบผ่านทางมาแล้วก็สามารถดึงข้อมูล user ของ facebook ได้แล้ว
     */
    private function set_user_profile() {
        $this->user_profile = $this->facebook->api('/' . $this->facebook->getUser(), 'GET');
    }

    function get_user_profile() {
        return $this->user_profile;
    }

    /**
     * เมื่อสร้าง
     * @param type $facebook_user_id
     */
    public function auto_login($facebook_user_id = '') {
        if ($facebook_user_id == '') {
            $facebook_user_id = $this->user_profile['id'];
        }

        $this->auth->fb_login($facebook_user_id);
    }

    function is_register() {
        $this->init_fb();

        $this->db->where('facebook_user_id', $this->user_profile['id']);
        if ($this->db->count_all_results('u_user') > 0) {
            return TRUE;
        }
        return FALSE;
    }

    public function get_facebook_appId() {
        return $this->facebook_appId;
    }

    function register() {
        $user_profile = $this->get_user_profile();
        $this->db->where('email', $user_profile['email']);
        if ($this->db->count_all_results('u_user') > 0) {
            $this->connect();
        } else {


            $this->db->trans_start();

            $data['rid'] = 2;
            $data['password'] = rand(111111, 999999);
            $active = 1;
            $data['email'] = $user_profile['email'];
            $data['first_name'] = $user_profile['first_name'];
            $data['last_name'] = $user_profile['last_name'];
            $data['sex'] = ($user_profile['gender'] == 'male') ? 'ชาย' : 'หญิง';
// u_user
            $this->db->set('rid', $data['rid']);
            $this->db->set('password', $this->auth->encode_password($data['password']));
            $this->db->set('email', $data['email']);
            $this->db->set('active', $active);
            $this->db->set('register_time', $this->time);
            $this->db->set('facebook_user_id', $user_profile['id']);
            $this->db->set('facebook_email', $user_profile['email']);
            $this->db->insert('u_user');
            $uid = $this->db->insert_id();

// u_user_detail
            $this->db->set('rid', $data['rid']);
            $this->db->set('uid', $uid);
            $this->db->set('first_name', $data['first_name']);
            $this->db->set('last_name', $data['last_name']);
            $this->db->set('full_name', $data['first_name'] . ' ' . $data['last_name']);

            $this->db->set('sex', $data['sex']);
            $this->db->insert('u_user_detail');
//update u_user
            $this->db->set('personal_dir', $this->auth->make_personal_dir($uid));
            $this->db->where('uid', $uid);
            $this->db->update('u_user');
            $this->db->trans_complete();
//set money
            $this->db->set('uid', $uid);
            $this->db->set('money_bonus', $this->start_money);
            $this->db->set('update_time', time());
            $this->db->insert('u_user_credit');

            return TRUE;
        }
    }

    function connect() {
        $user_profile = $this->get_user_profile();
        if ($this->auth->is_login()) {
            $this->db->where('facebook_user_id', 0);
            $this->db->where('uid', $this->auth->uid());
            $q = $this->db->get('u_user');
            if ($q->num_rows() > 0) {
                $this->db->set('facebook_user_id', $user_profile['id']);
                $this->db->set('facebook_email', $user_profile['email']);
                //$this->db->set('money_bonus', 'money_bonus+' . $this->start_money, FALSE);
                $this->db->where('uid', $this->auth->uid());
                $this->db->update('u_user');
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            $this->db->where('facebook_user_id', 0);
            $this->db->where('email', $user_profile['email']);
            $q = $this->db->get('u_user');
            if ($q->num_rows() > 0) {
                $this->db->set('facebook_user_id', $user_profile['id']);
                $this->db->set('facebook_email', $user_profile['email']);
                $this->db->set('money_bonus', 'money_bonus+' . $this->start_money, FALSE);
                $this->db->where('email', $user_profile['email']);
                $this->db->update('u_user');
                return TRUE;
            } else {
                return FALSE;
            }
        }
        return FALSE;
    }

    public function post($params) {
        $uid = $this->auth->uid();
        $this->init_fb();
        try {
            $this->facebook->api("/" . $this->facebook->getUser() . "/feed", "POST", $params);
            $money = 50;
            $this->db->where('uid', $uid);
            $this->db->where('act_type', 2);
            $q = $this->db->get('b_act_bonus');
            if ($q->num_rows() > 0) {
                return FALSE;
            } else {
                $this->db->set('uid', $uid);
                $this->db->set('act_type', 2);
                $this->db->set('money_bonus', $money);
                $this->db->set('create_time', $this->time);
                $this->db->insert('b_act_bonus');


                $this->db->set('money_bonus', 'money_bonus+' . $money, FALSE);
                $this->db->set('update_time', $this->time);
                $this->db->where('uid', $uid);
                $this->db->update('u_user_credit');
                return TRUE;
            }
        } catch (FacebookApiException $e) {
            echo $e;
            return FALSE;
        }
    }

    public function like($uid) {
        $money = 30;
        $this->db->where('uid', $uid);
        $this->db->where('act_type', 1);
        $q = $this->db->get('b_act_bonus');
        if ($q->num_rows() > 0) {
            return FALSE;
        } else {
            $this->db->set('uid', $uid);
            $this->db->set('act_type', 1);
            $this->db->set('money_bonus', $money);
            $this->db->set('create_time', $this->time);
            $this->db->insert('b_act_bonus');


            $this->db->set('money_bonus', 'money_bonus+' . $money, FALSE);
            $this->db->set('update_time', $this->time);
            $this->db->where('uid', $uid);
            $this->db->update('u_user_credit');
            return TRUE;
        }
    }

    public function count_friend($id = '') {
        if ($id == '') {
            $this->init_fb();
            $result = $this->facebook->api("/me/friends");
            return count($result['data']);
        } else {
            $result = $this->facebook->api($id . "/friends");
            return count($result['data']);
        }
    }

    public function get_friend($id = '') {
        if ($id == '') {
            $this->init_fb();
            $result = $this->facebook->api("/me/friends");
            return $result['data'];
        } else {
            $result = $this->facebook->api($id . "/");
            return $result;
        }
    }

    public function me() {
        $this->init_fb();
        return $this->facebook->api("/me");
    }

    public function fql($fql) {
//        $this->init_fb();
//        return $this->facebook->api("/me/friends");
        $fql = urlencode($fql);
        return $this->facebook->api(
                        '/fql?q=' . $fql
        );
    }

}