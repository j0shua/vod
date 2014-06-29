<?php

/**
 * Description of taxonomy_manager
 *
 * @author lojorider
 * @property taxonomy_manager_model $taxonomy_manager_model
 * @property resource_menu_model $resource_menu_model
 */
class taxonomy_manager extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->auth->access_limit($this->auth->permis_resource);
        $this->load->model('resource/taxonomy_manager_model');
        $this->load->model('core/ddoption_model');
        $this->load->model('main_side_menu_model');
    }

// ส่วน จัดการ ชุดวิดีโอการสอนหน้าเพจจัดแสดง =================================================
    function index() {
        $this->load->helper('form');
        $this->template->load_showloading();
        $this->template->load_flexgrid();
        $this->template->application_script('resource/taxonomy_manager/main_grid.js');

        $data['title'] = 'ชุดวิดีโอการสอนหน้าเพจ';
        $data['grid_menu'] = array(
            array('url' => site_url('house/u/' . $this->auth->uid()), 'title' => 'หน้าเพจของตน', 'extra' => ''),
            array('url' => site_url('resource/taxonomy_manager/add'), 'title' => 'เพิ่มชุดวิดีโอ', 'extra' => ''),
//            array('url' => site_url('house/u/' . $this->auth->uid()), 'title' => 'ดูชุดวิดีโอ', 'extra' => 'target="_blank"')
        );

        $data['main_side_menu'] = $this->main_side_menu_model->study('taxonomy_manager');
        $data['ajax_grid_url'] = site_url('resource/taxonomy_manager/ajax_list');
        $this->template->write_view('resource/taxonomy_manager/main_grid', $data);
        $this->template->render();
    }

    function ajax_list() {
        $a = $this->taxonomy_manager_model->find_all($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query', TRUE), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE));
        echo json_encode($a);
    }

    function add() {
        $this->load->helper('form');
        $data = array(
            'form_title' => 'เพิ่มชุดวิดีโอ',
            'form_action' => site_url('resource/taxonomy_manager/do_save'),
            'form_data' => $this->taxonomy_manager_model->get_form_data(),
            'cancel_link' => site_url('resource/taxonomy_manager'),
            'publish_options' => $this->ddoption_model->get_taxonomy_publish_options(),
            'weight_options' => $this->ddoption_model->get_weight_options()
        );

        $this->template->write_view('resource/taxonomy_manager/input_form', $data);
        $this->template->render();
    }

    function edit($tid) {
        if (!$this->taxonomy_manager_model->is_owner($tid)) {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/taxonomy_manager'),
                'heading' => 'ระบบผิดพลาด',
                'message' => '<p>ระบบผิดพลาด คุณไม่สามารถแก้ไขข้อมูลชุดนี้ได้</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $this->load->helper('form');
            $data = array(
                'form_title' => 'แก้ไขชุดวิดีโอ',
                'form_action' => site_url('resource/taxonomy_manager/do_save'),
                'form_data' => $this->taxonomy_manager_model->get_form_data($tid),
                'cancel_link' => site_url('resource/taxonomy_manager'),
                'delete_link' => site_url('resource/taxonomy_manager/delete/' . $tid),
                'publish_options' => $this->ddoption_model->get_taxonomy_publish_options(),
                'weight_options' => $this->ddoption_model->get_weight_options()
            );

            $this->template->application_script('resource/taxonomy_manager/input_form.js');
            $this->template->write_view('resource/taxonomy_manager/input_form', $data);
            $this->template->render();
        }
    }

    function do_save() {
        if ($this->taxonomy_manager_model->save($this->input->post('tid'), $this->input->post('data'))) {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/taxonomy_manager'),
                'heading' => 'แก้ไขข้อมูลเสร็จสิ้น',
                'message' => '<p>แก้ไขข้อมูลเสร็จสิ้น</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/taxonomy_manager'),
                'heading' => 'ระบบผิดพลาด',
                'message' => '<p>ระบบผิดพลาด คุณไม่สามารถแก้ไขข้อมูลชุดนี้ได้</p>'
            );
            $this->load->view('refresh_page', $data);
        }
    }

    function delete($tid) {
        if ($this->taxonomy_manager_model->count_sub($tid) > 0) {
            $message = '<p>หากคุณลบข้อมูล ชุดวิดีโอนี้ และ รวมถึงบทในชุดวิดีโอนี้ มันจะเป็นการลบข้อมูลอย่างถาวร</p>';
        } else {
            $message = '<p>หากคุณลบข้อมูล ชุดวิดีโอนี้ มันจะเป็นการลบข้อมูลอย่างถาวร</p>';
        }
        $data = array(
            'url' => site_url('resource/taxonomy_manager/do_delete/' . $tid),
            'cancel_url' => site_url('resource/taxonomy_manager'),
            'heading' => 'คุณต้องการลบข้อมูล video นี้ใช่ไหม',
            'message' => $message
        );

        $this->load->view('confirm_page', $data);
//        if ($this->taxonomy_manager_model->count_sub($tid) > 0) {
//            $data = array(
//                'time' => 5,
//                'url' => site_url('resource/taxonomy_manager/'),
//                'heading' => 'ไม่สามารถลบข้อมูล',
//                'message' => '<p>คุณไม่สามารถลบ กลุ่มนี้ เพราะยังมีกลุ่มย่อยอยู่ </p>'
//            );
//            $this->load->view('refresh_page', $data);
//        } else {
//            $data = array(
//                'url' => site_url('resource/taxonomy_manager/do_delete/' . $tid),
//                'cancel_url' => site_url('resource/taxonomy_manager'),
//                'heading' => 'คุณต้องการลบข้อมูล video นี้ใช่ไหม',
//                'message' => '<p>หากคุณลบข้อมูล video นี้แล้วระบบจะลบข้อมูล video อย่างถาวร</p>'
//            );
//
//            $this->load->view('confirm_page', $data);
//        }
    }

    function do_delete($tid) {

        if ($this->taxonomy_manager_model->delete($tid)) {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/taxonomy_manager/'),
                'heading' => 'ทำการลบข้อมูลเสร็จสิ้น',
                'message' => '<p>คุณไม่สามารถเรียกคืน กลุ่มคำ นี้ได้อีก</p>'
            );
        } else {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/taxonomy_manager/'),
                'heading' => 'การลบข้อมูลผิดพลาด',
                'message' => '<p>คุณไม่สามารถลบ กลุ่มคำ นี้ </p>'
            );
        }
        $this->load->view('refresh_page', $data);
    }

    function copy($tid) {
        $new_tid = $this->taxonomy_manager_model->copy($tid);
        if ($new_tid) {
            $data = array(
                'time' => 1,
                'url' => site_url('house/u/' . $this->auth->uid() . '/' . $new_tid),
                'heading' => 'ทำการลิ้งเสร็จสิ้น',
                'message' => '<p>ทำการลิ้งเสร็จสิ้น</p>'
            );
        } else {
            $data = array(
                'time' => 5,
                'url' => site_url('house/u/' . $this->auth->uid()),
                'heading' => 'ทำการลิ้งไม่ได้',
                'message' => '<p>ทำการลิ้งไม่ได้</p>'
            );
        }
        $this->load->view('refresh_page', $data);
    }

