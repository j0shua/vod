<?php

/**
 * Description of flash_media_manager_model
 *
 * @author lojorider
 */
class flash_media_manager_model extends CI_Model {

    var $CI;
    var $flash_media_dir = '';
    var $resource_type_id = 8;
    var $flash_media_file_size_limit;
    var $flash_media_extension_whitelist;
    var $flash_media_upload_extension_whitelist;
    var $upload_temp_dir;
    var $pattern = 'selectChapter*.swf';
    var $full_flash_media_dir;

    public function __construct() {
        parent::__construct();
        $this->CI = & get_instance();
        $this->CI->load->model('core/ddoption_model');
        $this->load->helper('number');
        $this->full_flash_media_dir = $this->config->item('full_flash_media_dir');
        $this->load->helper('tag');
        $this->time = time();
        $this->flash_media_file_size_limit = $this->config->item('flash_media_file_size_limit');
        $this->flash_media_extension_whitelist = $this->config->item('flash_media_extension_whitelist');
        $this->flash_media_upload_extension_whitelist = $this->config->item('flash_media_upload_extension_whitelist');
        $this->upload_temp_dir = $this->config->item('upload_temp_dir');
    }

    public function find_all($page, $qtype, $query, $rp, $sortname, $sortorder) {
        $publish_options = $this->CI->ddoption_model->get_publish_options();
        $privacy_options = $this->CI->ddoption_model->get_privacy_options();
        if ($qtype == 'custom') {
            parse_str($query, $query);
        }
        $data = array(
            'rows' => array(),
            'page' => $page,
            'total' => 0
        );
        //make offset
        $offset = (($page - 1) * $rp);
        // Start Sql Query State for count row
        $this->find_all_where('r_resource', $qtype, $query);
        // END Sql Query State
        $total = $this->db->count_all_results();
        // Start Sql Query State
        $this->db->select('r_resource.*');
        $this->find_all_where('r_resource', $qtype, $query);
        // END Sql Query State
        $this->db->limit($rp, $offset);
        $this->db->order_by($sortname, $sortorder);
        $result = $this->db->get();
        $data['total'] = $total;
        //print_r($result->result_array());
        foreach ($result->result_array() as $row) {
            $row['checkbox'] = '<input type = "checkbox" name = "cb_resource_id[]" value = "' . $row['resource_id'] . '" />';
//            $row['count_video_join'] = $this->count_video_join($row['resource_id']);
//            $row['action'] = '<a href="' . site_url('v/' . $row['resource_id']) . '" target="_blank">เปิดเอกสาร</a>';
            $row['action'] = '<a href="' . site_url('resource/flash_media_manager/edit/' . $row['resource_id']) . '">แก้ไข</a>';
            $row['action'] .= '<a href="' . site_url('resource/flash_media_manager/delete/' . $row['resource_id']) . '">ลบ</a>';
//            if ($row['count_video_join'] > 0) {
//                $row['action'] .= '<a href="' . site_url('resource/resource_join/join_video/' . $row['resource_id']) . '">แก้ไขการเชื่อมวิดีโอ</a>';
//            } else {
//                $row['action'] .= '<a href="' . site_url('resource/resource_join/join_video/' . $row['resource_id']) . '">เพิ่มการเชื่อมวิดีโอ</a>';
//            }
            $row['title'] = '<span title="' . $row['title'] . "\n" . $row['desc'] . '">' . $row['title'] . '</span>';
            $row['title_play'] = '<a href="' . site_url('play/play_resource/flash_media/' . $row['resource_id']) . '/play' . '" target="_blank">▶</a>' . $row['title'];
            $row['publish'] = $publish_options[$row['publish']];
            $row['privacy'] = $privacy_options[$row['privacy']];

            $flash_media_data = $this->get_flash_media_data($row['resource_id']);
            $row = array_merge($row, $flash_media_data);
            //$row['file_size'] = $this->get_file_size($row['resource_id'], TRUE);
            $data['rows'][] = array(
                'id' => $row['resource_id'],
                'cell' => $row
            );
        }


        return $data;
    }

//    public function iframe_find_all($page, $qtype, $query, $rp, $sortname, $sortorder) {
//        $publish_options = $this->CI->ddoption_model->get_publish_options();
//        $privacy_options = $this->CI->ddoption_model->get_privacy_options();
//        if ($qtype == 'custom') {
//            parse_str($query, $query);
//        }
//        //set now timestamp
//        //$time = time();
//        //initial data        
//        $data = array(
//            'rows' => array(),
//            'page' => $page,
//            'total' => 0
//        );
//        //make offset
//        $offset = (($page - 1) * $rp);
//        // Start Sql Query State for count row
//        $this->find_all_where('r_resource', $qtype, $query);
//        // END Sql Query State
//        $total = $this->db->count_all_results();
//        // Start Sql Query State
//        $this->db->select('r_resource.*');
//        $this->find_all_where('r_resource', $qtype, $query);
//        // END Sql Query State
//        $this->db->limit($rp, $offset);
//        $this->db->order_by($sortname, $sortorder);
//        $result = $this->db->get();
//        $data['total'] = $total;
//
//        print_r($result->result_array());
//        foreach ($result->result_array() as $row) {
//            $row['checkbox'] = '<input type = "checkbox" name = "cb_resource_id[]" value = "' . $row['resource_id'] . '" />';
//            $row['count_video_join'] = $this->count_video_join($row['resource_id']);
////            $row['action'] = '<a href="' . site_url('v/' . $row['resource_id']) . '" target="_blank">เปิดเอกสาร</a>';
//            $row['action'] = '';
//            $row['title'] = '<span title="' . $row['title'] . "\n" . $row['desc'] . '">' . $row['title'] . '</span>';
//            $row['title_play'] = '<a href="' . site_url('v/' . $row['resource_id']) . '" target="_blank">เปิดดู</a>' . $row['title'];
//            $row['publish'] = $publish_options[$row['publish']];
//            $row['privacy'] = $privacy_options[$row['privacy']];
//
//            $flash_media_data = $this->get_flash_media_data($row['resource_id']);
//            $row = array_merge($row, $flash_media_data);
//            //$row['file_size'] = $this->get_file_size($row['resource_id'], TRUE);
//            $data['rows'][] = array(
//                'id' => $row['resource_id'],
//                'cell' => $row
//            );
//        }
//
//
//        return $data;
//    }

