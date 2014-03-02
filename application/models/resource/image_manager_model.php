<?php

/**
 * Description of image_manager_model
 *
 * @author lojorider
 */
class image_manager_model extends CI_Model {

    var $CI;
    var $image_dir = '';
    var $resource_type_id = 3;

    public function __construct() {
        parent::__construct();
        $this->CI = & get_instance();
        $this->CI->load->model('core/ddoption_model');
        $this->load->helper('number');
        $this->full_image_dir = $this->config->item('full_image_dir');
        $this->load->helper('tag');
        $this->time = time();
    }

    public function find_all($page, $qtype, $query, $rp, $sortname, $sortorder) {
        $publish_options = $this->CI->ddoption_model->get_publish_options();
        $privacy_options = $this->CI->ddoption_model->get_privacy_options();
        if ($qtype == 'custom') {
            parse_str($query, $query);
        }
        //set now timestamp
        //$time = time();
        //initial data        
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

        foreach ($result->result_array() as $row) {
            $row['checkbox'] = '<input type = "checkbox" name = "cb_resource_id[]" value = "' . $row['resource_id'] . '" />';
//            $row['action'] = '<a href="' . site_url('v/' . $row['resource_id']) . '" target="_blank">เปิดดูภาพ</a>';
            $row['action'] = '<a href="' . site_url('resource/image_manager/edit/' . $row['resource_id']) . '">แก้ไข</a>';
            $row['action'] .= '<a href="' . site_url('resource/image_manager/delete/' . $row['resource_id']) . '">ลบ</a>';

            $row['publish'] = $publish_options[$row['publish']];
            $row['privacy'] = $privacy_options[$row['privacy']];
            $image_data = $this->get_image_data($row['resource_id']);
            $row = array_merge($row, $image_data);
            $row['thumbnail'] = '<img width="100"  src="' . site_url('ztatic/resource_image/' . $row['resource_id']) . '" />';
            $row['title'] = '<span title="' . $row['title'] . "\n" . $row['desc'] . '">' . $row['title'] . '</span>';
            $row['title_play'] = '<a href="' . site_url('v/' . $row['resource_id']) . '" target="_blank">▶</a>' . $row['title'];
            if ($row['update_time'] == 0) {
                $row['update_time'] = thdate('d-M-Y H:i น.', $row['create_time']);
            } else {
                $row['update_time'] = thdate('d-M-Y H:i น.', $row['update_time']);
            }
            $row['create_time'] = thdate('d-M-Y H:i น.', $row['create_time']);

            $data['rows'][] = array(
                'id' => $row['resource_id'],
                'cell' => $row
            );
        }


        return $data;
    }

    public function iframe_find_all($page, $qtype, $query, $rp, $sortname, $sortorder, $input_form_id) {
        $publish_options = $this->CI->ddoption_model->get_publish_options();
        $privacy_options = $this->CI->ddoption_model->get_privacy_options();
        if ($qtype == 'custom') {
            parse_str($query, $query);
        }
        //set now timestamp
        //$time = time();
        //initial data        
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

        foreach ($result->result_array() as $row) {
            $image_data = $this->get_image_data($row['resource_id']);
//            $row['action'] = '<a href=\'javascript:insert_image("' . $image_data['file_path'] . '")\'>แทรกภาพ</a>';
            $row['action'] = '<a href="' . site_url('resource/image_manager/iframe_edit/' . $row['resource_id'] . '/' . $input_form_id) . '">แก้ไข</a>';
            $row['action'] .= '<a href="' . site_url('resource/image_manager/iframe_delete/' . $row['resource_id'] . '/' . $input_form_id) . '">ลบ</a>';

            $row['publish'] = $publish_options[$row['publish']];
            $row['privacy'] = $privacy_options[$row['privacy']];
            $image_data = $this->get_image_data($row['resource_id']);
            $row = array_merge($row, $image_data);
            $row['thumbnail'] = '<img width="100"  src="' . site_url('ztatic/resource_image/' . $row['resource_id']) . '" />';
            $row['title_play'] = '<a href="' . site_url('v/' . $row['resource_id']) . '" target="_blank">▶</a>' . $row['title'];
            $row['action_insert'] = '<a href=\'javascript:insert_image("' . $image_data['file_path'] . '")\'>แทรกภาพ</a>';
            $data['rows'][] = array(
                'id' => $row['resource_id'],
                'cell' => $row
            );
        }


        return $data;
    }

