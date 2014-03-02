<?php

/**
 * โปรแกรมแสดงรายงานสำหรับครู
 * @property course_model $course_model
 * @property sheet_model $sheet_model
 * @property exam_model $exam_model
 * @property xelatex_exam_model $xelatex_exam_model
 * @property ddoption_model $ddoption_model
 */
class teacher_report extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth->access_limit($this->auth->permis_course_manager);
        $this->load->model('study/course_model');
        $this->load->helper('form');
        $this->load->model('main_side_menu_model');
    }

    function index() {
        $data = array(
            'title' => 'รายงานหลักสูตร',
            'publish_options' => $this->ddoption_model->get_publish_options(),
            'weight_options' => $this->ddoption_model->get_weight_options(),
        );
        $data['grid_menu'] = array(
//            array('url' => site_url('study/course_manager/add_course'), 'title' => 'เพิ่มหลักสูตร', 'extra' => ''),
        );
        $data['qtype_options'] = array(
            'title' => 'ชื่อหลักสูตร',
            'c_id' => 'เลขที่หลักสูตร',
            'desc' => 'รายละเอียด',
            'degree_id' => 'ชั้นเรียน',
            'subject_title' => 'วิชา',
            'chapter_title' => 'บทเรียน'
        );
        $data['main_side_menu'] = $this->main_side_menu_model->study('teacher_course_report');
        $this->template->script_var(
                array('ajax_grid_url' => site_url('report/teacher_report/ajax_course'))
        );
        $this->template->application_script('report/teacher_report/course_report_grid.js');
        $this->template->load_showloading();
        $this->template->load_flexgrid();
        $this->template->write_view('report/teacher_report/course_report_grid', $data);
        $this->template->render();
    }

    /**
     * ajax ดึงข้อมูล หลักสูตรของครู
     */
    function ajax_course() {
        $a = $this->course_model->find_all_course_rp($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query'), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE));
        echo json_encode($a);
    }

}