<?php

/**
 * Description of post_tutor
 *
 * @author lojorider
 * @property post_tutor_model $post_tutor_model
 */
class post_tutor extends CI_Controller {

    function __construct() {
        parent::__construct();
        if (!$this->auth->is_login()) {
            exit("Permission Denied.");
        }
        if (!$this->auth->can_access($this->auth->mid_resource_manager)) {
            exit("Permission Denied.");
        }
        $this->load->model('community/post_tutor_model');
        $this->load->model('core/ddoption_model');
    }

    function index() {
        $this->load->helper('form');
        $this->template->load_showloading();
        $this->template->load_flexgrid();
        $this->template->application_script('community/post_tutor/main_grid.js');
        $data = array(
            'add_link' => site_url('community/post_tutor/add'),
            'main_side_menu' => $this->load->view('resource/main_side_menu', array('active' => 'dycontent'), TRUE),
            'qtype_options' => array('title' => 'ชื่อประกาศ', 'p_id' => 'เลขที่ประกาศ', 'desc' => 'รายละเอียด', 'tags' => 'tags', 'category_id' => 'หมวดหมู่'),
            'ajax_grid_url' => site_url('community/post_tutor/ajax_uploads_list')
        );
        $this->template->write_view('community/post_tutor/main_grid', $data);
        $this->template->render();
    }

    function ajax_uploads_list() {
        $a = $this->post_tutor_model->find_all($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query', TRUE), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE));
        echo json_encode($a);
    }

    function add() {
        $this->load->helper('form');
        $data = array(
            'form_action' => site_url('community/post_tutor/do_save'),
            'title' => 'ลงประกาศสอนพิเศษ',
            'cancel_link' => site_url('community/post_tutor'),
            'form_data' => $this->post_tutor_model->get_form_data(),
            'category_options' => $this->ddoption_model->get_category_array_options(),
            'province_options' => $this->ddoption_model->get_province_options(),
            'location_type_options' => $this->ddoption_model->get_location_type_options(),
            'post_tutor_time_limit_options' => $this->ddoption_model->get_post_tutor_time_limit_options(),
            'post_tutor_type_options' => $this->ddoption_model->get_post_tutor_type_options()
        );

        $this->template->title('ลงประกาศสอนพิเศษ');
        $this->template->write_view('community/post_tutor/input_form', $data);
        $this->template->render();
    }

    function edit($p_id) {
        if (!$this->post_tutor_model->is_owner($p_id)) {
            $data = array(
                'time' => 1,
                'url' => site_url('community/post_tutor'),
                'heading' => 'ระบบผิดพลาด',
                'message' => '<p>ระบบผิดพลาด คุณไม่สามารถแก้ไขข้อมูลชุดนี้ได้</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $this->load->helper('form');
            $data = array(
                'form_action' => site_url('community/post_tutor/do_save'),
                'title' => 'แก้ไขลงประกาศสอนพิเศษ',
                'cancel_link' => site_url('community/post_tutor'),
                'form_data' => $this->post_tutor_model->get_form_data($p_id),
                'category_options' => $this->ddoption_model->get_category_array_options(),
                'province_options' => $this->ddoption_model->get_province_options(),
                'location_type_options' => $this->ddoption_model->get_location_type_options(),
                'post_tutor_time_limit_options' => $this->ddoption_model->get_post_tutor_time_limit_options(),
                'post_tutor_type_options' => $this->ddoption_model->get_post_tutor_type_options()
            );
            $this->template->write_view('community/post_tutor/input_form', $data);
            $this->template->render();
        }
    }

    function do_save() {
        if ($this->post_tutor_model->save($this->input->post('data'))) {
            $data = array(
                'time' => 1,
                'url' => site_url('community/post_tutor'),
                'heading' => 'ประกาศเสร็จสิ้น',
                'message' => '<p>เพิ่มข้อมูลเสร็จสิ้น</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $data = array(
                'time' => 1,
                'url' => site_url('community/post_tutor'),
                'heading' => 'ระบบผิดพลาด',
                'message' => '<p>ระบบผิดพลาด คุณไม่สามารถลงกระกาศนี้ได้</p>'
            );
            $this->load->view('refresh_page', $data);
        }
    }

    function delete($p_id) {
        $data = array(
            'url' => site_url('community/post_tutor/do_delete/' . $p_id),
            'cancel_url' => site_url('community/post_tutor'),
            'heading' => 'คุณต้องการลบข้อมูล video นี้ใช่ไหม',
            'message' => '<p>หากคุณลบข้อมูล video นี้แล้วระบบจะลบข้อมูล video อย่างถาวร</p>'
        );

        $this->load->view('confirm_page', $data);
    }

    function do_delete($id) {
        if ($this->post_tutor_model->delete($id)) {
            $data = array(
                'time' => 1,
                'url' => site_url('community/post_tutor'),
                'heading' => 'ทำการลบข้อมูลเสร็จสิ้น',
                'message' => '<p>คุณไม่สามารถเรียกคืน ประกาศ นี้ได้อีก</p>'
            );
        } else {
            $data = array(
                'time' => 1,
                'url' => site_url('community/post_tutor'),
                'heading' => 'การลบข้อมูลผิดพลาด',
                'message' => '<p>คุณไม่สามารถลบ ประกาศ </p>'
            );
        }
        $this->load->view('refresh_page', $data);
    }

}