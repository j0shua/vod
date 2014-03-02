<?php

/**
 * Description of Setting
 * @author lojorider
 * @copyright educasy.com
 */
class Setting extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    function get_site_name() {
        return $this->config->item('site_name');
    }
   

    function get_site_email() {
        return $this->config->item('site_email');
    }

    function get_alert_email() {
        return $this->config->item('alert_email');
    }

    function get_noreply_email() {
        return $this->config->item('noreply_email');
    }

}