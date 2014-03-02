<?php

/**
 * Description of user_model
 * สำหรับ user ใช้ เช่น ลงทะเบียน การแก้ไขทะเบียน การแก้ password
 * @author lojoriderget_account_data
 */
class user_model extends CI_Model {

    var $CI;
    private $form_error = array();
    private $form_data = array(
        'username' => '',
        'email' => '',
        'email_confirm' => '',
        'first_name' => '',
        'last_name' => '',
        'sex' => '',
        'birthday' => '',
        'province_id' => '',
        'school_name' => '',
        'degree_id' => '',
        'phone_number' => '',
        'coupon_code' => ''
    );
    private $time;
    private $real_email = TRUE; //set not check email on internet
    var $password_length = array('min' => 4, 'max' => 16);
    var $personal_document_dir = '';
    var $username_field;
    var $is_parent_site;
    var $standalone_site;
    var $site_id;

    public function __construct() {
        parent::__construct();
        $this->CI = & get_instance();

        $this->time = time();
        $this->personal_document_dir = $this->config->item('personal_document_dir');
        $this->username_field = $this->config->item('username_field');
        $this->is_parent_site = $this->config->item('is_parent_site');
        $this->standalone_site = $this->config->item('standalone_site');
        $this->site_id = $this->config->item('site_id');
        if (!$this->is_parent_site && !$this->standalone_site) {
            $this->db_parent = $this->load->database('parent', TRUE);
            $this->db->close();
        }
    }

