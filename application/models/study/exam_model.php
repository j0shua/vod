<?php

/**
 * exam_model
 * @property resource_codec $resource_codec
 */
class exam_model extends CI_Model {

    var $CI;
    var $time;
    var $sheet_data = array();
    var $course_act_asheet_data = FALSE;

    public function __construct() {
        parent::__construct();
        $this->CI = & get_instance();
        $this->CI->load->model('resource/dycontent_model');
        $this->CI->load->model('resource/sheet_model');
        $this->CI->load->model('study/course_model');

        $this->load->helper('str');
        $this->time = time();
    }

    function make_answer_sheet($ca_id, $uid = '', $remove_old_asheet = FALSE) {
        if ($uid == '') {
            $uid = $this->auth->uid();
        }

        $act_data = $this->CI->course_model->get_act_data($ca_id);
        $sheet_data = $this->CI->sheet_model->get_resource_data($act_data['data']);

        //check have answer sheet
        $this->db->where('ca_id', $ca_id);
        $this->db->where('uid', $uid);
        //$this->db->where('send_time', 0);
        $q = $this->db->get('s_course_act_asheet');
        $to_create = FALSE;
        $to_replace = FALSE;
        if ($q->num_rows() > 0) {

            if ($remove_old_asheet) {
                $to_create = TRUE;
                $to_replace = TRUE;
            }
        } else {
            $to_create = TRUE;
        }

        if (!$to_create) {
            $row = $q->row_array();
            //มีแต่ยังไม่ได้ส่ง
            if ($row['send_time'] == 0) {
                $answer_sheet_data = json_decode($row['answer_sheet_data'], TRUE);
            } else { //มีและส่งแล้ว
                $answer_sheet_data = FALSE;
            }
        } else {
            $question_index = 0;
            $question = array();
            if ($sheet_data['data']['question_num'] > 0) {
                $section_index = 0;

                foreach ($sheet_data['data']['sheet_set'] as $k => $q) {

                    if (isset($q['resource_id'])) {
                        $dycontent_data = $this->CI->dycontent_model->get_dycontent_data($q['resource_id']);
                        foreach ($dycontent_data['data']['content_questions'] as $inner_k => $inner_q) {
                            switch ($inner_q['content_type_id']) {
                                case 2: case 3: //ตัวเลือก คำตอบเดียว และหลายคำตอบ
                                    $question[$question_index] = array(
                                        'resource_id' => $q['resource_id'],
                                        'q_index' => $inner_k,
                                        'send_answer' => array(),
                                        'true_answers' => $inner_q['true_answers'],
                                        'send_time' => '',
                                        'sure' => '',
                                        'is_true' => '',
                                        'content_type_id' => $inner_q['content_type_id'],
                                        'section_index' => $section_index,
                                        'score_pq' => $q['score_pq'],
                                        'get_score' => ''
                                    );
                                    $question_index++;
                                    break;
                                case 4:case 5://เติมคำ คำตอบเดียว และหลายคำตอบ
                                    $question[$question_index] = array(
                                        'resource_id' => $q['resource_id'],
                                        'q_index' => $inner_k,
                                        'send_answer' => array(),
                                        'true_answers' => $inner_q['true_answers'],
                                        'send_time' => '',
                                        'sure' => '',
                                        'is_true' => '',
                                        'content_type_id' => $inner_q['content_type_id'],
                                        'section_index' => $section_index,
                                        'score_pq' => $q['score_pq'],
                                        'get_score' => ''
                                    );
                                    $question_index++;
                                    break;
                                default:
                                    break;
                            }
                        }
                    } else {
                        //ถ้าแบบเป็นตอนๆ index เรื่อมต้นจะเป็น 0 ไม่ต้อง + index
                        //แต่ถ้าเลยถ้าวนลูป ไปอีก ถ้าเจอที่ไม่ใช่โจทย์ต้อง + index
                        //ถ้าไม่เจอหัวตอนเลยจะไม่เข้าใน else นี้
                        if ($k > 0) {
                            $section_index++;
                        }
                    }
                }
            }

            $answer_sheet_data = array(
                'title' => $act_data['title'],
                'answer_sheet' => $question,
                'question_count' => $question_index,
                'send_count' => 0,
                'sure_count' => 0,
                'section_score' => $sheet_data['section_score'],
                'section_num' => $sheet_data['data']['section_num'],
                'full_score' => 0,
                'get_score' => 0,
                'is_pass' => 0
            );
            $course_act_asheet_set = array(
                'ca_id' => $ca_id,
                'c_id' => $act_data['c_id'],
                'uid' => $uid,
                'full_score' => $act_data['full_score'],
                'get_score' => 0,
                'send_time' => '',
                'update_time' => $this->time,
                'answer_sheet_data' => json_encode($answer_sheet_data),
                'activity_data' => '',
                'is_online' => '1'
            );

            $this->db->set($course_act_asheet_set);
            if ($to_replace) {

                $this->db->where('ca_id', $ca_id);
                $this->db->where('uid', $uid);
                $this->db->update('s_course_act_asheet');
            } else {

                $this->db->insert('s_course_act_asheet');
            }
        }
        $data['cource_act_data'] = $act_data;
        $data['answer_sheet_data'] = $answer_sheet_data;

        return $data;
    }

