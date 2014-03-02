<?php

/**
 * Description of user
 *
 * @author lojorider
 * @property user_model $user_model
 * @property backup_model $backup_model
 * @property ddoption_model $ddoption_model
 * @property fb_model $fb_model
 */
class user extends CI_Controller {

    var $username_field;

    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('core/ddoption_model');
        $this->load->helper('form');
        $this->username_field = $this->config->item('username_field');
    }

//==============================================================================    
// หน้า Login
//==============================================================================    
    public function index() {

        if ($this->auth->is_login()) {
            $this->template->render();
        } else {
            redirect('user/login');
        }
    }

//==============================================================================    
// Login section
//==============================================================================   

    function login() {
        if ($this->auth->is_login()) {
            redirect();
        }
        $this->template->application_script('user/login_form.js');
        $this->template->load_typeonly();
        $data = array(
            'form_title' => 'ลงชื่อเข้าใช้งาน',
            'form_action' => site_url('user/do_login'),
            'forget_pass_url' => site_url('user/forget_pass'),
            'referer_url' => @$_SERVER['HTTP_REFERER']
        );
        $this->template->write_view('user/login_form', $data);
        $this->template->render();
    }

    public function do_login() {
     
        $remember = ($this->input->post('remember_me')) ? TRUE : FALSE;


        if ($this->auth->login($this->input->post('username', TRUE), $this->input->post('password', TRUE), $remember)) {

            redirect($this->input->post('referer_url'));
        } else {
            $data = array(
                'time' => 5,
                'url' => site_url(),
                'heading' => 'เกิดข้อผิดพลาด!',
                'message' => '<p class="error-text">ลงชื่อเข้าใช้ไม่ได้</p><p>ไม่มีชื่อเข้าใช้นี้ หรือ รหัสผ่านไม่ถูกต้อง</p>'
            );
            $this->load->view('refresh_page', $data);
        }
    }

    public function logout() {
        $this->auth->logout();
        $data = array(
            'time' => 0,
            'url' => site_url(),
            'heading' => 'ออกจากระบบ',
            'message' => '<p>ออกจากระบบ</p>'
        );
        $this->load->view('refresh_page', $data);
    }

    public function forget_pass() {
        $data = array(
            'form_action' => site_url('user/do_forget_pass'),
            'form_error' => $this->session->flashdata('form_error')
        );
        $this->template->write_view('user/forget_pass_form', $data);
        $this->template->render();
    }

    public function do_forget_pass() {
        if ($this->user_model->forget_password($this->input->post('email', TRUE))) {
            $data = array(
                'time' => 5,
                'url' => site_url(),
                'heading' => 'ส่งข้อมูลสำหรับการเปลี่ยน รหัสผ่าน ',
                'message' => '<p>ส่งข้อมูลสำหรับการเปลี่ยนรหัสผ่านไปยัง อีเมล์ของคุณ เสร็จสิ้น</p><p>กรุณาเข้าตรวจสอบ  อีเมล์ของคุณ</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $data = array(
                'time' => 15,
                'url' => site_url('user/forget_pass'),
                'heading' => 'เกิดข้อผิดพลาด!',
                'message' => '<p><span class="error-text">อีเมล์ของคุณไม่มีอยู่ในระบบ หรือ คุณกรอกอีเมล์ไม่ถูกต้อง</span></p>'
            );
            $this->load->view('refresh_page', $data);
        }
    }

    public function reset_pass($uid, $session_id) {
        $this->template->application_script('user/reset_pass_form.js');
        $this->template->load_typeonly();
        $data = array(
            'form_action' => site_url('user/do_reset_pass/' . $uid . '/' . $session_id)
        );
        $this->template->write_view('user/reset_pass_form', $data);
        $this->template->render();
    }

    public function do_reset_pass($uid, $session_id) {
        if ($this->user_model->reset_pass($uid, $session_id, $this->input->post('password', TRUE))) {
            $data = array(
                'time' => 1,
                'url' => site_url(),
                'heading' => 'เปลี่ยนรหัสผ่าน เสร็จสิ้น',
                'message' => '<p>เปลี่ยนรหัสผ่าน เสร็จสิ้น</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $data = array(
                'time' => 1,
                'url' => site_url(),
                'heading' => 'เกิดข้อผิดพลาด',
                'message' => '<p class="error-text">เปลี่ยนรหัสผ่านไม่ได้</p>'
            );
            $this->load->view('refresh_page', $data);
        }
    }

