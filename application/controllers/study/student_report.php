<?php

/**
 * โปรแกรมแสดงรายงานสำหรับครู
 * @property course_model $course_model
 * @property sheet_model $sheet_model
 * @property exam_model $exam_model
 * @property xelatex_exam_model $xelatex_exam_model
 * @property ddoption_model $ddoption_model
 */
class student_report extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth->access_limit($this->auth->permis_course);
        $this->load->model('study/course_model');
        $this->load->helper('form');
    }
    
    function index(){
        
    }
    function course(){
        
    }

   

}