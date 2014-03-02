<?php

/**
 * Description of vod_clone_model
 *
 * @author lojorider
 * @property ddoption_model $ddoption_model
 */
class vod_clone_model extends CI_Model {

    var $CI;
    var $full_video_dir = '';
    var $full_video_upload_temp_dir = '';
    var $resource_type_id = 1;
    var $time = 0;
    var $full_vod_video_dir = '/var/www/clients/client2/web1/web/v2/video_vod_clone/';

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
    }

    public function set_resource_type_id($resource_type_id) {
        $this->resource_type_id = $resource_type_id;
    }

    public function find_all($page, $qtype, $query, $rp, $sortname, $sortorder) {
        $publish_options = $this->CI->ddoption_model->get_publish_options();
        $privacy_options = $this->CI->ddoption_model->get_privacy_options();

        $this->update_all_file_size();
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
        $this->find_all_where('vod_resource', $qtype, $query);
// END Sql Query State
        $total = $this->db->count_all_results();
// Start Sql Query State
        $this->db->select('vod_resource.*');
        $this->find_all_where('vod_resource', $qtype, $query);
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
            $row['action'] .= '<a href="' . site_url('resource/vod_clone/clone_now/' . $row['resource_id']) . '">clone now!</a>';


            $video_data = $this->get_vod_resource_data($row['resource_id']);
            $row = array_merge($row, $video_data);
            $row['file_size'] = byte_format($row['file_size']);
            $row['duration'] = gmdate("H:i:s", $row['duration']);
            $row['thumbnail'] = '<img height="48"  src="' . site_url('resource/ntimg/video_thumbnail/' . $row['resource_id']) . '" />';
            $row['title_desc'] = '<span title="' . $row['desc'] . '">' . $row['title'] . '</span>';
            $row['publish'] = $publish_options[$row['publish']];
            $row['privacy'] = $privacy_options[$row['privacy']];
            $row['title_play'] = '<span title="' . $row['desc'] . '">' . $row['title'] . '</span>';
            if ($row['unit_price'] == -1) {
                $row['unit_price'] = $this->config->item('standard_unit_price') . ' *';
            } elseif ($row['unit_price'] == 0) {
                $row['unit_price'] = '<span class="icon-free">ฟรี</span>';
            }
            $row['is_clone_complete'] = ($this->is_clone_complete($row['resource_id'])) ? 'YES' : '-';

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

    public function is_clone_complete($resource_id_vod) {
        $this->db->where('resource_id_vod', $resource_id_vod);
        if ($this->db->count_all_results('r_resource_video') > 0) {
            return TRUE;
        }
        return FALSE;
    }

    public function clone_all() {
        $sql = "SELECT
        vod_resource.resource_id,
	vod_resource.title,
	vod_resource.unit_price,
	vod_resource.`desc`,
	vod_resource.create_time,
	vod_resource.uid_owner,
	vod_resource.publish,
	vod_resource.privacy,
	vod_resource.tags,
	vod_resource.resource_type_id,
	vod_resource.views,
	vod_resource.my_student_free,
	vod_resource.degree_id,
	vod_resource.la_id,
	vod_resource.subj_id,
	vod_resource.subject_title,
	vod_resource.chapter_id,
	vod_resource.chapter_title,
	vod_resource.category_id,
	vod_resource.resource_code,
	vod_resource.update_time,
	vod_resource.resource_id_parent,
	vod_resource.sub_chapter_title,
        vod_resource_video.file_path,
        vod_resource_video.file_size_org,
        vod_resource_video.file_size,
        vod_resource_video.duration,
        vod_resource_video.uid_owner,
        vod_resource_video.encode_complete,
        vod_resource_video.create_time
FROM
(select * from vod_resource where resource_type_id=1 and 
vod_resource.uid_owner = 2) vod_resource
INNER JOIN vod_resource_video ON vod_resource.resource_id = vod_resource_video.resource_id

";
        $q = $this->db->query($sql);
        if ($q->num_rows() > 0) {
            foreach ($q->result_array() as $r) {
                if (file_exists($this->full_vod_video_dir . $r['file_path'])) {
                    if ($this->is_clone_complete($r['resource_id'])) {
                        echo "clone complete ------------------------" . $r['file_path'] . "\n<br/>";
                    } else {
                        // rename($this->full_vod_video_dir.$r['file_path'], $this->full_video_dir.$r['file_path']);
                        $set_vod_resource = array(
                            'title' => $r['title'],
                            'unit_price' => -1,
                            'desc' => $r['desc'],
                            'create_time' => $r['create_time'],
                            'uid_owner' => $r['uid_owner'],
                            'publish' => $r['publish'],
                            'privacy' => $r['privacy'],
                            'tags' => $r['tags'],
                            'resource_type_id' => $r['resource_type_id'],
                            'views' => $r['views'],
                            'my_student_free' => $r['my_student_free'],
                            'degree_id' => $r['degree_id'],
                            'la_id' => $r['la_id'],
                            'subj_id' => $r['subj_id'],
                            'subject_title' => $r['subject_title'],
                            'chapter_id' => $r['chapter_id'],
                            'chapter_title' => $r['chapter_title'],
                            'category_id' => $r['category_id'],
                            'resource_code' => $r['resource_code'],
                            'update_time' => $r['update_time'],
                            'resource_id_parent' => $r['resource_id_parent'],
                            'sub_chapter_title' => $r['sub_chapter_title']
                        );
                        $this->db->set($set_vod_resource);
                        $this->db->insert('r_resource');
                        $resource_id = $this->db->insert_id();
                        $set_vod_resource_video = array(
                            'resource_id' => $resource_id,
                            'file_path' => $r['file_path'],
                            'file_size_org' => $r['file_size_org'],
                            'file_size' => $r['file_size'],
                            'duration' => $r['duration'],
                            'uid_owner' => $r['uid_owner'],
                            'encode_complete' => $r['encode_complete'],
                            'create_time' => $r['create_time'],
                            'resource_id_vod' => $r['resource_id']
                        );

                        $this->db->set($set_vod_resource_video);
                        $this->db->insert('r_resource_video');
                        rename($this->full_vod_video_dir . $r['file_path'], $this->full_video_dir . $r['file_path']);
                        echo $r['title'] . "\n<br/>";
                    }
                } else {
                    echo "NO------------------------" . $r['file_path'] . "\n<br/>";
                }
            }
        }
    }

    public function get_video_data($resource_id) {
        $this->update_all_file_size();
        $this->db->select('file_size_org,file_size,duration');
        $this->db->where('resource_id', $resource_id);
        $this->db->from('r_resource_video');
        $q1 = $this->db->get();
        return $q1->row_array();
    }

    public function get_vod_resource_data($resource_id) {
        $sql = "SELECT vod_resource.* ,vod_resource_video.file_size_org,vod_resource_video.file_size,vod_resource_video.duration FROM(SELECT
*
FROM
vod_resource
WHERE vod_resource.`resource_id` = $resource_id)vod_resource 
LEFT JOIN vod_resource_video
ON vod_resource.resource_id=vod_resource_video.resource_id
";
        $q1 = $this->db->query($sql);
        return $q1->row_array();
    }

    public function get_resource_data($resource_id) {
        $sql = "SELECT r_resource.* ,r_resource_video.file_size_org,r_resource_video.file_size,r_resource_video.duration FROM(SELECT
*
FROM
r_resource
WHERE r_resource.`resource_id` = $resource_id)r_resource 
LEFT JOIN r_resource_video
ON r_resource.resource_id=r_resource_video.resource_id
";
        $q1 = $this->db->query($sql);
        return $q1->row_array();
    }

