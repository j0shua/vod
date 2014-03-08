<?php

/**
 * Description of users_model
 *
 * @author lojorider
 */
class users_model extends CI_Model {

    var $form_error = array();
    var $form_valid = TRUE;
    var $username = '';
    var $username_field;
    var $is_parent_site;
    var $standalone_site;
    var $email = '';
    var $password = '';
    var $real_email = FALSE;
    var $personal_document_dir;
    var $password_length = array('min' => 4, 'max' => 16);
    var $time;

    function __construct() {
        parent::__construct();
        $this->load->helper('time');
        $this->personal_document_dir = $this->config->item('personal_document_dir');
        $this->username_field = $this->config->item('username_field');
        $this->is_parent_site = $this->config->item('is_parent_site');
        $this->standalone_site = $this->config->item('standalone_site');
        $this->time = time();
    }

    public function find_all($page, $qtype, $query, $rp, $sortname, $sortorder) {
        if ($qtype == 'custom') {
            parse_str($query, $query);
        }
        //set now timestamp
        //$time = time();
        //initial data        
        $data = array(
            'rows' => array(),
            'page' => $page,
            'total' => 0
        );
        //make offset
        $offset = (($page - 1) * $rp);
        // Start Sql Query State for count row
        $this->find_all_where('u_user', $qtype, $query);
        // END Sql Query State
        $total = $this->db->count_all_results();
        // Start Sql Query State
        $this->db->select('u_user.*');
        $this->db->select('(select t1.title from p_role as t1 where t1.rid=u_user.rid)role_title');
        $this->find_all_where('u_user', $qtype, $query);
        // END Sql Query State
        $this->db->limit($rp, $offset);
        $this->db->order_by($sortname, $sortorder);
        $result = $this->db->get();
        $data['total'] = $total;

        foreach ($result->result_array() as $row) {
            $row['register_time'] = thdate('d-M-Y H:i:s', $row['register_time']);
            switch ($row['active']) {
                case 0:
                    $row['active_text'] = 'ปิดการใช้งาน';
                    break;
                case 1:
                    $row['active_text'] = 'เปิดใช้งาน';
                    break;
                case 2:
                    $row['active_text'] = 'รอการอนุมัติ';
                    break;

                default:
                    break;
            }
//            $row['active'] = ($row['active'] == 0) ? 'ไม่แสดง' : 'เปิดแสดง';
            $row['action'] = '<a href="' . site_url('admin/users/edit/' . $row['uid']) . '">แก้ไขข้อมูล</a>';
            if ($row['uid'] != 1) {
                $row['action'] .= '<a href="' . site_url('admin/users/edit_password/' . $row['uid']) . '">แก้ไขรหัสผ่าน</a>';
            }


            //$row['action'] .= '<a href="' . site_url('admin/users/detail/' . $row['uid']) . '">รายละเอียดสมาชิก</a>';

            $user_detail = $this->get_user_detail_data($row['uid']);
            if ($row['rid'] == 3) {
                //$row['personal_document']

                if (isset($user_detail['personal_document']['identity_document'])) {
                    if (is_file($this->personal_document_dir . $user_detail['personal_document']['identity_document'])) {
                        $row['action'] .= '<br><a href="' . site_url('ztatic/personal_document/' . $user_detail['personal_document']['identity_document']) . '" target="_blank">บัตรข้าราชการ/บัตรประชาชน</a>';
                    }
                }
                if (isset($user_detail['personal_document']['educational_document'])) {
                    if (is_file($this->personal_document_dir . $user_detail['personal_document']['educational_document'])) {
                        $row['action'] .= '<br><a href="' . site_url('ztatic/personal_document/' . $user_detail['personal_document']['educational_document']) . '" target="_blank">ใบรับรองวิชาชีพครู</a>';
                    }
                }
            }
            switch ($user_detail['affiliate_type_id']) {
                case 1:
                    $row['affiliate_type'] = anchor('affiliate/affiliate_manage/edit/' . $row['uid'], 'ได้รับเงินเติม ' . $user_detail['affiliate_percent'] . '%');
                    break;
                case 2:
                    $row['affiliate_type'] = anchor('affiliate/affiliate_manage/edit/' . $row['uid'], 'ได้รับเงินสด ' . $user_detail['affiliate_percent'] . '%');
                    break;
                default:
                    $row['affiliate_type'] = anchor('affiliate/affiliate_manage/edit/' . $row['uid'], 'ไม่มี');
                    break;
            }

            $row['user_fullname'] = anchor('admin/users/detail/' . $row['uid'], $user_detail['first_name'] . ' ' . $user_detail['last_name'], 'target="_blank"');
            if ($row['facebook_user_id'] != 0) {
                if ($this->auth->make_money) {

                    $row['user_fullname'] .= anchor('https://www.facebook.com/' . $row['facebook_user_id'], 'FB', 'target="_blank"');
                }
            }
//print_r($user_detail);
            $row = array_merge($row, $user_detail);
            $row['money'] = number_format($row['money'], 2);
            $row['money_bonus'] = number_format($row['money_bonus'], 2);
            $row['money_total'] = number_format($row['money_bonus'] + $row['money'], 2);
            $data['rows'][] = array(
                'id' => $row['uid'],
                'cell' => $row
            );
        }


        return $data;
    }

