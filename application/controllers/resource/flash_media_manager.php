<?php

/**
 * Description of flash_media_manager
 *
 * @author lojorider
 * @property flash_media_manager_model $flash_media_manager_model
 * @property disk_quota_service_model $disk_quota_service_model
 * @property resource_menu_model $resource_menu_model
 */
class flash_media_manager extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->auth->access_limit($this->auth->permis_resource);
        $this->load->model('resource/flash_media_manager_model');
        $this->load->model('core/ddoption_model');
        $this->load->model('main_side_menu_model');
        $this->make_money = $this->config->item('make_money');
        $this->load->helper('form');
    }

    function index() {

        $this->load->model('service/disk_quota_service_model');
        $this->template->load_showloading();
        $this->template->load_flexgrid();
        $this->template->application_script('resource/flash_media_manager/main_grid.js');

        $data['title'] = 'จัดการบทเรียนแฟลช';
        $this->disk_quota_service_model->init_user_quota($this->auth->uid());
        $data['can_upload'] = $this->disk_quota_service_model->can_upload();
        $data['user_disk_size'] = $this->disk_quota_service_model->get_user_disk_size();
        $data['user_disk_quota'] = $this->disk_quota_service_model->get_user_disk_quota();
        $data['upload_link'] = site_url('resource/flash_media_manager/upload');
        $data['main_side_menu'] = $this->main_side_menu_model->resource('flash_media_manager');
        $data['qtype_options'] = array(
            'title' => 'ชื่อ',
            'resource_id' => 'เลขที่',
            'desc' => 'รายละเอียด',
            'tags' => 'ป้ายกำกับ',
            'subject_title' => 'วิชา',
            'chapter_title' => 'บทเรียน'
        );
        $data['command_to_resource_options'] = $this->ddoption_model->get_command_to_resource_options();
        $this->template->write_view('resource/flash_media_manager/main_grid', $data);
        $this->template->script_var(array(
            'ajax_grid_url' => site_url('resource/flash_media_manager/ajax_uploads_list'),
            'ajax_act_resource_url' => site_url('resource/flash_media_manager/ajax_act_resource')
        ));

        $this->template->application_script('resource/flash_media_manager/main_grid.js');

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
                    $a_result['status'] = $this->flash_media_manager_model->delete($a_resource_id);
                    break;
                case 'to_private':
                    $a_result['status'] = $this->flash_media_manager_model->privacy($a_resource_id, 0);
                    break;
                case 'to_no_private':
                    $a_result['status'] = $this->flash_media_manager_model->privacy($a_resource_id, 1);
                    break;
                case 'to_publish':
                    $a_result['status'] = $this->flash_media_manager_model->publish($a_resource_id, 1);
                    break;
                case 'to_no_publish':
                    $a_result['status'] = $this->flash_media_manager_model->publish($a_resource_id, 0);
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
        $a = $this->flash_media_manager_model->find_all($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query', TRUE), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE));
        echo json_encode($a);
    }

    function edit($resource_id) {
        if (!$this->flash_media_manager_model->is_owner($resource_id)) {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/flash_media_manager'),
                'heading' => 'ระบบผิดพลาด',
                'message' => '<p>ระบบผิดพลาด คุณไม่สามารถแก้ไขข้อมูลชุดนี้ได้</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {

            $this->template->load_showloading();
            $this->template->application_script('resource/flash_media_manager/edit_form.js');
            $full_flash_media_dir = $this->flash_media_manager_model->get_full_flash_media_dir();
            $file_path_options = $this->flash_media_manager_model->get_file_path_options($full_flash_media_dir . $resource_id);

            $data = array(
                //ทั่วไป
                'title' => 'แก้ไขข้อมูลเอกสาร',
                'form_action' => site_url('resource/flash_media_manager/do_save'),
                'data' => $this->flash_media_manager_model->get_flash_media_form_data($resource_id),
                'cancel_link' => site_url('resource/flash_media_manager'),
                'delete_link' => site_url('resource/flash_media_manager/delete/' . $resource_id),
                'publish_options' => $this->ddoption_model->get_publish_options(),
                'privacy_options' => $this->ddoption_model->get_privacy_options(),
                'degree_options' => $this->ddoption_model->get_degree_id_options(),
                'file_path_options' => $file_path_options,
                'learning_area_options' => $this->ddoption_model->get_learning_area_options(),
                'ajax_subject_options_url' => site_url('core/ajax_ddoptions/get_subject_options'),
                'ajax_chapter_autocomplete_url' => site_url('core/ajax_autocomplete/get_chapter'),
            );
            $this->template->script_var(
                    array(
                        'ajax_subject_options_url' => site_url('core/ajax_ddoptions/get_subject_options'),
                        'ajax_chapter_options_url' => site_url('core/ajax_ddoptions/get_chapter_options'),
                        'ajax_sub_chapter_autocomplete_url' => site_url('core/ajax_autocomplete/get_sub_chapter'),
                        'subj_id' => $data['data']['subj_id'],
                    )
            );
            $this->template->write_view('resource/flash_media_manager/edit_form', $data);
            $this->template->render();
        }
    }

    function do_save() {
        if ($this->flash_media_manager_model->save($this->input->post('resource_id'), $this->input->post('data'))) {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/flash_media_manager'),
                'heading' => 'แก้ไขข้อมูลเสร็จสิ้น',
                'message' => '<p>แก้ไขข้อมูลเสร็จสิ้น</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/flash_media_manager'),
                'heading' => 'ระบบผิดพลาด',
                'message' => '<p>ระบบผิดพลาด คุณไม่สามารถแก้ไขข้อมูลชุดนี้ได้</p>'
            );
            $this->load->view('refresh_page', $data);
        }
    }

    function delete($resource_id) {
        $data = array(
            'url' => site_url('resource/flash_media_manager/do_delete/' . $resource_id),
            'cancel_url' => site_url('resource/flash_media_manager'),
            'heading' => 'คุณต้องการลบข้อมูล เอกสาร นี้ใช่ไหม',
            'message' => '<p>หากคุณลบข้อมูล เอกสาร นี้แล้วระบบจะลบข้อมูล เอกสาร อย่างถาวร</p>'
        );

        $this->load->view('confirm_page', $data);
    }

    function do_delete($id) {
        if ($this->flash_media_manager_model->delete($id)) {
            $data = array(
                'time' => 100,
                'url' => site_url('resource/flash_media_manager'),
                'heading' => 'ทำการลบข้อมูลเสร็จสิ้น',
                'message' => '<p>คุณไม่สามารถเรียกคืน เอกสารนี้ นี้ได้อีก</p>'
            );
        } else {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/flash_media_manager'),
                'heading' => 'การลบข้อมูลผิดพลาด',
                'message' => '<p>คุณไม่สามารถลบ เอกสารนี้ </p>'
            );
        }
        $this->load->view('refresh_page', $data);
    }

