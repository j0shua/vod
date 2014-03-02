<?php

/**
 * ระบบสร้างไฟล์รูปภาพ ของ dycontent
 * ระบบสามารถสร้างแล้วทำเป็นไฟล์อยู๋ สองแบบคือ 
 *  1 สร้างแบบ real_time เพื่อ preview
 *  2 สร้างแล้วเก็บไว้ ที่ cache ->ชื่อเป็นดังนี้ $prefix_$resource_id_$update_time.png เช่น dycontent_152_1232563212.pnd
 *  @property xelatex $xelatex
 */
class xelatex_exam_model extends CI_Model {

    var $formula = array();
    var $preable, $full_image_dir, $xelatex_temp_folder;
    var $prefix_question_file = 'exam_';
    var $prefix_question_solve_file = 'exam_solve_';
    var $have_solve = FALSE;
    var $itemize_char;
    var $itemize_type = 'num';

    public function __construct() {
        parent::__construct();
        $this->full_image_dir = $this->config->item('full_image_dir');
        $this->load->library('xelatex');
        $this->xelatex_temp_folder = $this->config->item('temp_folder') . '/xelatex_temp/';
        $this->itemize_char = $this->config->item('itemize_char');
    }

    /**
     * ตั้งค่าเริ่มต้นก่อนสร้าง โจทย์
     * @param type $data
     */
    function init_content($data, $have_solve = FALSE) {
        $this->have_solve = $have_solve;
        $this->formula = array();
        $this->make_exam($data);
        $this->set_preamble('exam_preamble_formula');
    }

    /**
     * ตั้งค่า preamble
     * @param type $preamble_name
     */
    private function set_preamble($preamble_name) {
        $this->preable = $this->load->view('xelatex/' . $preamble_name, array(), TRUE);
    }

    /**
     * set_formula
     * @param type $formula
     */
    private function set_formula($formula) {
        $this->formula[] = $formula . "\n";
    }

    /**
     * render_pdf
     * @param string $target_path
     * @param string $replace_file
     * @return array
     */
    function render_pdf($target_path = '', $replace_file = FALSE) {
        return $this->render($target_path, $replace_file, TRUE);
    }

    /**
     * render
     * @param string $target_path
     * @param string $replace_file
     * @param boolean $get_pdf
     * @return array
     */
    function render($target_path = '', $replace_file = FALSE, $get_pdf = FALSE) {

        $formula[] = $this->preable;
        $formula[] = '\begin{document}';
        $formula[] = '\graphicspath{{' . $this->full_image_dir . '}}';
        $formula[] = implode("\n", $this->formula);
        $formula[] = '\end{document}';
        $this->xelatex->formula(implode("\n", $formula));
        if ($target_path == '') {
            if ($this->have_solve) {
                if ($get_pdf) {
                    $target_path = $this->xelatex_temp_folder . $this->prefix_question_solve_file . md5($this->xelatex->formula()) . '.pdf';
                } else {
                    $target_path = $this->xelatex_temp_folder . $this->prefix_question_solve_file . md5($this->xelatex->formula()) . '.png';
                }
            } else {
                if ($get_pdf) {
                    $target_path = $this->xelatex_temp_folder . $this->prefix_question_file . md5($this->xelatex->formula()) . '.pdf';
                } else {
                    $target_path = $this->xelatex_temp_folder . $this->prefix_question_file . md5($this->xelatex->formula()) . '.png';
                }
            }
        }
        $this->xelatex->set_trim();
        $result = $this->xelatex->render($target_path, $replace_file);
        $pathinfo = pathinfo($target_path);
        $result['render_file_base_uri'] = str_replace(FCPATH, '', $pathinfo['dirname']) . '/';
        return $result;
    }

    /**
     * สร้างสูตร latex ของโจทย์ในรูปแบบต่างๆ รวมถึงโจทย์กลุ่มด้วย
     * @param type $data
     */
    private function make_exam($data) {
        if ($data['content_header'] != '') {
            $this->make_content_header($data['content_header']);
        }
        $question = $data['content_question'];
        switch ($question['content_type_id']) {
            case 2:
                $this->make_mc($question); //ตัวเลือก 1 คำตอบ
                break;
            case 3:
                $this->make_mcma($question); //ตัวเลือก หลายคำตอบ
                break;
            case 4:
                $this->make_ct($question); // เติมคำตอบ
                break;
            case 5:
                $this->make_mct($question); //เติมคำหลายคำตอบ
                break;
            case 6:
                $this->make_pair($question); //จับคู่
                break;
            default:
                break;
        }
    }

    /**
     * สร้างโจทย์นำหรือเนื้อหา
     * @param type $content_header
     */
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
        if ($this->have_solve) {
            $this->set_formula($data['solve_answer']);
        }
    }

    private function make_mcma($data) {
        $this->set_formula($data['question']);
        $this->set_formula($this->make_itemize($data['choices']));
    }

    private function make_ct($data) {
        $this->set_formula($data['question']);
        //$this->set_formula('ตอบ \fbox {' . $data['true_answers'][0] . '}');
        if ($this->have_solve) {
            $this->set_formula($data['solve_answer']);
        }
    }

    private function make_mct($data) {
        
    }

    private function make_pair($data) {
        
    }

}