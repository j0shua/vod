<?php

/**
 * @property bookplayer_model $bookplayer_model
 */
class bookplayer extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('play/bookplayer_model');
    }

    function search() {
        $data = array(
            'form_title' => 'ค้นหาวิดีโอ',
            'form_action' => site_url('play/bookplayer/do_search')
        );
        $this->template->write_view('play/bookplayer_search_form', $data);
        $this->template->render();
    }

    function do_search() {
        $search_text = $this->input->post('search_text');
        if (!$search_text) {
            $search_text = $this->input->get('search_text');
        }
        $result = $this->bookplayer_model->search($search_text);
        
        if ($result['found_count'] > 0) {
            redirect('v/' . $result['resource_id']);
        } else {
            $data = array(
                'time' => 2,
                'url' => site_url('play/bookplayer/search'),
                'heading' => 'ผลการค้นหา',
                'message' => '<p>ไม่สามารถค้นหาวิดีโอนี้ได้</p>'
            );
            $this->load->view('refresh_page', $data);
//            $this->template->write_view('play/bookplayer_search_result');
//            $this->template->render();
        }
    }

    function play_direct($search_text) {
//        $form_data = array(
//            'search_text' => $resource_code
//        );
        $result = $this->bookplayer_model->search($search_text);
        if ($result['found']) {
            redirect('v/' . $result['resource_id']);
        } else {
            $data = array(
                'time' => 2,
                'url' => site_url('play/bookplayer/search'),
                'heading' => 'ข้อผิดพลาด',
                'message' => '<p>ไม่มีวิดีโออยู่</p>'
            );
            $this->load->view('refresh_page', $data);
        }
    }

}