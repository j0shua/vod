<?php

/**
 * practice_model
 * @property resource_codec $resource_codec
 */
class practice_model extends CI_Model {

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

    function make_answer_sheet($ca_id, $uid = '') {
        if ($uid == '') {
            $uid = $this->auth->uid();
        }
        $act_data = $this->CI->course_model->get_act_data($ca_id);
        $sheet_data = $this->CI->sheet_model->get_resource_data($act_data['data']);
        //check have answer sheet
        $this->db->where('ca_id', $ca_id);
        $this->db->where('uid', $uid);
        $this->db->where('send_time', 0);
        $q = $this->db->get('s_course_act_asheet_practice');

        $create_new = TRUE;
        if ($q->num_rows() > 0) {
            $row = $q->row_array();
            $create_new = FALSE;
        }


        if (!$create_new) {
            $row = $q->row_array();
            $answer_sheet_data = json_decode($row['answer_sheet_data'], TRUE);
            $caasp_id = $row['caasp_id'];
        } else {
            $question_index = 0;
            $question = array();
            if ($sheet_data['data']['question_num'] > 0) {
                $section_index = 0;


                $resource_id_not_in = array();
                
                foreach ($sheet_data['data']['sheet_set'] as $k => $q) {
                    if (isset($q['resource_id'])) {
                        $resource_id_not_in[] = $q['resource_id'];
                    }
                }
                
                foreach ($sheet_data['data']['sheet_set'] as $k => $q) {

                    if (isset($q['resource_id'])) { //ถ้าเป็น dycontent
                        $dycontent_data = $this->CI->dycontent_model->get_dycontent_data($q['resource_id']);
                        if ($dycontent_data) {
                            foreach ($dycontent_data['data']['content_questions'] as $inner_q) {
                                if ($inner_q['content_type_id'] != 1) {
                                    $resource_id_new = $this->get_resource_id_question_same_sub_chapter($q['resource_id'], $resource_id_not_in);
                                    $resource_id_not_in[] = $resource_id_new;
                                }
                                $dycontent_data_new = $this->CI->dycontent_model->get_dycontent_data($resource_id_new);


                                $inner_q_new = $dycontent_data_new['data']['content_questions'][0];
                                $inner_k_new = 0;

                                switch ($inner_q_new['content_type_id']) {
                                    case 2: case 3: //ตัวเลือก คำตอบเดียว และหลายคำตอบ
                                        $question[$question_index] = array(
                                            'resource_id' => $resource_id_new,
                                            'q_index' => $inner_k_new,
                                            'send_answer' => array(),
                                            'true_answers' => $inner_q_new['true_answers'],
                                            'send_time' => '',
                                            'sure' => '',
                                            'is_true' => '',
                                            'content_type_id' => $inner_q_new['content_type_id'],
                                            'section_index' => $section_index,
                                            'score_pq' => $q['score_pq'],
                                            'get_score' => ''
                                        );
                                        $question_index++;
                                        break;
                                    case 4:case 5://เติมคำ คำตอบเดียว และหลายคำตอบ
                                        $question[$question_index] = array(
                                            'resource_id' => $resource_id_new,
                                            'q_index' => $inner_k_new,
                                            'send_answer' => array(),
                                            'true_answers' => $inner_q_new['true_answers'],
                                            'send_time' => '',
                                            'sure' => '',
                                            'is_true' => '',
                                            'content_type_id' => $inner_q_new['content_type_id'],
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
                            return FALSE;
                        }
                    } else {
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
            $this->db->insert('s_course_act_asheet_practice');
            $caasp_id = $this->db->insert_id();
        }
        $data['cource_act_data'] = $act_data;
        $data['answer_sheet_data'] = $answer_sheet_data;
        $data['caasp_id'] = $caasp_id;
        return $data;
    }

    function get_resource_id_question_same_sub_chapter($resource_id, $resource_id_not_in = array()) {
        $where_not_in = '';
        if (count($resource_id_not_in) > 0) {
            $where_not_in = 'and r_resource.resource_id not in (' . implode(',', $resource_id_not_in) . ')';
        }

        $this->db->where('resource_id', $resource_id);
        $q_resource = $this->db->get('r_resource');
        $r_resource = $q_resource->row_array();
//        $chapter_title = $r_resource['chapter_title'];
//        
//        $subj_id = $r_resource['subj_id'];


        $sub_chapter_title = $r_resource['sub_chapter_title'];
        $chapter_id = $r_resource['chapter_id'];
        $uid_owner = $r_resource['uid_owner'];
        //sql ค้นเต็ม --- ตอน
        $sql = "SELECT
r_resource_dycontent.resource_id,
r_resource_dycontent.`data`,
r_resource_dycontent.render_type_id,
r_resource_dycontent.num_questions,
r_resource_dycontent.content_type_id,
r_resource_dycontent.p,
r_resource_dycontent.r,
r_resource_dycontent.uid_owner
FROM r_resource_dycontent,(SELECT r_resource.resource_id FROM r_resource where resource_type_id = 4 
and uid_owner=$uid_owner and r_resource.resource_id_parent = 0 
and r_resource.chapter_id=$chapter_id 
and r_resource.sub_chapter_title='$sub_chapter_title' 
$where_not_in
)r_resource
where r_resource.resource_id=r_resource_dycontent.resource_id and content_type_id != 1 and num_questions =1 
ORDER BY RAND()
LIMIT 1
";
        $q_dycontent = $this->db->query($sql);
        if ($q_dycontent->num_rows() > 0) {
            return $q_dycontent->row()->resource_id;
        } else {
            //sql อนุโลม โจทย์เทียบได้
            $sql = "SELECT
r_resource_dycontent.resource_id,
r_resource_dycontent.`data`,
r_resource_dycontent.render_type_id,
r_resource_dycontent.num_questions,
r_resource_dycontent.content_type_id,
r_resource_dycontent.p,
r_resource_dycontent.r,
r_resource_dycontent.uid_owner
FROM r_resource_dycontent,(SELECT r_resource.resource_id FROM r_resource where resource_type_id = 4 
and uid_owner=$uid_owner and r_resource.resource_id_parent > 0 
and r_resource.chapter_id=$chapter_id 
and r_resource.sub_chapter_title='$sub_chapter_title' 
$where_not_in
)r_resource
where r_resource.resource_id=r_resource_dycontent.resource_id and content_type_id != 1 and num_questions =1 
ORDER BY RAND()
LIMIT 1
";
            $q_dycontent = $this->db->query($sql);
            if ($q_dycontent->num_rows() > 0) {
                return $q_dycontent->row()->resource_id;
            } else {
                //sql อนุโลม บทเดียวกัน
                $sql = "SELECT
r_resource_dycontent.resource_id,
r_resource_dycontent.`data`,
r_resource_dycontent.render_type_id,
r_resource_dycontent.num_questions,
r_resource_dycontent.content_type_id,
r_resource_dycontent.p,
r_resource_dycontent.r,
r_resource_dycontent.uid_owner
FROM r_resource_dycontent,(SELECT r_resource.resource_id FROM r_resource where resource_type_id = 4 
and uid_owner=$uid_owner and r_resource.resource_id_parent = 0 
and r_resource.chapter_id=$chapter_id    
)r_resource
where r_resource.resource_id=r_resource_dycontent.resource_id and content_type_id != 1 and num_questions =1 
ORDER BY RAND()
LIMIT 1
";
                $q_dycontent = $this->db->query($sql);
                if ($q_dycontent->num_rows() > 0) {
                    return $q_dycontent->row()->resource_id;
                } else {
                    
                }
            }
        }
    }

    function get_practice_answer_sheet() {

        return $this->course_act_asheet_data['answer_sheet_data']['answer_sheet'];
    }

    function get_section_num() {
        return $this->course_act_asheet_data['answer_sheet_data']['section_num'];
    }

    function init_course_act_asheet($caasp_id, $return = FALSE) {
        $uid = $this->auth->uid();
        $this->db->where('caasp_id', $caasp_id);
        $this->db->where('uid', $uid);
        $q = $this->db->get('s_course_act_asheet_practice');
        $row = $q->row_array();
        $row['answer_sheet_data'] = json_decode($row['answer_sheet_data'], TRUE);
        if ($return) {
            return $row;
        } else {
            $this->course_act_asheet_data = $row;
        }
    }

    function get_question_data($sheet_q_index, $caasp_id) {
        $this->init_course_act_asheet($caasp_id);
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
        $this->db->where('resource_id', $resource_id_dycontent);
        $q = $this->db->get('r_resource_video_join');
        if ($q->num_rows() > 0) {
            return $q->row()->resource_id_video;
        }
        return FALSE;
    }

    function get_question_count() {

        return $this->course_act_asheet_data['answer_sheet_data']['question_count'];
    }

    function send_answer($caasp_id, $send_answer) {
        $uid = $this->auth->uid();
        $this->init_course_act_asheet($caasp_id);
        $b_send = FALSE;
        if (count($send_answer) == 1) {
            $sheet_q_index = current(array_keys($send_answer));
            //$q_one = $this->course_act_asheet_data['answer_sheet_data']['answer_sheet'][$send_answer[0]['sheet_q_index']];
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
            $this->db->set('answer_sheet_data', $json_answer_sheet_data);
            $this->db->where('caasp_id', $caasp_id);
            $this->db->where('uid', $uid);
            $this->db->update('s_course_act_asheet_practice');
        }
        return $this->course_act_asheet_data;
    }

    function commit_practice_answer_sheet($caasp_id) {
        $this->init_course_act_asheet($caasp_id);
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

        $score_data = $this->get_summary_answer_sheet();
        $this->course_act_asheet_data['answer_sheet_data']['full_score'] = $score_data['full_score'];
        $this->course_act_asheet_data['answer_sheet_data']['get_score'] = $score_data['get_score'];
        $this->course_act_asheet_data['answer_sheet_data']['is_pass'] = $score_data['is_pass'];
        $this->db->where('caasp_id', $caasp_id);
        $this->db->where('uid', $this->auth->uid());
        $q = $this->db->get('s_course_act_asheet_practice');
        $row = $q->row_array();
        $set = array(
            'get_score' => $get_score,
            'send_time' => $this->time,
            'update_time' => $this->time,
            'answer_sheet_data' => json_encode($this->course_act_asheet_data['answer_sheet_data']),
        );
        $this->db->set($set);
        $this->db->where('caasp_id', $row['caasp_id']);
        $this->db->update('s_course_act_asheet_practice');
        $caasp_id = $row['caasp_id'];
        return $caasp_id;
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

    function get_db_summary_answer_sheet($caasp_id) {
        $this->db->where('caasp_id', $caasp_id);
        $q = $this->db->get('s_course_act_asheet_practice');
        $row = $q->row_array();
        $row['answer_sheet_data'] = json_decode($row['answer_sheet_data'], TRUE);
        return $row;
    }

    function get_all_summary($ca_id) {
        $sql = "SELECT
DISTINCT(s_course_act_asheet_practice.uid),
s_course_act_asheet_practice.caasp_id,
s_course_act_asheet_practice.ca_id,
s_course_act_asheet_practice.c_id,
s_course_act_asheet_practice.full_score,
MAX(s_course_act_asheet_practice.get_score)get_score,
s_course_act_asheet_practice.send_time,
s_course_act_asheet_practice.update_time,
s_course_act_asheet_practice.answer_sheet_data,
s_course_act_asheet_practice.activity_data,
s_course_act_asheet_practice.is_online
FROM (SELECT * FROM s_course_act_asheet_practice WHERE s_course_act_asheet_practice.ca_id = $ca_id)s_course_act_asheet_practice
GROUP BY s_course_act_asheet_practice.uid";
        $q = $this->db->query($sql);
        if ($q->num_rows() > 0) {
            $data = array();
            foreach ($q->result_array() as $row) {

                $row['user_data'] = $this->auth->get_user_data($row['uid']);
                $row['answer_sheet_data'] = json_decode($row['answer_sheet_data'], TRUE);
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    function get_caasp_id($ca_id) {
        $this->db->where('ca_id', $ca_id);
        $q = $this->db->get('s_course_act_asheet_practice');
        if ($q->num_rows() > 0) {
            return $q->row()->caasp_id;
        }
        return FALSE;
    }

}
