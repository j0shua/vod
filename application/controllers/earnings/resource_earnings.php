<?php

/**
 * โปรแกรมสำหรับส่วนรายได้ของผู้ใช้
 * @property ddoption_model $ddoption_model
 * @property resource_earnings_model $resource_earnings_model
 */
class resource_earnings extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->model('earnings/resource_earnings_model');
    }

    /**
     * หน้าแรกรายงาน รายได้ 
     */
    function index() {

        // วันที่ ---------------------------------
        if (!$this->input->get('from') || !$this->input->get('to')) {
            $now_month = date('n');
            $now_year = date('Y');
            if (date('j') == 1) {
                if (date('n') == 1) {
                    $now_year = data('Y') - 1;
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
            redirect('earnings/resource_earnings?' . $query_string);
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
        // วันที่ ---------------------------------

        $data = $this->resource_earnings_model->get_earnings_summary_data($this->auth->uid(), $q_from, $q_to);
        $data['from'] = $from;
        $data['to'] = $to;
        //print_r($data);
        $this->load->model('main_side_menu_model');
        $this->template->load_showloading();

        //$data = array();
        $data['title'] = 'รายงานรายได้';
        $data['form_action'] = site_url('earnings/resource_earnings');
        $data['withdraw_url'] = site_url('earnings/resource_earnings/withdraw');
        $data['withdraw_history_url'] = site_url('earnings/resource_earnings/withdraw_history');
        $data['account_url'] = site_url('user/earnings_account');

        $data['date_from_stamp'] = time();
        $data['date_to_stamp'] = time();
        $data['main_side_menu'] = $this->main_side_menu_model->study('resource_earnings');
        $this->template->application_script('earnings/resource_earnings/main_report.js');
        $this->template->write_view('earnings/resource_earnings/main_report', $data);
        $this->template->render();
    }

    /**
     * รายงานรายได้เป็นตาราง
     */
    function earnings_grid() {
        $this->load->model('main_side_menu_model');
        $this->template->load_showloading();
        $this->template->load_flexgrid();
        $data = array();
        $data['title'] = 'รายงานรายได้';
        $data['date_from_stamp'] = time();
        $data['date_to_stamp'] = time();
        $data['main_side_menu'] = $this->main_side_menu_model->study('resource_earnings');
        $script_var = array(
            'ajax_grid_url' => site_url('earnings/resource_earnings/ajax_resource_earnings_grid'),
            'date_query' => array('value' => time())
        );
        $this->template->script_var($script_var);
        $this->template->application_script('earnings/resource_earnings/main_grid.js');
        $this->template->write_view('earnings/resource_earnings/main_grid', $data);
        $this->template->render();
    }

    function ajax_resource_earnings_grid() {
        
    }

    /**
     * เบิกเงิน
     */
    function withdraw() {
        $this->load->model('main_side_menu_model');
        $this->template->load_showloading();


        // วันที่ ---------------------------------
        $from = $this->resource_earnings_model->get_withdraw_from_date($this->auth->uid());
        if (!$this->input->get('to')) {
            $now_month = date('n');
            $now_year = date('Y');
            if (date('j') == 1) {
                if (date('n') == 1) {
                    $now_year = data('Y') - 1;
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

            $to = $now_day . '/' . $now_month . '/' . $now_year;
            $query_string = ('to=' . urlencode($to));
            redirect('earnings/resource_earnings/withdraw?' . $query_string);
        } else {

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
        //$q_from = 20010101;
        $data = $this->resource_earnings_model->get_earnings_summary_data($this->auth->uid(), $q_from, $q_to);

        $data['from'] = $from;
        $data['to'] = $to;

        $data['title'] = 'รายงานรายได้';
        $data['filter_form_action'] = site_url('earnings/resource_earnings/withdraw');
        $data['withdraw_form_action'] = site_url('earnings/resource_earnings/do_withdraw');
        $data['account_url'] = site_url('user/earnings_account');
        $data['withdraw_fee'] = $this->resource_earnings_model->get_withdraw_fee();

        $data['date_from_stamp'] = time();
        $data['date_to_stamp'] = time();
        $data['main_side_menu'] = $this->main_side_menu_model->study('resource_earnings');
        $this->template->application_script('earnings/resource_earnings/withdraw.js');
        $this->template->write_view('earnings/resource_earnings/withdraw', $data);
        $this->template->render();
    }

    function do_withdraw() {
        $from = $this->resource_earnings_model->get_withdraw_from_date($this->auth->uid());
        if (!$this->input->get('to')) {
            $now_month = date('n');
            $now_year = date('Y');
            if (date('j') == 1) {
                if (data('n') == 1) {
                    $now_year = data('Y') - 1;
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

            $to = $now_day . '/' . $now_month . '/' . $now_year;
            $query_string = ('to=' . urlencode($to));
            redirect('earnings/resource_earnings/withdraw?' . $query_string);
        } else {

            $to = $this->input->get('to');
        }
        $result = $this->resource_earnings_model->withdraw($from,$to);
        print_r($result);
        
    }

    /**
     * ประวัติการเบิกเงิน
     */
    function withdraw_history() {
        $this->load->model('main_side_menu_model');
        $this->template->load_showloading();
        $this->template->load_flexgrid();
        $data = array();
        $data['title'] = 'ประวิติการเบิกเงิน';
        $data['withdraw_url'] = site_url('earnings/resource_earnings/withdraw');
        $data['withdraw_history'] = site_url('earnings/resource_earnings/withdraw_history');

        $data['date_from_stamp'] = time();
        $data['date_to_stamp'] = time();
        $data['main_side_menu'] = $this->main_side_menu_model->study('resource_earnings');
        $this->template->write_view('earnings/resource_earnings/withdraw_history', $data);
        $this->template->render();
    }

    function test() {
        $r = $this->auth->get_user_data();
        print_r($r);
//        $sql = "SELECT
//b_view_log.id,
//b_view_log.uid_owner,
//b_view_log.uid_view,
//b_view_log.view_type,
//b_view_log.resource_id,
//b_view_log.first_time,
//b_view_log.unit_price,
//b_view_log.money,
//b_view_log.last_time,
//b_view_log.is_end,
//b_view_log.uid_referer,
//b_view_log.referer_url
//FROM
//b_view_log
//ORDER BY
//b_view_log.first_time DESC
//";
//        $q = $this->db->query($sql);
//        print_r($q->row_array());
    }

}