    /**
     * ลงทะเบียน
     * @return type
     */
    public function register($data) {

        $active = 1;
        $bvalid = TRUE;

// Check Form data
        if ($this->username_field == 'username') {
            $data['username'] = trim($data['username']);
            if ($this->check_username($data['username'])) {
                $bvalid &= TRUE;
            } else {
                $data['username'] = '';
                $bvalid &= FALSE;
            }
        }
        if ($this->check_email($data['email'])) {
            $bvalid &= TRUE;
        } else {
            $data['email'] = '';
            $bvalid &= FALSE;
        }

        if ($this->check_password($data['password'], $data['password_confirm'])) {
            $bvalid &= TRUE;
        } else {
            $bvalid &= FALSE;
        }

        if ($this->check_first_name($data['first_name'])) {
            $bvalid &= TRUE;
        } else {
            $data['first_name'] = '';
            $bvalid &= FALSE;
        }
        if ($this->check_last_name($data['last_name'])) {
            $bvalid &= TRUE;
        } else {
            $data['last_name'] = '';
            $bvalid &= FALSE;
        }
        if ($this->check_sex($data['sex'])) {
            $bvalid &= TRUE;
        } else {
            $data['sex'] = '';
            $bvalid &= FALSE;
        }
        if ($this->check_birthday($data['birthday'])) {
            $bvalid &= TRUE;
        } else {
            $data['birthday'] = '';
            $bvalid &= FALSE;
        }
        if ($this->check_province($data['province_id'])) {
            $bvalid &= TRUE;
        } else {
            $data['province_id'] = '';
            $bvalid &= FALSE;
        }
        if ($this->check_school_name($data['school_name'])) {
            $bvalid &= TRUE;
        } else {
            $data['school_name'] = '';
            $bvalid &= FALSE;
        }
        if ($data['rid'] != 3) {
            if ($this->check_degree($data['degree_id'], $data['rid'])) {
                $bvalid &= TRUE;
            } else {
                $data['degree_id'] = '';
                $bvalid &= FALSE;
            }
        } else {
            $active = 2; //สำหรับ server จริง
            //$active = 1; // สำหรับ server อบรม
        }
        if ($this->check_phone_number($data['phone_number'])) {
            $bvalid &= TRUE;
        } else {
            $data['phone_number'] = '';
            $bvalid &= FALSE;
        }
        //$bvalid = TRUE;
// return ค่า   
//        echo $data[$this->username_field];
//        print_r($data);
//        exit();
        if ($bvalid) {
            $this->db->trans_start();
            // u_user
            if ($this->username_field == 'username') {
                $this->db->set('username', $data['username']);
            }
            $this->db->set('rid', $data['rid']);
            $this->db->set('password', $this->auth->encode_password($data['password']));
            $this->db->set('email', $data['email']);
            $this->db->set('active', $active);
            $this->db->set('register_time', $this->time);
            $this->db->insert('u_user');
            $uid = $this->db->insert_id();

            // u_user_detail
            switch ($data['rid']) {
                case 2://นักเรียน
                    $data['personal_document'] = $this->save_student_personal_document($uid);
                    break;
                case 3://ครู
                    $data['personal_document'] = $this->save_teacher_personal_document($uid);
                    break;
                default:
                    break;
            }
            $this->db->set('rid', $data['rid']);
            $this->db->set('uid', $uid);

            $this->db->set('first_name', $data['first_name']);
            $this->db->set('last_name', $data['last_name']);
            $this->db->set('full_name', $data['first_name'] . ' ' . $data['last_name']);
            $this->db->set('sex', $data['sex']);
            $this->db->set('birthday', $data['birthday']);
            $this->db->set('province_id', $data['province_id']);
            $this->db->set('school_name', $data['school_name']);
            $this->db->set('degree_id', $data['degree_id']);
            $this->db->set('phone_number', $data['phone_number']);
            $this->db->set('personal_document', $data['personal_document']);
            $this->db->insert('u_user_detail');
            //update u_user
            $this->db->set('personal_dir', $this->auth->make_personal_dir($uid));
            $this->db->where('uid', $uid);
            $this->db->update('u_user');
            //set money
            $this->db->set('uid', $uid);
            $this->db->set('money', 0);
            $this->db->set('money_bonus', 0);
            $this->db->set('update_time', time());
            $this->db->insert('u_user_credit');
            if (!$this->standalone_site) {
                if ($this->is_parent_site) {
                    $this->db->set('uid_site', $uid);
                    $this->db->set('site_id', $this->site_id);
                    $this->db->set('username', $data[$this->username_field]);
                    $this->db->insert('u_username_main');
                } else {
                    $this->db_parent->close();
                    $this->db_parent->where('username', $data[$this->username_field]);
                    if ($this->db_parent->count_all_results('u_username_main') == 0) {
                        $this->db_parent->set('uid_site', $uid);
                        $this->db_parent->set('site_id', $this->site_id);
                        $this->db_parent->set('username', $data[$this->username_field]);
                        $this->db_parent->insert('u_username_main');
                        $this->db->close();
                    } else {
                        $this->db_parent->set('uid_site', $uid);
                        $this->db_parent->set('site_id', $this->site_id);
                        $this->db_parent->where('username', $data[$this->username_field]);
                        $this->db_parent->update('u_username_main');
                        $this->db->close();
                    }
                }
            }

            $this->db->trans_complete();
            //use coupon
            if (isset($data['coupon_code'])) {
                $this->use_coupon($uid, $data['coupon_code'], 'register');
            }

            $email_data = array(
                'email' => $data['email'],
                'password' => $data['password']
            );
            $this->send_email_register($data['email'], $email_data);
            return TRUE;
        } else {
            $form_data = array(
                'email' => $data['email'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'sex' => $data['sex'],
                'birthday' => $data['birthday'],
                'province_id' => $data['province_id'],
                'school_name' => $data['school_name'],
                'coupon_code' => $data['coupon_code'],
                'degree_id' => $data['degree_id'],
                'phone_number' => $data['phone_number'],
                'coupon_code' => ''
            );
            if ($this->username_field == 'username') {
                $form_data['username'] = $data['username'];
            }
            $this->session->set_flashdata('form_error', $this->get_form_error());
            $this->session->set_flashdata('form_data', $form_data);
            return FALSE;
        }
    }

    function save_teacher_personal_document($uid) {
        $this->load->library('upload');
        $result = array(
            'identity_document' => '',
            'educational_document' => ''
        );
        $config['upload_path'] = $this->personal_document_dir;
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '10000';
        $config['max_width'] = '102400';
        $config['max_height'] = '768000';
        $config['overwrite'] = TRUE;


        $config['file_name'] = $uid . '_identity_document';
        $this->upload->initialize($config);
        $result1 = $this->upload->do_upload('identity_document');
        $upload_data1 = $this->upload->data();
        $config['file_name'] = $uid . '_educational_document';
        $this->upload->initialize($config);
        $result2 = $this->upload->do_upload('educational_document');
        $upload_data2 = $this->upload->data();
        if ($result1) {
            $result['identity_document'] = $upload_data1['file_name'];
        }
        if ($result2) {
            $result['educational_document'] = $upload_data2['file_name'];
        }

        return json_encode($result);
    }

    function save_student_personal_document() {
        $result = array(
            'identity_document' => '',
            'educational_document' => ''
        );


        return json_encode($result);
    }

    function send_email_register($user_email, $data) {
        if ($this->auth->is_system_host() && $this->real_email) {
            $this->load->library('email');
            $this->email->to($user_email);
            $this->email->subject('ลงทะเบียน ' . $this->setting->get_site_name() . ' เรียบร้อยแล้ว');
            $data['site_name'] = $this->setting->get_site_name();
            $data['site_email'] = $this->setting->get_site_email();
            $this->email->message_view('user/_register_email', $data);
            $this->email->send();
            return TRUE;
        }
        return FALSE;
    }

    function use_coupon($uid, $coupon_code, $use_from) {
        $result = array(
            'success' => FALSE,
            'message' => 'ไม่มีรหัสนี้อยู่ในระบบ'
        );
        //check coupon
        $this->db->where('coupon_code', $coupon_code);
        $this->db->where('active', 1);
        $q_coupon = $this->db->get('b_coupon');
        if ($q_coupon->num_rows() > 0) {
            $r_coupon = $q_coupon->row_array();
            if ($r_coupon['reuse_number'] == 0 || $r_coupon['reuse_number'] > $r_coupon['used_number']) {
                $this->db->where('uid', $uid);
                $this->db->where('cid', $r_coupon['cid']);
                $q_coupon_log = $this->db->get('b_coupon_log');
                if ($q_coupon_log->num_rows() > 0) {
                    $result = array(
                        'success' => FALSE,
                        'message' => 'มีการใช้รหัสนี้ไปแล้ว'
                    );
                } else {
                    $money_bonus = $r_coupon['money_bonus'];
                    $money = $r_coupon['money'];
                    $this->db->set('money_bonus', $money_bonus);
                    $this->db->set('money', $money);
                    $this->db->set('update_time', $this->time);
                    $this->db->where('uid', $uid);
                    $this->db->update('u_user_credit');
                    //update b_coupon 
                    $this->db->set('`used_number`', "`used_number`+1", FALSE);
                    $this->db->where('cid', $r_coupon['cid']);
                    $this->db->update('b_coupon');

                    //insert coupon log
                    $this->db->set('cid', $r_coupon['cid']);
                    $this->db->set('coupon_type', $r_coupon['coupon_type']);
                    $this->db->set('coupon_code', $coupon_code);
                    $this->db->set('use_time', $this->time);
                    $this->db->set('money', $money);
                    $this->db->set('money_bonus', $money_bonus);
                    $this->db->set('use_from', $use_from);
                    $this->db->set('uid', $uid);
                    $this->db->insert('b_coupon_log');
                    $result = array(
                        'success' => TRUE,
                        'message' => 'การเติมเงินเสร็จสิ้น'
                    );
                }
            }
        } else {
            $f_array = array(
                'uid' => $uid,
                'coupon_code_fail' => $coupon_code,
                'use_time_fail' => time(),
                'fail_from' => 'register'
            );
            $this->db->set($f_array);
            $this->db->insert('b_coupon_fail');
        }
        return $result;
    }

    /**
     * check username
     * @param type $username
     * @return boolean
     */
    private function check_username($username) {
        if (!$this->standalone_site) {
            if ($this->is_parent_site) {
                $this->db->close();
                $this->db->where('username', $username);
                $this->db->where('username !=', '');
                if ($this->db->count_all_results('u_username_main') > 0) {
                    $this->set_form_error('ยูสเซอร์เนม ซ้ำ');
                    return FALSE;
                }
            } else {
                $this->db_parent->close();
                $this->db_parent->where('username', $username);
                //$this->db_parent->where('username !=', '');
                if ($this->db_parent->count_all_results('u_username_main') > 0) {
                    $this->set_form_error('ยูสเซอร์เนม ซ้ำ');
                    return FALSE;
                }
            }
        }
        $this->db->close();
        $this->db->where('username', $username);
        $this->db->where('username !=', '');
        if ($this->db->count_all_results('u_user') > 0) {
            $this->set_form_error('ยูสเซอร์เนม ซ้ำ');
            return FALSE;
        }
        $result = strlen($username);
        if ($result == '') {
            $this->set_form_error('ต้องกรอกยูสเซอร์เนม');
            return FALSE;
        }
        if ($result < 4) {
            $this->set_form_error('ยูสเซอร์เนมน้อยกว่า 4 ตัวอักษร');
            return FALSE;
        }
        if ($result > 16) {
            $this->set_form_error('ยูสเซอร์เนมมากกว่า 16 ตัวอักษร');
            return FALSE;
        }
        return TRUE;
    }

    /**
     * เช็ค Email
     * @param type $email
     * @param type $email_confirm
     * @return boolean
     */
    private function check_email($email, $allow_email = '') {
// Check Email Format        
        if (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $email)) {
            $this->set_form_error('Email  ไม่ถูกต้อง');
            return FALSE;
        }
// check email dub
        if ($email != $allow_email) {
            $this->db->where('email', $email);
            $this->db->where('email !=', '');
            if ($this->db->count_all_results('u_user') > 0) {
                $this->set_form_error('Email ซ้ำ');
                return FALSE;
            }
        }

        if (!$this->standalone_site) {
            if ($this->username_field == 'email') {
                $this->db_parent->close();
                $this->db_parent->where('username', $email);
                //$this->db_parent->where('username !=', '');
                $count = $this->db_parent->count_all_results('u_username_main');
                $this->db->close();
                if ($count > 0) {
                    $this->set_form_error('Email ซ้ำ');
                    return FALSE;
                }
            }
        }



// Check Real Email    

        if ($this->auth->is_system_host() && $this->real_email && TRUE) {
            require_once(APPPATH . 'third_party/smtp_validateEmail.class.php');
            $sender = $this->setting->get_alert_email();
            $SMTP_Validator = new SMTP_validateEmail();
            $SMTP_Validator->debug = false;
            $results = $SMTP_Validator->validate(array($email), $sender);

            if (!$results[$email]) {
                $this->email = '';
                $this->set_form_error($email . ' -- Email นี้ไม่สามารถใช้งานได้');
                $this->form_valid = FALSE;
                return FALSE;
            }
        }

        return TRUE;
    }

