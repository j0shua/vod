<?php

/**
 * Description of users
 * การเข้าสูหน้าจอนี้จำเป็นต้อง Login ก่อนถึงจะเข้าได้
 * file path : admin/users.php
 * @author lojorider
 * @property users_model $users_model
 */
class users extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('admin/users_model');
        $this->load->model('core/ddoption_model');
        $this->load->model('admin/admin_menu_model');
        $this->load->helper('form');
    }

    /**
     * หน้าหลักของการจัดการ user 
     * แสดงออกมาเป็น Grid เริ่มต้นจะแสดงข้อมูลของ user ปกติ สามารถเลือกกรองได้ว่าเป็น admin หรือ ครูได้อีกทีหนึง
     */
    function index() {
        $this->auth->access_limit($this->auth->permis_users);
        $this->template->load_showloading();
        $this->template->load_flexgrid();
        if ($this->auth->make_money) {
            $this->template->application_script('admin/users/main_grid.js');
        } else {
            $this->template->application_script('admin/users/main_grid_freesys.js');
        }

        $data['main_title'] = 'บริหารระบบ';
        $data['title'] = 'จัดการผู้ใช้';
        $data['role_options'] = $this->users_model->get_role_options('ใดๆ', 1);
        $data['grid_menu'] = array(
            array('url' => site_url('admin/users/add/teacher'), 'title' => 'เพิ่มครู', 'extra' => ''),
            array('url' => site_url('admin/users/add/student'), 'title' => 'เพิ่มนักเรียน', 'extra' => ''),
        );
        $data['main_side_menu'] = $this->admin_menu_model->main_side_menu('users');
        $data['active_options'] = array('' => 'ใดๆ', '0' => 'ปิดการใช้งาน', '1' => 'เปิดการใช้งาน', '2' => 'รอการอนุมัติ');
        $this->template->write_view('admin/users/main_grid', $data);
        $this->template->render();
    }

    function ajax_users_table() {
        $a = $this->users_model->find_all($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query'), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE));
        echo json_encode($a);
    }

    /**
     * เพิ่มข้อมูลสมาชิก
     */
    function add($user_type) {
        
        switch ($user_type) {
            case 'teacher':
                $label_degree = 'สอนชั้น';
                $rid = 3;
                $title = 'เพิ่มครู';
                break;
            case 'student':
                $label_degree = 'เรียนชั้น';
                $rid = 2;
                $title = 'เพิ่มนักเรียน';
                break;

            default:
                exit();
                break;
        }
        $this->auth->access_limit($this->auth->permis_users);
        $this->template->load_typeonly();
        $this->template->application_script('admin/users/add_form.js');
        $form_data = $this->users_model->get_form_data();
        $form_data['rid'] = $rid;
        $data = array(
            'title' => $title,
            'username_field'=>$this->auth->username_field,
            'form_action' => site_url('admin/users/do_add/'.$user_type),
            'cancel_url' => site_url('admin/users'),
            'form_error' => $this->session->flashdata('form_error'),
            'form_data' => $form_data,
            'sex_options' => $this->ddoption_model->get_sex_options(),
            'province_options' => $this->ddoption_model->get_province_options(),
            'degree_id_options' => $this->ddoption_model->get_degree_id_options(),
            'active_options' => array('0' => 'ปิดการใช้งาน', '1' => 'เปิดการใช้งาน'),
            'label_degree' => $label_degree
        );
        $data['script_var'] = array(
            'ajax_school_name_url' => site_url('user/ajax_school_name')
        );

        $this->template->write_view('admin/users/add_form', $data);
        $this->template->render();
    }

    function do_add($user_type) {
        $this->auth->access_limit($this->auth->permis_users);
        $form_data = $this->input->post('form_data');
        if (!$this->users_model->add_user($form_data)) {
            if ($form_data['uid'] == '') {
                redirect('admin/users/add/'.$user_type);
            } else {
                redirect('admin/users/edit/' . $form_data['uid']);
            }
        } else {
            if ($form_data['uid'] == '') {
                $data = array(
                    'time' => 0,
                    'url' => site_url('admin/users'),
                    'heading' => 'เพิ่มสมาชิก',
                    'message' => '<p>เพิ่มสมาชิกเสร็จสิ้น</p>');
            } else {
                $data = array(
                    'time' => 0,
                    'url' => site_url('admin/users'),
                    'heading' => 'แก้ไขสมาชิก',
                    'message' => '<p>แก้ไขสมาชิกเสร็จสิ้น</p>');
            }
            
            //$this->load->view('refresh_page', $data);
        }
    }

    /**
     * แก้ไขข้อมูลสมาชิก
     * @param int $uid เลขที่สมาชิก in table: a_user
     */
    function edit($uid = '') {
        if ($uid == '') {
            redirect('/Page Not Found/', 'location', 301);
            exit();
        }
        $this->auth->access_limit($this->auth->permis_users);

        $this->template->load_typeonly();
        $this->template->application_script('admin/users/edit_form.js');
        $form_data = $this->users_model->get_account_data($uid);
        if ($form_data['active'] == 1 || $form_data['active'] == 0) {
            $active_options = array('0' => 'ปิดการใช้งาน', '1' => 'เปิดการใช้งาน');
        } else {
            $active_options = array('0' => 'ปิดการใช้งาน', '1' => 'เปิดการใช้งาน', '2' => 'รอการอนุมัติ');
        }

        $data = array(
            'form_action' => site_url('admin/users/do_edit'),
            'cancel_url' => site_url('admin/users'),
            'form_data' => $form_data,
            'sex_options' => $this->ddoption_model->get_sex_options(),
            'province_options' => $this->ddoption_model->get_province_options(),
            'degree_id_options' => $this->ddoption_model->get_degree_id_options(),
            'active_options' => $active_options
        );
        $data['script_var'] = array(
            'ajax_school_name_url' => site_url('user/ajax_school_name')
        );


        $this->template->write_view('admin/users/edit_form', $data);
        $this->template->render();
    }

    function do_edit() {
        $this->auth->access_limit($this->auth->permis_users);
        $form_data = $this->input->post('form_data');
        if (!$this->users_model->edit_user($form_data)) {
            if ($form_data['uid'] == '') {
                redirect('admin/users/add');
            } else {
                redirect('admin/users/edit/' . $form_data['uid']);
            }
        } else {
            if ($form_data['uid'] == '') {
                $data = array(
                    'time' => 0,
                    'url' => site_url('admin/users'),
                    'heading' => 'เพิ่มสมาชิก',
                    'message' => '<p>เพิ่มสมาชิกเสร็จสิ้น</p>');
            } else {
                $data = array(
                    'time' => 0,
                    'url' => site_url('admin/users'),
                    'heading' => 'แก้ไขสมาชิก',
                    'message' => '<p>แก้ไขสมาชิกเสร็จสิ้น</p>');
            }
            $this->load->view('refresh_page', $data);
        }
    }

    function detail($uid) {
        if ($uid == '') {
            //redirect('/Page Not Found/', 'location', 301);
            exit();
        }
//        if (!isset($_SERVER['HTTP_REFERER'])) {
//            $this->auth->access_limit($this->auth->permis_users);
//        } else {
//            $url_parm = parse_url($_SERVER['HTTP_REFERER']);
//            if ($url_parm['path'] != '/report/play_report/show_all') {
//                $this->auth->access_limit($this->auth->permis_users);
//            }
//            if ($uid == 1) {
//                $this->auth->access_limit($this->auth->permis_users);
//            }
//        }
        $can_access = $this->auth->can_access($this->auth->permis_users);
        if (!($this->users_model->is_my_course_student($uid) || $can_access)) {
            //redirect('/Page Not Found/', 'location', 301);
            exit();
        }

        $this->load->model('user_model');
        $data = array(
            'form_data' => $this->user_model->get_account_data($uid),
            'can_see_money' => $can_access
        );
        $this->template->write_view('admin/users/detail', $data);
        $this->template->render();
    }

    function edit_password($uid) {
        $this->template->application_script('admin/users/password_edit_form.js');
        $this->load->helper('form');
        $data = array(
            'form_action' => site_url('admin/users/do_edit_password'),
            'cancel_url' => site_url('admin/users'),
            'uid' => $uid
        );
        $this->template->write_view('admin/users/password_edit_form', $data);
        $this->template->render();
    }

    function do_edit_password() {
        $result = $this->users_model->edit_password($this->input->post('uid'), $this->input->post('password'));
        if ($result) {
            $data = array(
                'time' => 1,
                'url' => site_url('admin/users'),
                'heading' => 'แก้ไขรหัสผ่านเสร็จแล้ว',
                'message' => '<p>แก้ไขรหัสผ่านเสร็จแล้ว</p>'
            );
        } else {
            $error = $this->user_model->get_form_error();
            $error = '<p>' . implode('</p><p>', $error) . '</p>';
            $data = array(
                'time' => 1,
                'url' => site_url('admin/users'),
                'heading' => 'การแก้ไขผิดพลาด',
                'message' => $error
            );
        }

        $this->load->view('refresh_page', $data);
    }

    function gen_student() {
        $data = array(
            'title' => 'สร้างรายชื่อนักเรียน',
            'form_action' => site_url('admin/users/do_gen_student'),
            'cancel_link' => site_url('admin/users/')
        );
        $this->template->write_view('admin/users/gen_user', $data);
        $this->template->load_typeonly();
        $this->template->render();
    }

    function do_gen_student() {
        $pdata = $this->input->post();
        $this->users_model->gen_user(2, $pdata['prefix'], $pdata['total'], $pdata['password']);
        $data = array(
            'time' => 1,
            'url' => site_url('admin/users'),
            'heading' => 'เสร็จสิ้น',
            'message' => '<p>เสร็จสิ้น</p>'
        );
        $this->load->view('refresh_page', $data);
    }

    function gen_teacher() {
        $data = array(
            'title' => 'สร้างรายชื่อครู',
            'form_action' => site_url('admin/users/do_gen_teacher'),
            'cancel_link' => site_url('admin/users/')
        );
        $this->template->write_view('admin/users/gen_user', $data);
        $this->template->load_typeonly();
        $this->template->render();
    }

    function do_gen_teacher() {
        $pdata = $this->input->post();
        $this->users_model->gen_user(3, $pdata['prefix'], $pdata['total'], $pdata['password']);
        $data = array(
            'time' => 1,
            'url' => site_url('admin/users'),
            'heading' => 'เสร็จสิ้น',
            'message' => '<p>เสร็จสิ้น</p>'
        );
        $this->load->view('refresh_page', $data);
    }

}
