<?php

/**
 * @property advance_report_model $advance_report_model
 */
class advance_report extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    function index() {
        
    }

    function filter($tbl_no = 0) {
        if (!$this->input->get('from') || !$this->input->get('to')) {
            $now_month = date('n');
            $now_year = date('Y');
            if (date('j') == 1) {
                if (date('n') == 1) {
                    $now_year = date('Y') - 1;
                    $now_month = 12;
                } else {
                    $now_month = date('n') - 1;
                }
                $now_day = cal_days_in_month(CAL_GREGORIAN, $now_month, $now_year);
            } else {
                $now_day = date('j') - 1;
            }


            $now_day = str_pad($now_day, 2, 0, STR_PAD_LEFT);
            $now_month = str_pad($now_month, 2, 0, STR_PAD_LEFT);
            $now_year = str_pad($now_year, 2, 0, STR_PAD_LEFT);
            $from = '01/' . $now_month . '/' . $now_year;
            //echo $from;
            //exit();
            $to = $now_day . '/' . $now_month . '/' . $now_year;
            $query_string = ('from=' . urlencode($from) . '&to=' . urlencode($to));
            redirect('report/advance_report/filter/' . $tbl_no . '?' . $query_string);
        } else {
            $from = $this->input->get('from');
            $to = $this->input->get('to');
        }

        $a_from = explode('/', $from);
        if (count($a_from) == 3) {
            $q_from = $a_from[2] . $a_from[1] . $a_from[0];
        }
        $a_to = explode('/', $to);
        if (count($a_to) == 3) {
            $q_to = $a_to[2] . $a_to[1] . $a_to[0];
        }

        $data['table_list'] = array(
            'รายงาน การเข้าชมวิดีโอ ของครู',
            'รายงาน การเข้าชมวิดีโอ ของนักเรียน',
            'รายงาน การเข้าใช้งานเว็บ ของครู',
            'รายงาน การเข้าใช้งานเว็บ ของนักเรียน',
            'รายงาน การเข้าใช้งานเว็บ ของโรงเรียน',
            'รายงาน การลงทะเบียน ของครู',
            'รายงาน การลงทะเบียน ของนักเรียน',
            'รายงาน สรุปการเข้าใช้งานเว็บ ของครู',
            'รายงาน สรุปการเข้าใช้งานเว็บ ของนักเรียน',
            'รายงานจำนวนครูที่ลงทะเบียน',
            'รายงานจำนวนนักเรียนที่ลงทะเบียน',
//            'รายงานการลงทะเบียนของนักเรียน',
//            'รายงานการลงทะเบียนของครู',
        );
        $tbl_name = '';


        switch ($tbl_no) {
            case 0:

                $sql = "select `u_user_detail`.`uid` AS `uid`,`u_user_detail`.`full_name` AS `full_name`,sec_to_time((`b_view_log`.`last_time` - `b_view_log`.`first_time`)) AS `total_view`,`b_view_log`.`first_time` AS `first_time` from (`b_view_log` left join `u_user_detail` on((`b_view_log`.`uid_view` = `u_user_detail`.`uid`))) "
                        . "where ((`u_user_detail`.`rid` = 3) and "
                        . "FROM_UNIXTIME(`b_view_log`.`first_time`,'%Y%m%d') >= $q_from "
                        . "AND FROM_UNIXTIME(`b_view_log`.`first_time`,'%Y%m%d') <= $q_to"
                        . ")"
                        . "group by `b_view_log`.`uid_view`";

                break;
            case 1:
                $sql = "select `u_user_detail`.`uid` AS `uid`,`u_user_detail`.`full_name` AS `full_name`,sec_to_time((`b_view_log`.`last_time` - `b_view_log`.`first_time`)) AS `total_view`,`b_view_log`.`first_time` AS `first_time` from (`b_view_log` left join `u_user_detail` on((`b_view_log`.`uid_view` = `u_user_detail`.`uid`))) "
                        . "where ((`u_user_detail`.`rid` = 2) and "
                        . "FROM_UNIXTIME(`b_view_log`.`first_time`,'%Y%m%d') >= $q_from "
                        . "AND FROM_UNIXTIME(`b_view_log`.`first_time`,'%Y%m%d') <= $q_to"
                        . ")"
                        . "group by `b_view_log`.`uid_view`";


                break;
            case 2:
                $sql = "select `u_user_online_log`.`log_id` AS `log_id`,from_unixtime(`u_user_online_log`.`login_time`) AS `login_time`,from_unixtime(`u_user_online_log`.`logout_time`) AS `logout_time`,sec_to_time(`u_user_online_log`.`online_times`) AS `online_times`,`u_user_online_log`.`school_name` AS `school_name`,`u_user_detail`.`full_name` AS `full_name` from (`u_user_online_log` left join `u_user_detail` on((`u_user_online_log`.`uid` = `u_user_detail`.`uid`))) "
                        . "where ((`u_user_online_log`.`rid` = 3) and ("
                        . "FROM_UNIXTIME(`u_user_online_log`.`login_time`,'%Y%m%d') >= $q_from "
                        . "AND FROM_UNIXTIME(`u_user_online_log`.`login_time`,'%Y%m%d') <= $q_to"
                        . "))";
                break;
            case 3:
                $sql = "select `u_user_online_log`.`uid` AS `uid`,from_unixtime(`u_user_online_log`.`login_time`) AS `login_time`,from_unixtime(`u_user_online_log`.`logout_time`) AS `logout_time`,sec_to_time(`u_user_online_log`.`online_times`) AS `online_times`,`u_user_online_log`.`school_name` AS `school_name`,`u_user_detail`.`full_name` AS `full_name`,`u_user_online_log`.`log_id` AS `log_id` from (`u_user_online_log` join `u_user_detail` on((`u_user_online_log`.`uid` = `u_user_detail`.`uid`))) "
                        . "where ((`u_user_online_log`.`rid` = 2) and ("
                        . "FROM_UNIXTIME(`u_user_online_log`.`login_time`,'%Y%m%d') >= $q_from "
                        . "AND FROM_UNIXTIME(`u_user_online_log`.`login_time`,'%Y%m%d') <= $q_to"
                        . "))";
                break;
            case 4:
                $sql = "select from_unixtime(`u_user_online_log`.`login_time`) AS `login_time`,from_unixtime(`u_user_online_log`.`logout_time`) AS `logout_time`,sec_to_time(`u_user_online_log`.`online_times`) AS `online_times`,`u_user_online_log`.`school_name` AS `school_name` from (`u_user_online_log` join `u_user_detail` on((`u_user_online_log`.`uid` = `u_user_detail`.`uid`))) "
                        . "where ("
                        . "FROM_UNIXTIME(`u_user_online_log`.`login_time`,'%Y%m%d') >= $q_from "
                        . "AND FROM_UNIXTIME(`u_user_online_log`.`login_time`,'%Y%m%d') <= $q_to"
                        . ") "
                        . "group by `u_user_online_log`.`school_name`";
                break;
            case 5:
                $sql = "select `u_user_detail`.`uid` AS `uid`,`u_user_detail`.`full_name` AS `ชื่อ`,from_unixtime(`u_user`.`register_time`) AS `เวลาลงทะเบียน`,`u_user_detail`.`school_name` AS `โรงเรียน` from (`u_user` join `u_user_detail` on((`u_user_detail`.`uid` = `u_user`.`uid`))) "
                        . "where ((`u_user`.`rid` = 3) and ("
                        . "FROM_UNIXTIME(`u_user`.`register_time`,'%Y%m%d') >= $q_from "
                        . "AND FROM_UNIXTIME(`u_user`.`register_time`,'%Y%m%d') <= $q_to"
                        . "))";
                break;
            case 6:
                $sql = "select `u_user_detail`.`uid` AS `uid`,`u_user_detail`.`full_name` AS `ชื่อ`,from_unixtime(`u_user`.`register_time`) AS `เวลาลงทะเบียน`,`u_user_detail`.`school_name` AS `โรงเรียน` from (`u_user` join `u_user_detail` on((`u_user_detail`.`uid` = `u_user`.`uid`))) "
                        . "where ((`u_user`.`rid` = 2) and ("
                        . "FROM_UNIXTIME(`u_user`.`register_time`,'%Y%m%d') >= $q_from "
                        . "AND FROM_UNIXTIME(`u_user`.`register_time`,'%Y%m%d') <= $q_to"
                        . "))";
                break;
            case 7:
                $sql = "select `u_user_online_log`.`uid` AS `uid`,`u_user_detail`.`full_name` AS `full_name`,sec_to_time(sum(`u_user_online_log`.`online_times`)) AS `online_times`,`u_user_online_log`.`school_name` AS `school_name`,`u_user_online_log`.`log_id` AS `log_id` from (`u_user_online_log` left join `u_user_detail` on((`u_user_online_log`.`uid` = `u_user_detail`.`uid`))) "
                        . "where ((`u_user_online_log`.`rid` = 3) and ("
                        . "FROM_UNIXTIME(`u_user_online_log`.`login_time`,'%Y%m%d') >= $q_from "
                        . "AND FROM_UNIXTIME(`u_user_online_log`.`login_time`,'%Y%m%d') <= $q_to"
                        . ")) "
                        . "group by `u_user_online_log`.`uid`";
                break;
            case 8:
                $sql = "select `u_user_online_log`.`uid` AS `uid`,`u_user_detail`.`full_name` AS `full_name`,sec_to_time(sum(`u_user_online_log`.`online_times`)) AS `online_times`,`u_user_online_log`.`school_name` AS `school_name`,`u_user_online_log`.`log_id` AS `log_id` from (`u_user_online_log` left join `u_user_detail` on((`u_user_online_log`.`uid` = `u_user_detail`.`uid`))) "
                        . "where ((`u_user_online_log`.`rid` = 2) and ("
                        . "FROM_UNIXTIME(`u_user_online_log`.`login_time`,'%Y%m%d') >= $q_from "
                        . "AND FROM_UNIXTIME(`u_user_online_log`.`login_time`,'%Y%m%d') <= $q_to"
                        . ")) "
                        . "group by `u_user_online_log`.`uid`";
                break;
            case 9:
                $sql = "select count(`u_user`.`uid`) AS `จำนวนครู` from `u_user` "
                        . "where (`u_user`.`rid` = 3) and ("
                        . "FROM_UNIXTIME(`u_user`.`register_time`,'%Y%m%d') >= $q_from "
                        . "AND FROM_UNIXTIME(`u_user`.`register_time`,'%Y%m%d') <= $q_to"
                        . ")";
                break;
            case 10:
                $sql = "select count(`u_user`.`uid`) AS `จำนวนนักเรียน` from `u_user` "
                        . "where (`u_user`.`rid` = 2) and ("
                        . "FROM_UNIXTIME(`u_user`.`register_time`,'%Y%m%d') >= $q_from "
                        . "AND FROM_UNIXTIME(`u_user`.`register_time`,'%Y%m%d') <= $q_to"
                        . ")";
                break;
            case 11:
                $sql = "SELECT u_user_detail.uid, u_user_detail.full_name, u_user_detail.school_name, from_unixtime(u_user.register_time)register_time "
                        . "FROM u_user INNER JOIN u_user_detail ON u_user.uid = u_user_detail.uid "
                        . "WHERE u_user.register_time > 0 AND u_user.rid = 2"
                        . "FROM_UNIXTIME(`u_user`.`register_time`,'%Y%m%d') >= $q_from "
                        . "AND FROM_UNIXTIME(`u_user`.`register_time`,'%Y%m%d') <= $q_to";
                break;
            case 12:
                $sql = "SELECT u_user_detail.uid, u_user_detail.full_name, u_user_detail.school_name, from_unixtime(u_user.register_time) register_time "
                        . "FROM u_user INNER JOIN u_user_detail ON u_user.uid = u_user_detail.uid "
                        . "WHERE u_user.register_time > 0 AND u_user.rid = 3"
                        . "FROM_UNIXTIME(`u_user`.`register_time`,'%Y%m%d') >= $q_from "
                        . "AND FROM_UNIXTIME(`u_user`.`register_time`,'%Y%m%d') <= $q_to";
                break;

            default:
                break;
        }

        $q = $this->db->query($sql);
        $r = $q->row_array();
        $result = $q->result_array();
        $this->load->library('table');
        $tmpl = array(
            'table_open' => '<table class="data" border="0" cellpadding="4" cellspacing="0">'
        );


        $heading = array();
        foreach ($r as $k => $v) {
            $heading[] = $k;
        }
        $this->table->set_heading($heading);
        $this->table->set_template($tmpl);
        $data['filter_form_action'] = site_url('report/advance_report/filter/' . $tbl_no . '?');
        $data['from'] = $from;
        $data['to'] = $to;
        $data['tbl_name'] = $data['table_list'][$tbl_no];
        $data['tbl_no'] = $tbl_no;
        $data['table'] = $this->table->generate($result);
        $this->template->write_view('report/advance_report/report_table', $data);
        $this->template->render();
    }

}