//    private function get_file_size($resource_id, $use_byte_format = FALSE) {
//        $this->update_all_file_size();
//        $this->db->select('file_size_org,file_size');
//        $this->db->where('resource_id', $resource_id);
//        $this->db->from('r_resource_video');
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
        $row = array();
        if ($resource_id == '') {
            $data = $this->db->field_data('r_resource');

            foreach ($data as $v) {
                $row[$v->name] = $v->default;
            }
            $data = $this->db->field_data('r_resource_video');
            foreach ($data as $v) {
                $row[$v->name] = $v->default;
            }
        } else {
            $this->db->where('resource_id', $resource_id);
            $query = $this->db->get('r_resource');
            $row = $query->row_array();
            $this->db->where('resource_id', $resource_id);
            $query = $this->db->get('r_resource_video');
            $row = array_merge($row, $query->row_array());
        }

        return $row;
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
                $this->db->set('resource_type_id', 6);
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
        $q = $this->db->get('r_resource_video');

        if ($q->num_rows() > 0) {
            $this->db->trans_start();
            foreach ($q->result_array() as $row) {

                if (file_exists($this->full_video_dir . $row['file_path'])) {
                    if (@unlink($this->full_video_dir . $row['file_path'])) {
                        $this->db->where('resource_id', $row['resource_id']);
                        $this->db->where('uid_owner', $this->auth->uid());
                        $this->db->delete('r_resource');

                        $this->db->where('resource_id', $row['resource_id']);
                        $this->db->where('uid_owner', $this->auth->uid());
                        $this->db->delete('r_resource_video');

                        $this->db->where('resource_id_video', $row['resource_id']);
                        $this->db->delete('r_resource_video_join');
                    }
                } else {

                    $this->db->where('resource_id', $row['resource_id']);
                    $this->db->where('uid_owner', $this->auth->uid());
                    $this->db->delete('r_resource');

                    $this->db->where('resource_id', $row['resource_id']);
                    $this->db->where('uid_owner', $this->auth->uid());
                    $this->db->delete('r_resource_video');

                    $this->db->where('resource_id_video', $row['resource_id']);
                    $this->db->delete('r_resource_video_join');
                }
            }
            $this->db->trans_complete();
            return TRUE;
        } else {
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

    function get_resource_type_id($resource_id) {
        $this->db->where('resource_id', $resource_id);
        $query = $this->db->get('r_resource');
        return $query->row()->resource_type_id;
    }

//    function clean_video_file() {
//        $personal_dir = $this->auth->get_personal_dir();
//        $dir = $this->full_video_dir . $personal_dir;
//        foreach (glob($dir . "*.*") as $filename) {
//            $file_path = $personal_dir . basename($filename);
//            $this->db->where('file_path', $file_path);
//            if ($this->db->count_all_results('r_resource_video') > 0) {
//                
//            } else {
//                unlink($this->full_video_dir . $file_path);
//            }
//        }
//    }
// FOR UPDATE FILESIZE
    function update_all_file_size() {
        //$time_out = time() - 5400;  // 1.30 ชม
        $this->db->where('encode_complete', 0);
        //$this->db->where('create_time >', $time_out);
        $this->db->from('r_resource_video');
        $q1 = $this->db->get();


        if ($q1->num_rows() > 0) {
            //$this->update_all_duration();
            foreach ($q1->result_array() as $v1) {
                $path_parts = pathinfo($v1['file_path']);
                $log_full_file_path = $this->full_encode_log_dir . $v1['uid_owner'] . '_' . $path_parts['filename'] . '.log';
                if (!is_file($this->full_video_dir . $v1['file_path'])) {
                    $this->db->set('encode_complete', 1);
                } else if ($this->is_encode_video_complete($log_full_file_path)) {
                    $this->db->set('encode_complete', 1);
                    //unlink($log_full_file_path);
                }

                $file_path = $v1['file_path'];
                $file_size = @filesize($this->full_video_dir . $file_path);
                $this->db->set('file_size', $file_size);
                $this->db->where('resource_id', $v1['resource_id']);
                $this->db->update('r_resource_video');
            }
        }
    }

    function update_all_duration() {

        $this->db->where('duration', 0);
        $q = $this->db->get('r_resource_video');
        if ($q->num_rows() > 0) {
            foreach ($q->result_array() as $v) {

                $input_full_file_path = $this->full_video_dir . $v['file_path'];
                if (file_exists($input_full_file_path)) {
                    $movie = new ffmpeg_movie($input_full_file_path);
                    $duration = floor($movie->getDuration());
                    //echo $duration;
                    //echo '<br>';
                    $this->db->set('duration', $duration);
                    $this->db->where('resource_id', $v['resource_id']);
                    $this->db->update('r_resource_video');
                    unset($movie);
                }
            }
        }
    }

    function is_encode_video_complete($log_full_file_path) {
        if (!is_file($log_full_file_path)) {
            return TRUE;
        }
        $this->load->helper('file');
        $string = read_file($log_full_file_path);
        //global headers
        if (stripos($string, "headers") !== false) {
            return TRUE;
        }return FALSE;
    }

    function count_resource_join($resource_id) {
        $this->db->where('resource_id_video', $resource_id);
        return $this->db->count_all_results('r_resource_video_join');
    }

}