<?php

/**
 * โมเดล สำหรับการเติมเงินด้วย coupon
 * @author lojorider <lojorider@gmail.com>
 */
class coupon_model extends CI_Model {

    var $time;
    var $coupon_code_pad = 7;

    public function __construct() {
        parent::__construct();
        $this->load->helper('time');
        $this->time = time();
    }

    /**
     * 
     * @param type $uid เลขที่ผู้ใช้
     * @param type $coupon_code รหัสคูปอง หรือ รหัสหนังสือก็ได้
     * @param type $use_from มีค่าที่ต้องกำหนดคือ "register" และ "normal"
     * @return string
     */
    function use_coupon($uid, $coupon_code, $use_from) {
        $result = array(
            'success' => FALSE,
            'message' => 'ไม่มีรหัสนี้อยู่ในระบบ'
        );
        //check coupon

        $this->db->where('coupon_code', $coupon_code);
        $this->db->where('active', 1);
        $q_coupon = $this->db->get('b_coupon');
        if ($q_coupon->num_rows() > 0) {
            $r_coupon = $q_coupon->row_array();
            if ($r_coupon['reuse_number'] == 0 || $r_coupon['reuse_number'] > $r_coupon['used_number']) {
                $this->db->where('uid', $uid);
                $this->db->where('cid', $r_coupon['cid']);
                $q_coupon_log = $this->db->get('b_coupon_log');
                if ($q_coupon_log->num_rows() > 0) {
                    $result = array(
                        'success' => FALSE,
                        'message' => 'มีการใช้รหัสนี้ไปแล้ว',
                    );
                } else {
                    $money = $r_coupon['money'];
                    $money_bonus = $r_coupon['money_bonus'];

                    $this->db->set('money_bonus', 'money_bonus+' . $money_bonus, FALSE);
                    $this->db->set('money', 'money+' . $money, FALSE);
                    $this->db->set('update_time', $this->time);
                    $this->db->where('uid', $uid);
                    $this->db->update('u_user_credit');
                    //update b_coupon
                    $this->db->set('`used_number`', "`used_number`+1", FALSE);
                    $this->db->where('cid', $r_coupon['cid']);
                    $this->db->update('b_coupon');

                    //insert coupon log
                    $this->db->set('cid', $r_coupon['cid']);
                    $this->db->set('coupon_code', $coupon_code);
                    $this->db->set('coupon_type', $r_coupon['coupon_type']);
                    $this->db->set('use_time', $this->time);
                    $this->db->set('money', $money);
                    $this->db->set('money_bonus', $money_bonus);
                    $this->db->set('use_from', $use_from);
                    $this->db->set('uid', $uid);
                    $this->db->insert('b_coupon_log');
                    $clid = $this->db->insert_id();
                    $result = array(
                        'success' => TRUE,
                        'message' => 'การเติมเงินจำนวน ' . ($money+$money_bonus) . ' บาท เสร็จสิ้น',
                        'cid' => $r_coupon['cid'],
                        'clid' => $clid
                    );
                }
            } else {

                $result = array(
                    'success' => FALSE,
                    'message' => 'มีการใช้รหัสนี้ไปแล้ว รหัสนี้ไม่สามารถใช้ซ้ำได้'
                );
            }
        } else {
            if ($use_from != 'fail_topup') {
                $f_array = array(
                    'uid' => $uid,
                    'coupon_code_fail' => $coupon_code,
                    'use_time_fail' => time(),
                    'fail_from' => $use_from
                );
                $this->db->set($f_array);
                $this->db->insert('b_coupon_fail');
            }
        }
        return $result;
    }

    function coupon_check_bits($coupon_code) {
        $cond_num = strlen($coupon_code);
        for ($i = 0, $sum = 0; $i < $cond_num; $i++) {
            $sum += (int) ($coupon_code{$i}) * ($cond_num - $i);
        }
        return substr($sum, -1);
    }