    function get_exam_answer_sheet() {

        return $this->course_act_asheet_data['answer_sheet_data']['answer_sheet'];
    }

    function get_section_num() {
        return $this->course_act_asheet_data['answer_sheet_data']['section_num'];
    }

    function init_course_act_asheet($ca_id, $return = FALSE) {
        $uid = $this->auth->uid();
        $this->db->where('ca_id', $ca_id);
        $this->db->where('uid', $uid);
        $q = $this->db->get('s_course_act_asheet');
        $row = $q->row_array();
        $row['answer_sheet_data'] = json_decode($row['answer_sheet_data'], TRUE);
        if ($return) {
            return $row;
        } else {
            $this->course_act_asheet_data = $row;
        }
    }

    function get_question_data($sheet_q_index, $ca_id) {
        $this->init_course_act_asheet($ca_id);
        return $this->course_act_asheet_data['answer_sheet_data']['answer_sheet'][$sheet_q_index];
    }

    function get_question_one($resource_id, $sheet_q_index) {
        $this->load->library('resource_codec');
        $this->db->where('resource_id', $resource_id);
        $q = $this->db->get('r_resource_dycontent');
        $data = $this->resource_codec->sheet_decode($q->row()->data);
        $q_one['content_header'] = $data['content_header'];
        $q_one['content_question'] = $data['content_questions'][$sheet_q_index];
        return $q_one;
    }

    function get_resource_data($resource_id) {
        $this->db->where('resource_id', $resource_id);
        $q = $this->db->get('r_resource');
        return $q->row_array();
    }

//    function get_resource_id_video_guide($resource_id_dycontent) {
//        //return FALSE; //for set no video guide
//
//        $resource_data = $this->get_resource_data($resource_id_dycontent);
//        if ($resource_data['resource_id_parent'] != 0) {
//            $this->db->where('resource_id', $resource_data['resource_id_parent']);
//            $q = $this->db->get('r_resource_video_join');
//            if ($q->num_rows() > 0) {
//                return $q->row()->resource_id_video;
//            }
//            return FALSE;
//        }
//        return FALSE;
//    }

