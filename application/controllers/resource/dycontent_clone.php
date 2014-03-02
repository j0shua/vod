<?php

/**
 * Description of dycontent_clone
 *
 * @author lojorider
 * @property dycontent_clone_model $dycontent_clone_model
 * @property xelatex_preview_model $xelatex_preview_model
 * @property  xelatex_dycontent_clone_model $xelatex_dycontent_clone_model
 */
class dycontent_clone extends CI_Controller {

    var $content_type_options = array(
        '' => 'ทุกแบบ',
        1 => 'เนื้อหา',
        2 => 'โจทย์ตัวเลือก 1 คำตอบ(mc)',
        3 => 'โจทย์ตัวเลือกหลายคำตอบ(mcma)',
        4 => 'โจทย์เติมคำ(ct)',
//            5 => 'โจทย์เติมคำหลายคำตอบ(ctma)'
    );

    function __construct() {
        parent::__construct();
        //$this->auth->access_limit($this->auth->permis_resource);
        $this->load->model('resource/dycontent_clone_model');
        $this->load->model('core/ddoption_model');
        $this->load->model('main_side_menu_model');
        $this->load->helper('form');
    }

    function test() {
        $this->dycontent_clone_model->test();
    }

}
