<?php

/**
 * Description of dycontent_model
 *
 * @author lojorider
 * @property resource_codec $resource_codec
 * 
 */
class dycontent_model extends CI_Model {

    var $CI;
    // ตัวแปร content r_resource_video_join
    var $content_header = ''; //ส่วนหัวสำหรับโจทย์กลุ่ม หรือ ส่วนตัวสำหรับเนื้อหา
    var $content_questions = array(); //ส่วนข้อสอบ เป็น array ใช้เก็บข้อมูลข้อสอบหลายข้อ
    var $resource_id_media = ''; //ใช้เก็บข้อมูล resource _id เพื่อที่จะนำไปแสดงสำหรับโจทย์ที่ต้องการ MP3 หรือ video
    //ตัวแปรเริ่มต้น
    var $time;
    var $content_type_id;  //ประเภทโจทย์
    var $resource_type_id = 4; //ประเภทหลัก   
    var $render_type_id; //ประเภทการแสดงผล render   
    var $array_content_type;
    var $array_render_type;
    private $resource_data = array(
        'data' => array('content_header' => '', 'content_questions' => '')
    );
    var $title_str_limit = 25;

    function __construct() {
        parent::__construct();
        $this->CI = & get_instance();
        $this->CI->load->model('core/ddoption_model');
        $this->time = time();
        $this->load->helper('tag');
        $this->load->library('resource_codec');
        $this->load->helper('str');
    }

    private function get_content_type_array() {
        if (empty($this->array_content_type)) {
            $this->array_content_type = array(
                '1' => 'เนื้อหา',
                '2' => 'mc',
                '3' => 'mcma',
                '4' => 'ct',
                '5' => 'mct',
                '6' => 'pair' // ตัวอย่างเช่น เติมคำในวิชา
            );
        }
        return $this->array_content_type;
    }

    private function get_render_type_array() {
        if (empty($this->array_render_type)) {
            $this->array_render_type = array(
                '1' => 'latex',
                '2' => 'html',
                '3' => 'bbcode'
            );
        }
        return $this->array_render_type;
    }

    public function find_all($page, $qtype, $query, $rp, $sortname, $sortorder) {
        if ($sortname == 'title_play') {
            $sortname = 'title';
        }
        $publish_options = $this->CI->ddoption_model->get_publish_options();
        $privacy_options = $this->CI->ddoption_model->get_privacy_options();
        if ($qtype == 'custom') {
            parse_str($query, $query);
        }
        $data = array(
            'rows' => array(),
            'page' => $page,
            'total' => 0
        );
        //make offset
        $offset = (($page - 1) * $rp);
        // Start Sql Query State for count row
        $this->find_all_where('r_resource', $qtype, $query);
        // END Sql Query State
        //$data['xxx'] = $this->db->_compile_select();
        $total = $this->db->count_all_results();

        // Start Sql Query State
        $this->db->select('r_resource.*');
        $this->find_all_where('r_resource', $qtype, $query);
        // END Sql Query State
        $this->db->limit($rp, $offset);
        $this->db->order_by($sortname, $sortorder);
        //$data['xxx'] = $this->db->_compile_select();
        $result = $this->db->get();
        $data['total'] = $total;

        foreach ($result->result_array() as $row) {
            $row['checkbox'] = '<input type = "checkbox" name = "cb_resource_id[]" value = "' . $row['resource_id'] . '" />';
            $row['count_video_join'] = $this->count_video_join($row['resource_id']);
            $row['action'] = '';
            if ($row['count_video_join'] > 0) {
                $row['action'] .= '<a href="' . site_url('resource/resource_join/join_video/' . $row['resource_id']) . '">แก้ไขการเชื่อมวิดีโอ</a>';
            } else {
                $row['action'] .= '<a href="' . site_url('resource/resource_join/join_video/' . $row['resource_id']) . '">เพิ่มการเชื่อมวิดีโอ</a>';
            }
            $row['action'] .= '<a href="' . site_url('resource/dycontent/edit/' . $row['resource_id']) . '">แก้ไข</a>';
            $row['action'] .= '<a href="' . site_url('resource/dycontent/delete/' . $row['resource_id']) . '">ลบ</a>';
            $title = $row['title'];
//            
//            $title = mb_substr($row['title'], 0, $this->title_str_limit, 'UTF-8');
//            if ($title != $row['title']) {
//                $title = $title . '...';
//            }
            $row['title'] = '<span title="ชื่อสื่อ : ' . $row['title'] . "\n" . 'คำอธิบาย : ' . $row['desc'] . '" >' . $title . '</span>';
            $row['title_play'] = '<a href="' . site_url('v/' . $row['resource_id']) . '" target="_blank">▶</a>' . $row['title'];
            $row['publish'] = $publish_options[$row['publish']];
            $row['privacy'] = $privacy_options[$row['privacy']];
            $row = array_merge($row, $this->get_dycontent_data($row['resource_id']));
            $row['second_dycontent_count'] = '-';
            if ($row['content_type_id'] != 1) {
                $row['second_dycontent_count'] = '<a title="เพิ่มโจทย์เทียบ" href="' . site_url('resource/dycontent/second_dycontent/' . $row['resource_id']) . '" target="_blank">' . $this->get_second_dycontent_count($row['resource_id']) . '</a>';
            }
            $data['rows'][] = array(
                'id' => $row['resource_id'],
                'cell' => $row
            );
        }


        return $data;
    }

