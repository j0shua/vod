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
        $data = $this->resource_earnings_model->get_earnings_data($this->auth->uid());
        print_r($data);
//        $this->load->model('main_side_menu_model');
//        $this->template->load_showloading(); 
//
//        $data = array();
//        $data['title'] = 'รายงานรายได้';
//        $data['withdraw_url'] = site_url('earnings/resource_earnings/withdraw');
//        $data['withdraw_history'] = site_url('earnings/resource_earnings/withdraw_history');
//
//        $data['date_from_stamp'] = time();
//        $data['date_to_stamp'] = time();
//        $data['main_side_menu'] = $this->main_side_menu_model->study('resource_earnings');
//        $this->template->write_view('earnings/resource_earnings/main_report', $data);
//        $this->template->render();
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

        $data = array();
        $data['title'] = 'เบิกเงิน';
        $data['withdraw_url'] = site_url('earnings/resource_earnings/withdraw');
        $data['withdraw_history'] = site_url('earnings/resource_earnings/withdraw_history');

        $data['date_from_stamp'] = time();
        $data['date_to_stamp'] = time();
        $data['main_side_menu'] = $this->main_side_menu_model->study('resource_earnings');
        $this->template->write_view('earnings/resource_earnings/withdraw', $data);
        $this->template->render();
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

}