    /**
     * เช็ค password
     * @param type $password
     * @return boolean
     */
    private function check_password($password, $password_confirm) {

        $result = strlen($password);

        $sanitized_name = preg_replace('/[^ก-๙]/u', '', $password);
        if (trim($sanitized_name) != "") {
            $this->set_form_error('รหัสผ่านต้องไม่เป็นภาษาไทย');
            return FALSE;
        }
        if ($result < $this->password_length['min']) {
            $this->set_form_error('รหัสผ่าน มีตัวอักษรน้อยกว่า ' . $this->password_length['min'] . ' ตัวอักษร');
            return FALSE;
        }
        if ($result > $this->password_length['max']) {
            $this->set_form_error('รหัสผ่าน มีตัวอักษรมากกว่า ' . $this->password_length['max'] . ' ตัวอักษร');

            return FALSE;
        }
        if ($password != $password_confirm) {
            $this->set_form_error('การยืนยัน รหัสผ่านไม่ถูกต้อง');

            return FALSE;
        }


        return TRUE;
    }

    /**
     * เช็คชื่อ
     * @param type $first_name
     * @return boolean
     */
    private function check_first_name($value) {
        $result = strlen($value);
        if ($result < 1) {
            $this->set_form_error('ชื่อน้อยกว่า 1 ตัวอักษร');
            return FALSE;
        }
        if ($result > 50) {
            $this->set_form_error('ชื่อมากกว่า 50 ตัวอักษร');
            return FALSE;
        }
        return TRUE;
    }