    function get_resource_id_question_guide($resource_id_dycontent) {
        $result = array(
            'resource_id_video' => FALSE,
            'resource_id_dycontent' => FALSE
        );
        $resource_data = $this->get_resource_data($resource_id_dycontent);
        if ($resource_data['resource_id_parent'] != 0) { //เป็นโจทย์เทียบ
            $this->db->where('resource_id', $resource_data['resource_id_parent']);
            $q = $this->db->get('r_resource_video_join');
            if ($q->num_rows() > 0) {
                $result['resource_id_video'] = $q->row()->resource_id_video;
            }
            $result['resource_id_dycontent'] = $resource_data['resource_id_parent'];
            return $result;
        } else {
            return FALSE;
            $this->db->where('resource_id', $resource_id_dycontent);
            $q = $this->db->get('r_resource_video_join');
            if ($q->num_rows() > 0) {
                $result['resource_id_video'] = $q->row()->resource_id_video;
            }
            $result['resource_id_dycontent'] = $resource_id_dycontent;
            return $result;
        }
        return FALSE;
    }

    function get_question_count() {

        return $this->course_act_asheet_data['answer_sheet_data']['question_count'];
    }

    function send_answer($ca_id, $send_answer) {

        $uid = $this->auth->uid();
        $this->init_course_act_asheet($ca_id);
        $b_send = FALSE;
        if (count($send_answer) == 1) {
            $sheet_q_index = current(array_keys($send_answer));
            if (isset($send_answer[$sheet_q_index]['answer'])) {
                $b_send = TRUE;
                $this->course_act_asheet_data['answer_sheet_data']['answer_sheet'][$send_answer[$sheet_q_index]['sheet_q_index']]['send_answer'] = $send_answer[$sheet_q_index]['answer'];
                $this->course_act_asheet_data['answer_sheet_data']['answer_sheet'][$send_answer[$sheet_q_index]['sheet_q_index']]['send_time'] = $this->time;
                $this->course_act_asheet_data['answer_sheet_data']['answer_sheet'][$send_answer[$sheet_q_index]['sheet_q_index']]['sure'] = $send_answer[$sheet_q_index]['sure'];
            }
        }
        //นับ การส่งและการแน่ใจ
        $sure_count = 0;
        $send_cont = 0;

        foreach ($this->course_act_asheet_data['answer_sheet_data']['answer_sheet'] as $v2) {
            if ($v2['sure'] == 1) {
                $sure_count++;
            }
            if (count($v2['send_answer']) > 0) {
                $send_cont++;
            }
        }
        $this->course_act_asheet_data['answer_sheet_data']['send_count'] = $send_cont;
        $this->course_act_asheet_data['answer_sheet_data']['sure_count'] = $sure_count;
        if ($b_send) {
            $json_answer_sheet_data = json_encode($this->course_act_asheet_data['answer_sheet_data']);
            $this->db->set('answer_sheet_data', $json_answer_sheet_data);
            $this->db->where('ca_id', $ca_id);
            $this->db->where('uid', $uid);
            $this->db->update('s_course_act_asheet');
        }

        return $this->course_act_asheet_data;
    }

//    function get_send_count() {
//
//        return $this->course_act_asheet_data['answer_sheet_data']['send_count'];
//    }
//
//    function get_sure_count() {
//        return $this->course_act_asheet_data['answer_sheet_data']['sure_count'];
//    }