// ส่วน จัดการ ชุดวิดีโอ =================================================
    function sub($tid_parent) {
        $this->load->helper('form');
        $this->template->load_showloading();
        $this->template->load_flexgrid();
        $this->template->application_script('resource/taxonomy_manager/sub_grid.js');

        $data['title'] = 'จัดการบทในชุดวิดีโอ';
        $data['grid_menu'] = array(
            array('url' => site_url('resource/taxonomy_manager/'), 'title' => '<', 'extra' => ''),
            array('url' => site_url('resource/taxonomy_manager/sub_add/' . $tid_parent), 'title' => 'เพิ่มบท', 'extra' => ''),
//            array('url' => site_url('house/u/' . $this->auth->uid() . '/' . $tid_parent), 'title' => 'ดูชุดวิดีโอ', 'extra' => 'target="_blank"')
        );

        $data['main_side_menu'] = $this->main_side_menu_model->study('taxonomy_manager');
        $data['tid_parent'] = $tid_parent;
        $data['add_link'] = site_url('resource/taxonomy_manager/sub_add/' . $tid_parent);
        $data['main_link'] = site_url('resource/taxonomy_manager/');
        $data['title_parent'] = $this->taxonomy_manager_model->get_title($tid_parent);
        $data['ajax_grid_url'] = site_url('resource/taxonomy_manager/ajax_sub_list/' . $tid_parent);
        $this->template->write_view('resource/taxonomy_manager/sub_grid', $data);
        $this->template->render();
    }

    function ajax_sub_list($tid_parent) {
        $a = $this->taxonomy_manager_model->sub_find_all($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query', TRUE), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE), $tid_parent);
        echo json_encode($a);
    }

    function ajax_valid_data() {

        $a = $this->taxonomy_manager_model->valid_data($this->input->post('data'));
        echo json_encode($a);
    }

    function sub_add($tid_parent) {
        $this->load->helper('form');
        $data = array(
            'form_title' => 'เพิ่มบท',
            'form_action' => site_url('resource/taxonomy_manager/sub_do_save'),
            'form_data' => $this->taxonomy_manager_model->get_form_data(),
            'cancel_link' => site_url('resource/taxonomy_manager/sub/' . $tid_parent),
            'publish_options' => $this->ddoption_model->get_publish_options(),
            'weight_options' => $this->ddoption_model->get_weight_options(),
            'tid_parent' => $tid_parent,
            'title_parent' => $this->taxonomy_manager_model->get_title($tid_parent)
        );
        $this->template->script_var(array(
            'iframe_video_manager_url' => site_url('resource/video_manager/iframe/data')
        ));

        $this->template->application_script('resource/taxonomy_manager/sub_input_form.js');
        $this->template->load_typeonly();
        $this->template->write_view('resource/taxonomy_manager/sub_input_form', $data);
        $this->template->render();
    }

    function sub_edit($tid) {
        $tid_parent = $this->taxonomy_manager_model->get_tid_parent($tid);
        if (!$this->taxonomy_manager_model->is_owner($tid)) {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/taxonomy_manager/sub/' . $tid_parent),
                'heading' => 'ระบบผิดพลาด',
                'message' => '<p>ระบบผิดพลาด คุณไม่สามารถแก้ไขข้อมูลชุดนี้ได้</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $this->load->helper('form');
            $data = array(
                'form_title' => 'แก้ไขบท',
                'form_action' => site_url('resource/taxonomy_manager/sub_do_save'),
                'form_data' => $this->taxonomy_manager_model->get_form_data($tid),
                'cancel_link' => site_url('resource/taxonomy_manager/sub/' . $tid_parent),
                'delete_link' => site_url('resource/taxonomy_manager/sub_delete/' . $tid),
                'publish_options' => $this->ddoption_model->get_publish_options(),
                'weight_options' => $this->ddoption_model->get_weight_options(),
                'tid_parent' => $tid_parent,
                'title_parent' => $this->taxonomy_manager_model->get_title($tid_parent)
            );
            $this->template->script_var(array(
                'iframe_video_manager_url' => site_url('resource/video_manager/iframe/data')
            ));
            $this->template->application_script('resource/taxonomy_manager/sub_input_form.js');
            $this->template->load_typeonly();
            $this->template->write_view('resource/taxonomy_manager/sub_input_form', $data);
            $this->template->render();
        }
    }

    function sub_do_save() {
        $post_data = $this->input->post('data');
        if (isset($post_data['tid_parent'])) {
            $tid_parent = $post_data['tid_parent'];
        } else {
            $tid_parent = $this->taxonomy_manager_model->get_tid_parent($this->input->post('tid'));
        }

        if ($this->taxonomy_manager_model->sub_save($this->input->post('tid'), $post_data)) {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/taxonomy_manager/sub/' . $tid_parent),
                'heading' => 'แก้ไขข้อมูลเสร็จสิ้น',
                'message' => '<p>แก้ไขข้อมูลเสร็จสิ้น</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/taxonomy_manager/sub/' . $tid_parent),
                'heading' => 'ระบบผิดพลาด',
                'message' => '<p>ระบบผิดพลาด คุณไม่สามารถแก้ไขข้อมูลชุดนี้ได้</p>'
            );
            $this->load->view('refresh_page', $data);
        }
    }

    function sub_delete($tid) {
        $tid_parent = $this->taxonomy_manager_model->get_tid_parent($tid);

        $data = array(
            'url' => site_url('resource/taxonomy_manager/sub_do_delete/' . $tid),
            'cancel_url' => site_url('resource/taxonomy_manager/sub/' . $tid_parent),
            'heading' => 'คุณต้องการลบข้อมูล กลุ่ม นี้ใช่ไหม',
            'message' => '<p>หากคุณลบข้อมูล กลุ่ม นี้แล้วระบบจะลบข้อมูล กลุ่ม อย่างถาวร</p>'
        );

        $this->load->view('confirm_page', $data);
    }

    function sub_do_delete($tid) {
        $tid_parent = $this->taxonomy_manager_model->get_tid_parent($tid);
        if ($this->taxonomy_manager_model->sub_delete($tid)) {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/taxonomy_manager/sub/' . $tid_parent),
                'heading' => 'ทำการลบข้อมูลเสร็จสิ้น',
                'message' => '<p>คุณไม่สามารถเรียกคืน กลุ่ม นี้ได้อีก</p>'
            );
        } else {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/taxonomy_manager/sub/' . $tid_parent),
                'heading' => 'การลบข้อมูลผิดพลาด',
                'message' => '<p>คุณไม่สามารถลบ กลุ่มนี้ </p>'
            );
        }
        $this->load->view('refresh_page', $data);
    }

}