    /**
     * เช็คนามสกุล
     * @param type $last_name
     * @return boolean
     */
    private function check_last_name($value) {
        $result = strlen($value);
        if ($result < 1) {
            $this->set_form_error('นามสกุลน้อยกว่า 1 ตัวอักษร');
            return FALSE;
        }
        if ($result > 50) {
            $this->set_form_error('นามสกุลมากกว่า 50 ตัวอักษร');
            return FALSE;
        }
        return TRUE;
    }

    /**
     * เช็ค เพศ
     * @param type $sex
     * @return boolean
     */
    private function check_sex($value) {
        if ($value == '') {
            $this->set_form_error('โปรดเลือกเพศ');
            return FALSE;
        }
        return TRUE;
    }

    /**
     * เช็ควันเกิด
     * @param type $birthday
     * @return boolean
     */
    private function check_birthday($value) {
        if ($value == '') {
            $this->set_form_error('โปรดกรอกข้อมูลวันเกิด');
            return FALSE;
        }
        return TRUE;
    }

    private function check_province($value) {
        if ($value == '') {
            $this->set_form_error('โปรดเลือกจังหวัด');
            return FALSE;
        }
        return TRUE;
    }

    private function check_school_name($value) {
        if ($value == '') {
            $this->set_form_error('โปรดกรอกชื่อโรงเรียน');
            return FALSE;
        }
        return TRUE;
    }

