<?php

/**
 * โปรแกรมสำหรับการสั่งซื้อ Service ของลูกค้า
 * @category User module
 * @author lojorider
 * @property disk_quota_service_model $disk_quota_service_model
 */
class disk_quota_service extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('service/disk_quota_service_model');
        $this->load->model('admin/admin_menu_model');
        //$this->make_money = $this->config->item('make_money');
        $this->load->helper('form');
    }

    function index() {
        $data['title'] = 'พื้นที่ให้บริการ';
        $data['main_side_menu'] = $this->admin_menu_model->main_side_menu('disk_quota_service');
        $data['grid_menu'] = array(
            array('url' => site_url('service/disk_quota_service/template_disk_quota'), 'title' => 'แม่แบบ', 'extra' => ''),
            array('url' => site_url('service/disk_quota_service/add_disk_quota'), 'title' => 'เพิ่มพื้นที่ผู้ใช้', 'extra' => ''),
        );
        $this->template->script_var(array(
            'ajax_grid_url' => site_url('service/disk_quota_service/ajax_grid_user_disk_quota'),
        ));
        $this->template->write_view('service/disk_quota_service/disk_quota_grid', $data);
        $this->template->application_script('service/disk_quota_service/disk_quota_grid.js');
        $this->template->load_flexgrid();
        $this->template->render();
    }

    function ajax_grid_user_disk_quota() {
        $a = $this->disk_quota_service_model->disk_quota_find_all($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query', TRUE), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE));
        echo json_encode($a);
    }

    function add_disk_quota() {
        $form_data = array();
        $data = array(
            'title' => 'เพิ่มผู้รับบริการ',
            'form_action' => site_url('service/disk_quota_service/do_add_disk_quota'),
            'cancel_link' => site_url('service/disk_quota_service'),
            'form_data' => $form_data,
            'paymoney_options' => $this->disk_quota_service_model->get_paymoney_options(),
            'disk_quota_template_options' => $this->disk_quota_service_model->get_disk_quota_template_options()
        );
        $this->template->script_var(array(
            'ajax_search_user_url' => site_url('core/ajax_search/user_not_have_disk_quota'),
            'ajax_get_template_disk_quota_url' => site_url('service/disk_quota_service/ajax_get_template_disk_quota')
        ));
        $this->template->write_view('service/disk_quota_service/add_disk_quota', $data);
        //$this->template->application_script('service/disk_quota_service/add_disk_quota');
        $this->template->load_typeonly();
        $this->template->render();
    }

    function do_add_disk_quota() {
        if ($this->disk_quota_service_model->add_disk_quota($this->input->post('data'))) {
            $data = array(
                'time' => 0,
                'url' => site_url('service/disk_quota_service'),
                'heading' => 'บันทึกข้อมูลเสร็จสิ้น',
                'message' => '<p>บันทึกข้อมูลเสร็จสิ้น</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $data = array(
                'time' => 0,
                'url' => site_url('service/disk_quota_service'),
                'heading' => 'ไม่สามารถบันทึกข้อมูลได้',
                'message' => '<p>ไม่สามารถบันทึกข้อมูลได้</p>'
            );
            $this->load->view('refresh_page', $data);
        }
    }

    function edit_disk_quota($dq_id) {
        $form_data = $this->disk_quota_service_model->get_disk_quota_data($dq_id);
        $data = array(
            'title' => 'ปก้ไขผู้รับบริการ',
            'form_action' => site_url('service/disk_quota_service/do_edit_disk_quota'),
            'cancel_link' => site_url('service/disk_quota_service'),
            'form_data' => $form_data,
            'is_active_options' => $this->disk_quota_service_model->get_is_active_options(),
        );
        $this->template->script_var(array(
            'ajax_search_user_url' => site_url('core/ajax_search/user_not_have_disk_quota'),
            'ajax_get_template_disk_quota_url' => site_url('service/disk_quota_service/ajax_get_template_disk_quota')
        ));
        $this->template->write_view('service/disk_quota_service/edit_disk_quota', $data);
        //$this->template->application_script('service/disk_quota_service/add_disk_quota');
        $this->template->load_typeonly();
        $this->template->render();
    }

    function do_edit_disk_quota() {
        if ($this->disk_quota_service_model->edit_disk_quota($this->input->post('data'))) {
            $data = array(
                'time' => 0,
                'url' => site_url('service/disk_quota_service'),
                'heading' => 'บันทึกข้อมูลเสร็จสิ้น',
                'message' => '<p>บันทึกข้อมูลเสร็จสิ้น</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $data = array(
                'time' => 0,
                'url' => site_url('service/disk_quota_service'),
                'heading' => 'ไม่สามารถบันทึกข้อมูลได้',
                'message' => '<p>ไม่สามารถบันทึกข้อมูลได้</p>'
            );
            $this->load->view('refresh_page', $data);
        }
    }

    function service_billing() {
        
    }

    function ajax_grid_user_billing() {
        
    }

    function renew_disk_quota() {
        
    }

    //template ------------------------
    function template_disk_quota() {
        $data['title'] = 'พื้นที่ให้บริการ';
        $data['main_side_menu'] = $this->admin_menu_model->main_side_menu('disk_quota_service');
        $data['grid_menu'] = array(
            array('url' => site_url('service/disk_quota_service/'), 'title' => '< จัดการพื้นที่ผู้ใช้', 'extra' => ''),
            array('url' => site_url('service/disk_quota_service/add_template_disk_quota'), 'title' => 'เพิ่มแม่แบบ', 'extra' => ''),
        );
        $this->template->script_var(array(
            'ajax_grid_url' => site_url('service/disk_quota_service/ajax_grid_template_disk_quota'),
        ));
        $this->template->write_view('service/disk_quota_service/template_disk_quota_grid', $data);
        $this->template->application_script('service/disk_quota_service/template_disk_quota_grid.js');
        $this->template->load_flexgrid();
        $this->template->render();
    }

    function ajax_grid_template_disk_quota() {
        $a = $this->disk_quota_service_model->template_disk_quota_find_all($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query', TRUE), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE));
        echo json_encode($a);
    }

    function add_template_disk_quota() {
        $form_data = $this->disk_quota_service_model->get_template_disk_quota_data();
        $data = array(
            'title' => 'เพิ่มแม่แบบ',
            'form_action' => site_url('service/disk_quota_service/do_save_template_disk_quota'),
            'cancel_link' => site_url('service/disk_quota_service/template_disk_quota'),
            'form_data' => $form_data,
        );
        $data['script_var'] = array(
            'ajax_search_user_url' => site_url('utopup/manual_topup/ajax_search_user')
        );
        $this->template->write_view('service/disk_quota_service/input_template_disk_quota', $data);

        $this->template->load_typeonly();
        $this->template->render();
    }

    function edit_template_disk_quota($dqt_id) {
        $form_data = $this->disk_quota_service_model->get_template_disk_quota_data($dqt_id);
        $data = array(
            'title' => 'แก้ไขแม่แบบ',
            'form_action' => site_url('service/disk_quota_service/do_save_template_disk_quota'),
            'cancel_link' => site_url('service/disk_quota_service/template_disk_quota'),
            'form_data' => $form_data,
        );
        $data['script_var'] = array(
            'ajax_search_user_url' => site_url('utopup/manual_topup/ajax_search_user')
        );
        $this->template->write_view('service/disk_quota_service/input_template_disk_quota', $data);

        $this->template->load_typeonly();
        $this->template->render();
    }

    function do_save_template_disk_quota() {
        if ($this->disk_quota_service_model->save_template_disk_quota($this->input->post('data'))) {
            $data = array(
                'time' => 0,
                'url' => site_url('service/disk_quota_service/template_disk_quota'),
                'heading' => 'บันทึกข้อมูลเสร็จสิ้น',
                'message' => '<p>บันทึกข้อมูลเสร็จสิ้น</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $data = array(
                'time' => 0,
                'url' => site_url('service/disk_quota_service/template_disk_quota'),
                'heading' => 'บันทึกข้อมูลเสร็จสิ้น',
                'message' => '<p>บันทึกข้อมูลเสร็จสิ้น</p>'
            );
            $this->load->view('refresh_page', $data);
        }
    }

    function delete_template_disk_quota($dqt_id) {
        if ($this->disk_quota_service_model->delete_template_disk_quota($dqt_id)) {
            $data = array(
                'time' => 0,
                'url' => site_url('service/disk_quota_service/template_disk_quota'),
                'heading' => 'ลบข้อมูลเสร็จสิ้น',
                'message' => '<p>ลบข้อมูลเสร็จสิ้น</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $data = array(
                'time' => 0,
                'url' => site_url('service/disk_quota_service/template_disk_quota'),
                'heading' => 'ลบข้อมูลเสร็จสิ้น',
                'message' => '<p>ลบข้อมูลเสร็จสิ้น</p>'
            );
            $this->load->view('refresh_page', $data);
        }
    }

    function ajax_get_template_disk_quota() {
        $dqt_id = $this->input->post('dqt_id');
        $a = $this->disk_quota_service_model->get_template_disk_quota_data($dqt_id);
        echo json_encode($a);
    }

}