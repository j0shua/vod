<?php

/**
 * Description of nttransfer_model
 *
 * @author lojorider
 * @property CI_DB_active_record $nt_db
 */
class nttransfer_model extends CI_Model {

    var $CI;
    var $nt_db;
    var $nt_db_config = array('hostname' => "localhost",
        'username' => "lojorider",
        'password' => "XHNJBKNO4",
        'database' => "lojo_noobthink",
        'dbdriver' => "mysql",
        'dbprefix' => "",
        'pconnect' => FALSE,
        'db_debug' => TRUE,
        'cache_on' => FALSE,
        'cachedir' => "",
        'char_set' => "utf8",
        'dbcollat' => "utf8_general_ci");
    var $full_video_dir;

    public function __construct() {
        parent::__construct();
        $this->load->helper('number');
        $this->load->helper('tag');
        $this->time = time();
        $this->CI = & get_instance();
        $this->CI->load->model('core/ddoption_model');
        $this->full_video_dir = $this->config->item('full_video_dir');
    }

    function test() {
        $this->nt_db = $this->load->database($this->nt_db_config, TRUE);
        $this->nt_db->where('uid_owner', 9);
        $this->nt_db->where('tid_parent', 0);
        $this->nt_db->where('publish', 1);
        $this->nt_db->order_by('weight');
        $q = $this->nt_db->get('r_taxonomy');
        print_r($q->result_array());
    }

    // for main
    public function taxonomy_find_all($page, $qtype, $query, $rp, $sortname, $sortorder) {
        $this->nt_db = $this->load->database($this->nt_db_config, TRUE);

        $publish_options = $this->CI->ddoption_model->get_taxonomy_publish_options();
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
        $this->taxonomy_find_all_where('r_taxonomy', $qtype, $query);
// END Sql Query State
        $total = $this->nt_db->count_all_results();
// Start Sql Query State
        $this->nt_db->select('r_taxonomy.*');
        $this->taxonomy_find_all_where('r_taxonomy', $qtype, $query);
// END Sql Query State
        $this->nt_db->limit($rp, $offset);

        $this->nt_db->order_by($sortname, $sortorder);
        $result = $this->nt_db->get();
        $data['total'] = $total;

        foreach ($result->result_array() as $row) {
            $count_sub = $this->count_sub($row['tid']);

            $row['action'] = '<a href="' . site_url('resource/nttransfer/taxonomy_sub/' . $row['tid']) . '">บทในชุดวิดีโอ</a>';
            $row['action'] .= '<a href="' . site_url('resource/nttransfer/transfer/' . $row['tid']) . '">Transfer</a>';
//            $row['action'] .= '<a href="' . site_url('resource/taxonomy_manager/edit/' . $row['tid']) . '">แก้ไข</a>';
////if ($count_sub == 0) {
//            if (TRUE) {
//                $row['action'] .= '<a href="' . site_url('resource/taxonomy_manager/delete/' . $row['tid']) . '">ลบ</a>';
//            }
            $row['publish'] = $publish_options[$row['publish']];
            $row['count_sub'] = $count_sub;
            //$row['title_link'] = '<a href="' . site_url('house/u/' . $this->auth->uid() . '/' . $row['tid']) . '" target="_blank">' . $row['title'] . '</a>';
            //$row['title_play'] = '<a href="' . site_url('house/u/' . $this->auth->uid() . '/' . $row['tid']) . '" target="_blank">เปิดดู</a>' . '<span title="' . $row['desc'] . '">' . $row['title'] . '</span>';
            $row['title_play'] = $row['title'];

            $data['rows'][] = array(
                'id' => $row['tid'],
                'cell' => $row
            );
        }
        return $data;
    }

    private function taxonomy_find_all_where($table_name, $qtype, $query) {
        $this->nt_db->where('tid_parent', 0);
        $this->nt_db->where('uid_owner', $this->auth->uid());
        switch ($qtype) {
            case 'custom':
                foreach ($query as $k => $v) {
                    if ($v != '') {
                        $this->nt_db->where($k, $v);
                    }
                }
                break;
            default:
                if ($query != '') {
                    $this->nt_db->where($qtype, $query);
                }
                break;
        }
        $this->nt_db->from($table_name);
    }

    function count_sub($tid_parent) {
        $this->nt_db->where('tid_parent', $tid_parent);
        return $this->nt_db->count_all_results('r_taxonomy');
    }

