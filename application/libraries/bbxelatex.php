<?php

class bbxelatex extends xelatex {

    public function __construct() {
        parent::__construct();
        $this->set_trim();
    }

//    function bbrender($text) {
//        preg_match_all('#\[tex\](.*?)\[/tex\]#si', $text, $tex_matches);
//        for ($i = 0; $i < count($tex_matches[0]); $i++) {
//            $pos = strpos($text, $tex_matches[0][$i]);
//            $latex_formula = $tex_matches[1][$i];
//            $formula[] = $this->load->view('xelatex/content_preamble_formula', array(), TRUE);
//            $formula[] = "\begin{document}";
//            $formula[] = $latex_formula;
//            $formula[] = "\end{document}";
//            $this->xelatex->formula(implode("\n", $formula));
//            $target_path = FCPATH . 'temp/xelatex_temp/' . md5($this->xelatex->formula()) . '.png';
//            $result = $this->xelatex->render($target_path, FALSE);
//            $url = base_url('temp/xelatex_temp/' . $result['files_basename'][0]);
//            $alt_latex_formula = htmlentities($latex_formula, ENT_QUOTES);
//            $alt_latex_formula = str_replace("\r", "&#13;", $alt_latex_formula);
//            $alt_latex_formula = str_replace("\n", "&#10;", $alt_latex_formula);
//            $text = substr_replace($text, "<img src='" . $url . "' title='" . $alt_latex_formula . "' alt='" . $alt_latex_formula . "' align=absmiddle>", $pos, strlen($tex_matches[0][$i]));
//        }
//        return $text;
//    }

}