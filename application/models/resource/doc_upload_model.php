<?php

/**
 * Description of doc_upload_model
 *
 * @author lojorider
 */
class doc_upload_model extends CI_Model {

    var $debug = TRUE;
    var $upload_error_msg = array();
    var $flash_error = array();
    var $upload_temp_dir = '';
    var $full_doc_dir = '';
    var $doc_file_size_limit = '';
    var $doc_extension_whitelist = '';
    var $ffmpeg_option = array();
    var $ffmpeg_codec_map = array();
    var $time = 0;
    var $resource_type_id = 2;

    public function __construct() {
        parent::__construct();
        $this->upload_temp_dir = $this->config->item('upload_temp_dir');
        $this->full_doc_dir = $this->config->item('full_doc_dir');
        $this->doc_file_size_limit = $this->config->item('doc_file_size_limit');
        $this->doc_extension_whitelist = $this->config->item('doc_extension_whitelist');
        $this->load->helper('tag');
        $this->time = time();
    }

//==============================================================================
// Save Section
//==============================================================================
    public function save($data, $resume_file) {
        $subject_title = $this->get_subject_title($data['subj_id']);
        $chapter_title = $this->get_chapter_title($data['chapter_id']);
        $time = time();
        $input_full_file_path = $this->upload_temp_dir . $resume_file;
        $output_file_path = $this->auth->get_personal_dir() . $this->make_output_file_name($resume_file);
        $output_full_file_path = $this->full_doc_dir . $output_file_path;
        // save to DB
        $this->db->trans_start();
        $resource_set = array(
            'title' => $data['title'],
            'desc' => $data['desc'],
            'create_time' => $time,
            'uid_owner' => $this->auth->uid(),
            'publish' => $data['publish'],
            'privacy' => $data['privacy'],
            'tags' => encode_tags($data['tags']),
            'resource_type_id' => $this->resource_type_id,
            //new field
            'degree_id' => $data['privacy'],
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
        $file_size = filesize($input_full_file_path);
        $resource_doc_set = array(
            'resource_id' => $resource_id,
            'file_path' => $output_file_path,
            'uid_owner' => $this->auth->uid(),
            'file_size' => $file_size,
            'create_time' => $time
        );
        $this->db->set($resource_doc_set);
        $this->db->insert('r_resource_doc');
        $this->db->trans_complete();

        if (file_exists($input_full_file_path)) {
            $this->move_doc($input_full_file_path, $output_full_file_path);
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

    /**
     * 
     * @param type $input_file
     * @param type $output_file
     * @return boolean ผลการ Encode
     */
    function move_doc($input_file, $output_file) {
        if (file_exists($input_file)) {
            rename($input_file, $output_file);
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function make_output_file_name($resume_file) {
        $file_name = time() . '.' . end(explode('.', $resume_file));
        return $file_name;
    }

    function get_extension_whitelist() {

        return $this->doc_extension_whitelist;
    }

    function get_file_size_limit() {
        return $this->doc_file_size_limit;
    }

}