    private function check_degree($value, $rid = 2) {
        if ($value == '') {
            if ($rid != 3) {
                $this->set_form_error('โปรดเลือกระดับชั้นเรียน');
            } else {
                $this->set_form_error('โปรดเลือกระดับชั้นที่สอน');
            }

            return FALSE;
        }
        return TRUE;
    }

    private function check_phone_number($value) {
        if ($value == '') {
            $this->set_form_error('โปรดกรอกข้อมูลหมายเลขโทรศัพท์');
            return FALSE;
        }
        return TRUE;
    }

    /**
     * กำหนด ข้อความ Error ของ form
     * @param string $str_error
     */
    private function set_form_error($str_error) {
        $this->form_error[] = $str_error;
    }

    /**
     * ดึงค่า Error ของ form
     * @return array
     */
    public function get_form_error() {
        return $this->form_error;
    }

    public function get_form_data() {
        if (is_array($this->session->flashdata('form_data'))) {
            return $this->session->flashdata('form_data');
        }
        return $this->form_data;
    }

//    private function birthday() {
//        $birthday = explode('/', $this->birthday);
//        $birthday = $birthday[2] . '-' . $birthday[1] . '-' . $birthday[0];
//        return $birthday;
//    }
//==============================================================================    
// เมื่อผู้ใช้ลืมรหัสผ่านจะใช้ระบบ การ ลืม passwordดังนี้
//==============================================================================

