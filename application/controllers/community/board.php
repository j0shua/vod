<?php

/**
 * @property board_model  $board_model
 */
class board extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('community/board_model');
        $this->load->helper('time');
    }

    function type($board_type_id = 3) {
        $board_data = $this->board_model->find_all(1, '', '', 10, 'p_id', 'desc', $board_type_id);
        //$board_data['title'] = anchor('community/board/' . $board_type_id, 'Board') . ' | ' . $board_data['title'];
        $board_data['write_url'] = site_url('community/board/write/' . $board_type_id);
        $this->template->write_view('community/board/display_main', $board_data);
        $this->template->render();
    }

    function write($board_type_id = 3) {
        if ($this->auth->is_login()) {
            $cancel_link = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : site_url();
            $data = array(
                'form_title' => 'ตั้งกระทู้',
                'form_action' => site_url('community/board/do_write'),
                'form_data' => $this->board_model->get_write_form_data($board_type_id),
                'cancel_link' => $cancel_link
            );

            $this->template->load_markitup_bbcode();
            $this->template->write_view('community/board/write_form', $data);
            $this->template->render();
        } else {
            $data = array(
                'time' => 10,
                'url' => site_url(),
                'heading' => 'ต้อง ลงชื่อเข้าใช้ก่อน',
                'message' => '<p>ลงชื่อเข้าใช้ก่อน</p>');
            $this->load->view('refresh_page', $data);
        }
    }

    function do_write() {
        $post_data = $this->input->post('data');
        if ($this->board_model->save($post_data)) {
            $data = array(
                'time' => 0,
                'url' => site_url('community/board/type/' . $post_data['board_type_id']),
                'heading' => 'ตั้งกระทู้เสร็จ',
                'message' => '<p>ตั้งกระทู้เสร็จ</p>'
            );
        } else {
            $data = array(
                'time' => 10,
                'url' => site_url(),
                'heading' => 'ผิดพลาด',
                'message' => '<p>ผิดพลาด</p>'
            );
        }
        $this->load->view('refresh_page', $data);
    }

    function do_reply() {
        $post_data = $this->input->post('data');
        $this->input->post('data');
        $url = site_url('community/board/view/' . $post_data['p_id_parent']);
        if ($this->board_model->save($post_data)) {
            $data = array(
                'time' => 0,
                'url' => $url,
                'heading' => 'ตอบกลับเสร็จ',
                'message' => '<p>ตอบกลับเสร็จ</p>'
            );
        } else {
            $data = array(
                'time' => 0,
                'url' => $url,
                'heading' => 'ผิดพลาด',
                'message' => '<p>ผิดพลาด</p>'
            );
        }
        $this->load->view('refresh_page', $data);
    }

    function view($p_id) {
        $this->load->helper('time');
        $this->board_model->plus_view($p_id);
        $data = array(
            'form_action' => site_url('community/board/do_reply'),
            'form_data' => $this->board_model->get_reply_form_data($p_id)
        );
        $data['post_data'] = $this->board_model->get_post_data($p_id);

        $data['reply_data'] = $this->board_model->get_reply_data($p_id);
        //print_r($data['reply_data'] );
        if (!$data['reply_data']) {
            $data['reply_data'] = array();
        }
        $this->template->load_markitup_bbcode();
        $this->template->title($data['post_data']['title']);
        $this->template->write_view('community/board/view_post', $data);
        $this->template->render();
    }

    function delete($p_id) {
        $result = $this->board_model->delete($p_id);
        if ($result && $this->auth->is_admin()) {
            if ($result['p_id_parent'] > 0) {
                $url = site_url('community/board/view/' . $result['p_id_parent']);
            } else {
                $url = site_url('community/board/type/' . $result['board_type_id']);
            }
            $data = array(
                'time' => 0,
                'url' => $url,
                'heading' => 'ลบเสร็จสิ้น',
                'message' => '<p>ลบเสร็จสิ้น</p>'
            );
        } else {
            $data = array(
                'time' => 0,
                'url' => $url,
                'heading' => 'ไม่สามารถลบข้อมูลได้',
                'message' => '<p>ไม่สามารถลบข้อมูลได้</p>'
            );
        }
        $this->load->view('refresh_page', $data);
    }

}