<?php

class notpermission extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    function index() {
        $data = array(
            'time' => 2,
            'url' => site_url(),
            'heading' => 'คุณไม่มีสิทธิเข้าใช้งาน',
            'message' => '<p>คุณไม่มีสิทธิเข้าใช้งาน</p>'
        );
        $this->load->view('refresh_page', $data);
    }

}