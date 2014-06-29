<?php

/**
 * Description of genlog_model
 *
 * @author lojorider
 */
class genlog_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->time = time();
        $this->load->helper('time');
    }

}
