<?php

class ajax_search_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    function user($cond, $where = array()) {
        $data['detect'] = FALSE;
        $data['user_data'] = array();
        $cond['query'] = trim($cond['query']);
        if ($cond['query'] == '') {
            return $data;
        }
        if (count($where > 0)) {
            $this->db->where($where);
        }
        $this->db->or_like('uid', $cond['query'], 'after');
        $this->db->or_like('email', $cond['query'], 'after');
        $this->db->where('rid', 2);
        $q1 = $this->db->get('u_user');
        if ($q1->num_rows() > 0) {
            $data['user_data'] = $q1->row_array();
            $data['user_data'] = $this->auth->get_user_data($data['user_data']['uid']);
            $data['detect'] = TRUE;
        }



        return $data;
    }

    function user_not_have_disk_quota($cond) {
        $uid_not = array();
        $q = $this->db->get('b_disk_quota');
        if ($q->num_rows() > 0) {
            foreach ($q->result_array() as $row) {
                $uid_not[] = $row['uid_customer'];
            }
        }
        $data['detect'] = FALSE;
        $data['user_data'] = array();
        $query = trim($cond['query']);
        if ($query == '') {
            return $data;
        }

        if (count($uid_not) > 0) {
            $str_uid_not = "'" . implode("','", $uid_not) . "'";

            $sql = "SELECT * FROM (select * from u_user where uid not in ($str_uid_not))u_user WHERE `rid` = 2 AND `uid` LIKE '$query%' OR `email` LIKE '$query%'";
            //$sql = "SELECT * FROM (select * from u_user where uid not in ($str_uid_not))u_user WHERE `rid` = 2 AND `uid` LIKE '$query%' OR `email` LIKE '$query%'";
        } else {

            $sql = "SELECT * u_user WHERE `rid` = 3 AND `uid` LIKE 'p%' OR `email` LIKE 'p%'";
        }
        $q1 = $this->db->query($sql);

        if ($q1->num_rows() > 0) {
            $data['user_data'] = $q1->row_array();
            $data['user_data'] = $this->auth->get_user_data($data['user_data']['uid']);
            $data['detect'] = TRUE;
        }
        return $data;
    }

}
