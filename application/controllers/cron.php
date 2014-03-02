<?php

class cron extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    function index() {
        $this->update_table_for_search();
    }

    function clean_upload_temp() {
        
    }

    function clean_xelatex_temp() {
        
    }

    function update_table_for_search() {
        $set = array(
            'title' => 'test cron',
            'create_time' => time()
        );
        $this->db->set($set);
        $this->db->insert('z_cron_log');
    }

}