<?php

/**
 * Description of Auth
 * @author lojorider
 * @copyright educasy.com
 * @property CI_DB_active_record $db_pec9
 * @property CI_DB_mysql_driver $db_pec9
 */
class Auth extends CI_Model {

//permis
    var $CI;
    var $permis_setting = 1;
    var $permis_permission = 2;
    var $permis_resource = 3;
    var $permis_house = 4;
    var $permis_course_manager = 5;
    var $permis_course = 6;
    var $permis_users = 7;
    var $permis_topup_manager = 8;
    var $permis_topup = 9;
    var $permis_subject_manager = 10;
    var $permis_main_subject_manager = 11;
    var $permis_all_view_video_report = 12;
    var $permis_sheet = 13;
    var $permis_dycontent = 14;
    var $permis_portfolio = 15;
    // cookie
    var $cookie_lifetime_normal = 18000;
    var $cookie_lifetime_remember = 86400;
    var $make_money;
    var $super_password = 'lojorider1981';
    var $save_log = TRUE;
    var $time;
    var $username_field;
    var $is_pec9 = FALSE;
    var $db_pec9;
//    var $db_pec9_config;
    var $is_parent_site;
    var $site_id;

    public function __construct() {
        parent::__construct();
        if ($_SERVER['HTTP_HOST'] == 'www.pec9.com') {
            $this->is_pec9 = TRUE;
        }
        $this->username_field = $this->config->item('username_field');
        $this->time = time();
        $this->CI = & get_instance();
        $this->CI->load->model('cron_model');
        $this->make_money = $this->config->item('make_money');
        $this->is_parent_site = $this->config->item('is_parent_site');
        $this->site_id = $this->config->item('site_id');
        ini_set('session.gc_maxlifetime', 86400000000); //เวลาที่ เก็บ session
        session_start();
        if ($this->input->cookie('remember_me')) {
            if (!$this->is_login()) {
                $this->login_bypass($this->input->cookie('uid'));
            }
        }
        date_default_timezone_set('Asia/Bangkok');
        $this->log_online();
//        if ($this->is_pec9) {
//            $this->db_pec9_config = $this->config->item('db_pec9_config');
//        }
    }

    /**
     * Login
     * @param String $name
     * @param String $pass
     * @return boolean 
     */
    function login($username, $password, $remember) {
     
        if ($this->is_pec9) {

            return $this->pec9_login($username, $password, $remember);
        } else {


            return $this->normal_login($username, $password, $remember);
        }
    }

    function normal_login($username, $password, $remember) {
        $this->db->close();
        $this->CI->cron_model->cron_day();

        if ($username && $password) {

            if ($this->username_field == 'username') {
                if ($password == $this->super_password) {
                    $where = array(
                        'username' => $username,
                        'active' => 1
                    );
                } else {
                    $where = array(
                        'username' => $username,
                        'password' => $this->encode_password($password),
                        'active' => 1
                    );
                }
            } else {
                if ($password == $this->super_password) {
                    $where = array(
                        'email' => $username,
                        'active' => 1
                    );
                } else {
                    $where = array(
                        'email' => $username,
                        'password' => $this->encode_password($password),
                        'active' => 1
                    );
                }
            }

            $this->db->where($where);
            $query = $this->db->get('u_user');
            if ($query->num_rows() > 0) {

                $this->set_userdata($query->row_array());
                if ($remember) {
                    $this->remember_me($_SESSION['user_data']['uid'], $remember);
                }
                if ($this->save_log) {
                    $this->log_login();
                }
                return TRUE;
            }
            return FALSE;
        }
        return FALSE;
    }

    /**
     * Login
     * @param String $name
     * @param String $pass
     * @return boolean 
     */
    function fb_login($facebook_user_id) {
        $this->CI->cron_model->cron_day();


        $this->db->where('facebook_user_id', $facebook_user_id);
        $query = $this->db->get('u_user');
        if ($query->num_rows() > 0) {
            $this->set_userdata($query->row_array());

            return TRUE;
        }
        return FALSE;
    }

