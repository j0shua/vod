<?php

/**
 * Description of image_upload_model
 *
 * @author lojorider
 */
class image_upload_model extends CI_Model {

    var $debug = TRUE;
    var $upload_error_msg = array();
    var $flash_error = array();
    var $upload_temp_dir = '';
    var $full_image_dir = '';
    var $image_file_size_limit = '';
    var $image_extension_whitelist = '';
    var $ffmpeg_option = array();
    var $ffmpeg_codec_map = array();
    var $time = 0;
    var $resource_type_id = 3;

    public function __construct() {
        parent::__construct();
        $this->upload_temp_dir = $this->config->item('upload_temp_dir');
        $this->full_image_dir = $this->config->item('full_image_dir');
        $this->image_file_size_limit = $this->config->item('image_file_size_limit');
        $this->image_extension_whitelist = $this->config->item('image_extension_whitelist');
        $this->load->helper('tag');
        $this->time = time();
        $this->load->library('image_lib');
    }

//==============================================================================
// Save Section
//==============================================================================
    public function save($data, $resume_file) {
        $subject_title = $this->get_subject_title($data['subj_id']);
        $chapter_title = $this->get_chapter_title($data['chapter_id']);
        $time = time();
        $input_full_file_path = $this->upload_temp_dir . $resume_file;
        //check eps
        $file_check = explode('.', $input_full_file_path);
        if ($file_check[1] == 'eps') { //ถ้าเป็น EPS
            $file_check[1] = 'png';
            $im = new Imagick();
            $im->readImage($input_full_file_path);
            $im->writeImage($file_check[0] . ".png");
            $input_full_file_path = implode('.', $file_check);
            $resume_file = explode('.', $resume_file);
            $resume_file[1] = 'png';
            $resume_file = implode('.', $resume_file);
        }

//        $image = new Imagick($input_full_file_path);
//        $imageprops = $image->getImageGeometry();
//        if ($imageprops['width'] <= 200 ) {
//            
//        } else {
//            
//            $image->resizeImage(700, 500, imagick::FILTER_LANCZOS, 0.9, true);
//            $image->writeImage($input_full_file_path);
//        }

        $output_file_path = $this->auth->get_personal_dir() . $this->make_output_file_name($resume_file);
        $output_full_file_path = $this->full_image_dir . $output_file_path;



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

        $image_properties = $this->image_lib->get_image_properties($input_full_file_path, TRUE);
        if ($image_properties['width'] == NULL) {
            $image_properties['width'] = 0;
            $image_properties['height'] = 0;
        }
        $file_size = filesize($input_full_file_path);
        $resource_image_set = array(
            'resource_id' => $resource_id,
            'file_path' => $output_file_path,
            'uid_owner' => $this->auth->uid(),
            'file_size' => $file_size,
            'create_time' => $time,
            'width' => $image_properties['width'],
            'height' => $image_properties['height'],
            'mime_type' => $image_properties['mime_type']
        );
        $this->db->set($resource_image_set);
        $this->db->insert('r_resource_image');
        $this->db->trans_complete();

        if (file_exists($input_full_file_path)) {
            $this->move_file($input_full_file_path, $output_full_file_path);
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
    function move_file($input_file, $output_file) {
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
        return $this->image_extension_whitelist;
    }

    function get_file_size_limit() {
        return $this->image_file_size_limit;
    }

}