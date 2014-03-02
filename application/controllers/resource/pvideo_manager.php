<?php

/**
 * Description of pvideo_manager
 *
 * @author lojorider
 * @property pvideo_manager_model $pvideo_manager_model
 */
class pvideo_manager extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->auth->access_limit($this->auth->permis_resource);
        $this->load->model('resource/pvideo_manager_model');
        $this->load->model('core/ddoption_model');
        $this->load->model('main_side_menu_model');
        $this->make_money = $this->config->item('make_money');
    }

    function index() {
        $this->load->helper('form');
        $this->load->model('service/disk_quota_service_model');

        $data['title'] = 'จัดการวิดีโอจาก Prokru.com';
        $this->disk_quota_service_model->init_user_quota($this->auth->uid());
        $data['can_upload'] = $this->disk_quota_service_model->can_upload();
        $data['user_disk_size'] = $this->disk_quota_service_model->get_user_disk_size();
        $data['user_disk_quota'] = $this->disk_quota_service_model->get_user_disk_quota();
        $data['upload_link'] = site_url('resource/video_upload');
        if ($this->make_money) {
            $data['qtype_options'] = array(
                'resource_id' => 'เลขที่วิดีโอ',
                'resource_code' => 'รหัสวิดีโอ',
                'title' => 'ชื่อวิดีโอ',
                'desc' => 'รายละเอียด',
                'tags' => 'ป้ายกำกับ',
                'subject_title' => 'วิชา',
                'chapter_title' => 'บทเรียน'
            );
        } else {
            $data['qtype_options'] = array(
                'resource_id' => 'เลขที่วิดีโอ',
                //'resource_code' => 'รหัสวิดีโอ',
                'title' => 'ชื่อวิดีโอ',
                'desc' => 'รายละเอียด',
                'tags' => 'ป้ายกำกับ',
                'subject_title' => 'วิชา',
                'chapter_title' => 'บทเรียน'
            );
        }

        $data['command_to_resource_options'] = $this->ddoption_model->get_command_to_resource_options();
        $this->template->script_var(array(
            'ajax_grid_url' => site_url('resource/pvideo_manager/ajax_list'),
            'ajax_act_resource_url' => site_url('resource/pvideo_manager/ajax_act_resource')
        ));
        $data['main_side_menu'] = $this->main_side_menu_model->resource('pvideo_manager');
        if ($this->make_money) {
            $this->template->application_script('resource/pvideo_manager/main_grid.js');
        } else {
            $this->template->application_script('resource/pvideo_manager/main_grid_freesys.js');
        }

        $this->template->load_showloading();
        $this->template->load_flexgrid();
        $this->template->write_view('resource/pvideo_manager/main_grid', $data);
        $this->template->render();
    }

    function ajax_list() {
        $a = $this->pvideo_manager_model->find_all($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query'), $this->input->post('rp', TRUE), $this->input->post('sortname'), $this->input->post('sortorder'));
        echo json_encode($a);
    }

    function ajax_sync_parent() {
        
    }

    function sync_parent() {
        $this->pvideo_manager_model->sync_parent();
    }
     function edit($resource_id) {
        $url = site_url('resource/pvideo_manager');
        if ($this->pvideo_manager_model->get_resource_type_id($resource_id) == 6) {
            $url = site_url('resource/pvideo_manager/');
        }
        if (!$this->pvideo_manager_model->is_owner($resource_id)) {
            $data = array(
                'time' => 1,
                'url' => $url,
                'heading' => 'ระบบผิดพลาด',
                'message' => '<p>ระบบผิดพลาด คุณไม่สามารถแก้ไขข้อมูลชุดนี้ได้</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $this->load->helper('form');
            $this->template->load_showloading();
            $this->template->application_script('resource/pvideo_manager/edit_form.js');

            $data = array(
                'title' => 'แก้ไขรายละเอียดวีดีโอ (prokru.com)',
                'form_action' => site_url('resource/pvideo_manager/do_save'),
                'form_data' => $this->pvideo_manager_model->get_video_form_data($resource_id),
                'cancel_link' => $url,
                'delete_link' => site_url('resource/pvideo_manager/delete/' . $resource_id),
                'publish_options' => $this->ddoption_model->get_publish_options(),
                'privacy_options' => $this->ddoption_model->get_privacy_options(),
                'degree_id_options' => $this->ddoption_model->get_degree_id_options(),
                'learning_area_options' => $this->ddoption_model->get_learning_area_options(),
                'unit_price_options' => $this->ddoption_model->get_unit_price_options()
            );


            $this->template->script_var(
                    array(
                        'ajax_subject_options_url' => site_url('core/ajax_ddoptions/get_subject_options'),
                        'ajax_chapter_options_url' => site_url('core/ajax_ddoptions/get_chapter_options'),
                        'ajax_sub_chapter_autocomplete_url' => site_url('core/ajax_autocomplete/get_sub_chapter'),
                        'subj_id' => $data['form_data']['subj_id'],
                        'chapter_id' => $data['form_data']['chapter_id'],
                    )
            );

            $this->template->write_view('resource/pvideo_manager/edit_form', $data);
            $this->template->render();
        }
    }

    function do_save() {
        if ($this->pvideo_manager_model->save($this->input->post('data'))) {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/pvideo_manager'),
                'heading' => 'แก้ไขข้อมูลเสร็จสิ้น',
                'message' => '<p>แก้ไขข้อมูลเสร็จสิ้น</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/pvideo_manager'),
                'heading' => 'ระบบผิดพลาด',
                'message' => '<p>ระบบผิดพลาด คุณไม่สามารถแก้ไขข้อมูลชุดนี้ได้</p>'
            );
            $this->load->view('refresh_page', $data);
        }
    }

    //=========================================================================
    // Iframe
    //=========================================================================
    function iframe($input_id) {
        $this->load->helper('form');


        $data['title'] = 'วิดีโอ prokru.com';


        $data['content_type_options'] = array(
            '' => 'ทุกแบบ',
            1 => 'เนื้อหา',
            2 => 'โจทย์หนึ่งตัวเลือก(mc)',
            3 => 'โจทย์หลายตัวเลือก(mcma)',
            4 => 'โจทย์เติมคำ(ct)',
//            5 => 'โจทย์เติมคำหลายคำตอบ(ctma)'
        );
        if ($this->make_money) {
            $data['qtype_options'] = array(
                'resource_id' => 'เลขที่วิดีโอ',
                'resource_code' => 'รหัสวิดีโอ',
                'title' => 'ชื่อวิดีโอ',
                'desc' => 'รายละเอียด',
                'tags' => 'ป้ายกำกับ',
                'subject_title' => 'วิชา',
                'chapter_title' => 'บทเรียน'
            );
        } else {
            $data['qtype_options'] = array(
                'resource_id' => 'เลขที่วิดีโอ',
                //'resource_code' => 'รหัสวิดีโอ',
                'title' => 'ชื่อวิดีโอ',
                'desc' => 'รายละเอียด',
                'tags' => 'ป้ายกำกับ',
                'subject_title' => 'วิชา',
                'chapter_title' => 'บทเรียน'
            );
        }

        $this->template->script_var(array(
            'ajax_grid_url' => site_url('resource/pvideo_manager/ajax_iframe_list/'),
            'input_id' => $input_id
        ));

        $this->template->application_script('resource/pvideo_manager/iframe_main_grid.js');
        $this->template->load_showloading();
        $this->template->load_flexgrid();
        $this->template->write_view('resource/pvideo_manager/iframe_grid', $data);
        $this->template->temmplate_name('normal');
        $this->template->render();
    }

    function ajax_iframe_list() {
        $a = $this->pvideo_manager_model->iframe_find_all($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query', TRUE), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE));
        echo json_encode($a);
    }

}
