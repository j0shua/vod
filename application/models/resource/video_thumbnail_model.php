<?php

class video_thumbnail_model extends CI_Model {

    var $full_video_dir = '';
    var $full_video_thumbnail_dir = '';

    public function __construct() {
        parent::__construct();
        $this->full_video_dir = $this->config->item('full_video_dir');
        $this->full_video_thumbnail_dir = $this->config->item('full_video_thumbnail_dir');
    }

    function get_image_path($resource_id) {
        $this->db->where('resource_id', $resource_id);
        $q1 = $this->db->get('r_resource_video');
        if ($q1->num_rows() > 0) {
            $image_file_path = $this->full_video_thumbnail_dir . $resource_id;
            if (!is_file($this->full_video_thumbnail_dir . $resource_id)) {
                $this->make_video_thumbnail($this->full_video_dir . $q1->row()->file_path, $image_file_path);
            } else {

                if (filesize($image_file_path) == 0) {
                    @unlink($image_file_path);
                }
            }

            return $this->full_video_thumbnail_dir . $resource_id;
        }
        return FALSE;
    }

    private function make_video_thumbnail($video_file_path, $image_file_path) {
        $command = "ffmpegthumbnailer -i $video_file_path -o $image_file_path -t 5";
        exec($command);
    }

}