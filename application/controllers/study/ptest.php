<?php

/**
 * @property course_model $course_model
 * @property ptest_model $ptest_model
 */
class ptest extends CI_Controller {

    var $time;
    var $debug = TRUE;

    public function __construct() {
        parent::__construct();
        $this->load->model('study/ptest_model');
        $this->load->helper('form');
        $this->time = time();
    }

    /**
     *  หน้าทำข้อสอบ
     * @param type $ca_id
     */
    function do_pretest($ca_id) {


        $this->auth->access_limit($this->auth->permis_course);

        $cas_id = $this->ptest_model->get_cas_id($ca_id);
        $this->ptest_model->processing_summary($ca_id);
        if ($cas_id) {
            $data = array(
                'time' => 0,
                'url' => site_url('study/ptest/summary_course_act_send/' . $cas_id),
                'heading' => 'ส่งคำตอบแล้ว',
                'message' => '<p>ระบบนำไปสู่สรุปข้อมูลคะแนน</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $this->load->model('study/course_model');
            $this->load->model('resource/sheet_model');
            $make_data = $this->ptest_model->make_answer_sheet($ca_id, $this->auth->uid(), 'pretest');
            if ($make_data) {

                $cource_act_data = $make_data['cource_act_data'];
                //
//            if ($cource_act_data['end_time'] > $this->time && $cource_act_data['start_time'] < $this->time) {
                $answer_sheet_data = $make_data['answer_sheet_data'];
                $remaining_time = $cource_act_data['end_time'] - $cource_act_data['start_time'];
                if ($remaining_time > 86400) {
                    $remaining_time = 3600;
                }


                if (date('Ymd', $cource_act_data['start_time']) == date('Ymd', $cource_act_data['end_time'])) {
                    $start_end_text = thdate('lที่ j F Y', $this->time) . ' เริ่ม ' . date('H:i', $this->time) . ' ถึง ' . date('H:i', $this->time + $remaining_time);
                } else {
                    $start_end_text = thdate('j M Y H:i', $this->time) . ' ถึง ' . thdate('j M Y H:i', $this->time + $remaining_time);
                }


                $data = array(
                    'title' => 'พรีเทส : ' . $cource_act_data['title'],
                    'title_ranking' => 'ลำดับคะแนนพรีเทส',
                    'start_end_text' => $start_end_text,
                    'remaining_time' => $remaining_time,
                    'form_action' => site_url('study/ptest/send_answer_sheet_pretest'),
                    'ca_id' => $ca_id,
                );

                $this->template->script_var(
                        array(
                            'ca_id' => $ca_id,
                            'ajax_get_question_url' => site_url("study/ptest/ajax_get_pretest_question"),
                            'ajax_send_answer_url' => site_url("study/ptest/ajax_send_pretest_answer"),
                            'question_count' => array('value' => $answer_sheet_data['question_count'])
                        )
                );
                $this->template->load_showloading();
                $data['ranking_data'] = $this->ptest_model->get_ranking($data['ca_id'], 'pre');
                $this->template->write_view('study/ptest/do_test_form', $data);
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
//            }
        }
    }

    /**
     * ดึงข้อสอบ ทีละข้อ
     */
    function ajax_get_pretest_question() {
        $value = array();
        $this->load->model('study/xelatex_exam_model');
        $this->load->model('study/ptest_model');
        if ($this->input->post('sheet_q_index')) {
            $sheet_q_index = $this->input->post('sheet_q_index');
        } else {
            $sheet_q_index = 0;
        }
        $ca_id = $this->input->post('ca_id');
        $q_one_data = $this->ptest_model->get_question_data($sheet_q_index, $ca_id, 'pretest');


        $q_one = $this->ptest_model->get_question_one($q_one_data['resource_id'], $q_one_data['q_index']);
        $q_one = array_merge($q_one, $q_one_data);
        $q_one['sheet_q_index_previous'] = '';
        $q_one['sheet_q_index_next'] = '';
        $q_one['sheet_q_index'] = $sheet_q_index;
        $q_one['question_count'] = $this->ptest_model->get_question_count();
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
        $resource_id_video_guide = $this->ptest_model->get_resource_id_video_guide($q_one_data['resource_id']);
        if ($resource_id_video_guide) {
            $question_box_data['video_guide'] = site_url('v/' . $resource_id_video_guide);
        } else {
            //$question_box_data['video_guide'] = site_url('v/595');
            $question_box_data['video_guide'] = FALSE;
        }

        $value['render']['question_box'] = $this->load->view('study/ptest/question_box', $question_box_data, TRUE);
        $answer_sheet_data['questions'] = $this->ptest_model->get_ptest_answer_sheet();
        $answer_sheet_data['section_num'] = $this->ptest_model->get_section_num();
        $answer_sheet_data['sheet_q_index'] = $sheet_q_index;

        $value['render']['answer_sheet'] = $this->load->view('study/ptest/answer_sheet', $answer_sheet_data, TRUE);
        $value['data'] = $q_one;
        echo json_encode($value);
    }

    /**
     * ส่งคำตอบเป็นข้อๆ
     */
    function ajax_send_pretest_answer() {
        $ca_id = $this->input->post('ca_id');
        $this->load->model('study/ptest_model');
        $result = $this->ptest_model->send_answer($ca_id, $this->input->post('send_answer'), 'pretest');
        $value = $result['answer_sheet_data'];
//        $value['send_count'] = $this->ptest_model->get_send_count();
//        $value['sure_count'] = $this->ptest_model->get_sure_count();
//        $value['question_count'] = $this->ptest_model->get_question_count();

        echo json_encode($value);
    }

    /**
     * ส่งกระดาษคำตอบ
     */
    function send_answer_sheet_pretest() {
        $this->load->model('study/ptest_model');
        $caaspt_id = $this->ptest_model->commit_ptest_answer_sheet($this->input->post('ca_id'), 'pretest');
        if ($caaspt_id) {
            $data = array(
                'time' => 0,
                'url' => site_url('study/ptest/summary_ptest/pretest/' . $caaspt_id),
                'heading' => 'ส่งงานเสร็จสิ้น',
                'message' => '<p>ส่งงานเสร็จสิ้น</p>'
            );
            $this->load->view('refresh_page', $data);
        }
    }

    function summary_ptest($test_type, $caaspt_id) {
        if ($test_type == 'posttest') {
            $prefix_field = 'post_';
            $title = 'สรุปผลคะแนนโพสเทส';

            $show_solve = TRUE;
        } else {
            $prefix_field = 'pre_';

            $title = 'สรุปผลคะแนนพรีเทส';
            $show_solve = FALSE;
        }
        $this->load->helper('html');
        $this->load->model('study/xelatex_exam_model');
        $this->load->model('study/ptest_model');
        $data = $this->ptest_model->get_summary_course_act_send($caaspt_id);
        $this->ptest_model->processing_summary($data['ca_id']);
        $posttest_has_done = $this->course_model->posttest_has_done($data['ca_id']);
        if ($posttest_has_done) {
            $show_solve = TRUE;
        }
        $data['title'] = $title;
        $data['data'] = $data[$prefix_field . 'answer_sheet_data'];
        unset($data[$prefix_field . 'answer_sheet_data']);
        foreach ($data['data']['answer_sheet'] as $k => $q_one_data) {
            $q_one = $this->ptest_model->get_question_one($q_one_data['resource_id'], $q_one_data['q_index']);
            $this->xelatex_exam_model->init_content($q_one, TRUE);
            $data['data']['answer_sheet'][$k]['question_render_result'] = $this->xelatex_exam_model->render();
        }
        $data['show_solve'] = $show_solve;
        $data['ranking_pre_data'] = $this->ptest_model->get_ranking($data['ca_id'], 'pre');
        $data['ranking_post_data'] = $this->ptest_model->get_ranking($data['ca_id'], 'post');
        $this->template->write_view('study/ptest/summary_answer_sheet', $data);
        $this->template->render();
    }

    // POSTTEST ====================================================================================
    /**
     *  หน้าทำข้อสอบ
     * @param type $ca_id
     */
    function do_posttest($ca_id) {


        $this->auth->access_limit($this->auth->permis_course);

        $cas_id = $this->ptest_model->get_cas_id($ca_id);
        $this->ptest_model->processing_summary($ca_id);
        if ($cas_id) {
            $data = array(
                'time' => 0,
                'url' => site_url('study/ptest/summary_course_act_send/' . $cas_id),
                'heading' => 'ส่งคำตอบแล้ว',
                'message' => '<p>ระบบนำไปสู่สรุปข้อมูลคะแนน</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $this->load->model('study/course_model');
            $this->load->model('resource/sheet_model');
            $make_data = $this->ptest_model->make_answer_sheet($ca_id, $this->auth->uid(), 'posttest');
            $cource_act_data = $make_data['cource_act_data'];
            if ($cource_act_data['end_time'] > $this->time && $cource_act_data['start_time'] < $this->time) {
                $answer_sheet_data = $make_data['answer_sheet_data'];
//                if (date('Ymd', $cource_act_data['start_time']) == date('Ymd', $cource_act_data['end_time'])) {
//                    $start_end_text = thdate('lที่ j F Y', $cource_act_data['start_time']) . ' เริ่ม ' . date('H:i', $cource_act_data['start_time']) . ' ถึง ' . date('H:i', $cource_act_data['end_time']);
//                    //$start_end_text = thdate('j/M/Y H:i', $cource_act_data['start_time']) . ' ถึง ' . thdate('j/M/Y H:i', $cource_act_data['end_time']);
//                } else {
//                    $start_end_text = thdate('j M Y H:i', $cource_act_data['start_time']) . ' ถึง ' . thdate('j M Y H:i', $cource_act_data['end_time']);
//                }

                $remaining_time = $cource_act_data['end_time'] - $cource_act_data['start_time'];
                if ($remaining_time > 86400) {
                    $remaining_time = 3600;
                }

                if (date('Ymd', $cource_act_data['start_time']) == date('Ymd', $cource_act_data['end_time'])) {
                    $start_end_text = thdate('lที่ j F Y', $this->time) . ' เริ่ม ' . date('H:i', $this->time) . ' ถึง ' . date('H:i', $this->time + $remaining_time);
                } else {
                    $start_end_text = thdate('j M Y H:i', $this->time) . ' ถึง ' . thdate('j M Y H:i', $this->time + $remaining_time);
                }

                $data = array(
                    'title' => 'โพสเทส : ' . $cource_act_data['title'],
                    'title_ranking' => 'ลำดับคะแนนโพสเทส',
                    'start_end_text' => $start_end_text,
                    'remaining_time' => $remaining_time,
                    'form_action' => site_url('study/ptest/send_answer_sheet_posttest'),
                    'ca_id' => $ca_id,
                );

                $this->template->script_var(
                        array(
                            'ca_id' => $ca_id,
                            'ajax_get_question_url' => site_url("study/ptest/ajax_get_posttest_question"),
                            'ajax_send_answer_url' => site_url("study/ptest/ajax_send_posttest_answer"),
                            'question_count' => array('value' => $answer_sheet_data['question_count'])
                        )
                );
                $this->template->load_showloading();
                $data['ranking_data'] = $this->ptest_model->get_ranking($data['ca_id'], 'post');
                $this->template->write_view('study/ptest/do_test_form', $data);
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
    function ajax_get_posttest_question() {
        $value = array();
        $this->load->model('study/xelatex_exam_model');
        $this->load->model('study/ptest_model');
        if ($this->input->post('sheet_q_index')) {
            $sheet_q_index = $this->input->post('sheet_q_index');
        } else {
            $sheet_q_index = 0;
        }
        $ca_id = $this->input->post('ca_id');
        $q_one_data = $this->ptest_model->get_question_data($sheet_q_index, $ca_id, 'posttest');


        $q_one = $this->ptest_model->get_question_one($q_one_data['resource_id'], $q_one_data['q_index']);
        $q_one = array_merge($q_one, $q_one_data);
        $q_one['sheet_q_index_previous'] = '';
        $q_one['sheet_q_index_next'] = '';
        $q_one['sheet_q_index'] = $sheet_q_index;
        $q_one['question_count'] = $this->ptest_model->get_question_count();
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
        $resource_id_video_guide = $this->ptest_model->get_resource_id_video_guide($q_one_data['resource_id']);
        if ($resource_id_video_guide) {
            $question_box_data['video_guide'] = site_url('v/' . $resource_id_video_guide);
        } else {
            //$question_box_data['video_guide'] = site_url('v/595');
            $question_box_data['video_guide'] = FALSE;
        }

        $value['render']['question_box'] = $this->load->view('study/ptest/question_box', $question_box_data, TRUE);
        $answer_sheet_data['questions'] = $this->ptest_model->get_ptest_answer_sheet();
        $answer_sheet_data['section_num'] = $this->ptest_model->get_section_num();
        $answer_sheet_data['sheet_q_index'] = $sheet_q_index;

        $value['render']['answer_sheet'] = $this->load->view('study/ptest/answer_sheet', $answer_sheet_data, TRUE);
        $value['data'] = $q_one;
        echo json_encode($value);
    }

    /**
     * ส่งคำตอบเป็นข้อๆ
     */
    function ajax_send_posttest_answer() {
        $ca_id = $this->input->post('ca_id');
        $this->load->model('study/ptest_model');
        $result = $this->ptest_model->send_answer($ca_id, $this->input->post('send_answer'), 'posttest');
        $value = $result['answer_sheet_data'];
//        $value['send_count'] = $this->ptest_model->get_send_count();
//        $value['sure_count'] = $this->ptest_model->get_sure_count();
//        $value['question_count'] = $this->ptest_model->get_question_count();

        echo json_encode($value);
    }

    /**
     * ส่งกระดาษคำตอบ
     */
    function send_answer_sheet_posttest() {
        $this->load->model('study/ptest_model');
        $caaspt_id = $this->ptest_model->commit_ptest_answer_sheet($this->input->post('ca_id'), 'posttest');
        if ($caaspt_id) {
            $data = array(
                'time' => 0,
                'url' => site_url('study/ptest/summary_ptest/posttest/' . $caaspt_id),
                'heading' => 'ส่งงานเสร็จสิ้น',
                'message' => '<p>ส่งงานเสร็จสิ้น</p>'
            );
            $this->load->view('refresh_page', $data);
        }
    }

}
