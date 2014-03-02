<?php

class resource_video_model extends CI_Model {

    var $resource_type_id = 1;

    public function __construct() {
        parent::__construct();
    }

    function get_resource_data() {
        $this->resource_data_where();
        $this->db->where('resource_type_id', $this->resource_type_id);
        $q1 = $this->db->get('r_resource');
        return $q1->result_array();
    }

    private function resource_data_where() {
        
    }

}