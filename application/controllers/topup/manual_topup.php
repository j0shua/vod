<?php

/**
 * @author lojorider <lojorider@gmail.com>
 * @property manual_topup_model $manual_topup_model
 * @property topup_menu_model $topup_menu_model
 */
class manual_topup extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('topup/manual_topup_model');
        $this->load->model('topup/topup_menu_model');
    }

    function index() {
        if (!$this->auth->is_login()) {
            exit("Permission Denied.");
        }
        $this->load->helper('form');
        $data['title'] = 'การเติมเงิน';
        $data['grid_menu'] = array(
            array('url' => site_url('topup/manual_topup/quick_topup'), 'title' => 'เติมเงินแบบเร็ว', 'extra' => '')
        );
        $data['main_side_menu'] = $this->topup_menu_model->main_side_menu('topup_manager');
        $this->template->script_var(array(
            'ajax_grid_url' => site_url('topup/manual_topup/ajax_topup_list')
        ));
        $this->template->load_showloading();
        $this->template->load_flexgrid();
        $this->template->application_script('topup/manual_topup/topup_main_grid.js');
        $this->template->write_view('topup/manual_topup/topup_main_grid', $data);
        $this->template->render();
    }

    function ajax_topup_list() {
        $a = $this->manual_topup_model->topup_find_all($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query', TRUE), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE));
        echo json_encode($a);
    }

    //==========================================================================
    //  จัดการ    
    //==========================================================================

    function informant_manager() {
        if (!$this->auth->is_login()) {
            exit("Permission Denied.");
        }
        $this->load->helper('form');
        $this->template->load_showloading();
        $this->template->load_flexgrid();
        $this->template->application_script('topup/manual_topup/informant_main_grid.js');
        $data['title'] = 'การโอนเงินเติม';
        $data['main_side_menu'] = $this->topup_menu_model->main_side_menu('informant_manager');
        $this->template->script_var(array(
            'ajax_grid_url' => site_url('topup/manual_topup/ajax_informant_list')
        ));

        $this->template->write_view('topup/manual_topup/informant_main_grid', $data);
        $this->template->render();
    }

    function ajax_informant_list() {
        $a = $this->manual_topup_model->inform_find_all($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query', TRUE), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE));
        echo json_encode($a);
    }

    /**
     * ฟอร์มแจ้งการโอนเงิน
     */
    function inform_transfer() {
        if (!$this->auth->is_login()) {
            $data = array(
                'time' => 5,
                'url' => site_url('user/login'),
                'heading' => ' ไม่สามารถเข้าใช้บริการได้',
                'message' => '<p>โปรด Login ก่อน</p>'
            );
            $this->load->view('refresh_page', $data);
            return;
        }
        $this->auth->access_limit($this->auth->permis_topup);
        $this->template->load_jquery_ui_timepicker();
        $this->template->load_typeonly();
        $this->template->load_meio_mask();
        $data['form_action'] = site_url('topup/manual_topup/to_inform_transfer');
        if (isset($_SERVER['HTTP_REFERER'])) {
            $data['cancel_link'] = $_SERVER['HTTP_REFERER'];
        } else {
            $data['cancel_link'] = site_url();
        }

        $this->template->write_view('topup/manual_topup/inform_transfer_form', $data);
        $this->template->render();
    }

    /*
     * ทำการแจ้งการโอนเงิน
     */

    function to_inform_transfer() {
        $this->auth->access_limit($this->auth->permis_topup);
//        $this->manual_topup_model->inform_transfer($post);
        $this->manual_topup_model->inform_transfer($this->input->post('data'));
        $data = array(
            'time' => 5,
            'url' => site_url(''),
            'heading' => 'แจ้งการโอนเงินเสร็จสิ้น',
            'message' => '<p>แจ้งการโอนเงินเสร็จสิ้น</p>'
        );
        $this->load->view('refresh_page', $data);
    }

    function quick_topup() {
        $this->auth->access_limit($this->auth->permis_topup_manager);
        $this->template->load_jquery_ui_timepicker();
        $this->template->load_typeonly();
        $this->template->load_meio_mask();
        $data['form_action'] = site_url('topup/manual_topup/to_quick_topup');
        $data['cancel_link'] = site_url('topup/manual_topup');
        $this->template->write_view('topup/manual_topup/quick_topup_form', $data);
        $this->template->render();
    }

    function to_quick_topup() {
        $this->auth->access_limit($this->auth->permis_topup_manager);
        $this->manual_topup_model->quick_topup($this->input->post('data'));
        $data = array(
            'time' => 5,
            'url' => site_url('topup/manual_topup'),
            'heading' => 'เติมเงินเสร็จสิ้น',
            'message' => '<p>เติมเงินเสร็จสิ้น</p>'
        );
        $this->load->view('refresh_page', $data);
    }

    function infrom_topup($mt_id) {
        $this->auth->access_limit($this->auth->permis_topup_manager);
        $this->template->load_jquery_ui_timepicker();
        $this->template->load_typeonly();
        $this->template->load_meio_mask();
        $data['inform_data'] = $this->manual_topup_model->get_inform_data($mt_id);
        $data['form_action'] = site_url('topup/manual_topup/to_infrom_topup');
        $data['cancel_link'] = site_url('topup/manual_topup');
        $this->template->write_view('topup/manual_topup/infrom_topup_form', $data);
        $this->template->render();
    }

    function to_infrom_topup() {
        if (!$this->auth->is_login()) {
            exit("Permission Denied.");
        }
        $this->manual_topup_model->infrom_topup($this->input->post('data'));
        $data = array(
            'time' => 5,
            'url' => site_url('topup/manual_topup'),
            'heading' => 'เติมเงินเสร็จสิ้น',
            'message' => '<p>เติมเงินเสร็จสิ้น</p>'
        );
        $this->load->view('refresh_page', $data);
    }

    function no_transfer($mt_id) {
        $this->auth->access_limit($this->auth->permis_topup_manager);
        $data['inform_data'] = $this->manual_topup_model->get_inform_data($mt_id);
        $data['form_action'] = site_url('topup/manual_topup/do_no_transfer');
        $data['cancel_link'] = site_url('topup/manual_topup');
        $this->template->write_view('topup/manual_topup/no_transfer_form', $data);
        $this->template->render();
    }

    function do_no_transfer() {
        $this->auth->access_limit($this->auth->permis_topup_manager);
        $this->manual_topup_model->no_transfer($this->input->post('data'));
        $data = array(
            'time' => 1,
            'url' => site_url('topup/manual_topup'),
            'heading' => 'ลบการโอนเงิน',
            'message' => '<p>ลบการโอนเงินเสร็จสิ้น</p>'
        );
        $this->load->view('refresh_page', $data);
    }

    function ajax_search_user() {

        $value = $this->manual_topup_model->search_user($this->input->post(NULL, TRUE));
        echo json_encode($value);
    }

}