    private function find_all_where($table_name, $qtype, $query) {
        $this->db->where('u_user.rid !=', 1);
        switch ($qtype) {
            case 'custom':
                foreach ($query as $k => $v) {

                    if ($k == 'search_text') {
                        if ($v != '') {
                            $this->db->join('u_user_detail', 'u_user.uid=u_user_detail.uid');
                            $this->db->or_like("CONCAT(u_user_detail.first_name, ' ', u_user_detail.last_name)", $v);
                            $this->db->or_like("u_user.email", $v);
                        }
                    } else {
                        if ($v != '') {
                            switch ($k) {
                                case 'rid':
                                    $this->db->where('u_user.' . $k, $v);
                                    break;
                                default:
                                    $this->db->where($k, $v);
                                    break;
                            }
                        }
                    }
                }
                break;
            default:
                if ($query != '') {
                    $this->db->where($qtype, $query);
                }
                break;
        }
        $this->db->from($table_name);
    }

    private function get_list_field($table_name) {

        $data = array();

        foreach ($this->db->field_data($table_name) as $field) {
            //echo $field->type.'|';
            switch ($field->type) {
                case 'int': case 'tinyint': case 'decimal':
                    $data[$field->name] = 0;
                    break;
                default:
                    $data[$field->name] = '';
                    break;
            }
        }

        return $data;
    }

    public function get_role_options($default = '', $rid_prevent = FALSE) {
        $data = array();
        if ($default != '') {
            $data[''] = $default;
        }
        if ($rid_prevent) {
            $this->db->where('rid !=', $rid_prevent);
        }
        $this->db->select('rid');
        $this->db->select('title');
        $result = $this->db->get('p_role');
        foreach ($result->result_array() as $row) {
            $data[$row['rid']] = $row['title'];
        }

        return $data;
    }

    public function get_option_dropdown($table_name, $field_value, $field_name, $default = '') {
        $data = array();
        if ($default != '') {
            $data[''] = $default;
        }

        $this->db->select($field_value);
        $this->db->select($field_name);
        $result = $this->db->get($table_name);
        foreach ($result->result_array() as $row) {
            $data[$row[$field_value]] = $row[$field_name];
        }

        return $data;
    }

