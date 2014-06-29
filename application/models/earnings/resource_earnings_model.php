<?php

class resource_earnings_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    function get_earnings_data($uid) {
        $result = array();
        //video owner
        $this->db->where('uid_owner', $uid);
        $q1 = $this->db->get('b_view_log');
        $result['video_owner'] = $q->result_array();

        //video ref
        return $result;
    }

}
