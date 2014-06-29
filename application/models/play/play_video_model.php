<?php

class play_video_model extends CI_Model {

    var $full_video_dir = '';
    var $time;
    var $video_data, $make_money;

    public function __construct() {
        parent::__construct();
        $this->full_video_dir = $this->config->item('full_video_dir');
        $this->time = time();
        $this->make_money = $this->config->item('make_money');
    }

    function init_resource($resource_id) {
        $this->db->select('r_resource.resource_id');
        $this->db->select('r_resource.title');
        $this->db->select('r_resource.unit_price');
        $this->db->select('r_resource.uid_owner');
        $this->db->select('r_resource.resource_type_id');
        $this->db->select('(select r_resource_video.file_path from r_resource_video where r_resource.resource_id = r_resource_video.resource_id) file_path', FALSE);
        $this->db->where('r_resource.resource_id', $resource_id);
        $q1 = $this->db->get('r_resource');
        if ($q1->num_rows() > 0) {
            $row = $q1->row_array();
            if ($row['unit_price'] == -1) {
                $row['unit_price'] = $this->config->item('standard_unit_price');
            }
            $this->video_data = $row;
        }
        return FALSE;
    }

    function init_video_play($is_owner = FALSE, $referer_url = FALSE) {
        $this->clear_view_log();
        $data['view_log_id'] = $this->add_view_log($is_owner, $referer_url);
        $data['video_path'] = $this->get_video_path();
        return $data;
    }

    function get_video_path() {
        $file_path = $this->video_data['file_path'];
        $pathinfo = pathinfo($this->full_video_dir . $file_path);
        switch ($pathinfo['extension']) {
            case 'mp4':
                return 'mp4:' . $file_path;
                break;
            case 'flv':
                return $file_path;
                break;
            default:
                return $file_path;
                break;
        }
    }

    function get_jw_video_path() {
        $file_path = $this->video_data['file_path'];
        $pathinfo = pathinfo($this->full_video_dir . $file_path);
        switch ($pathinfo['extension']) {
            case 'mp4':
                return 'mp4:' . $file_path;
                break;
            case 'flv':
                return 'flv:' . $file_path;
                break;
            default:
                return $file_path;
                break;
        }
    }

    function get_resource_type_id() {
        return $this->video_data['resource_type_id'];
    }

    function add_view_log($is_owner, $referer_url = FALSE) {

        $view_type = 'money';
        if ($this->auth->have_money_bonus()) {
            $view_type = 'money_bonus';
        }
        $uid_referer = 0;
        if ($referer_url) {
            $uid_referer = $this->find_uid_by_referer_url($referer_url);
        }

        $this->db->set('uid_referer', $uid_referer);
        $this->db->set('referer_url', $referer_url);
        $this->db->set('resource_id', $this->video_data['resource_id']);
        $this->db->set('uid_owner', $this->video_data['uid_owner']);
        $this->db->set('uid_view', $this->auth->uid());
        $this->db->set('view_type', $view_type);
        if ($this->make_money && !$is_owner) {
            $this->db->set('unit_price', $this->video_data['unit_price']);
        } else {
            $this->db->set('unit_price', 0);
        }
        $this->db->set('first_time', $this->time);
        $this->db->set('last_time', $this->time);
        $this->db->insert('b_view_log');
        $view_log_id = $this->db->insert_id();
        return $view_log_id;
    }

    function find_uid_by_referer_url($referer_url) {
        $url_segment = explode('/', $referer_url);

        if ($url_segment[0] == 'house') {
            return $url_segment[2];
        }
        if ($url_segment[0] == 'study') {
            if ($url_segment[2] == 'course_act') {
                $this->db->select('uid_owner');
                $this->db->where('c_id', $url_segment[3]);
                $q = $this->db->get('s_course');
                return $q->row()->uid_owner;
            }
            return 0;
        }
        return 0;
    }

    function update_view_log($view_log_id, $to_close = FALSE) {
        $data = array();
        $uid = $this->auth->uid();
        // เรียกข้อมูล view log
        $money = 0;
        $unit_price = 0;
        $this->db->where('id', $view_log_id);
        $q2 = $this->db->get('b_view_log');
        $view_log_data = $q2->row_array();
        //ดูว่า view_type เป็นอะไร
        //$view_type = $view_log_data['view_type'];

        $play_time = $this->time - $view_log_data['last_time'];
        $data['can_play'] = TRUE;

        if ($view_log_data['unit_price'] > 0) {
            $unit_price = $view_log_data['unit_price'] / 3600;
            $money = $unit_price * $play_time;
        }
        if ($money > 0) {
            if ($view_log_data['view_type'] == 'money') {
                $user_money = $this->auth->money();
            } else {
                $user_money = $this->auth->money_bonus();
            }
            if ($user_money < $money) {
                $money = $user_money;
                $data['can_play'] = FALSE;
            }
        }

        $this->db->trans_start();
        if ($money > 0) {
            //update user credit
            $this->db->where('uid', $uid);
            if ($view_log_data['view_type'] == 'money') {
                $this->db->set('money', "money-$money", FALSE);
            } else {
                $this->db->set('money_bonus', "money_bonus-$money", FALSE);
            };
            $this->db->set('update_time', $this->time);
            $this->db->update('u_user_credit');
        }
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

    function clear_view_log() {
        $time_out = $this->time - 300;
        $this->db->where('last_time <', $time_out);
        $this->db->set('is_end', 1);
        $this->db->update('b_view_log');
    }

    function get_users_view() {
        $a_uid = array();
        $user_data = array();
        $this->db->where('is_end', 0);
        $q1 = $this->db->get('b_view_log');
        if ($q1->num_rows() > 0) {
            foreach ($q1->result_array() as $v) {
                $a_uid[] = $v['uid_view'];
            }
            $this->db->select('u_user_detail.*');
            $this->db->select('u_user.facebook_user_id');
            $this->db->from('u_user');
            $this->db->where('u_user.uid=u_user_detail.uid', NULL, FALSE);
            $this->db->where_in('u_user_detail.uid', $a_uid);
            $q2 = $this->db->get('u_user_detail');
            $user_data = $q2->result_array();
        }

        return $user_data;
    }

}
