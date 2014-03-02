<?php

/**
 * Description of xelatex_preview_render สร้าง code สำหรับ สร้าง สูตร latex
 *
 * @author lojorider
 */
class xelatex_preview_model extends CI_Model {

    var $formula, $preable, $full_image_dir;

    public function __construct() {
        parent::__construct();
        $this->full_image_dir = $this->config->item('full_image_dir');
        $this->load->library('xelatex');
    }

    function set_preable($preable_name) {
        $this->preable = $this->load->view('resource/xelatex_preview/' . $preable_name, array(), TRUE);
    }

    function set_formula($formula) {
        $this->formula[] = $formula;
    }

    function make_formula() {
        $formula[] = $this->preable;
        $formula[] = "\begin{document}";
        $formula[] = "\graphicspath{{" . $this->full_image_dir . "}}";
        $formula[] = implode("\n", $this->formula);
        $formula[] = "\end{document}";
        return implode("\n", $formula);
    }

    function render($target_path, $replace_file = FALSE) {
        $this->xelatex->formula($this->make_formula());
        return $this->xelatex->render($target_path, $replace_file);
    }

}