    public function iframe_find_all($page, $qtype, $query, $rp, $sortname, $sortorder) {
        if ($sortname == 'title_play') {
            $sortname = 'title';
        }
        $publish_options = $this->CI->ddoption_model->get_publish_options();
        $privacy_options = $this->CI->ddoption_model->get_privacy_options();
        if ($qtype == 'custom') {
            parse_str($query, $query);
        }
        $user_data = $this->auth->get_user_data();
        $uid = $this->auth->uid();
        $owner_full_name = $user_data['full_name'];
        if (isset($query['owner_full_name'])) {
            if ($query['owner_full_name'] != '') {
                $this->db->where('full_name', $query['owner_full_name']);
                $this->db->where('dycontent_count >', 0);
                $this->db->limit(1);
                $q = $this->db->get('u_user_detail');
                if ($q->num_rows() > 0) {
                    $uid = $q->row()->uid;
                    $owner_full_name = $q->row()->full_name;
                }
            }
        }
        $data = array(
            'rows' => array(),
            'page' => $page,
            'total' => 0,
            'message' => $uid
        );
        //make offset
        $offset = (($page - 1) * $rp);
        // Start Sql Query State for count row
        $this->find_all_where('r_resource', $qtype, $query, $uid);
        // END Sql Query State
        $total = $this->db->count_all_results();
        // Start Sql Query State
        $this->db->select('r_resource.*');
        $this->db->select('r_resource_dycontent.num_questions,r_resource_dycontent.content_type_id');
        $this->find_all_where('r_resource', $qtype, $query, $uid);
        // END Sql Query State
        $this->db->limit($rp, $offset);
        $this->db->order_by($sortname, $sortorder);
        $result = $this->db->get();
        $data['total'] = $total;
        $content_type = $this->get_content_type_array();
        foreach ($result->result_array() as $row) {
            $row['checkbox'] = '<input type = "checkbox" name = "cb_resource_id[]" value = "' . $row['resource_id'] . '" />';
            $row['count_video_join'] = $this->count_video_join($row['resource_id']);
            $row['action'] = '';
//            $row['action'] = '<a href="' . site_url('v/' . $row['resource_id']) . '" target="_blank">เปิดเอกสาร</a>';
            $row['publish'] = $publish_options[$row['publish']];
            $row['privacy'] = $privacy_options[$row['privacy']];
            $row['owner_full_name'] = $owner_full_name;
            $tmp_content_type_id = array_unique(explode(',', $row['content_type_id']));

            if (count($tmp_content_type_id) == 1) {
                $row['content_type'] = $content_type[$row['content_type_id']];
            } else {

                $tmp_ct_name = array();
                foreach ($tmp_content_type_id as $v) {
                    $tmp_ct_name[] = $content_type[$v['content_type_id']];
                }
                $row['content_type'] = implode(", ", $tmp_ct_name);
            }

            $row['title_play'] = '<a href="' . site_url('v/' . $row['resource_id']) . '" target="_blank">▶</a>' . $row['title'];
            $data['rows'][] = array(
                'id' => $row['resource_id'],
                'cell' => $row
            );
        }


        return $data;
    }

