<?php

class genlog extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    function index() {
        $this->update_table_for_search();
    }

  

}