    private function get_image_data($resource_id) {

//        $this->db->select('file_size');
//        $this->db->select('file_ext');
//        $this->db->select('file_path');
        $this->db->where('resource_id', $resource_id);
        $this->db->from('r_resource_image');
        $q1 = $this->db->get();
        $row = array();
        if ($q1->num_rows() > 0) {
            $row = $q1->row_array();
            if ($row['file_ext'] == '') {
                $row['file_ext'] = pathinfo($row['file_path'], PATHINFO_EXTENSION);
                $this->db->set('file_ext', $row['file_ext']);
                $this->db->where('resource_id', $resource_id);
                $this->db->update('r_resource_image');
            }
            $row['h_file_size'] = byte_format($row['file_size']);
        } else {
            $data = $this->db->field_data('r_resource_image');
            foreach ($data as $field) {
                $row[$field->name] = $field->default;
            }
        }

        return $row;
    }

//    private function get_file_size($resource_id, $use_byte_format = FALSE) {
//
//        $this->db->select('file_size');
//        $this->db->where('resource_id', $resource_id);
//        $this->db->from('r_resource_image');
//        $q1 = $this->db->get();
//        $row = $q1->row_array();
//        if ($use_byte_format) {
//            $file_size = byte_format($row['file_size']);
//        } else {
//            $file_size = $row['file_size'];
//        }
//        return $file_size;
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

    function get_image_form_data($resource_id) {
        $this->db->where('resource_id', $resource_id);
        $query = $this->db->get('r_resource');
        $row = $query->row_array();
        $row['tags'] = $row['tags'];
        return $row;
    }

    function save($resource_id, $data) {
        $subject_title = $this->get_subject_title($data['subj_id']);
        $chapter_title = $this->get_chapter_title($data['chapter_id']);
        $this->db->where('resource_id', $resource_id);
        $query = $this->db->get('r_resource');
        if ($query->num_rows() > 0) {
            $set = array(
                'title' => $data['title'],
                'desc' => $data['desc'],
                'tags' => encode_tags($data['tags']),
                'publish' => $data['publish'],
                'privacy' => $data['privacy'],
                //new field
                'degree_id' => $data['privacy'],
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

    function delete($resource_id) {
        if (is_array($resource_id)) {
            $this->db->where_in('resource_id', $resource_id);
        } else {
            if ($resource_id == '') {
                return FALSE;
            }
            $this->db->where('resource_id', $resource_id);
        }

        $this->db->select('file_path');
        $this->db->select('resource_id');
        $this->db->where('uid_owner', $this->auth->uid());
        $q = $this->db->get('r_resource_image');

        if ($q->num_rows() > 0) {
            $this->db->trans_start();
            foreach ($q->result_array() as $row) {

                if (file_exists($this->full_image_dir . $row['file_path'])) {
                    if (@unlink($this->full_image_dir . $row['file_path'])) {
                        $this->db->where('resource_id', $row['resource_id']);
                        $this->db->where('uid_owner', $this->auth->uid());
                        $this->db->delete('r_resource');

                        $this->db->where('resource_id', $row['resource_id']);
                        $this->db->where('uid_owner', $this->auth->uid());
                        $this->db->delete('r_resource_image');
                    }
                } else {

                    $this->db->where('resource_id', $row['resource_id']);
                    $this->db->where('uid_owner', $this->auth->uid());
                    $this->db->delete('r_resource');

                    $this->db->where('resource_id', $row['resource_id']);
                    $this->db->where('uid_owner', $this->auth->uid());
                    $this->db->delete('r_resource_image');
                }
            }
            $this->db->trans_complete();
            return TRUE;
        } else {
            if (is_array($resource_id)) {
                $this->db->where_in('resource_id', $resource_id);
            } else {
                if ($resource_id == '') {
                    return FALSE;
                }
                $this->db->where('resource_id', $resource_id);
            }
            $this->db->where('resource_type_id', 3);
            $q = $this->db->get('r_resource');

            if ($q->num_rows() > 0) {
                foreach ($q->result_array() as $v) {
                    $this->db->where('resource_id', $v['resource_id']);
                    $this->db->where('uid_owner', $this->auth->uid());
                    $this->db->delete('r_resource');
                }
                return TRUE;
            }
            return FALSE;
        }
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

    //==============================================================================
// Extra Section
//==============================================================================
// ลบ เอกสารที่ไม่มีในตาราง
    function clean_image_file() {
        $personal_dir = $this->auth->get_personal_dir();
        $dir = $this->full_image_dir . $personal_dir;
        foreach (glob($dir . "*.*") as $filename) {
            $file_path = $personal_dir . basename($filename);
            $this->db->where('file_path', $file_path);
            if ($this->db->count_all_results('r_resource_image') > 0) {
                
            } else {
                unlink($this->full_image_dir . $file_path);
            }
        }
    }

}
