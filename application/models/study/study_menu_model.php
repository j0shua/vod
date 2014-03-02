<?php

class study_menu_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function main_side_menu($active = '') {
        $menu_data['main_title'] = 'การเรียนการสอน';
        $menu_data['active'] = $active;
        $menu_data['menu']['course_manager'] = array('title' => 'หลักสูตรการเรียน', 'uri' => 'study/course_manager');
        $menu_data['menu']['course_open'] = array('title' => 'หลักสูตรการเรียนที่เปิด', 'uri' => 'study/course_manager/course_open');
//        $menu_data['menu']['my_course_student'] = array('title' => 'นักเรียน', 'uri' => 'study/course_manager');
//        $menu_data['menu']['course_report'] = array('title' => 'รายงาน', 'uri' => 'study/course_manager');
        return $this->load->view('main_side_menu', $menu_data, TRUE);
    }
     public function main_side_menu_student($active = '') {
        $menu_data['main_title'] = 'การเรียนการสอน';
        $menu_data['active'] = $active;
        $menu_data['menu']['course_enroll'] = array('title' => 'หลักสูตรการเรียนที่เรียนอยู่', 'uri' => 'study/course');
        $menu_data['menu']['course_open'] = array('title' => 'หลักสูตรการเรียนที่เปิด', 'uri' => 'study/course/course_open');
        //$menu_data['menu']['report_course_score'] = array('title' => 'รายงาน', 'uri' => 'study/course/report_course_score');
        return $this->load->view('main_side_menu', $menu_data, TRUE);
    }
//     public function main_side_menu_study_course($active = '') {
//        $menu_data['main_title'] = 'การเรียนการสอน';
//        $menu_data['active'] = $active;
//        $menu_data['menu']['course_enroll'] = array('title' => 'หลักสูตรการเรียนที่เรียนอยู่', 'uri' => 'study/course/course_open');
//        $menu_data['menu']['course_open'] = array('title' => 'หลักสูตรการเรียนที่เปิด', 'uri' => 'study/course');
//        $menu_data['menu']['report_course_score'] = array('title' => 'รายงาน', 'uri' => 'study/course/report_course_score');
//        return $this->load->view('main_side_menu', $menu_data, TRUE);
//    }

}