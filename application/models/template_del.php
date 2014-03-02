<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Template
 * @author lojorider
 * @copyright educasy.com
 */
class template extends CI_Model {

    var $CI;
    var $link_tag = '';
    var $script_tag = '';
    var $content = '';
    var $footer = '';
    var $top_r_menu = array();
    var $og_image = '';
    var $description = '';
    var $title = '';
    var $temmplate_name = 'simple';

    public function __construct() {
        parent::__construct();
        $this->CI = & get_instance();
        $this->load_jquery();
        $this->load_jquery_ui();
        $this->CI->load->config('facebook');
    }

    public function meta_description($description) {
        $data = array('name' => 'description', 'content' => $description);
        $this->meta($data);
    }

    public function meta_keywords($keyword) {
        $data = array('name' => 'keywords', 'content' => $keyword);
        $this->meta($data);
    }

    private function meta($value = array()) {
        $str = '';
        foreach ($value as $k => $v) {
            $str .= $k . '="' . $v . '" ';
        }
        $str = '<meta ' . $str . '/>';
    }

    function link($href = '', $rel = 'stylesheet', $type = 'text/css', $title = '', $media = '', $index_page = FALSE) {
        $link = '<link ';

        if (is_array($href)) {
            foreach ($href as $k => $v) {
                if ($k == 'href' AND strpos($v, '://') === FALSE) {
                    if ($index_page === TRUE) {
                        $link .= 'href="' . $this->config->site_url($v) . '" ';
                    } else {
                        $link .= 'href="' . $this->config->slash_item('base_url') . $v . '" ';
                    }
                } else {
                    $link .= "$k=\"$v\" ";
                }
            }

            $link .= "/>";
        } else {
            if (strpos($href, '://') !== FALSE) {
                $link .= 'href="' . $href . '" ';
            } elseif ($index_page === TRUE) {
                $link .= 'href="' . $this->config->site_url($href) . '" ';
            } else {
                $link .= 'href="' . $this->config->slash_item('base_url') . $href . '" ';
            }

            $link .= 'rel="' . $rel . '" type="' . $type . '" ';

            if ($media != '') {
                $link .= 'media="' . $media . '" ';
            }

            if ($title != '') {
                $link .= 'title="' . $title . '" ';
            }

            $link .= '/>';
        }


        $this->link_tag .= $link;
    }

    function application_script($file) {
        $this->script('assets/application/' . $file);
    }

    private function script($file) {
        $tmp = parse_url($file);
        if (!isset($tmp['scheme'])) {
            $file = base_url($file);
        }
        $this->script_tag .= "\n" . '<script type="text/javascript" src="' . $file . '"></script>';
    }

    /**
     * เขียน Content ลงตำแหน่งที่ต้องการของ Tempalte
     * @param String $view <p>ที่อยู่ของ View </p>
     * @param Array $data <p>ข้อมูล Array ที่ส่งให้ View </p>
     * @param String $region <p>สามารถเป็นได้หลายอย่าง เช่น <b>"content"</b> หรือ <b>"footer"</b></p>
     */
    public function write_view($view, $data = array(), $region = 'content') {
        switch ($region) {
            case 'content':
                $region = &$this->content;
                break;
            case 'footer':
                $region = &$this->footer;
                break;

            default:
                break;
        }

        $region = $this->CI->parser->parse($view, $data, TRUE);
    }

    public function write($content, $region = 'content') {
        switch ($region) {
            case 'content':
                $region = &$this->content;
                break;
            case 'footer':
                $region = &$this->footer;
                break;

            default:
                break;
        }
        $region = $content;
    }

    function get_top_r_menu() {
        $rid = $this->auth->get_rid();
        $this->top_r_menu[] = array('uri' => site_url('resource/video_manager'), 'title' => 'จัดการวิดีโอ');
        $this->top_r_menu[] = array('uri' => site_url('resource/doc_manager'), 'title' => 'จัดการเอกสาร');
        $this->top_r_menu[] = array('uri' => site_url('resource/taxonomy_manager'), 'title' => 'จัดการวิชา');
        $this->top_r_menu[] = array('uri' => site_url('user/account'), 'title' => 'บัญชี');
        $this->top_r_menu[] = array('uri' => site_url('user/my_money'), 'title' => 'ยอดเงินในบัญชี');
        if ($rid == 1) {
            $this->top_r_menu[] = array('uri' => site_url('utopup/manual_topup/informant_manager'), 'title' => 'จัดการการโอนเงิน');
            $this->top_r_menu[] = array('uri' => site_url('utopup/manual_topup/'), 'title' => 'จัดการการเติมเงิน');
        } else {
            $this->top_r_menu[] = array('uri' => site_url('utopup/manual_topup/inform_tranfer'), 'title' => 'แจ้งการโอนเงิน');
        }

        return $this->top_r_menu;
    }

