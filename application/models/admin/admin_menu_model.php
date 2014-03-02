<?php

class admin_menu_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function main_side_menu($active = '') {
        $menu_data['main_title'] = 'บริหารระบบ';
        $menu_data['active'] = $active;
        $menu_data['menu']['users'] = array('title' => 'จัดการผู้ใช้', 'uri' => 'admin/users');
        if ($this->auth->can_access($this->auth->permis_permission)) {
            $menu_data['menu']['permission'] = array('title' => 'สิทธิการใช้', 'uri' => 'admin/permission');
        }
        if ($this->auth->can_access($this->auth->permis_main_subject_manager)) {
            $menu_data['menu']['subject_manager'] = array('title' => 'จัดการวิชา', 'uri' => 'resource/subject_manager/main_learning_area');
        }
        if ($this->auth->can_access($this->auth->permis_permission)) {
            $menu_data['menu']['service_manager'] = array('title' => 'บริการ', 'uri' => 'admin/service_manager');
            $menu_data['menu']['user_service'] = array('title' => 'บริการที่ขาย', 'uri' => 'admin/service_manager/user_service');
        }
        if ($this->auth->can_access($this->auth->permis_all_view_video_report)) {
            $menu_data['menu']['view_video_report'] = array('title' => 'รายงานการเข้าชมวิดีโอ', 'uri' => 'report/play_report/show_all');
        }
        if ($this->auth->can_access($this->auth->permis_permission)) {
            $menu_data['menu']['disk_quota_service'] = array('title' => 'พื้นที่ให้บริการ', 'uri' => 'service/disk_quota_service/');
        }


        return $this->load->view('main_side_menu', $menu_data, TRUE);
    }

}