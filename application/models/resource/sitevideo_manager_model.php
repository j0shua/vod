<?php

/**
 * Description of sitevideo_manager_model
 *
 * @author lojorider
 */
class sitevideo_manager_model extends CI_Model {

    var $video_dir = '';
    var $resource_type_id = 3;
    var $time = 0;

    public function __construct() {
        parent::__construct();
        $this->full_video_dir = $this->config->item('full_video_dir');
        $this->load->helper('tag');
        $this->time = time();
    }

    public function find_all($page, $qtype, $query, $rp, $sortname, $sortorder) {
        if ($qtype == 'custom') {
            parse_str($query, $query);
        }
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
        $resource_id_list = array();
        foreach ($result->result_array() as $row) {
            $row['action'] = '<a href="' . site_url('resource/sitevideo_manager/edit/' . $row['resource_id']) . '">แก้ไข</a>';
            $row['action'] .= '<a href="' . site_url('v/' . $row['resource_id']) . '" target="_blank">เล่น</a>';
            $row['action'] .= '<a href="' . site_url('resource/sitevideo_manager/delete/' . $row['resource_id']) . '">ลบ</a>';

            $row['publish'] = $publish_options[$row['publish']];
            $row['privacy'] = $privacy_options[$row['privacy']];
            $row['duration'] = '';
            $data['rows'][] = array(
                'id' => $row['resource_id'],
                'cell' => $row
            );
            $resource_id_list[] = $row['resource_id'];
        }
        $data['resource_id'] = implode(",", $resource_id_list);
        $data['rows'][] = array(
            'id' => '0',
            'cell' => array(
                'resource_id' => '',
                'title' => $data['resource_id'],
                'duration' => '',
                'publish' => '',
                'privacy' => '',
                'action' => ''
            )
        );
        return $data;
    }

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

    function get_video_form_data($resource_id = '') {
        if ($resource_id == '') {
            $data = $this->db->field_data('r_resource');
            $row = array();
            foreach ($data as $v) {
                $row[$v->name] = $v->default;
            }
            $data = $this->db->field_data('r_resource_sitevideo');
            foreach ($data as $v) {
                $row[$v->name] = $v->default;
            }
        } else {
            $this->db->where('resource_id', $resource_id);
            $query = $this->db->get('r_resource');
            $row = $query->row_array();
            $this->db->select('url_video');
            $this->db->where('resource_id', $resource_id);
            $q2 = $this->db->get('r_resource_sitevideo');
            if ($q2->num_rows() > 0) {
                $row['url_video'] = $q2->row()->url_video;
            } else {
                $row['url_video'] = '';
            }
        }

        return $row;
    }

    function save($resource_id, $data) {
        $sitevideo_data = $this->get_sitevideo_data($data['url_video']);
        if (!$sitevideo_data) {
            return FALSE;
        }
//$this->get_sitevideo_data = '';
        if ($resource_id == '') {
            $this->db->trans_start();
            $set = array(
                'title' => $data['title'],
                'create_time' => $this->time,
                'uid_owner' => $this->auth->uid(),
                'resource_type_id' => $this->resource_type_id,
                'desc' => $data['desc'],
                'tags' => encode_tags($data['tags']),
                'publish' => $data['publish'],
                'privacy' => $data['privacy'],
                'category_id' => $data['category_id'],
            );
            $this->db->set($set);
            $this->db->insert('r_resource');
            $resource_id = $this->db->insert_id();
            $v_data = array(
                'resource_id' => $resource_id,
                'video_code' => $sitevideo_data['video_code'],
                'url_video' => $data['url_video'],
                'videosite_id' => $sitevideo_data['videosite_id'],
                'uid_owner' => $this->auth->uid()
            );
            $this->db->set($v_data);
            $this->db->insert('r_resource_sitevideo');

            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                return FALSE;
            }
            return TRUE;
        } else {
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
                    'category_id' => $data['category_id']
                );
                $this->db->set($set);
                $this->db->where('resource_id', $resource_id);
                $this->db->update('r_resource');

                $v_data = array(
                    'resource_id' => $resource_id,
                    'video_code' => $sitevideo_data['video_code'],
                    'url_video' => $data['url_video'],
                    'videosite_id' => $sitevideo_data['videosite_id']
                );
                $this->db->set($v_data);
                $this->db->where('resource_id', $resource_id);
                $this->db->update('r_resource_sitevideo');
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    return FALSE;
                }
                return TRUE;
            }


            return FALSE;
        }
    }

    function get_video_data($resource_id) {
        $this->db->where('resource_id', $resource_id);
        $q1 = $this->db->get('r_resource_sitevideo');
        return $q1->row()->url_video;
    }

    function get_sitevideo_data($url) {
        $videosite_id = $this->get_videosite_id($url);
        $site_url = parse_url($url, PHP_URL_HOST);
        switch ($videosite_id) {
            case 1:
                $video_code = $this->get_youtube_video_code($url);
                break;
            case 2:

                $video_code = $this->get_dailymotion_video_code($url);

                break;
            default:
                return FALSE;
                break;
        }

        $data = array(
            'video_code' => $video_code,
            'site_url' => $site_url,
            'videosite_id' => $videosite_id
        );
        return $data;
    }

    function get_videosite_id($url) {
        $site_url = parse_url($url, PHP_URL_HOST);
        $this->db->where('site_url', $site_url);
        $q1 = $this->db->get('r_videosite');
        if ($q1->num_rows() > 0) {
            return $q1->row()->videosite_id;
        }
        return FALSE;
    }

    function delete($resource_id) {
        $this->db->where('resource_id', $resource_id);
        $result = $this->db->get('r_resource');
        if ($result->num_rows() > 0) {

            $this->db->trans_start();

            $this->db->where('resource_id', $resource_id);
            $this->db->delete('r_resource');

            $this->db->where('resource_id', $resource_id);
            $this->db->delete('r_resource_sitevideo');

            $this->db->trans_complete();
            return TRUE;
        }
        return FALSE;
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

    function get_youtube_video_code($url) {
        $url_string = parse_url($url, PHP_URL_QUERY);
        $args = array();
        parse_str($url_string, $args);
        if (isset($args['v'])) {
            return $args['v'];
        } else {
            return FALSE;
        }
    }

    function get_dailymotion_video_code($url) {

        $url_string = explode('/', trim(parse_url($url, PHP_URL_PATH), '/'));

        $video_id = current(explode('_', $url_string[1]));
        if ($video_id == '') {
            return FALSE;
        } else {
            return $video_id;
        }
    }

}