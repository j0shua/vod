<?php

/**
 * ระบบสร้างไฟล์รูปภาพ ของ dycontent
 * ระบบสามารถสร้างแล้วทำเป็นไฟล์อยู๋ สองแบบคือ 
 *  1 สร้างแบบ real_time เพื่อ preview
 *  2 สร้างแล้วเก็บไว้ ที่ cache ->ชื่อเป็นดังนี้ $resource_id_$suffix_$time.png
 *  @property xelatex $xelatex
 */
class xelatex_studysheet_model extends CI_Model {

    var $formula = array();
    var $preable, $full_image_dir, $xelatex_temp_path;
    var $prefix_file = 'studysheet_';

    public function __construct() {
        parent::__construct();
        $this->full_image_dir = $this->config->item('full_image_dir');
        $this->load->library('xelatex');
        $this->xelatex_temp_path = FCPATH . 'temp/xelatex_temp/';
    }

    function init_content($data) {
        $this->set_preable('content_preamble_formula');
        $this->make_explanation($data['explanation']);
        
        $this->set_formula('\begin {enumerate}');

        foreach ($data['resources'] as $resource) {
            //print_r($resource);
            $resource_data = $this->get_resource_data($resource['resource_id']);
            if ($resource_data) {
                //print_r($resource_data);
                $this->formula[] = '%==== เลขที่สื่อ : ' . $resource_data['resource_id'] . '=====================================';
                $this->make_dycontent($resource_data['data']);
                $this->set_formula('\vspace{' . $resource['vspace'] . 'mm}');
            }
        }
        $this->set_formula('\end {enumerate}');
    }

    private function set_preable($preable_name) {
        $this->preable = $this->load->view('xelatex/' . $preable_name, array(), TRUE);
    }

    private function set_formula($formula) {
        $this->formula[] = $formula . "\n";
    }

    function render($target_path = '', $replace_file = FALSE) {
        $formula[] = $this->preable;
        $formula[] = "\begin{document}";
        $formula[] = "\graphicspath{{" . $this->full_image_dir . "}}";
        $formula[] = implode("\n", $this->formula);
        $formula[] = "\end{document}";
        $this->xelatex->formula(implode("\n", $formula));
        if ($target_path == '') {
            $target_path = $this->xelatex_temp_path . $this->prefix_file . md5($this->xelatex->formula()) . '.png';
        }
        $result = $this->xelatex->render($target_path, $replace_file);
        $pathinfo = pathinfo($target_path);
        $result['render_file_base_uri'] = str_replace(FCPATH, '', $pathinfo['dirname']) . '/';
        return $result;
    }

    private function make_explanation($explanation) {
        $this->set_formula('\noindent\fbox{\begin{minipage}{1\textwidth}');
        $this->set_formula($explanation);
        $this->set_formula('\end{minipage}}');
        $this->set_formula('\vspace{3mm}');
    }

    private function get_resource_data($resource_id) {
        $this->db->select('r_resource.resource_id');
        $this->db->select('r_resource.title');
        $this->db->select('r_resource.unit_price');
        $this->db->select('r_resource.uid_owner');
        $this->db->select('r_resource.publish');
        $this->db->select('r_resource.privacy');
        $this->db->select('r_resource.category_id');
        $this->db->select('r_resource.desc');
        $this->db->select('r_resource.tags');
        $this->db->where('r_resource.resource_id', $resource_id);
        $q1 = $this->db->get('r_resource');
        if ($q1->num_rows() == 0) {
            return FALSE;
        }
        $row1 = $q1->row_array();
        $this->db->select('r_resource_dycontent.data');
        $this->db->select('r_resource_dycontent.render_type_id');
        $this->db->select('r_resource_dycontent.content_type_id');
        $this->db->where('resource_id', $resource_id);
        $q2 = $this->db->get('r_resource_dycontent');
        $row2 = $q2->row_array();
        $row2['data'] = $this->decode_data($row2['data']);
        return array_merge($row1, $row2);
    }

    private function make_dycontent($data) {
        //$this->set_formula('\renewcommand{labelenumi}{thaialph{enumi})}');

        if (isset($data['content_questions'])) {
            if (count($data['content_questions']) > 1) {
                $this->make_content_header($data['content_header']);
            }

//            $this->set_formula('\begin {enumerate}');
            foreach ($data['content_questions'] as $question) {
                $this->set_formula('\item');
                switch ($question['content_type_id']) {
                    case 2:
                        $this->make_mc($question);
                        break;
                    case 3:
                        $this->make_mcma($question);
                        break;
                    case 4:
                        $this->make_ct($question);
                        break;
                    case 5:
                        $this->make_mct($question);
                        break;
                    case 6:
                        $this->make_pair($question);
                        break;
                    default:
                        break;
                }
            }
//            $this->set_formula('\end {enumerate}');
        } else {
            $this->make_content_header($data['content_header']);
        }
    }

    private function make_content_header($content_header) {
        $this->set_formula($content_header);
    }

    private function make_enumerate($array_list) {
        $formula = array();
        $formula[] = '\begin {enumerate}';
        foreach ($array_list as $item) {
            $formula[] = '\item ' . $item;
        }
        $formula[] = '\end {enumerate}';
        return implode("\n", $formula);
    }

    private function make_mc($data) {
        $this->set_formula($data['question']);
        $this->set_formula($this->make_enumerate($data['choices']));
    }

    private function make_mcma($data) {
        
    }

    private function make_ct($data) {
        print_r($data);
        exit();
    }

    private function make_mct($data) {
        print_r($data);
        exit();
    }

    private function make_pair($data) {
        print_r($data);
        exit();
    }

    function decode_data($data) {

        return json_decode($data, TRUE);
    }

}