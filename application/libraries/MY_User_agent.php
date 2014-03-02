<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_User_agent extends CI_User_agent {

    var $CI;
    var $is_android = FALSE;
    var $android_version = FALSE;

    public function __construct() {
        parent::__construct();
        $this->CI = & get_instance();
        $this->_set_android_mobile();
    }

    private function _set_android_mobile() {
        $android_str = current(explode(';', strstr($this->agent, 'Android')));
        if ($android_str) {
            $this->is_android = TRUE;
            $this->android_version = end(explode(' ', $android_str));
        }
    }

    public function is_android() {
        return $this->is_android;
    }

    public function android_version() {
        return $this->android_version;
    }

}