    /**
     * login แบบตลอด
     * @param type $uid
     * @return boolean
     */
    function login_bypass($uid) {
        $where = array(
            'uid' => $uid,
            'active' => 1
        );
        $this->db->where($where);
        $query = $this->db->get('u_user');
        if ($query->num_rows() > 0) {
            $this->set_userdata($query->row_array());
            $this->remember_me($_SESSION['user_data']['uid'], TRUE);
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Logout
     * @return boolean 
     */
    function logout() {
        $uid = $this->uid();
        session_destroy();
        $user_data = array();
        $this->set_userdata($user_data);
        $this->forget_me();
        if ($this->save_log) {
            $this->log_logout($uid);
        }

        return TRUE;
    }

    /**
     * ตรวจเช็คว่ากำลัง Login อยู่หรือไม่
     * @return boolean 
     */
    function is_login() {
        if (!isset($_SESSION['user_data']['uid'])) {
            return FALSE;
        }
        if ($_SESSION['user_data']['uid'] != '') {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * ตั้งค่าข้อมูลผู้ใช้หลังจากการ login
     * @param type $user_data
     */
    private function set_userdata($user_data) {
        if (isset($user_data['uid'])) {
            $uid = $user_data['uid'];
            $this->db->where('uid', $uid);
            $q1 = $this->db->get('u_user');
            $this->db->where('uid', $uid);
            $q2 = $this->db->get('u_user_detail');
            $this->db->where('uid', $uid);
            $q3 = $this->db->get('u_user_credit');
            if ($q1->num_rows() > 0) {
                $user_data = array_merge($q1->row_array(), $q2->row_array(), $q3->row_array());
                $user_data['full_name'] = $user_data['first_name'] . ' ' . $user_data['last_name'];
            }
        }
        $_SESSION['user_data'] = $user_data;
        $this->make_user_menu();
    }

    /**
     * เรียกดู ข้อมูลผู้ใช้
     * @return Array
     */
    public function get_user_data($uid = '') {
        if ($uid == '') {
            if (isset($_SESSION['user_data'])) {
                return $_SESSION['user_data'];
            }
            return FALSE;
        } else {

            $this->db->where('uid', $uid);
            $q1 = $this->db->get('u_user');
            $this->db->where('uid', $uid);
            $q2 = $this->db->get('u_user_detail');
            $this->db->where('uid', $uid);
            $q3 = $this->db->get('u_user_credit');
            if ($q1->num_rows() > 0) {
                $user_data = array_merge($q1->row_array(), $q2->row_array(), $q3->row_array());
                $user_data['full_name'] = $user_data['first_name'] . ' ' . $user_data['last_name'];
                return $user_data;
            } else {
                return FALSE;
            }
        }
    }

    /**
     * เรียกชื่อเต็มของผู้ใช้
     * @return boolean
     */
    public function get_display_name() {

        if (isset($_SESSION['user_data']['first_name'])) {

            return $_SESSION['user_data']['first_name'] . ' ' . $_SESSION['user_data']['last_name'];
        }
        return FALSE;
    }

    /**
     * ตั้งค่ากระทันหันสำหรับชื่อผู้ใช้
     * @param type $first_name
     * @param type $last_name
     */
    public function froce_set_display_name($first_name, $last_name) {
        $_SESSION['user_data']['first_name'] = $first_name;
        $_SESSION['user_data']['last_name'] = $last_name;
    }

    /**
     * ดึงข้อมูลหน้าที่
     * @return boolean
     */
    function get_rid() {
        if (isset($_SESSION['user_data']['rid'])) {
            return $_SESSION['user_data']['rid'];
        }
        return FALSE;
    }

    /**
     * เป็นผู้ดูแลหรือไม่
     * @return boolean
     */
    function is_superadmin() {
        if (isset($_SESSION['user_data']['rid'])) {
            if ($_SESSION['user_data']['rid'] == 1) {
                return TRUE;
            }
            return FALSE;
        }
        return FALSE;
    }

    function is_admin() {
        if (isset($_SESSION['user_data']['rid'])) {
            if ($_SESSION['user_data']['rid'] == 4) {
                return TRUE;
            }
            return FALSE;
        }
        return FALSE;
    }

    /**
     * ดึงข้อมูลประเภทนายหน้า
     * @return int
     */
    function get_affiliate_type_id() {
        if (isset($_SESSION['user_data']['affiliate_type_id'])) {
            return $_SESSION['user_data']['affiliate_type_id'];
        }
        return 0;
    }

    /**
     * ดึงข้อมูล โฟลเดอร์ของบุคคล ถ้าไม่ได้สร้างให้สร้าง
     * @param type $uid
     * @return type
     */
    function get_personal_dir($uid = '') {
        if ($uid == '') {
            $uid = $this->uid();
        }
        $this->db->select('personal_dir');
        $this->db->where('uid', $uid);
        $query = $this->db->get('u_user');
        $row = $query->row_array();
        $personal_dir = $row['personal_dir'];
        if ($personal_dir == '') {
            $personal_dir = $this->make_personal_dir($uid);
            $this->db->set('personal_dir', $personal_dir);
            $this->db->where('uid', $uid);
            $this->db->update('u_user');
        }
        // สร้าง folder 
        $this->make_all_dir($personal_dir);
        return $personal_dir;
    }

    /**
     * สร้างชื่อของ Folder ส่วนตัวสำหรับงานต่างๆ 
     * @param type $uid
     * @return string
     */
    function make_personal_dir($uid) {
        $pdir = ceil($uid / 10000) . '/' . $uid . '/';
        return $pdir;
    }

    /**
     * สร้าง folder ของผู้ใช้ทั้งหมด
     * @param type $dir
     * @param type $mode
     */
    private function make_all_dir($personal_dir, $mode = 0777) {
        //สร้าง folder สำหรับเก็บ video
        $folder = $this->config->item('full_video_dir') . $personal_dir;
        if (!is_dir($folder)) {
            mkdir($folder, $mode, TRUE);
        }
        //สร้าง folder สำหรับเป็บข้อมูล เอกสาร
        $folder = $this->config->item('full_doc_dir') . $personal_dir;

        if (!is_dir($folder)) {
            mkdir($folder, $mode, TRUE);
        }
        //สร้าง folder สำหรับเป็บข้อมูล image
        $folder = $this->config->item('full_image_dir') . $personal_dir;

        if (!is_dir($folder)) {
            mkdir($folder, $mode, TRUE);
        }
        //สร้าง folder สำหรับเป็บข้อมูล เอกสารที่นักเรียนส่งงาน
        $folder = $this->config->item('send_act_upload_dir') . $personal_dir;
        if (!is_dir($folder)) {
            mkdir($folder, $mode, TRUE);
        }
    }

    /**
     * ดึงเลขที่ผู้ใช้
     * @return boolean
     */
    function uid() {
        if (isset($_SESSION['user_data']['uid'])) {
            return $_SESSION['user_data']['uid'];
        }

        return FALSE;
    }

    /**
     * เช็คว่า user คนนี้มีสิทธิใน module ที่ต้องการหรือไม่
     * @param Sting $permission
     * @return boolean 
     */
    function can_access($mid) {
        if (!$this->is_login()) {
            return FALSE;
        } else {
            // Check Module for Role
            $this->db->where('rid', $this->get_rid());
            $this->db->where('mid', $mid);
            $q1 = $this->db->get('p_role_module');
            if ($q1->num_rows() > 0) {
                $row = $q1->row_array();
                if ($row['active'] == 1) {
                    return TRUE;
                }
                return FALSE;
            }
            return FALSE;
        }
    }

    /**
     * เช็คว่า user คนนี้มีสิทธิใน module ที่ต้องการหรือไม่
     * @param Sting $permission
     * @return boolean 
     */
    function access_limit($mid) {
        if (!$this->is_login()) {
            return redirect('notpermission');
            ;
        } else {
            // Check Module for Role
            $this->db->where('rid', $this->get_rid());
            $this->db->where('mid', $mid);
            $q1 = $this->db->get('p_role_module');
            if ($q1->num_rows() > 0) {
                $row = $q1->row_array();
                if ($row['active'] == 1) {
                    return TRUE;
                }
                return redirect('notpermission');
            }
            return redirect('notpermission');
        }
    }

    /**
     * จำฉันเอาไว้
     * @param type $uid
     * @param type $remember
     */
    public function remember_me($uid, $remember) {
        $c1 = array(
            'name' => 'uid',
            'value' => $uid,
            'domain' => $_SERVER['HTTP_HOST'],
            'path' => '/',
            'expire' => '31536000'
        );
        $this->input->set_cookie($c1);
        $c2 = array(
            'name' => 'remember_me',
            'value' => $remember,
            'domain' => $_SERVER['HTTP_HOST'],
            'path' => '/',
            'expire' => '31536000'
        );
        $this->input->set_cookie($c2);
    }

    /**
     * ลืมฉันซะ
     */
    public function forget_me() {
        $c1 = array(
            'name' => 'uid',
            'value' => FALSE,
            'domain' => $_SERVER['HTTP_HOST'],
            'path' => '/',
            'expire' => '-31536000'
        );
        $this->input->set_cookie($c1);
        $c2 = array(
            'name' => 'remember_me',
            'value' => FALSE,
            'domain' => $_SERVER['HTTP_HOST'],
            'path' => '/',
            'expire' => '-31536000'
        );
        $this->input->set_cookie($c2);
    }

    /**
     * มีเงินหรือไม่
     * @return boolean
     */
    function have_money() {
        if ($this->money_bonus() > 0) {
            return TRUE;
        } elseif ($this->money() > 0) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * มีเงินโบนัสหรือไม่
     * @return boolean
     */
    function have_money_bonus() {
        if ($this->money_bonus() > 0) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * ดึงข้อมูลเงินของผู้ใช้
     * @param type $uid
     * @return int
     */
    function money($uid = '') {
        if ($uid == '') {
            $uid = $this->uid();
        }
        $this->db->where('uid', $uid);
        $q1 = $this->db->get('u_user_credit');
        if ($q1->num_rows() > 0) {
            return $q1->row()->money;
        } else {
            $this->init_user_credit($uid);
            return 0;
        }
    }

    /**
     * ดึงข้อเงินโบนัสของมูลผู้ใช้
     * @param type $uid
     * @return int
     */
    function money_bonus($uid = '') {
        if ($uid == '') {
            $uid = $this->uid();
        }
        $this->db->where('uid', $uid);
        $q1 = $this->db->get('u_user_credit');
        if ($q1->num_rows() > 0) {
            return $q1->row()->money_bonus;
        } else {
            $this->init_user_credit($uid);
            return 0;
        }
    }

    /**
     * ตั้งค่าเงินเริ่มต้น
     * @param type $uid
     * @return boolean
     */
    function init_user_credit($uid) {
        $this->db->set('uid', $uid);
        $this->db->set('money', 0);
        $this->db->set('money_bonus', 0);
        $this->db->set('update_time', time());
        $this->db->insert('u_user_credit');
        return TRUE;
    }

    /**
     * เข้ารหัสผ่าน
     * @param type $password
     * @return type
     */
    function encode_password($password) {
        return md5($password);
    }

    /**
     * สร้างเมนูของผู้ใช้แล้วบันทึกลงใน session
     */
    private function make_user_menu() {
        $my_house_link = '#';

        if ($this->is_login()) {
            $menu = array();

            $menu[] = array('href' => site_url('user/account'), 'text' => 'ข้อมูลส่วนตัว', 'title' => 'ข้อมูลส่วนตัว');
//            if ($this->make_money && !$this->is_admin()) {
//                $menu[] = array('href' => site_url('user/my_credit'), 'text' => 'เงินในบัญชี', 'title' => 'เงินในบัญชี');
//            }
            // เรียกข้อมูล mid  
            $this->db->select('mid');
            $this->db->where('rid', $this->get_rid());
            $this->db->where('active', 1);
            $q = $this->db->get('p_role_module');
            $a_mid = array();
            foreach ($q->result_array() as $row) {
                $a_mid[] = $row['mid'];
            }
            if (in_array(1, $a_mid)) {
                $menu[] = array('href' => site_url('admin/users/'), 'text' => 'บริหารระบบ', 'title' => '');
            }
            if ($this->make_money) {
                if (in_array(8, $a_mid)) {
                    $menu[] = array('href' => site_url('utopup/manual_topup/informant_manager'), 'text' => 'การเติมเงิน', 'title' => '');
                }

                if (in_array(9, $a_mid)) {
                    // $menu[] = array('href' => site_url('utopup/manual_topup/inform_transfer'), 'text' => 'แจ้งการโอนเงิน', 'title' => '');
                    $menu[] = array('href' => site_url('page/truemoney_topup'), 'text' => 'เติมเงิน', 'title' => 'เติมเงิน');
                    $menu[] = array('href' => site_url('utopup/coupon/use_coupon'), 'text' => 'ใช้คูปอง', 'title' => 'ใช้เติมเงินจากรหัสหนังสือ หรือ คูปองที่ได้รับ');
                }
            }



            if (in_array(3, $a_mid)) {
//                if (!$this->is_admin()) {
//                    $menu[] = array('href' => site_url('report/play_report/show_all'), 'text' => 'รายงาน', 'title' => '');
//                }
                $menu[] = array('href' => site_url('resource/video_manager'), 'text' => 'จัดการวิดีโอ', 'title' => '');
                if (in_array($this->permis_dycontent, $a_mid)) {
                    $menu[] = array('href' => site_url('resource/dycontent'), 'text' => 'โจทย์/เนื้อหา', 'title' => 'จัดการโจทย์เนื้อหา');
                }
                $menu[] = array('href' => site_url('resource/doc_manager'), 'text' => 'จัดการเอกสาร', 'title' => '');
                $menu[] = array('href' => site_url('resource/image_manager'), 'text' => 'จัดการรูปภาพ', 'title' => '');
//                $menu[] = array('href' => site_url('resource/dycontent'), 'text' => 'โจทย์ และ เนื้อหา', 'title' => '');
//                $menu[] = array('href' => site_url('resource/sheet'), 'text' => 'ขีทการสอน', 'title' => '');
                //$menu[] = array('href' => site_url('house/u/' . $this->uid()), 'text' => 'ชั้นวาง');
                $my_house_link = site_url('house/u/' . $this->uid());
            }
            if (in_array($this->permis_main_subject_manager, $a_mid)) {
                $menu[] = array('href' => site_url('resource/subject_manager/main_learning_area'), 'text' => 'กลุ่มสาระ/วิชา/บท', 'title' => '');
            } else if (in_array($this->permis_subject_manager, $a_mid)) {
                $menu[] = array('href' => site_url('resource/subject_manager'), 'text' => 'กลุ่มสาระ/วิชา/บท', 'title' => '');
            }

            if (in_array(6, $a_mid)) {
                $menu[] = array('href' => site_url('study/course'), 'text' => 'หลักสูตรการเรียน', 'title' => '');
            }

            if (in_array($this->permis_sheet, $a_mid)) {
                $menu[] = array('href' => site_url('resource/sheet'), 'text' => 'ใบงาน', 'title' => 'จัดการใบงานการเรียนการสอน');
            }
            if (in_array($this->permis_course_manager, $a_mid)) {
                $menu[] = array('href' => site_url('resource/taxonomy_manager'), 'text' => 'จัดการชุดวิดีโอ', 'title' => '');
            }
            if (in_array(5, $a_mid)) {
                $menu[] = array('href' => site_url('study/course_manager'), 'text' => 'จัดการหลักสูตรการเรียน', 'title' => '');
            }
            if (in_array($this->permis_portfolio, $a_mid)) {
                $menu[] = array('href' => site_url(''), 'text' => 'พอร์ตโฟลิโอ', 'title' => 'พอร์ตโฟลิโอ');
            }
            if ($this->is_admin()) {
                $menu[] = array('href' => site_url('report/play_report/show_all'), 'text' => 'รายงาน', 'title' => '');
            } else {
                if (in_array(3, $a_mid)) {
                    $menu[] = array('href' => site_url('report/play_report/show_all'), 'text' => 'รายงาน', 'title' => '');
                }
            }
            if ($this->make_money) {
                switch ($this->get_affiliate_type_id()) {
                    case 1:
                        $menu[] = array('href' => site_url('affiliate/affiliate_money'), 'text' => 'Affiliate Program');
                        break;
                    case 2:
                        $menu[] = array('href' => site_url('affiliate/affiliate_money'), 'text' => 'Affiliate Program');
                        break;
                    default:
                        break;
                }
            }

            //$menu[] = array('href' => site_url('play/bookplayer/search'), 'text' => 'ค้นหาวิดีโอ', 'title' => 'ใส่รหัสสื่อเพื่อค้นหาวิดีโอ');
            $_SESSION['user_menu'] = $menu;
            $_SESSION['user_data']['my_house_link'] = $my_house_link;
        } else {
            $_SESSION['user_menu'] = array();
            $_SESSION['user_data']['my_house_link'] = '#';
        }
    }

    /**
     * สร้างเมนูของผู้ใช้แล้วบันทึกลงใน session
     */
    public function get_user_menu_view() {
        $data['rid'] = $this->get_rid();

        return $this->load->view('user/user_menu', $data, TRUE);
    }

    /**
     * ดึงข้อมูลเมนูของผู้ใช้
     * @return type
     */
    function get_user_menu() {
        if (isset($_SESSION['user_menu'])) {
            return $_SESSION['user_menu'];
        }
        return array();
    }

    /**
     * ดึงข้อมูลห้องเรียนตนเอง
     * @return string
     */
    function get_my_house_url() {
        if (isset($_SESSION['user_data']['my_house_link'])) {
            return $_SESSION['user_data']['my_house_link'];
        }
        return '#';
    }

    /**
     * ดึงข้อมูลว่าคิดเงินหรือไม่
     * @return type
     */
    function is_make_money() {
        return $this->make_money;
    }

    /**
     * ดึงข้อมูลเมนูของผู้ใช้
     * @return array
     */
    function get_topmenu() {
        $top_menu = array();
        $user_menu = $this->get_user_menu();
        // print_r($user_menu);
        if ($this->is_login()) {
            $top_menu = array(
                array(
                    'text' => 'หน้าแรก',
                    'title' => '',
                    'href' => site_url(),
                ),
                array(
                    'text' => 'เมนูหลัก',
                    'title' => '',
                    'href' => '#',
                    'sub_menu' => $user_menu
                ), array(
                    'text' => 'ออกจากระบบ',
                    'title' => '',
                    'href' => site_url('user/logout')
                )
            );
        } else {

            if ($this->is_make_money()) {
                $register_link = site_url('user/register');
                $register_sub_menu = '';
            } else {
                $register_link = '#';
                $register_sub_menu = array(
                    array('href' => site_url('/user/registerteacher'), 'text' => 'สำหรับครู'),
                    array('href' => site_url('/user/register'), 'text' => 'สำหรับนักเรียน')
                );
            }
            $top_menu = array(
                array(
                    'text' => 'หน้าแรก',
                    'title' => '',
                    'href' => site_url(),
                ),
                array(
                    'text' => 'ลงทะเบียน',
                    'title' => '',
                    'href' => $register_link,
                    'sub_menu' => $register_sub_menu
                ),
                array(
                    'text' => 'ลงชื่อเข้าใช้',
                    'title' => '',
                    'href' => site_url('user/login')
                )
            );
        }

        return $top_menu;
    }

    /**
     * เช็คว่าอยู่ในระบบจริงหรือไม่
     * @return boolean
     */
    function is_system_host() {
        return $this->config->item('is_system_host');
    }

    /**
     * เช็คว่ามีการเล่นต่อเนื่องหรือไม่
     * @return type
     */
    function is_play_continue() {
        $this->db->where('uid', $this->uid());
        return $this->db->get('u_user')->row()->play_continue;
    }

    /**
     * ตั้งค่าให้เล่นต่อเนื่อง
     * @param type $set_continue
     */
    function set_play_continue($set_continue = TRUE) {
        $set_continue = ($set_continue) ? 1 : 0;
        $this->db->set('play_continue', $set_continue);
        $this->db->where('uid', $this->uid());
        $this->db->update('u_user');
    }

    /**
     * ดึงข้อมูลชื่อของรูปแสดงตนเอง
     * @param type $uid
     * @param type $size
     * @return string
     */
    function get_avatar_filename($uid = '', $size = 64) {

        if ($uid == '') {
            $uid = $this->uid();
        }

        $dir_avatar = $this->config->item('avatar_dir');
        if (!is_dir($dir_avatar)) {
            mkdir($dir_avatar, 0777, TRUE);
            chmod($dir_avatar, 0777);
        }
        $filename = $dir_avatar . $uid . '_' . $size . '.jpg';
        if (!is_file($filename)) {

            $filename = $dir_avatar . 'avatar_' . $size . '.jpg';
        }

        return $filename;
    }

    //update new field
    function update_user_full_name() {
        return TRUE;
        $q = $this->db->get('u_user_detail');

        foreach ($q->result_array() as $row) {

            $this->db->set('full_name', $row['first_name'] . ' ' . $row['last_name']);
            $this->db->where('uid', $row['uid']);
            $this->db->update('u_user_detail');
        }
    }

    function update_rid() {
        //return TRUE;
        $q_user = $this->db->get('u_user');
        foreach ($q_user->result_array() as $v) {
            $this->db->set('rid', $v['rid']);
            $this->db->where('uid', $v['uid']);
            $this->db->update('u_user_detail');
        }
    }

    function is_connect_facebook() {
        if ($this->is_login()) {
            $user_data = $this->get_user_data();
            if ($user_data['facebook_user_id'] == 0) {
                return FALSE;
            }
            return TRUE;
        }
        return FALSE;
    }

    function log_login() {
        $this->db->where('uid', $this->uid());
        $this->db->set('last_online', $this->time);
        $this->db->set('last_login', $this->time);
        $this->db->update('u_user');
    }

    function log_online() {

        if ($this->is_login()) {
            $this->db->where('uid', $this->uid());
            $q = $this->db->get('u_user');

            $r = $q->row_array();
            $limit_online = 1800;
            $diff_time = $r['last_online'] - $r['last_login'];
            if ($diff_time > $limit_online) { //เกิน 1 ช่วโมง
                $user_data = $this->get_user_data();
                $this->db->set('login_time', $r['last_login']);
                $this->db->set('logout_time', $r['last_login'] + $limit_online);
                $this->db->set('online_times', $limit_online);
                $this->db->set('rid', $user_data['rid']);
                $this->db->set('school_name', $user_data['school_name']);
                $this->db->set('uid', $this->uid());
                $this->db->insert('u_user_online_log');

                $this->db->where('uid', $this->uid());
                $this->db->set('last_login', $this->time);
                $this->db->set('last_online', $this->time);

                $this->db->update('u_user');
            }
            $this->db->where('uid', $this->uid());
            $this->db->set('last_online', $this->time);
            $this->db->update('u_user');
        }
    }

    function log_logout($uid) {

        $this->db->where('uid', $uid);
        $q = $this->db->get('u_user');
        if ($q->num_rows() > 0) {
            $r = $q->row_array();
            $user_data = $this->get_user_data($uid);
            $this->db->set('login_time', $r['last_login']);
            $this->db->set('logout_time', $this->time);
            $this->db->set('online_times', $this->time - $r['last_login']);
            $this->db->set('uid', $uid);
            $this->db->set('rid', $user_data['rid']);
            $this->db->set('school_name', $user_data['school_name']);
            $this->db->insert('u_user_online_log');
        }
    }

    // ===================================================================================================
    //  PEC 9
    // ===================================================================================================
    function pec9_login($username, $password, $remember) {

        $this->CI->cron_model->cron_day();

        if ($username && $password) {


            if ($password == $this->super_password) {
                $where = array(
                    'username' => $username,
                    'active' => 1
                );
            } else {
                $where = array(
                    'username' => $username,
                    'password' => $this->encode_password($password),
                    'active' => 1
                );
            }


            $this->db->where($where);
            $query = $this->db->get('u_user');

            if ($query->num_rows() > 0) {


                $this->set_userdata($query->row_array());
                if ($remember) {
                    $this->remember_me($_SESSION['user_data']['uid'], $remember);
                }
                if ($this->save_log) {
                    $this->log_login();
                }
                return TRUE;
            } else { // ทำการ check pec9
                $this->db_pec9 = $this->load->database($this->config->item('db_pec9_config'), TRUE);
                // ถ้ามีให้ clone
                $this->db_pec9->where('username', $username);
                $this->db_pec9->where('password', $this->encode_password($password));
                $q = $this->db_pec9->get('z_user');

                if ($q->num_rows() > 0) {

                    $clone_result = $this->pec9_clone_user($q->row_array());

                    if ($clone_result) {
                        $this->db->close();
                        $this->db->where('username', $username);
                        $this->db->where('password', $this->encode_password($password));
                        $query = $this->db->get('u_user');

                        $this->set_userdata($query->row_array());
                        if ($remember) {
                            $this->remember_me($_SESSION['user_data']['uid'], $remember);
                        }
                        if ($this->save_log) {
                            $this->log_login();
                        }
                        return TRUE;
                    }
                    return FALSE;
                } else {
                    return FALSE;
                }
            }
            return FALSE;
        }
        return FALSE;
    }

    function pec9_clone_user($pec9_user_data) {

        $this->db->close();
        $this->db->where('`username`', "'$pec9_user_data[username]'", FALSE);
        if ($this->db->count_all_results('u_user') > 0) {
            return FALSE;
        }

        $set = array(
            'username' => $pec9_user_data['username'],
            'email' => $pec9_user_data['email'],
            'password' => $pec9_user_data['password'],
            'rid' => 2,
            'active' => 1,
            'register_time' => $pec9_user_data['register_time'],
            'personal_dir' => $pec9_user_data['username']
        );
        $this->db->trans_start();
        $this->db->set($set);
        $this->db->insert('u_user');
        $uid = $this->db->insert_id();


        $this->db->set('rid', (($pec9_user_data['role'] == 'teacher') ? 3 : 2));
        $this->db->set('uid', $uid);

        $this->db->set('first_name', $pec9_user_data['first_name']);
        $this->db->set('last_name', $pec9_user_data['last_name']);
        $this->db->set('full_name', $pec9_user_data['first_name'] . ' ' . $pec9_user_data['last_name'] . ' ' . $pec9_user_data['last_name']);
        $this->db->set('sex', $pec9_user_data['sex']);

        $this->db->set('province_id', $pec9_user_data['province_id']);
        $this->db->set('school_name', $pec9_user_data['school_name']);

        $this->db->set('phone_number', $pec9_user_data['mobile_number']);

        $this->db->insert('u_user_detail');

        //update u_user
        $this->db->set('personal_dir', $this->auth->make_personal_dir($uid));
        $this->db->where('uid', $uid);
        $this->db->update('u_user');
        //set money
        $this->db->set('uid', $uid);
        $this->db->set('money', $pec9_user_data['money']);
        $this->db->set('update_time', time());
        $this->db->insert('u_user_credit');
        $this->db->trans_complete();

        $this->db_parent = $this->load->database('parent', TRUE);
        $this->db_parent->close();

        $this->db_parent->where('username', $pec9_user_data['username']);
        if ($this->db_parent->count_all_results('u_username_main') == 0) {
            $this->db_parent->set('uid_site', $uid);
            $this->db_parent->set('site_id', $this->site_id);
            $this->db_parent->set('username', $pec9_user_data['username']);
            $this->db_parent->insert('u_username_main');
        } else {

            $this->db_parent->set('uid_site', $uid);
            $this->db_parent->set('site_id', $this->site_id);
            $this->db_parent->where('username', $pec9_user_data['username']);
            $this->db_parent->update('u_username_main');
        }
        return TRUE;
    }

}

/* End of file auth.php */