<?php

class bookplayer_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    function search($search_text) {
        $txt = trim($search_text);
//        $txt_array = array_filter(explode(" ", $txt));
        if ($txt == '') {
            $result_data = array(
                'found' => FALSE,
                'found_count' => 0,
                'message' => 'ไม่สามารถค้นพบ',
                'resource_id' => ''
            );
            return $result_data;
        }
        $this->db->like('resource_code', $txt);
        $this->db->or_like('title', $txt);
        $this->db->or_like('desc', $txt);
//        if (count($txt_array) > 0) {
//            foreach ($txt_array as $txt) {
//                $this->db->or_like('resource_code', $txt);
//                $this->db->or_like('title', $txt);
//                $this->db->or_like('desc', $txt);
//            }
//        }
        $q = $this->db->get('r_resource');
        $result_data = array();
        if ($q->num_rows() > 0) {
            $r = $q->row_array();
            $result_data = array(
                'found' => TRUE,
                'found_count' => 1,
                'message' => 'ค้นหาพบ',
                'resource_id' => $r['resource_id']
            );
        } else {
//            $this->db->like('title', $txt);
//            $this->db->or_like('desc', $txt);
//            $q = $this->db->get('r_resource');
//
//            if ($q->num_rows() == 1) {
//                $r = $q->row_array();
//                $result_data = array(
//                    'found' => TRUE,
//                    'found_count' => 1,
//                    'message' => 'ค้นหาพบ',
//                    'resource_id' => $r['resource_id']
//                );
//            } else if ($q->num_rows() > 1) {
//                $resource_id = array();
//                foreach ($q->result_array() as $r) {
//                    $resource_id[] = $r['resource_id'];
//                    print_r($r);
//                }
//                print_r($r);
//                $result_data = array(
//                    'found' => TRUE,
//                    'found_count' => $q->num_rows(),
//                    'message' => 'ค้นหาพบวิดีโอ',
//                    'resource_id' => $resource_id[0]
//                );
//            } else {
////                
//            }
            $result_data = array(
                'found' => FALSE,
                'found_count' => 0,
                'message' => 'ไม่สามารถค้นพบ',
                'resource_id' => ''
            );
        }
        return $result_data;
    }

}