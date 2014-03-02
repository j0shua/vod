<?php

class play_sitevideo_model extends CI_Model {

    var $full_video_dir = '';
    var $time;
    var $video_data;

    public function __construct() {
        parent::__construct();
        $this->full_video_dir = $this->config->item('full_video_dir');
        $this->time = time();
    }

    function init_resource($resource_id) {
        $this->db->select('r_resource.resource_id');
        $this->db->select('r_resource.title');
        $this->db->select('r_resource.desc');
        $this->db->select('r_resource.unit_price');
        $this->db->select('r_resource.uid_owner');
        $this->db->select('(select r_resource_sitevideo.video_code from r_resource_sitevideo where r_resource.resource_id = r_resource_sitevideo.resource_id) video_code', FALSE);
        $this->db->where('r_resource.resource_id', $resource_id);
        $q1 = $this->db->get('r_resource');
        if ($q1->num_rows() > 0) {
            $this->video_data = $q1->row_array();
        }
        return FALSE;
    }

    function init_video_play() {
        $this->clear_view_log();
        $data['view_log_id'] = $this->add_view_log();
        $data['video_code'] = $this->get_video_path();
        return $data;
    }

    function get_sitevideo_code() {
        return $this->video_data['video_code'];
    }

    function get_video_data() {
        return $this->video_data;
    }

    function get_videosite_id($resource_id) {
        $this->db->where('resource_id', $resource_id);
        $q1 = $this->db->get('r_resource_sitevideo');
        if ($q1->num_rows() > 0) {
            return $q1->row()->videosite_id;
        }
    }

    function is_free() {
        if ($this->video_data['unit_price'] > 0) {
            return FALSE;
        }
        return TRUE;
    }

    function add_view_log() {
        $this->db->set('resource_id', $this->video_data['resource_id']);
        $this->db->set('uid_owner', $this->video_data['uid_owner']);
        $this->db->set('uid_view', $this->auth->uid());
        $this->db->set('view_type', 'money');
        $this->db->set('unit_price', $this->video_data['unit_price']);
        $this->db->set('first_time', $this->time);
        $this->db->set('last_time', $this->time);
        $this->db->insert('b_view_log');
        $view_log_id = $this->db->insert_id();
        return $view_log_id;
    }

    function update_view_log($view_log_id, $to_close = FALSE) {
        $data = array();
        $uid = $this->auth->uid();
        // เรียกข้อมูล view log
        $this->db->where('id', $view_log_id);
        $q2 = $this->db->get('b_view_log');
        $view_log_data = $q2->row_array();
        $unit_price = $view_log_data['unit_price'] / 3600;
        $play_time = $this->time - $view_log_data['last_time'];
        $money = $unit_price * $play_time;

        $data['can_play'] = TRUE;
        if ($money > 0) {
            $user_money = $this->auth->money();
            if ($user_money < $money) {
                $money = $user_money;
                $data['can_play'] = FALSE;
            }
        }

        $this->db->trans_start();
        $this->db->where('uid', $uid);
        $this->db->set('money', "money-$money", FALSE);
        $this->db->set('update_time', $this->time);
        $this->db->update('u_user_credit');
        // update view log
        if ($to_close) {
            $this->db->set('is_end', 1);
        }
        $this->db->where('id', $view_log_id);
        $this->db->set('last_time', $this->time);
        $this->db->set('money', "money+$money", FALSE);
        $this->db->update('b_view_log');

        $this->db->trans_complete();



        $data['money'] = $money;
        $data['unit_price'] = $unit_price;
        $data['play_time'] = $play_time;
        return $data;
    }

    function is_owner($uid = '') {
        if ($this->auth->is_login() && $uid == '') {
            $uid = $this->auth->uid();
        }
        if ($uid == $this->video_data['uid_owner']) {
            return TRUE;
        }
        return FALSE;
    }

    function clear_view_log() {
        $time_out = $this->time - 300;
        $this->db->where('last_time <', $time_out);
        $this->db->set('is_end', 1);
        $this->db->update('b_view_log');
    }

}