    private function get_flash_media_data($resource_id) {
        $this->db->select('file_size');
//        $this->db->select('file_ext');
        $this->db->select('file_path');
        $this->db->where('resource_id', $resource_id);
        $this->db->from('r_resource_flash_media');
        $q1 = $this->db->get();
        $row = $q1->row_array();

//        if ($row['file_ext'] == '') {
//            $row['file_ext'] = pathinfo($row['file_path'], PATHINFO_EXTENSION);
//            $this->db->set('file_ext', $row['file_ext']);
//            $this->db->where('resource_id', $resource_id);
//            $this->db->update('r_resource_flash_media');
//        }
        $row['h_file_size'] = byte_format($row['file_size']);
        return $row;
    }

//    function count_video_join($resource_id) {
//
//        $this->db->where('resource_id', $resource_id);
//        return $this->db->count_all_results('r_resource_video_join');
//    }

    private function find_all_where($table_name, $qtype, $query) {
        $this->db->where('resource_type_id', $this->resource_type_id);
        $this->db->where('uid_owner', $this->auth->uid());
        switch ($qtype) {
            case 'custom':
                foreach ($query as $k => $v) {
                    if ($v != '') {
                        switch ($k) {
                            case 'resource_id':
                                $this->db->like($k, $v, 'after');
                                break;
                            case 'title':
                                $this->db->like($k, $v);
                                break;
                            case 'desc':
                                $this->db->like($k, $v);
                                break;
                            case 'tags':
                                $this->db->like($k, $v);
                                break;

                            default:
                                $this->db->where($k, $v);
                                break;
                        }
                    }
                }
                break;
            default:
                if ($query != '') {
                    $this->db->where($qtype, $query);
                }
                break;
        }
        $this->db->from($table_name);
    }