    function og_image($image_url) {
        $this->og_image = $image_url;
    }

    function description($description) {
        $this->description = $description;
    }

    function title($title) {
        $this->title = $title;
    }

    function temmplate_name($temmplate_name = '') {
        if ($temmplate_name == '') {
            return $this->temmplate_name;
        }
        $this->temmplate_name = $temmplate_name;
    }

    /**
     * เพื่อแสดงออกทางหน้าจอ
     * @param type $return
     * @return type 
     */
    public function render($return = FALSE) {
        $data = array(
            'filepath' => './themes/' . $this->temmplate_name . '/page.php',
            'title' => $this->title,
            'content' => $this->content,
            'footer' => $this->footer,
            'script' => $this->script_tag,
            'link' => $this->link_tag,
            'is_login' => $this->auth->is_login(),
            'top_r_menu' => $this->get_top_r_menu(),
            'facebook_appId' => $this->CI->config->item('facebook_appId'),
            'analytics' => $this->CI->config->item('analytics'),
            'og_image' => $this->og_image,
            'description' => $this->description
        );

        return $this->CI->parser->parse('_layouts/main', $data);
    }

    /**
     * คำสั่งโหลด script ต่างๆ 
     */
    private function load_jquery() {
        $this->script('https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js');
    }

    public function load_swfupload() {
        $this->script(base_url() . 'assets/swfupload/swfupload.js');
        $this->link('assets/swfupload/swfupload.css');
    }

    public function load_fileuploader() {
        $this->script(base_url() . 'assets/fileuploader/fileuploader.min.js');
        $this->link('assets/fileuploader/fileuploader.css');
    }

    private function load_jquery_ui() {
        $this->script('https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js');
        $this->script(base_url() . 'assets/jquery-ui/jquery.ui.datepicker-th.js');
        $this->link('assets/jquery-ui/ui-lightness/jquery-ui-1.8.21.custom.css');
    }

    public function load_jquery_ui_timepicker() {
        $this->script('https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js');
        $this->script(base_url() . 'assets/jquery-ui-timepicker/jquery.ui.timepicker.js');
        $this->script(base_url() . 'assets/jquery-ui-timepicker/jquery.ui.timepicker-th');

        $this->link('assets/jquery-ui-timepicker/jquery.ui.timepicker.css');
    }

    public function load_typeonly() {
        $this->script(base_url() . 'assets/lojo/lojo.typeonly.js');
    }

    public function load_swfobject() {
        
    }

    public function load_google_chart() {
        $this->script('https://www.google.com/jsapi');
    }

    public function load_meio_mask() {
        $this->script(base_url() . 'assets/jquery/jquery.meio.mask.min.js');
    }

    public function load_flowplayer() {
        $this->script(base_url() . 'assets/flowplayer/flowplayer-3.2.11.min.js');
        $this->script(base_url() . 'assets/flowplayer/flowplayer.playlist-3.2.10.min.js');
    }

    public function load_flexgrid() {
        $this->script(base_url() . 'assets/flexgrid/flexigrid.js');
        $this->script(base_url() . 'assets/flexgrid/flexigrid.pack.th.js');
        $this->link('assets/flexgrid/flexigrid.pack.css');
        $this->link('assets/flexgrid/style.css');
    }

    public function load_showloading() {
        $this->script(base_url() . 'assets/showloading/js/jquery.showLoading.min.js');
        $this->link('assets/showloading/css/showLoading.css');
    }

    public function load_markitup_bbcode() {
        $this->script(base_url() . 'assets/markitup/jquery.markitup.js');
        $this->script(base_url() . 'assets/markitup/sets/bbcode/set.js');
        $this->link('assets/markitup/skins/nt/style.css');
        $this->link('assets/markitup/sets/bbcode/style.css');
    }

    public function load_markitup() {
        $this->script(base_url() . 'assets/markitup/jquery.markitup.js');
        $this->script(base_url() . 'assets/markitup/sets/defaults/set.js');
        $this->link('assets/markitup/skins/nt/style.css');
        $this->link('assets/markitup/sets/defaults/style.css');
    }

}

/* End of file template.php */
