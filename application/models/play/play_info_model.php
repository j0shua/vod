<?php

class play_info_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    function get_user_view_video_data() {
        $this->load->helper('time');
        $result = array();
        $this->db->where('is_end', 0);
        $q = $this->db->get('b_view_log');
        if ($q->num_rows() > 0) {
            foreach ($q->result_array() as $v) {
                $this->db->where('uid', $v['uid_view']);
                $q2 = $this->db->get('u_user_detail');
                $v['first_thai_time'] = thdate('d-M-Y h:i', $v['first_time']);
                $v['last_thai_time'] = thdate('d-M-Y h:i', $v['last_time']);
                $v['user_view_details'] = $q2->row_array();
                $v['resource_details'] = $this->get_resource_data($v['resource_id']);

                $result[] = $v;
            }
        }
        return $result;
    }

    function get_resource_data($resource_id) {
        $this->db->where('resource_id', $resource_id);
        $this->db->from('r_resource');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row_array();
        }return FALSE;
    }

}