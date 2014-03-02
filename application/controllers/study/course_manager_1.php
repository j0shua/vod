<?php

/**
 * โปรแกรมสำหรับจัดการ course
 * @property course_model $course_model
 * @property ddoption_model $ddoption_model
 * @property resource_menu_model $resource_menu_model
 * @property practice_model $practice_model
 * @property ptest_model $ptest_model
 */
class course_manager extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth->access_limit($this->auth->permis_course_manager);
        $this->load->model('study/course_model');
        $this->load->model('core/ddoption_model');
        $this->load->model('main_side_menu_model');
        $this->load->helper('form');
    }

    /**
     * ข้อมูลหลักสูตรของครู
     */
    function index() {
        $data = array(
            'title' => 'จัดการหลักสูตร',
            'publish_options' => $this->ddoption_model->get_publish_options(),
            'weight_options' => $this->ddoption_model->get_weight_options(),
        );
        $data['grid_menu'] = array(
            array('url' => site_url('study/course_manager/add_course'), 'title' => 'เพิ่มหลักสูตร', 'extra' => ''),
        );
        $data['qtype_options'] = array(
            'title' => 'ชื่อหลักสูตร',
            'c_id' => 'เลขที่หลักสูตร',
            'desc' => 'รายละเอียด',
            'degree_id' => 'ชั้นเรียน',
            'subject_title' => 'วิชา',
            'chapter_title' => 'บทเรียน'
        );
        $data['main_side_menu'] = $this->main_side_menu_model->study('course_manager');
        $this->template->script_var(
                array('ajax_grid_url' => site_url('study/course_manager/ajax_course'))
        );
        $this->template->application_script('study/course_manager/course_grid.js');
        $this->template->load_showloading();
        $this->template->load_flexgrid();
        $this->template->load_jquery_colorbox();
        $this->template->write_view('study/course_manager/main_grid', $data);
        $this->template->render();
    }

    /**
     * ajax ดึงข้อมูล หลักสูตรของครู
     */
    function ajax_course() {
        $a = $this->course_model->find_all_course($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query'), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE));
        echo json_encode($a);
    }

    /**
     * แก้ไขข้อมูล หลักสูตร
     * @param type $c_id
     */
    function edit_course($c_id) {
        //$this->course_model->init_course($c_id);
        $data = array(
            'form_title' => 'แก้ไขหลักสูตร',
            'form_action' => site_url('study/course_manager/do_save'),
            'form_data' => $this->course_model->get_course_data($c_id),
            'cancel_link' => site_url('study/course_manager'),
            'enroll_type_id_options' => $this->ddoption_model->get_enroll_type_options(),
            'enroll_limit_options' => $this->ddoption_model->get_enroll_limit_options(),
            'publish_options' => $this->ddoption_model->get_publish_options(),
            'degree_options' => $this->ddoption_model->get_degree_id_options(),
            'learning_area_options' => $this->ddoption_model->get_learning_area_options()
        );
        $this->template->script_var(array(
            'ajax_subject_options_url' => site_url('core/ajax_ddoptions/get_subject_options'),
            'ajax_chapter_autocomplete_url' => site_url('core/ajax_autocomplete/get_chapter'),
            'sheet_iframe_url' => site_url('resource/image_manager/iframe/'),
            'subj_id' => $data['form_data']['subj_id']
        ));
        $this->template->load_typeonly();
        $this->template->load_showloading();
        $this->template->application_script('study/course_manager/course_input_form.js');
        $this->template->write_view('study/course_manager/course_input_form', $data);
        $this->template->render();
    }

    /**
     * เพิ่มหลักสูตร
     */
    function add_course() {
        $this->load->helper('form');
        $data = array(
            'form_title' => 'เพิ่มหลักสูตร',
            'form_action' => site_url('study/course_manager/do_save'),
            'form_data' => $this->course_model->get_course_data(),
            'cancel_link' => site_url('study/course_manager'),
            'enroll_type_id_options' => $this->ddoption_model->get_enroll_type_options(),
            'enroll_limit_options' => $this->ddoption_model->get_enroll_limit_options(),
            'publish_options' => $this->ddoption_model->get_publish_options(),
            'degree_options' => $this->ddoption_model->get_degree_id_options(),
            'learning_area_options' => $this->ddoption_model->get_learning_area_options()
        );
        $this->template->script_var(array(
            'ajax_subject_options_url' => site_url('core/ajax_ddoptions/get_subject_options'),
            'ajax_chapter_autocomplete_url' => site_url('core/ajax_autocomplete/get_chapter'),
            'sheet_iframe_url' => site_url('resource/image_manager/iframe/'),
            'subj_id' => $data['form_data']['subj_id']
        ));
        $this->template->load_showloading();
        $this->template->load_typeonly();
        $this->template->application_script('study/course_manager/course_input_form.js');
        $this->template->write_view('study/course_manager/course_input_form', $data);
        $this->template->render();
    }

    function delete_course($c_id) {
        $data = array(
            'cancel_url' => site_url('study/course_manager'),
            'url' => site_url('study/course_manager/do_delete_course/' . $c_id),
            'heading' => 'ลบข้อมูลหลักสูตรทั้งหมด',
            'message' => '<p>ระบบจะลบข้อมูลหลักสูตรทั้งหมดและข้อมูลที่เกี่ยวข้อง</p>'
        );
        $this->load->view('confirm_page', $data);
    }

    function do_delete_course($c_id) {
        if ($this->course_model->delete_course($c_id)) {
            $data = array(
                'time' => 0,
                'url' => site_url('study/course_manager'),
                'heading' => 'ลบข้อมูลเสร็จส',
                'message' => '<p>บันทึกข้อมูลเสร็จสิ้น</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $data = array(
                'time' => 0,
                'url' => site_url('study/course_manager'),
                'heading' => 'ลบข้อมูลเสร็จส',
                'message' => '<p>บันทึกข้อมูลเสร็จสิ้น</p>'
            );
            $this->load->view('refresh_page', $data);
        }
    }

    function do_save() {
        if ($this->course_model->save_course($this->input->post('data'))) {
            $data = array(
                'time' => 0,
                'url' => site_url('study/course_manager'),
                'heading' => 'บันทึกข้อมูลเสร็จสิ้น',
                'message' => '<p>บันทึกข้อมูลเสร็จสิ้น</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $data = array(
                'time' => 0,
                'url' => site_url('study/course_manager'),
                'heading' => 'บันทึกข้อมูลเสร็จสิ้น',
                'message' => '<p>บันทึกข้อมูลเสร็จสิ้น</p>'
            );
            $this->load->view('refresh_page', $data);
        }
    }

    function student_course($c_id = '') {
        if ($c_id == '') {
            redirect('study/course_manager/');
        }
//        $this->course_model->init_course($c_id);
        $course_data = $this->course_model->get_course_data($c_id);
        $student_count = $this->course_model->get_count_student_in_course($c_id);
        $data = array(
            'title' => 'นักเรียนในหลักสูตร : ' . $course_data['title'],
            'desc' => $course_data['desc'],
            'student_count' => $student_count,
            'course_data' => $course_data
        );
        $data['grid_menu'] = array(
            array('url' => site_url('study/course_manager/course_act/' . $c_id), 'title' => '<', 'extra' => ''),
            array('url' => site_url('study/course_manager/print_student_course/' . $c_id), 'title' => 'พิมพ์รายชื่อนักเรียน', 'extra' => ''),
        );
//        if (isset($_SERVER['HTTP_REFERER'])) {
//
//            if ($_SERVER['HTTP_REFERER'] != site_url('study/course_manager/print_student_course/' . $c_id)) {
//                $data['grid_menu'] = array(
//                    array('url' => $_SERVER['HTTP_REFERER'], 'title' => '<', 'extra' => ''),
//                    array('url' => site_url('study/course_manager/print_student_course/' . $c_id), 'title' => 'พิมพ์รายชื่อนักเรียน', 'extra' => ''),
//                );
//            }
//        }


        $data['main_side_menu'] = $this->main_side_menu_model->study('course_manager');
        $this->template->script_var(
                array('ajax_grid_url' => site_url('study/course_manager/ajax_student_course/' . $c_id))
        );
        $this->template->application_script('study/course_manager/student_course_grid.js');
        $this->template->load_showloading();
        $this->template->load_flexgrid();
        $this->template->write_view('study/course_manager/student_course_grid', $data);
        $this->template->render();
    }

    function rp_student_course($c_id = '') {
        if ($c_id == '') {
            redirect('study/course_manager/');
        }
//        $this->course_model->init_course($c_id);
        $course_data = $this->course_model->get_course_data($c_id);
        $student_count = $this->course_model->get_count_student_in_course($c_id);
        $data = array(
            'title' => 'นักเรียนในหลักสูตร : ' . $course_data['title'],
            'desc' => $course_data['desc'],
            'student_count' => $student_count,
            'course_data' => $course_data
        );
        $data['grid_menu'] = array(
            array('url' => site_url('report/teacher_report'), 'title' => '<', 'extra' => ''),
            array('url' => site_url('study/course_manager/print_student_course/' . $c_id), 'title' => 'พิมพ์รายชื่อนักเรียน', 'extra' => ''),
        );
//        if (isset($_SERVER['HTTP_REFERER'])) {
//
//            if ($_SERVER['HTTP_REFERER'] != site_url('study/course_manager/print_student_course/' . $c_id)) {
//                $data['grid_menu'] = array(
//                    array('url' => $_SERVER['HTTP_REFERER'], 'title' => '<', 'extra' => ''),
//                    array('url' => site_url('study/course_manager/print_student_course/' . $c_id), 'title' => 'พิมพ์รายชื่อนักเรียน', 'extra' => ''),
//                );
//            }
//        }


        $data['main_side_menu'] = $this->main_side_menu_model->study('course_manager');
        $this->template->script_var(
                array('ajax_grid_url' => site_url('study/course_manager/ajax_student_course/' . $c_id))
        );
        $this->template->application_script('study/course_manager/student_course_grid.js');
        $this->template->load_showloading();
        $this->template->load_flexgrid();
        $this->template->write_view('study/course_manager/student_course_grid', $data);
        $this->template->render();
    }

    function print_student_course($c_id, $range = 25) {
        $this->load->helper('time');
        $course_data = $this->course_model->get_course_data($c_id);
        $data = array(
            'title' => 'นักเรียนในหลักสูตร',
            'desc' => $course_data['desc'],
            'course_data' => $course_data
        );
        $data['enroll_data'] = $this->course_model->get_all_course_enroll_data($c_id);
        $data['grid_menu'] = array(
            array('url' => site_url('study/course_manager/student_course/' . $c_id), 'title' => '<', 'extra' => ''),
            array('url' => site_url('study/course_manager/student_course_score/'), 'title' => 'พิมพ์', 'extra' => 'id="btn_print_page" onClick="window.print();return false"'),
        );
        $data['range'] = $range;
        //print_r($course_data);
        $this->template->write_view('study/course_manager/print_student_course', $data);
        $this->template->render();
    }

    function ajax_student_course($c_id) {
        $a = $this->course_model->find_all_student_course($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query'), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE), $c_id);
        echo json_encode($a);
    }

    function course_enroll_approve($ce_id) {
        if (isset($_SERVER['HTTP_REFERER'])) {
            $url = $_SERVER['HTTP_REFERER'];
        } else {
            $url = site_url('study/course_manager');
        }

        if ($this->course_model->course_enroll_approve($ce_id)) {
            $data = array(
                'time' => 0,
                'url' => $url,
                'heading' => 'ผลการอนุมัติ',
                'message' => '<p>อนุมัติการเข้าเรียนเสร็จสิ้น</p>'
            );
        } else {
            $data = array(
                'time' => 2,
                'url' => $url,
                'heading' => 'ผลการอนุมัติ',
                'message' => '<p>อนุมัติการเข้าเรียนไม่ผ่าน</p>'
            );
        }

        $this->load->view('refresh_page', $data);
    }

    function course_enroll_delete($ce_id) {
        $enroll_data = $this->course_model->get_course_enroll_data($ce_id);
        $data = array(
            'cancel_url' => site_url('study/course_manager/student_course/' . $enroll_data['c_id']),
            'url' => site_url('study/course_manager/do_course_enroll_delete/' . $ce_id),
            'heading' => 'เอานักเรียนออกจากหลักสูตร',
            'message' => '<p>ระบบจะดึง' . $enroll_data['user_datail']['full_name'] . ' ออกจากหลักสูตรพร้อมทั้งลบงานที่ได้ทำในหลักสูตรออกทั้งหมด</p>'
        );
        $this->load->view('confirm_page', $data);
    }

    function do_course_enroll_delete($ce_id) {
        $result = $this->course_model->course_enroll_delete($ce_id);
        $data = array(
            'time' => 0,
            'url' => site_url('study/course_manager/student_course/' . $result['course_enroll_data']['c_id']),
            'heading' => 'ผลการดึงนักเรียนออกจากหลักสูตร',
        );
        if ($result['success']) {
            $data['message'] = '<p>ดึงนักเรียนออกไปแล้ว</p>';
        } else {
            $data['message'] = '<p>ไม่สามารถดึงนักเรียนออกได้</p>';
        }

        $this->load->view('refresh_page', $data);
    }

//====================================================================================================================
// Course act
//====================================================================================================================
    /**
     * หน้างานต่างๆ ในหลักสูตรของครู
     * @param type $c_id
     */
    function course_act($c_id) {

        $course_data = $this->course_model->get_course_data($c_id);
        if ($course_data['uid_owner'] == $this->auth->uid()) {
            $student_count = $this->course_model->get_count_student_in_course($c_id);
            $data = array(
                'title' => 'แผนการสอน หลักสูตร : ' . $course_data['title'],
                'student_count' => $student_count,
                'course_data' => $course_data
            );
            $data['grid_menu'] = array(
                array('url' => site_url('study/course_manager'), 'title' => '<', 'extra' => ''),
                array('url' => site_url('study/course_manager/add_course_act/' . $c_id), 'title' => 'สั่งงาน', 'extra' => ''),
                array('url' => site_url('study/course_manager/student_course/' . $c_id), 'title' => 'รายชื่อนักเรียน [' . $student_count . ' คน]', 'extra' => ''),
                array('url' => site_url('study/course_manager/student_course_score/' . $c_id), 'title' => 'คะแนนนักเรียน', 'extra' => ''),
            );
            $data['main_side_menu'] = $this->main_side_menu_model->study('course_manager');
            $this->template->script_var(
                    array(
                        'ajax_grid_url' => site_url('study/course_manager/ajax_course_act/' . $c_id)
                    )
            );
            $this->template->application_script('study/course_manager/course_act_grid.js');
            $this->template->write_view('study/course_manager/course_act_grid', $data);
            $this->template->load_flexgrid();
            $this->template->load_jquery_colorbox();

            $this->template->render();
        } else {
            $data = array(
                'time' => 0,
                'url' => site_url('user/login'),
                'heading' => 'คุณต้องลงชื่อเข้าใช้ก่อน',
                'message' => '<p>คุณต้องลงชื่อเข้าใช้ก่อน</p>'
            );
            $this->load->view('refresh_page', $data);
        }
    }

    function ajax_course_act($c_id) {
        $a = $this->course_model->find_all_course_act($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query', TRUE), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE), $c_id);
        echo json_encode($a);
    }

    function add_course_act($c_id) {

        $st_id_options_normal = $st_id_options_sheet = $this->ddoption_model->get_send_type_options();
        $have_preposttest_options = $this->ddoption_model->have_preposttest_options();
        unset($st_id_options_normal[4]);

        $data = array(
            'title' => 'สร้างงาน',
            'form_action' => site_url('study/course_manager/do_save_course_act'),
            'cancel_url' => site_url('study/course_manager/course_act/' . $c_id),
            'cmat_id_options' => $this->ddoption_model->get_cmat_id_options(),
            'act_type_options' => $this->ddoption_model->get_act_type_options(),
            'st_id_options_normal' => $st_id_options_normal,
            //'st_id_options_sheet' => $st_id_options_sheet,
            'have_preposttest_options' => $have_preposttest_options,
            'form_data' => $this->course_model->get_course_act_data(),
            'can_edit' => TRUE,
            'can_edit_course_act_start_time' => TRUE
        );
        $st_id_options_normal_sheet_set = $st_id_options_normal_no_test = $st_id_options_normal = $st_id_options_normal;



//        $st_id_options_normal_test = $st_id_options_normal;
//        unset($st_id_options_normal_test[1]);
//        unset($st_id_options_normal_test[3]);
//        unset($st_id_options_normal_test[5]);
//        $st_id_options_sheet_test = $st_id_options_sheet;
//        unset($st_id_options_sheet_test[1]);
//        unset($st_id_options_sheet_test[3]);
//        unset($st_id_options_sheet_test[5]);
        //for ธรรมดา การบ้าน
        $tmp_option = array();
        foreach ($st_id_options_normal as $k => $v) {
            $tmp_option[] = "'" . $k . "' : '" . $v . "'";
        }
        $st_id_options_normal = '{' . implode(',', $tmp_option) . '}';

        //for sheet การบ้าน
        $tmp_option = array();
        foreach ($st_id_options_sheet as $k => $v) {
            $tmp_option[] = "'" . $k . "' : '" . $v . "'";
        }
        $st_id_options_sheet = '{' . implode(',', $tmp_option) . '}';

        //for ธรรมดา การสอบ
        $tmp_option = array();
        foreach ($st_id_options_normal_test as $k => $v) {
            $tmp_option[] = "'" . $k . "' : '" . $v . "'";
        }
        $st_id_options_normal_test = '{' . implode(',', $tmp_option) . '}';

        //for sheet การสอบ
        $tmp_option = array();
        foreach ($st_id_options_sheet_test as $k => $v) {
            $tmp_option[] = "'" . $k . "' : '" . $v . "'";
        }
        $st_id_options_sheet_test = '{' . implode(',', $tmp_option) . '}';



        $this->template->script_var(array(
            'sheet_browser_iframe_url' => site_url('resource/sheet/iframe/data'),
            'st_id_options_normal' => array('value' => $st_id_options_normal),
            'st_id_options_sheet' => array('value' => $st_id_options_sheet),
            'st_id_options_normal_test' => array('value' => $st_id_options_normal_test),
            'st_id_options_sheet_test' => array('value' => $st_id_options_sheet_test),
            'st_id' => 2,
            'can_edit_course_act_start_time' => array('value' => 'true')
        ));
        // print_r($data['form_data']);
        $data['form_data']['c_id'] = $c_id;
        $this->template->write_view('study/course_manager/course_act_form', $data);
        $this->template->application_script('study/course_manager/course_act_form.js');
        $this->template->load_jquery_ui_timepicker();
        $this->template->load_meio_mask();
        $this->template->load_typeonly();
        $this->template->render();
    }

    function delete_course_act($ca_id) {
        $course_act_data = $this->course_model->get_course_act_data($ca_id);
        $data = array(
            'cancel_url' => site_url('study/course_manager/course_act/' . $course_act_data['c_id']),
            'url' => site_url('study/course_manager/do_delete_course_act/' . $ca_id),
            'heading' => 'ลบข้อมูลงานทั้งหมด',
            'message' => '<p>ระบบจะลบข้อมูลข้องงาน "' . $course_act_data['title'] . '" ทั้งหมดรวมถึงคะแนนของนักเรียนด้วย</p>'
        );
        $this->load->view('confirm_page', $data);
    }

    function do_delete_course_act($ca_id) {
        $course_act_data = $this->course_model->get_course_act_data($ca_id);
        if ($this->course_model->delete_course_act($ca_id)) {
            $data = array(
                'time' => 0,
                'url' => site_url('study/course_manager/course_act/' . $course_act_data['c_id']),
                'heading' => 'ลบข้อมูลเสร็จสิ้น',
                'message' => '<p>บันทึกข้อมูลเสร็จสิ้น</p>'
            );
        } else {
            $data = array(
                'time' => 0,
                'url' => site_url('study/course_manager/course_act/' . $course_act_data['c_id']),
                'heading' => 'ลบข้อมูลผิดพลาด',
                'message' => '<p>เกิดข้อผิดพลาดในการลบข้อมูล</p>'
            );
        }
        $this->load->view('refresh_page', $data);
    }

    function edit_course_act($ca_id) {
        $st_id_options_normal = $st_id_options_sheet = $this->ddoption_model->get_send_type_options();
        unset($st_id_options_normal[4]);
        $have_preposttest_options = $this->ddoption_model->have_preposttest_options();
        $course_act_data = $this->course_model->get_course_act_data($ca_id);
        $can_edit = $this->course_model->can_edit_course_act($ca_id);

        $can_edit_course_act_start_time = $can_edit;
        $can_edit_course_act_start_time = $this->course_model->can_edit_course_act_start_time($ca_id);
        $data = array(
            'title' => 'แก้ไขงาน',
            'form_action' => site_url('study/course_manager/do_save_course_act'),
            'cancel_url' => site_url('study/course_manager/course_act/' . $course_act_data['c_id']),
            'cmat_id_options' => $this->ddoption_model->get_cmat_id_options(),
            'act_type_options' => $this->ddoption_model->get_act_type_options(),
            'st_id_options_normal' => $st_id_options_normal,
            'st_id_options_sheet' => $st_id_options_sheet,
            'have_preposttest_options' => $have_preposttest_options,
            'form_data' => $course_act_data,
            'can_edit' => $can_edit,
            'can_edit_course_act_start_time' => $can_edit_course_act_start_time
        );


        $st_id_options_normal_test = $st_id_options_normal;
        unset($st_id_options_normal_test[1]);
        unset($st_id_options_normal_test[3]);
        unset($st_id_options_normal_test[5]);
        $st_id_options_sheet_test = $st_id_options_sheet;
        unset($st_id_options_sheet_test[1]);
        unset($st_id_options_sheet_test[3]);
        unset($st_id_options_sheet_test[5]);
        //for ธรรมดา การบ้าน
        $tmp_option = array();
        foreach ($st_id_options_normal as $k => $v) {
            $tmp_option[] = "'" . $k . "' : '" . $v . "'";
        }
        $st_id_options_normal = '{' . implode(',', $tmp_option) . '}';

        //for sheet การบ้าน
        $tmp_option = array();
        foreach ($st_id_options_sheet as $k => $v) {
            $tmp_option[] = "'" . $k . "' : '" . $v . "'";
        }
        $st_id_options_sheet = '{' . implode(',', $tmp_option) . '}';

        //for ธรรมดา การสอบ
        $tmp_option = array();
        foreach ($st_id_options_normal_test as $k => $v) {
            $tmp_option[] = "'" . $k . "' : '" . $v . "'";
        }
        $st_id_options_normal_test = '{' . implode(',', $tmp_option) . '}';

        //for sheet การสอบ
        $tmp_option = array();
        foreach ($st_id_options_sheet_test as $k => $v) {
            $tmp_option[] = "'" . $k . "' : '" . $v . "'";
        }
        $st_id_options_sheet_test = '{' . implode(',', $tmp_option) . '}';


        $this->template->script_var(array(
            'sheet_browser_iframe_url' => site_url('resource/sheet/iframe/data'),
            'st_id_options_normal' => array('value' => $st_id_options_normal),
            'st_id_options_sheet' => array('value' => $st_id_options_sheet),
            'st_id_options_normal_test' => array('value' => $st_id_options_normal_test),
            'st_id_options_sheet_test' => array('value' => $st_id_options_sheet_test),
            'st_id' => $course_act_data['st_id'],
            'can_edit_course_act_start_time' => array('value' => ($can_edit_course_act_start_time) ? 'true' : 'false'),
            'start_time' => date('m/d/Y')
        ));
        $this->template->write_view('study/course_manager/course_act_form', $data);
        $this->template->application_script('study/course_manager/course_act_form.js');

        $this->template->load_jquery_ui_timepicker();
        $this->template->load_meio_mask();
        $this->template->load_typeonly();
        $this->template->render();
    }

    function do_save_course_act() {
        $post_data = $this->input->post('data');

//        if ($post_data['cmat_id'] == 2) {
//            $post_data['st_id'] = $post_data['st_id_sheet'];
//            unset($post_data['st_id_sheet']);
//        }

        if ($this->course_model->save_assign_act($post_data)) {
            $data = array(
                'time' => 0,
                'url' => site_url('study/course_manager/course_act/' . $post_data['c_id']),
                'heading' => 'บันทึกข้อมูลเสร็จสิ้น',
                'message' => '<p>บันทึกข้อมูลเสร็จสิ้น</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            
        }
    }

    /**
     * หน้างาน จะมีรายชื่อนักเรียนที่ต้องทำงานนั้น
     * @param type $ca_id
     */
    function student_act($ca_id) {
        $act_data = $this->course_model->get_course_act_data($ca_id);
        $course_act_options = $this->course_model->get_course_act_options($act_data['c_id']);
        $data = array(
            'title1' => 'งาน : ' . $act_data['title'],
            'act_data' => $act_data,
            'title2' => 'รายชื่อนักเรียนที่ได้รับงาน : ' . form_dropdown("", $course_act_options, $ca_id, 'id="sel_course_act"'),
        );
        $data['open_sheet_url'] = FALSE;
        if ($act_data['cmat_id'] == 2) {
            $data['open_sheet_url'] = site_url('play/play_resource/open_sheet/' . $act_data['data'] . '/' . $act_data['uid_assign']);
        }
        $data['grid_menu'] = array(
            array('url' => site_url('study/course_manager/course_act/' . $act_data['c_id']), 'title' => '<', 'extra' => ''),
        );
        $data['main_side_menu'] = $this->main_side_menu_model->study('course_manager');
        $this->template->script_var(array(
            'ajax_grid_url' => site_url('study/course_manager/ajax_student_course_act/' . $ca_id),
            'course_act_url' => site_url('study/course_manager/student_act') . '/'
        ));

        $this->template->application_script('study/course_manager/student_act_grid.js');
        $this->template->write_view('study/course_manager/student_act_grid', $data);
        $this->template->load_showloading();
        $this->template->load_flexgrid();
        $this->template->render();
    }

    function ajax_student_course_act($c_id) {
        $a = $this->course_model->find_all_student_course_act($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query'), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE), $c_id);
        echo json_encode($a);
    }

    /**
     * ให้คะแนนลูกศิษย์ในแต่ละงาน
     * @param type $ca_id
     * @param type $uid
     */
    function give_score_student_act($ca_id, $uid_sender) {
        $course_act_data = $this->course_model->get_course_act_data($ca_id);
        $send_act_data = $this->course_model->get_send_act_data($ca_id, $uid_sender);

        $data = array(
            'title' => 'ให้คะแนน : ' . $course_act_data['title'],
            'form_action' => site_url('study/course_manager/do_give_score_student_act'),
            'cancel_link' => site_url('study/course_manager/student_act/' . $ca_id),
            'course_act_data' => $course_act_data,
            'form_data' => $send_act_data,
            'user_detail' => $this->auth->get_user_data($uid_sender),
            'give_score_options' => $this->course_model->get_give_score_options(0, $course_act_data['full_score'])
        );


        switch ($course_act_data['st_id']) {
            case 1://uploadd
                $this->template->application_script('study/course_manager/give_score_act_upload.js');
                $this->template->write_view('study/course_manager/give_score_act_upload', $data);
                break;
            case 2://ส่งเป็นกระดาษที่โต๊ะ
                $data['form_data']['c_id'] = $course_act_data['c_id'];
                $data['form_data']['ca_id'] = $ca_id;
                $data['form_data']['uid_sender'] = $uid_sender;
                $this->template->load_jquery_ui_timepicker();
                $this->template->load_meio_mask();
                $this->template->application_script('study/course_manager/give_score_act_table.js');
                $this->template->write_view('study/course_manager/give_score_act_table', $data);
                break;
            case 3://textarea
                $this->template->application_script('study/course_manager/give_score_act_textarea.js');
                $this->template->write_view('study/course_manager/give_score_act_textarea', $data);
                break;
            default:
                break;
        }
        $this->template->render();
    }

    /**
     * ทำการให้คะแนน
     */
    function do_give_score_student_act() {
        $post_data = $this->input->post('form_data');

        if ($this->course_model->give_score_course_act($post_data)) {
            $data = array(
                'time' => 0,
                'url' => site_url('study/course_manager/student_act/' . $post_data['ca_id']),
                'heading' => 'ผลการให้คะแนน',
                'message' => '<p>ให้คะแนนเสร็จสิ้น</p>'
            );
        } else {
            $data = array(
                'time' => 0,
                'url' => site_url('study/course_manager/student_act/' . $post_data['ca_id']),
                'heading' => 'ผลการให้คะแนน',
                'message' => '<p>ไม่สามารถให้คะแนนได้</p>'
            );
        }
        $this->load->view('refresh_page', $data);
    }

    function student_course_score($c_id) {

        $studeunt_score_data = $this->course_model->get_studeunt_score_data($c_id);


        $data['title'] = 'คะแนนของนักเรียน';
        $data['studeunt_score_data'] = $studeunt_score_data;



        if (isset($_SERVER['HTTP_REFERER'])) {
            $data['grid_menu'] = array(
                array('url' => $_SERVER['HTTP_REFERER'], 'title' => '<', 'extra' => ''),
                array('url' => site_url('study/course_manager/student_course_score/'), 'title' => 'พิมพ์', 'extra' => 'id="btn_print_page" onClick="window.print();return false"'),
            );
        } else {
            $data['grid_menu'] = array(
                array('url' => site_url('study/course_manager/course_act/' . $c_id), 'title' => '<', 'extra' => ''),
                array('url' => site_url('study/course_manager/student_course_score/'), 'title' => 'พิมพ์', 'extra' => 'id="btn_print_page" onClick="window.print();return false"'),
            );
        }
//        $data['grid_menu'] = array(
//            array('url' => site_url('study/course_manager/course_act/' . $c_id), 'title' => '<', 'extra' => ''),
//            array('url' => site_url('study/course_manager/student_course_score/'), 'title' => 'พิมพ์', 'extra' => 'id="btn_print_page" onClick="window.print();return false"'),
//        );
        //$this->template->temmplate_name('normal');
        $this->template->write_view('study/course_manager/student_course_score', $data);
        $this->template->render();
    }

    //สรุปผล
    function summary_practice($ca_id) {
        $this->load->model('study/practice_model');
        $course_act_data = $this->course_model->get_course_act_data($ca_id);
        $summary_data = $this->practice_model->get_all_summary($ca_id);
        if ($summary_data) {
            $data['title'] = 'อันดับคะแนนซ้อมสอบ : ' . $course_act_data['title'];
            $data['summary_data'] = $summary_data;
            $this->template->write_view('study/course_manager/all_summary_practice', $data);
            $this->template->render();
        } else {
            $data = array(
                'time' => 0,
                'url' => site_url('study/course_manager/course_act/' . $course_act_data['c_id']),
                'heading' => 'ไม่มีข้อมูลผู้ฝึกสอบ',
                'message' => '<p>ไม่มีข้อมูลผู้ฝึกสอบ</p>'
            );
            $this->load->view('refresh_page', $data);
        }
    }

    function summary_ptest($ca_id) {
        $this->load->model('study/ptest_model');
        $course_act_data = $this->course_model->get_course_act_data($ca_id);
        $summary_data = $this->ptest_model->get_all_summary($ca_id);
        // print_r($summary_data);
        if ($summary_data) {
            $data['title'] = $course_act_data['title'];
            $data['summary_data'] = $summary_data;
            $this->template->write_view('study/course_manager/all_summary_ptest', $data);
            $this->template->render();
        } else {
            $data = array(
                'time' => 0,
                'url' => site_url('study/course_manager/course_act/' . $course_act_data['c_id']),
                'heading' => 'ไม่มีข้อมูลผู้ฝึกสอบ',
                'message' => '<p>ไม่มีข้อมูลผู้สอบ พรีเทส โพสเทส</p>'
            );
            //$this->load->view('refresh_page', $data);
        }
    }

    function view_course_details($c_id) {


        $data['course_data'] = $this->course_model->get_course_data($c_id);

        $data['course_act_data'] = $this->course_model->find_all_course_act(1, '', '', 1000, 'start_time', 'asc', $c_id);
        $data['title'] = 'หลักสูตร : ' . $data['course_data']['title'];

        if (isset($_SERVER['HTTP_REFERER'])) {
            $referent_url = $_SERVER['HTTP_REFERER'];
        } else {
            $referent_url = site_url('house/u/' . $data['course_data']['uid_owner']);
        }
        $data['grid_menu'] = array(
            array('url' => $referent_url, 'title' => '< กลับ', 'extra' => '')
        );
        if ($data['course_act_data']['total'] > 0) {
            $data['grid_menu'][] = array('url' => site_url('study/course_manager/copy_course/' . $c_id), 'title' => 'คัดลอกหลักสูตรนี้', 'extra' => '');
        }
        $this->template->write_view('study/course_manager/view_course_details', $data);
        $this->template->render();
    }

    function copy_course($c_id) {
        $this->course_model->copy_course($c_id);
        $data = array(
            'time' => 0,
            'url' => site_url('study/course_manager'),
            'heading' => 'ทำการคัดลอกเสร็จสมบูรณ์',
            'message' => '<p>ทำการคัดลอกเสร็จสมบูรณ์</p>'
        );
        $this->load->view('refresh_page', $data);
    }

    function course_open() {
        $this->template->load_showloading();
        $this->template->load_flexgrid();
        $this->template->application_script('study/course_manager/course_open_grid.js');
        $data['title'] = 'หลักสูตรการเรียนที่ลงทะเบียน';
        $data['qtype_options'] = array(
            'title' => 'ชื่อหลักสูตร',
            'c_id' => 'เลขที่หลักสูตร',
            'desc' => 'รายละเอียด',
            'degree_id' => 'ชั้นเรียน',
            'subject_title' => 'วิชา',
            'chapter_title' => 'บทเรียน',
            'full_name_owner' => 'ชื่อผู้สอน',
            'school_name_owner' => 'โรงเรียน'
        );
        $data['default_qtype'] = 'subject_title';
        $data['degree_options'] = $this->ddoption_model->get_degree_id_options('-- เลือกชั้นเรียน --');

        $this->template->script_var(
                array(
                    'ajax_grid_url' => site_url('study/course_manager/ajax_grid_course_open')
                )
        );
        $data['main_side_menu'] = $this->main_side_menu_model->study('course_open');
        $this->template->write_view('study/course_manager/course_open_grid', $data);
        $this->template->render();
    }

    /**
     * เรียกหลักสูตรที่เปิดให้สมัคร
     */
    function ajax_grid_course_open() {
        $a = $this->course_model->find_all_course_open_teacher($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query', TRUE), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE));
        echo json_encode($a);
    }

}
