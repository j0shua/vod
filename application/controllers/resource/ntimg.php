<?php

/**
 * Description of ntimg
 *
 * @author lojorider
 * @property video_thumbnail_model $video_thumbnail_model
 * @property avatar_thumbnail_model $avatar_thumbnail_model
 */
class ntimg extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    function video_thumbnail_show($resource_id) {
        echo '<img src="' . site_url('resource/ntimg/video_thumbnail/' . $resource_id) . '" />';
    }

    function video_thumbnail($resource_id) {
        $this->load->model('resource/video_thumbnail_model');
        $filename = $this->video_thumbnail_model->get_image_path($resource_id);
        if ($filename) {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: private", false);
            header("Content-Type: image/png");
            header("Content-Disposition: attachment; filename=\"" . basename($filename) . ".png\";");
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: " . filesize($filename));
            readfile($filename);
        } else {
            echo 'NO';
        }
    }

    function avatar_thumbnail($uid) {
        $this->load->model('resource/avatar_thumbnail_model');
    }

}