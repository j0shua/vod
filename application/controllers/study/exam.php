<?php

/**
 * @property course_model $course_model
 * @property exam_model $exam_model
 */
class exam extends CI_Controller {

    var $time;
    var $debug = FALSE;

    public function __construct() {
        parent::__construct();
        $this->load->model('study/exam_model');
        $this->load->helper('form');
        $this->time = time();
    }

    /**
     *  หน้าทำข้อสอบ
     * @param type $ca_id
     */
    function do_exam($ca_id) {
        $this->auth->access_limit($this->auth->permis_course);
        $cas_id = $this->exam_model->get_cas_id($ca_id);
        if ($cas_id) {
            $data = array(
                'time' => 0,
                'url' => site_url('study/exam/summary_course_act_send/' . $cas_id),
                'heading' => 'ส่งคำตอบแล้ว',
                'message' => '<p>ระบบนำไปสู่สรุปข้อมูลคะแนน</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $this->load->model('study/course_model');
            $this->load->model('resource/sheet_model');
            $make_data = $this->exam_model->make_answer_sheet($ca_id);
            $cource_act_data = $make_data['cource_act_data'];
            if ($cource_act_data['end_time'] > $this->time && $cource_act_data['start_time'] < $this->time) {
                $answer_sheet_data = $make_data['answer_sheet_data'];
                if (date('Ymd', $cource_act_data['start_time']) == date('Ymd', $cource_act_data['end_time'])) {
                    $start_end_text = thdate('lที่ j F Y', $cource_act_data['start_time']) . ' เริ่ม ' . date('H:i', $cource_act_data['start_time']) . ' ถึง ' . date('H:i', $cource_act_data['end_time']);
                } else {
                    $start_end_text = thdate('j M Y H:i', $cource_act_data['start_time']) . ' ถึง ' . thdate('j M Y H:i', $cource_act_data['end_time']);
                }

                $data = array(
                    'title' => $cource_act_data['title'],
                    'start_end_text' => $start_end_text,
                    'remaining_time' => ($cource_act_data['end_time'] - time()),
                    'form_action' => site_url('study/exam/send_answer_sheet'),
                    'ca_id' => $ca_id,
                    'have_'
                );

                $this->template->script_var(
                        array(
                            'ca_id' => $ca_id,
                            'ajax_get_question_url' => site_url("study/exam/ajax_get_question"),
                            'ajax_send_answer_url' => site_url("study/exam/ajax_send_answer"),
                            'question_count' => array('value' => $answer_sheet_data['question_count'])
                        )
                );
                $this->template->load_showloading();
                $this->template->write_view('study/exam/do_test_form', $data);
                $this->template->temmplate_name('normal');
                $this->template->render();
            } else {
                if ($cource_act_data['end_time'] > $this->time) {
                    $data = array(
                        'time' => 3,
                        'url' => site_url('study/course/course_act/' . $cource_act_data['c_id']),
                        'heading' => 'ไม่สามารถเข้าทำข้อสอบ',
                        'message' => '<p>ยังไม่ถึงเวลาในการทำข้อสอบ</p>'
                    );
                } else {
                    $data = array(
                        'time' => 3,
                        'url' => site_url('study/course/course_act/' . $cource_act_data['c_id']),
                        'heading' => 'ไม่สามารถเข้าทำข้อสอบ',
                        'message' => '<p>หมดเวลาในการทำข้อสอบ</p>'
                    );
                }

                $this->load->view('refresh_page', $data);
            }
        }
    }

    /**
     * ดึงข้อสอบ ทีละข้อ
     */
    function ajax_get_question() {
        $value = array();
        $this->load->model('study/xelatex_exam_model');
        $this->load->model('study/exam_model');
        $this->load->model('study/course_model');
        if ($this->input->post('sheet_q_index')) {
            $sheet_q_index = $this->input->post('sheet_q_index');
        } else {
            $sheet_q_index = 0;
        }
        $ca_id = $this->input->post('ca_id');
        $q_one_data = $this->exam_model->get_question_data($sheet_q_index, $ca_id);

        $q_one = $this->exam_model->get_question_one($q_one_data['resource_id'], $q_one_data['q_index']);
        $q_one = array_merge($q_one, $q_one_data);
        $q_one['sheet_q_index_previous'] = '';
        $q_one['sheet_q_index_next'] = '';
        $q_one['sheet_q_index'] = $sheet_q_index;
        $q_one['question_count'] = $this->exam_model->get_question_count();
        $q_one['sheet_q_index_previous'] = $sheet_q_index - 1;

        if ($q_one['sheet_q_index'] != ($q_one['question_count'] - 1)) {
            $q_one['sheet_q_index_next'] = $sheet_q_index + 1;
        }
        $this->xelatex_exam_model->init_content($q_one);

        $render_result = $this->xelatex_exam_model->render();

        if ($this->debug) {
            $value['render_result'] = $render_result;
        }
        $question_box_data = array_merge($q_one, $render_result);

        $act_data = $this->course_model->get_act_data($ca_id);
        $question_box_data['video_guide'] = FALSE;
        $question_box_data['question_guide'] = FALSE;
        if ($act_data['at_id'] == 1) { //ประเภทเป็นการบ้าน
            $resource_id_question_guide = $this->exam_model->get_resource_id_question_guide($q_one_data['resource_id']);
            if ($resource_id_question_guide) {
                if($resource_id_question_guide['resource_id_video']){
                    $question_box_data['video_guide'] = site_url('v/' . $resource_id_question_guide['resource_id_video']);
                }
                if($resource_id_question_guide['resource_id_dycontent']){
                    $question_box_data['question_guide'] = site_url('play/play_resource/guide/' . $resource_id_question_guide['resource_id_dycontent']);
                }
                
            }
        }


        $value['render']['question_box'] = $this->load->view('study/exam/question_box', $question_box_data, TRUE);
        $answer_sheet_data['questions'] = $this->exam_model->get_exam_answer_sheet();
        $answer_sheet_data['section_num'] = $this->exam_model->get_section_num();
        $answer_sheet_data['sheet_q_index'] = $sheet_q_index;

        $value['render']['answer_sheet'] = $this->load->view('study/exam/answer_sheet', $answer_sheet_data, TRUE);
        $value['data'] = $q_one;
        echo json_encode($value);
    }

    /**
     * ส่งคำตอบเป็นข้อๆ
     */
    function ajax_send_answer() {
        $ca_id = $this->input->post('ca_id');
        $this->load->model('study/exam_model');
        $result = $this->exam_model->send_answer($ca_id, $this->input->post('send_answer'));
        $value = $result['answer_sheet_data'];
//        $value['send_count'] = $this->exam_model->get_send_count();
//        $value['sure_count'] = $this->exam_model->get_sure_count();
//        $value['question_count'] = $this->exam_model->get_question_count();

        echo json_encode($value);
    }

    /**
     * ส่งกระดาษคำตอบ
     */
    function send_answer_sheet() {
        $this->load->model('study/exam_model');
        $cas_id = $this->exam_model->commit_exam_answer_sheet($this->input->post('ca_id'));
        $data = array(
            'time' => 0,
            'url' => site_url('study/exam/summary_answer_sheet/' . $cas_id),
            'heading' => 'ส่งงานเสร็จสิ้น',
            'message' => '<p>ส่งงานเสร็จสิ้น</p>'
        );
        $this->load->view('refresh_page', $data);
    }

    function summary_answer_sheet($cas_id) {
        $this->load->helper('html');
        $this->load->model('study/xelatex_exam_model');
        $this->load->model('study/exam_model');
        $data = $this->exam_model->get_summary_course_act_send($cas_id);
        $course_act_data = $this->course_model->get_course_act_data($data['ca_id']);
        $data['title'] = 'สรุปผลคะแนน : ' . $course_act_data['title'];
        $data['show_solve'] = ($course_act_data['end_time'] < time()) ? TRUE : FALSE;
        $data['grid_menu'] = array(
            array('url' => site_url('study/course/course_act/' . $course_act_data['c_id']), 'title' => 'กลับไปหลักสูตรการเรียน', 'extra' => '')
        );
        foreach ($data['data']['answer_sheet'] as $k => $q_one_data) {
            $q_one = $this->exam_model->get_question_one($q_one_data['resource_id'], $q_one_data['q_index']);
            $this->xelatex_exam_model->init_content($q_one, TRUE);
            $data['data']['answer_sheet'][$k]['question_render_result'] = $this->xelatex_exam_model->render();
        }

        $this->template->write_view('study/exam/summary_answer_sheet', $data);
        $this->template->render();
    }

    // do exam for teacher ==============================================
    /**
     *  หน้าทำข้อสอบ
     * @param type $ca_id
     */
    function do_teacher_exam($ca_id) {
        $this->auth->access_limit($this->auth->permis_course_manager);
        $this->load->model('study/course_model');
        $this->load->model('resource/sheet_model');
        $make_data = $this->exam_model->make_answer_sheet($ca_id, $this->auth->uid(), TRUE);
        $cource_act_data = $make_data['cource_act_data'];

        $answer_sheet_data = $make_data['answer_sheet_data'];
        $remaining_time = $cource_act_data['end_time'] - $cource_act_data['start_time'];
        if (date('Ymd', $cource_act_data['start_time']) == date('Ymd', $cource_act_data['end_time'])) {
            $start_end_text = thdate('lที่ j F Y', $this->time) . ' เริ่ม ' . date('H:i', $this->time) . ' ถึง ' . date('H:i', $this->time + $remaining_time);
        } else {
            $start_end_text = thdate('j M Y H:i', $this->time) . ' ถึง ' . thdate('j M Y H:i', $this->time + $remaining_time);
        }


        $data = array(
            'title' => $cource_act_data['title'],
            'start_end_text' => $start_end_text,
            'remaining_time' => $remaining_time,
            'form_action' => site_url('study/exam/send_teacher_answer_sheet'),
            'ca_id' => $ca_id,
            'have_'
        );

        $this->template->script_var(
                array(
                    'ca_id' => $ca_id,
                    'ajax_get_question_url' => site_url("study/exam/ajax_get_question"),
                    'ajax_send_answer_url' => site_url("study/exam/ajax_send_answer"),
                    'question_count' => array('value' => $answer_sheet_data['question_count'])
                )
        );
        $this->template->load_showloading();
        $this->template->write_view('study/exam/do_test_form', $data);
        $this->template->temmplate_name('normal');
        $this->template->render();
    }

    /**
     * ส่งกระดาษคำตอบ
     */
    function send_teacher_answer_sheet() {
        $this->load->model('study/exam_model');
        $this->exam_model->commit_exam_answer_sheet($this->input->post('ca_id'), FALSE);
        $data = array(
            'time' => 0,
            'url' => site_url('study/exam/summary_teacher_answer_sheet/' . $this->input->post('ca_id')),
            'heading' => 'ส่งงานเสร็จสิ้น',
            'message' => '<p>ส่งงานเสร็จสิ้น</p>'
        );
        $this->load->view('refresh_page', $data);
    }

    function summary_teacher_answer_sheet($ca_id) {
        $this->load->helper('html');
        $this->load->model('study/xelatex_exam_model');
        $this->load->model('study/exam_model');
        $data = $this->exam_model->get_teacher_summary_course_act_send($ca_id);
        $course_act_data = $this->course_model->get_course_act_data($data['ca_id']);
        $data['title'] = 'สรุปผลคะแนน : ' . $course_act_data['title'];
        $data['show_solve'] = ($course_act_data['end_time'] < time()) ? TRUE : FALSE;
        $data['data'] = $data['answer_sheet_data'];
        $data['grid_menu'] = array(
            array('url' => site_url('study/course_manager/course_act/' . $course_act_data['c_id']), 'title' => 'กลับไปหลักสูตรการเรียน', 'extra' => '')
        );
        unset($data['answer_sheet_data']);
        foreach ($data['data']['answer_sheet'] as $k => $q_one_data) {
            $q_one = $this->exam_model->get_question_one($q_one_data['resource_id'], $q_one_data['q_index']);
            $this->xelatex_exam_model->init_content($q_one, TRUE);
            $data['data']['answer_sheet'][$k]['question_render_result'] = $this->xelatex_exam_model->render();
        }

        $this->template->write_view('study/exam/summary_answer_sheet', $data);
        $this->template->render();
    }

}