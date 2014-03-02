<?php

/**
 * Xelatex สร้างไฟล์ที่ต้องการจาก tex
 * 
 * @author lojorider
 * 
 */
class xelatex {

    private $CI;
    private $formula; //สูตร xelatex
    private $xelatex_builder_path; //folder เก็บ ไฟล์สำหรับการ render
    private $trim = FALSE; //ตัดขอบ
    private $transparent = FALSE; //โปร่งใส
    private $path_info; //ข้อมูล file path
    private $file_path; //ไฟล์ที่ต้องการที่จะได้
    private $want_log; //ต้องการ log file
    private $clean_temp = FALSE; //ลบ ไฟล์ temp [ไฟล์สำหรับการ render]
    private $error_msg = '';
    private $debug = TRUE;
    //private $xelatex_path = '/usr/local/texlive/2013/bin/x86_64-linux/xelatex';
    private $xelatex_path = 'xelatex'; 

    public function __construct() {
        $this->CI = &get_instance();
        $this->CI->load->helper('file');
        $temp_folder = $this->CI->config->item('temp_folder');
        $this->xelatex_builder_path = $temp_folder . 'xelatex_builder/';
    }

    /**
     * กำหนดสูตรให้ ไฟล์ tex
     * @param type $formula
     */
    public function formula($formula = '') {
        if ($formula == '') {
            return $this->formula;
        }
        $this->formula = $formula;
    }

    /**
     * ตัดขอบ
     * @param type $trim
     */
    public function set_trim($trim = TRUE) {
        $this->trim = $trim;
    }

    /**
     * ทำโปร่งใส
     * @param type $color
     */
    public function set_transparent($color = "#FFFFFF") {
        $this->transparent = $color;
    }

    /**
     * แสดง  log
     * @param type $want_log
     */
    public function want_log($want_log = TRUE) {
        $this->want_log = $want_log;
    }

    /**
     * เลือกไม่ลบ temp
     * @param type $clean_temp
     */
    public function not_clean_temp($clean_temp = FALSE) {
        $this->clean_temp = $clean_temp;
    }

    /**
     * สร้างไฟล์ tex
     * @return boolean
     */
    private function make_tex_file() {
        $file_path = $this->xelatex_builder_path . $this->path_info['filename'] . '.tex';
        //echo $file_path;

        if (!$this->debug) {
            // @unlink($file_path);
        }
        if (!write_file($file_path, $this->formula)) {
            $this->error_msg = '<h2>ไม่สามารถเขียนไฟล์ .txt ไปที่ ' . $file_path . ' ได้<h2>';
            return FALSE;
        }
        return TRUE;
    }

//    function render_bbcode($text) {
//        $this->set_trim();
//        preg_match_all('#\[tex\](.*?)\[/tex\]#si', $text, $tex_matches);
//        for ($i = 0; $i < count($tex_matches[0]); $i++) {
//            $pos = strpos($text, $tex_matches[0][$i]);
//            $latex_formula = $tex_matches[1][$i];
//            $formula[] = $this->CI->load->view('xelatex/content_preamble_formula', array(), TRUE);
//            $formula[] = '\begin{document}';
//            $formula[] = $latex_formula;
//            $formula[] = '\end{document}';
//            $this->formula(implode("\n", $formula));
//            $target_path = FCPATH . 'temp/xelatex_temp/bbcode_' . md5($this->formula()) . '.png';
//            $result = $this->render($target_path, FALSE);
//            $url = @base_url('temp/xelatex_temp/' . $result['files_basename'][0]);  //ป้องกันการ render ไม่ได้
//            $text = substr_replace($text, "<img src='" . $url . "' align=absmiddle>", $pos, strlen($tex_matches[0][$i]));
//        }
//        return $text;
//    }

