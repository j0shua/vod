<?php

/**
 * @author lojorider <lojorider@gmail.com>
 * @property coupon_model $coupon_model
 * @property topup_menu_model $topup_menu_model
 */
class coupon_manager extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('topup/coupon_model');
        $this->load->model('topup/topup_menu_model');
    }

    function index() {
        $data['title'] = 'จัดการคูปอง';
        $data['grid_menu'] = array(
            array('url' => site_url('utopup/coupon_manager/add_book_coupon'), 'title' => 'สร้างรหัสหนังสือ', 'extra' => ''),
            array('url' => site_url('utopup/coupon_manager/add_coupon'), 'title' => 'สร้างรหัสคูปอง', 'extra' => ''),
        );
        $this->template->script_var(array(
            'ajax_grid_url' => site_url('utopup/coupon_manager/ajax_mian_grid')
        ));
        $data['main_side_menu'] = $this->topup_menu_model->main_side_menu('coupon_manager');
        $this->template->load_flexgrid();
        $this->template->application_script('topup/coupon_manager/main_grid.js');
        $this->template->write_view('topup/coupon_manager/main_grid', $data);
        $this->template->render();
    }

    function ajax_mian_grid() {
        $a = $this->coupon_model->find_all_coupon($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query', TRUE), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE));
        echo json_encode($a);
    }

    function coupon_fail() {
        $data['title'] = 'จัดการคูปองที่บันทึกผิด';
        $data['main_side_menu'] = $this->topup_menu_model->main_side_menu('coupon_fail');
        $this->template->script_var(array(
            'ajax_grid_url' => site_url('utopup/coupon_manager/ajax_coupon_fail')
        ));
        $this->template->load_flexgrid();
        $this->template->application_script('topup/coupon_manager/grid_coupon_fail.js');
        $this->template->write_view('topup/coupon_manager/grid_coupon_fail', $data);
        $this->template->render();
    }

    function ajax_coupon_fail() {
        $a = $this->coupon_model->find_all_coupon_fail($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query', TRUE), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE));
        echo json_encode($a);
    }

    function add_book_coupon() {
        $data = array(
            'title' => 'สร้างรหัสหนังสือ',
            'form_action' => site_url('utopup/coupon_manager/do_add_book_coupon'),
            'cancel_link' => site_url('utopup/coupon_manager/')
        );
        $data['form_data'] = array('coupon_type' => 'bookcode', 'money' => 260);
        $data['script_var'] = array();
        $this->template->load_typeonly();
        $this->template->application_script('topup/coupon_manager/book_coupon_input.js');
        $this->template->write_view('topup/coupon_manager/book_coupon_input', $data);
        $this->template->render();
    }

    function do_add_book_coupon() {
        $data = $this->input->post('data');


        $this->coupon_model->gen_coupon_code($data['amount'], $data['money'], $data['coupon_type']);
        $data = array(
            'time' => 1,
            'url' => site_url('utopup/coupon_manager'),
            'heading' => 'สร้างคูปองเสร็จสิ้น',
            'message' => '<p>สร้างคูปองเสร็จสิ้น</p>'
        );
        $this->load->view('refresh_page', $data);
    }

    function add_coupon() {
        $data = array(
            'title' => 'สร้างคูปอง',
            'form_action' => site_url('utopup/coupon_manager/add_coupon'),
            'cancel_link' => site_url('utopup/coupon_manager/')
        );
        $data['form_data'] = array('cid' => '', 'coupon_code' => '');
        $data['script_var'] = array();
        $this->template->load_typeonly();
        $this->template->application_script('topup/coupon_manager/coupon_input.js');
        $this->template->write_view('topup/coupon_manager/coupon_input', $data);
        $this->template->render();
    }

    function delete_coupon_fail($cfid) {
        $this->coupon_model->delete_coupon_fail($cfid);
        $data = array(
            'time' => 1,
            'url' => site_url('utopup/coupon_manager/coupon_fail'),
            'heading' => 'ลบข้อมูล',
            'message' => '<p>ลบข้อมูลเสร็จสิ้น</p>'
        );
        $this->load->view('refresh_page', $data);
    }

    function fail_topup($cfid) {
        $data = array(
            'title' => 'เติมเงินสำหรับคูปองที่ผิดพลาด',
            'form_action' => site_url('utopup/coupon_manager/do_fail_topup'),
            'cancel_link' => site_url('utopup/coupon_manager/coupon_fail')
        );
        $fail_topup_data = $this->coupon_model->get_fail_topup_data($cfid);
        $data['form_data'] = $fail_topup_data;
        $user_detail_data = $this->coupon_model->get_user_detail_data($fail_topup_data['uid']);
        $data['form_data']['user_detail_data'] = $user_detail_data;
        $this->template->script_var(array(
            'ajax_check_coupon_url' => site_url('utopup/coupon_manager/ajax_check_coupon')
        ));
        $this->template->application_script('topup/coupon_manager/fail_topup_form.js');
        $this->template->load_typeonly();
        $this->template->load_showloading();
        $this->template->write_view('topup/coupon_manager/fail_topup_form', $data);
        $this->template->render();
    }

    function ajax_check_coupon() {
        $a = array(
            'success' => false
        );
        echo json_encode($a);
    }

    function do_fail_topup() {
        $result = $this->coupon_model->fail_topup($this->input->post('form_data'));
        if ($result) {
            $data = array(
                'time' => 1,
                'url' => site_url('utopup/coupon_manager/coupon_fail'),
                'heading' => 'ผลการใช้คูปอง',
                'message' => '<p>ใช้คูปองเสร็จสิ้น</p>'
            );
        } else {
            $data = array(
                'time' => 1,
                'url' => site_url('utopup/coupon_manager/coupon_fail'),
                'heading' => 'ผลการใช้คูปอง',
                'message' => '<p>ไม่สามารถใช้คูปปองเติมเงินได้</p>'
            );
        }

        $this->load->view('refresh_page', $data);
    }

    function dlcoupon($time_stamp) {
        $result = $this->coupon_model->get_coupon_data_by_time($time_stamp);
        $this->load->helper('download');
//        $list = array(
//            array('aaa', 'bbb', 'ccc', 'dddd'),
//            array('123', '456', '789'),
//            array('"aaa"', '"bbb"')
//        );

        $fp = fopen('file.csv', 'w');
        $list = array();
        $i = 0;
        $j = 0;
        foreach ($result as $row) {
            $list[$i][] = $row['coupon_code'];
            if ($j > 6) {
                $j = 0;
                $i++;
            } else {
                $j++;
            }
        }
        foreach ($list as $fields) {
            fputcsv($fp, $fields);
        }

        fclose($fp);
        force_download_file('code.csv', 'file.csv');
    }

}