    private function find_all_where($table_name, $qtype, $query, $uid = '') {
        if ($uid == '') {
            $this->db->where('r_resource.uid_owner', $this->auth->uid());
        } elseif ($uid == $this->auth->uid()) {
            $this->db->where('r_resource.uid_owner', $uid);
        } else {
            $this->db->where('privacy', 1);
            $this->db->where('r_resource.uid_owner', $uid);
        }
        $this->db->where('resource_type_id', $this->resource_type_id);

        $this->db->from('r_resource_dycontent');
        $this->db->where('r_resource_dycontent.resource_id=r_resource.resource_id', NULL, FALSE);
        if (isset($query['resource_level'])) {
            if ($query['resource_level'] == 1) {
                $this->db->where('r_resource.resource_id_parent', 0);
            } else {
                $this->db->where('resource_id_parent >', 0);
            }
        } else {
            $this->db->where('r_resource.resource_id_parent', 0);
        }
        switch ($qtype) {
            case 'custom':
                foreach ($query as $k => $v) {
                    if ($v != '') {
                        switch ($k) {
                            case 'resource_id':
                                $this->db->like('r_resource.' . $k, $v, 'after');
                                break;
                            case 'title':
                                $this->db->like('r_resource.' . $k, $v);
                                break;
                            case 'desc':
                                $this->db->like('r_resource.' . $k, $v);
                                break;
                            case 'subject_title':case 'chapter_title':
                                $this->db->like('r_resource.' . $k, $v);
                                break;

                            case 'tags':
                                $this->db->like('r_resource.' . $k, $v);
                                break;
                            case 'content_type_id':
                                if ($v != '') {
                                    $this->db->where($k, $v);
                                }
                                break;
                            case 'owner_full_name':

                                break;
                            case 'resource_level':

                                break;
                            default:
                                $this->db->where($k, $v);
                                break;
                        }
                    }
                }
                break;
            default:
                if ($query != '') {
                    $this->db->where($qtype, $query);
                }
                break;
        }
        $this->db->from($table_name);
    }

    function get_dycontent_data($resource_id) {
        $content_type = $this->get_content_type_array();
        $render_type = $this->get_render_type_array();
        $this->db->select('data');
        $this->db->select('content_type_id');
        $this->db->select('num_questions');
        $this->db->select('render_type_id');
        //$this->db->select('resource_id_parent');
        $this->db->where('resource_id', $resource_id);
        $this->db->from('r_resource_dycontent');
        $q1 = $this->db->get();
        if ($q1->num_rows() > 0) {
            $row = $q1->row_array();
            $tmp_content_type_id = array_unique(explode(',', $row['content_type_id']));

            if (count($tmp_content_type_id) == 1) {
                $row['content_type'] = $content_type[$row['content_type_id']];
            } else {

                $tmp_ct_name = array();
                foreach ($tmp_content_type_id as $v) {
                    $tmp_ct_name[] = $content_type[$v['content_type_id']];
                }
                $row['content_type'] = implode(", ", $tmp_ct_name);
            }


            $row['render_type'] = (isset($render_type[$row['render_type_id']])) ? $render_type[$row['render_type_id']] : 0;
            $row['data'] = json_decode($row['data'], TRUE);
            return $row;
        } else {
            return FALSE;
        }
    }

    function get_second_dycontent_count($resource_id) {
        $this->db->where('resource_id_parent', $resource_id);
        $this->db->where('resource_type_id', $this->resource_type_id);
        return $this->db->count_all_results('r_resource');
    }

    /**
     * ตั้งค่าข้อมูลส่วนหัว 
     * @param type $h ข้อมูลส่วนหัว เช่นเกริ่นนำ เพื่อให้เข้าดู media ไม่มีก็ได้
     */
    function set_content_header($content_header) {
        $this->content_header = $content_header;
    }

    /**
     * ตั่งค่าข้อมูล เอกสารที่จะนำเสนอ เช่น เสียง สิดีโอ เอกสารให้ download หรือ อื่นๆ เท่าที่จะหาได้ จะแสดงหลังจาก h 
     * สามารถมีได้หลายตัว
     * @param type $resource_id
     */
    function set_media($resource_id_media) {
        $this->resource_id_media[] = $resource_id_media;
    }

