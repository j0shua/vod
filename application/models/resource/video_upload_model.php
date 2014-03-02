<?php

/**
 * Description of video_upload_model
 *
 * @author lojorider
 * @property phpavconv $phpavconv
 */
class video_upload_model extends CI_Model {

    var $upload_error_msg = array();
    var $flash_error = array();
    var $upload_temp_dir = '';
    var $full_video_dir = '';
    var $video_file_size_limit = '';
    var $video_extension_whitelist = '';
    var $resource_type_id = 1;
    var $time = 0;
    var $full_encode_log_dir;

    public function __construct() {
        parent::__construct();
        $this->upload_temp_dir = $this->config->item('upload_temp_dir');
        $this->full_video_dir = $this->config->item('full_video_dir');
        $this->video_file_size_limit = $this->config->item('video_file_size_limit');
        $this->video_extension_whitelist = $this->config->item('video_extension_whitelist');
        $this->load->library('phpavconv');
        $this->full_encode_log_dir = $this->config->item('full_encode_log_dir');
        $this->load->helper('tag');
        $this->time = time();
    }

//==============================================================================
// Save Section
//==============================================================================
    public function save($data, $resume_file) {
        $subject_title = $this->get_subject_title($data['subj_id']);
        $chapter_title = $this->get_chapter_title($data['chapter_id']);
        $input_full_file_path = $this->upload_temp_dir . $resume_file;
        $output_file_name = $this->make_output_file_name();
        $output_file_path = $this->auth->get_personal_dir() . $output_file_name . '.' . $this->phpavconv->get_file_output_extension();
        $output_full_file_path = $this->full_video_dir . $output_file_path;
        $log_full_file_path = $this->full_encode_log_dir . $this->auth->uid() . '_' . $output_file_name . '.log';
        // get video data
        $movie = new ffmpeg_movie($input_full_file_path);
        // save to DB
        $this->db->trans_start();
        $resource_set = array(
            'title' => $data['title'],
            'unit_price' => $data['unit_price'],
            'desc' => $data['desc'],
            'create_time' => $this->time,
            'update_time' => $this->time,
            'uid_owner' => $this->auth->uid(),
            'publish' => $data['publish'],
            'privacy' => $data['privacy'],
            'tags' => encode_tags($data['tags']),
            'resource_type_id' => $this->resource_type_id,
            'resource_code' => $data['resource_code'],
            //new field
            'degree_id' => $data['degree_id'],
            'la_id' => $data['la_id'],
            'subj_id' => $data['subj_id'],
            'subject_title' => $subject_title,
            'chapter_id' => $data['chapter_id'],
            'chapter_title' => $chapter_title,
            'sub_chapter_title' => $data['sub_chapter_title']
        );
        $this->db->set($resource_set);
        $this->db->insert('r_resource');
        $resource_id = $this->db->insert_id();
        $resource_video_set = array(
            'resource_id' => $resource_id,
            'file_path' => $output_file_path,
            'duration' => $movie->getDuration(),
            'uid_owner' => $this->auth->uid(),
            'file_size_org' => filesize($input_full_file_path),
            'create_time' => $this->time
        );
        $this->db->set($resource_video_set);
        $this->db->insert('r_resource_video');
        $this->db->trans_complete();

        if (file_exists($input_full_file_path)) {
            $this->phpavconv->encode($input_full_file_path, $output_full_file_path, $log_full_file_path);

            return TRUE;
        }
        return FALSE;
    }

    function get_subject_title($subj_id) {
        $this->db->select('title');
        $this->db->where('subj_id', $subj_id);
        $q = $this->db->get('f_subject');
        if ($q->num_rows() > 0) {
            return $q->row()->title;
        } else {
            return '';
        }
    }

    function get_chapter_title($chapter_id) {
        $this->db->select('chapter_title');
        $this->db->where('chapter_id', $chapter_id);
        $q = $this->db->get('f_chapter');
        if ($q->num_rows() > 0) {
            return $q->row()->chapter_title;
        } else {
            return '';
        }
    }

    function make_output_file_name() {
        $file_name = $this->time;
        return $file_name;
    }

    function get_extension_whitelist() {
        return $this->video_extension_whitelist;
    }

    function get_file_size_limit() {
        return $this->video_file_size_limit;
    }

}