    function commit_exam_answer_sheet($ca_id, $save_sent = TRUE) {
        $uid = $this->auth->uid();
        $this->init_course_act_asheet($ca_id);
        // ทำการเฉลยข้อสอบ
        foreach ($this->course_act_asheet_data['answer_sheet_data']['answer_sheet'] as $k => $v) {

            switch ($v['content_type_id']) {

                case 2://โจทย์ตัวเลือก
                case 4://โจทย์เติมคำ
                    if (isset($v['send_answer'][0])) {
                        if ($v['true_answers'][0] == trim($v['send_answer'][0])) {
                            $this->course_act_asheet_data['answer_sheet_data']['answer_sheet'][$k]['is_true'] = 1;
                            $score_pq = $this->course_act_asheet_data['answer_sheet_data']['answer_sheet'][$k]['score_pq'];
                            $get_score = $this->course_act_asheet_data['answer_sheet_data']['answer_sheet'][$k]['get_score'] = 1 * $score_pq;
                        } else {
                            $get_score = $this->course_act_asheet_data['answer_sheet_data']['answer_sheet'][$k]['is_true'] = 0;
                            $this->course_act_asheet_data['answer_sheet_data']['answer_sheet'][$k]['get_score'] = 0;
                        }
                    } else {
                        $get_score = $this->course_act_asheet_data['answer_sheet_data']['answer_sheet'][$k]['is_true'] = 0;
                        $this->course_act_asheet_data['answer_sheet_data']['answer_sheet'][$k]['get_score'] = 0;
                    }
                    //ให้คะแนน Section
                    $section_index = $this->course_act_asheet_data['answer_sheet_data']['answer_sheet'][$k]['section_index'];
                    if (!isset($this->course_act_asheet_data['answer_sheet_data']['section_score'][$section_index]['get_score'])) {
                        $this->course_act_asheet_data['answer_sheet_data']['section_score'][$section_index]['get_score'] = $get_score;
                    } else {
                        $this->course_act_asheet_data['answer_sheet_data']['section_score'][$section_index]['get_score'] += $get_score;
                    }
                    break;
                case 3://เลือกได้หลายคำตอบ
                case 5://เติมคำได้หลายคำตอบ
                    $b_true = 1;
                    if (isset($v['send_answer'][0])) {
                        foreach ($v['send_answer'] as $vs) {
                            if (!in_array(trim($vs), $v['true_answers'])) {
                                $b_true = 0;
                            }
                        }
                        $score_pq = $this->course_act_asheet_data['answer_sheet_data']['answer_sheet'][$k]['score_pq'];
                        $this->course_act_asheet_data['answer_sheet_data']['answer_sheet'][$k]['get_score'] = $b_true * $score_pq;
                        $this->course_act_asheet_data['answer_sheet_data']['answer_sheet'][$k]['is_true'] = $b_true;
                        //ให้คะแนน Section
                        $section_index = $this->course_act_asheet_data['answer_sheet_data']['answer_sheet'][$k]['section_index'];
                        if (isset($this->course_act_asheet_data['answer_sheet_data']['section_score'][$section_index]['get_score'])) {
                            $this->course_act_asheet_data['answer_sheet_data']['section_score'][$section_index]['get_score'] += $get_score;
                        } else {
                            $this->course_act_asheet_data['answer_sheet_data']['section_score'][$section_index]['get_score'] = 0;
                        }
                    } else {
                        $get_score = $this->course_act_asheet_data['answer_sheet_data']['answer_sheet'][$k]['is_true'] = 0;
                        $this->course_act_asheet_data['answer_sheet_data']['answer_sheet'][$k]['get_score'] = 0;
                    }
                    //ให้คะแนน Section
                    $section_index = $this->course_act_asheet_data['answer_sheet_data']['answer_sheet'][$k]['section_index'];
                    if (!isset($this->course_act_asheet_data['answer_sheet_data']['section_score'][$section_index]['get_score'])) {
                        $this->course_act_asheet_data['answer_sheet_data']['section_score'][$section_index]['get_score'] = $get_score;
                    } else {
                        $this->course_act_asheet_data['answer_sheet_data']['section_score'][$section_index]['get_score'] += $get_score;
                    }
                    break;
                default:
                    break;
            }
        }

        $this->db->where('ca_id', $ca_id);
        $q = $this->db->get('s_course_act');
        $row_course_act = $q->row_array();
        $score_data = $this->get_summary_answer_sheet($ca_id);
        $this->course_act_asheet_data['answer_sheet_data']['full_score'] = $score_data['full_score'];
        $this->course_act_asheet_data['answer_sheet_data']['get_score'] = $score_data['get_score'];
        $this->course_act_asheet_data['answer_sheet_data']['is_pass'] = $score_data['is_pass'];
        //ปรับคะแนน
        $get_score = ($row_course_act['full_score'] * $score_data['get_score']) / $score_data['full_score'];

        //update s_course_act_asheet
        $set = array(
            'full_score' => $row_course_act['full_score'],
            'get_score' => $get_score,
            'send_time' => $this->time,
            'update_time' => $this->time,
            'answer_sheet_data' => json_encode($this->course_act_asheet_data['answer_sheet_data']),
            'is_online' => '0',
            'is_pass' => $score_data['is_pass']
        );
        $this->db->set($set);
        $this->db->where('ca_id', $ca_id);
        $this->db->where('uid', $uid);
        $this->db->update('s_course_act_asheet');
        $cas_id = $this->db->insert_id();


        if ($save_sent) {
            //SAVE TO DB
            // check ว่า ส่งแล้วหรือยัง
            $this->db->where('ca_id', $ca_id);
            $this->db->where('uid_sender', $uid);
            $q = $this->db->get('s_course_act_send');
            if ($q->num_rows() > 0) {
                $row = $q->row_array();
                $set = array(
                    'st_id' => $row_course_act['st_id'],
                    'get_score' => $get_score,
                    'full_score' => $row_course_act['full_score'],
                    'send_time' => $this->time,
                    'update_time' => $this->time,
                    'data' => json_encode($this->course_act_asheet_data['answer_sheet_data']),
                    'give_score_time' => $this->time,
                    'uid_give_score' => $uid,
                    'is_pass' => $score_data['is_pass'],
                );
                $this->db->set($set);
                $this->db->where('cas_id', $row['cas_id']);
                $this->db->update('s_course_act_send');
                $cas_id = $row['cas_id'];
            } else {
                $set = array(
                    'ca_id' => $ca_id,
                    'c_id' => $row_course_act['c_id'],
                    'st_id' => $row_course_act['st_id'],
                    'get_score' => $get_score,
                    'full_score' => $row_course_act['full_score'],
                    'send_time' => $this->time,
                    'update_time' => $this->time,
                    'uid_sender' => $uid,
                    'data' => json_encode($this->course_act_asheet_data['answer_sheet_data']),
                    'give_score_time' => $this->time,
                    'uid_give_score' => $uid,
                    'is_pass' => $score_data['is_pass'],
                );
                $this->db->set($set);
                $this->db->insert('s_course_act_send');
                $cas_id = $this->db->insert_id();
            }

            return $cas_id;
        } else {
            
        }
    }

