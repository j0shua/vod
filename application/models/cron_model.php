<?php

class cron_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    function cron_day() {
        $this->db->where('cron_id', 'day');
        $q = $this->db->get('z_cron');
        $r = $q->row_array();
        if (date($r['format_character']) != $r['value']) {
            $this->update_user_resource_count();
            $this->update_course_owner_detail();
            $this->db->where('cron_id', 'day');
            $this->db->set('value', date($r['format_character']));
            $this->db->update('z_cron');
        }
    }

    function update_user_resource_count() {
        $this->db->where('rid', 3);
        $q1 = $this->db->get('u_user_detail');
        if ($q1->num_rows() > 0) {
            foreach ($q1->result_array() as $v1) {

                $this->db->where('resource_type_id', 1);
                $this->db->where('uid_owner', $v1['uid']);
                $set['video_count'] = $this->db->count_all_results('r_resource');

                $this->db->where('resource_type_id', 4);
                $this->db->where('uid_owner', $v1['uid']);
                $set['dycontent_count'] = $this->db->count_all_results('r_resource');

                $this->db->where('resource_type_id', 5);
                $this->db->where('uid_owner', $v1['uid']);
                $set['sheet_count'] = $this->db->count_all_results('r_resource');

                $this->db->set($set);
                $this->db->where('uid', $v1['uid']);
                $this->db->update('u_user_detail');
            }
        }
    }

    /**
     * อัพเดตชื่อเจ้าของคอร์ส
     * @param type $uid_owner
     */
    function update_course_owner_detail($uid_owner = '') {
        if ($uid_owner != '') {
            $user_data = $this->auth->get_user_data($uid_owner);
            $set = array(
                'full_name_owner' => $user_data['full_name'],
                'school_name_owner' => $user_data['school_name']
            );
            $this->db->set($set);
            $this->db->where('uid_owner', $uid_owner);
            $this->db->update('s_course');
        } else {
            $this->db->distinct('uid_owner');
            $q = $this->db->get('s_course');
            if ($q->num_rows() > 0) {
                foreach ($q->result_array() as $row) {
                    $user_data = $this->auth->get_user_data($row['uid_owner']);
                    $set = array(
                        'full_name_owner' => $user_data['full_name'],
                        'school_name_owner' => $user_data['school_name']
                    );
                    $this->db->set($set);
                    $this->db->where('uid_owner', $row['uid_owner']);
                    $this->db->update('s_course');
                }
            }
        }
    }

}
