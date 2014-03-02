<?php

/**
 * โมเดล สำหรับการเติมเงินด้วยมือ
 * @author lojorider <lojorider@gmail.com>
 */
class manual_topup_model extends CI_Model {

    var $time;

    public function __construct() {
        parent::__construct();
        $this->load->helper('time');
        $this->time = time();
    }

    /**
     * เรียกดูข้อมูล การแจ้งการโอนเงิน แสดงทั้งที่เติมแล้วและยังไม่ได้เติม
     * ที่เติมแล้วไม่สามารถลบได้
     * ที่ยังไม่ได้เติม หรือ การแจ้งซ้ำ สามารถลบได้
     */
    function inform_find_all($page, $qtype, $query, $rp, $sortname, $sortorder, $no_transfer = FALSE) {
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
        $this->inform_find_all_where('b_money_transfer', $qtype, $query, $no_transfer);
// END Sql Query State
        $total = $this->db->count_all_results();
// Start Sql Query State
        $this->db->select('b_money_transfer.*');
        $this->inform_find_all_where('b_money_transfer', $qtype, $query, $no_transfer);
// END Sql Query State
        $this->db->limit($rp, $offset);

        $this->db->order_by('topup_time', 'desc');
        $this->db->order_by($sortname, $sortorder);

        $result = $this->db->get();
        $data['total'] = $total;

        foreach ($result->result_array() as $row) {
            $row['action'] = '';
            if ($row['topup_time'] == 0) {
                $row['action'] .= '<a href="' . site_url('utopup/manual_topup/infrom_topup/' . $row['mt_id']) . '">เติมเงินให้</a>';
                $row['action'] .= '<a href="' . site_url('utopup/manual_topup/no_transfer/' . $row['mt_id']) . '">ไม่มีการโอนเงิน</a>';
            }
            $row['inform_time'] = date("Y-m-d H:i:s", $row['inform_time']);
            $row['transfer_time'] = date("Y-m-d H:i:s", $row['transfer_time']);
            $row['topup_time'] = ( $row['topup_time'] == 0) ? 'ยังไม่ได้เติม' : date("Y-m-d H:i:s", $row['topup_time']);
            $row['topup_type'] = ($row['uid_informant'] == $row['uid_use']) ? 'เติมเมื่อแจ้ง' : 'เติมเร็ว';
            $row['fullname_use'] = $this->get_user_fullname($row['uid_use']);
            //$row['fullname_informant'] = ($row['uid_informant'] == $row['uid_use']) ? $this->get_user_fullname($row['uid_informant']) : '-';
            $row['fullname_informant'] = $this->get_user_fullname($row['uid_informant']);

            $data['rows'][] = array(
                'id' => $row['mt_id'],
                'cell' => $row
            );
        }
        return $data;
    }

