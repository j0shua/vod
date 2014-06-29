<?php

/**
 * Description of pvideo_manager_model
 *
 * @author lojorider
 * @property ddoption_model $ddoption_model
 */
class pvideo_manager_model extends CI_Model {

    var $CI;
    var $full_video_dir = '';
    var $full_video_upload_temp_dir = '';
    var $resource_type_id = 6;
    var $time = 0;
    var $db_parent;

    public function __construct() {
        parent::__construct();
        $this->CI = & get_instance();
        $this->CI->load->model('core/ddoption_model');
        $this->load->helper('number');
        $this->full_video_dir = $this->config->item('full_video_dir');
        $this->full_video_upload_temp_dir = $this->config->item('full_video_upload_temp_dir');
        $this->full_encode_log_dir = $this->config->item('full_encode_log_dir');
        $this->load->helper('tag');
        $this->time = time();

        $this->db_parent = $this->load->database('parent', TRUE);
        
    }

    public function set_resource_type_id($resource_type_id) {
        $this->resource_type_id = $resource_type_id;
    }

    public function find_all($page, $qtype, $query, $rp, $sortname, $sortorder) {
        $this->db->close();
        $publish_options = $this->CI->ddoption_model->get_publish_options();
        $privacy_options = $this->CI->ddoption_model->get_privacy_options();


        if ($qtype == 'custom') {
            parse_str($query, $query);
        }
        //initial data        
        $data = array(
            'rows' => array(),
            'page' => $page,
            'total' => 0
        );
// make offset 
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

        if ($sortname == 'title_desc' || $sortname == 'title_play') {
            $this->db->order_by('title', $sortorder);
        }


        $result = $this->db->get();
        $data['total'] = $total;
        $resource_id_list = array();
        foreach ($result->result_array() as $row) {
            $row['checkbox'] = '<input type = "checkbox" name = "cb_resource_id[]" value = "' . $row['resource_id'] . '" />';
            $row['count_resource_join'] = $this->count_resource_join($row['resource_id']);
            $row['action'] = '';
            if ($row['count_resource_join'] > 0) {
                $row['action'] .= '<a href="' . site_url('resource/resource_join/video_join/' . $row['resource_id']) . '">แก้ไขการเชื่อมสื่อ</a>';
            } else {
                $row['action'] .= '<a href="' . site_url('resource/resource_join/video_join/' . $row['resource_id']) . '">เพิ่มการเชื่อมสื่อ</a>';
            }
            $row['action'] .= '<a href="' . site_url('resource/video_manager/edit/' . $row['resource_id']) . '" target="_blank">แก้ไข</a>';
            $row['action'] .= '<a href="' . site_url('resource/video_manager/delete/' . $row['resource_id']) . '">ลบ</a>';
            $video_data = $this->get_resource_data($row['resource_id']);
            $row = array_merge($row, $video_data);
            $row['file_size'] = byte_format($row['file_size']);
            $row['duration'] = gmdate("H:i:s", $row['duration']);
            $row['thumbnail'] = '<img height="48"  src="' . site_url('resource/ntimg/video_thumbnail/' . $row['resource_id']) . '" />';
            $row['title_desc'] = '<span title="' . $row['desc'] . '">' . $row['title'] . '</span>';
            $row['publish'] = $publish_options[$row['publish']];
            $row['privacy'] = $privacy_options[$row['privacy']];
            $row['title_play'] = '<a href="' . site_url('v/' . $row['resource_id']) . '" target="_blank">▶</a>' . '<span title="' . $row['desc'] . '">' . $row['title'] . '</span>';
            if ($row['unit_price'] == -1) {
                $row['unit_price'] = $this->config->item('standard_unit_price') . ' *';
            } elseif ($row['unit_price'] == 0) {
                $row['unit_price'] = '<span class="icon-free">ฟรี</span>';
            }

            $data['rows'][] = array(
                'id' => $row['resource_id'],
                'cell' => $row
            );
            $resource_id_list[] = $row['resource_id'];
        }

        $data['resource_id'] = '<input onfocus="$(this).select();" id="grid_id_all" style="width:100%;margin:-3px;" type="text" value="' . implode(",", $resource_id_list) . '">';
        $data['rows'][] = array(
            'id' => '0',
            'cell' => array(
                'resource_id' => '',
                'thumbnail' => '',
                'title' => $data['resource_id'],
                'title_desc' => $data['resource_id'],
                'title_play' => $data['resource_id'],
                'tags' => '',
                'file_size' => '',
                'duration' => '',
                'publish' => '',
                'privacy' => '',
                'chapter_title' => '',
                'subject_title' => '',
                'action' => '',
                'resource_code' => '',
                'unit_price' => '',
                'checkbox' => '',
                'count_resource_join' => ''
            )
        );
        return $data;
    }
     public function iframe_find_all($page, $qtype, $query, $rp, $sortname, $sortorder) {
        $publish_options = $this->CI->ddoption_model->get_publish_options();
        $privacy_options = $this->CI->ddoption_model->get_privacy_options();
        
        if ($qtype == 'custom') {
            parse_str($query, $query);
        }
        //initial data        
        $data = array(
            'rows' => array(),
            'page' => $page,
            'total' => 0
        );
// make offset 
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

        if ($sortname == 'title_desc' || $sortname == 'title_play') {
            $this->db->order_by('title', $sortorder);
        }


        $result = $this->db->get();
        $data['total'] = $total;
        $resource_id_list = array();
        foreach ($result->result_array() as $row) {
            $row['checkbox'] = '<input type = "checkbox" name = "cb_resource_id[]" value = "' . $row['resource_id'] . '" />';
            $row['action'] = '<a href="' . site_url('resource/pvideo_manager/edit/' . $row['resource_id']) . '" target="_blank">แก้ไข</a>';
            $row['action'] .= '<a href="' . site_url('v/' . $row['resource_id']) . '" target="_blank">เล่น</a>';
            //$row['action'] .= '<a href="' . site_url('resource/video_manager/edit/' . $row['resource_id']) . '">วางขาย</a>';
            //$row['file_size'] = $this->get_file_size($row['resource_id'], TRUE);
            $video_data = $this->get_resource_data($row['resource_id']);
            $row = array_merge($row, $video_data);
            $row['file_size'] = byte_format($row['file_size']);
            $row['duration'] = gmdate("H:i:s", $row['duration']);
            $row['thumbnail'] = '<img height="48"  src="' . site_url('resource/ntimg/video_thumbnail/' . $row['resource_id']) . '" />';
            $row['title_desc'] = '<span title="' . $row['desc'] . '">' . $row['title'] . '</span>';
            $row['publish'] = $publish_options[$row['publish']];
            $row['privacy'] = $privacy_options[$row['privacy']];
            if ($row['unit_price'] == -1) {
                $row['unit_price'] = $this->config->item('standard_unit_price') . ' *';
            } elseif ($row['unit_price'] == 0) {
                $row['unit_price'] = '<span class="icon-free">ฟรี</span>';
            }

            $data['rows'][] = array(
                'id' => $row['resource_id'],
                'cell' => $row
            );
            $resource_id_list[] = $row['resource_id'];
        }

        $data['resource_id'] = '<input onfocus="$(this).select();" id="grid_id_all" style="width:100%;margin:-3px;" type="text" value="' . implode(",", $resource_id_list) . '">';
        $data['rows'][] = array(
            'id' => '0',
            'cell' => array(
                'resource_id' => '',
                'thumbnail' => '',
                'title' => $data['resource_id'],
                'title_desc' => $data['resource_id'],
                'tags' => '',
                'file_size' => '',
                'duration' => '',
                'publish' => '',
                'privacy' => '',
                'chapter_title' => '',
                'subject_title' => '',
                'action' => '',
                'resource_code' => '',
                'unit_price' => '',
                'checkbox' => ''
            )
        );
        return $data;
    }