//==============================================================================    
// Account section
//==============================================================================   
    public function account() {
        $this->load->helper('form');
        $data = array(
            'form_data' => $this->user_model->get_account_data(),
            'edit_profile_url' => site_url('user/edit_profile'),
            'edit_email_url' => site_url('user/edit_email'),
            'edit_password_url' => site_url('user/edit_password'),
            'edit_bank_account_url' => site_url('user/bank_account'),
            'swf_upload_avatar_url' => site_url('user/swf_upload_avatar')
        );

        $this->template->load_jquery_fancybox();
        $this->template->write_view('user/account', $data);
        $this->template->render();
    }

    public function edit_profile() {

        $this->template->application_script('user/profile_edit_form.js');
        $this->load->helper('form');
        $data = array(
            'form_action' => site_url('user/do_edit_profile'),
            'cancel_url' => site_url('user/account'),
            'form_data' => $this->user_model->get_profile_form_data(),
            'sex_options' => $this->ddoption_model->get_sex_options(),
            'province_options' => $this->ddoption_model->get_province_options(),
            'degree_id_options' => $this->ddoption_model->get_degree_id_options(),
            'school_name_options' => $this->ddoption_model->get_school_name_options(),
        );
        $this->template->script_var(
                array(
                    'ajax_school_name_url' => site_url('user/ajax_school_name')
                )
        );
        $this->template->load_typeonly();
        $this->template->write_view('user/profile_edit_form', $data);
        $this->template->render();
    }

    public function do_edit_profile() {
        if (!$this->user_model->save_profile($this->input->post('form_data'))) {
            redirect('user/edit_profile');
        } else {
            redirect('user/account');
        }
    }

    public function edit_email() {
        $this->template->application_script('user/email_edit_form.js');
        $this->load->helper('form');
        $data = array(
            'form_action' => site_url('user/do_edit_email'),
            'cancel_url' => site_url('user/account'),
            'form_data' => $this->user_model->get_email_form_data(),
            'sex_options' => $this->ddoption_model->get_sex_options(),
            'province_options' => $this->ddoption_model->get_province_options()
        );
        $this->template->write_view('user/email_edit_form', $data);
        $this->template->render();
    }

    public function do_edit_email() {
        if ($this->input->post('email') == $this->input->post('old_email')) {
            $data = array(
                'time' => 1,
                'url' => site_url('user/account'),
                'heading' => 'ไม่มีการเปลี่ยนแลง',
                'message' => '<p>ไม่มีการแก้ไข อีเมล์</p>'
            );
        } else {
            $result = $this->user_model->edit_email($this->input->post('email'));
            if ($result['success']) {
                $data = array(
                    'time' => 1,
                    'url' => site_url('user/account'),
                    'heading' => 'แก้ไขข้อมูลเสร็จสิ้น',
                    'message' => '<p>' . $result['message'] . '</p>'
                );
            } else {
                $data = array(
                    'time' => 1,
                    'url' => site_url('user/edit_email'),
                    'heading' => 'ไม่สามารถแก้ไขอีเมล์ได้',
                    'message' => $result['message']
                );
            }
        }

        $this->load->view('refresh_page', $data);
    }

    public function edit_password() {
        $this->template->application_script('user/password_edit_form.js');
        $this->load->helper('form');
        $data = array(
            'form_action' => site_url('user/do_edit_password'),
            'cancel_url' => site_url('user/account'),
        );
        $this->template->write_view('user/password_edit_form', $data);
        $this->template->render();
    }

    public function do_edit_password() {
        $result = $this->user_model->edit_password($this->input->post('old_password'), $this->input->post('new_password'));
        if ($result) {
            $data = array(
                'time' => 1,
                'url' => site_url('user/account'),
                'heading' => 'แก้ไขรหัสผ่านเสร็จแล้ว',
                'message' => '<p>แก้ไขรหัสผ่านเสร็จแล้ว</p>'
            );
        } else {
            $error = $this->user_model->get_form_error();
            $error = '<p>' . implode('</p><p>', $error) . '</p>';
            $data = array(
                'time' => 1,
                'url' => site_url('user/account'),
                'heading' => 'การแก้ไขผิดพลาด',
                'message' => $error
            );
        }

        $this->load->view('refresh_page', $data);
    }

    function personal_document($document_name) {
        $uid = $this->auth->uid();
        $personal_document_data = $this->user_model->get_personal_document_data($uid);
        $data['cancel_url'] = site_url('/user/account');
        $data['form_action'] = site_url('user/save_personal_document');
        $data['uid'] = $uid;
        switch ($document_name) {
            case 'identity_document':
                $data['title'] = 'รูปถ่ายบัตรข้าราชการ/บัตรประชาชน';
                $data['document_label'] = 'รูปถ่ายบัตรข้าราชการ/บัตรประชาชน';
                $data['document_name'] = 'identity_document';
                if ($personal_document_data['identity_document']) {
                    $data['image_url'] = site_url('ztatic/personal_document/' . $personal_document_data['identity_document']);
                } else {
                    $data['image_url'] = FALSE;
                }
                break;
            case 'educational_document':
                $data['title'] = 'รูปถ่ายใบรับรองวิชาชีพครู';
                $data['document_label'] = 'รูปถ่ายใบรับรองวิชาชีพครู';
                $data['document_name'] = 'educational_document';
                if ($personal_document_data['educational_document']) {
                    $data['image_url'] = site_url('ztatic/personal_document/' . $personal_document_data['educational_document']);
                } else {
                    $data['image_url'] = FALSE;
                }
                break;
            default:
                return;
                break;
        }
        $this->template->write_view('user/personal_document', $data);
        $this->template->render();
    }

    function save_personal_document() {
        $this->user_model->update_teacher_personal_document($this->input->post());
        redirect('user/account');
    }

    function my_money() {
        $data['money'] = $this->auth->money();
        $this->template->write_view('user/my_money', $data);
        $this->template->render();
    }

    function my_credit() {
        $data['money_bonus'] = $this->auth->money_bonus();
        $data['money'] = $this->auth->money();
        $this->template->write_view('user/my_credit', $data);
        $this->template->render();
    }

    function encode_password($password) {
        echo $this->auth->encode_password($password);
    }

