<?php

/**
 * ptest_model
 * @property resource_codec $resource_codec
 */
class ptest_model extends CI_Model {

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

    function make_answer_sheet($ca_id, $uid = '', $test_type = 'pretest') {
        if ($uid == '') {
            $uid = $this->auth->uid();
        }

        $act_data = $this->CI->course_model->get_act_data($ca_id);
        $sheet_data = $this->CI->sheet_model->get_resource_data($act_data['data']);


        if ($test_type == 'posttest') {
            $prefix_field = 'post_';
        } else {
            $prefix_field = 'pre_';
        }

        //check have answer sheet
        $this->db->where('ca_id', $ca_id);
        $this->db->where('uid', $uid);
        $q = $this->db->get('s_course_act_asheet_ptest');


        if ($q->num_rows() > 0) {
            $row = $q->row_array();
            //มีแต่ยังไม่ได้ส่ง
            if ($row[$prefix_field . 'send_time'] == 0) {
                $answer_sheet_data = json_decode($row[$prefix_field . 'answer_sheet_data'], TRUE);
            } else { //มีและส่งแล้ว
                $answer_sheet_data = FALSE;
            }
        } else {
            //ถ้าไม่มีข้อมูลเลยให้สร้าง
            $question_index = 0;
            $question = array();
            $full_score = 0;
            if ($sheet_data['data']['question_num'] > 0) {

                $section_index = 0;

                foreach ($sheet_data['data']['sheet_set'] as $k => $q) {

                    if (isset($q['resource_id'])) {
                        $dycontent_data = $this->CI->dycontent_model->get_dycontent_data($q['resource_id']);
                        if ($dycontent_data) {
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
                                        $full_score +=$q['score_pq'];
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
                                        $full_score +=$q['score_pq'];
                                        break;
                                    default:
                                        break;
                                }
                            }
                        } else {
                            return FALSE;
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
                'full_score' => $full_score,
                'get_score' => 0,
                'is_pass' => 0
            );
            $course_act_asheet_set = array(
                'ca_id' => $ca_id,
                'c_id' => $act_data['c_id'],
                'uid' => $uid,
                'pre_full_score' => $full_score,
                'pre_get_score' => 0,
                'pre_send_time' => '',
                'pre_update_time' => $this->time,
                'pre_answer_sheet_data' => json_encode($answer_sheet_data),
                'pre_activity_data' => '',
                'post_full_score' => $full_score,
                'post_get_score' => 0,
                'post_send_time' => '',
                'post_update_time' => $this->time,
                'post_answer_sheet_data' => json_encode($answer_sheet_data),
                'post_activity_data' => '',
                'is_online' => '1'
            );
            $this->db->set($course_act_asheet_set);
            $this->db->insert('s_course_act_asheet_ptest');
        }
        $data['cource_act_data'] = $act_data;
        $data['answer_sheet_data'] = $answer_sheet_data;

        return $data;
    }

    function get_ptest_answer_sheet() {

        return $this->course_act_asheet_data['answer_sheet_data']['answer_sheet'];
    }

    function get_section_num() {
        return $this->course_act_asheet_data['answer_sheet_data']['section_num'];
    }

    function init_course_act_asheet($ca_id, $test_type, $return = FALSE) {
        $uid = $this->auth->uid();
        $this->db->where('ca_id', $ca_id);
        $this->db->where('uid', $uid);

        $q = $this->db->get('s_course_act_asheet_ptest');
        $row = $q->row_array();
        if ($test_type == 'posttest') {
            $row['answer_sheet_data'] = json_decode($row['post_answer_sheet_data'], TRUE);
        } else {
            $row['answer_sheet_data'] = json_decode($row['pre_answer_sheet_data'], TRUE);
        }

        if ($return) {
            return $row;
        } else {
            $this->course_act_asheet_data = $row;
        }
    }

    function get_question_data($sheet_q_index, $ca_id, $test_type) {
        $this->init_course_act_asheet($ca_id, $test_type);

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

    function get_resource_id_video_guide($resource_id_dycontent) {
        $resource_data = $this->get_resource_data($resource_id_dycontent);
        if ($resource_data['resource_id_parent'] != 0) {
            $this->db->where('resource_id', $resource_data['resource_id_parent']);
            $q = $this->db->get('r_resource_video_join');
            if ($q->num_rows() > 0) {
                return $q->row()->resource_id_video;
            }
            return FALSE;
        }
        return FALSE;
    }

    function get_question_count() {

        return $this->course_act_asheet_data['answer_sheet_data']['question_count'];
    }

    function send_answer($ca_id, $send_answer, $test_type) {
        $uid = $this->auth->uid();
        $this->init_course_act_asheet($ca_id, $test_type);
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
        $json_answer_sheet_data = json_encode($this->course_act_asheet_data['answer_sheet_data']);
        if ($b_send) {
            if ($test_type == 'posttest') {
                $prefix_field = 'post_';
            } else {
                $prefix_field = 'pre_';
            }

            $this->db->set($prefix_field . 'answer_sheet_data', $json_answer_sheet_data);
            $this->db->where('ca_id', $ca_id);
            $this->db->where('uid', $uid);
            $this->db->update('s_course_act_asheet_ptest');
        }

        return $this->course_act_asheet_data;
    }

    function commit_ptest_answer_sheet($ca_id, $test_type) {
        if ($test_type == 'posttest') {
            $prefix_field = 'post_';
        } else {
            $prefix_field = 'pre_';
        }
        $this->init_course_act_asheet($ca_id, $test_type);
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

        //SAVE TO DB
        $this->db->where('ca_id', $ca_id);
        $q = $this->db->get('s_course_act');
        $row_course_act = $q->row_array();
        $score_data = $this->get_summary_answer_sheet($ca_id);
        $this->course_act_asheet_data['answer_sheet_data']['full_score'] = $score_data['full_score'];
        $this->course_act_asheet_data['answer_sheet_data']['get_score'] = $score_data['get_score'];
        $this->course_act_asheet_data['answer_sheet_data']['is_pass'] = $score_data['is_pass'];
        //ปรับคะแนน
        if ($row_course_act['full_score'] == 0) {
            $row_course_act['full_score'] = $score_data['full_score'];
        }
        $get_score = ($row_course_act['full_score'] * $score_data['get_score']) / $score_data['full_score'];
        // check ว่า ส่งแล้วหรือยัง
        $this->db->where('ca_id', $ca_id);
        $this->db->where('uid', $this->auth->uid());
        $this->db->where($prefix_field . 'send_time', 0);
        $q = $this->db->get('s_course_act_asheet_ptest');
        if ($q->num_rows() > 0) { //ส่งไปแล้ว
            $caaspt_id = $q->row()->caaspt_id;
            $set = array(
                'ca_id' => $ca_id,
                'c_id' => $row_course_act['c_id'],
                $prefix_field . 'get_score' => $get_score,
                $prefix_field . 'full_score' => $row_course_act['full_score'],
                $prefix_field . 'send_time' => $this->time,
                $prefix_field . 'update_time' => $this->time,
                $prefix_field . 'answer_sheet_data' => json_encode($this->course_act_asheet_data['answer_sheet_data']),
            );
            $this->db->set($set);
            $this->db->where('caaspt_id', $caaspt_id);
            $this->db->update('s_course_act_asheet_ptest');
        } else {
            return FALSE;
        }
        return $caaspt_id;
    }

    function get_summary_answer_sheet() {
        $b_pass = 1;
        $full_score = 0;
        $get_score = 0;

        //print_r($this->course_act_asheet_data['answer_sheet_data']['section_score']);
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

    function get_summary_course_act_send($caaspt_id, $uid = '') {
        if ($uid == '') {
            $uid = $this->auth->uid();
        }
        $this->db->where('caaspt_id', $caaspt_id);

        $q = $this->db->get('s_course_act_asheet_ptest');
        $row = $q->row_array();

        $row['pre_answer_sheet_data'] = json_decode($row['pre_answer_sheet_data'], TRUE);
        $row['post_answer_sheet_data'] = json_decode($row['post_answer_sheet_data'], TRUE);
        return $row;
    }

    function get_cas_id($ca_id) {
        $this->db->where('ca_id', $ca_id);
        $q = $this->db->get('s_course_act_send');
        if ($q->num_rows() > 0) {
            return $q->row()->cas_id;
        }
        return FALSE;
    }

    function get_all_summary($ca_id) {
        $this->db->where('ca_id', $ca_id);
        $q = $this->db->get('s_course_act_asheet_ptest');
        if ($q->num_rows() > 0) {
            $data = array();
            $result = array();
            foreach ($q->result_array() as $row) {
                $data = $row;
                $data['user_data'] = $this->auth->get_user_data($row['uid']);
                $data['pre_answer_sheet_data'] = json_decode($row['pre_answer_sheet_data'], TRUE);
                $data['post_answer_sheet_data'] = json_decode($row['post_answer_sheet_data'], TRUE);
                $result[] = $data;
            }
            return $result;
        }
        return FALSE;
    }

    function get_ranking($ca_id, $type) {
        $user_data = array();
        switch ($type) {
            case 'pre':
                $sql = "SELECT DISTINCT
s_course_act_asheet_ptest_summary.ca_id,
s_course_act_asheet_ptest_summary.c_id,
s_course_act_asheet_ptest_summary.uid,
s_course_act_asheet_ptest_summary.pre_caaspt_id,
s_course_act_asheet_ptest_summary.pre_full_score,
s_course_act_asheet_ptest_summary.pre_get_score,
s_course_act_asheet_ptest_summary.pre_send_time,
s_course_act_asheet_ptest_summary.pre_update_time
FROM
s_course_act_asheet_ptest_summary
WHERE
s_course_act_asheet_ptest_summary.ca_id = $ca_id
ORDER BY
s_course_act_asheet_ptest_summary.pre_get_score DESC
";
                $q = $this->db->query($sql);
                if ($q->num_rows() > 0) {
                    $result = array();
                    foreach ($q->result_array() as $r) {
                        if (!isset($user_data[$r['uid']])) {
                            $user_data[$r['uid']] = $this->auth->get_user_data($r['uid']);
                        }
                        $r['user_data'] = $user_data[$r['uid']];
                        $r['get_score'] = $r['pre_get_score'];
                        $result[] = $r;
                    }
                    return $result;
                } else {
                    return FALSE;
                }
                break;
            case 'post':
                $sql = "SELECT DISTINCT
s_course_act_asheet_ptest_summary.ca_id,
s_course_act_asheet_ptest_summary.c_id,
s_course_act_asheet_ptest_summary.uid,
s_course_act_asheet_ptest_summary.post_caaspt_id,
s_course_act_asheet_ptest_summary.post_full_score,
s_course_act_asheet_ptest_summary.post_get_score,
s_course_act_asheet_ptest_summary.post_send_time,
s_course_act_asheet_ptest_summary.post_update_time
FROM
s_course_act_asheet_ptest_summary
WHERE
s_course_act_asheet_ptest_summary.ca_id = $ca_id AND
s_course_act_asheet_ptest_summary.post_get_score > 0
ORDER BY
s_course_act_asheet_ptest_summary.post_get_score DESC
";
                $q = $this->db->query($sql);
                if ($q->num_rows() > 0) {
                    $result = array();
                    foreach ($q->result_array() as $r) {
                        if (!isset($user_data[$r['uid']])) {
                            $user_data[$r['uid']] = $this->auth->get_user_data($r['uid']);
                        }
                        $r['user_data'] = $user_data[$r['uid']];
                        $r['get_score'] = $r['post_get_score'];
                        $result[] = $r;
                    }
                    return $result;
                } else {
                    return FALSE;
                }

                break;

            default:
                return FALSE;
                break;
        }
    }

    function processing_summary($ca_id='') {

        $sql = "SELECT DISTINCT
s_course_act_asheet_ptest.uid,
s_course_act_asheet_ptest.ca_id
FROM
s_course_act_asheet_ptest 
";
        if($ca_id != ''){
            $sql = $sql." WHERE s_course_act_asheet_ptest.ca_id=$ca_id";
        }
        $q_distinct = $this->db->query($sql);
        foreach ($q_distinct->result_array() as $v_distinct) {
            $sql_max = "SELECT
s_course_act_asheet_ptest.caaspt_id,
s_course_act_asheet_ptest.ca_id,
s_course_act_asheet_ptest.c_id,
s_course_act_asheet_ptest.uid,
s_course_act_asheet_ptest.pre_full_score,
Max(s_course_act_asheet_ptest.pre_get_score) pre_get_score,
s_course_act_asheet_ptest.pre_send_time,
s_course_act_asheet_ptest.pre_update_time,
s_course_act_asheet_ptest.pre_activity_data

FROM
s_course_act_asheet_ptest
WHERE
s_course_act_asheet_ptest.uid = $v_distinct[uid] AND
s_course_act_asheet_ptest.ca_id = $v_distinct[ca_id] AND
s_course_act_asheet_ptest.pre_send_time >0 AND
s_course_act_asheet_ptest.pre_get_score >0
GROUP BY
s_course_act_asheet_ptest.uid
";
            $q_max = $this->db->query($sql_max);
            if ($q_max->num_rows() > 0) {
                $r_max = $q_max->row_array();
                $this->db->where('uid', $r_max['uid']);
                $this->db->where('ca_id', $r_max['ca_id']);
                $r_max['pre_caaspt_id'] = $r_max['caaspt_id'];
                unset($r_max['caaspt_id']);

                if ($this->db->count_all_results('s_course_act_asheet_ptest_summary') > 0) {

                    $this->db->where('uid', $r_max['uid']);
                    $this->db->where('ca_id', $r_max['ca_id']);
                    unset($r_max['uid']);
                    unset($r_max['ca_id']);
                    $this->db->set($r_max);
                    $this->db->update('s_course_act_asheet_ptest_summary');
                } else {
                    $this->db->set($r_max);
                    $this->db->insert('s_course_act_asheet_ptest_summary');
                }
            }
            $sql_max = "SELECT
s_course_act_asheet_ptest.caaspt_id,
s_course_act_asheet_ptest.ca_id,
s_course_act_asheet_ptest.c_id,
s_course_act_asheet_ptest.uid,
s_course_act_asheet_ptest.post_full_score,
Max(s_course_act_asheet_ptest.post_get_score) post_get_score,
s_course_act_asheet_ptest.post_send_time,
s_course_act_asheet_ptest.post_update_time,
s_course_act_asheet_ptest.post_activity_data

FROM
s_course_act_asheet_ptest
WHERE
s_course_act_asheet_ptest.uid = $v_distinct[uid] AND
s_course_act_asheet_ptest.ca_id = $v_distinct[ca_id] AND
s_course_act_asheet_ptest.post_send_time >0 AND
s_course_act_asheet_ptest.post_get_score >0
GROUP BY
s_course_act_asheet_ptest.uid
";
            $q_max = $this->db->query($sql_max);
            if ($q_max->num_rows() > 0) {
                $r_max = $q_max->row_array();
                $this->db->where('uid', $r_max['uid']);
                $this->db->where('ca_id', $r_max['ca_id']);
                $r_max['post_caaspt_id'] = $r_max['caaspt_id'];
                unset($r_max['caaspt_id']);

                if ($this->db->count_all_results('s_course_act_asheet_ptest_summary') > 0) {

                    $this->db->where('uid', $r_max['uid']);
                    $this->db->where('ca_id', $r_max['ca_id']);
                    unset($r_max['uid']);
                    unset($r_max['ca_id']);
                    $this->db->set($r_max);
                    $this->db->update('s_course_act_asheet_ptest_summary');
                } else {
                    $this->db->set($r_max);
                    $this->db->insert('s_course_act_asheet_ptest_summary');
                }
            }
        }
    }

}
