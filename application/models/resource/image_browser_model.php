<?php

/**
 * Description of image_browser_model
 *
 * @author lojorider
 */
class image_browser_model extends CI_Model {

    var $doc_dir = '';
    var $resource_type_id = 3;
    var $a_image_ext = array(
        'png', 'jpg', 'gif', 'jpeg', 'eps', 'pdf'
    );

    public function __construct() {
        parent::__construct();
        $this->load->helper('number');
        $this->full_doc_dir = $this->config->item('full_doc_dir');
        $this->load->helper('tag');
        $this->time = time();
    }

    public function find_all($page, $qtype, $query, $rp, $sortname, $sortorder) {
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
        $this->db->select('r_resource_doc.file_path,r_resource_doc.file_ext,r_resource_doc.file_size');

        $this->find_all_where('r_resource', $qtype, $query);
        // END Sql Query State
        $this->db->limit($rp, $offset);
        $this->db->order_by($sortname, $sortorder);

        $result = $this->db->get();
        $data['total'] = $total;

        foreach ($result->result_array() as $row) {

            $row['action'] = '<a href=\'javascript:insert_image("' . $row['file_path'] . '")\'>แทรกภาพ</a>';
            $row['publish'] = $publish_options[$row['publish']];
            $row['privacy'] = $privacy_options[$row['privacy']];
            $doc_data = $this->get_doc_data($row['resource_id']);
            $row = array_merge($row, $doc_data);
            $row['h_file_size'] = byte_format($row['file_size']);
            $data['rows'][] = array(
                'id' => $row['resource_id'],
                'cell' => $row
            );
        }


        return $data;
    }

    private function get_doc_data($resource_id) {
        //echo $resource_id;
        $this->db->select('file_size');
        $this->db->select('file_ext');
        $this->db->select('file_path');
        $this->db->where('resource_id', $resource_id);
        $this->db->from('r_resource_image');
        $q1 = $this->db->get();
        $row = $q1->row_array();
        if ($row['file_ext'] == '') {
            $row['file_ext'] = pathinfo($row['file_path'], PATHINFO_EXTENSION);
            $this->db->set('file_ext', $row['file_ext']);
            $this->db->where('resource_id', $resource_id);
            $this->db->update('r_resource_doc');
        }
        $row['h_file_size'] = byte_format($row['file_size']);
        return $row;
    }


    private function find_all_where($table_name, $qtype, $query) {
        $image_ext = '"' . implode('","', $this->a_image_ext) . '"';
        $this->db->join('(select resource_id,file_ext,file_path,file_size from r_resource_doc where  uid_owner="' . $this->auth->uid() . '" and file_ext in(' . $image_ext . ') )r_resource_doc ', 'r_resource_doc.resource_id=r_resource.resource_id');
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
                            case 'category_id':
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

    function get_doc_form_data($resource_id) {
        $this->db->where('resource_id', $resource_id);
        $query = $this->db->get('r_resource');
        $row = $query->row_array();
        $row['tags'] = $row['tags'];
        return $row;
    }

    function save($resource_id, $data) {
        $this->db->where('resource_id', $resource_id);
        $query = $this->db->get('r_resource');
        if ($query->num_rows() > 0) {
            $set = array(
                'title' => $data['title'],
                'desc' => $data['desc'],
                'tags' => encode_tags($data['tags']),
                'publish' => $data['publish'],
                'privacy' => $data['privacy'],
                'category_id' => $data['category_id']
            );
            $this->db->set($set);
            $this->db->where('resource_id', $resource_id);
            $this->db->update('r_resource');
            return TRUE;
        }
        return FALSE;
    }

    function delete($resource_id) {
        $this->db->where('resource_id', $resource_id);
        $result = $this->db->get('r_resource');
        if ($result->num_rows() > 0) {
            $this->db->select('file_path');
            $this->db->where('resource_id', $resource_id);
            $query = $this->db->get('r_resource_doc');
            $row = $query->row_array();
            if (file_exists($this->full_doc_dir . $row['file_path'])) {
                if (@unlink($this->full_doc_dir . $row['file_path'])) {
                    $this->db->trans_start();

                    $this->db->where('resource_id', $resource_id);
                    $this->db->delete('r_resource');

                    $this->db->where('resource_id', $resource_id);
                    $this->db->delete('r_resource_doc');

                    $this->db->trans_complete();
                    return TRUE;
                }
                return FALSE;
            } else {
                $this->db->trans_start();

                $this->db->where('resource_id', $resource_id);
                $this->db->delete('r_resource');

                $this->db->where('resource_id', $resource_id);
                $this->db->delete('r_resource_doc');

                $this->db->trans_complete();
                return TRUE;
            }
            return TRUE;
        }
        return FALSE;
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
    function clean_doc_file() {
        $personal_dir = $this->auth->get_personal_dir();
        $dir = $this->full_doc_dir . $personal_dir;
        foreach (glob($dir . "*.*") as $filename) {
            $file_path = $personal_dir . basename($filename);
            $this->db->where('file_path', $file_path);
            if ($this->db->count_all_results('r_resource_doc') > 0) {
                
            } else {
                unlink($this->full_doc_dir . $file_path);
            }
        }
    }

}