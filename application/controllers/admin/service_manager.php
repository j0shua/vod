<?php

/**
 * โปรแกรมสำหรับการตอบรับการซื้อ Service ของ Admin
 * @category Admin module
 * @author lojorider
 * @property service_manager_model $service_manager_model
 * @property admin_menu_model $admin_menu_model
 */
class service_manager extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/admin_menu_model');
    }

    function index() {
        $data['title'] = 'รายการบริการ';
        $data['grid_menu'] = array(
                // array('url' => site_url('resource/subject_manager/add_main_subject'), 'title' => 'เพิ่มกลุ่มสาระ', 'extra' => ''),
        );
        $data['main_side_menu'] = $this->admin_menu_model->main_side_menu('service_manager');
        $this->template->write_view('admin/service_manager/main_grid', $data);
        $this->template->application_script('admin/service_manager/main_grid.js');
        $this->template->script_var(
                array(
                    'ajax_grid_url' => site_url('admin/service_manager/ajax_service_list')
                )
        );
        $this->template->load_flexgrid();
        $this->template->render();
    }

    function ajax_service_list() {
        $a = $this->service_manager_model->find_all_service($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query', TRUE), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE));
        echo json_encode($a);
    }

    function user_service() {
        $data['title'] = 'บริการที่ขาย';
        $data['grid_menu'] = array(
                // array('url' => site_url('resource/subject_manager/add_main_subject'), 'title' => 'เพิ่มกลุ่มสาระ', 'extra' => ''),
        );
        $data['main_side_menu'] = $this->admin_menu_model->main_side_menu('user_service');
        $this->template->write_view('admin/service_manager/main_grid', $data);
        $this->template->application_script('admin/service_manager/main_grid.js');
        $this->template->script_var(
                array(
                    'ajax_grid_url' => site_url('admin/service_manager/ajax_service_list')
                )
        );
        $this->template->load_flexgrid();
        $this->template->render();
    }

    function ajax_user_service() {
        $a = $this->service_manager_model->find_all_service($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query', TRUE), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE));
        echo json_encode($a);
    }

}