    function set_resource_type_id($resource_type_id) {
        $this->resource_type_id = $resource_type_id;
    }

    function set_content_type_id($content_type_id) {
        $this->content_type_id = $content_type_id;
    }

    /**
     * 
     * @param type $question
     * @param type $choices
     * @param type $true_choice
     * @param type $solve
     */
    function add_question($question, $choices, $true_answers, $solve_answer, $answer_sort, $content_type_id) {
        $tmp_array = array(
            'question' => $question,
            'true_answers' => $true_answers,
            'solve_answer' => $solve_answer,
            'content_type_id' => $content_type_id
        );
        if ($choices) {
            $tmp_array['choices'] = $choices;
        }
        if ($answer_sort) {
            $tmp_array['answer_sort'] = $answer_sort;
        }
        $this->content_questions[] = $tmp_array;
    }

    //SAVE
    function save($data) {
        $subject_title = $this->get_subject_title($data['subj_id']);
        $chapter_title = $this->get_chapter_title($data['chapter_id']);
        $this->content_questions = array();
        if (isset($data['content_questions'])) {
            if (count($data['content_questions']) > 1) {
                $this->set_content_header($data['content_header']);
            }
            $content_type_id = array();
            foreach ($data['content_questions'] as $question) {
                if (!isset($question['choices'])) {
                    $question['choices'] = FALSE;
                }
                if (!isset($question['answer_sort'])) {
                    $question['answer_sort'] = FALSE;
                }
                $this->add_question($question['question'], $question['choices'], $question['true_answers'], $question['solve_answer'], $question['answer_sort'], $question['content_type_id']);
                $content_type_id[] = $question['content_type_id'];
            }
            $this->set_content_type_id(implode(',', array_unique($content_type_id)));
        } else {
            $this->set_content_header($data['content_header']);
            $this->set_content_type_id(1);
        }

        $this->db->trans_start();
        // set main
        if ($data['resource_id'] == '') {
            $set = array(
                'title' => $data['title'],
                'create_time' => $this->time,
                'update_time' => $this->time,
                'uid_owner' => $this->auth->uid(),
                'resource_type_id' => $this->resource_type_id,
                'resource_id_parent' => $data['resource_id_parent'],
                'desc' => $data['desc'],
                'tags' => encode_tags($data['tags']),
                'publish' => $data['publish'],
                'privacy' => $data['privacy'],
                //new field
                'degree_id' => $data['degree_id'],
                'la_id' => $data['la_id'],
                'subj_id' => $data['subj_id'],
                'subject_title' => $subject_title,
                'chapter_id' => $data['chapter_id'],
                'chapter_title' => $chapter_title,
                'sub_chapter_title' => $data['sub_chapter_title']
            );
            $this->db->set($set);
            $this->db->insert('r_resource');
            $new_resource_id = $this->db->insert_id();
        } else {
            $set = array(
                'title' => $data['title'],
                'desc' => $data['desc'],
                'update_time' => $this->time,
                'tags' => encode_tags($data['tags']),
                'publish' => $data['publish'],
                'privacy' => $data['privacy'],
                //new field
                'degree_id' => $data['degree_id'],
                'la_id' => $data['la_id'],
                'subj_id' => $data['subj_id'],
                'subject_title' => $subject_title,
                'chapter_id' => $data['chapter_id'],
                'chapter_title' => $chapter_title,
                'sub_chapter_title' => $data['sub_chapter_title']
            );
            $this->db->set($set);
            $this->db->where('resource_id', $data['resource_id']);
            $this->db->update('r_resource');
        }
// set detail
        $this->db->set('data', $this->resource_codec->dycontent_encode(array('content_header' => $this->content_header, 'content_questions' => $this->content_questions)));
        $this->db->set('render_type_id', $data['render_type_id']);
        $this->db->set('num_questions', count($this->content_questions));
        $this->db->set('content_type_id', $this->content_type_id);
        if ($data['resource_id'] == '') {
            $this->db->set('uid_owner', $this->auth->uid());
//            $this->db->set('resource_id_parent', $data['resource_id_parent']);
            $this->db->set('resource_id', $new_resource_id);
            $this->db->insert('r_resource_dycontent');
        } else {
            $this->db->where('resource_id', $data['resource_id']);
            $this->db->update('r_resource_dycontent');
        }
        $this->db->trans_complete();
        return TRUE;
    }

