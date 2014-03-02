<?php

/**
 * โจทย์แบบแยกคำถาม กับตัวเลือก เอาไว้สร้าง exam
 */
class xelatex_dycontent_separate_model extends CI_Model {

    var $prefix = 'separate_';
    var $suffix_question = '_q';
    var $suffix_choice = '_c';
    var $dycontent_cache_dir = '';
    var $full_image_dir = '';

    public function __construct() {
        parent::__construct();
        $this->load->library('xelatex');
        $this->dycontent_cache_dir = $this->config->item('dycontent_cache_dir');
        $this->full_image_dir = $this->config->item('full_image_dir');
    }

    function get_content_data($resource_id) {
        $this->set_preable('content_preamble_formula');
        $this->db->where('resource_id', $resource_id);
        $q = $this->db->get('r_resource_dycontent');
        $row = $q->row_array();
        $row['data'] = $this->decode_data($row['data']);
        $update_time = $this->get_update_time($resource_id);
        $question = $row['data']['content_questions'][0];
        $file_path = $this->dycontent_cache_dir . $this->prefix . $resource_id . '_' . $update_time . $this->suffix_question . '.png';
        $pattern = $this->dycontent_cache_dir . $this->prefix . $resource_id . '_' . $update_time . $this->suffix_question . '*.png';
        $files = glob($pattern, GLOB_BRACE);
        if (empty($files)) {
            $this->make_question($question['question'], $file_path);
            foreach ($question['choices'] as $k => $choice) {
                $file_path = $this->dycontent_cache_dir . $this->prefix . $resource_id . '_' . $update_time . $this->suffix_choice . str_pad($k, 2, 0, STR_PAD_LEFT) . '.png';
                $this->make_choice($choice, $file_path);
            }
        }
        $pattern = $this->dycontent_cache_dir . $this->prefix . $resource_id . '_' . '*.png';
        $this->clean_file($this->dycontent_cache_dir . $this->prefix . $resource_id . '_*', $this->dycontent_cache_dir . $this->prefix . $resource_id . '_' . $update_time . '_*');
    }

    function get_update_time($resource_id) {
        $this->db->where('resource_id', $resource_id);
        $q = $this->db->get('r_resource');
        $row = $q->row_array();
        return $row['update_time'];
    }

    private function set_preable($preable_name) {
        $this->preable = $this->load->view('xelatex/' . $preable_name, array(), TRUE);
    }

    function make_question($question, $file_path) {
        $formula[] = $this->preable;
        $formula[] = "\begin{document}";
        $formula[] = "\graphicspath{{" . $this->full_image_dir . "}}";
        //$formula[] = "\noindent";
        $formula[] = $question;
        $formula[] = "\end{document}";
        $formula = implode("\n", $formula);

        $this->xelatex->formula($formula);
        $this->xelatex->render($file_path, FALSE);
    }

    function make_choice($choice, $file_path) {
        $formula[] = $this->preable;
        $formula[] = "\begin{document}";
        $formula[] = "\graphicspath{{" . $this->full_image_dir . "}}";
        //$formula[] = "\noindent";
        $formula[] = $choice;
        $formula[] = "\end{document}";
        $formula = implode("\n", $formula);
        $this->xelatex->formula($formula);
        $this->xelatex->render($file_path, FALSE);
    }

    function decode_data($data) {
        return json_decode($data, TRUE);
    }

    function clean_file($pattern_all, $pattern_want) {
        $files_all = glob($pattern_all, GLOB_BRACE);
        $file_want = glob($pattern_want, GLOB_BRACE);
        foreach ($files_all as $file) {
            if (!in_array($file, $file_want)) {
                @unlink($file);
            }
        }
    }

}