<?php

class affiliate_model extends CI_Model {

    var $cookie_expire = '86500000';

    public function __construct() {
        parent::__construct();
    }

    function set_uid_affiliate() {
        if (!$this->input->cookie('uid_affiliate')) {
            if ($this->input->get('affiliate_code')) {
                $uid = $this->decode_affiliate_code($this->input->get('affiliate_code'));
                $this->db->where('uid', $uid);
                if ($this->db->count_all_results('u_user') > 0) {
                    $cookie = array(
                        'name' => 'uid_affiliate',
                        'value' => $this->decode_affiliate_code($this->input->get('affiliate_code')),
                        'expire' => $this->cookie_expire,
                        'domain' => trim($_SERVER['HTTP_HOST'], 'www'),
                        'path' => '/'
                    );
                    $this->input->set_cookie($cookie);
                }
            }
        }
    }

    function get_uid_affiliate() {
        if ($this->input->cookie('uid_affiliate', TRUE)) {
            return $this->input->cookie('uid_affiliate', TRUE);
        }
        return 0;
    }

    function decode_affiliate_code($affiliate_code) {
        return base64_decode($affiliate_code);
    }

    function encode_affiliate_code($uid) {
        return rtrim(base64_encode($uid), '=');
    }

}