    function get_flash_media_form_data($resource_id) {
        $this->db->where('resource_id', $resource_id);
        $query = $this->db->get('r_resource');
        $row = $query->row_array();
        $row['tags'] = $row['tags'];
        $this->db->where('resource_id', $resource_id);
        $query2 = $this->db->get('r_resource_flash_media');
        $row = array_merge($row, $query2->row_array());
        return $row;
    }

    function save($resource_id, $data) {

        $subject_title = $this->get_subject_title($data['subj_id']);
        $chapter_title = $this->get_chapter_title($data['chapter_id']);
        $this->db->where('resource_id', $resource_id);
        $query = $this->db->get('r_resource');
        if ($query->num_rows() > 0) {
            $this->db->trans_start();
            $set = array(
                'title' => $data['title'],
                'desc' => $data['desc'],
                'tags' => encode_tags($data['tags']),
                'publish' => $data['publish'],
                'privacy' => $data['privacy'],
                //new field
                'degree_id' => $data['degree_id'],
                'la_id' => $data['la_id'],
                'subj_id' => $data['subj_id'],
                'subject_title' => $subject_title,
                'chapter_id' => $data['chapter_id'],
                'chapter_title' => $chapter_title,
                'sub_chapter_title' => $data['sub_chapter_title']
            );
            $this->db->set($set);
            $this->db->where('resource_id', $resource_id);
            $this->db->update('r_resource');
            $this->db->set('file_path', $data['file_path']);
            $this->db->where('resource_id', $resource_id);
            $this->db->update('r_resource_flash_media');
            $this->db->trans_complete();
            return TRUE;
        }
        return FALSE;
    }

    function delete($resource_id) {
        if (is_array($resource_id)) {
            $this->db->where_in('resource_id', $resource_id);
        } else {
            if ($resource_id == '') {
                return FALSE;
            }
            $this->db->where('resource_id', $resource_id);
        }

        $result = $this->db->get('r_resource');
        if ($result->num_rows() > 0) {
            $this->db->select('folder_path');
            $this->db->where('resource_id', $resource_id);
            $query = $this->db->get('r_resource_flash_media');
            $row = $query->row_array();
            if (file_exists($this->full_flash_media_dir . $row['folder_path'])) {
                rrmdir($this->full_flash_media_dir . $row['folder_path']);

                $this->db->trans_start();

                $this->db->where('resource_id', $resource_id);
                $this->db->delete('r_resource');

                $this->db->where('resource_id', $resource_id);
                $this->db->delete('r_resource_flash_media');



                $this->db->trans_complete();
                return TRUE;
            } else {
                $this->db->trans_start();

                $this->db->where('resource_id', $resource_id);
                $this->db->delete('r_resource');

                $this->db->where('resource_id', $resource_id);
                $this->db->delete('r_resource_flash_media');



                $this->db->trans_complete();
                return TRUE;
            }
            return TRUE;
        }
        return FALSE;
    }

    function publish($resource_id, $publish) {
        $this->db->set('publish', $publish);
        if (is_array($resource_id)) {
            $this->db->where_in('resource_id', $resource_id);
        } else {
            $this->db->where('resource_id', $resource_id);
        }
        $this->db->where('uid_owner', $this->auth->uid());
        $this->db->update('r_resource');
        return TRUE;
    }

    function privacy($resource_id, $publish) {
        $this->db->set('privacy', $publish);
        if (is_array($resource_id)) {
            $this->db->where_in('resource_id', $resource_id);
        } else {
            $this->db->where('resource_id', $resource_id);
        }
        $this->db->where('uid_owner', $this->auth->uid());
        $this->db->update('r_resource');
        return TRUE;
    }

