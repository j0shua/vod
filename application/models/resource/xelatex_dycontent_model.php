<?php

/**
 * ระบบสร้างไฟล์รูปภาพ ของ dycontent
 * ระบบสามารถสร้างแล้วทำเป็นไฟล์อยู๋ สองแบบคือ 
 *  1 สร้างแบบ real_time เพื่อ preview
 *  2 สร้างแล้วเก็บไว้ ที่ cache ->ชื่อเป็นดังนี้ $prefix_$resource_id_$update_time.png เช่น dycontent_152_1232563212.pnd
 *  @property xelatex $xelatex
 */
class xelatex_dycontent_model extends CI_Model {

    var $formula = array();
    var $preable, $full_image_dir, $xelatex_temp_folder;
    var $prefix_file = 'dycontent_';
    var $prefix_file_no_solve = 'dycontent_no_solve_';
    var $itemize_char;
    var $itemize_type = 'num';
    var $show_solve_answer = TRUE;

    public function __construct() {
        parent::__construct();
        $this->full_image_dir = $this->config->item('full_image_dir');
        $this->load->library('xelatex');
        $this->xelatex_temp_folder = $this->config->item('temp_folder') . '/xelatex_temp/';
        $this->itemize_char = $this->config->item('itemize_char');
    }

    function show_solve_answer($show_solve_answer = TRUE) {
        $this->show_solve_answer = $show_solve_answer;

        if (!$this->show_solve_answer) {
            $this->prefix_file = $this->prefix_file_no_solve;
        }
    }

    /**
     * ตั้งค่าเริ่มต้นก่อนสร้าง โจทย์
     * @param type $data
     */
    function init_content($data, $show_solve_answer = FALSE) {
        if (!$show_solve_answer) {
            $this->show_solve_answer($show_solve_answer);
        }
        $this->make_dycontent($data);
        $this->set_preamble('dycontent_preamble_formula');
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

    /**
     * สร้างสูตร latex ของโจทย์ในรูปแบบต่างๆ รวมถึงโจทย์กลุ่มด้วย
     * @param type $data
     */
    private function make_dycontent($data) {

        if (isset($data['content_questions'])) {//check ว่ามี โจทย์ไหม
            $count_content_questions = count($data['content_questions']);
            if ($count_content_questions == 0) {//ถ้าโจทย์มีค่าเป็น 0 ให้สร้าง เนื้อหา
                $this->make_content_header($data['content_header']);
            } elseif ($count_content_questions > 0) {
                if ($count_content_questions > 1) { //ถ้าโจทย์มีหลายข้อให้สร้างโจทย์นำ
                    $this->make_content_header($data['content_header']);
                }
                $this->set_formula('\begin {enumerate}');
                foreach ($data['content_questions'] as $question) {
                    $this->set_formula('\item');
                    
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
                $this->set_formula('\end {enumerate}');
            }
        } else {//ไม่มีโจทย์ ให้ทำการสร้าง เนื้อหา
            $this->make_content_header($data['content_header']);
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
//        print_r($data);
//        exit();
        if ($this->show_solve_answer) {
            //$this->set_formula('เฉลย');
            if(isset($data['true_answers'][0])){
                $this->set_formula('เฉลย ข้อ ' . $this->itemize_char[$this->itemize_type][($data['true_answers'][0])]);
            }else{
                $this->set_formula('เฉลย ข้อ ');
            }
            
            // $this->set_formula('เฉลย' . ($data['true_answers'] + 1));

            $this->set_formula($data['solve_answer']);
        }
    }

    private function make_mcma($data) {
        $this->set_formula($data['question']);
        $this->set_formula($this->make_itemize($data['choices']));
        if ($this->show_solve_answer) {
            $this->set_formula('เฉลย');
            $this->set_formula($data['solve_answer']);
        }
    }

    private function make_ct($data) {
        $this->set_formula($data['question']);
        $this->set_formula('ตอบ \fbox {' . $data['true_answers'][0] . '}');
        if ($this->show_solve_answer) {
            $this->set_formula('เฉลย');
            $this->set_formula($data['solve_answer']);
        }
    }

    private function make_mct($data) {
        $this->set_formula('เฉลย');
        $this->set_formula($data['solve_answer']);
    }

    private function make_pair($data) {
        $this->set_formula('เฉลย');
        $this->set_formula($data['solve_answer']);
    }

}