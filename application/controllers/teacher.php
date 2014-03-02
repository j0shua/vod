<?php

/**
 * Description of t
 *
 * @author lojoriderrefresh
 * @property t_model $t_model
 */
class teacher extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    function u($uid) {
        redirect('house/u/' . $uid);
    }

}

