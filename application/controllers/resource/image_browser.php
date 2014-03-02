<?php

/**
 * Description of image_browser
 *
 * @author lojorider
 * @property image_browser_model $image_browser_model
 */
class image_browser extends CI_Controller {

    function __construct() {
        parent::__construct();
      $this->auth->access_limit($this->auth->permis_resource);
        $this->load->model('resource/image_browser_model');
        $this->load->model('resource/image_manager_model');
        $this->load->model('core/ddoption_model');
    }

    function iframe($input_form_id) {
        $data['input_form_id'] = $input_form_id;
        $data['upload_doc_url'] = site_url('resource/doc_upload');
        $this->template->load_flexgrid();
        $this->template->write_view('resource/image_browser/iframe', $data);
        $this->template->temmplate_name('normal');
        $this->template->render();
    }

    function ajax_image_list() {
        $a = $this->image_manager_model->find_all($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query', TRUE), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE));
        echo json_encode($a);
    }

}