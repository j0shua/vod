<?php

/**
 * @property ajax_search_model $ajax_search_model
 */
class ajax_search extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('core/ajax_search_model');
    }

    function user() {
        $value = $this->ajax_search_model->user($this->input->post(NULL));
        echo json_encode($value);
    }

    function user_not_have_disk_quota() {
        
        $value = $this->ajax_search_model->user_not_have_disk_quota($this->input->post(NULL));
        echo json_encode($value);
    }

}