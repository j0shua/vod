<?php

/**
 * Description of doc_upload
 *
 * @author lojorider
 * @property doc_upload_model $doc_upload_model
 * @property disk_quota_service_model $disk_quota_service_model
 */
class doc_upload extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->auth->access_limit($this->auth->permis_resource);
        $this->load->model('resource/doc_upload_model');
        $this->load->model('core/ddoption_model');
    }

    function index() {
        $this->load->model('service/disk_quota_service_model');
        $this->disk_quota_service_model->init_user_quota($this->auth->uid());
        if (!$this->disk_quota_service_model->can_upload()) {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/doc_manager'),
                'heading' => 'ไม่สามารถ Upload ได้',
                'message' => '<p>ไม่สามารถ Upload ได้</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $this->load->helper('form');

            $this->template->load_fileuploader();
            $this->template->load_showloading();
            $this->template->application_script('resource/doc_upload/upload_form.js');
            $data = array(
                //ทั่วไป
                'title' => 'อัพโหลดเอกสาร',
                'form_action' => site_url('resource/doc_upload/do_save'),
                'cancel_link' => site_url('resource/doc_manager'),
                'publish_options' => $this->ddoption_model->get_publish_options(),
                'privacy_options' => $this->ddoption_model->get_privacy_options(),
                'degree_options' => $this->ddoption_model->get_degree_id_options(),
                'learning_area_options' => $this->ddoption_model->get_learning_area_options()
            );
            $extension_whitelist = $this->doc_upload_model->get_extension_whitelist();
            $data['extension_whitelist'] = implode(',', $extension_whitelist);
            $extension_whitelist = "['" . implode("', '", $extension_whitelist) . "']";

            $data['file_size_limit'] = $file_size_limit = $this->doc_upload_model->get_file_size_limit();

            $this->template->script_var(array(
                'cgi_bin_url' => $this->config->item('cgi_upload_url'),
                'upload_dir' => $this->config->item('cgi_upload_dir'),
                'ajax_subject_options_url' => site_url('core/ajax_ddoptions/get_subject_options'),
                'ajax_chapter_options_url' => site_url('core/ajax_ddoptions/get_chapter_options'),
                'ajax_sub_chapter_autocomplete_url' => site_url('core/ajax_autocomplete/get_sub_chapter'),
                'extension_whitelist' => array('value' => $extension_whitelist),
                'file_size_limit' => array('value' => $file_size_limit)
            ));
            $this->template->write_view('resource/doc_upload/upload_form', $data);
            $this->template->render();
        }
    }

    function do_save() {

        $this->load->model('resource/doc_upload_model');
        if ($this->doc_upload_model->save($this->input->post('data'), $this->input->post('resume_file'))) {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/doc_manager'),
                'heading' => 'upload เสร็จสิ้น',
                'message' => '<p>ผลการ upload เป็นไปได้ด้วยดี</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $data = array(
                'time' => 5,
                'url' => site_url('resource/doc_manager'),
                'heading' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล',
                'message' => '<p>โปรดลองใหม่อีกครั้ง</p>'
            );
            $this->load->view('refresh_page', $data);
        }
    }

}