    function is_owner($resource_id) {
        $this->db->where('resource_id', $resource_id);
        $this->db->where('uid_owner', $this->auth->uid());
        $query = $this->db->get('r_resource');
        if ($query->num_rows() > 0) {
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

    function get_extension_whitelist() {
        return $this->flash_media_extension_whitelist;
    }

    function get_upload_extension_whitelist() {
        return $this->flash_media_upload_extension_whitelist;
    }

    function get_file_size_limit() {
        return $this->flash_media_file_size_limit;
    }

    function get_upload_temp_dir() {
        return $this->upload_temp_dir;
    }

    function get_full_flash_media_dir() {
        return $this->full_flash_media_dir;
    }

    //==============================================================================
// Extra Section
//==============================================================================
// ลบ เอกสารที่ไม่มีในตาราง
    function clean_flash_media_file() {
        $personal_dir = $this->auth->get_personal_dir();
        $dir = $this->full_flash_media_dir . $personal_dir;
        foreach (glob($dir . "*.*") as $filename) {
            $file_path = $personal_dir . basename($filename);
            $this->db->where('file_path', $file_path);
            if ($this->db->count_all_results('r_resource_flash_media') > 0) {
                
            } else {
                unlink($this->full_flash_media_dir . $file_path);
            }
        }
    }

    function upload_step2($data) {
        $result_data['data'] = $data['data'];
        $folder_name = $result_data['data']['folder_path'] = current(explode('.', $data['resume_file']));
        $upload_temp_dir = $this->get_upload_temp_dir();
        $result_data['data']['file_size'] = filesize($upload_temp_dir . $_POST['resume_file']);
        $zip = new ZipArchive;
        $res = $zip->open($upload_temp_dir . $_POST['resume_file']);
        if ($res === TRUE) {
            $zip->extractTo($upload_temp_dir . $folder_name);
            $zip->close();
            $file_path_options = $this->get_file_path_options($upload_temp_dir . $folder_name);
            if ($file_path_options) {
                $result_data['file_path_options'] = $file_path_options;
                return $result_data;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    function upload_save($data) {
        $subject_title = $this->get_subject_title($data['subj_id']);
        $chapter_title = $this->get_chapter_title($data['chapter_id']);
        $time = time();
        $input_full_folder_path = $this->upload_temp_dir . $data['folder_path'];
        $output_full_folder_path = $this->full_flash_media_dir;
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
        $resource_doc_set = array(
            'resource_id' => $resource_id,
            'file_path' => $data['file_path'],
            'folder_path' => $resource_id,
            'uid_owner' => $this->auth->uid(),
            'file_size' => $data['file_size'],
            'create_time' => $time
        );
        $this->db->set($resource_doc_set);
        $this->db->insert('r_resource_flash_media');
        $this->db->trans_complete();

        if (file_exists($input_full_folder_path)) {
            //echo $input_full_folder_path . '|' . $output_full_folder_path . $resource_id;
            $this->move_flash_media_folder($input_full_folder_path, $output_full_folder_path . $resource_id);
            return TRUE;
        }
        return FALSE;
    }

    function move_flash_media_folder($src, $dst) {
        rcopy($src, $dst, TRUE);
    }

    function get_file_path_options($folder_name) {
        $pattern = $folder_name . '/' . $this->pattern;
        $file_path_options = array();

        $files = glob($pattern, GLOB_BRACE);
        if (!empty($files)) {
            $file_path_options = array();
            foreach ($files as $v) {
                $file_path_options[basename($v)] = basename($v);
            }
            return $file_path_options;
        } else {
            return array();
        }
    }

}

function rrmdir($dir) {
    if (is_dir($dir)) {
        $files = scandir($dir);
        foreach ($files as $file)
            if ($file != "." && $file != "..")
                rrmdir("$dir/$file");
        rmdir($dir);
    }
    else if (file_exists($dir))
        unlink($dir);
}

// Function to Copy folders and files       
function rcopy($src, $dst, $del_src = FALSE) {
    if (file_exists($dst))
        rrmdir($dst);
    if (is_dir($src)) {
        mkdir($dst);
        $files = scandir($src);
        foreach ($files as $file)
            if ($file != "." && $file != "..")
                rcopy("$src/$file", "$dst/$file");
    } else if (file_exists($src))
        copy($src, $dst);
    if ($del_src)
        rrmdir($src);
}