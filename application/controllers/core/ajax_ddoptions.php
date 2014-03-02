<?php

/**
 * @property ajax_ddoptions_model $ajax_ddoptions_model
 */
class ajax_ddoptions extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('core/ajax_ddoptions_model');
    }

    function get_subject_options() {
        $value = $this->ajax_ddoptions_model->get_subject_options($this->input->post('la_id'));
        echo json_encode($value);
    }
    
    function get_chapter_options() {
        $value = $this->ajax_ddoptions_model->get_chapter_options($this->input->post('subj_id'));
        echo json_encode($value);
    }

}