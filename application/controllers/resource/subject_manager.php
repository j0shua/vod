<?php

/**
 * จัดการวิชา
 * 
 * @property subject_manager_model $subject_manager_model
 */
class subject_manager extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('resource/subject_manager_model');
        $this->load->model('main_side_menu_model');
        $this->load->model('admin/admin_menu_model');
        $this->load->helper('form');
    }

    function index() {
        $this->template->load_showloading();
        $this->template->load_flexgrid();
        $this->template->load_jquery_colorbox();


        $data['title'] = 'กลุ่มสาระ';
        $data['grid_menu'] = array();
        if ($this->auth->is_admin()) {
            $this->template->application_script('resource/subject_manager/admin_learning_area_grid.js');
            $data['grid_menu'] = array(
                    //array('url' => site_url('resource/subject_manager/add_subject'), 'title' => 'เพิ่มกลุ่มสาระ', 'extra' => ''),
            );
        } else {
            $this->template->application_script('resource/subject_manager/learning_area_grid.js');
        }
        $data['main_side_menu'] = $this->main_side_menu_model->study('subject_manager');
        $this->template->script_var(
                array(
                    'ajax_grid_url' => site_url('resource/subject_manager/ajax_learning_area_list')
                )
        );
        $this->template->write_view('resource/subject_manager/learning_area_grid', $data);
        $this->template->render();
    }

    function ajax_learning_area_list($is_main = FALSE) {
        $a = $this->subject_manager_model->learning_area_find_all($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query', TRUE), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE), $is_main);
        echo json_encode($a);
    }

    function subject($la_id) {
        $learning_area_data = $this->subject_manager_model->get_learning_area_data($la_id);
        $this->template->load_showloading();
        $this->template->load_flexgrid();
        $this->template->application_script('resource/subject_manager/subject_grid.js');
        $data['main_title'] = 'บริหารระบบ';
        $data['title'] = 'กลุ่มสาระ ' . $learning_area_data['title'] . ' > จัดการวิชา';
        $data['grid_menu'] = array(
            array('url' => site_url('resource/subject_manager'), 'title' => '<', 'extra' => ''),
            array('url' => site_url('resource/subject_manager/add_subject/' . $la_id), 'title' => 'เพิ่มวิชา', 'extra' => '')
        );

        $data['main_side_menu'] = $this->main_side_menu_model->study('subject_manager');
        $this->template->script_var(
                array(
                    'ajax_grid_url' => site_url('resource/subject_manager/ajax_subject_list/' . $la_id)
                )
        );
        $this->template->write_view('resource/subject_manager/subject_grid', $data);
        $this->template->render();
    }

    function ajax_subject_list($la_id, $is_main = FALSE) {
        $a = $this->subject_manager_model->subject_find_all($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query', TRUE), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE), $la_id, $is_main);
        echo json_encode($a);
    }

    function add_subject($la_id) {
//        $this->template->application_script('resource/dycontent/input_material_xelatex_form.js');
        $data = array(
            'title' => 'เพิ่มวิชา',
            'form_data' => array(),
            'form_action' => site_url('resource/subject_manager/do_save_subject'),
            'cancel_link' => site_url('resource/subject_manager/subject/' . $la_id),
        );
        $data['form_data'] = $this->subject_manager_model->get_subject_data();
        $data['form_data']['la_id'] = $la_id;
        if ($this->auth->can_access($this->auth->permis_main_subject_manager)) {
            $data['form_data']['uid_owner'] = 0;
        } else {
            $data['form_data']['uid_owner'] = $this->auth->uid();
        }
        $this->template->write_view('resource/subject_manager/input_subject_form', $data);
        $this->template->render();
    }

    function edit_subject($subj_id = '') {
        if ($subj_id == '') {
            exit("");
        }
        $subject_data = $this->subject_manager_model->get_subject_data($subj_id);
        $data = array(
            'title' => 'แก้ไขวิชา',
            'form_data' => array(),
            'form_action' => site_url('resource/subject_manager/do_save_subject'),
            'cancel_link' => site_url('resource/subject_manager/subject/' . $subject_data['la_id']),
        );
        $data['form_data'] = $subject_data;
        $this->template->write_view('resource/subject_manager/input_subject_form', $data);
        $this->template->render();
    }

    function do_save_subject() {
        $data = $this->input->post('data');
        $la_id = $data['la_id'];
        if ($this->subject_manager_model->save_subject($data)) {
            $refresh_data = array(
                'time' => 1,
                'url' => site_url('resource/subject_manager/subject/' . $la_id),
                'heading' => 'บันทึกข้อมูลเสร็จสิ้น',
                'message' => '<p>บันทึกข้อมูลเสร็จสิ้น</p>'
            );
        } else {
            $refresh_data = array(
                'time' => 1,
                'url' => site_url('resource/subject_manager/subject/' . $la_id),
                'heading' => 'ไม่สามารถบันทึกข้อมูลได้',
                'message' => '<p>ไม่สามารถบันทึกข้อมูลได้</p>'
            );
        }
        $this->load->view('refresh_page', $refresh_data);
    }

    // chapter manage
    function chapter($subj_id) {
        $subject_data = $this->subject_manager_model->get_subject_data($subj_id);
        $this->template->load_showloading();
        $this->template->load_flexgrid();
        $this->template->application_script('resource/subject_manager/chapter_grid.js');

        $data['title'] = 'วิชา ' . $subject_data['title'] . ' > จัดการบท';
        $data['grid_menu'] = array(
            array('url' => site_url('resource/subject_manager'), 'title' => '<', 'extra' => ''),
            array('url' => site_url('resource/subject_manager/add_chapter/' . $subj_id), 'title' => 'เพิ่มบท', 'extra' => '')
        );

        $data['main_side_menu'] = $this->main_side_menu_model->study('subject_manager');
        $this->template->script_var(
                array(
                    'ajax_grid_url' => site_url('resource/subject_manager/ajax_chapter_list/' . $subj_id)
                )
        );
        $this->template->write_view('resource/subject_manager/chapter_grid', $data);
        $this->template->render();
    }

    function ajax_chapter_list($subj_id, $is_main = FALSE) {
        $a = $this->subject_manager_model->chapter_find_all($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query', TRUE), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE), $subj_id, $is_main);
        echo json_encode($a);
    }
     function add_chapter($subj_id) {
//        $this->template->application_script('resource/dycontent/input_material_xelatex_form.js');
        $data = array(
            'title' => 'เพิ่มบท',
            'form_data' => array(),
            'form_action' => site_url('resource/subject_manager/do_save_chapter'),
            'cancel_link' => site_url('resource/subject_manager/chapter/' . $subj_id),
        );
        $data['form_data'] = $this->subject_manager_model->get_chapter_data();
        $data['form_data']['subj_id'] = $subj_id;
        if ($this->auth->can_access($this->auth->permis_main_subject_manager)) {
            $data['form_data']['uid_owner'] = 0;
        } else {
            $data['form_data']['uid_owner'] = $this->auth->uid();
        }
        $this->template->write_view('resource/subject_manager/input_chapter_form', $data);
        $this->template->render();
    }
     function edit_chapter($chapter_id = '') {
        if ($chapter_id == '') {
            exit("");
        }
        $chapter_data = $this->subject_manager_model->get_chapter_data($chapter_id);
        
        $data = array(
            'title' => 'แก้ไขบท',
            'form_data' => array(),
            'form_action' => site_url('resource/subject_manager/do_save_chapter'),
            'cancel_link' => site_url('resource/subject_manager/chapter/' . $chapter_data['subj_id']),
        );
        $data['form_data'] = $chapter_data;
        $this->template->write_view('resource/subject_manager/input_chapter_form', $data);
        $this->template->render();
    }
      function do_save_chapter() {
        $data = $this->input->post('data');
        $subj_id = $data['subj_id'];
        if ($this->subject_manager_model->save_chapter($data)) {
            $refresh_data = array(
                'time' => 1,
                'url' => site_url('resource/subject_manager/chapter/' . $subj_id),
                'heading' => 'บันทึกข้อมูลเสร็จสิ้น',
                'message' => '<p>บันทึกข้อมูลเสร็จสิ้น</p>'
            );
        } else {
            $refresh_data = array(
                'time' => 1,
                'url' => site_url('resource/subject_manager/chapter/' . $subj_id),
                'heading' => 'ไม่สามารถบันทึกข้อมูลได้',
                'message' => '<p>ไม่สามารถบันทึกข้อมูลได้</p>'
            );
        }
        $this->load->view('refresh_page', $refresh_data);
    }

// forradmin =====================================================================

    /**
     * 
     */
    function main_learning_area() {
        $this->template->load_showloading();
        $this->template->load_flexgrid();
        $data['main_title'] = 'จัดการสื่อ';
        $data['title'] = 'กลุ่มสาระ';

        $this->template->application_script('resource/subject_manager/learning_area_grid.js');
        $data['grid_menu'] = array(
                // array('url' => site_url('resource/subject_manager/add_main_subject'), 'title' => 'เพิ่มกลุ่มสาระ', 'extra' => ''),
        );
        $data['main_side_menu'] = $this->admin_menu_model->main_side_menu('subject_manager');
        $this->template->script_var(
                array(
                    'ajax_grid_url' => site_url('resource/subject_manager/ajax_learning_area_list/main')
                )
        );
        $this->template->write_view('resource/subject_manager/learning_area_grid', $data);
        $this->template->render();
    }

    function main_subject($la_id) {
        $learning_area_data = $this->subject_manager_model->get_learning_area_data($la_id);
        $this->template->load_showloading();
        $this->template->load_flexgrid();
        $this->template->application_script('resource/subject_manager/subject_grid.js');
        $data['main_title'] = 'บริหารระบบ';
        $data['title'] = 'กลุ่มสาระ ' . $learning_area_data['title'] . ' > จัดการวิชา';
        $data['grid_menu'] = array(
            array('url' => site_url('resource/subject_manager/main_learning_area'), 'title' => '<', 'extra' => ''),
            array('url' => site_url('resource/subject_manager/add_main_subject/' . $la_id), 'title' => 'เพิ่มวิชา', 'extra' => '')
        );
        $data['main_side_menu'] = $this->admin_menu_model->main_side_menu('subject_manager');
        $this->template->script_var(
                array(
                    'ajax_grid_url' => site_url('resource/subject_manager/ajax_subject_list/' . $la_id . '/main')
                )
        );


        $this->template->write_view('resource/subject_manager/subject_grid', $data);
        $this->template->render();
    }

    function edit_main_subject($subj_id = '') {
        if ($subj_id == '') {
            exit("");
        }
        $subject_data = $this->subject_manager_model->get_subject_data($subj_id);
        $data = array(
            'title' => 'แก้ไขวิชา',
            'form_data' => array(),
            'form_action' => site_url('resource/subject_manager/do_save_main_subject'),
            'cancel_link' => site_url('resource/subject_manager/main_subject/' . $subject_data['la_id']),
        );
        $data['form_data'] = $subject_data;
        $this->template->write_view('resource/subject_manager/input_subject_form', $data);
        $this->template->render();
    }

    function add_main_subject($la_id) {
        if ($la_id == '') {
            exit("");
        }
        $data = array(
            'title' => 'เพิ่มวิชา',
            'form_data' => array(),
            'form_action' => site_url('resource/subject_manager/do_save_main_subject'),
            'cancel_link' => site_url('resource/subject_manager/main_subject/' . $la_id),
        );
        $data['form_data'] = $this->subject_manager_model->get_subject_data();
        $data['form_data']['uid_owner'] = 0;
        $data['form_data']['la_id'] = $la_id;
        $this->template->application_script('resource/subject_manager/input_subject_form.js');
        $this->template->write_view('resource/subject_manager/input_subject_form', $data);
        $this->template->render();
    }

    function do_save_main_subject() {
        $data = $this->input->post('data');
        $la_id = $data['la_id'];
        if ($this->subject_manager_model->save_subject($data)) {
            $refresh_data = array(
                'time' => 1,
                'url' => site_url('resource/subject_manager/main_subject/' . $la_id),
                'heading' => 'บันทึกข้อมูลเสร็จสิ้น',
                'message' => '<p>บันทึกข้อมูลเสร็จสิ้น</p>'
            );
        } else {
            $refresh_data = array(
                'time' => 1,
                'url' => site_url('resource/subject_manager/main_subject/' . $la_id),
                'heading' => 'ไม่สามารถบันทึกข้อมูลได้',
                'message' => '<p>ไม่สามารถบันทึกข้อมูลได้</p>'
            );
        }
        $this->load->view('refresh_page', $refresh_data);
    }

    
     // chapter manage
    function main_chapter($subj_id) {
        $subject_data = $this->subject_manager_model->get_subject_data($subj_id);
        $this->template->load_showloading();
        $this->template->load_flexgrid();
        $this->template->application_script('resource/subject_manager/chapter_grid.js');

        $data['title'] = 'วิชา ' . $subject_data['title'] . ' > จัดการบท';
        $data['grid_menu'] = array(
            array('url' => site_url('resource/subject_manager'), 'title' => '<', 'extra' => ''),
            array('url' => site_url('resource/subject_manager/add_main_chapter/' . $subj_id), 'title' => 'เพิ่มบท', 'extra' => '')
        );

        $data['main_side_menu'] = $this->main_side_menu_model->study('subject_manager');
        $this->template->script_var(
                array(
                    'ajax_grid_url' => site_url('resource/subject_manager/ajax_chapter_list/' . $subj_id.'/main')
                )
        );
        $this->template->write_view('resource/subject_manager/chapter_grid', $data);
        $this->template->render();
    }

   
     function add_main_chapter($subj_id) {
//        $this->template->application_script('resource/dycontent/input_material_xelatex_form.js');
        $data = array(
            'title' => 'เพิ่มบท',
            'form_data' => array(),
            'form_action' => site_url('resource/subject_manager/do_save_main_chapter'),
            'cancel_link' => site_url('resource/subject_manager/main_chapter/' . $subj_id),
        );
        $data['form_data'] = $this->subject_manager_model->get_chapter_data();
        $data['form_data']['subj_id'] = $subj_id;
        if ($this->auth->can_access($this->auth->permis_main_subject_manager)) {
            $data['form_data']['uid_owner'] = 0;
        } else {
            $data['form_data']['uid_owner'] = $this->auth->uid();
        }
        $this->template->write_view('resource/subject_manager/input_chapter_form', $data);
        $this->template->render();
    }
     function edit_main_chapter($chapter_id = '') {
        if ($chapter_id == '') {
            exit("");
        }
        $chapter_data = $this->subject_manager_model->get_chapter_data($chapter_id);
        
        $data = array(
            'title' => 'แก้ไขบท',
            'form_data' => array(),
            'form_action' => site_url('resource/subject_manager/do_save_main_chapter'),
            'cancel_link' => site_url('resource/subject_manager/main_chapter/' . $chapter_data['subj_id']),
        );
        $data['form_data'] = $chapter_data;
        $this->template->write_view('resource/subject_manager/input_chapter_form', $data);
        $this->template->render();
    }
      function do_save_main_chapter() {
        $data = $this->input->post('data');
        $subj_id = $data['subj_id'];
        if ($this->subject_manager_model->save_chapter($data)) {
            $refresh_data = array(
                'time' => 1,
                'url' => site_url('resource/subject_manager/main_chapter/' . $subj_id),
                'heading' => 'บันทึกข้อมูลเสร็จสิ้น',
                'message' => '<p>บันทึกข้อมูลเสร็จสิ้น</p>'
            );
        } else {
            $refresh_data = array(
                'time' => 1,
                'url' => site_url('resource/subject_manager/main_chapter/' . $subj_id),
                'heading' => 'ไม่สามารถบันทึกข้อมูลได้',
                'message' => '<p>ไม่สามารถบันทึกข้อมูลได้</p>'
            );
        }
        $this->load->view('refresh_page', $refresh_data);
    }
}