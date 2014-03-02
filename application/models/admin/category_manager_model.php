<?php

/**
 * Description of category_manager_model
 *
 * @author lojorider
 */
class category_manager_model extends CI_Model {

    var $video_dir = '';
    

    public function __construct() {
        parent::__construct();
        $this->full_video_dir = $this->config->item('full_video_dir');
    }

    public function parent_find_all($page, $qtype, $query, $rp, $sortname, $sortorder) {
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
        $this->parent_find_all_where('t_category', $qtype, $query);
        // END Sql Query State
        $total = $this->db->count_all_results();
        // Start Sql Query State
        $this->db->select('t_category.*');
        $this->parent_find_all_where('t_category', $qtype, $query);
        // END Sql Query State
        $this->db->limit($rp, $offset);
        $this->db->order_by($sortname, $sortorder);
        $result = $this->db->get();
        $data['total'] = $total;

        foreach ($result->result_array() as $row) {
            $count_sub = $this->get_count_sub($row['id']);

            $row['action'] = '<a href="' . site_url('admin/category_manager/edit/' . $row['id']) . '">แก้ไข</a>';
            $row['action'] .= '<a href="' . site_url('admin/category_manager/sub/' . $row['id']) . '">หมวดย่อย [' . $count_sub . ']</a>';
            $row['count_video'] = 0;
            $row['count_doc'] = 0;
            $data['rows'][] = array(
                'id' => $row['id'],
                'cell' => $row
            );
        }
        $last_row_data = array(
            'count_video' => 10,
            'count_doc' => 10
        );
        $data['rows'][] = array(
            'id' => '',
            'cell' => $this->make_last_row($row, $last_row_data)
        );
        return $data;
    }

