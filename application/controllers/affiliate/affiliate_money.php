<?php

/**
 * @property  affiliate_money_model $affiliate_money_model
 */
class affiliate_money extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('affiliate/affiliate_money_model');
    }

    function index($date = '') {
        $date = explode('-', $date);
        if (count($date) == 2) {
            $data['date_query'] = 'from=' . $date[0] . '&to=' . $date[1];
            $data['date_from_stamp'] = mktime(0, 0, 0, substr($date[0], 4, 2), substr($date[0], 6, 2), substr($date[0], 0, 4));
            $data['date_to_stamp'] = mktime(0, 0, 0, substr($date[1], 4, 2), substr($date[1], 6, 2), substr($date[1], 0, 4));
        } else {
            $data['date_from_stamp'] = mktime(0, 0, 0, date('m'), 1, date('Y'));
            $data['date_to_stamp'] = time();
            $data['date_query'] = 'from=' . date('Ymd', $data['date_from_stamp']) . '&to=' . date('Ymd');
        }
        $this->load->helper('form');
        //$this->template->load_showloading();
        $this->template->load_flexgrid();
        $this->template->application_script('affiliate/affiliate_money/main_grid.js');
        $data['site_menu'] = $this->load->view('affiliate/affiliate_menu', array('active' => ''), TRUE);
        $data['ajax_grid_url'] = site_url('affiliate/affiliate_money/ajax_affiliate_user');

        $this->template->write_view('affiliate/affiliate_money/main_grid', $data);
        $this->template->render();
    }

    function ajax_affiliate_user() {
        $a = $this->affiliate_money_model->find_all($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query', TRUE), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE));
        echo json_encode($a);
    }

    function user_detail($uid) {
        if (!$this->affiliate_money_model->is_downline($uid)) {
            $data = array(
                'time' => 5,
                'url' => site_url(),
                'heading' => ' ไม่สามารถเข้าใช้บริการส่วนนี้ได้',
                'message' => '<p>กรุณาเขาในทางที่ถูกต้อง</p>'
            );
            $this->load->view('refresh_page', $data);
            return;
        }
        $this->load->model('user_model');
        $data = array(
            'form_data' => $this->user_model->get_account_data($uid)
        );
        $this->template->write_view('affiliate/user_detail', $data);
        $this->template->render();
    }

}