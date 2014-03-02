<?php

/**
 * Description of affiliate_money_model
 *
 * @author lojorider
 */
class affiliate_money_model extends CI_Model {

    var $doc_dir = '';
    var $resource_type_id = 2;
    var $w_from = '';
    var $w_to = '';

    public function __construct() {
        parent::__construct();
        $this->load->helper('number');
        $this->full_doc_dir = $this->config->item('full_doc_dir');
        $this->load->helper('tag');
        $this->time = time();
        $this->load->helper('time');
    }

    public function find_all($page, $qtype, $query, $rp, $sortname, $sortorder) {
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
        //select count sum and ...
        // Start Sql Query State for count row
        $this->find_all_where(' u_user_detail', $qtype, $query, $this->auth->uid());
        // END Sql Query State
        $q = $this->db->get();

        $total = $q->num_rows();

        // Start Sql Query State
        $this->db->select(' u_user_detail.*');

        $this->find_all_where(' u_user_detail', $qtype, $query, $this->auth->uid());
        // END Sql Query State
        $this->db->limit($rp, $offset);
        $this->db->order_by($sortname, $sortorder);
        $result = $this->db->get();
        $data['total'] = $total;
        $affiliate_percent = $this->get_affiliate_percent();
        $sum_money_topup = 0;
        $sum_money_earnings = 0;
        foreach ($result->result_array() as $row) {
            $row['action'] = '';
            $row['user_downline_fullname'] = anchor('affiliate/affiliate_money/user_detail/' . $row['uid'], $this->get_user_fullname($row['uid']), 'target="_blank"');
            $sum_money_topup += $money_topup = $this->get_money_topup($row['uid']);
            $row['money_topup'] = number_format($money_topup, 2);
            $money_earnings = ($affiliate_percent / 100) * $money_topup;
            $sum_money_earnings += $money_earnings;
            $row['money_earnings'] = number_format($money_earnings, 2);
            $data['rows'][] = array(
                'id' => $row['uid'],
                'cell' => $row
            );
        }
        $data['rows'][] = array(
            'id' => 0,
            'cell' => array(
                'uid' => '',
                'money_topup' => number_format($sum_money_topup, 2),
                'money_earnings' => number_format($money_earnings, 2),
                'user_downline_fullname' => $total . ' คน'
            )
        );


        return $data;
    }

    private function find_all_where($table_name, $qtype, $query, $uid_affiliate) {
        $this->w_from = '';
        $this->w_to = '';
        $this->db->where('uid_affiliate', $uid_affiliate);
        switch ($qtype) {
            case 'custom':
                foreach ($query as $k => $v) {
                    if ($v != '') {
                        switch ($k) {
                            case 'id':
                                $this->db->like($k, $v, 'after');
                                break;
                            case 'title':
                                $this->db->like($k, $v);
                                break;
                            case 'from':
                                $a_v = explode('/', $v);

                                if (count($a_v) == 3) {
                                    list($d, $m, $y) = $a_v;
                                    $v = $y . $m . $d;
                                }

                                $this->w_from = $v;

//                                $this->db->where("FROM_UNIXTIME(`topup_time`,'%Y%m%d') >=" . $v, NULL, FALSE);
                                break;
                            case 'to':
                                $a_v = explode('/', $v);

                                if (count($a_v) == 3) {
                                    list($d, $m, $y) = $a_v;
                                    $v = $y . $m . $d;
                                }
                                $this->w_to = $v;
//                                $this->db->where("FROM_UNIXTIME(`topup_time`,'%Y%m%d') <=" . $v, NULL, FALSE);
                                break;
                            default:
                                $this->db->where($k, $v);
                                break;
                        }
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
        $this->db->select('u_user_detail.first_name');
        $this->db->select('u_user_detail.last_name');
        $this->db->where('uid', $uid);
        $query = $this->db->get('u_user_detail');
        if ($query->num_rows() > 0) {
            $r = $query->row_array();
            $fullname = $r['first_name'] . ' ' . $r['last_name'];
        } else {
            $fullname = '! ไม่มีบุคคลนี้อยู่แล้ว';
        }
        return $fullname;
    }

    function is_downline($uid) {
        $this->db->where('uid', $uid);
        $this->db->where('uid_affiliate', $this->auth->uid());
        if ($this->db->count_all_results('u_user_detail') > 0) {
            return TRUE;
        }
        return FALSE;
    }

    function get_money_topup($uid_use) {
        $this->db->where('uid_use', $uid_use);

        if ($this->w_from && $this->w_to) {

            $this->db->where("FROM_UNIXTIME(`topup_time`,'%Y%m%d') >=" . $this->w_from, NULL, FALSE);
            $this->db->where("FROM_UNIXTIME(`topup_time`,'%Y%m%d') <=" . $this->w_to, NULL, FALSE);
        }
        $this->db->select_sum('money_topup');
        $q = $this->db->get('b_manual_topup');
        $money_topup = (int) $q->row()->money_topup;
        return $money_topup;
    }

    function get_affiliate_percent() {
        $uid = $this->auth->uid();
        $this->db->where('uid', $uid);
        $q = $this->db->get('u_user_detail');
        return $q->row()->affiliate_percent;
    }

}