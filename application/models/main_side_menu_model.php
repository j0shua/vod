<?php

class main_side_menu_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function resource($active = '') {
        $menu_data['main_title'] = 'จัดการ content';
        $menu_data['active'] = $active;

        if ($this->auth->can_access($this->auth->permis_dycontent)) {
            $menu_data['menu']['dycontent'] = array('title' => 'โจทย์/เนื้อหา', 'uri' => 'resource/dycontent');
        }
        $menu_data['menu']['video_manager'] = array('title' => 'จัดการวิดีโอ', 'uri' => 'resource/video_manager');
        if ($this->auth->make_money) {
            $menu_data['menu']['pvideo_manager'] = array('title' => 'จัดการวิดีโอ (prokru.com)', 'uri' => 'resource/pvideo_manager');
        }


        $menu_data['menu']['doc_manager'] = array('title' => 'จัดการแฟ้มเอกสาร', 'uri' => 'resource/doc_manager');
        $menu_data['menu']['image_manager'] = array('title' => 'จัดการรูปภาพ', 'uri' => 'resource/image_manager');
        $menu_data['menu']['flash_media_manager'] = array('title' => 'จัดการบทเรียนแฟลช', 'uri' => '/resource/flash_media_manager');




//        if ($this->auth->can_access($this->auth->permis_main_subject_manager)) {
//            $menu_data['menu']['subject_manager'] = array('title' => 'กลุ่มสาระ/วิชา/บท', 'uri' => 'resource/subject_manager/main_learning_area');
//        } else {
//            $menu_data['menu']['subject_manager'] = array('title' => 'กลุ่มสาระ/วิชา/บท', 'uri' => 'resource/subject_manager');
//        }
//        if ($this->auth->can_access($this->auth->permis_sheet)) {
//            $menu_data['menu']['sheet'] = array('title' => 'ใบงาน', 'uri' => 'resource/sheet');
//        }
//
//        $menu_data['menu']['course_manager'] = array('title' => 'หลักสูตรการสอน', 'uri' => 'study/course_manager');
//        $menu_data['menu']['course_open'] = array('title' => 'คัดลอกหลักสูตรมาใช้', 'uri' => 'study/course_manager/course_open');
//
//        $menu_data['menu']['taxonomy_manager'] = array('title' => 'ชุดวิดีโอการสอนหน้าเพจ', 'uri' => 'resource/taxonomy_manager');
//        $menu_data['menu']['view_video_report'] = array('title' => 'รายงานการเข้าชมวิดีโอ', 'uri' => 'report/play_report/show_all');



        return $this->load->view('main_side_menu', $menu_data, TRUE);
    }

    function study($active) {
        $menu_data['main_title'] = 'จัดการการเรียนการสอน';
        $menu_data['active'] = $active;

        if ($this->auth->can_access($this->auth->permis_main_subject_manager)) {
            $menu_data['menu']['subject_manager'] = array('title' => 'กลุ่มสาระ/วิชา/บท', 'uri' => 'resource/subject_manager/main_learning_area');
        } else {
            $menu_data['menu']['subject_manager'] = array('title' => 'กลุ่มสาระ/วิชา/บท', 'uri' => 'resource/subject_manager');
        }
        if ($this->auth->can_access($this->auth->permis_sheet)) {
            $menu_data['menu']['sheet'] = array('title' => 'ใบงาน', 'uri' => 'resource/sheet');
        }

        $menu_data['menu']['course_manager'] = array('title' => 'หลักสูตรการสอน', 'uri' => 'study/course_manager');
        $menu_data['menu']['course_open'] = array('title' => 'คัดลอกหลักสูตรมาใช้', 'uri' => 'study/course_manager/course_open');

        $menu_data['menu']['taxonomy_manager'] = array('title' => 'ชุดวิดีโอการสอนหน้าเพจ', 'uri' => 'resource/taxonomy_manager');
        $menu_data['menu']['teacher_course_report'] = array('title' => 'รายงานหลักสูตร', 'uri' => 'report/teacher_report');
        $menu_data['menu']['view_video_report'] = array('title' => 'รายงานการเข้าชมวิดีโอ', 'uri' => 'report/play_report/show_all');
        if ($this->auth->is_make_money()) {
            $menu_data['menu']['resource_earnings'] = array('title' => 'รายงานรายได้', 'uri' => 'earnings/resource_earnings');
        }




        return $this->load->view('main_side_menu', $menu_data, TRUE);
    }

}
