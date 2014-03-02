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
            array('url' => site_url('topup/coupon_manager/add_book_coupon'), 'title' => 'สร้างรหัสหนังสือ', 'extra' => ''),
            array('url' => site_url('topup/coupon_manager/add_coupon'), 'title' => 'สร้างรหัสคูปอง', 'extra' => ''),
        );
        $this->template->script_var(array(
            'ajax_grid_url' => site_url('topup/coupon_manager/ajax_mian_grid')
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
            'ajax_grid_url' => site_url('topup/coupon_manager/ajax_coupon_fail')
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
            'form_action' => site_url('topup/coupon_manager/add_book_coupon'),
            'cancel_link' => site_url('topup/coupon_manager/')
        );
        $data['form_data'] = array('cid' => '', 'coupon_code' => '');
        $data['script_var'] = array();
        $this->template->load_typeonly();
        $this->template->application_script('topup/coupon_manager/book_coupon_input.js');
        $this->template->write_view('topup/coupon_manager/book_coupon_input', $data);
        $this->template->render();
    }

    function add_coupon() {
        $data = array(
            'title' => 'สร้างคูปอง',
            'form_action' => site_url('topup/coupon_manager/add_coupon'),
            'cancel_link' => site_url('topup/coupon_manager/')
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
            'url' => site_url('topup/coupon_manager/coupon_fail'),
            'heading' => 'ลบข้อมูล',
            'message' => '<p>ลบข้อมูลเสร็จสิ้น</p>'
        );
        $this->load->view('refresh_page', $data);
    }

    function fail_topup($cfid) {
        $data = array(
            'title' => 'เติมเงินสำหรับคูปองที่ผิดพลาด',
            'form_action' => site_url('topup/coupon_manager/do_fail_topup'),
            'cancel_link' => site_url('topup/coupon_manager/coupon_fail')
        );
        $fail_topup_data = $this->coupon_model->get_fail_topup_data($cfid);
        $data['form_data'] = $fail_topup_data;
        $user_detail_data = $this->coupon_model->get_user_detail_data($fail_topup_data['uid']);
        $data['form_data']['user_detail_data'] = $user_detail_data;
        $this->template->script_var(array(
            'ajax_check_coupon_url' => site_url('topup/coupon_manager/ajax_check_coupon')
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
                'url' => site_url('topup/coupon_manager/coupon_fail'),
                'heading' => 'ผลการใช้คูปอง',
                'message' => '<p>ใช้คูปองเสร็จสิ้น</p>'
            );
        } else {
            $data = array(
                'time' => 1,
                'url' => site_url('topup/coupon_manager/coupon_fail'),
                'heading' => 'ผลการใช้คูปอง',
                'message' => '<p>ไม่สามารถใช้คูปปองเติมเงินได้</p>'
            );
        }

        $this->load->view('refresh_page', $data);
    }

    function download_coupon($start, $limit) {
        $this->load->helper('download');
        $this->load->library("excel");
        $option = array('setWidth' => 12);
        $c = 8;
        $r = 23;
        //$start = 2859;
        //$start = 6559;
        $this->db->where('cid >=', $start);
        $this->db->limit($limit);
        $q = $this->db->get('b_coupon');
        $data = array();
        $j = 1;
        $i = 1;
        foreach ($q->result_array() as $row) {
            $data[$j][$i] = $row['coupon_code'];
            if ($i == $c) {
                $j++;
                $i = 1;
            } else {
                $i++;
            }
        }
        $file_name = 'filename.xlsx';
        $i = 0;

        $sheet_data = array();
        $count_data = count($data);
        $title_index = 1;
        $this->excel->getDefaultStyle()
                ->getNumberFormat()
                ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        foreach ($data as $row) {
            $sheet_data[] = $row;
            $i++;
            if ($i % $r == 0) {
                $this->excel->add_sheet($sheet_data, FALSE, 'หน้า ' . $title_index, $option);
                $title_index++;
                $sheet_data = array();
            } else if ($i == $count_data) {
                $this->excel->add_sheet($sheet_data, FALSE, 'หน้า ' . $title_index, $option);
            }
        }
        //$this->excel->add_sheet($data, FALSE, 'ต้นฉบับรวม');
        $this->excel->save('temp/' . $file_name);
        force_download_file($file_name, 'temp/' . $file_name);
    }

}
