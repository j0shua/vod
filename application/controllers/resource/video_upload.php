<?php

/**
 * Description of video_upload
 *
 * @author lojorider
 * @property video_upload_model $video_upload_model
 * @property disk_quota_service_model $disk_quota_service_model
 */
class video_upload extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->auth->access_limit($this->auth->permis_resource);
        $this->load->model('resource/video_upload_model');
        $this->load->model('core/ddoption_model');
    }

    function check_permission() {
        if (!$this->auth->is_login()) {
            redirect('user/login');
        }
        if (!$this->auth->can_access($this->auth->permis_resource)) {
            exit("Permission Denied.");
        }
    }

    function index() {
        $this->load->helper('number');
        $this->load->model('service/disk_quota_service_model');
        $this->disk_quota_service_model->init_user_quota($this->auth->uid());
        if (!$this->disk_quota_service_model->can_upload()) {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/video_manager'),
                'heading' => 'ไม่สามารถ Upload ได้',
                'message' => '<p>ไม่สามารถ Upload ได้</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $this->load->helper('form');
            $this->check_permission();
            $this->template->load_fileuploader();
            $this->template->load_showloading();
            $this->template->load_jquery_colorbox(); 
            $this->template->application_script('resource/video_upload/upload_form.js');
            $data = array(
                //ทั่วไป
                'title' => 'อัพโหลดวิดีโอ',
                'form_action' => site_url('resource/video_upload/do_save'),
                'cancel_link' => site_url('resource/video_manager'),
                'publish_options' => $this->ddoption_model->get_publish_options(),
                'privacy_options' => $this->ddoption_model->get_privacy_options(),
                'degree_options' => $this->ddoption_model->get_degree_id_options(),
                'learning_area_options' => $this->ddoption_model->get_learning_area_options(),
                'unit_price_options' => $this->ddoption_model->get_unit_price_options()
            );
            $extension_whitelist = $this->video_upload_model->get_extension_whitelist();
            $data['extension_whitelist'] = implode(',', $extension_whitelist);
            $extension_whitelist = "['" . implode("', '", $extension_whitelist) . "']";
            $data['file_size_limit'] = $file_size_limit = $this->video_upload_model->get_file_size_limit();
            
            $this->template->script_var(
                    array(
                        'cgi_bin_url' => $this->config->item('cgi_upload_url'),
                        'upload_dir' => $this->config->item('cgi_upload_dir'),
                        'extension_whitelist' => array('value' => $extension_whitelist),
                        'file_size_limit' => array('value' => $file_size_limit),
                        'ajax_subject_options_url' => site_url('core/ajax_ddoptions/get_subject_options'),
                        'ajax_chapter_options_url' => site_url('core/ajax_ddoptions/get_chapter_options'),
                        'ajax_sub_chapter_autocomplete_url' => site_url('core/ajax_autocomplete/get_sub_chapter'),
                        'subj_id' => '',
                        'chapter_id' => ''
                    )
            );

            $this->template->write_view('resource/video_upload/upload_form', $data);
            $this->template->render();
        }
    }

    function do_save() {
        $this->check_permission();
        $this->load->model('resource/video_upload_model');
        if ($this->video_upload_model->save($this->input->post('data'), $this->input->post('resume_file'))) {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/video_manager'),
                'heading' => 'upload เสร็จสิ้น',
                'message' => '<p>ผลการ upload เป็นไปได้ด้วยดี</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/video_manager'),
                'heading' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล',
                'message' => '<p>โปรดลองใหม่อีกครั้ง</p>'
            );
            $this->load->view('refresh_page', $data);
        }
    }

}