<?php

/**
 * Description of t
 *
 * @author lojoriderrefresh
 * @property t_model $t_model
 */
class t extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    function index($uid) {
        redirect('house/u/' . $uid);
    }

}

