<?php

/**
 * @property course_model $course_model
 * @property practice_model $practice_model
 */
class practice extends CI_Controller {

    var $time;
    var $debug = FALSE;

    public function __construct() {
        parent::__construct();
        $this->load->model('study/practice_model');
        $this->load->helper('form');
        $this->time = time();
    }

    /**
     *  หน้าทำข้อสอบ
     * @param type $ca_id
     */
    function do_practice($ca_id) {
        $this->auth->access_limit($this->auth->permis_course);
        $this->load->model('study/course_model');
        $this->load->model('resource/sheet_model');

        $make_data = $this->practice_model->make_answer_sheet($ca_id);
        if ($make_data) {


            $cource_act_data = $make_data['cource_act_data'];
            $answer_sheet_data = $make_data['answer_sheet_data'];
            $remaining_time = $cource_act_data['end_time'] - $cource_act_data['start_time'];

            if (date('Ymd', $cource_act_data['start_time']) == date('Ymd', $cource_act_data['end_time'])) {
                $start_end_text = thdate('lที่ j F Y', $this->time) . ' เริ่ม ' . date('H:i', $this->time) . ' ถึง ' . date('H:i', $this->time + $remaining_time);
            } else {
                $start_end_text = thdate('j M Y H:i', $this->time) . ' ถึง ' . thdate('j M Y H:i', $this->time + $remaining_time);
            }


            $data = array(
                'title' => 'ซ้อมสอบ : ' . $cource_act_data['title'],
                'title_ranking' => 'ลำดับคะแนนซ้อมสอบ',
                'start_end_text' => $start_end_text,
                'remaining_time' => $remaining_time,
                'form_action' => site_url('study/practice/send_answer_sheet'),
                'ca_id' => $ca_id,
                'caasp_id' => $make_data['caasp_id']
            );

            $this->template->script_var(
                    array(
                        'ca_id' => $ca_id,
                        'caasp_id' => $make_data['caasp_id'],
                        'ajax_get_question_url' => site_url("study/practice/ajax_get_question"),
                        'ajax_send_answer_url' => site_url("study/practice/ajax_send_answer"),
                        'question_count' => array('value' => $answer_sheet_data['question_count'])
                    )
            );
            $this->template->load_showloading();
            $data['ranking_data'] = $this->practice_model->get_all_summary($ca_id);
            $this->template->write_view('study/practice/do_test_form', $data);
            $this->template->temmplate_name('normal');
            $this->template->render();
        } else {
            $course_act_data = $this->course_model->get_course_act_data($ca_id);
            $data = array(
                'time' => 5,
                'url' => site_url('study/course/course_act/' . $course_act_data['c_id']),
                'heading' => 'ข้อสอบบางข้อถูกลบ',
                'message' => '<p>ระบบนำไปสู่หน้าจอหลักสูตร</p>'
            );
            $this->load->view('refresh_page', $data);
        }
    }

    /**
     * ดึงข้อสอบ ทีละข้อ
     */
    function ajax_get_question() {
        $value = array();
        $this->load->model('study/xelatex_exam_model');
        $this->load->model('study/practice_model');
        if ($this->input->post('sheet_q_index')) {
            $sheet_q_index = $this->input->post('sheet_q_index');
        } else {
            $sheet_q_index = 0;
        }
        $caasp_id = $this->input->post('caasp_id');
        $q_one_data = $this->practice_model->get_question_data($sheet_q_index, $caasp_id);

        $q_one = $this->practice_model->get_question_one($q_one_data['resource_id'], $q_one_data['q_index']);
        $q_one = array_merge($q_one, $q_one_data);
        $q_one['sheet_q_index_previous'] = '';
        $q_one['sheet_q_index_next'] = '';
        $q_one['sheet_q_index'] = $sheet_q_index;
        $q_one['question_count'] = $this->practice_model->get_question_count();
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
        $resource_id_video_guide = $this->practice_model->get_resource_id_video_guide($q_one_data['resource_id']);

        if ($resource_id_video_guide) {
            $question_box_data['video_guide'] = site_url('v/' . $resource_id_video_guide);
        } else {
            //$question_box_data['video_guide'] = site_url('v/595');
            $question_box_data['video_guide'] = FALSE;
        }

        $value['render']['question_box'] = $this->load->view('study/practice/question_box', $question_box_data, TRUE);
        $answer_sheet_data['questions'] = $this->practice_model->get_practice_answer_sheet();
        $answer_sheet_data['section_num'] = $this->practice_model->get_section_num();
        $answer_sheet_data['sheet_q_index'] = $sheet_q_index;

        $value['render']['answer_sheet'] = $this->load->view('study/practice/answer_sheet', $answer_sheet_data, TRUE);
        $value['data'] = $q_one;
        echo json_encode($value);
    }

    /**
     * ส่งคำตอบเป็นข้อๆ
     */
    function ajax_send_answer() {
        $caasp_id = $this->input->post('caasp_id');
        $this->load->model('study/practice_model');
        $result = $this->practice_model->send_answer($caasp_id, $this->input->post('send_answer'));
        $value = $result['answer_sheet_data'];
//        $value['send_count'] = $this->practice_model->get_send_count();
//        $value['sure_count'] = $this->practice_model->get_sure_count();
//        $value['question_count'] = $this->practice_model->get_question_count();

        echo json_encode($value);
    }

    /**
     * ส่งกระดาษคำตอบ
     */
    function send_answer_sheet() {
        $this->load->model('study/practice_model');
        $caasp_id = $this->practice_model->commit_practice_answer_sheet($this->input->post('caasp_id'));
        $data = array(
            'time' => 0,
            'url' => site_url('study/practice/summary_answer_sheet/' . $caasp_id),
            'heading' => 'ส่งงานเสร็จสิ้น',
            'message' => '<p>ส่งงานเสร็จสิ้น</p>'
        );
        $this->load->view('refresh_page', $data);
    }

    function summary_answer_sheet($caasp_id) {
        $this->load->helper('html');
        $this->load->model('study/xelatex_exam_model');
        $this->load->model('study/practice_model');
        $data = $this->practice_model->get_db_summary_answer_sheet($caasp_id);
        $data['title'] = 'สรุปผลคะแนนซ้อมสอบ';
//        $render_result = array();
        foreach ($data['answer_sheet_data']['answer_sheet'] as $k => $q_one_data) {
            $q_one = $this->practice_model->get_question_one($q_one_data['resource_id'], $q_one_data['q_index']);
            $this->xelatex_exam_model->init_content($q_one, TRUE);
            $data['answer_sheet_data']['answer_sheet'][$k]['question_render_result'] = $this->xelatex_exam_model->render();
            
            $data['answer_sheet_data']['answer_sheet'][$k]['resource_id_video_guide'] = $this->practice_model->get_resource_id_video_guide($q_one_data['resource_id']);
            //$render_result[] = $this->xelatex_exam_model->render();
        }
        //$data['render_result'] = $render_result;


        $this->template->write_view('study/practice/summary_answer_sheet', $data);
        $this->template->render();
    }

}