    function get_subject_title($subj_id) {
        $this->db->select('title');
        $this->db->where('subj_id', $subj_id);
        $q = $this->db->get('f_subject');
        if ($q->num_rows() > 0) {
            return $q->row()->title;
        } else {
            return '';
        }
    }

    function get_chapter_title($chapter_id) {
        $this->db->select('chapter_title');
        $this->db->where('chapter_id', $chapter_id);
        $q = $this->db->get('f_chapter');
        if ($q->num_rows() > 0) {
            return $q->row()->chapter_title;
        } else {
            return '';
        }
    }

    function delete($resource_id) {
        if (is_array($resource_id)) {
            $this->db->where_in('resource_id', $resource_id);
        } else {
            if ($resource_id == '') {
                return FALSE;
            }
            $this->db->where('resource_id', $resource_id);
        }
        $this->db->select('resource_id');
        $this->db->where('uid_owner', $this->auth->uid());
        $q = $this->db->get('r_resource_dycontent');

        if ($q->num_rows() > 0) {
            $this->db->trans_start();
            foreach ($q->result_array() as $row) {
                $this->db->where('resource_id', $row['resource_id']);
                $this->db->where('uid_owner', $this->auth->uid());
                $this->db->delete('r_resource');

                $this->db->where('resource_id', $row['resource_id']);
                $this->db->where('uid_owner', $this->auth->uid());
                $this->db->delete('r_resource_dycontent');

                $this->db->where('resource_id', $row['resource_id']);
                $this->db->delete('r_resource_video_join');
            }
            $this->db->trans_complete();
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * ลบข้อมูลโจทย์เทียบ
     * @param type $resource_id
     * @return boolean
     */
    function second_delete($resource_id) {

//echo $resource_id;
//exit();
        $this->db->where('resource_id', $resource_id);
        $q = $this->db->get('r_resource');
        $row = $q->row_array();

        if (!is_array($resource_id)) {
            $resource_id = array($resource_id);
        }
        $this->db->trans_start();
        $this->db->where_in('resource_id', $resource_id);
        $this->db->where('uid_owner', $this->auth->uid());
        $this->db->delete('r_resource');

        $this->db->where_in('resource_id', $resource_id);
        $this->db->delete('r_resource_dycontent');
        $this->db->trans_complete();
        $result = array(
            'success' => TRUE,
            'resource_id_parent' => $row['resource_id_parent']
        );
        return $result;
    }

    function publish($resource_id, $publish) {
        $this->db->set('publish', $publish);
        if (is_array($resource_id)) {
            $this->db->where_in('resource_id', $resource_id);
        } else {
            $this->db->where('resource_id', $resource_id);
        }
        $this->db->where('uid_owner', $this->auth->uid());
        $this->db->update('r_resource');
        return TRUE;
    }

    function privacy($resource_id, $publish) {
        $this->db->set('privacy', $publish);
        if (is_array($resource_id)) {
            $this->db->where_in('resource_id', $resource_id);
        } else {
            $this->db->where('resource_id', $resource_id);
        }
        $this->db->where('uid_owner', $this->auth->uid());
        $this->db->update('r_resource');
        return TRUE;
    }

    function init_resource($resource_id = '') {
        $resource_data = array();
        if ($resource_id == '') {
            $data = $this->db->field_data('r_resource');
            foreach ($data as $field) {
                $resource_data[$field->name] = $field->default;
            }
            $data = $this->db->field_data('r_resource_dycontent');
            foreach ($data as $field) {
                $resource_data[$field->name] = $field->default;
            }
            //$resource_data['data'] = array('content_header' => '', 'content_questions' => '');
            $resource_data['data'] = $this->resource_codec->dycontent_decode();
            $this->resource_data = $resource_data;
        } else {
            $this->db->where('resource_id', $resource_id);
            $q1 = $this->db->get('r_resource');
            if ($q1->num_rows() > 0) {
                $row1 = $q1->row_array();
                $this->db->select('data');
//                $this->db->select('resource_id_parent');
                $this->db->select('render_type_id');
                $this->db->select('content_type_id');
                $this->db->select('num_questions');
                $this->db->where('resource_id', $resource_id);
                $q2 = $this->db->get('r_resource_dycontent');
                $row2 = $q2->row_array();
                $row2['data'] = $this->resource_codec->dycontent_decode($row2['data']);
                $this->resource_data = array_merge($row1, $row2);
            } else {
                return FALSE;
            }
        }
        return TRUE;
    }

    function get_content_type_id() {
        return $this->resource_data['content_type_id'];
    }

    function get_render_type_id() {
        return $this->resource_data['render_type_id'];
    }

    function get_resource_data() {
        return $this->resource_data;
    }

    function get_all() {
        return $this->content_questions;
    }

    function get_content_header() {
        return $this->content_questions['data']['content_header'];
    }

    function get_content_questions() {
        return $this->content_questions['data']['content_questions'];
    }

    // second dycontent ==============================================================

    public function second_find_all($page, $qtype, $query, $rp, $sortname, $sortorder, $resource_id_parent) {
        $publish_options = $this->CI->ddoption_model->get_publish_options();
        $privacy_options = $this->CI->ddoption_model->get_privacy_options();
        if ($qtype == 'custom') {
            parse_str($query, $query);
        }
        $data = array(
            'rows' => array(),
            'page' => $page,
            'total' => 0
        );
        //make offset
        $offset = (($page - 1) * $rp);
        // Start Sql Query State for count row
        $this->second_find_all_where('r_resource', $qtype, $query, $resource_id_parent);
        // END Sql Query State
        $total = $this->db->count_all_results();
        // Start Sql Query State
        $this->db->select('r_resource.*');
        $this->second_find_all_where('r_resource', $qtype, $query, $resource_id_parent);
        // END Sql Query State
        $this->db->limit($rp, $offset);
        $this->db->order_by($sortname, $sortorder);
        $result = $this->db->get();
        $data['total'] = $total;

        foreach ($result->result_array() as $row) {
            $row['checkbox'] = '<input type = "checkbox" name = "cb_resource_id[]" value = "' . $row['resource_id'] . '" />';
            $row['action'] = '<a href="' . site_url('resource/dycontent/second_edit/' . $row['resource_id']) . '">แก้ไข</a>';

            $row['action'] .= '<a href="' . site_url('resource/dycontent/second_delete/' . $row['resource_id']) . '">ลบ</a>';
            $row['action'] .= '<a href="' . site_url('v/' . $row['resource_id']) . '" target="_blank">เปิดเอกสาร</a>';
            $row['publish'] = $publish_options[$row['publish']];
            $row['privacy'] = $privacy_options[$row['privacy']];
            $row['title_play'] = '<a href="' . site_url('v/' . $row['resource_id']) . '" target="_blank">▶</a>' . '<span title="' . $row['desc'] . '">' . $row['title'] . '</span>';
            $row = array_merge($row, $this->get_dycontent_data($row['resource_id']));
            $data['rows'][] = array(
                'id' => $row['resource_id'],
                'cell' => $row
            );
        }


        return $data;
    }

    private function second_find_all_where($table_name, $qtype, $query, $resource_id_parent) {
        $this->db->where('resource_type_id', $this->resource_type_id);
        $this->db->where('resource_id_parent', $resource_id_parent);
        $this->db->where('r_resource.uid_owner', $this->auth->uid());
        $this->db->from('r_resource_dycontent');
        $this->db->where('r_resource_dycontent.resource_id=r_resource.resource_id', NULL, FALSE);
        switch ($qtype) {
            case 'custom':
                foreach ($query as $k => $v) {
                    if ($v != '') {
                        switch ($k) {
                            case 'resource_id':
                                $this->db->like($k, $v, 'after');
                                break;
                            case 'title':
                                $this->db->like($k, $v);
                                break;
                            case 'desc':
                                $this->db->like($k, $v);
                                break;
                            case 'tags':
                                $this->db->like($k, $v);
                                break;
                            case 'content_type_id':
                                if ($v != '') {
                                    $this->db->where($k, $v);
                                }
                                break;
                            default:
                                $this->db->where($k, $v);
                                break;
                        }
                    }
                }
                break;
            default:
                if ($query != '') {
                    $this->db->where($qtype, $query);
                }
                break;
        }
        $this->db->from($table_name);
    }

    function count_video_join($resource_id) {
        $this->db->where('resource_id', $resource_id);
        return $this->db->count_all_results('r_resource_video_join');
    }

}
