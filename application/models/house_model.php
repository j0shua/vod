<?php

class house_model extends CI_Model {

    var $CI, $uid, $tid, $taxonomy_parent, $taxonomy_parent_duration, $sub_taxonomy, $taxonomy_parent_title, $taxonomy_parent_desc;

    public function __construct() {
        parent::__construct();
        $this->CI = & get_instance();
    }

    function valid_uid($uid) { 
        $this->db->where('uid', $uid);
        if ($this->db->count_all_results('u_user') > 0) {

            return TRUE;
        }
        return FALSE;
    }

    function init($uid, $tid = '') {
        $this->uid = $uid;
        $this->tid = $tid;
        $this->make_taxonomy();
        $this->make_sub_taxnomy();
    }

    function get_user($uid = '') {
        if ($uid == '') {
            $this->db->where('uid', $this->uid);
        } else {
            $this->db->where('uid', $uid);
        }
        return $this->db->get('u_user_detail')->row_array();
    }

    function get_taxonomy() {
        return $this->taxonomy_parent;
    }

    function get_tid() {
        return $this->tid;
    }

    function get_sub_taxonomy() {
        return $this->sub_taxonomy;
    }

    function get_taxonomy_parent_title() {
        return $this->taxonomy_parent_title;
    }

    function get_taxonomy_parent_desc() {
        return $this->taxonomy_parent_desc;
    }

    function get_taxonomy_parent_duration() {
        return $this->taxonomy_parent_duration;
    }

    function make_taxonomy() {
        $this->db->where('uid_owner', $this->uid);
        $this->db->where('tid_parent', 0);
        $this->db->where('publish', 1);
        $this->db->order_by('title');
        $q = $this->db->get('r_taxonomy');

        $this->taxonomy_parent = $q->result_array();
        //print_r($this->taxonomy_parent);
    }

    function make_sub_taxnomy() {
        $this->CI->load->model('play/play_resource_model');
        $this->db->where('uid_owner', $this->uid);
        if ($this->tid != '') {
            $this->db->where('tid', $this->tid);
        }
        $this->db->where('tid_parent', 0);
        $this->db->where('publish', 1);
        $this->db->order_by('title');
        $q = $this->db->get('r_taxonomy');
        if ($q->num_rows() > 0) {
            $row = $q->row_array();

            $this->tid = $row['tid'];
            $this->taxonomy_parent_title = $row['title'];
            $this->taxonomy_parent_desc = $row['desc'];
        } else {
            $this->sub_taxonomy = array();
            return FALSE;
        }

        $this->db->where('publish', 1);
        $this->db->where('tid_parent', $this->tid);
        $this->db->order_by('title');
        $q2 = $this->db->get('r_taxonomy');
        $sub_taxonomy = array();
        $sum_duration = 0;
        if ($q2->num_rows() > 0) {

            foreach ($q2->result_array() as $row) {
                if (trim($row['data']) == '') {
                    $row['resource_set'] = array();
                } else {

                    $a_resource_id = explode(',', $row['data']);
                    $resource_set = $this->get_resource($a_resource_id);
                    $row['resource_set'] = $resource_set['resource_set'];
                    $row['sum_duration'] = $resource_set['sum_duration'];
                    $sum_duration += $resource_set['sum_duration'];
                }
                $row['have_join_content'] = ($this->CI->play_resource_model->get_join_content_in_taxonomy($row['tid'])) ? TRUE : FALSE;

                $sub_taxonomy[] = $row;
            }
        }

        $this->taxonomy_parent_duration = $sum_duration;
        $this->sub_taxonomy = $sub_taxonomy;
    }

    function get_resource($a_resource_id) {
        $sum_duration = 0;
        $resource_set = array();

        foreach ($a_resource_id as $resource_id) {
            $resource_data = $this->get_resource_data($resource_id);

            if ($resource_data) {
                $resource_set[] = $resource_data;
                $sum_duration += @$resource_data['duration'];
            }
        }
        $result_set['resource_set'] = $resource_set;
        $result_set['sum_duration'] = $sum_duration;
        return $result_set;
    }

    function get_resource_data($resource_id) {
        $this->db->where('publish', 1);
        $this->db->where('resource_id', $resource_id);
        $q = $this->db->get('r_resource');

        if ($q->num_rows() > 0) {
            $row = $q->row_array();

            switch ($row['resource_type_id']) {
                case 1:
                    $row = array_merge($q->row_array(), $this->get_video_data($q->row()->resource_id));
                    break;
                case 3:
                    $row = array_merge($q->row_array(), $this->get_sitevideo_data($q->row()->resource_id));
                    break;
                case 6:
                    $row = array_merge($q->row_array(), $this->get_video_parent_data($q->row()->resource_id));
                    break;
                default:
                    break;
            }
            if ($row['unit_price'] == -1) {
                $row['unit_price'] = $this->config->item('standard_unit_price');
            }
            $row['resource_id_join'] = $this->get_resource_join($resource_id);
//            $search = array('_', '(', ')', '=', '&');
//            $replace = array('-', '-', '', '-', '-');
//            $temp_title = urldecode(str_replace($search, $replace, $row['title']));
            //$this->load->helper('url');
            //$temp_title = url_title($row['title']);
            $temp_title = '';
            $row['link'] = site_url('v/' . $row['resource_id'] . '/' . $temp_title);
            $row['edit_link'] = '';
            if ($row['uid_owner'] == $this->auth->uid()) {
                switch ($row['resource_type_id']) {
                    case 1:
                        $row['edit_link'] = anchor('resource/video_manager/edit/' . $row['resource_id'], 'แก้ไข', 'target="_blank" class="btn-a-small"');
                        break;
                    case 3:
                        $row['edit_link'] = anchor('resource/sitevideo_manager/edit/' . $row['resource_id'], 'แก้ไข', 'target="_blank" class="btn-a-small"');
                        break;
                    default:
                        break;
                }

                $row['edit_link'] .= anchor('resource/resource_join/video_join/' . $row['resource_id'], 'เชื่อมเอกสาร', 'target="_blank" class="btn-a-small"');
            }

            return $row;
        }
        return FALSE;
    }

    function get_resource_join($resource_id_video) {
        $this->db->select('resource_id');
        $this->db->where('resource_id_video', $resource_id_video);
        $q = $this->db->get('r_resource_video_join');
        if ($q->num_rows() > 0) {
            return $q->row()->resource_id;
        }
        return FALSE;
    }

    public function get_video_data($resource_id) {

        $this->db->select('file_size_org,file_size,duration');
        $this->db->where('resource_id', $resource_id);
        $this->db->from('r_resource_video');
        $q1 = $this->db->get();
        $row = $q1->row_array();

        return $row;
    }

    public function get_video_parent_data($resource_id) {

        $this->db->select('file_size_org,file_size,duration');
        $this->db->where('resource_id', $resource_id);
        $this->db->from('r_resource_video_parent');
        $q1 = $this->db->get();
        $row = $q1->row_array();

        return $row;
    }

    public function get_sitevideo_data($resource_id) {
        $this->db->select('duration');
        $this->db->where('resource_id', $resource_id);
        $this->db->from('r_resource_sitevideo');
        $q1 = $this->db->get();
        return $q1->row_array();
    }

    function get_all_teacher($limit = 5) {
        $this->db->where('rid', 3);
        $this->db->limit($limit);
        $q = $this->db->get('u_user');
        $rows = array();
        foreach ($q->result_array() as $v) {
            $user_detail = $this->auth->get_user_data($v['uid']);
            $rows[] = array_merge($v, $user_detail);
        }
        return $rows;
    }

}
