<?php

class video_ftp_model extends CI_Model {

    var $upload_ftp_folder, $full_video_dir, $time;
    var $resource_type_id = 1;
    var $unit_price = -1;

    public function __construct() {
        parent::__construct();
        $this->full_video_upload_ftp_dir = $this->config->item('full_video_upload_ftp_dir');
        $this->full_video_dir = $this->config->item('full_video_dir');

        $this->time = time();
        $this->load->library('phpavconv');
    }

    function get_source_file_list() {
        $this->load->helper('number');
        $data['rows'] = array();
        $pattern = $this->full_video_upload_ftp_dir . '*.*';
        $files = glob($pattern, GLOB_BRACE);
        if (is_array($files)) {
            foreach ($files as $k => $filename) {

                $path_parts = pathinfo($filename);
                $fn = end(explode('/', $filename));
                $action = '<a href="' . site_url('resource/video_ftp/do_save/' . $k) . '">ลงทะเบียน video</a>';
                $data['rows'][] = array(
                    'id' => $k,
                    'cell' => array('filename' => $fn, 'filesize' => byte_format(filesize($filename)), 'action' => $action)
                );
            }
        }
        $data['page'] = 20;
        $data['total'] = count($files);
        return $data;
    }

    function save($key_of_file) {
        $this->db->trans_start();
        $full_ftp_file_path = $this->get_full_ftp_file_name($key_of_file);

        $movie = new ffmpeg_movie($full_ftp_file_path);
        $duration = $movie->getDuration();
        $output_file_name = $this->make_output_file_path();
        $output_file_path = $this->auth->get_personal_dir() . $output_file_name . '.' . $this->phpavconv->get_file_output_extension();
        $output_full_file_path = $this->full_video_dir . $output_file_path;


        //$pathinfo = pathinfo($full_ftp_file_path);
        //$filename = explode('_', $title['filename']);
        //$filename = end(explode('/', $full_ftp_file_path));
        //$filename = current(explode('.', $filename));
        $filename = explode('_', current(explode('.', end(explode('/', $full_ftp_file_path)))));
        if (count($filename) == 1) {
            $title = $filename[0];
            $resource_code = '';
        } else {
            $title = $filename[0];
            $resource_code = $filename[1];
        }
        $title = str_replace('-', ' ', $title);
        $resource_set = array(
            'title' => $title,
            'resource_code' => $resource_code,
            'desc' => 'upload by ftp',
            'create_time' => $this->time,
            'uid_owner' => $this->auth->uid(),
            'publish' => 1,
            'privacy' => 1,
            'unit_price' => $this->unit_price,
            'tags' => 'ftp',
            'category_id' => 1,
            'resource_type_id' => $this->resource_type_id
        );
        $this->db->set($resource_set);
        $this->db->insert('r_resource');
        $resource_id = $this->db->insert_id();
        $resource_video_set = array(
            'resource_id' => $resource_id,
            'file_path' => $output_file_path,
            'uid_owner' => $this->auth->uid(),
            'file_size_org' => filesize($full_ftp_file_path),
            'file_size' => filesize($full_ftp_file_path),
            'encode_complete' => 1,
            'create_time' => $this->time,
            'duration' => $duration
        );

        $this->db->set($resource_video_set);
        $this->db->insert('r_resource_video');
        $this->db->trans_complete();

        if (file_exists($full_ftp_file_path)) {
            //exit($full_ftp_file_path);
            rename($full_ftp_file_path, $output_full_file_path);
            return TRUE;
        }
        return FALSE;
    }

    function make_output_file_path() {
        $file_name = $this->time;
        return $file_name;
    }

    function get_full_ftp_file_name($key_of_file) {
        $pattern = $this->full_video_upload_ftp_dir . '*.*';
        $files = glob($pattern, GLOB_BRACE);
        if (count($files) > 0) {
            return $files[$key_of_file];
        }
        return FALSE;
    }

}