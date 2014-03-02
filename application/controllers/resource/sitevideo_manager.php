<?php

/**
 * Description of sitevideo_manager
 *
 * @author lojorider
 * @property sitevideo_manager_model $sitevideo_manager_model
 */
class sitevideo_manager extends CI_Controller {

    function __construct() {
        parent::__construct();
     $this->auth->access_limit($this->auth->permis_resource);
        $this->load->model('resource/sitevideo_manager_model');
        $this->load->model('core/ddoption_model');
    }

    function index() {
        $this->load->helper('form');
        $this->template->load_showloading();
        $this->template->load_flexgrid();
        $this->template->application_script('resource/sitevideo_manager/main_grid.js');
        $data['title']='YouTube และ อื่นๆ';
        $data['add_link'] = site_url('resource/sitevideo_manager/add');
        $data['main_side_menu'] = $this->load->view('resource/main_side_menu', array('active' => 'sitevideo_manager'), TRUE);
        $data['qtype_options'] = array( 'title' => 'ชื่อวิด๊โอ', 'resource_id' => 'เลขที่วิดีโอ','desc' => 'รายละเอียด', 'tags' => 'tags', 'category_id' => 'หมวดหมู่');
        $this->template->write_view('resource/sitevideo_manager/main_grid', $data);
        $this->template->render();
    }

    function ajax_uploads_list() {
        $a = $this->sitevideo_manager_model->find_all($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query', TRUE), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE));
        echo json_encode($a);
    }

    function add() {
        $this->load->helper('form');
        $data = array(
            'form_action' => site_url('resource/sitevideo_manager/do_add'),
            'title'=>'เพิ่ม sitevideo',
            'cancel_link' => site_url('resource/sitevideo_manager'),
            'form_data' => $this->sitevideo_manager_model->get_video_form_data(),
            'publish_options' => $this->ddoption_model->get_publish_options(),
            'privacy_options' => $this->ddoption_model->get_privacy_options(),
            'category_options' => $this->ddoption_model->get_category_array_options()
        );

        $this->template->write_view('resource/sitevideo_manager/input_form', $data);
        $this->template->render();
    }

    function edit($resource_id) {
        if (!$this->sitevideo_manager_model->is_owner($resource_id)) {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/sitevideo_manager'),
                'heading' => 'ระบบผิดพลาด',
                'message' => '<p>ระบบผิดพลาด คุณไม่สามารถแก้ไขข้อมูลชุดนี้ได้</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $this->load->helper('form');
            $data = array(
                'form_action' => site_url('resource/sitevideo_manager/do_edit'),
                'title'=>'แก้ไข sitevideo',
                'form_data' => $this->sitevideo_manager_model->get_video_form_data($resource_id),
                'cancel_link' => site_url('resource/sitevideo_manager'),
                
                'publish_options' => $this->ddoption_model->get_publish_options(),
                'privacy_options' => $this->ddoption_model->get_privacy_options(),
                'category_options' => $this->ddoption_model->get_category_array_options()
            );
            $this->template->write_view('resource/sitevideo_manager/input_form', $data);
            $this->template->render();
        }
    }

    function do_add() {
        if ($this->sitevideo_manager_model->save('', $this->input->post('data'))) {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/sitevideo_manager'),
                'heading' => 'เพิ่มข้อมูลเสร็จสิ้น',
                'message' => '<p>เพิ่มข้อมูลเสร็จสิ้น</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/sitevideo_manager'),
                'heading' => 'ระบบผิดพลาด',
                'message' => '<p>ระบบผิดพลาด คุณไม่สามารถเพิ่มข้อมูลชุดนี้ได้</p>'
            );
            $this->load->view('refresh_page', $data);
        }
    }

    function do_edit() {
        if ($this->sitevideo_manager_model->save($this->input->post('resource_id'), $this->input->post('data'))) {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/sitevideo_manager'),
                'heading' => 'แก้ไขข้อมูลเสร็จสิ้น',
                'message' => '<p>แก้ไขข้อมูลเสร็จสิ้น</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/sitevideo_manager'),
                'heading' => 'ระบบผิดพลาด',
                'message' => '<p>ระบบผิดพลาด คุณไม่สามารถแก้ไขข้อมูลชุดนี้ได้</p>'
            );
            $this->load->view('refresh_page', $data);
        }
    }

    function delete($resource_id) {
        $data = array(
            'url' => site_url('resource/sitevideo_manager/do_delete/' . $resource_id),
            'cancel_url' => site_url('resource/sitevideo_manager'),
            'heading' => 'คุณต้องการลบข้อมูล video นี้ใช่ไหม',
            'message' => '<p>หากคุณลบข้อมูล video นี้แล้วระบบจะลบข้อมูล video อย่างถาวร</p>'
        );

        $this->load->view('confirm_page', $data);
    }

    function do_delete($id) {
        if ($this->sitevideo_manager_model->delete($id)) {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/sitevideo_manager'),
                'heading' => 'ทำการลบข้อมูลเสร็จสิ้น',
                'message' => '<p>คุณไม่สามารถเรียกคืน video นี้ได้อีก</p>'
            );
        } else {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/sitevideo_manager'),
                'heading' => 'การลบข้อมูลผิดพลาด',
                'message' => '<p>คุณไม่สามารถลบ video </p>'
            );
        }
        $this->load->view('refresh_page', $data);
    }

}