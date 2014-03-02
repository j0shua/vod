<?php

/**
 * Description of video_manager_model
 *
 * @author lojorider
 */
class playlist_model extends CI_Model {

    var $video_dir = '';

    public function __construct() {
        parent::__construct();
        $this->video_dir = $this->config->item('video_dir');
    }

    public function find_all($page, $qtype, $query, $rp, $sortname, $sortorder) {
        if ($qtype == 'custom') {
            parse_str($query, $query);
        }
        //set now timestamp
        //$time = time();
        //initial data        
        $data = array(
            'rows' => array(),
            'page' => $page,
            'total' => 0
        );
        //make offset
        $offset = (($page - 1) * $rp);
        // Start Sql Query State for count row
        $this->find_all_where('a_resource', $qtype, $query);
        // END Sql Query State
        $total = $this->db->count_all_results();
        // Start Sql Query State
        $this->db->select('a_resource.*');
        $this->find_all_where('a_resource', $qtype, $query);
        // END Sql Query State
        $this->db->limit($rp, $offset);
        $this->db->order_by($sortname, $sortorder);
        $result = $this->db->get();
        $data['total'] = $total;

        foreach ($result->result_array() as $row) {
            $row['action'] = '<a href="' . site_url('resource/video_manager/edit/' . $row['id']) . '">แก้ไข</a>';
            $row['action'] .= '<a href="' . site_url('resource/video_manager/delete/' . $row['id']) . '">ลบ</a>';
            $data['rows'][] = array(
                'id' => $row['id'],
                'cell' => $row
            );
        }

        // set Summary row
        $summary_row = $this->get_list_field('a_resource');
        $summary_row['action'] = '';
        $data['rows'][] = array(
            'id' => '',
            'cell' => $summary_row
        );
        return $data;
    }

    private function find_all_where($table_name, $qtype, $query) {
        $this->db->where('resource_type', 1);
        switch ($qtype) {
            case 'custom':
                foreach ($query as $k => $v) {
                    if ($v != '') {
                        $this->db->where($k, $v);
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

    function save() {
        
    }

    function delete($id) {
        $this->db->where('id', $id);
        $result = $this->db->get('a_resource');
        if ($result->num_rows() > 0) {
            $row = $result->row_array();
            $file_data = $this->decode_video_data($row['data']);
            if (file_exists($this->video_dir . $file_data['path'])) {
                if (@unlink($this->video_dir . $file_data['path'])) {
                    $this->db->where('id', $id);
                    $this->db->delete('a_resource');
                    return TRUE;
                }
                return FALSE;
            }
            return TRUE;
        }
        return FALSE;
    }

    function decode_video_data($str) {
        return unserialize($str);
    }

    function get_user_video_size() {
        
    }

    function get_user_video_size_quota() {
        //สามารถ upload ได้ จำกัด ประมาณ 25 clip
    }

    private function get_list_field($table_name) {
        $data = array();
        foreach ($this->db->list_fields($table_name) as $field) {
            $data[$field] = '';
        }
        return $data;
    }

}