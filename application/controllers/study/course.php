<?php

/**
 * โปรแกรมสำหรับนักเรียนที่อยู่ใน course ทำกิจกรรมต่างๆ
 * @property course_model $course_model
 * @property sheet_model $sheet_model
 * @property exam_model $exam_model
 * @property xelatex_exam_model $xelatex_exam_model
 * @property ddoption_model $ddoption_model
 */
class course extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth->access_limit($this->auth->permis_course);
        $this->load->model('study/course_model');
        $this->load->model('study/study_menu_model');
        $this->load->helper('form');
    }

    /**
     * หลักสูตรที่นักเรียนกำลังเรียนอยู่
     */
    function index() {

        $this->template->load_showloading();
        $this->template->load_flexgrid();
        $this->template->application_script('study/course/my_course_grid.js');
        $data['title'] = 'หลักสูตรการเรียนที่ลงทะเบียน';
        $data['qtype_options'] = array(
            'title' => 'ชื่อหลักสูตร',
            'c_id' => 'เลขที่หลักสูตร',
            'desc' => 'รายละเอียด',
            'degree_id' => 'ชั้นเรียน',
            'subject_title' => 'วิชา',
            'chapter_title' => 'บทเรียน'
        );
        $data['main_side_menu'] = $this->study_menu_model->main_side_menu_student('course_enroll');
        $this->template->script_var(
                array(
                    'ajax_grid_url' => site_url('study/course/ajax_my_course')
                )
        );
        $this->template->write_view('study/course/my_course_grid', $data);
        $this->template->render();
    }

    /**
     * เรียกหลักสูตรที่ตนเองกำลังเรียนอยู่
     */
    function ajax_my_course() {
        $a = $this->course_model->find_all_my_course($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query', TRUE), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE));
        echo json_encode($a);
    }

    /**
     * หลักสูตรที่กำลังเปิดให้สมัครอยู่
     */
    function course_open() {
        $this->template->load_showloading();
        $this->template->load_flexgrid();
        $this->template->application_script('study/course/course_open_grid.js');
        $data['title'] = 'หลักสูตรการเรียนที่เปิด';
        $data['qtype_options'] = array(
            'title' => 'ชื่อหลักสูตร',
            'c_id' => 'เลขที่หลักสูตร',
            'desc' => 'รายละเอียด',
            'degree_id' => 'ชั้นเรียน',
            'subject_title' => 'วิชา',
            'chapter_title' => 'บทเรียน',
            'full_name_owner' => 'ชื่อผู้สอน'
        );
        $data['degree_options'] = $this->ddoption_model->get_degree_id_options('-- เลือกชั้นเรียน --');
        $this->template->script_var(
                array(
                    'ajax_grid_url' => site_url('study/course/ajax_grid_course_open')
                )
        );
        $data['main_side_menu'] = $this->study_menu_model->main_side_menu_student('course_open');
        $this->template->write_view('study/course/course_open_grid', $data);
        $this->template->render();
    }

    /**
     * เรียกหลักสูตรที่เปิดให้สมัคร
     */
    function ajax_grid_course_open() {
        $a = $this->course_model->find_all_course_open($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query', TRUE), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE));
        echo json_encode($a);
    }

    /**
     * เข้าหน้าหลักสูตร จะมี เมนูสำหรับนักเรียนด้วย
     * @param type $c_id
     */
    function course_act($c_id) {
        $enroll_data = $this->course_model->get_my_course_enroll_data($c_id);
        if ($enroll_data) {
            if ($enroll_data['enroll_type_id'] == 2 && $enroll_data['active'] == 0) {
                $data = array(
                    'time' => 10,
                    'url' => site_url('study/course'),
                    'heading' => 'ต้องรอการอนุมิติ',
                    'message' => '<p>ต้องรอการอนุมิติจากครูผู้สอนเพื่อเข้าชั้นเรียน</p>',
                );
                $this->load->view('refresh_page', $data);
            } else {
                $data = array(
                    'title' => 'หลักสูตรการเรียน ' . $enroll_data['title'],
                    'main_side_menu' => $this->study_menu_model->main_side_menu_student('course_enroll')
                );
                $data['grid_menu'] = array(
                    array('url' => site_url('study/course/'), 'title' => '<', 'extra' => ''),
                    array('url' => site_url('study/course/report_course_score/' . $c_id), 'title' => 'รายงานสรุปผลคะแนน', 'extra' => '')
                );
                $this->template->script_var(array(
                    'ajax_grid_url' => site_url('study/course/ajax_course_act/' . $c_id)
                ));
                $data['course_data'] = $this->course_model->get_course_data($c_id);

                $this->template->application_script('study/course/course_act_grid.js');
                $this->template->write_view('study/course/course_act_grid', $data);
                $this->template->load_flexgrid();
                $this->template->render();
            }
        } else {
            $data = array(
                'time' => 10,
                'url' => site_url('user/login'),
                'heading' => 'คุณต้องลงชื่อเข้าใช้ก่อน',
                'message' => '<p>คุณต้องลงชื่อเข้าใช้ก่อน</p>'
            );
            $this->load->view('refresh_page', $data);
        }
    }

    /**
     * ดึงข้อมูลรายการกิจกรรมในคอร์สของตน สำหรับนักเรียน
     * @param type $c_id
     */
    function ajax_course_act($c_id) {
        $a = $this->course_model->find_all_my_course_act($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query'), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE), $c_id);
        echo json_encode($a);
    }

    /**
     * สมัครหลักสูตร
     * @param type $c_id
     */
    function enroll($c_id) {
        if ($this->auth->is_login()) {
            if ($this->course_model->have_enroll($c_id)) {
                $data = array(
                    'time' => 0,
                    'url' => site_url('study/course'),
                    'heading' => 'ผลการสมัคร',
                    'message' => '<p>คุณได้สมัครหลักสูตรนี้ไปแล้ว</p>'
                );
                $this->load->view('refresh_page', $data);
                return;
            }
            //$this->course_model->init_course($c_id);
            $course_data = $this->course_model->get_course_data($c_id);
            switch ($course_data['enroll_type_id']) {
                case 1: //เข้าเรียนได้ทันที
                    if ($this->course_model->enroll($c_id)) {
                        $data = array(
                            'time' => 0,
                            'url' => site_url('study/course'),
                            'heading' => 'บันทึกข้อมูลเสร็จสิ้น',
                            'message' => '<p>บันทึกข้อมูลเสร็จสิ้น</p>'
                        );
                    } else {
                        $data = array(
                            'time' => 0,
                            'url' => site_url('study/course'),
                            'heading' => 'ไม่สามารถบันทึกข้อมูลได้',
                            'message' => '<p>ไม่สามารถบันทึกข้อมูลได้</p>'
                        );
                    }
                    $this->load->view('refresh_page', $data);
                    break;
                case 2://ต้องขออนุมัติก่อน
                    if ($this->course_model->enroll($c_id)) {
                        $data = array(
                            'time' => 0,
                            'url' => site_url('study/course'),
                            'heading' => 'บันทึกข้อมูลเสร็จสิ้น',
                            'message' => '<p>บันทึกข้อมูลเสร็จสิ้น</p>'
                        );
                    } else {
                        $data = array(
                            'time' => 0,
                            'url' => site_url('study/course'),
                            'heading' => 'ไม่สามารถบันทึกข้อมูลได้',
                            'message' => '<p>ไม่สามารถบันทึกข้อมูลได้</p>'
                        );
                    }
                    $this->load->view('refresh_page', $data);
                    break;
                case 3://ต้องกรอก password
                    $data = array(
                        'title' => 'กรอกรหัสผ่านเพื่อเข้าเรียนหลักสูตร',
                        'form_action' => site_url('study/course/do_enroll_password'),
                        'form_data' => $course_data,
                        'cancel_link' => site_url('study/course/course_open')
                    );

                    $this->template->write_view('study/course/enroll_password', $data);
                    $this->template->render();
                    break;
                default:
                    break;
            }
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

    function do_enroll_password() {
        $enroll_result = $this->course_model->enroll_password($this->input->post('data'));
        if ($enroll_result['success']) {
            $data = array(
                'time' => 0,
                'url' => site_url('study/course'),
                'heading' => 'ลงทะเบียนหลักสูตรเสร็จสิ้น',
                'message' => "<p>$enroll_result[message]</p>"
            );
        } else {
            $data = array(
                'time' => 2,
                'url' => site_url('study/course/course_open'),
                'heading' => 'ไม่สามารถบันทึกข้อมูลได้',
                'message' => "<p class=\"error-text\">$enroll_result[message]</p>"
            );
        }
        $this->load->view('refresh_page', $data);
    }

    /**
     * ออกจากหลักสูตร
     * @param type $c_id
     */
    function delete_enroll($c_id) {

        $data = array(
            'cancel_url' => site_url('study/course/'),
            'url' => site_url('study/course/do_delete_enroll/' . $c_id),
            'heading' => 'ลบข้อมูลการทั้งหมดในหลักสูตรนี้',
            'message' => '<p>ระบบจะลบข้อมูลคะแนนและเอกสารที่เกี่ยวข้องทั้งหมด</p>'
        );
        $this->load->view('confirm_page', $data);
    }

    /**
     * ออกจากหลักสูตร
     * @param type $c_id
     */
    function do_delete_enroll($c_id) {
        if ($this->course_model->delete_enroll($c_id)) {
            $data = array(
                'time' => 0,
                'url' => site_url('study/course'),
                'heading' => 'ลบข้อมูลเสร็จสิ้น',
                'message' => '<p>บันทึกข้อมูลเสร็จสิ้น</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            
        }
    }

    //=========================================================================
    // การเรียนการสอน
    //=========================================================================

    /**
     * เข้าดูรายละเอียยดกิจกรรม
     * @param type $act_id
     */
    function view_act($act_id) {
        $act_data = $this->course_model->get_act_data($act_id);
        switch ($act_data['cmat_id']) {
            case 1:
                //ประเภทของกิจกรรม เรียน
                $this->view_act_normal($act_data);

                break;
            case 2:
                // ประเภทของกิจกรรม สอบ
                $this->view_act_normal($act_data);

                break;

            default:
                break;
        }
    }

    private function view_act_normal($act_data) {
        $data = array(
            'title' => $act_data['title'],
            'form_data' => $act_data
        );
        switch ($act_data['st_id']) {
            case 0:
                $data['do_act_link'] = 'ไม่ต้องส่งงาน';
                break;
            case 1:
                $data['do_act_link'] = anchor('study/course/do_act/' . $act_data['ca_id'], 'อัพโหลดงาน', ' class="btn-a"');
                break;
            case 2:
                $data['do_act_link'] = 'ส่งที่โต๊ะ';
                break;
            case 3: case 4:
                $data['do_act_link'] = anchor('study/course/do_act/' . $act_data['ca_id'], 'ทำงาน', ' class="btn-a"');
                break;

            default:
                break;
        }


        $this->template->write_view('study/course/view_act_page', $data);
        $this->template->render();
    }

    /**
     * ทำกิจกรรม แต่ละกิจกรรมจะต่างกันไป
     * @param type $act_id
     */
    function do_act($ca_id) {
        $act_data = $this->course_model->get_act_data($ca_id);
        if ($act_data['can_send']) {
            switch ($act_data['st_id']) {
                case 1:
                    //ทำงานแบบอัพโหลดข้อมูล
                    $this->do_act_upload($act_data);
                    break;
                case 3:
                    //ทำงานแบบกรอกข้อมูล
                    $this->do_act_textarea($act_data);
                    break;
                case 4:
                    //ทำงานแบบทำออนไลน์
                    redirect('study/exam/do_exam/' . $ca_id);
                    break;
                default:
                    break;
            }
        } else {
            $data = array(
                'time' => 3,
                'url' => site_url('study/course/course_act/' . $act_data['c_id']),
                'heading' => 'ท่านไม่มีสิทธิส่งงานนี้',
                'message' => '<p>หมดเวลาส่งงานแล้วท่านไม่สามารถส่งงานนี้ได้</p>'
            );

            $this->load->view('refresh_page', $data);
        }
    }

    /**
     * ทำงานแบบ upload file
     * @param type $act_data
     */
    private function do_act_upload($act_data) {
        $this->load->helper('number');
        $cancel_link = site_url('study/course/course_act/' . $act_data['c_id']);
        $send_act_allowed_types = $this->config->item('send_act_allowed_types');
        $send_act_max_size = $this->config->item('send_act_max_size');
        $data = array(
            'title' => $act_data['title'],
            'form_action' => site_url('study/course/save_do_act_upload'),
            'cancel_link' => $cancel_link,
            'form_data' => $this->course_model->get_send_act_data($act_data['ca_id']),
            'act_data' => $act_data,
            'send_act_allowed_types' => str_replace('|', ', ', $send_act_allowed_types),
            'send_act_max_size' => $send_act_max_size
        );
        $send_act_allowed_types = '["' . str_replace('|', '","', $send_act_allowed_types) . '"]';
        $this->template->script_var(
                array(
                    'send_act_allowed_types' => array('value' => $send_act_allowed_types),
                    'send_act_max_size' => array('value' => $send_act_max_size),
                )
        );
        $this->template->application_script('study/course/do_act_upload.js');
        $this->template->write_view('study/course/do_act_upload', $data);
        $this->template->render();
    }

    /**
     * ทำงานแบบ upload file
     */
    function save_do_act_upload() {
        $post_data = $this->input->post('data');
        $act_data = $this->course_model->get_act_data($post_data['ca_id']);
        $personal_dir = $this->auth->get_personal_dir($act_data['uid_assign']);
        $file_name = $post_data['ca_id'] . '_' . $this->auth->uid();
        $post_data['data'] = $personal_dir . $file_name . '.' . pathinfo($_FILES['userfile']['name'], PATHINFO_EXTENSION);
        $this->course_model->save_send_course_act($post_data);
        $config['upload_path'] = $this->config->item('send_act_upload_dir') . $personal_dir;
        $config['file_name'] = $file_name;
        $config['overwrite'] = TRUE;
        $config['allowed_types'] = $this->config->item('send_act_allowed_types');
        $config['max_size'] = $this->config->item('send_act_max_size');
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload()) {
            $data = array(
                'time' => 5,
                'url' => site_url('study/course/course_act/' . $act_data['c_id']),
                'heading' => 'ไม่สามารถบันทึกข้อมูลได้',
                'message' => '<p>ไม่สามารถบันทึกข้อมูลได้</p><p>' . $this->upload->display_errors() . '</p>'
            );
        } else {
            $data = array(
                'time' => 3,
                'url' => site_url('study/course/course_act/' . $act_data['c_id']),
                'heading' => 'ส่งงานเสร็จสิ้น',
                'message' => '<p>ส่งงานเสร็จสิ้น</p>'
            );
        }
        $this->load->view('refresh_page', $data);
    }

    /**
     * ทำงานแบบกรอกข้อมูล
     * @param type $act_data
     */
    private function do_act_textarea($act_data) {
        $data = array(
            'title' => $act_data['title'],
            'form_action' => site_url('study/course/save_do_act_textarea'),
            'cancel_link' => site_url('study/course/course_act/' . $act_data['c_id']),
            'form_data' => $this->course_model->get_send_act_data($act_data['ca_id']),
            'act_data' => $act_data
        );
        $this->template->application_script('study/course/do_act_textarea.js');
        $this->template->write_view('study/course/do_act_textarea', $data);
        $this->template->render();
    }

    /**
     * ทำงานแบบกรอกข้อมูล
     */
    function save_do_act_textarea() {
        $post_data = $this->input->post('data');
        $act_data = $this->course_model->get_act_data($post_data['ca_id']);
        if ($this->course_model->save_send_course_act($post_data)) {
            $data = array(
                'time' => 0,
                'url' => site_url('study/course/course_act/' . $act_data['c_id']),
                'heading' => 'ส่งงานเสร็จสิ้น',
                'message' => '<p>ส่งงานเสร็จสิ้น</p>'
            );
        } else {
            $data = array(
                'time' => 0,
                'url' => site_url('study/course/course_act/' . $act_data['c_id']),
                'heading' => 'ไม่สามารถบันทึกข้อมูลได้',
                'message' => '<p>ไม่สามารถบันทึกข้อมูลได้</p>'
            );
        }
        $this->load->view('refresh_page', $data);
    }

    function summary_answer_sent($cas_id) {

        $data['course_act_sent_data'] = $this->course_model->get_course_act_send_data_by_id($cas_id);
        $data['course_act_data'] = $this->course_model->get_act_data($data['course_act_sent_data']['ca_id']);
        $data['title'] = 'ผลคะแนน : ' . $data['course_act_data']['title'];
        $data['cancel_link'] = site_url('study/course/course_act/' . $data['course_act_data']['c_id']);
        switch ($data['course_act_data']['st_id']) {
            case 1:
                $this->template->write_view('study/course/summary_act_upload', $data);
                break;
            case 2:
                $this->template->write_view('study/course/summary_act_table', $data);
                break;
            case 3:
                $this->template->write_view('study/course/summary_act_textarea', $data);
                break;

            default:
                break;
        }
        $this->template->render();
    }

    function view_course_details($c_id) {


        $data['course_data'] = $this->course_model->get_course_data($c_id);

        $data['course_act_data'] = $this->course_model->find_all_my_course_act(1, '', '', 1000, 'start_time', 'asc', $c_id);
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
            $data['grid_menu'][] = array('url' => site_url('study/course/enroll/' . $c_id), 'title' => 'สมัครหลักสูตรนี้', 'extra' => '');
            //$row['action'] .= '<a href="' . site_url('study/course/enroll/' . $row['c_id']) . '">สมัคร</a>';
        }
        $this->template->write_view('study/course/view_course_details', $data);
        $this->template->render();
    }

    // Report ========================================================================
    function report_course_score($c_id) {
        $this->load->model('core/ddoption_model');
        $data = array(
            'title' => 'รายงานสรุปผลคะแนน',
            'main_side_menu' => $this->study_menu_model->main_side_menu_student('course_enroll')
        );
        $data['course_act_data'] = $this->course_model->get_all_my_course_act_data($c_id);
        $data['act_type_options'] = $this->ddoption_model->get_act_type_options();
        $this->template->write_view('study/course/report_course_score', $data);
        $this->template->render();
    }

}