<?php

/**
 * โมเดล สำหรับการเติมเงินด้วย truemoney
 * @author lojorider <lojorider@gmail.com>
 */
class truemoney_model extends CI_Model {

    var $time;

    public function __construct() {
        parent::__construct();
        $this->load->helper('time');
        $this->time = time();
    }

    function find_all($page, $qtype, $query, $rp, $sortname, $sortorder) {
        if ($qtype == 'custom') {
            parse_str($query, $query);
        }
        //set now timestamp
        //$time = time();
        //initial data        
        $data = array(
            'rows' => array(),
            'page' => $page,
            'total' => 0
        );
// make offset 
        $offset = (($page - 1) * $rp);
// Start Sql Query State for count row
        $this->find_all_where('b_card_true', $qtype, $query);
// END Sql Query State
        $total = $this->db->count_all_results();
// Start Sql Query State
        $this->db->select('b_card_true.*');
        $this->find_all_where('b_card_true', $qtype, $query);
// END Sql Query State
        $this->db->limit($rp, $offset);

        //$this->db->order_by('create_time', 'desc');
        $this->db->order_by($sortname, $sortorder);

        $result = $this->db->get();
        $data['total'] = $total;
        $user_data = array();
        foreach ($result->result_array() as $row) {
            $row['action'] = '';

            if (!isset($user_data[$row['uid_use']])) {
                $user_data[$row['uid_use']] = $this->auth->get_user_data($row['uid_use']);
            }
            $row['create_time'] = date("Y-m-d H:i:s", $row['create_time']);
            $row['fullname_use'] = anchor('admin/users/detail/' . $row['uid_use'], $user_data[$row['uid_use']]['full_name'], 'target="_blank"');
            if ($user_data[$row['uid_use']]['facebook_user_id'] != 0) {

                $row['fullname_use'] .= anchor('https://www.facebook.com/' . $user_data[$row['uid_use']]['facebook_user_id'], 'FB', 'target="_blank"');
            }
            //<a target="_blank" href="http://www.educasy.com/admin/users/detail/274">Arm Prapa</a>


            $data['rows'][] = array(
                'id' => $row['cid'],
                'cell' => $row
            );
        }
        return $data;
    }

    function find_all_where($table_name, $qtype, $query) {
//$this->db->order_by($orderby);


        switch ($qtype) {
            case 'custom':
                foreach ($query as $k => $v) {
                    if ($v != '') {
                        $this->db->where($k, $v);
                    }
                }
                break;
            default:
                if ($query != '') {
                    $this->db->where($qtype, $query);
                }
                break;
        }
        $this->db->from($table_name);
    }

    function get_user_fullname($uid) {
        $this->db->where('uid', $uid);
        $q = $this->db->get('u_user_detail');
        if ($q->num_rows() > 0) {
            $row = $q->row_array();
            return $row['first_name'] . ' ' . $row['last_name'];
        }
        return '-';
    }

}