    // SUB ==========================================================================
    public function taxonomy_sub_find_all($page, $qtype, $query, $rp, $sortname, $sortorder, $tid_parent) {
        $this->nt_db = $this->load->database($this->nt_db_config, TRUE);

        $publish_options = $this->CI->ddoption_model->get_taxonomy_publish_options();
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
        $this->taxonomy_sub_find_all_where('r_taxonomy', $qtype, $query, $tid_parent);
// END Sql Query State
        $total = $this->nt_db->count_all_results();
// Start Sql Query State
        $this->nt_db->select('r_taxonomy.*');
        $this->taxonomy_sub_find_all_where('r_taxonomy', $qtype, $query, $tid_parent);
// END Sql Query State
        $this->nt_db->limit($rp, $offset);

        $this->nt_db->order_by($sortname, $sortorder);
        $result = $this->nt_db->get();
        $data['total'] = $total;

        foreach ($result->result_array() as $row) {
            $row['action'] = '';
//            $row['action'] = '<a href="' . site_url('resource/taxonomy_manager/sub_edit/' . $row['tid']) . '">แก้ไข</a>';
//            $row['action'] .= '<a href="' . site_url('resource/taxonomy_manager/sub_delete/' . $row['tid']) . '">ลบ</a>';
            $row['publish'] = $publish_options[$row['publish']];
            //  $resource_id_video = current(explode(',', $row['data']));
            $row['title_play'] = $row['title'];
//            if ($resource_id_video) {
//                $row['title_play'] = '<a href="' . site_url('v/' . $resource_id_video . '?pltid=' . $row['tid']) . '" target="_blank">▶ ดูวิดีโอ</a>' . $row['title'];
//            } else {
//                $row['title_play'] = $row['title'];
//            }

            $data['rows'][] = array(
                'id' => $row['tid'],
                'cell' => $row
            );
        }
        return $data;
    }

    private function taxonomy_sub_find_all_where($table_name, $qtype, $query, $tid_parent) {
        $this->nt_db->where('tid_parent', $tid_parent);
        $this->nt_db->where('uid_owner', $this->auth->uid());
        switch ($qtype) {
            case 'custom':
                foreach ($query as $k => $v) {
                    if ($v != '') {
                        $this->nt_db->where($k, $v);
                    }
                }
                break;
            default:
                if ($query != '') {
                    $this->nt_db->where($qtype, $query);
                }
                break;
        }
        $this->nt_db->from($table_name);
    }

    function transfer($tid_parent) {
        $this->nt_db = $this->load->database($this->nt_db_config, TRUE);
        $this->nt_db->where('tid', $tid_parent);
        $q = $this->nt_db->get('r_taxonomy');
        $row_parent = $q->row_array();
        unset($row_parent['tid']);
        $this->db->set($row_parent);
        $this->db->insert('r_taxonomy');
        $new_tid_parent = $this->db->insert_id();

        $this->nt_db->where('tid_parent', $tid_parent);
        $q_child_temp = $this->nt_db->get('r_taxonomy');
        foreach ($q_child_temp->result_array() as $v1) {
            $new_resource_id = array();
            unset($v1['tid']);
            $v1['tid_parent'] = $new_tid_parent;
            $this->nt_db->where_in('resource_id', explode(',', $v1['data']));
            $q_nt = $this->nt_db->get('r_resource');
            foreach ($q_nt->result_array() as $v2) {
                $this->nt_db->where('resource_id', $v2['resource_id']);
                $q_nt_video = $this->nt_db->get('r_resource_video');
                $r_nt_video = $q_nt_video->row_array();
                unset($v2['resource_id']);
                $v2['unit_price'] = '-1';
                $this->db->set($v2);
                $this->db->insert('r_resource');
                $insert_id = $this->db->insert_id();
                $new_resource_id[] = $insert_id;
                $r_nt_video['resource_id'] = $insert_id;
                //$r_nt_video['file_path'] = str_replace('mp4', 'flv', $r_nt_video['file_path']);
                //$r_nt_video['file_path'] = str_replace('mp4', 'flv', $r_nt_video['file_path']);
                $this->db->set($r_nt_video);
                $this->db->insert('r_resource_video');
            }
            $v1['data'] = implode(',', $new_resource_id);
            $this->db->set($v1);
            $this->db->insert('r_taxonomy');
        }
    }

    function transfer_video() {
        $this->load->library('phpavconv');
        $input_full_file_path = '/var/www/clients/client3/web16/video/1/9/1354707261.mp4';
        $output_full_file_path = $this->full_video_dir . '1/9/1354707261.flv';
        $this->phpavconv->encode($input_full_file_path, $output_full_file_path);
    }

    function update_time() {
        $input_full_file_path = '/var/www/clients/client3/web16/video/';
        $this->db->where('duration', 0);
        $q = $this->db->get('r_resource_video');
        foreach ($q->result_array() as $v) {
            $p = $input_full_file_path . $v['file_path'];
            $movie = new ffmpeg_movie($p);
            //echo $movie->getDuration().'|';
            $this->db->set('duration',$movie->getDuration());
            $this->db->where('resource_id',$v['resource_id']);
            $this->db->update('r_resource_video');
        }
    }

}