    public function get_video_data($resource_id) {
        $this->db->close();
        
        $this->db->select('file_size_org,file_size,duration');
        $this->db->where('resource_id', $resource_id);
        $this->db->from('r_resource_video_parent');
        $q1 = $this->db->get();
        return $q1->row_array();
    }

    public function get_resource_data($resource_id) {
        $this->db->close();
        $sql = "SELECT r_resource.* ,r_resource_video_parent.file_size_org,r_resource_video_parent.file_size,r_resource_video_parent.duration FROM(SELECT
*
FROM
r_resource
WHERE r_resource.`resource_id` = $resource_id)r_resource 
LEFT JOIN r_resource_video_parent
ON r_resource.resource_id=r_resource_video_parent.resource_id
";
        $q1 = $this->db->query($sql);
        return $q1->row_array();
    }

    private function find_all_where($table_name, $qtype, $query) {
        $this->db->close();
        $this->db->where('resource_type_id', $this->resource_type_id);
        $this->db->where('uid_owner', $this->auth->uid());
        //print_r($query);
        switch ($qtype) {
            case 'custom':
                foreach ($query as $k => $v) {
                    if ($v != '') {
                        switch ($k) {
                            case 'resource_id':
                                $this->db->like($k, $v, 'after');
                                break;
                            case 'title':
//                                if (substr($v, 0, 1) == '%') {
//                                    $v = trim($v, '%');
                                $this->db->like($k, $v);
//                                } else {
//                                    $this->db->like($k, $v, 'after');
//                                }
                                break;
                            case 'desc':
                                $this->db->like($k, $v);
                                break;
                            case 'tags':
                                $this->db->like($k, $v);
                                break;
                            case 'subject_title':
                                $this->db->like($k, $v);
                                break;
                            case 'chapter_title':
                                $this->db->like($k, $v);
                                break;

                            default:
                                $this->db->like($k, $v);
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

    function get_video_form_data($resource_id = '') {
        $this->db->close();
        $row = array();
        if ($resource_id == '') {
            $data = $this->db->field_data('r_resource');

            foreach ($data as $v) {
                $row[$v->name] = $v->default;
            }
            $data = $this->db->field_data('r_resource_video_parent');
            foreach ($data as $v) {
                $row[$v->name] = $v->default;
            }
        } else {
            $this->db->where('resource_id', $resource_id);
            $query = $this->db->get('r_resource');
            $row = $query->row_array();
            $this->db->where('resource_id', $resource_id);
            $query = $this->db->get('r_resource_video_parent');
            $row = array_merge($row, $query->row_array());
        }

        return $row;
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

    
    function publish($resource_id, $publish) {
        $this->db->close();
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
        $this->db->close();
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
        $this->db->close();
        $this->db->where('resource_id', $resource_id);
        $this->db->where('uid_owner', $this->auth->uid());
        $query = $this->db->get('r_resource');
        if ($query->num_rows() > 0) {
            return TRUE;
        }
        return FALSE;
    }

    function get_resource_type_id($resource_id) {
        $this->db->close();
        $this->db->where('resource_id', $resource_id);
        $query = $this->db->get('r_resource');
        return $query->row()->resource_type_id;
    }

    function count_resource_join($resource_id) {
        $this->db->close();
        $this->db->where('resource_id_video', $resource_id);
        return $this->db->count_all_results('r_resource_video_join');
    }

    function get_uid_parent() {
        $site_id = 2;
        $this->db_parent->close();
        $this->db_parent->where('site_id', $site_id);
        $q = $this->db_parent->get('f_site');
        $r = $q->row_array();
        $uid_parent = $r['uid'];
        return $uid_parent;
    }

    function sync_parent() {
        $uid = $this->auth->uid();
        $uid_parent = $this->get_uid_parent();
        $this->db_parent->close();
        $this->db_parent->where('uid_owner', $uid_parent);
        $this->db_parent->where('resource_type_id', 1);
        $q = $this->db_parent->get('r_resource');
        if ($q->num_rows() > 0) {

            foreach ($q->result_array() as $r_resource_parent) {

                $this->db->close();
                $this->db->where('resource_id_parent', $r_resource_parent['resource_id']);
                $have_video = $this->db->count_all_results('r_resource_video_parent');

                if (!$have_video) {
                    $resource_id_parent = $r_resource_parent['resource_id'];
                    unset($r_resource_parent['resource_id']);
                    $r_resource_parent['resource_type_id'] = $this->resource_type_id;

                    $this->db_parent->close();
                    $this->db_parent->where('resource_id', $resource_id_parent);
                    $q_resource_video_parent = $this->db_parent->get('r_resource_video');


                    $r_resource_video_parent = $q_resource_video_parent->row_array();
                    //print_r($r_resource_video_parent);
                    //    echo '<br/>';
                    $this->db->close();
                    $this->db->trans_start();
                    $r_resource_parent['uid_owner'] = $uid;
                    unset($r_resource_parent['resource_id_vod']);
                    $this->db->set($r_resource_parent);
                    $this->db->insert('r_resource');



                    $r_resource_video_parent['resource_id'] = $this->db->insert_id();
                    $r_resource_video_parent['resource_id_parent'] = $resource_id_parent;
                    $r_resource_video_parent['uid_owner'] = $uid;
                    unset($r_resource_video_parent['resource_id_vod']);
                    $this->db->set($r_resource_video_parent);
                    $this->db->insert('r_resource_video_parent');
                    $this->db->trans_complete();
                    if ($this->db->trans_status() === FALSE) {
                        // generate an error... or use the log_message() function to log your error
                    }
                }
            }
        }
    }
      function save($data) {
        $subject_title = $this->get_subject_title($data['subj_id']);
        $chapter_title = $this->get_chapter_title($data['chapter_id']);
        $set = array(
            'title' => $data['title'],
            'unit_price' => $data['unit_price'],
            'desc' => $data['desc'],
            'tags' => encode_tags($data['tags']),
            'publish' => $data['publish'],
            'privacy' => $data['privacy'],
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
        if ($data['resource_id'] != '') {
            $this->db->where('resource_id', $data['resource_id']);
            $query = $this->db->get('r_resource');
            if ($query->num_rows() > 0) {
                $this->db->set($set);
                $this->db->where('resource_id', $data['resource_id']);
                $this->db->update('r_resource');
                return TRUE;
            }
        } else { //ต้องเป็นเพิ่ม file_path เคุณั้น
            if (isset($data['file_path'])) { // ถ้าส่ง file path มา
                $this->db->trans_start();
                $this->db->set($set);
                $this->db->set('resource_type_id', $this->resource_type_id);
                $this->db->set('uid_owner', $this->auth->uid());
                $this->db->insert('r_resource');
                $resource_id = $this->db->insert_id();
                $this->db->set('resource_id', $resource_id);
                $this->db->set('file_path', $data['file_path']);
                $this->db->set('encode_complete', 1);
                $this->db->set('create_time', $this->time);
                $this->db->set('duration', $data['duration']);
                $this->db->insert('r_resource_video');
                $this->db->trans_complete();
                return TRUE;
            }return FALSE;
        }
        return FALSE;
    }

}