    /**
     * สร้างผลลัพท์
     * @param type $file_path
     * @return array
     */
    public function render($file_path, $replace_file = TRUE) {
        $result = array();
        $xelatex_command = '';
        $convert_command = '';
        $this->file_path = $file_path;
        $this->path_info = pathinfo($this->file_path);
        if (!$replace_file) {
            switch ($this->path_info['extension']) {
                case 'png':
                    // เช็คว่ามีไฟล์เก่าอยู่หรือไม่
                    $pattern = $this->path_info['dirname'] . '/' . $this->path_info['filename'] . '*.png';
                    $files = glob($pattern, GLOB_BRACE);
                    if (!empty($files)) {
                        $image_files = array();
                        foreach ($files as $v) {
                            $image_files[] = $v;
                            $image_files_basename[] = basename($v);
                        }
                        $result['files'] = $image_files;
                        $result['files_basename'] = $image_files_basename;
                        return $result;
                    }
                    break;
                case 'pdf':
                    if (is_file($this->file_path)) {
                        $result['files'] = array($this->file_path);
                        return $result;
                    }
                    break;
                case 'log':
                    if (is_file($this->file_path)) {
                        $result['files'] = array($this->file_path);
                        return $result;
                    }
                    break;
            }
        }
        if (!is_writable($this->path_info['dirname'])) {
            $result['error_msg'] = '<h2>เขียนไฟล์ไปที่ folder' . $this->path_info['dirname'] . ' ไม่ได้ ลองเช็คดู สิทธิ์ ของการเขียนไฟล์<h2>';
            return $result;
        }
        // สร้างไฟล์ tex 
        if (!$this->make_tex_file()) {
            $result['error_msg'] = $this->error_msg;
            return $result;
        }
        // สร้างคำสั่ง สร้าง xelatex
        chdir($this->xelatex_builder_path); 
        //$xelatex_command = 'xelatex ' . " " . $this->xelatex_builder_path . $this->path_info['filename'] . '.tex';
        $xelatex_command = $this->xelatex_path . " " . $this->xelatex_builder_path . $this->path_info['filename'] . '.tex';
        
        exec($xelatex_command);
        chdir(FCPATH);
        //if (!is_file($this->xelatex_builder_path . $this->path_info['filename'] . '.pdf')) {
        //สร้าง Error msg
        $subject = utf8_encode(str_replace("\n", "<br>", read_file($this->xelatex_builder_path . '/' . $this->path_info['filename'] . ".log")));
        $latex_error_msg = array();
        preg_match_all("/!(.+?)Emergency stop/U", $subject, $latex_error_msg, PREG_PATTERN_ORDER);
        $error_msg = str_replace("<br>", "\n", implode("\n", $latex_error_msg[1]));
        if ($error_msg != '') {
            $formula = "\n========================== TEX FILES ========================== \n";
            foreach (explode("\n", $this->formula) as $k => $formula_line) {
                $formula .=($k + 1) . "\t" . $formula_line . "\n";
            }
            $formula .= "\n========================== TEX FILES ========================== \n";
            //texlive , texlive-xetex , texlive-latex-extra
            $result['error_msg'] = '<h2>ไม่สามารถแสดงตัวอย่างได้ : pdf file not create</h2> <p><textarea readonly style="resize: none;width:780px;height:400px;">' . $error_msg . $formula . ' </textarea></p>';
            return $result;
        }
        // }
        switch ($this->path_info['extension']) {
            case 'pdf':
                rename($this->xelatex_builder_path . $this->path_info['filename'] . '.pdf', $this->file_path);
                $result['files'] = array($this->file_path);
                break;
            case 'png':
                // สร้างคำสั่ง แปลงเป็นรูปภาพ
                $trim = ($this->trim) ? ' -trim ' : '';
                $transparent = ($this->transparent) ? ' -transparent "' . $this->transparent . '" ' : '';
                ///$convert_command = "convert -density 140 $trim $transparent " . $this->xelatex_builder_path . $this->path_info['filename'] . ".pdf " . $this->path_info['dirname'] . '/' . $this->path_info['filename'] . ".png";
                $convert_command = "convert -density 120 $trim $transparent " . $this->xelatex_builder_path . $this->path_info['filename'] . ".pdf " . $this->path_info['dirname'] . '/' . $this->path_info['filename'] . "-%03d.png";
                exec($convert_command);
                //echo $convert_command;
                //เรียกดูชื่อ file png ที่ convert ออกมา ได้
                $files = glob($pattern, GLOB_BRACE);
                $image_files = array();
                $image_files_basename = array();
                if (!empty($files) && !$replace_file) {
                    foreach ($files as $v) {
                        $image_files[] = $v;
                        $image_files_basename[] = basename($v);
                    }
                } else {
                    $result['error_msg'] = '<h2>คุณอาจยังไม่ได้ติดตั้ง imagemagick</h2>'.$this->xelatex_path . " " . $this->xelatex_builder_path . $this->path_info['filename'] . '.tex';;
                }
                $result['files'] = $image_files;
                $result['files_basename'] = $image_files_basename;
                break;
            case 'log':
                // ไม่ต้องย้ายไฟล์ ดึงข้อมูลจาก temp ได้เลย
                $result['files'] = read_file($this->xelatex_builder_path . '/' . $this->path_info['filename'] . ".log");
                $result['xelatex_command'] = $xelatex_command;
                break;
            default:
                break;
        }
        if ($this->want_log) {
            $result['xelatex_command'] = $xelatex_command;
            $result['convert_command'] = $convert_command;
            $result['log'] = read_file($this->xelatex_builder_path . '/' . $this->path_info['filename'] . ".log");
        }
        $this->clean_temp();
        return $result;
    }

    /**
     * ลบ temp
     * @return type
     */
    private function clean_temp() {
        if ($this->debug) {
            return TRUE;
        }
        if (!$this->clean_temp) {
            return;
        }
        $pattern = $this->xelatex_builder_path . '*.*';
        $files = glob($pattern, GLOB_BRACE);
        foreach ($files as $v) {
            unlink($v);
        }
    }

}
