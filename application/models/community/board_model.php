<?php

class board_model extends CI_Model {

    var $time;
    var $strip_tag_allow;
    var $title_str_limit = 45;

    public function __construct() {
        parent::__construct();
        $this->time = time();
        $this->load->config('board');
        $this->strip_tag_allow = $this->config->item('strip_tag_allow');
        $this->load->helper('bbcode');
        $this->load->library('xelatex');
    }

    public function find_all($page, $qtype, $query, $rp, $sortname, $sortorder, $board_type_id) {
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
        $this->find_all_where('c_post', $qtype, $query, $board_type_id);
        // END Sql Query State
        $total = $this->db->count_all_results();
        // Start Sql Query State
        $this->db->select('c_post.*');
        $this->find_all_where('c_post', $qtype, $query, $board_type_id);
        // END Sql Query State
        $this->db->limit($rp, $offset);
        $this->db->order_by($sortname, $sortorder);
        $result = $this->db->get();
        $data['total'] = $total;

        foreach ($result->result_array() as $row) {
            $row['user_post_fullname'] = $this->get_user_fullname($row['uid_post']);
            $row['action'] = '';
            if ($this->auth->is_admin()) {
                $row['delete_url'] = site_url('community/board/delete/' . $row['p_id']);
            }
            $row['count_reply'] = $this->count_reply($row['p_id']);
            $title_temp = mb_substr($row['title'], 0, $this->title_str_limit, 'UTF-8');
            if ($title_temp != $row['title']) {
                $row['title'] = $title_temp . '...';
            }
            $data['rows'][] = array(
                'id' => $row['p_id'],
                'cell' => $row
            );
        }
        $board_type_data = $this->get_board_type_data($board_type_id);
        $data['board_type_id'] = $board_type_id;
        $data['title'] = $board_type_data['title'];

        return $data;
    }

    function count_reply($p_pid) {
        $this->db->where('p_id_parent', $p_pid);
        return $this->db->count_all_results('c_post');
    }

    function get_board_type_data($board_type_id) {
        $this->db->where('board_type_id', $board_type_id);
        return $this->db->get('c_board_type')->row_array();
    }

    private function find_all_where($table_name, $qtype, $query, $board_type_id) {
        $this->db->where('uid_owner', 0);
        $this->db->where('p_id_parent', 0);
        $this->db->where('board_type_id', $board_type_id);
        switch ($qtype) {
            case 'custom':
                foreach ($query as $k => $v) {
                    if ($v != '') {
                        switch ($k) {
                            case 'p_id':
                                $this->db->like($k, $v, 'after');
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

    function get_post_data($p_id) {
        $this->db->where('p_id', $p_id);
        $q = $this->db->get('c_post');
        if ($q->num_rows() > 0) {
            $row = $q->row_array();


            $row['body'] = nl2br($this->xelatex->render_bbcode(strip_tags(parse_bbcode($row['body']), $this->strip_tag_allow)));
            return $row;
        }
        return FALSE;
    }

    function get_reply_data($p_id) {
        //$this->db->from('u_user_detail');
        //$this->db->where('c_post.uid_post=u_user_detail.uid', NULL, FALSE);
        $this->db->where('c_post.p_id_parent', $p_id);
        $q = $this->db->get('c_post');
        if ($q->num_rows() > 0) {
            $result = array();
            foreach ($q->result_array() as $row) {
                $row['user_post_fullname'] = $this->get_user_fullname($row['uid_post']);
                if ($this->auth->is_admin()) {
                    $row['delete_url'] = site_url('community/board/delete/' . $row['p_id']);
                }

                $row['body'] = nl2br($this->xelatex->render_bbcode(strip_tags(parse_bbcode($row['body']), $this->strip_tag_allow)));
                //$row['body'] = nl2br(strip_tags(parse_bbcode($this->xelatex->render_bbcode($row['body'])), $this->strip_tag_allow));

                $result[] = $row;
            }
            return $result;
        }
        return FALSE;
    }

    function save($post_data) {
        if ($post_data['p_id_parent'] != '') {
            $reply_num = $this->get_reply_nums($post_data['p_id_parent']);
            $reply_num++;
        }

        $this->db->set('title', filter_var($post_data['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        //$this->db->set('body', filter_var($post_data['body'], FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $this->db->set('body', $post_data['body']);
        $this->db->set('create_time', $this->time);
        $this->db->set('uid_post', $this->auth->uid());
        $this->db->set('board_type_id', $post_data['board_type_id']);
        $this->db->set('allow_comment', 1);

        if ($post_data['p_id'] != '') {
            $this->db->where('p_id', $post_data['p_id']);
            $this->db->update();
        } else {
            if ($post_data['p_id_parent'] != '') {
                $this->db->set('reply_num', $reply_num);
                $this->db->set('p_id_parent', $post_data['p_id_parent']);
            }
            $this->db->insert('c_post');
        }
        return TRUE;
    }

    function get_reply_nums($p_id_parent) {
        $this->db->where('p_id_parent', $p_id_parent);
        return $this->db->count_all_results('c_post');
    }

    function delete($p_id) {
        $this->db->where('p_id', $p_id);
        $q = $this->db->get('c_post');
        if ($q->num_rows > 0) {
            $result = $q->row_array();
            $this->db->where('p_id', $p_id);
            $this->db->or_where('p_id_parent', $p_id);
            $this->db->delete('c_post');
            return $result;
        }
        return FALSE;
    }

    function get_write_form_data($board_type_id) {
        $data = $this->db->field_data('c_post');
        $row = array();
        foreach ($data as $v) {
            $row[$v->name] = $v->default;
        }
        $row['board_type_id'] = $board_type_id;
        return $row;
    }

    function get_reply_form_data($p_id_parent) {
        $data = $this->db->field_data('c_post');
        $row = array();
        foreach ($data as $v) {
            $row[$v->name] = $v->default;
        }
        $row['p_id_parent'] = $p_id_parent;
        return $row;
    }

    ///
    function get_user_fullname($uid) {
        $this->db->select('u_user_detail.first_name');
        $this->db->select('u_user_detail.last_name');
        $this->db->where('uid', $uid);
        $query = $this->db->get('u_user_detail');
        if ($query->num_rows() > 0) {
            $r = $query->row_array();
            $fullname = $r['first_name'] . ' ' . $r['last_name'];
        } else {
            $fullname = '! ไม่มีบุคคลนี้อยู่แล้ว';
        }
        return $fullname;
    }

    function plus_view($p_id) {
        $this->db->set('views', 'views+1', FALSE);
        $this->db->where('p_id', $p_id);
        $this->db->update('c_post');
    }

}