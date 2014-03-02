<?php

/**
 * @property ajax_autocomplete_model $ajax_autocomplete_model
 */
class ajax_autocomplete extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('core/ajax_autocomplete_model');
    }

    function get_chapter() {
        echo $this->ajax_autocomplete_model->get_chapter($this->input->get('term'), $this->input->get('subj_id'));
    }

    function get_sub_chapter() {
        echo $this->ajax_autocomplete_model->get_sub_chapter($this->input->get('term'), $this->input->get('chapter_id'));
    }

    function get_school_name() {
        echo $this->ajax_autocomplete_model->get_school_name($this->input->get('term'));
    }

    function get_teacher_school_name() {
        echo $this->ajax_autocomplete_model->get_user_school_name($this->input->get('term'), 3);
    }

    function get_teacher_full_name() {
        echo $this->ajax_autocomplete_model->get_user_full_name($this->input->get('term'), 3); 
    }
    function get_teacher_full_name_ref_dycontent() {
        echo $this->ajax_autocomplete_model->get_user_full_name_ref_dycontent($this->input->get('term'), 3); 
    }
    function get_teacher_full_name_ref_sheet() {
        echo $this->ajax_autocomplete_model->get_user_full_name_ref_sheet($this->input->get('term'), 3); 
    }

    function get_student_full_name() {
        echo $this->ajax_autocomplete_model->get_user_full_name($this->input->get('term'), 2);
    }

}