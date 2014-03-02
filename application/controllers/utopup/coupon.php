<?php

/**
 * @author lojorider <lojorider@gmail.com>
 * @property coupon_model $coupon_model
 */
class coupon extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('topup/coupon_model');
    }

    function use_coupon() {
        $data = array(
            'form_title' => 'กรอกรหัสหนังสือ หรือ รหัสคูปอง',
            'form_action' => site_url('utopup/coupon/do_use_coupon')
        );
        $this->template->write_view('topup/coupon/use_coupon_form', $data);
        $this->template->render();
    }

    function do_use_coupon() {
        $coupon_code = $this->input->post('coupon_code');
        $result = $this->coupon_model->use_coupon($this->auth->uid(), $coupon_code, 'normal');
        if ($result['success']) {
            $data = array(
                'time' => 5,
                'url' => site_url('user/account'),
                'heading' => 'ใช้คูปองเสร็จสิ้น',
                'message' => '<p>' . $result['message'] . '</p>'
            );
        } else {
            $data = array(
                'time' => 5,
                'url' => site_url('utopup/coupon/use_coupon'),
                'heading' => 'เกิดข้อผิดพลาด!',
                'message' => '<p class="error-text">' . $result['message'] . '</p>'
            );
        }

        $this->load->view('refresh_page', $data);
    }

    function gen($amount = 10) {
        $this->coupon_model->gen_coupon_code($amount, 260);
    }

    function csv() {
        $filename = 'running_coupon_code.csv';
        $this->load->helper('file');
        $this->load->helper('download');
        $q = $this->coupon_model->get_running_coupon_code();
        $str = '';
        foreach ($q as $r) {
            $str[] = implode(",", $r);
        }
        $string = implode("\n", $str);
        force_download($filename, $string);
    }

}
