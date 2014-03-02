<?php

/**
 * Description of dycontent_clone_model
 *
 * @author lojorider
 * @property resource_codec $resource_codec 
 * @property dycontent_model $dycontent_model
 *  
 */
class dycontent_clone_model extends CI_Model {

    var $CI;
    var $uid;
    var $resource_type_id = 4; //ประเภทหลัก   
    var $personal_dir;
    var $full_image_dir, $full_image_dir_vod;

    function __construct() {
        parent::__construct();
        $this->CI = & get_instance();
        $this->time = time();
        $this->load->library('resource_codec');
        $this->load->helper('str');
        $this->uid = 2;
        $this->personal_dir = $this->auth->get_personal_dir($this->uid);
        $this->full_image_dir = $this->config->item('full_image_dir');
        $this->full_image_dir_vod = FCPATH . 'vod_image/';
    }

    function get_dycontent_data() {
        //$this->db->limit(1);
        //$this->db->order_by('resource_id', 'random');
        $this->db->where('content_type_id', 2);
        $this->db->where('uid_owner', 2);
        $q_dycontent = $this->db->get('vod_resource_dycontent');

        foreach ($q_dycontent->result_array() as $row_dycontent) {
            $row_dycontent['data'] = $this->resource_codec->dycontent_decode($row_dycontent['data']);
            $tmp_data = array();
            foreach ($row_dycontent['data'] as $k_data => $v_data) {
                $tmp_data[$k_data] = $this->replace_image($v_data);
            }
            $this->db->where('resource_id', $row_dycontent['resource_id']);
            $q_vod_resource = $this->db->get('vod_resource');
            $r_vod_resource = $q_vod_resource->row_array();
            $r_vod_resource['resource_id_vod'] = $r_vod_resource['resource_id'];
            unset($r_vod_resource['resource_id']);
            $r_vod_resource['uid_owner'] = $this->uid;
            $this->db->set($r_vod_resource);
            $this->db->insert('r_resource');
            $insert_id = $this->db->insert_id();

            $row_dycontent['data'] = $this->resource_codec->dycontent_encode($tmp_data);
            $row_dycontent['resource_id'] = $insert_id;
            $row_dycontent['uid_owner'] = $this->uid;
            $this->db->set($row_dycontent);
            $this->db->insert('r_resource_dycontent');
        }
    }

    function replace_image($v_data) {

        if (is_array($v_data)) {
            $tmp_data = array();
            foreach ($v_data as $k => $v) {
                $tmp_data[$k] = $this->replace_image($v);
                //echo $k;
            }
            return $tmp_data;
        } else {
            $pattern = "/includegraphics.*{(.*)}/";
            preg_match_all($pattern, $v_data, $matches);
            $files = array();
            foreach ($matches[1] as $f) {
                $files_search[] = $f;
                $replace_file_path = $this->personal_dir . basename($f);
                $files_replace[] = $replace_file_path;
                $this->db->where('file_path', $f);
                $q_image = $this->db->get('vod_resource_image');
                if ($q_image->num_rows() == 0) {
                    continue;
                }
                $r_image = $q_image->row_array();
                $r_image['file_path'] = $replace_file_path;
//start clone_image
                $this->db->where('resource_id', $r_image['resource_id']);
                $q_resource = $this->db->get('vod_resource');
                $r_resource = $q_resource->row_array();
// start check is clone
                $this->db->where('resource_id_vod', $r_resource['resource_id']);
                if ($this->db->count_all_results('r_resource') > 0) {
                    continue;
                }
                echo $r_resource['resource_id'];
// end check is clone
                $r_resource['resource_id_vod'] = $r_resource['resource_id'];
                unset($r_resource['resource_id']);
                $r_resource['uid_owner'] = $this->uid;
                $this->db->set($r_resource);
                $this->db->insert('r_resource');
                $resource_id_new = $this->db->insert_id();
                $r_image['resource_id'] = $resource_id_new;
                $r_image['uid_owner'] = $this->uid;
                $this->db->set($r_image);
                $this->db->insert('r_resource_image');
//end clone_image
                $v_data = str_replace($files_search, $files_replace, $v_data);
                $input_file = $this->full_image_dir_vod . $f;
                $output_file = $this->full_image_dir . $replace_file_path;
                $this->move_file($input_file, $output_file);
            }
            return $v_data;
        }
    }

    function move_file($input_file, $output_file) {
        if (file_exists($input_file)) {
            //rename($input_file, $output_file);
            copy($input_file, $output_file);
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function test() {
        $this->get_dycontent_data();
    }

}