    private function parent_find_all_where($table_name, $qtype, $query) {
        $this->db->where('parent_id', 0);
        switch ($qtype) {
            case 'custom':
                foreach ($query as $k => $v) {
                    if ($v != '') {
                        $this->db->where($k, $v);
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

    public function get_count_sub($parent_id) {
        $this->db->where('parent_id', $parent_id);
        return $this->db->count_all_results('t_category');
    }

    public function get_parent_title($parent_id) {
        $this->db->where('id', $parent_id);
        $q = $this->db->get('t_category');
        return $q->row()->title;
    }

    public function sub_find_all($page, $qtype, $query, $rp, $sortname, $sortorder, $parent_id) {
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
        $this->sub_find_all_where('t_category', $qtype, $query, $parent_id);
        // END Sql Query State
        $total = $this->db->count_all_results();
        // Start Sql Query State
        $this->db->select('t_category.*');
        $this->sub_find_all_where('t_category', $qtype, $query, $parent_id);
        // END Sql Query State
        $this->db->limit($rp, $offset);
        $this->db->order_by($sortname, $sortorder);
        $result = $this->db->get();
        $data['total'] = $total;

        foreach ($result->result_array() as $row) {
            $row['action'] = '<a href="' . site_url('admin/category_manager/edit/' . $row['id']) . '">แก้ไข</a>';
            $data['rows'][] = array(
                'id' => $row['id'],
                'cell' => $row
            );
        }
        $data['rows'][] = array(
            'id' => '',
            'cell' => $this->make_last_row($row)
        );
        return $data;
    }

    private function sub_find_all_where($table_name, $qtype, $query, $parent_id) {
        $this->db->where('parent_id', $parent_id);
        switch ($qtype) {
            case 'custom':
                foreach ($query as $k => $v) {
                    if ($v != '') {
                        $this->db->where($k, $v);
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

    function get_video_form_data($resource_id) {
        $this->db->where('id', $resource_id);
        $query = $this->db->get('r_resource');
        $row = $query->row_array();
        $row['tags'] = $row['tags'];
        return $row;
    }

    function save($resource_id, $data) {
        $this->db->where('id', $resource_id);
        $query = $this->db->get('r_resource');
        if ($query->num_rows() > 0) {
            $set = array(
                'title' => $data['title'],
                'desc' => $data['desc'],
                'tags' => $this->encode_tags($data['tags']),
                'publish' => $data['publish'],
                'privacy' => $data['privacy'],
                'category' => $data['category']
            );
            $this->db->set($set);
            $this->db->where('id', $resource_id);
            $this->db->update('r_resource');
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
            $this->db->select('file_path');
            $this->db->where('resource_id', $resource_id);
            $query = $this->db->get('r_resource_video');
            $row = $query->row_array();
            if (file_exists($this->full_video_dir . $row['file_path'])) {
                if (@unlink($this->full_video_dir . $row['file_path'])) {
                    $this->db->trans_start();

                    $this->db->where('id', $resource_id);
                    $this->db->delete('r_resource');

                    $this->db->where('resource_id', $resource_id);
                    $this->db->delete('r_resource_video');

                    $this->db->trans_complete();
                    return TRUE;
                }
                return FALSE;
            } else {
                $this->db->trans_start();

                $this->db->where('id', $resource_id);
                $this->db->delete('r_resource');

                $this->db->where('resource_id', $resource_id);
                $this->db->delete('r_resource_video');

                $this->db->trans_complete();
                return TRUE;
            }
            return TRUE;
        }
        return FALSE;
    }

    function get_user_video_size() {
        
    }

    function get_user_video_size_quota() {
        //สามารถ upload ได้ จำกัด ประมาณ 25 clip
    }

    private function make_last_row($template_row, $last_row_data = array(), $bold = TRUE) {

        $data = array();
        foreach ($template_row as $k => $v) {
            if (isset($last_row_data[$k])) {
                if ($bold) {
                    $data[$k] = '<strong>'.$last_row_data[$k].'</strong>';
                } else {
                    $data[$k] = $last_row_data[$k];
                }
            } else {
                $data[$k] = '';
            }
        }
        $data['action'] = '';
        return $data;
    }

    function is_owner($resource_id) {
        $this->db->where('resource_id', $resource_id);
        //$this->db->where('uid_owner', $this->auth->uid());
        $query = $this->db->get('r_resource');
        if ($query->num_rows() > 0) {
            return TRUE;
        }
        return FALSE;
    }

    //==============================================================================
// Extra Section
//==============================================================================
    /**
     * get_publish_options
     * ค่า Default ควรเป็น 1
     * @return array option for form_dropdown
     */
    function get_publish_options() {
        $options = array(
            0 => 'ไม่ตีพิมพ์',
            1 => 'ตีพิมพ์'
        );
        return $options;
    }

    /**
     * get_privacy_options
     * ค่า Default ควรเป็น 1
     * @return array option for form_dropdown
     */
    function get_privacy_options() {
        $options = array(
            0 => 'ใช้ส่วนตัวเท่านั้น',
            1 => 'ให้ผู้อื่นใช้ได้ด้วย'
        );
        return $options;
    }

    function get_category_options() {
        $options = array();
        $this->db->where('parent_id', 0);
        $this->db->from('t_category');
        $q1 = $this->db->get();
        if ($q1->num_rows() > 0) {
            foreach ($q1->result_array() as $k1 => $v1) {
                $this->db->where('parent_id', $v1['id']);
                $this->db->from('t_category');
                $q2 = $this->db->get();
                $value = array();
                if ($q2->num_rows() > 0) {

                    foreach ($q2->result_array() as $v2) {
                        $value[$v2['id']] = $v2['title'];
                    }
                }
                $value[$v1['id']] = $v1['title'] . ' ทั่วไป';
                $options[$v1['title']] = $value;
            }
        }
        //print_r($options);
        //exit();
        return $options;
    }

    function encode_tags($tags) {
        $a_tags = array();
        $tags = explode(' ', str_replace(',', ' ', $tags));
        foreach ($tags as $v) {
            $a_tags[] = trim($v);
        }
        return implode(' ', $a_tags);
    }

    function clean_video_file() {
        $personal_dir = $this->auth->get_personal_dir();
        $dir = $this->full_video_dir . $personal_dir;
        foreach (glob($dir . "*.*") as $filename) {
            $file_path = $personal_dir . basename($filename);
            $this->db->where('file_path', $file_path);
            if ($this->db->count_all_results('r_resource_video') > 0) {
                
            } else {
                unlink($this->full_video_dir . $file_path);
            }
        }
    }

}