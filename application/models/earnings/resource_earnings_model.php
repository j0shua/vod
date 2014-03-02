<?php

class resource_earnings_model extends CI_Model {

    var $percent_earnings_owner_video;
    var $percent_earnings_refer_video;
    var $withdraw_fee;
    var $time;

    public function __construct() {
        parent::__construct();
        $this->load->config('earnings');
        $this->load->helper('time');
        $this->percent_earnings_owner_video = $this->config->item('percent_earnings_owner_video');
        $this->percent_earnings_refer_video = $this->config->item('percent_earnings_refer_video');
        $this->withdraw_fee = $this->config->item('withdraw_fee');
        $this->time = time();
    }

    // ของ admin  --------------------------------------------------------------------------------------------------

    function get_site_earnings() {
        $data['site_earnings_true'] = $this->get_site_earnings_true();
        $data['site_earnings_topup'] = $this->get_site_earnings_topup();
        return $data;
    }

    function get_site_earnings_true() {
        
    }

    function get_site_earnings_topup() {
        
    }

    function get_users_earnings() {
        $data['find_all_users_earnings_resource'] = $this->get_users_earnings_resource();
        $data['find_all_users_earnings_share'] = $this->get_users_earnings_share();
        return $data;
    }

    function get_users_earnings_resource() {
        
    }

    function get_users_earnings_share() {
        
    }

// All  --------------------------------------------------------------------------------------------------
    function get_date_range_obj($date = '') {
        if ($date == '') {
            $now_date_obj = new DateTime();
        } else {
            $now_date_obj = new DateTime($date); //20131230 **
        }

        if ($now_date_obj->format('j') == 1) {

            if ($now_date_obj->format('n') == 1) {

                $from_month = 12;
                $from_year = $now_date_obj->format('Y') - 1;
            } else {
                $from_month = $now_date_obj->format('n') - 1;
                $from_year = $now_date_obj->format('Y');
            }

            $to_day = cal_days_in_month(CAL_GREGORIAN, $from_month, $from_year);
        } else {
            $from_month = $now_date_obj->format('n');
            $from_year = $now_date_obj->format('Y');
            $to_day = $now_date_obj->format('j') - 1;
        }


        $to_day = str_pad($to_day, 2, '0', STR_PAD_LEFT);
        $from_month = str_pad($from_month, 2, '0', STR_PAD_LEFT);
        $data['from'] = new DateTime($from_year . $from_month . '01');
        $data['to'] = new DateTime($from_year . $from_month . $to_day);
        return $data;
    }

    function get_earnings_summary_data($uid, $from, $to) {
        $result = array();
        //video owner
        $this->db->select_sum('money');
        $this->db->where('is_end', 1);
        $this->db->where('uid_owner', $uid);
        $this->db->where('view_type', 'money');


        $this->db->where("FROM_UNIXTIME(`first_time`,'%Y%m%d') >=" . $from, NULL, FALSE);
        $this->db->where("FROM_UNIXTIME(`first_time`,'%Y%m%d') <=" . $to, NULL, FALSE);
        $q1 = $this->db->get('b_view_log');
        $r1 = $q1->row_array();
        $r1['money'] = number_format($r1['money'], 2);
        $result['earnings_data'][0] = $r1;
        $result['earnings_data'][0]['title'] = 'วิดีโอของตนเอง';
        $result['earnings_data'][0]['type'] = 'video_owne';
        $result['earnings_data'][0]['percent_earnings'] = $this->percent_earnings_owner_video;
        $e1 = $result['earnings_data'][0]['earnings_money'] = number_format($r1['money'] * ($this->percent_earnings_owner_video / 100 ), 2);


        //video refer
        $this->db->select_sum('money');
        $this->db->where('is_end', 1);
        $this->db->where('uid_referer', $uid);
        $this->db->where('view_type', 'money');
        $this->db->where("FROM_UNIXTIME(`first_time`,'%Y%m%d') >=" . $from, NULL, FALSE);
        $this->db->where("FROM_UNIXTIME(`first_time`,'%Y%m%d') <=" . $to, NULL, FALSE);
        $q2 = $this->db->get('b_view_log');
        $r2 = $q2->row_array();
        $r2['money'] = number_format($r2['money'], 2);

        $result['earnings_data'][1] = $r2;
        $result['earnings_data'][1]['title'] = 'วิดีโอที่ตนนำเสนอ';
        $result['earnings_data'][1]['type'] = 'video_refer';
        $result['earnings_data'][1]['percent_earnings'] = $this->percent_earnings_refer_video;
        $e2 = $result['earnings_data'][1]['earnings_money'] = number_format($r2['money'] * ($this->percent_earnings_refer_video / 100 ), 2);
        $result['sum_earnings'] = $e1 + $e2;
        $result['uid_customer'] = $uid;
        $result['start_time'] = $from;
        $result['end_time'] = $to;

        $result['start_time_unix'] = mktimestamp($from);
        $result['end_time_unix'] = mktimestamp($to);
        return $result;
    }

    // ของสมาชิก --------------------------------------------------------------------------------------------------

    function get_withdraw_from_date($uid) {
        //เคยเบิกเงินหรือยัง
        $this->db->limit(1);
        $this->db->where('uid_customer', $uid);
        $this->db->order_by('start_time', 'asc');
        $q = $this->db->get('b_earning');
        if ($q->num_rows() > 0) {

            $r = $q->row_array();

            return date('d/m/Y', $r['end_time'] + 1);
        } else {
            $user_data = $this->auth->get_user_data($uid);

            return date('d/m/Y', $user_data['register_time']);
        }
    }

    function get_withdraw_fee() {
        return $this->withdraw_fee;
    }

    function withdraw($from, $to) {
        $uid = $this->auth->uid();
        $start_time = mktimestamp($from);
        $end_time = mktimestamp($to);
        $earnings_summary_data = $this->get_earnings_summary_data($uid, $from, $to);
        print_r($earnings_summary_data);
        $set = array(
            'uid_customer' => $uid,
            'desc' => '',
            'data' => '',
            'money_total' => '',
            'request_time' => $this->time,
            'start_time' => $start_time,
            'end_time' => $end_time,
        );
        $this->db->set($set);
        $this->db->insert('b_earning');
        return TRUE;
    }

}