    function gen_coupon_code($amount, $money, $money_bonus, $coupon_type, $active = 1) {

        $this->db->select_max('running_number');
        $q = $this->db->get('b_coupon');
        $start = ($q->row()->running_number) + 1;
        $end = $start + ($amount - 1);

        foreach (range($start, $end) as $running) {
            $running_number = $running;
            $running = str_pad($running, $this->coupon_code_pad, "0", STR_PAD_LEFT);
            $running = rand(1, 9) . substr($running, 0, ($this->coupon_code_pad - 3)) . rand(0, 9) . substr($running, -3);
            $running_final = $running . $this->coupon_check_bits($running);
            $coupon_set = array(
                'coupon_code' => $running_final,
                'reuse_number' => 1,
                'used_number' => 0,
                'money' => $money,
                'money_bonus' => $money_bonus,
                'active' => $active,
                'running_number' => $running_number,
                'coupon_type' => $coupon_type,
                'create_time' => $this->time
            );
            $this->db->set($coupon_set);
            $this->db->insert('b_coupon');
        }
    }

    function get_running_coupon_code() {
        $this->db->where('coupon_type', 1);
        $q = $this->db->get('b_coupon');
        return $q->result_array();
    }

    /* ===============================================================
     * for coupon manager
     * ===============================================================
     */

    public function find_all_coupon($page, $qtype, $query, $rp, $sortname, $sortorder) {
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
        //make offset
        $offset = (($page - 1) * $rp);
        // Start Sql Query State for count row
        $this->find_all_where_coupon('b_coupon', $qtype, $query);
        // END Sql Query State
        $total = $this->db->count_all_results();
        // Start Sql Query State
        $this->find_all_where_coupon('b_coupon', $qtype, $query);
        // END Sql Query State
        $this->db->limit($rp, $offset);
        $this->db->order_by($sortname, $sortorder);
        $result = $this->db->get();
        $data['total'] = $total;

        foreach ($result->result_array() as $row) {
            $row['action'] = '';
            //$row['action'] = '<a href="' . site_url('admin/users/edit/' . $row['cid']) . '">ทำการเติมเงินให้</a>';
            //$user_detail = $this->get_user_detail_data($row['uid']);
            //$row = array_merge($row,$user_detail);
            $row['create_time'] = thdate('d-M-Y H:i:s', $row['create_time']);
            $data['rows'][] = array(
                'id' => $row['cid'],
                'cell' => $row
            );
        }

        // set Summary row
//        $summary_row = $this->get_list_field('b_coupon_fail');
//        $summary_row['action'] = '';
//        $summary_row['user_fullname'] = '';
//        $summary_row['create_time']='';
//
//
//
//
//        $data['rows'][] = array(
//            'id' => '',
//            'cell' => $summary_row
//        );
        return $data;
    }