    /**
     * ส่ง link สำหรับการตั้งค่ารหัสผ่านใหม่ ถึงผู้ใช้ ผ่าน Email
     * @param type $email
     * @return boolean
     */
    function forget_password($email) {
        $this->db->select('uid');
        $this->db->select('session_id');
        $this->db->where('email', $email);
        $query = $this->db->get('u_user');
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            // fix if no session_id
            //if ($row['session_id'] == '') {
            $row['session_id'] = md5(rand(1111111111, 9999999999));
            $this->db->set('session_id', $row['session_id']);
            $this->db->where('uid', $row['uid']);
            $this->db->update('u_user');
            //}
            $data['reset_pass_url'] = site_url('user/reset_pass/' . $row['uid'] . '/' . $row['session_id']);
            $this->send_email_reset_pass($email, $data);
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function send_email_reset_pass($user_email, $data) {
        if ($this->auth->is_system_host() && $this->real_email) {
            $this->load->library('email');
            $this->email->to($user_email);
            $this->email->subject('แจ้งลิ้งสำหรับการกำหนดรหัสผ่านใหม่');
            $data['site_name'] = $this->setting->get_site_name();
            $data['site_email'] = $this->setting->get_site_email();
            $this->email->message_view('user/_reset_pass_email', $data);
            $this->email->send();
            //$this->email->print_debugger();
        }
    }

    /**
     * ทำการ reset password
     * @param type $uid
     * @param type $session_id
     * @param type $password
     * @return boolean
     */
    function reset_pass($uid, $session_id, $password) {
        $where = array(
            'uid' => $uid,
            'session_id' => $session_id,
        );
        $this->db->where($where);
        $query = $this->db->get('u_user');
        if ($query->num_rows() > 0) {
            //can reset only one time per email
            $this->db->set('session_id', md5(rand(1111111111, 9999999999)));
            $this->db->set('password', $this->auth->encode_password($password));
            $this->db->where('uid', $uid);
            $this->db->update('u_user');
            return TRUE;
        } else {
            return FALSE;
        }
    }

//==============================================================================    
//  Account Section
//  ระบบสำหรับ จัดการ account ตนเอง
//==============================================================================    

    /**
     * ดึงข้อมูล ของ account ตนเอง
     * @return string
     */
    function get_account_data($uid = '') {
        if ($uid == '') {
            $uid = $this->auth->uid();
        }
        $this->db->where('uid', $uid);
        $q1 = $this->db->get('u_user');
        $rid = $q1->row()->rid;
        $have_user = $q1->num_rows() ? TRUE : FALSE;
        $this->db->select('u_user_detail.*');
        $this->db->select('(select province_name from f_province where f_province.id=u_user_detail.province_id)province_name', NULL, FALSE);
        $this->db->where('uid', $uid);
        $q2 = $this->db->get('u_user_detail');
        $have_user_detail = $q2->num_rows() ? TRUE : FALSE;
        $this->db->where('uid', $uid);
        $q3 = $this->db->get('u_user_credit');
        $have_user_credit = $q3->num_rows() ? TRUE : FALSE;

        if ($have_user) {
            if (!$have_user_detail) {
                $user_detail_set = array(
                    'uid' => $uid,
                    'rid' => $rid
                );
                $this->db->set($user_detail_set);
                $this->db->insert('u_user_detail');
                $this->db->select('u_user_detail.*');
                $this->db->select('(select province_name from f_province where f_province.id=u_user_detail.province_id)province_name', NULL, FALSE);
                $this->db->where('uid', $uid);
                $q2 = $this->db->get('u_user_detail');
            }
            if (!$have_user_credit) {
                $user_credit_set = array(
                    'uid' => $uid
                );
                $this->db->set($user_credit_set);
                $this->db->insert('u_user_credit');
                $this->db->where('uid', $uid);
                $q3 = $this->db->get('u_user_credit');
            }
        }




        $row_user = $q1->row_array();
        $row_user_detail = $q2->row_array();
        $row_user_credit = $q3->row_array();
        $row = array_merge($row_user, $row_user_detail, $row_user_credit);
        $row['degree_name'] = $this->get_degree_name($row['degree_id']);

        if ($this->auth->make_money) {

            if ($row['bank_id'] > 0) {
                $this->db->select('bank_name');
                $this->db->where('bank_id', $row['bank_id']);
                $q = $this->db->get('f_bank');
                $row['bank_name'] = $q->row()->bank_name;
            } else {
                $row['bank_name'] = '';
            }
        }


        return $row;
    }

    function get_personal_document_data($uid) {
        $this->db->select('personal_document');
        $this->db->where('uid', $uid);
        $q = $this->db->get('u_user_detail');
        if ($q->num_rows() > 0) {
            $r = $q->row_array();
            $result = $r['personal_document'];
            if ($result != '') {
                $result = json_decode($result, TRUE);
            } else {
                return FALSE;
            }
            return $result;
        }
        return FALSE;
    }

    function update_teacher_personal_document($data) {
        $uid = $data['uid'];
        $personal_document_data = $this->get_personal_document_data($uid);
        if (!$personal_document_data) {
            $personal_document_data = array(
                'identity_document' => '',
                'educational_document' => ''
            );
        }
        $document_name = $data['document_name'];
        $this->load->library('upload');

        $config['upload_path'] = $this->personal_document_dir;
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '10000';
        $config['max_width'] = '102400';
        $config['max_height'] = '768000';
        $config['overwrite'] = TRUE;


        $config['file_name'] = $uid . '_' . $document_name;
        $this->upload->initialize($config);
        $result1 = $this->upload->do_upload('personal_document');
        $upload_data1 = $this->upload->data();
        if ($result1) {
            $personal_document_data[$document_name] = $upload_data1['file_name'];
        }
        $this->db->where('uid', $uid);
        $this->db->set('personal_document', json_encode($personal_document_data));
        $this->db->update('u_user_detail');
        return TRUE;
    }

    function get_degree_name($degree_id, $short = FALSE) {

        if ($degree_id == 0) {
            return '';
        }
        $this->db->where('degree_id', $degree_id);
        if ($short) {
            return $this->db->get('f_degree')->row()->degree_short;
        } else {
            return $this->db->get('f_degree')->row()->degree_long;
        }
    }

    /**
     * ดึงข้อมุล Profile ของตนเอง เพื่อนำไปสร้างฟอร์มแก้ไข
     * @return array
     */
    function get_profile_form_data() {
        $this->db->where('uid', $this->auth->uid());
        $query = $this->db->get('u_user_detail');
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
        } else {
            foreach ($this->db->list_fields('u_user_detail') as $v) {
                $row[$v] = '';
            }
        }
        return $row;
    }

    /**
     * ดึงข้อมูล Email เพื่อนำไปสร้างฟอร์มแก้ไข
     * @return array
     */
    function get_email_form_data() {
        $this->db->select('uid,email');
        $this->db->where('uid', $this->auth->uid());
        $query = $this->db->get('u_user');
        $row = $query->row_array();
        $form_data = array(
            'uid' => $row['uid'],
            'email' => $row['email']
        );
        return $form_data;
    }