//==============================================================================    
// ส่วนลงทะเบียน
//==============================================================================   
    function registerteacher() {

        $this->register(3);
    }

    function register($rid = 2) {

        if (is_array($this->session->flashdata('form_data'))) {
            $form_data = $this->session->flashdata('form_data');
        } else {
            $form_data = $this->user_model->get_form_data();
        }
        $form_data['rid'] = $rid;
        $form_title = 'สมัครสมาชิก';
        $is_student = TRUE;
        if (!$this->auth->is_make_money()) {
            switch ($rid) {
                case 2:
                    $form_title = 'สมัครสมาชิก สำหรับนักเรียน';
                    $view = 'user/register_form';
                    $this->template->application_script('user/register_form.js');
                    $this->template->application_script('user/register_form.css');
                    break;
                case 3:
                    $form_title = 'สมัครสมาชิก สำหรับครู';
                    $is_student = FALSE;
                    $view = 'user/teacher_register_form';
                    $this->template->application_script('user/teacher_register_form.js');
                    $this->template->application_script('user/teacher_register_form.css');
                    break;

                default:
                    break;
            }
        } else {
            switch ($rid) {
                case 2:
                    $form_title = 'สมัครสมาชิก สำหรับนักเรียน';
                    $view = 'user/register_form';
                    $this->template->application_script('user/register_form.js');
                    $this->template->application_script('user/register_form.css');
                    break;
                case 3:
                    $form_title = 'สมัครสมาชิก สำหรับครู';
                    $is_student = FALSE;
                    $view = 'user/teacher_register_form';
                    $this->template->application_script('user/teacher_register_form.js');
                    $this->template->application_script('user/teacher_register_form.css');
                    break;

                default:
                    break;
            }
        }
        $data = array(
            'form_title' => $form_title,
            'form_action' => site_url('user/do_register'),
            'form_error' => $this->session->flashdata('form_error'),
            'form_data' => $form_data,
            'sex_options' => $this->ddoption_model->get_sex_options(),
            'province_options' => $this->ddoption_model->get_province_options(),
            'degree_id_options' => $this->ddoption_model->get_degree_id_options(),
            'school_name_options' => $this->ddoption_model->get_school_name_options(),
            'password_length' => $this->user_model->password_length,
            'is_student' => $is_student,
            'username_field' => $this->username_field
        );
        $data['btn_help'] = array(
            'coupon_code' => site_url('page/h_reg_coupon_code')
        );
        $this->template->script_var(
                array(
                    'ajax_school_name_url' => site_url('user/ajax_school_name')
                )
        );


        $v_data = array_merge($data, $this->user_model->get_form_data());

        $this->template->write_view($view, $v_data);
        $this->template->load_typeonly();
        $this->template->render();
    }

    function do_register() {
        $form_data = $this->input->post('form_data', TRUE);
//        print_r($form_data);
//        exit();
        if (!$this->user_model->register($form_data)) {
            switch ($form_data['rid']) {
                case 2:
                    redirect('user/register');
                    break;
                case 3:
                    redirect('user/registerteacher');
                    break;
                default:
                    redirect('user/register');
                    break;
            }
        } else {
            switch ($form_data['rid']) {
                case 2:
                    $time = 3;
                    $heading = 'ลงทะเบียนเสร็จสิ้น';
                    $message = '<p>ลงทะเบียนเสร็จสิ้น</p><p>โปรดเข้าเช็คข้อมูลของคุณทาง email</p>';
                    break;
                case 3:
                    $time = 8;
                    $heading = 'ลงทะเบียนเสร็จสิ้น';
                    $message = '<p>ลงทะเบียนเสร็จสิ้น</p><p>โปรดรอการอนุมัติจากผู้ดูแลระบบ</p>';
                    break;
                default:
                    redirect('user/register');
                    break;
            }
            $data = array(
                'time' => 3,
                'url' => site_url(),
                'heading' => $heading,
                'message' => $message
            );
            $this->load->view('refresh_page', $data);
        }
    }

    function ajax_school_name() {
        $term = $this->input->get('term');
        $this->db->like('school_name', $term);
        $this->db->limit(20);
        $q = $this->db->get('f_school');
        $list = array();
        if ($q->num_rows() > 0) {
            foreach ($q->result_array() as $r) {
                $list[] = '{"id":"' . $r['school_id'] . '","label":"' . $r['school_name'] . '","value":"' . $r['school_name'] . '"}';
            }
            echo '[' . implode(',', $list) . ']';
        } else {
            echo '[]';
        }
    }

    function do_upload_avatar() {
        $result = $this->user_model->upload_avatar();
        if (!$result['success']) {
            $result['message'] = '<span class="error-text">' . $result['message'] . '</span>';
        }
        $data = array(
            'time' => 0,
            'url' => site_url('user/account'),
            'heading' => 'ผลการอัพโหลดรูปภาพ',
            'message' => '<p>' . $result['message'] . '</p>'
        );
        $this->load->view('refresh_page', $data);
    }

    function swf_upload_avatar() {
        $this->template->script_var(
                array(
                    'expressInstall_url' => base_url('files/swf/expressInstall.swf'),
                    'swf_url' => base_url('files/swf/edy_capture.swf?t=' . time()),
                    'save_url' => site_url('user/do_swf_upload_avatar/' . $this->auth->uid())
                )
        );
        $this->template->write_view('user/swf_upload_avatar');
        $this->template->load_swfobject();
        $this->template->temmplate_name('normal');
        $this->template->render();
    }

    function do_swf_upload_avatar($uid) {
        $result = $this->user_model->swf_upload_avatar($uid);
        echo $result['message'];
    }

    function register_fb() {
        $this->load->model('fb_model');

        if (!$this->input->get('code')) {
            if ($this->input->get('error')) {
                print_r($this->input->get());
            } else {
                echo("<script> top.location.href='" . $this->fb_model->get_call_back_url() . "'</script>");
            }
        } else {
            $user_profile = $this->fb_model->get_user_profile();
            $form_data = array(
                'email' => $user_profile['email'],
                'first_name' => $user_profile['first_name'],
                'last_name' => $user_profile['last_name'],
                'sex' => ($user_profile['gender'] == 'male') ? 'ชาย' : 'หญิง',
                'birthday' => '',
                'school_name' => '',
                'coupon_code' => 'coupon50',
                'degree_id' => '',
                'phone_number' => ''
            );

            $this->session->set_flashdata('form_data', $form_data);
            redirect('user/register');
        }
    }

    function bank_account($cancel_url = '') {
        if ($cancel_url == '') {
            $cancel_url = site_url('user/account');
        }
        $this->load->helper('form');
        $data = array(
            'form_data' => $this->user_model->get_account_data(),
            'form_action' => site_url('user/do_edit_earnings_account'),
            'cancel_url' => $cancel_url,
            'edit_profile_url' => site_url('user/edit_profile'),
            'edit_email_url' => site_url('user/edit_email'),
            'edit_password_url' => site_url('user/edit_password'),
            'swf_upload_avatar_url' => site_url('user/swf_upload_avatar'),
            'bank_options' => $this->ddoption_model->get_bank_options()
        );
        $this->template->load_jquery_fancybox();
        $this->template->write_view('user/earnings_account', $data);
        $this->template->render();
    }

    function earnings_account() {
        $this->bank_account(site_url('earnings/resource_earnings'));
    }

    function do_edit_earnings_account() {
        redirect($this->input->post('cancel_url'));
    }

}
