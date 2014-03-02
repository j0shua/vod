<?php

/**
 * @property seo_model $seo_model
 */
class seo extends CI_Controller {

    var $video_sitemap_path = '';

    public function __construct() {
        parent::__construct();
        $this->load->model('seo_model');
        $this->video_sitemap_path = FCPATH . 'files/video_sitemap.xml';
    }

    function make_sitemap() {
        
    }

    function make_video_sitemap() {
        $this->load->helper('file');
        $data['resource_video_data'] = $this->seo_model->get_all_video_data();

        $str = $this->load->view('seo/video_sitemap', $data, TRUE);
        //echo $str;
        write_file($this->video_sitemap_path, $str);
        echo 'complete';
    }

}