<?php

/**
 * 
 */
class help extends CI_Controller {

    var $make_money;

    public function __construct() {
        parent::__construct();
        $this->load->model('affiliate/affiliate_model');
        $this->load->library('user_agent');
        $this->make_money = $this->config->item('make_money');
        $this->load->helper('html');
    }

    function teacher_manual() {
        $data['title'] = 'คู่มือแนะนำเบื้องต้นสำหรับคุณครู';
        $this->template->write_view('help/teacher_manual/page1', $data);
        $this->template->render();
    }

    function student_manual() {

        $data['title'] = 'คู่มือแนะนำเบื้องต้นสำหรับนักเรียน';
        $this->template->write_view('help/student_manual/page1', $data);
        $this->template->render();
    }

    function teacher_train() {
        $data['title'] = 'ไฟล์สำหรับอบรมการใช้งาน';
        $this->template->write_view('help/teacher_train/page1', $data);
        $this->template->render();
    }

}
