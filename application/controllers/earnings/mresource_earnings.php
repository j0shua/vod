<?php

/**
 * โปรแกรมสำหรับส่วนรายได้ของ admin
 * @property ddoption_model $ddoption_model
 * @property resource_earnings_model $resource_earnings_model
 */
class mresource_earnings extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->model('earnings/resource_earnings_model');
    }

    function get_sidebar_data($current) {
        $sidebar_data['nav'] = array(
            'site_earnings' => array('text' => 'รายได้ของเว็บไซต์', 'title' => '', 'uri' => 'earnings/mresource_earnings'),
            'teacher_earnings' => array('text' => 'รายได้ของครูในเว็บไซต์', 'title' => '', 'uri' => 'earnings/mresource_earnings/teacher_earnings'),
            'user_withdraw' => array('text' => 'การเบิกเงิน', 'title' => '', 'uri' => 'earnings/mresource_earnings/user_withdraw'),
        );
        $sidebar_data['current'] = $current;
        $sidebar_data['current_text'] = $sidebar_data['nav'][$current]['text'];
        $sidebar_data['current_title'] = $sidebar_data['nav'][$current]['title'];
        $sidebar_data['uri'] = $sidebar_data['nav'][$current]['uri'];
        return $sidebar_data;
    }

    /**
     * หน้าแรกรายงาน รายได้ของครู
     */
    function index() {
        
        // start date filter section ***
        $from = $this->input->get('from');
        $to = $this->input->get('to');
        if (!$from || !$to) {
            $date_obj = $this->resource_earnings_model->get_date_range_obj();
            $from_obj = $date_obj['from'];
            $to_obj = $date_obj['to'];
            $query_string = ('from=' . urlencode($from_obj->format('d/m/Y')) . '&to=' . urlencode($to_obj->format('d/m/Y')));
            redirect('earnings/mresource_earnings?' . $query_string);
        } else {
            $from_obj = new DateTime(implode('', array_reverse(explode('/', $from))));
            $to_obj = new DateTime(implode('', array_reverse(explode('/', $to))));
            if ($from_obj->getTimestamp() > $to_obj->getTimestamp()) {
                $date_obj = $this->resource_earnings_model->get_date_range_obj($from_obj->format('Ymd'));
                $from_obj = $date_obj['from'];
                $to_obj = $date_obj['to'];
            }
        }
        // end date filter section ***





        $sidebar_data = $this->get_sidebar_data('site_earnings');
        $data['sidebar_view'] = $this->load->view('dashboard_nav_sidebar', $sidebar_data, TRUE);
        $main_data = array(
            'title' => $sidebar_data['current_text'],
            'form_action' => site_url('earnings/mresource_earnings'),
            'from_text' => $from_obj->format('d/m/Y'),
            'to_text' => $to_obj->format('d/m/Y'),
            'date_range_text'=> thdate('d-M-Y', $from_obj->getTimestamp()).' ถึง '. thdate('d-M-Y', $to_obj->getTimestamp()) 
        );
        $data['main_view'] = $this->load->view('earnings/mresource_earnings/site_earning', $main_data, TRUE);

        $this->template->write_view('dashboard_wraper', $data);
        $this->template->render();
    }

    function user_earnings() {

        $data['sidebar_view'] = $this->load->view('dashboard_nav_sidebar', $this->get_sidebar_data('teacher_earnings'), TRUE);
        $main_data = array(
            'title' => 'รายได้ของสมาชิกในเว็บไซต์'
        );
        $data['main_view'] = $this->load->view('earnings/mresource_earnings/user_earnings', $main_data, TRUE);

        $this->template->write_view('dashboard_wraper', $data);
        $this->template->render();
    }

    /**
     * รายได้ของสมาชิกเว็บไซต์  โดยรวม
     * แสดงรายชื่อของสมาชิกที่มีรายได้ ตามช่วงเวลาที่กำหนด
     */
    function users_earnings() {

        $data['sidebar_view'] = $this->load->view('dashboard_nav_sidebar', $this->get_sidebar_data('teacher_earnings'), TRUE);
        $main_data = array(
            'title' => 'รายได้ของสมาชิกในเว็บไซต์'
        );
        $data['main_view'] = $this->load->view('earnings/mresource_earnings/user_earnings_details', $main_data, TRUE);

        $this->template->write_view('dashboard_wraper', $data);
        $this->template->render();
    }

    /**
     * รายได้ของสมาชิกเว็บไซต์ แยกตามบุคคล
     * แสดงรายชื่อของสมาชิกที่มีรายได้ ตามช่วงเวลาที่กำหนด
     */
    function user_earnings_details() {

        $data['sidebar_view'] = $this->load->view('dashboard_nav_sidebar', $this->get_sidebar_data('teacher_earnings'), TRUE);
        $main_data = array(
            'title' => 'รายได้ของ : ชื่อผู้ใช้'
        );
        $data['main_view'] = $this->load->view('earnings/mresource_earnings/user_earnings_details', $main_data, TRUE);

        $this->template->write_view('dashboard_wraper', $data);
        $this->template->render();
    }

    /**
     * รายการเบิกเงินของสมาชิก
     */
    function users_withdraw() {

        $data['sidebar_view'] = $this->load->view('dashboard_nav_sidebar', $this->get_sidebar_data('users_withdraw'), TRUE);
        $main_data = array(
            'title' => 'การเบิกเงิน'
        );
        $data['main_view'] = $this->load->view('earnings/mresource_earnings/users_withdraw', $main_data, TRUE);

        $this->template->write_view('dashboard_wraper', $data);
        $this->template->render();
    }

    function revert_date($date = '') {
        $result = '';
        if ($date == '') {
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
            $result = '01/' . $now_month . '/' . $now_year;
        } else {
            
        }
        return $result;
    }

}
