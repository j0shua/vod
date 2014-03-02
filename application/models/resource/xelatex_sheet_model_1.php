<?php

/**
 * ระบบสร้างไฟล์รูปภาพ ของ dycontent
 * ระบบสามารถสร้างแล้วทำเป็นไฟล์อยู๋ สองแบบคือ 
 *  1 สร้างแบบ real_time เพื่อ preview
 *  2 สร้างแล้วเก็บไว้ ที่ cache ->ชื่อเป็นดังนี้ $resource_id_$suffix_$time.png
 *  @property xelatex $xelatex
 */
class xelatex_sheet_model extends CI_Model {

    var $formula = array();
    var $preable, $full_image_dir, $xelatex_temp_path;
    var $prefix_file = 'sheet_';
    var $error_msg = array();
    var $itemize_type = 'num';
    var $show_solve_answer = TRUE;
    var $multiplier_line = 6;
    var $question_num = 0;

    public function __construct() {
        parent::__construct();
        $this->full_image_dir = $this->config->item('full_image_dir');
        $this->load->library('xelatex');
        $this->xelatex_temp_path = FCPATH . 'temp/xelatex_temp/';
        $this->itemize_char = $this->config->item('itemize_char');
    }

    function check_first_section($resources) {
        $section_num = 0;
        foreach ($resources as $resource) {
            if (isset($resource['section_title'])) {
                $section_num++;
            }
        }
        $current_resource = current($resources);
        if (count($resources) == 1 && isset($current_resource['section_title'])) {
            $this->error_msg("ต้องมีข้อมูลโจทย์หรือเนื้อหา");
            return FALSE;
        }
        if ($section_num > 0 && !isset($current_resource['section_title'])) {
            $this->error_msg("ตำแหน่งตอนไม่ถูกต้อง");
            return FALSE;
        }
        return TRUE;
    }

    function init_content($data, $show_solve = FALSE) {

        $uid_owner = $data['uid_owner'];
        //$this->auth->uid();
        $sheet_head_data = array(
            'title' => $data['title'],
            'subj_id' => $data['subj_id'],
            'la_id' => $data['la_id'],
            'chapter_title' => $this->get_chapter_title($data['chapter_id']),
            'degree_id' => $data['degree_id'],
            'uid_owner' => $uid_owner
        );

        //$chapter_title = $this->get_chapter_title($data['chapter_id']);
        //print_r($data);
        if (!isset($data['resources'])) {
            $this->error_msg("ยังไม่มีข้อมูลโจทย์");
            return FALSE;
        }
        if (!$this->check_first_section($data['resources'])) {
            return FALSE;
        }

        $this->set_preable('dycontent_preamble_formula');

        $this->make_sheet_head($sheet_head_data);
        $this->make_explanation($data['explanation']);

        $section_num = 1;

        if (!$this->check_first_section($data['resources'])) {
            $this->set_formula('\Huge  ตำแหน่งตอนไม่ถูกต้อง   \normalsize');
        }

        foreach ($data['resources'] as $resource) {
            if (isset($resource['resource_id'])) {
                $resource_data = $this->get_resource_data($resource['resource_id']);
                if ($resource_data['content_type_id'] == 1) {

                    $this->set_formula('%============= เริ่ม เนื้อหาเลขที่ : ' . $resource_data['resource_id'] . ' ===========');
                    $this->set_formula($resource_data['data']['content_header']);
                    $this->set_formula('\vspace{' . $resource['vspace'] * $this->multiplier_line . 'mm}');
                    $this->set_formula('%============= สิ้นสุด เนื้อหาเลขที่ : ' . $resource_data['resource_id'] . ' ===========');
                } else {
                    $this->set_formula('%============= เริ่ม โจทย์เลขที่ : ' . $resource_data['resource_id'] . ' ===========');
                    $this->make_dycontent($resource_data['data'], $resource['vspace'], $show_solve);

                    $this->set_formula('%============= สิ้นสุด โจทย์เลขที่ : ' . $resource_data['resource_id'] . ' ===========');
                }
            } else {
                $this->set_formula("\n");
                $this->set_formula('%++++++++++++++ เริ่มตอนที่ ' . $section_num . ' +++++++++++++');
                $this->set_formula('\noindent');
                $this->set_formula('\textbf{ตอนที่ ' . $section_num . ' ' . $resource['section_title'] . '}');
                if ($resource['vspace'] > 0) {
                    $this->set_formula('\\\\[' . $resource['vspace'] * $this->multiplier_line . 'mm]');
                } else {
                    $this->set_formula('\\\\[1mm]');
                }

                $section_num++;
            }
        }
        return TRUE;
    }

