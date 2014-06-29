<?php

/**
 * @author lojorider  <lojorider@gmail.com>
 * @property house_model $house_model 
 * @property course_model $course_model
 */
class house extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('house_model');
        $this->load->model('study/course_model');
    }

    function u($uid = '', $tid = '') {
        if (!$this->house_model->valid_uid($uid)) {
            $data = array(
                'time' => 2,
                'url' => site_url('house/u/2'),
                'heading' => 'ไม่มีหน้าที่ต้องการอยู่',
                'message' => '<p>ระบบ จะนำคุณไปสู่หน้าทดแทน</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $this->house_model->init($uid, $tid);
            $data['uid'] = $uid;
            $data['tid'] = $this->house_model->get_tid();
            $data['user'] = $this->house_model->get_user();
            $data['taxonomy_title'] = $this->house_model->get_taxonomy_parent_title();
            $data['taxonomy_desc'] = $this->house_model->get_taxonomy_parent_desc();
            $data['taxonomy_duration'] = $this->house_model->get_taxonomy_parent_duration();

            $data['taxonomy'] = $this->house_model->get_taxonomy();
            $data['sub_taxonomy'] = $this->house_model->get_sub_taxonomy();
            $desc = array();
            foreach ($data['sub_taxonomy'] as $v) {
                $desc[] = $v['title'];
            }
            $desc = " เนื้อหาดังนี้ " . implode(" ", $desc);

            //ดึงหลักสูตรที่เปิด 
            $page = '1';
            $qtype = '';
            $query = '';
            $rp = '1000';
            $sortname = 'start_time';
            $sortorder = 'asc';
            $data['course_open'] = $this->course_model->find_all_course_open($page, $qtype, $query, $rp, $sortname, $sortorder, $uid, TRUE);

            if ($this->auth->get_rid() == 3 && $this->auth->uid() != $uid) {

                $data['copy_url'] = site_url('resource/taxonomy_manager/copy/' . $data['tid']);
            }
            $this->template->load_jquery_treeview();
            $this->template->title($data['taxonomy_title']);
            $this->template->description($data['taxonomy_desc'] . $desc);
            if ($this->auth->is_make_money()) {
                $this->template->write_view('house/std_page_combine_make_money', $data);
            } else {
                $this->template->write_view('house/std_page_combine', $data);
            }

            $this->template->render();
        }
    }

    function c($uid = '') {
        if (!$this->house_model->valid_uid($uid)) {
            $data = array(
                'time' => 2,
                'url' => site_url('house/u/9'),
                'heading' => 'ไม่มีหน้าที่ต้องการอยู่',
                'message' => '<p>ระบบ จะนำคุณไปสู่หน้าทดแทน</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $this->load->model('study/course_model');
            $page = '1';
            $qtype = '';
            $query = '';
            $rp = '10';
            $sortname = 'c_id';
            $sortorder = 'desc';
            $data['courses'] = $this->course_model->find_all_course_open($page, $qtype, $query, $rp, $sortname, $sortorder, $uid);
            $data['user'] = $this->house_model->get_user($uid);
            $this->template->write_view('house/course_page', $data);
            $this->template->render();
        }
    }

}