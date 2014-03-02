<?php

/**
 * Description of resource_browser
 *
 * @author lojorider
 * @property resource_browser_model $resource_browser_model
 */
class resource_browser extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->auth->access_limit($this->auth->permis_resource);
        $this->load->model('resource/resource_browser_model');
        $this->load->model('core/ddoption_model');
    }

//    function iframe() {
//        $data = array();
//        $this->template->load_flexgrid();
//        $this->template->write_view('resource/resource_browser/dycontent_iframe', $data);
//        $this->template->temmplate_name('normal');
//        $this->template->script_var(
//                array('ajax_resource_list_url' => site_url('resource/resource_browser/ajax_resource_listss'))
//        );
//        $this->template->render();
//    }
//
//    function ajax_resource_list() {
//        $a = $this->resource_browser_model->find_all($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query', TRUE), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE));
//        echo json_encode($a);
//    }

    function dycontent_iframe() {
        $data = array();
        $this->template->load_flexgrid();
        $this->template->write_view('resource/resource_browser/dycontent_iframe', $data);
        $this->template->temmplate_name('normal');
        $this->template->script_var(
                array('ajax_resource_list_url' => site_url('resource/resource_browser/ajax_dycontent_list'))
        );
        $this->template->render();
    }

    function ajax_dycontent_list() {
        $a = $this->resource_browser_model->dycontent_find_all($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query', TRUE), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE));
        echo json_encode($a);
    }

}