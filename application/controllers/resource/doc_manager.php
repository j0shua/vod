<?php

/**
 * Description of doc_manager
 *
 * @author lojorider
 * @property doc_manager_model $doc_manager_model
 * @property disk_quota_service_model $disk_quota_service_model
 * @property resource_menu_model $resource_menu_model
 */
class doc_manager extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->auth->access_limit($this->auth->permis_resource);
        $this->load->model('resource/doc_manager_model');
        $this->load->model('core/ddoption_model');
        $this->load->model('main_side_menu_model');
        $this->make_money = $this->config->item('make_money');
        $this->load->helper('form');
    }

    function index() {

        $this->load->model('service/disk_quota_service_model');
        $this->template->load_showloading();
        $this->template->load_flexgrid();
        $this->template->load_jquery_colorbox();
        $this->template->application_script('resource/doc_manager/main_grid.js');

        $data['title'] = 'จัดการเอกสาร';
        $this->disk_quota_service_model->init_user_quota($this->auth->uid());
        $data['can_upload'] = $this->disk_quota_service_model->can_upload();
        $data['user_disk_size'] = $this->disk_quota_service_model->get_user_disk_size();
        $data['user_disk_quota'] = $this->disk_quota_service_model->get_user_disk_quota();
        $data['upload_link'] = site_url('resource/doc_upload');
        $data['main_side_menu'] = $this->main_side_menu_model->resource('doc_manager');
        $data['qtype_options'] = array(
            'title' => 'ชื่อเอกสาร',
            'resource_id' => 'เลขที่เอกสาร',
            'desc' => 'รายละเอียด',
            'tags' => 'ป้ายกำกับ',
            'subject_title' => 'วิชา',
            'chapter_title' => 'บทเรียน'
        );
        $data['command_to_resource_options'] = $this->ddoption_model->get_command_to_resource_options();
        $this->template->write_view('resource/doc_manager/main_grid', $data);
        $this->template->script_var(array(
            'ajax_grid_url' => site_url('resource/doc_manager/ajax_uploads_list'),
            'ajax_act_resource_url' => site_url('resource/doc_manager/ajax_act_resource')
        ));

        $this->template->application_script('resource/doc_manager/main_grid.js');

        $this->template->render();
    }

    /**
     * แก้ไข resourc แบบ ajax
     */
    function ajax_act_resource() {
        $a_result = array();
        $a_result['msg'] = "การกระทำเป็นไปด้วยดี";
        $a_resource_id = $this->input->post('cb_resource_id');
        if ($a_resource_id) {
            switch ($this->input->post('command')) {
                case 'to_delete':
                    $a_result['status'] = $this->doc_manager_model->delete($a_resource_id);
                    break;
                case 'to_private':
                    $a_result['status'] = $this->doc_manager_model->privacy($a_resource_id, 0);
                    break;
                case 'to_no_private':
                    $a_result['status'] = $this->doc_manager_model->privacy($a_resource_id, 1);
                    break;
                case 'to_publish':
                    $a_result['status'] = $this->doc_manager_model->publish($a_resource_id, 1);
                    break;
                case 'to_no_publish':
                    $a_result['status'] = $this->doc_manager_model->publish($a_resource_id, 0);
                    break;
                default:
                    break;
            }
            if (!$a_result['status']) {
                $a_result['msg'] = "การกระทำเกิดข้อผิดพลาดบางอย่าง";
            }
        } else {
            $a_result['status'] = false;
            $a_result['msg'] = "ท่านยังไม่ได้เลือกข้อมูลเพื่อทำการลบ";
        }

        echo json_encode($a_result);
    }

    function ajax_uploads_list() {
        $a = $this->doc_manager_model->find_all($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query', TRUE), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE));
        echo json_encode($a);
    }

    function edit($resource_id) {
        if (!$this->doc_manager_model->is_owner($resource_id)) {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/doc_manager'),
                'heading' => 'ระบบผิดพลาด',
                'message' => '<p>ระบบผิดพลาด คุณไม่สามารถแก้ไขข้อมูลชุดนี้ได้</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {

            $this->template->load_showloading();
            $this->template->application_script('resource/doc_manager/edit_form.js');
            $data = array(
                //ทั่วไป
                'title' => 'แก้ไขข้อมูลเอกสาร',
                'form_action' => site_url('resource/doc_manager/do_save'),
                'form_data' => $this->doc_manager_model->get_doc_form_data($resource_id),
                'cancel_link' => site_url('resource/doc_manager'),
                'delete_link' => site_url('resource/doc_manager/delete/' . $resource_id),
                'publish_options' => $this->ddoption_model->get_publish_options(),
                'privacy_options' => $this->ddoption_model->get_privacy_options(),
                'degree_options' => $this->ddoption_model->get_degree_id_options(),
                'learning_area_options' => $this->ddoption_model->get_learning_area_options(),
                'ajax_subject_options_url' => site_url('core/ajax_ddoptions/get_subject_options'),
                'ajax_chapter_autocomplete_url' => site_url('core/ajax_autocomplete/get_chapter'),
            );
            $this->template->script_var(
                    array(
                        'ajax_subject_options_url' => site_url('core/ajax_ddoptions/get_subject_options'),
                        'ajax_chapter_options_url' => site_url('core/ajax_ddoptions/get_chapter_options'),
                        'ajax_sub_chapter_autocomplete_url' => site_url('core/ajax_autocomplete/get_sub_chapter'),
                        'subj_id' => $data['form_data']['subj_id'],
                    )
            );
            $this->template->write_view('resource/doc_manager/edit_form', $data);
            $this->template->render();
        }
    }

    function do_save() {
        if ($this->doc_manager_model->save($this->input->post('resource_id'), $this->input->post('data'))) {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/doc_manager'),
                'heading' => 'แก้ไขข้อมูลเสร็จสิ้น',
                'message' => '<p>แก้ไขข้อมูลเสร็จสิ้น</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/doc_manager'),
                'heading' => 'ระบบผิดพลาด',
                'message' => '<p>ระบบผิดพลาด คุณไม่สามารถแก้ไขข้อมูลชุดนี้ได้</p>'
            );
            $this->load->view('refresh_page', $data);
        }
    }

    function delete($resource_id) {
        $data = array(
            'url' => site_url('resource/doc_manager/do_delete/' . $resource_id),
            'cancel_url' => site_url('resource/doc_manager'),
            'heading' => 'คุณต้องการลบข้อมูล เอกสาร นี้ใช่ไหม',
            'message' => '<p>หากคุณลบข้อมูล เอกสาร นี้แล้วระบบจะลบข้อมูล เอกสาร อย่างถาวร</p>'
        );

        $this->load->view('confirm_page', $data);
    }

    function do_delete($id) {
        if ($this->doc_manager_model->delete($id)) {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/doc_manager'),
                'heading' => 'ทำการลบข้อมูลเสร็จสิ้น',
                'message' => '<p>คุณไม่สามารถเรียกคืน เอกสารนี้ นี้ได้อีก</p>'
            );
        } else {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/doc_manager'),
                'heading' => 'การลบข้อมูลผิดพลาด',
                'message' => '<p>คุณไม่สามารถลบ เอกสารนี้ </p>'
            );
        }
        $this->load->view('refresh_page', $data);
    }

    //=========================================================================
    // Iframe
    //=========================================================================
    function iframe() {
        $data['qtype_options'] = array(
            'title' => 'ชื่อ',
            'resource_id' => 'เลขที่เอกสาร',
            'desc' => 'รายละเอียด',
            'tags' => 'ป้ายกำกับ',
            'subject_title' => 'วิชา',
            'chapter_title' => 'บทเรียน'
        );

        $this->template->application_script('resource/doc_manager/iframe.js');
        $this->template->load_flexgrid();
        $this->template->write_view('resource/doc_manager/iframe', $data);
        $this->template->temmplate_name('normal');
        $this->template->script_var(
                array('ajax_resource_list_url' => site_url('resource/doc_manager/ajax_iframe_doc_manager_list'))
        );
        $this->template->render();
    }

    function ajax_iframe_doc_manager_list() {
        $a = $this->doc_manager_model->iframe_find_all($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query', TRUE), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE));
        echo json_encode($a);
    }

}