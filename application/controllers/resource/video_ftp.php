<?php

/**
 * Description of video_ftp
 *
 * @author lojorider
 * @property video_ftp_model $video_ftp_model
 * @property disk_quota_service_model $disk_quota_service_model
 */
class video_ftp extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->auth->access_limit($this->auth->permis_resource);
        $this->load->model('resource/video_ftp_model');
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
        $this->load->helper('form');

        $this->template->load_showloading();
        $this->template->load_flexgrid();
        $this->template->application_script('resource/video_ftp/main_grid.js');
        $data['main_side_menu'] = $this->load->view('resource/main_side_menu', array('active' => ''), TRUE);
        $this->template->write_view('resource/video_ftp/main_grid', $data);
        $this->template->render();
    }

    function ajax_uploads_list() {
        $a = $this->video_ftp_model->get_source_file_list();
        echo json_encode($a);
    }

    function do_save($key_of_file) {
        $this->check_permission();
        $this->load->model('resource/video_ftp_model');
        if ($this->video_ftp_model->save($key_of_file)) {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/video_ftp'),
                'heading' => 'ลงทะเบียน FTP  เสร็จสิ้น',
                'message' => '<p>ผลการ ลงทะเบียน FTP เป็นไปได้ด้วยดี</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/video_ftpr'),
                'heading' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล',
                'message' => '<p>โปรดลองใหม่อีกครั้ง</p>'
            );
            $this->load->view('refresh_page', $data);
        }
    }

}