    /**
     * ทำการแก้ไข โปรไฟล์ของตนเอง
     * @param array $data
     */
    function save_profile($data) {

        $bvalid = TRUE;
        if ($this->check_first_name($data['first_name'])) {
            $bvalid &= TRUE;
        } else {
            $data['first_name'] = '';
            $bvalid &= FALSE;
        }
        if ($this->check_last_name($data['last_name'])) {
            $bvalid &= TRUE;
        } else {
            $data['last_name'] = '';
            $bvalid &= FALSE;
        }
        if ($this->check_sex($data['sex'])) {
            $bvalid &= TRUE;
        } else {
            $bvalid &= FALSE;
        }
        if ($this->check_birthday($data['birthday'])) {
            $bvalid &= TRUE;
        } else {
            $data['birthday'] = '';
            $bvalid &= FALSE;
        }
        if ($bvalid) {

            $set = array(
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'sex' => $data['sex'],
                'birthday' => $data['birthday'],
                'province_id' => $data['province_id'],
                'school_name' => $data['school_name'],
                'phone_number' => $data['phone_number'],
                'degree_id' => $data['degree_id'],
                'about_me' => $data['about_me'],
                'full_name' => $data['first_name'] . ' ' . $data['last_name']
            );
            $this->db->where('uid', $this->auth->uid());
            $q = $this->db->get('u_user_detail');
            if ($q->num_rows() > 0) {
                $row = $q->row_array();
                $this->db->set($set);
                $this->db->where('uid', $this->auth->uid());
                $this->db->update('u_user_detail');
                if ($row['first_name'] != $data['first_name'] && $row['last_name'] != $data['last_name'] && $row['school_name'] != $data['school_name']) {
                    $this->CI->load->model('study/course_model');
                    $this->CI->course_model->update_course_owner_detail($this->auth->uid());
                }
            } else {
                $set['uid'] = $this->auth->uid();
                $this->db->set($set);
                $this->db->insert('u_user_detail');
            }
            $this->auth->froce_set_display_name($data['first_name'], $data['last_name']);
            return TRUE;
        } else {
            $form_data = array(
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'sex' => $data['sex'],
                'birthday' => $data['birthday']
            );
            $this->session->set_flashdata('form_error', $this->get_form_error());
            $this->session->set_flashdata('form_data', $form_data);
            return FALSE;
        }
    }

    /**
     * ทำการแก้ไข Email
     * @param type $email
     */
    function edit_email($email) {


        if ($this->check_email($email)) {
            $this->db->where('uid', $this->auth->uid());
            $this->db->set('email', $email);
            $this->db->update('u_user');
            $result = array(
                'success' => TRUE,
                'message' => 'แก้ไขอีเมล์เสร็จสิ้น'
            );
        } else {
            $result = array(
                'success' => FALSE,
                'message' => '<p>' . implode("</p><p>", $this->get_form_error()) . '</p>'
            );
        }
        return $result;
    }

    /**
     * ทำการแก้ไข password
     * @param type $old_password
     * @param type $new_password
     * @return boolean
     */
    function edit_password($old_password, $new_password) {
        $this->password = $new_password;
        if ($this->check_password($new_password, $new_password)) {
            $this->db->where('password', $this->auth->encode_password($old_password));
            $this->db->where('uid', $this->auth->uid());
            if ($this->db->count_all_results('u_user')) {
                $this->db->set('password', $this->auth->encode_password($new_password));
                $this->db->where('uid', $this->auth->uid());
                $this->db->update('u_user');
                return TRUE;
            }

            $this->set_form_error('รหัสผ่านเดิมไม่ถูกต้อง');
            return FALSE;
        } else {
            return FALSE;
        }
    }

