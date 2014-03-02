<?php

/**
 * @author lojorider <lojorider@gmail.com>
 * @property truemoney_model $truemoney_model
 */
class truemoney extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('topup/truemoney_model');
        $this->load->model('topup/topup_menu_model');
    }

    function index() {
         
        $this->load->helper('form');
        $data['title'] = 'การเติมเงิน';
        $data['grid_menu'] = array(
            array('url' => site_url('page/truemoney_topup'), 'title' => 'ไปหน้าเติมเงิน', 'extra' => '')
        );
        $data['main_side_menu'] = $this->topup_menu_model->main_side_menu('truemoney');
        $this->template->script_var(array(
            'ajax_grid_url' => site_url('utopup/truemoney/ajax_topup_list')
        ));
        $this->template->load_showloading();
        $this->template->load_flexgrid();
        $this->template->application_script('topup/truemoney/topup_main_grid.js');
        $this->template->write_view('topup/truemoney/topup_main_grid', $data);
        $this->template->render();
    }

    function ajax_topup_list() {
        $a = $this->truemoney_model->find_all($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query', TRUE), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE));
        echo json_encode($a);
    }
}