    function error_msg($text = '') {
        if ($text == '') {
            return $this->error_msg;
        } else {
            $this->error_msg[] = $text;
        }
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
                $target_path = $this->xelatex_temp_path . $this->prefix_file . md5($this->xelatex->formula()) . '.pdf';
            } else {
                $target_path = $this->xelatex_temp_path . $this->prefix_file . md5($this->xelatex->formula()) . '.png';
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

    private function make_explanation($explanation) {
        if ($explanation != '') {
            $this->set_formula('%============= คำชี้แจง ===========');
            $this->set_formula('\noindent\fbox{\begin{minipage}{1\textwidth}');
            $this->set_formula($explanation);
            $this->set_formula('\end{minipage}}');
            $this->set_formula('\vspace{5mm}');
            $this->set_formula('%============= สิ้นสุดคำชี้แจง ===========');
            $this->set_formula('\noindent');
        }
    }

    private function make_sheet_head($sheet_head_data) {
        $owner_data = $this->auth->get_user_data($sheet_head_data['uid_owner']);
        $chapter_title = '';
//        if (trim($sheet_head_data['chapter_title']) != '') {
//            $chapter_title = 'บท' . $sheet_head_data['chapter_title'];
//        }
        $subject = $this->get_subject_title($sheet_head_data['subj_id']);
        if ($subject != '') {
            $subject = 'วิชา' . $subject;
        }
        $degree = $this->get_degree_title($sheet_head_data['degree_id']);
        if ($degree != '') {
            $degree = 'ระดับ' . $degree;
        }
        $learning_area = $this->get_learning_area_title($sheet_head_data['la_id']);
        if ($learning_area != '') {
            $learning_area = 'กลุ่มสาระ' . $learning_area;
        }
        $sheet_head = '\center{เอกสารประกอบการเรียน}\\
\center{' . $sheet_head_data['title'] . ' ' . $chapter_title . '}\\ ';
        if ($chapter_title . $degree . $learning_area != '') {
            $sheet_head .= '\center{' . $learning_area . ' ' . $subject . '  ' . $degree . '}\\ ';
        }
        $sheet_head .= '\center{ครูผู้สอน ' . $owner_data['first_name'] . ' ' . $owner_data['last_name'] . ' }\\';
        $this->set_formula('\noindent\fbox{\begin{minipage}{1\textwidth}');
        $this->set_formula($sheet_head);
        $this->set_formula('\end{minipage}}');
        $this->set_formula('\vspace{2mm}');
        $this->set_formula("\n");
        $this->set_formula('\noindent');
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

    private function make_dycontent($data, $vspace, $show_solve = FALSE) {
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
                        if ($show_solve) {
                            if (isset($question['true_answers'][0])) {
                                $this->set_formula('\textbf{ตอบข้อ} ' . $this->itemize_char[$this->itemize_type][($question['true_answers'][0])]);
                                $this->set_formula("\\\\");
                            }
                        }
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

                if ($show_solve) {
                    $this->set_formula('\textbf{เฉลย}' . "\\\\");
                    $this->set_formula($question['solve_answer']);
                    $this->set_formula('\vspace{' . (2 * $this->multiplier_line) . 'mm}');
                } else {

                    $this->set_formula('\vspace{' . $vspace * $this->multiplier_line . 'mm}');
                }
                $this->question_num++;
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
        $this->set_formula('\\\\[2mm]');
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

    function get_subject_title($subj_id) {
        $q = $this->db->get_where('f_subject', array('subj_id' => $subj_id));
        if ($q->num_rows() > 0) {
            return $q->row()->title;
        }
        return FALSE;
    }

    function get_chapter_title($chapter_id) {
        $this->db->select('chapter_title');
        $this->db->where('chapter_id', $chapter_id);
        $q = $this->db->get('f_chapter');
        if ($q->num_rows() > 0) {
            return $q->row()->chapter_title;
        }
        return FALSE;
    }

    function get_learning_area_title($la_id) {
        $q = $this->db->get_where('f_c51_learning_area', array('la_id' => $la_id));
        if ($q->num_rows() > 0) {
            return $q->row()->title;
        }
        return FALSE;
    }

    function get_degree_title($degree_id) {
        $q = $this->db->get_where('f_degree', array('degree_id' => $degree_id));
        if ($q->num_rows() > 0) {
            return $q->row()->degree_long;
        }
        return FALSE;
    }

}