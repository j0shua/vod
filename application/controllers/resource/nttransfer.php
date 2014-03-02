<?php

/**
 * Description of nttransfer
 *
 * @author lojorider
 * @property nttransfer_model $nttransfer_model

 */
class nttransfer extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('resource/nttransfer_model');
    }

    function index() {
        $this->load->helper('form');
        $this->template->load_showloading();
        $this->template->load_flexgrid();
        $this->template->application_script('resource/nttransfer/taxonomy_main_grid.js');

        $data['title'] = 'noobthink Transfer';
        $data['grid_menu'] = array(
//            array('url' => site_url('house/u/' . $this->auth->uid()), 'title' => 'หน้าเพจของตน', 'extra' => ''),
//            array('url' => site_url('resource/taxonomy_manager/add'), 'title' => 'เพิ่มชุดวิดีโอ', 'extra' => ''),
//            array('url' => site_url('house/u/' . $this->auth->uid()), 'title' => 'ดูชุดวิดีโอ', 'extra' => 'target="_blank"')
        );

        //$data['main_side_menu'] = $this->main_side_menu_model->study('taxonomy_manager');
        $script_var['ajax_grid_url'] = site_url('resource/nttransfer/ajax_taxonomy_list');
        
        $this->template->script_var($script_var);
        $this->template->write_view('resource/nttransfer/taxonomy_main_grid', $data);
        $this->template->render();
    }

    function ajax_taxonomy_list() {
        $a = $this->nttransfer_model->taxonomy_find_all($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query', TRUE), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE));
        echo json_encode($a);
    }

    function taxonomy_sub($tid_parent) {
        $this->load->helper('form');
        $this->template->load_showloading();
        $this->template->load_flexgrid();
        $this->template->application_script('resource/nttransfer/taxonomy_sub_grid.js');

        $data['title'] = 'noobthink Transfer';
        $data['grid_menu'] = array(
//            array('url' => site_url('house/u/' . $this->auth->uid()), 'title' => 'หน้าเพจของตน', 'extra' => ''),
//            array('url' => site_url('resource/taxonomy_manager/add'), 'title' => 'เพิ่มชุดวิดีโอ', 'extra' => ''),
//            array('url' => site_url('house/u/' . $this->auth->uid()), 'title' => 'ดูชุดวิดีโอ', 'extra' => 'target="_blank"')
        );

        //$data['main_side_menu'] = $this->main_side_menu_model->study('taxonomy_manager');
        $script_var['ajax_grid_url'] = site_url('resource/nttransfer/ajax_taxonomy_sub_list/'.$tid_parent);
        $this->template->script_var($script_var);
        $this->template->write_view('resource/nttransfer/taxonomy_sub_grid', $data);
        $this->template->render();
    }

    function ajax_taxonomy_sub_list($tid_parent) {
        $a = $this->nttransfer_model->taxonomy_sub_find_all($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query', TRUE), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE),$tid_parent);
        echo json_encode($a);
    }
    function transfer($tid_parent){
        $this->nttransfer_model->transfer($tid_parent);
        
    }
    function transfer_video(){
        $this->nttransfer_model->transfer_video();
    }
    function update_time(){
        $this->nttransfer_model->update_time();
    }
    

}