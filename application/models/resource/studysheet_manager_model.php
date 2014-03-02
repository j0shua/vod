<?php

/**
 * Description of studysheet_manager_model
 *
 * @author lojorider
 */
class studysheet_manager_model extends CI_Model {

    function __construct() {
        $this->time = time();
        $this->load->helper('tag');
    }

    public function find_all($page, $qtype, $query, $rp, $sortname, $sortorder) {
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
        $this->find_all_where('r_resource_studysheet', $qtype, $query);
        // END Sql Query State
        $total = $this->db->count_all_results();
        // Start Sql Query State
        $this->db->select('r_resource_studysheet.*');
        $this->find_all_where('r_resource_studysheet', $qtype, $query);
        // END Sql Query State
        $this->db->limit($rp, $offset);
        $this->db->order_by($sortname, $sortorder);
        $result = $this->db->get();
        $data['total'] = $total;

        foreach ($result->result_array() as $row) {
            $row['checkbox'] = '<input type = "checkbox" name = "cb_resource_id[]" value = "' . $row['resource_id'] . '" />';
            $row['action'] = '<a href="' . site_url('resource/teachset_manager/edit/' . $row['resource_id']) . '">แก้ไข</a>';
            $row = array_merge($row, $this->get_teachset_manager_data($row['resource_id']));
            $data['rows'][] = array(
                'id' => $row['resource_id'],
                'cell' => $row
            );
        }


        return $data;
    }

    function get_resource_data($resource_id = '') {
        $row = array();
        if ($resource_id == '') {
//ตารางแม่
            foreach ($this->db->field_data('r_resource') as $v) {
                $row[$v->name] = $v->default;
            }
//ตารางลูก
            foreach ($this->db->field_data('r_resource_studysheet') as $v) {
                $row[$v->name] = $v->default;
            }
        } else {
//ตารางแม่            
            $this->db->where('resource_id', $resource_id);
            $query1 = $this->db->get('r_resource');
            foreach ($query1->row_array() as $k => $v) {
                $row[$k] = $v;
            }
//ตารางลูก            
            $this->db->where('resource_id', $resource_id);
            $query2 = $this->db->get('r_resource_studysheet');
            foreach ($query2->row_array() as $k => $v) {
                $row[$k] = $v;
            }
        }

        return $row;
    }

    private function get_teachset_manager_data($resource_id) {
        $this->db->select('content_type_id');
        $this->db->select('render_type_id');
        $this->db->where('resource_id', $resource_id);
        $this->db->from('r_resource_studysheet_teachset_manager');
        $q1 = $this->db->get();
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


        return $row;
    }

    private function find_all_where($table_name, $qtype, $query) {
        $this->db->where('study_type_id', $this->study_type_id);
        $this->db->where('uid_owner', $this->auth->uid());
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
                            case 'category_id':
                                $this->db->like($k, $v);
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

    /**
     * ตั้งค่าข้อมูลส่วนหัว 
     * @param type $h ข้อมูลส่วนหัว เช่นเกริ่นนำ เพื่อให้เข้าดู media ไม่มีก็ได้
     */
    function set_content_header($content_header) {
        $this->content_header = $content_header;
    }

    //SAVE
    function save($data, $resource_id = '') {
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
            $this->set_content_type_id(1);
        }

        $this->db->trans_start();
        // set main
        if ($resource_id == '') {
            $set = array(
                'title' => $data['title'],
                'create_time' => $this->time,
                'uid_owner' => $this->auth->uid(),
                'study_type_id' => $this->study_type_id,
                'desc' => $data['desc'],
                'tags' => encode_tags($data['tags']),
                'publish' => $data['publish'],
                'privacy' => $data['privacy'],
                'category_id' => $data['category_id'],
            );
            $this->db->set($set);
            $this->db->insert('r_resource_studysheet');
            $new_resource_id = $this->db->insert_id();
        } else {
            $set = array(
                'title' => $data['title'],
                'desc' => $data['desc'],
                'tags' => encode_tags($data['tags']),
                'publish' => $data['publish'],
                'privacy' => $data['privacy'],
                'category_id' => $data['category_id']
            );
            $this->db->set($set);
            $this->db->where('resource_id', $resource_id);
            $this->db->update('r_resource_studysheet');
        }
// set detail
        $this->db->set('data', $this->encode_data(array('content_header' => $this->content_header, 'content_questions' => $this->content_questions)));
        $this->db->set('render_type_id', $data['render_type_id']);
        $this->db->set('num_questions', count($this->content_questions));
        $this->db->set('content_type_id', $this->content_type_id);
        if ($resource_id == '') {
            $this->db->set('resource_id', $new_resource_id);
            $this->db->insert('r_resource_studysheet_teachset_manager');
        } else {
            $this->db->where('resource_id', $resource_id);
            $this->db->update('r_resource_studysheet_teachset_manager');
        }
        $this->db->trans_complete();
        return TRUE;
    }

    function encode_data($data) {

        return json_encode($data);
    }

    function decode_data($data) {

        return json_decode($data, TRUE);
    }

}