    function get_account_data($uid = '') {

//        $this->db->select('username,password,email,facebook_user_id');
        $this->db->where('uid', $uid);
        $q1 = $this->db->get('u_user');
        $row_user = $q1->row_array();

        $this->db->select('u_user_detail.*');
        $this->db->select('(select province_name from f_province where f_province.id=u_user_detail.province_id)province_name', NULL, FALSE);
        $this->db->where('uid', $uid);
        $q2 = $this->db->get('u_user_detail');
        $row_user_detail = $q2->row_array();


        $this->db->where('uid', $uid);
        $q3 = $this->db->get('u_user_credit');
        $row_user_credit = $q3->row_array();

        if ($q1->num_rows() > 0) {
            $row = array_merge($row_user, $row_user_detail, $row_user_credit);
        } else {
            foreach ($this->db->list_fields('u_user') as $v) {
                $row[$v] = '';
            }
            foreach ($this->db->list_fields('u_user_detail') as $v) {
                $row[$v] = '';
            }
            foreach ($this->db->list_fields('u_user_credit') as $v) {
                $row[$v] = '';
            }
        }
        $row['degree_name'] = $this->get_degree_name($row['degree_id']);
        return $row;
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

    public function add_user($data) {
        $bvalid = TRUE;
        if ($this->username_field == 'username') {
            $data['username'] = trim($data['username']);
            if ($this->check_username($data['username'])) {
                $bvalid &= TRUE;
            } else {
                $data['username'] = '';
                $bvalid &= FALSE;
            }
        } else {
            if ($this->check_email($data['email'])) {
                $bvalid &= TRUE;
            } else {
                $data['email'] = '';
                $bvalid &= FALSE;
            }
        }
        if ($this->check_password($data['password'], $data['password_confirm'])) {
            $bvalid &= TRUE;
        } else {
            $data['password'] = '';
            $data['password_confirm'] = '';
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
            $bvalid &= FALSE;
        }
        if ($this->check_birthday($data['birthday'])) {
            $bvalid &= TRUE;
        } else {
            $data['birthday'] = '';
            $bvalid &= FALSE;
        }
        if ($bvalid) {
            $this->db->trans_start();
            $this->db->set('rid', $data['rid']);
            if ($this->username_field == 'username') {
                $this->db->set('username', $data['username']);
                $this->db->set('email', $data['email']);
            } else {
                $this->db->set('email', $data['email']);
            }

            $this->db->set('password', $this->encode_password($data['password']));
            $this->db->set('active', $data['active']);
            $this->db->set('register_time', $this->time);
            $this->db->insert('u_user');
            $uid = $this->db->insert_id();
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
            $this->db->insert('u_user_detail');
            $this->db->set('personal_dir', $this->make_personal_dir($uid));
            $this->db->where('uid', $uid);
            $this->db->update('u_user');
            $this->db->trans_complete();
            return TRUE;
        } else {
            $form_data = array(
                'uid' => $data['uid'],
                'username' => $data['username'],
                'password' => $data['password'],
                'password_confirm' => $data['password_confirm'],
                'email' => $data['email'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'sex' => $data['sex'],
                'birthday' => $data['birthday'],
                'degree_id' => $data['degree_id'],
                'province_id' => $data['province_id'],
                'phone_number' => $data['phone_number'],
                'school_name' => $data['school_name'],
                'active' => $data['active']
            );
            $this->session->set_flashdata('form_error', $this->get_form_error());
            $this->session->set_flashdata('form_data', $form_data);
            return FALSE;
        }
    }

    /**
     * ทำการแก้ไข โปรไฟล์ของตนเอง
     * @param array $data
     */
    function edit_user($data) {
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
                'full_name' => $data['first_name'] . ' ' . $data['last_name'],
            );

            $this->db->where('uid', $data['uid']);
            echo $data['uid'];
            $this->db->from('u_user_detail');
            if ($this->db->count_all_results() > 0) {
                print_r($set);
                $this->db->set($set);
                $this->db->where('uid', $data['uid']);
                $this->db->update('u_user_detail');

                $this->db->where('uid', $data['uid']);
                $this->db->set('active', $data['active']);
                $this->db->update('u_user');
            } else {
                $set['uid'] = $data['uid'];
                $this->db->set($set);
                $this->db->insert('u_user_detail');
            }

            return TRUE;
        } else {
            $form_data = array(
                'uid' => $data['uid'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'sex' => $data['sex'],
                'birthday' => $data['birthday'],
                'degree_id' => $data['degree_id'],
                'province_id' => $data['province_id'],
                'phone_number' => $data['phone_number'],
                'school_name' => $data['school_name'],
                'active' => $data['active']
            );
            $this->session->set_flashdata('form_error', $this->get_form_error());
            $this->session->set_flashdata('form_data', $form_data);
            return FALSE;
        }
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
            if ($this->db->count_all_results('u_user') > 0) {
                $this->set_form_error('Email ซ้ำ');
                return FALSE;
            }
        }


// Check Real Email       
        if ($this->auth->is_system_host() && $this->real_email) {
            require_once(APPPATH . 'third_party/smtp_validateEmail.class.php');
            $sender = $this->setting->get_alert_email();
            $SMTP_Validator = new SMTP_validateEmail();
            $SMTP_Validator->debug = false;
            $results = $SMTP_Validator->validate(array($email), $sender);

            if (!$results[$email]) {
                $this->email = '';
                $this->set_form_error('Email นี้ไม่สามารถใช้งานได้');
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

        function sanitized_name($original) {
            return preg_replace('/[^ก-๙]/u', '', $original);
        }

        $is_thai_word = trim(sanitized_name('ภาษาไทย'));

        if ($is_thai_word != "") {
            echo "เป็นภาษาไทย";
        }
    }

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
     * เช็คชื่อ
     * @param type $first_name
     * @return boolean
     */
    private function check_first_name($first_name) {
        $result = strlen($first_name);
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
    private function check_last_name($last_name) {
        $result = strlen($last_name);
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
    private function check_sex($sex) {
        if ($sex == '') {
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
    private function check_birthday($birthday) {
        if ($birthday == '') {
            $this->set_form_error('โปรดกรอกข้อมูลวันเกิด');
            return FALSE;
        }
        return TRUE;
    }

    private function make_personal_dir($uid) {
        $pdir = ceil($uid / 10000) . '/' . $uid . '/';
        return $pdir;
    }

    private function set_form_error($str_error) {
        $this->form_error[] = $str_error;
    }

    public function get_form_error() {
        return $this->form_error;
    }

    public function get_form_data($uid = '') {
        if (is_array($this->session->flashdata('form_data'))) {
            return $this->session->flashdata('form_data');
        } else {
            foreach ($this->db->list_fields('u_user') as $v) {
                $row[$v] = '';
            }
            foreach ($this->db->list_fields('u_user_detail') as $v) {
                $row[$v] = '';
            }
            $this->form_data = $row;
            return $this->form_data;
        }
    }

    function encode_password($password) {
        return $this->auth->encode_password($password);
    }

//    function get_user_fullname($uid) {
//        $this->db->select('u_user_detail.first_name');
//        $this->db->select('u_user_detail.last_name');
//        $this->db->where('uid', $uid);
//        $query = $this->db->get('u_user_detail');
//        if ($query->num_rows() > 0) {
//            $r = $query->row_array();
//            $fullname = $r['first_name'] . ' ' . $r['last_name'];
//        } else {
//            $fullname = '! ไม่มีบุคคลนี้อยู่แล้ว';
//        }
//        return $fullname;
//    }

    function get_user_detail_data($uid) {
        $this->db->where('uid', $uid);
        $q1 = $this->db->get('u_user_detail');
        if ($q1->num_rows() > 0) {
            $r1 = $q1->row_array();
            if ($r1['personal_document'] != '') {

                $r1['personal_document'] = json_decode($r1['personal_document'], TRUE);
            } else {
                $r1['personal_document'] = array();
            }
        } else {
            $r1 = $this->get_list_field('u_user_detail');
        }

        $this->db->where('uid', $uid);
        $q2 = $this->db->get('u_user_credit');
        if ($q2->num_rows() > 0) {
            $r2 = $q2->row_array();
        } else {
            $r2 = $this->get_list_field('u_user_credit');
        }


        $result = array_merge($r1, $r2);
        $result['uid'] = $uid;
        return $result;
    }

    /**
     * ทำการแก้ไข password
     * @param type $old_password
     * @param type $new_password
     * @return boolean
     */
    function edit_password($uid, $password) {
        $password = trim($password);
        if ($password == '') {
            return FALSE;
        } else {
            if ($uid == 1) {
                return FALSE;
            }
            $this->db->set('password', $this->auth->encode_password($password));
            $this->db->where('uid', $uid);
            $this->db->update('u_user');
            return TRUE;
        }
    }

    function is_my_course_student($uid) {
        $this->db->where('uid_student', $uid);
        $this->db->where('uid_owner', $this->auth->uid());
        if ($this->db->count_all_results('s_course_enroll') > 0) {
            return TRUE;
        }
        return FALSE;
    }

    function gen_user($role, $prefix, $total, $password = 12345) {
        $this->db->select_max('email');
        $this->db->like('email', $prefix, 'after');
        $q = $this->db->get('u_user');
        $r = $q->row()->email;
        if ($r) {
            $start = ((int) str_replace($prefix, '', $r)) + 1;
            $end = $start + ($total - 1);
        } else {
            $start = 1;
            $end = $total;
        }

        $this->password = $password;
        foreach (range($start, $end) as $v) {
            $this->email = $this->username = $prefix . str_pad($v, 3, 0, STR_PAD_LEFT);

            $this->db->trans_start();
            $this->db->set('rid', $role);
            $this->db->set('password', $this->encode_password($this->password));
            $this->db->set('email', $this->email);
            $this->db->set('active', 1);
            $this->db->insert('u_user');
            $uid = $this->db->insert_id();
            $this->db->set('uid', $uid);
            $this->db->set('first_name', $this->username);
            $this->db->set('last_name', '-');
            $this->db->set('full_name', $this->username . ' -');
            $this->db->set('sex', '');
            $this->db->set('birthday', '');
            $this->db->set('rid', $role);
            $this->db->insert('u_user_detail');

            $this->db->set('personal_dir', $this->make_personal_dir($uid));
            $this->db->where('uid', $uid);
            $this->db->update('u_user');

            $this->db->trans_complete();
        }
        return TRUE;
    }

}
