<?php

class testcase extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    function index() {
        $this->load->library('unit_test');
        $this->unit->use_strict(TRUE);
        $test = 1 + 1;
        $expected_result = 2;
        $test_name = 'Adds one plus one';
        $this->unit->run($test, $expected_result, $test_name);
        $expected_result = '2';
        $this->unit->run($test, $expected_result, $test_name);
        $this->unit->set_test_items(array('test_name', 'result'));
        echo $this->unit->report();
        echo $this->unit->result();
    }

}