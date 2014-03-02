<?php

/**
 * Description of post_tutor_model
 *
 * @author lojorider
 */
class post_tutor_model extends CI_Model {

    var $video_dir = '';
    var $resource_type_id = 3;
    var $time = 0;

    public function __construct() {
        parent::__construct();

        $this->load->helper('tag');
        $this->time = time();
    }

    public function find_all($page, $qtype, $query, $rp, $sortname, $sortorder) {
        if ($qtype == 'custom') {
            parse_str($query, $query);
        }
//initial data        
        $data = array(
            'rows' => array(),
            'page' => $page,
            'total' => 0
        );
//make offset
        $offset = (($page - 1) * $rp);
// Start Sql Query State for count row
        $this->find_all_where('c_post_tutor', $qtype, $query);
// END Sql Query State
        $total = $this->db->count_all_results();
// Start Sql Query State
        $this->db->select('c_post_tutor.*');
        $this->find_all_where('c_post_tutor', $qtype, $query);
// END Sql Query State
        $this->db->limit($rp, $offset);
        $this->db->order_by($sortname, $sortorder);
        $result = $this->db->get();
        $data['total'] = $total;
        foreach ($result->result_array() as $row) {
            $row['action'] = '<a href="' . site_url('community/post_tutor/edit/' . $row['p_id']) . '">แก้ไข</a>';
            $row['action'] .= '<a href="' . site_url('community/post_tutor/delete/' . $row['p_id']) . '">ลบ</a>';

            $data['rows'][] = array(
                'id' => $row['p_id'],
                'cell' => $row
            );
        }
        return $data;
    }

    private function find_all_where($table_name, $qtype, $query) {
        switch ($qtype) {
            case 'custom':
                foreach ($query as $k => $v) {
                    if ($v != '') {
                        switch ($k) {
                            case 'p_id':
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

    function get_form_data($p_id = '') {
        if ($p_id == '') {
            $data = $this->db->field_data('c_post_tutor');
            $row = array();
            foreach ($data as $v) {
                $row[$v->name] = $v->default;
            }
        } else {
            $this->db->where('p_id', $p_id);
            $query = $this->db->get('c_post_tutor');
            $row = $query->row_array();
        }

        return $row;
    }

    function save($data) {
        if ($data['p_id'] == '') {
            $set = array(
                'title' => $data['title'],
                'create_time' => $this->time,
                'uid_post' => $this->auth->uid(),
                'desc' => $data['desc'],
                'tags' => encode_tags($data['tags']),
                'category_id' => $data['category_id'],
            );
            $this->db->set($set);
            $this->db->insert('c_post_tutor');
            return TRUE;
        } else {
            $this->db->where('p_id', $p_id);
            $query = $this->db->get('c_post_tutor');
            if ($query->num_rows() > 0) {
                $this->db->trans_start();
                $set = array(
                    'title' => $data['title'],
                    'desc' => $data['desc'],
                    'tags' => encode_tags($data['tags']),
                    'category_id' => $data['category_id']
                );
                $this->db->set($set);
                $this->db->where('p_id', $p_id);
                $this->db->update('c_post_tutor');

                return TRUE;
            }


            return FALSE;
        }
    }

    function delete($p_id) {
        $this->db->where('p_id', $p_id);
        $result = $this->db->get('c_post_tutor');
        if ($result->num_rows() > 0) {


            $this->db->where('p_id', $p_id);
            $this->db->delete('c_post_tutor');


            return TRUE;
        }
        return FALSE;
    }

    function is_owner($p_id) {
        $this->db->where('p_id', $p_id);
        $this->db->where('uid_post', $this->auth->uid());
        if ($this->db->count_all_results('c_post_tutor') > 0) {
            return TRUE;
        }
        return FALSE;
    }

//==============================================================================
// Extra Section
//==============================================================================
}