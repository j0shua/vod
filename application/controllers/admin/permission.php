<?php

/**
 * Description of role
 *
 * @author lojorider
 * @property permission_model $permission_model
 */
class permission extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth->access_limit($this->auth->permis_permission);
        $this->load->model('admin/permission_model');
        $this->load->model('admin/users_model');
        $this->load->model('admin/admin_menu_model');
    }

    function index() {

        $this->load->helper('form');
        $data['main_title'] = 'บริหารระบบ';
        $data['title'] = 'สิทธิการใช้';
        $menu_data['active'] = 'permission';
        $data['main_side_menu'] = $this->admin_menu_model->main_side_menu('permission');
        $data['permission'] = $this->permission_model->permission_data();
        $data['form_action'] = site_url('admin/permission/save');
        $this->template->write_view('admin/permission/main_table', $data);
        $this->template->render();
    }

    function save() {
        $this->permission_model->save($this->input->post('data'));
        redirect('admin/permission');
    }

}