// UPLOAD ============================================================================================================
    function upload() {

        $this->load->model('service/disk_quota_service_model');
        $this->disk_quota_service_model->init_user_quota($this->auth->uid());
        if (!$this->disk_quota_service_model->can_upload()) {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/flash_media_manager'),
                'heading' => 'ไม่สามารถ Upload ได้',
                'message' => '<p>ไม่สามารถ Upload ได้</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $this->load->helper('form');

            $this->template->load_fileuploader();
            $this->template->load_showloading();
            $this->template->application_script('resource/flash_media_manager/upload_form.js');
            $data = array(
                //ทั่วไป
                'title' => 'อัพโหลด SWF',
                'form_action' => site_url('resource/flash_media_manager/do_upload_step2'),
                'cancel_link' => site_url('resource/flash_media_manager'),
                'publish_options' => $this->ddoption_model->get_publish_options(),
                'privacy_options' => $this->ddoption_model->get_privacy_options(),
                'degree_options' => $this->ddoption_model->get_degree_id_options(),
                'learning_area_options' => $this->ddoption_model->get_learning_area_options()
            );
            $extension_whitelist = $this->flash_media_manager_model->get_upload_extension_whitelist();
            $data['extension_whitelist'] = implode(',', $extension_whitelist);
            $extension_whitelist = "['" . implode("', '", $extension_whitelist) . "']";
            $data['file_size_limit'] = $file_size_limit = $this->flash_media_manager_model->get_file_size_limit();
            $this->template->script_var(array(
                'cgi_bin_url' => $this->config->item('cgi_upload_url'),
                'upload_dir' => $this->config->item('cgi_upload_dir'),
                'extension_whitelist' => array('value' => $extension_whitelist),
                'file_size_limit' => array('value' => $file_size_limit),
                'ajax_subject_options_url' => site_url('core/ajax_ddoptions/get_subject_options'),
                'ajax_chapter_options_url' => site_url('core/ajax_ddoptions/get_chapter_options'),
                'ajax_sub_chapter_autocomplete_url' => site_url('core/ajax_autocomplete/get_sub_chapter'),
                'subj_id' => '',
                'chapter_id' => ''
            ));
            $this->template->write_view('resource/flash_media_manager/upload_form', $data);
            $this->template->render();
        }
    }

    function do_upload_step2() {
        $result = $this->flash_media_manager_model->upload_step2($this->input->post());

        if ($result) {
            $data = $result;
            $data['title'] = 'step 2';
            $data['form_action'] = site_url('resource/flash_media_manager/do_upload_save');
            $data['cancel_link'] = site_url('resource/flash_media_manager/upload');

            $this->template->write_view('resource/flash_media_manager/do_upload_first_step1', $data);
            $this->template->render();
        } else {
            echo 'NO';
        }
    }

    function do_upload_save() {

        $this->flash_media_manager_model->upload_save($this->input->post('data'));
        $data = array(
            'time' => 1,
            'url' => site_url('resource/flash_media_manager'),
            'heading' => 'อัพโหลดไฟล์เสร็จสิ้น',
            'message' => '<p>อัพโหลดไฟล์เสร็จสิ้น</p>'
        );

        $this->load->view('refresh_page', $data);
    }

}