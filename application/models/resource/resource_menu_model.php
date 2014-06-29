<?php

class resource_menu_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function main_side_menu($active = '') {
        $menu_data['main_title'] = 'จัดการสื่อการสอน';
        $menu_data['active'] = $active;
        $menu_data['menu']['video_manager'] = array('title' => 'จัดการวิดีโอ', 'uri' => 'resource/video_manager');
        if ($this->auth->can_access($this->auth->permis_dycontent)) {
            $menu_data['menu']['dycontent'] = array('title' => 'โจทย์/เนื้อหา', 'uri' => 'resource/dycontent');
        }
        $menu_data['menu']['doc_manager'] = array('title' => 'จัดการเอกสาร', 'uri' => 'resource/doc_manager');
        $menu_data['menu']['image_manager'] = array('title' => 'จัดการรูปภาพ', 'uri' => 'resource/image_manager');
        if ($this->auth->can_access($this->auth->permis_main_subject_manager)) {
            $menu_data['menu']['subject_manager'] = array('title' => 'กลุ่มสาระ/วิชา/บท', 'uri' => 'resource/subject_manager/main_learning_area');
        } else {
            $menu_data['menu']['subject_manager'] = array('title' => 'กลุ่มสาระ/วิชา/บท', 'uri' => 'resource/subject_manager');
        }
        if ($this->auth->can_access($this->auth->permis_sheet)) {
            $menu_data['menu']['sheet'] = array('title' => 'ใบงาน', 'uri' => 'resource/sheet');
        }
        $menu_data['menu']['taxonomy_manager'] = array('title' => 'ชุดวิดีโอการสอนหน้าเพจ', 'uri' => 'resource/taxonomy_manager');
        $menu_data['menu']['view_video_report'] = array('title' => 'รายงานการเข้าชมวิดีโอ', 'uri' => 'report/play_report/show_all');
        return $this->load->view('main_side_menu', $menu_data, TRUE);
    }

}