    function get_summary_answer_sheet() {
        $b_pass = 1;
        $full_score = 0;
        $get_score = 0;

        foreach ($this->course_act_asheet_data['answer_sheet_data']['section_score'] as $v) {
            if ($v['pass_score'] > $v['get_score']) {
                $b_pass = 0;
            }
            $full_score += $v['full_score'];
            $get_score += $v['get_score'];
        }
        $data = array(
            'is_pass' => $b_pass,
            'get_score' => $get_score,
            'full_score' => $full_score
        );
        return $data;
    }

    function get_summary_course_act_send($cas_id, $uid = '') {
        if ($uid == '') {
            $uid = $this->auth->uid();
        }
        $this->db->where('uid_sender', $uid);
        $this->db->where('cas_id', $cas_id);
        $q = $this->db->get('s_course_act_send');
        $row = $q->row_array();
        $row['data'] = json_decode($row['data'], TRUE);
        return $row;
    }

    function get_cas_id($ca_id, $uid = '') {
        if ($uid == '') {
            $uid = $this->auth->uid();
        }
        $this->db->where('uid_sender', $uid);
        $this->db->where('ca_id', $ca_id);
        $q = $this->db->get('s_course_act_send');
        if ($q->num_rows() > 0) {
            return $q->row()->cas_id;
        }
        return FALSE;
    }

    // for teacher



    function get_teacher_summary_course_act_send($ca_id) {
        $uid = $this->auth->uid();
        $this->db->where('ca_id', $ca_id);
        $this->db->where('uid', $uid);
        $q = $this->db->get('s_course_act_asheet');
        $row = $q->row_array();
        $row['answer_sheet_data'] = json_decode($row['answer_sheet_data'], TRUE);
        return $row;
    }

}
