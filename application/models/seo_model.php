<?php

class seo_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    function get_all_video_data() {
        $this->db->select('r_resource.resource_id,r_resource.title,r_resource.desc,r_resource.resource_type_id');
        $this->db->select('r_resource_video.duration');
        $this->db->where('r_resource.resource_type_id', 1);
        $this->db->from('r_resource');
        $this->db->where('r_resource.resource_id=r_resource_video.resource_id', NULL, FALSE);
        $this->db->from('r_resource_video');
        //$this->db->limit(10);
        $q = $this->db->get();
        $result = array();
        if ($q->num_rows() > 0) {
            foreach ($q->result_array() as $v) {
                $result[] = array(
                    'url' => site_url('v/' . $v['resource_id']),
                    'thumbnail_url' => site_url('resource/ntimg/video_thumbnail/' . $v['resource_id']),
                    'title' => str_replace(array('&'), array(''), $v['title']),
                    'description' => str_replace(array('&'), array(''), $v['title']),
                    'duration' => $v['duration'],
                );
            }
            return $result;
        }
        return array();
    }

}