    private function find_all_where_coupon($table_name, $qtype, $query) {
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

    public function find_all_coupon_fail($page, $qtype, $query, $rp, $sortname, $sortorder) {
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
        //make offset
        $offset = (($page - 1) * $rp);
        // Start Sql Query State for count row
        $this->find_all_where_coupon_fail('b_coupon_fail', $qtype, $query);
        // END Sql Query State
        $total = $this->db->count_all_results();
        // Start Sql Query State
        $this->find_all_where_coupon_fail('b_coupon_fail', $qtype, $query);
        // END Sql Query State
        $this->db->limit($rp, $offset);
        $this->db->order_by($sortname, $sortorder);
        $result = $this->db->get();
        $data['total'] = $total;

        foreach ($result->result_array() as $row) {
            if ($row['use_time_finish'] > 0) {
                $row['action'] = '';
            } else {
                $row['action'] = '<a href="' . site_url('utopup/coupon_manager/fail_topup/' . $row['cfid']) . '">ทำการเติมเงินให้</a>';
                $row['action'] .= '<a href="' . site_url('utopup/coupon_manager/delete_coupon_fail/' . $row['cfid']) . '" class="confirm_action">ลบข้อมูลนี้</a>';
            }
            $user_detail = $this->get_user_detail_data($row['uid']);
            $row = array_merge($row, $user_detail);
            $row['use_time_fail'] = thdate('d-M-Y H:i:s', $row['use_time_fail']);
            $data['rows'][] = array(
                'id' => $row['cfid'],
                'cell' => $row
            );
        }

        // set Summary row
        $summary_row = $this->get_list_field('b_coupon_fail');
        $summary_row['action'] = '';
        $summary_row['user_fullname'] = '';




        $data['rows'][] = array(
            'id' => '',
            'cell' => $summary_row
        );
        return $data;
    }

    private function find_all_where_coupon_fail($table_name, $qtype, $query) {
        $this->db->where('cancel_time', 0);
        //$this->db->where('use_time_finish', 0);

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

    private function get_list_field($table_name) {
        $data = array();
        foreach ($this->db->list_fields($table_name) as $field) {
            $data[$field] = '';
        }
        return $data;
    }

    function get_user_detail_data($uid) {
        $this->db->where('uid', $uid);
        $query1 = $this->db->get('u_user_detail');
        if ($query1->num_rows() > 0) {
            $row = $query1->row_array();
            $row['user_fullname'] = $row['first_name'] . ' ' . $row['last_name'];
            $this->db->where('uid', $uid);
            $query2 = $this->db->get('u_user');
            $row_all = array_merge($row, $query2->row_array());
        } else {
            $row_all['user_fullname'] = 'ผู้ใช้ถูกลบ !!!';
        }
        return $row_all;
    }

  
    function delete_coupon_fail($cfid) {
        $this->db->where('cfid', $cfid);
        $this->db->set('cancel_time', time());
        $this->db->update('b_coupon_fail');
    }

    function get_fail_topup_data($cfid) {
        $this->db->where('cfid', $cfid);
        $q = $this->db->get('b_coupon_fail');
        $row = $q->row_array();
        return $row;
    }

    function fail_topup($data) {
        $result = $this->use_coupon($data['uid'], $data['coupon_code'], 'fail_topup');
        if ($result['success']) {
            $this->db->set('use_time_finish', $this->time);
            $this->db->set('uid_topup', $this->auth->uid());
            $this->db->set('coupon_code', $data['coupon_code']);
            $this->db->set('cid', $result['cid']);
            $this->db->set('clid', $result['clid']);
            $this->db->where('cfid', $data['cfid']);
            $this->db->update('b_coupon_fail');
            return true;
        } else {
            return false;
        }
    }

    function get_coupon_data_by_time($time_stamp) {
        $this->db->where('create_time', $time_stamp);
        $q = $this->db->get('b_coupon');
        return $q->result_array();
    }

    /* ===============================================================
     * for coupon used
     * ===============================================================
     */

    public function find_all_coupon_used($page, $qtype, $query, $rp, $sortname, $sortorder) {
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
        //make offset
        $offset = (($page - 1) * $rp);
        // Start Sql Query State for count row
        $this->find_all_where_coupon_used('b_coupon_log', $qtype, $query);
        // END Sql Query State
        $total = $this->db->count_all_results();
        // Start Sql Query State
        $this->find_all_where_coupon_used('b_coupon_log', $qtype, $query);
        // END Sql Query State
        $this->db->limit($rp, $offset);
        $this->db->order_by($sortname, $sortorder);
        $result = $this->db->get();
        $data['total'] = $total;
        $user_data = array();
        foreach ($result->result_array() as $row) {
            if (!isset($user_data[$row['uid']])) {
                $user_data[$row['uid']] = $this->auth->get_user_data($row['uid']);
            }
            $row['action'] = '';


            $row['full_name_use'] = anchor('admin/users/detail/' . $row['uid'], $user_data[$row['uid']]['full_name'], 'target="_blank"');
            if ($user_data[$row['uid']]['facebook_user_id'] != 0) {

                $row['full_name_use'] .= anchor('https://www.facebook.com/' . $user_data[$row['uid']]['facebook_user_id'], 'FB', 'target="_blank"');
            }
            //$row['action'] = '<a href="' . site_url('admin/users/edit/' . $row['cid']) . '">ทำการเติมเงินให้</a>';
            //$user_detail = $this->get_user_detail_data($row['uid']);
            //$row = array_merge($row,$user_detail);
            $row['use_time'] = thdate('d-M-Y H:i:s', $row['use_time']);
            $data['rows'][] = array(
                'id' => $row['cid'],
                'cell' => $row
            );
        }
        return $data;
    }

    private function find_all_where_coupon_used($table_name, $qtype, $query) {
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

}
