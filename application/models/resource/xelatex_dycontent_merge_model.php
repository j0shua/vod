<?php

/**
 * 
 * ระบบสามารถสร้างแล้วทำเป็นไฟล์อยู๋ สองแบบคือ 
 *  1 สร้างแบบ real_time เพื่อ preview
 *  2 สร้างแล้วเก็บไว้ ที่ cache ->ชื่อเป็นดังนี้ $resource_id_$suffix_$time.png
 *  @property xelatex $xelatex
 */
class xelatex_dycontent_merge_model extends CI_Model {

    var $formula = array();
    var $preable, $full_image_dir, $xelatex_temp_folder;
    var $prefix_file = 'dycontent_merge_';
    var $error_msg = array();
    var $itemize_type = 'num';
    var $show_solve_answer = TRUE;
    var $multiplier_line = 6;
    var $question_num = 0;

    public function __construct() {
        parent::__construct();
        $this->full_image_dir = $this->config->item('full_image_dir');
        $this->load->library('xelatex');
        $this->xelatex_temp_folder = $this->config->item('temp_folder') . '/xelatex_temp/';
        $this->itemize_char = $this->config->item('itemize_char');
    }

    function init_content($a_resource_id) {
        $this->set_preable('dycontent_preamble_formula');
        foreach ($a_resource_id as $resource_id) {
            $resource_data = $this->get_resource_data($resource_id);
            if ($resource_data['content_type_id'] == 1) {

                $this->set_formula('%============= เริ่ม เนื้อหาเลขที่ : ' . $resource_data['resource_id'] . ' ===========');
                $this->set_formula($resource_data['data']['content_header']);
                $this->set_formula('\vspace{' . ( $this->multiplier_line ) . 'mm}');
                $this->set_formula('%============= สิ้นสุด เนื้อหาเลขที่ : ' . $resource_data['resource_id'] . ' ===========');
            } else {
                $this->set_formula('%============= เริ่ม โจทย์เลขที่ : ' . $resource_data['resource_id'] . ' ===========');
                $this->make_dycontent($resource_data['data'], 1);
                $this->set_formula('%============= สิ้นสุด โจทย์เลขที่ : ' . $resource_id . ' ===========');
            }
        }
        return TRUE;
    }

    private function set_preable($preable_name) {
        $this->preable = $this->load->view('xelatex/' . $preable_name, array(), TRUE);
    }

    private function set_formula($formula) {
        $this->formula[] = $formula;
    }

    function render($target_path = '', $replace_file = FALSE, $get_pdf = FALSE) {
        $formula[] = $this->preable;
        $formula[] = '\begin{document}';
        $formula[] = '\graphicspath{{' . $this->full_image_dir . '}}';
        $formula[] = implode("\n", $this->formula);
        $formula[] = '\end{document}';
        $this->xelatex->formula(implode("\n", $formula));
        if ($target_path == '') {
            if ($get_pdf) {
                $target_path = $this->xelatex_temp_folder . $this->prefix_file . md5($this->xelatex->formula()) . '.pdf';
            } else {
                $target_path = $this->xelatex_temp_folder . $this->prefix_file . md5($this->xelatex->formula()) . '.png';
            }
        }
        $result = $this->xelatex->render($target_path, $replace_file);
        
        $pathinfo = pathinfo($target_path);
        $result['render_file_base_uri'] = str_replace(FCPATH, '', $pathinfo['dirname']) . '/';
        
        return $result;
    }

    function render_pdf($target_path = '', $replace_file = FALSE) {
        return $this->render($target_path, $replace_file, TRUE);
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

    private function make_dycontent($data, $vspace) {
        //$this->set_formula('\renewcommand{labelenumi}{thaialph{enumi})}');

        if (isset($data['content_questions'])) {
            if (count($data['content_questions']) > 1) {
                $this->make_content_header($data['content_header']);
            }

            $this->set_formula('\begin {enumerate}');
            $this->set_formula('\setcounter{enumi}{' . $this->question_num . '}');

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
                $this->question_num++;
                $this->set_formula('\vspace{' . $vspace * $this->multiplier_line . 'mm}');
            }
            $this->set_formula('\end {enumerate}');
        } else {
            $this->make_content_header($data['content_header']);
        }
    }

    private function make_content_header($content_header) {
        $this->set_formula($content_header);
    }

    private function make_itemize($array_list) {
        $formula = array();
        $formula[] = '\begin{itemize}';
        foreach ($array_list as $k => $item) {
            $formula[] = '\item[' . $this->itemize_char[$this->itemize_type][$k] . '.] ' . $item;
        }
        $formula[] = '\end {itemize}';
        return implode("\n", $formula);
    }

    private function make_mc($data) {
        $this->set_formula($data['question']);
        $this->set_formula($this->make_itemize($data['choices']));
    }

    private function make_mcma($data) {
        $this->set_formula($data['question']);
        $this->set_formula($this->make_itemize($data['choices']));
    }

    private function make_ct($data) {
        $this->set_formula($data['question']);
        $this->set_formula('');
        $this->set_formula('\vspace{2mm}');
        //$this->set_formula('\\\\[2mm]');
        $this->set_formula('ตอบ ');

        $this->set_formula('\fbox{ \begin{minipage}{0.5\textwidth} \hfill\vspace{4mm} \end{minipage} }');
    }

    private function make_mct($data) {
        exit();
    }

    private function make_pair($data) {
        exit();
    }

    function decode_data($data) {

        return json_decode($data, TRUE);
    }

}