    function upload_avatar() {
        $result = array(
            'success' => TRUE,
            'message' => 'อัพโหลดรูปภาพเสร็จสิ้น'
        );
        $config['upload_path'] = $this->config->item('avatar_dir');
        $config['file_name'] = $this->auth->uid();
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '1000';
        $config['max_width'] = '4000';
        $config['max_height'] = '3000';
        $config['overwrite'] = TRUE;


        $this->load->library('upload', $config);

        if ($this->upload->do_upload()) {
            $upload_data = $this->upload->data();
            $this->load->library('image_lib');
            $img_config['image_library'] = 'gd2';
            $img_config['source_image'] = $upload_data['full_path'];
//            $img_config['create_thumb'] = FALSE;
//            $img_config['thumb_marker'] = '';
            $img_config['maintain_ratio'] = FALSE;

            // 64
            $size = 32;
            $img_config['width'] = $size;
            $img_config['height'] = $size;
            $img_config['new_image'] = $upload_data['file_path'] . $upload_data['raw_name'] . '_' . $size . '.jpg';
            $this->image_lib->initialize($img_config);
            $this->image_lib->resize();
            // 64
            $size = 64;
            $img_config['width'] = $size;
            $img_config['height'] = $size;
            $img_config['new_image'] = $upload_data['file_path'] . $upload_data['raw_name'] . '_' . $size . '.jpg';
            $this->image_lib->initialize($img_config);
            $this->image_lib->resize();

            // 128
            $size = 128;
            $img_config['width'] = $size;
            $img_config['height'] = $size;
            $img_config['new_image'] = $upload_data['file_path'] . $upload_data['raw_name'] . '_' . $size . '.jpg';
            $this->image_lib->initialize($img_config);
            $this->image_lib->resize();
            // 256
            $size = 256;
            $img_config['width'] = $size;
            $img_config['height'] = $size;
            $img_config['new_image'] = $upload_data['file_path'] . $upload_data['raw_name'] . '_' . $size . '.jpg';
            $this->image_lib->initialize($img_config);
            if (!$this->image_lib->resize()) {
                $result = array(
                    'success' => FALSE,
                    'message' => $this->image_lib->display_errors()
                );
            }
            unlink($upload_data['full_path']);
        } else {
            //print_r($this->upload->display_errors());
            $result = array(
                'success' => FALSE,
                'message' => $this->upload->display_errors()
            );
        }
        return $result;
    }

    function swf_upload_avatar($uid) {
        $result = array(
            'success' => TRUE,
            'message' => "upload complete"
        );
        if (isset($GLOBALS["HTTP_RAW_POST_DATA"])) {
            $jpg = $GLOBALS["HTTP_RAW_POST_DATA"];
            $raw_name = $uid;
            $file_path = $this->config->item('avatar_dir');
            $filename = $file_path . $raw_name . '.jpg';
            file_put_contents($filename, $jpg);
            // upload data
            //$upload_data = $this->upload->data();
            $upload_data = array(
                'file_path' => $file_path,
                'raw_name' => $raw_name,
                'full_path' => $filename
            );

            $this->load->library('image_lib');
            $img_config['image_library'] = 'gd2';
            $img_config['source_image'] = $upload_data['full_path'];
            //   $img_config['create_thumb'] = TRUE;
//            
            //  $img_config['thumb_marker'] = '';
            $img_config['maintain_ratio'] = false;




            $img_config['x_axis'] = '40';
            $img_config['width'] = 240;
            $img_config['height'] = 240;
            $img_config['new_image'] = $upload_data['file_path'] . $upload_data['raw_name'] . '.jpg';
            $this->image_lib->initialize($img_config);
            $this->image_lib->crop();
            $img_config['source_image'] = $upload_data['file_path'] . $upload_data['raw_name'] . '.jpg';


            // 64
            $size = 32;
            $img_config['width'] = $size;
            $img_config['height'] = $size;
            $img_config['new_image'] = $upload_data['file_path'] . $upload_data['raw_name'] . '_' . $size . '.jpg';
            $this->image_lib->initialize($img_config);
            $this->image_lib->resize();
            // 64
            $size = 64;
            $img_config['width'] = $size;
            $img_config['height'] = $size;
            $img_config['new_image'] = $upload_data['file_path'] . $upload_data['raw_name'] . '_' . $size . '.jpg';
            $this->image_lib->initialize($img_config);
            $this->image_lib->resize();

            // 128
            $size = 128;
            $img_config['width'] = $size;
            $img_config['height'] = $size;
            $img_config['new_image'] = $upload_data['file_path'] . $upload_data['raw_name'] . '_' . $size . '.jpg';
            $this->image_lib->initialize($img_config);
            $this->image_lib->resize();
            // 256
            $size = 256;
            $img_config['width'] = $size;
            $img_config['height'] = $size;
            $img_config['new_image'] = $upload_data['file_path'] . $upload_data['raw_name'] . '_' . $size . '.jpg';
            $this->image_lib->initialize($img_config);
            if (!$this->image_lib->resize()) {
                $result = array(
                    'success' => FALSE,
                    'message' => $this->image_lib->display_errors()
                );
            }
            unlink($upload_data['full_path']);
        } else {
            //print_r($this->upload->display_errors());
            $result = array(
                'success' => FALSE,
                'message' => $this->upload->display_errors()
            );
        }
        return $result;
    }

}