    function inform_find_all_where($table_name, $qtype, $query, $no_transfer) {
//$this->db->order_by($orderby);
        if (!$no_transfer) {
            $this->db->where('no_transfer_time', 0);
        }

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

// การเติมเงินด้วยมือ ==================================================================================
    /**
     * เรียกดูข้อมูลการเติมเงินทั้งหมด
     * สามารถยกเลิกการเติมเงินได้ด้วยแต่ขั้นตอนต้องยากด้วย
     */
    function topup_find_all($page, $qtype, $query, $rp, $sortname, $sortorder) {
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
        $this->topup_find_all_where('b_money_transfer', $qtype, $query);
// END Sql Query State
        $total = $this->db->count_all_results();
// Start Sql Query State
        $this->db->select('b_money_transfer.*');
        $this->topup_find_all_where('b_money_transfer', $qtype, $query);
// END Sql Query State
        $this->db->limit($rp, $offset);

        $this->db->order_by('topup_time', 'desc');
        $this->db->order_by($sortname, $sortorder);

        $result = $this->db->get();
        $data['total'] = $total;

        foreach ($result->result_array() as $row) {
            $row['action'] = '';
            if ($row['topup_time'] == 0) {
                $row['action'] .= '<a href="' . site_url('utopup/manual_topup/infrom_topup/' . $row['mt_id']) . '">เติมเงินให้</a>';
                $row['action'] .= '<a href="' . site_url('utopup/manual_topup/no_transfer/' . $row['mt_id']) . '">ไม่มีกาโอนเงิน</a>';
            }
            $row['inform_time'] = date("Y-m-d H:i:s", $row['inform_time']);
            $row['transfer_time'] = date("Y-m-d H:i:s", $row['transfer_time']);
            $row['topup_type'] = ($row['uid_informant'] == $row['uid_use']) ? 'เติมเมื่อแจ้ง' : 'เติมเร็ว';
            $row['fullname_use'] = anchor('admin/users/detail/' . $row['uid_use'], $this->get_user_fullname($row['uid_use']), 'target="_blank"');
            $row['fullname_informant'] = anchor('admin/users/detail/' . $row['uid_informant'], $this->get_user_fullname($row['uid_informant']), 'target="_blank"');
            $row = array_merge($row, $this->get_topup_data($row['mt_id']));
            $row['topup_time'] = date("Y-m-d H:i:s", $row['topup_time']);
            $data['rows'][] = array(
                'id' => $row['mt_id'],
                'cell' => $row
            );
        }
        return $data;
    }

    function topup_find_all_where($table_name, $qtype, $query) {
        $this->db->where('topup_time !=', 0);
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

    function get_topup_data($mt_id) {
        $this->db->where('mt_id', $mt_id);
        return $this->db->get('b_manual_topup')->row_array();
    }

    /**
     * topup แบบไม่ต้องแจ้งโอนเงิน แต่กรอกข้อมูลการแจ้งการโอนเงินเอง
     */
    function quick_topup($post) {
        $this->load->helper('time');
        $topup_time = $inform_time = $this->time;
        $transfer_time = mktimestamp($post['transfer_date'] . ' ' . $post['transfer_time']);
        $this->db->where('uid', $post['uid']);
        $q1 = $this->db->get('u_user_credit');
        if ($q1->num_rows() > 0) {
            $r1 = $q1->row_array();
        } else {
            $this->db->where('uid', $post['uid']);
            $q2 = $this->db->get('u_user');
            if ($q2->num_rows() > 0) {
                $this->db->set('uid', $post['uid']);
                $this->db->set('money', 0);
                $this->db->set('update_time', $topup_time);
                $this->db->insert('u_user_credit');
                $this->db->where('uid', $post['uid']);
                $q1 = $this->db->get('u_user_credit');
                $r1 = $q1->row_array();
            }
        }

        $uid_use = $r1['uid'];
        $this->db->trans_start();
        //update b_money_transfer
        $this->db->set('uid_informant', $this->auth->uid());
        $this->db->set('inform_time', $inform_time);
        $this->db->set('transfer_time', $transfer_time);
        $this->db->set('money_transfer', $post['money_transfer']);
        $this->db->set('uid_use', $uid_use);
        $this->db->set('topup_time', $topup_time);
        $this->db->set('ref_no', $post['ref_no']);
        $this->db->insert('b_money_transfer');
        //update b_manual_topup
        $mt_id = $this->db->insert_id();
        $this->db->set('mt_id', $mt_id);
        $this->db->set('money_topup', $post['money_topup']);
        $this->db->set('uid_topup', $this->auth->uid());
        $this->db->set('topup_time', $topup_time);
        $this->db->set('uid_use', $uid_use);
        $this->db->set('last_money', $r1['money']);
        $this->db->insert('b_manual_topup');
        // update credit
        $this->db->set('money', 'money+' . $post['money_topup'], FALSE);
        $this->db->set('update_time', $topup_time, FALSE);
        $this->db->where('uid', $uid_use);
        $this->db->update('u_user_credit');
        $this->db->trans_complete();
        //$this->send_email_on_quick_topup($user_email, $data);
    }

    function inform_transfer($post) {
        $this->load->helper('time');
        $uid_inform = $this->auth->uid();
        $inform_time = $this->time;
        $transfer_time = mktimestamp($post['transfer_date'] . ' ' . $post['transfer_time']);
        $this->db->where('uid', $this->auth->uid());
        $q1 = $this->db->get('u_user_credit');
        if ($q1->num_rows() > 0) {
            $this->db->set('uid_informant', $uid_inform);
            $this->db->set('inform_time', $inform_time);
            $this->db->set('transfer_time', $transfer_time);
            $this->db->set('money_transfer', $post['money_transfer']);
            $this->db->set('uid_use', $uid_inform);
            $this->db->set('ref_no', $post['ref_no']);
            $this->db->set('desc', $post['desc']);
            $this->db->set('topup_time', 0);
            $this->db->insert('b_money_transfer');
        } else {
            $this->db->where('uid', $uid_inform);
            $q2 = $this->db->get('u_user');
            if ($q2->num_rows() > 0) {
                $this->db->set('uid', $uid_inform);
                $this->db->set('money', 0);
                $this->db->set('update_time', $inform_time);
                $this->db->insert('u_user_credit');
            }
        }
        $account_data = $this->get_account_data($uid_inform);
        $data = array(
            'user_email' => $account_data['email'],
            'fullname_use' => $account_data['first_name'] . ' ' . $account_data['last_name'],
            'uid_use' => $uid_inform,
            'money_transfer' => $post['money_transfer'],
            'transfer_time' => $post['transfer_date'] . ' ' . $post['transfer_time'],
            'ref_no' => $post['ref_no'],
            'desc' => $post['desc'],
            'site_email' => $this->setting->get_site_email(),
            'site_name' => $this->setting->get_site_name(),
            'uid_affiliate' => $account_data['uid_affiliate']
        );
        $this->send_email_on_inform_transfer($data);
    }

    // ส่ง email เมื่อแจ้งการโอนเงิน
    function send_email_on_inform_transfer($data) {
        $this->load->library('email');
        $this->email->to($data['user_email']);
        $this->email->subject('ผลการแจ้งโอนเงิน');
        $this->email->message_view('topup/_email_on_inform_transfer_user', $data);
        $this->email->send();
        //แจ้งถึง admin

        $this->email->to($this->setting->get_alert_email());
        $this->email->subject('มีสมาชิกแจ้งโอนเงิน');
        $this->email->message_view('topup/_email_on_inform_transfer_admin', $data);
        $this->email->send();
// email to uid_affiliate
        if ($data['uid_affiliate'] > 0) {
            $this->db->where('uid', $data['uid_affiliate']);
            $q = $this->db->get('u_user');
            if ($q->num_rows() > 0) {
                $this->email->to($q->row()->email);
                $this->email->subject('มีสมาชิก Downline ของคุณ แจ้งโอนเงิน');
                $this->email->message_view('topup/_email_on_inform_transfer_downline', $data);
                $this->email->send();
            }
        }
    }

    function send_email_on_quick_topup($user_email, $data) {
        $this->load->library('email');
        // แจ้งถึงผู้ได้รับการเติมเงิน
        $this->email->to($user_email);
        $this->email->subject('แจ้งผลการเติมเงิน');
        $data['site_name'] = $this->setting->get_site_name();
        $data['site_email'] = $this->setting->get_site_email();
        $this->email->message_view('topup/_email_on_quick_topup', $data);
        $this->email->send();
    }

    function get_inform_data($mt_id) {
        $this->db->where('mt_id', $mt_id);
        $this->db->where('topup_time', 0);
        $q1 = $this->db->get('b_money_transfer');
        if ($q1->num_rows() > 0) {
            $row = $q1->row_array();
            $row['transfer_date'] = date('d/m/Y');

            $row['transfer_time'] = date('H:i');
            $row['fullname_use'] = $this->get_user_fullname($row['uid_use']);
            return $row;
        }
        return FALSE;
    }

    /**
     * topup แบบต้องแจ้งการโอนเงินก่อน
     */
    function infrom_topup($data) {
        $topup_time = $this->time;
        $this->db->where('mt_id', $data['mt_id']);
        $this->db->where('topup_time', 0);
        $q1 = $this->db->get('b_money_transfer');

        if ($q1->num_rows() > 0) {
            $row = $q1->row_array();
            $last_money = $this->get_last_money($row['uid_use']);
            $uid_informant = $row['uid_informant'];
            $this->db->trans_start();
            $this->db->set('ref_no', $data['ref_no']);
            $this->db->set('desc', $data['desc']);
            $this->db->set('topup_time', $topup_time);
            $this->db->where('mt_id', $data['mt_id']);
            $this->db->update('b_money_transfer');
            // เพิ่มข้อมูลการเติมเงิน
            $this->db->set('mt_id', $data['mt_id']);
            $this->db->set('money_topup', $data['money_topup']);
            $this->db->set('uid_topup', $this->auth->uid());
            $this->db->set('topup_time', $topup_time);
            $this->db->set('uid_use', $uid_informant);
            $this->db->set('last_money', $last_money);
            $this->db->insert('b_manual_topup');
            $this->topup_to_user($uid_informant, $data['money_topup']);
            $this->db->trans_complete();

            $account_data = $this->get_account_data($row['uid_use']);
            $email_data = array(
                'user_email' => $account_data['email'],
                'money_topup' => $data['money_topup'],
                'topup_time' => thdate('Y-m-d H:i:s', $this->time),
                'fullname_use' => $account_data['first_name'] . ' ' . $account_data['last_name'],
                'uid_use' => $row['uid_use'],
                'money_transfer' => $row['money_transfer'],
                'transfer_time' => thdate('Y-m-d H:i:s', $row['transfer_time']),
                'ref_no' => $data['ref_no'],
                'desc' => $data['desc'],
                'site_email' => $this->setting->get_site_email(),
                'site_name' => $this->setting->get_site_name()
            );
            $this->send_email_on_infrom_topup($email_data);
            return TRUE;
        }
        return FALSE;
    }

    function send_email_on_infrom_topup($data) {
        $this->load->library('email');
        // แจ้งถึงผู้ได้รับการเติมเงิน
        $this->email->to($data['user_email']);
        $this->email->subject('แจ้งผลการเติมเงินจากการโอน');
        $this->email->message_view('topup/_email_on_infrom_topup', $data);
        $this->email->send();
        $this->email->print_debugger();
    }

    function no_transfer($data) {
        $this->db->where('mt_id', $data['mt_id']);
        $this->db->where('topup_time', 0);
        if ($this->db->count_all_results('b_money_transfer') > 0) {
            $this->db->set('no_transfer_time', $this->time);
            $this->db->set('no_transfer_desc', $data['no_transfer_desc']);
            $this->db->where('mt_id', $data['mt_id']);
            $this->db->update('b_money_transfer');
            //$this->send_email_on_no_transfer($email_form);
            return TRUE;
        }
        return FALSE;
    }

    function get_last_money($uid) {
        $this->db->where('uid', $uid);
        $q1 = $this->db->get('u_user_credit');
        if ($q1->num_rows() > 0) {
            return $q1->row()->money;
        } else {
            $this->db->set('uid', $uid);
            $this->db->set('money', 0);
            $this->db->set('update_time', $this->time);
            $this->db->insert('u_user_credit');
            return 0;
        }
    }

    function topup_to_user($uid, $money) {
        $this->db->where('uid', $uid);
        $this->db->set('money', 'money+' . $money, FALSE);
        $this->db->update('u_user_credit');
    }

//    function search_user($cond) {
//        $data['detect'] = FALSE;
//        $data['user_data'] = array();
//        $cond['query'] = trim($cond['query']);
//        if ($cond['query'] == '') {
//            return $data;
//        }
//        
//        $this->db->or_like('uid', $cond['query'], 'after');
//        $this->db->or_like('email', $cond['query'], 'after');
//        $this->db->where('rid', 2);
//        $q1 = $this->db->get('u_user');
//        if ($q1->num_rows() > 0) {
//            $data['user_data'] = $q1->row_array();
//            $data['detect'] = TRUE;
//        }
//
//
//        return $data;
//    }

    function get_account_data($uid) {
//        $this->db->select('username,password,email,facebook_user_id');
        $this->db->where('uid', $uid);
        $q1 = $this->db->get('u_user');
        $row_user = $q1->row_array();
        $this->db->select('u_user_detail.*');
        $this->db->select('(select province_name from f_province where f_province.id=u_user_detail.province_id)province_name', NULL, FALSE);
        $this->db->where('uid', $uid);
        $q2 = $this->db->get('u_user_detail');
        $row_user_detail = $q2->row_array();
        $this->db->where('uid', $uid);
        $q3 = $this->db->get('u_user_credit');
        $row_user_credit = $q3->row_array();
        $row = array_merge($row_user, $row_user_detail, $row_user_credit);
        return $row;
    }

}