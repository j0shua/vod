<?php

/**
 * Description of taxonomy_manager_model
 *
 * @author lojorider
 */
class taxonomy_manager_model extends CI_Model {

    var $CI;
    var $time;

    public function __construct() {
        parent::__construct();
        $this->CI = & get_instance();
        $this->CI->load->model('core/ddoption_model');
        $this->time = time();
    }

// for main
    public function find_all($page, $qtype, $query, $rp, $sortname, $sortorder) {
        if ($sortname == 'title_play') {
            $sortname = 'title';
        }
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
        $this->find_all_where('r_taxonomy', $qtype, $query);
// END Sql Query State
        $total = $this->db->count_all_results();
// Start Sql Query State
        $this->db->select('r_taxonomy.*');
        $this->find_all_where('r_taxonomy', $qtype, $query);
// END Sql Query State
        $this->db->limit($rp, $offset);

        $this->db->order_by($sortname, $sortorder);
        $result = $this->db->get();
        $data['total'] = $total;

        foreach ($result->result_array() as $row) {
            $count_sub = $this->count_sub($row['tid']);

            $row['action'] = '<a href="' . site_url('resource/taxonomy_manager/sub/' . $row['tid']) . '">บทในชุดวิดีโอ</a>';
            $row['action'] .= '<a href="' . site_url('resource/taxonomy_manager/edit/' . $row['tid']) . '">แก้ไข</a>';
//if ($count_sub == 0) {
            if (TRUE) {
                $row['action'] .= '<a href="' . site_url('resource/taxonomy_manager/delete/' . $row['tid']) . '">ลบ</a>';
            }
            $row['publish'] = $publish_options[$row['publish']];
            $row['count_sub'] = $count_sub;
            //$row['title_link'] = '<a href="' . site_url('house/u/' . $this->auth->uid() . '/' . $row['tid']) . '" target="_blank">' . $row['title'] . '</a>';
            $row['title_play'] = '<a href="' . site_url('house/u/' . $this->auth->uid() . '/' . $row['tid']) . '" target="_blank">เปิดดู</a>' . '<span title="' . $row['desc'] . '">' . $row['title'] . '</span>';

            $data['rows'][] = array(
                'id' => $row['tid'],
                'cell' => $row
            );
        }
        return $data;
    }

    private function find_all_where($table_name, $qtype, $query) {
        $this->db->where('tid_parent', 0);
        $this->db->where('uid_owner', $this->auth->uid());
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

    function save($tid, $data) {
        if ($tid == '') {
            $set = array(
                'title' => $data['title'],
                'desc' => $data['desc'],
                'publish' => $data['publish'],
                'uid_owner' => $this->auth->uid()
            );
            $this->db->set($set);
            if (isset($data['weight'])) {
                $this->db->set('weight', $data['weight']);
            }
            $query = $this->db->insert('r_taxonomy');
            return TRUE;
        } else {
            $this->db->where('tid', $tid);
            $query = $this->db->get('r_taxonomy');
            if ($query->num_rows() > 0) {
                $set = array(
                    'title' => $data['title'],
                    'desc' => $data['desc'],
                    'publish' => $data['publish'],
                );
                $this->db->set($set);
                if (isset($data['weight'])) {
                    $this->db->set('weight', $data['weight']);
                }
                $this->db->where('tid', $tid);
                $query = $this->db->update('r_taxonomy');
                return TRUE;
            }
        }
        return FALSE;
    }

    function delete($tid) {
        if ($this->count_sub($tid) > 0) {
            $this->db->where('tid_parent', $tid);
            $this->db->delete('r_taxonomy');
        }

        $this->db->where('tid', $tid);
        $q1 = $this->db->get('r_taxonomy');
        if ($q1->num_rows() > 0) {
            $this->db->where('tid', $tid);
            $this->db->delete('r_taxonomy');
            return TRUE;
        }
        return FALSE;
    }

    function count_sub($tid_parent) {
        $this->db->where('tid_parent', $tid_parent);
        return $this->db->count_all_results('r_taxonomy');
    }

    function copy($tid, $uid_owner = '') {
        if ($uid_owner == '') {
            $uid_owner = $this->auth->uid();
        }
        $this->db->where('tid', $tid);
        $q_taxonomy = $this->db->get('r_taxonomy');

        $r_taxonomy = $q_taxonomy->row_array();
        $this->db->where('tid_parent', $tid);
        $q_sub_taxonomy = $this->db->get('r_taxonomy');
//        print_r($q_sub_taxonomy->result_array());
//        exit();
        $this->db->trans_start();
        $taxonomy_set = array(
            'uid_owner' => $uid_owner,
            'title' => $r_taxonomy['title'],
            'desc' => $r_taxonomy['desc'],
            'data' => $r_taxonomy['data'],
            'weight' => $r_taxonomy['weight'],
            'publish' => $r_taxonomy['publish'],
            'tid_copy_from' => $tid
        );
        $this->db->set($taxonomy_set);
        $this->db->insert('r_taxonomy');
        $tid_parent = $this->db->insert_id();
        foreach ($q_sub_taxonomy->result_array() as $r_sub_taxonomy) {
            $sub_taxonomy_set = array(
                'uid_owner' => $uid_owner,
                'tid_parent' => $tid_parent,
                'title' => $r_sub_taxonomy['title'],
                'desc' => $r_sub_taxonomy['desc'],
                'data' => $r_sub_taxonomy['data'],
                'weight' => $r_sub_taxonomy['weight'],
                'publish' => $r_sub_taxonomy['publish']
            );
            $this->db->set($sub_taxonomy_set);
            $this->db->insert('r_taxonomy');
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        }
        return $tid_parent;
    }

    function get_uid_owner($tid) {
        $this->db->where('tid', $tid);
        $q_taxonomy = $this->db->get('r_taxonomy');
        return $q_taxonomy->row()->uid_owner;
    }

// SUB ==========================================================================
    public function sub_find_all($page, $qtype, $query, $rp, $sortname, $sortorder, $tid_parent) {
        if ($sortname == 'title_play') {
            $sortname = 'title';
        }
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
        $this->sub_find_all_where('r_taxonomy', $qtype, $query, $tid_parent);
// END Sql Query State
        $total = $this->db->count_all_results();
// Start Sql Query State
        $this->db->select('r_taxonomy.*');
        $this->sub_find_all_where('r_taxonomy', $qtype, $query, $tid_parent);
// END Sql Query State
        $this->db->limit($rp, $offset);

        $this->db->order_by($sortname, $sortorder);
        $result = $this->db->get();
        $data['total'] = $total;

        foreach ($result->result_array() as $row) {
            $row['action'] = '<a href="' . site_url('resource/taxonomy_manager/sub_edit/' . $row['tid']) . '">แก้ไข</a>';
            $row['action'] .= '<a href="' . site_url('resource/taxonomy_manager/sub_delete/' . $row['tid']) . '">ลบ</a>';
            $row['publish'] = $publish_options[$row['publish']];
            $resource_id_video = current(explode(',', $row['data']));
            if ($resource_id_video) {
                $row['title_play'] = '<a href="' . site_url('v/' . $resource_id_video . '?pltid=' . $row['tid']) . '" target="_blank">▶ ดูวิดีโอ</a>' . $row['title'];
            } else {
                $row['title_play'] = $row['title'];
            }

            $data['rows'][] = array(
                'id' => $row['tid'],
                'cell' => $row
            );
        }
        return $data;
    }

    private function sub_find_all_where($table_name, $qtype, $query, $tid_parent) {
        $this->db->where('tid_parent', $tid_parent);
        $this->db->where('uid_owner', $this->auth->uid());
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

    function valid_data($data) {
        $data = trim($data);
        if ($data == '') {
            $result = array(
                'valid' => FALSE,
                'msg' => 'เลขที่ video ต้องไม่เป็นค่าว่าง',
                'suggest' => ''
            );
            return $result;
        }
        $data_tmp = $data = array_unique(explode(',', $data));
        $result['suggest'] = implode(',', $data);
        $result = array(
            'valid' => TRUE,
            'msg' => '',
            'suggest' => ''
        );
        $msg = '';
        $deny_resource_id = array();
        $suggest = array();
        foreach ($data_tmp as $k => $v) {
            $this->db->where('resource_id', $v);
            $this->db->where('uid_owner', $this->auth->uid());
            if ($this->db->count_all_results('r_resource') > 0) {
                $suggest[] = $v;
            } else {
                $deny_resource_id[] = $v;
                $result['valid'] = FALSE;
            }
        }

        if (count($deny_resource_id) > 0) {
            $result['msg'] = 'เลขที่ video : ' . implode(',', $deny_resource_id) . ' ไม่สามารถบันทึกได้';
        } else {
            $result['msg'] = 'บันทึกได้ทันที';
        }

        $result['suggest'] = implode(',', $suggest);
        return $result;
    }

    function sub_save($tid, $data) {
        if ($tid == '') {
            $set = array(
                'title' => $data['title'],
                'desc' => $data['desc'],
                'data' => $data['data'],
                'publish' => $data['publish'],
                'uid_owner' => $this->auth->uid(),
                'tid_parent' => $data['tid_parent'],
                'tid_parent_site' => $data['tid_parent_site']
            );
            $this->db->set($set);
            if (isset($data['weight'])) {
                $this->db->set('weight', $data['weight']);
            }
            $query = $this->db->insert('r_taxonomy');
            return TRUE;
        } else {
            $this->db->where('tid', $tid);
            $query = $this->db->get('r_taxonomy');
            if ($query->num_rows() > 0) {
                $set = array(
                    'title' => $data['title'],
                    'desc' => $data['desc'],
                    'data' => $data['data'],
                    'publish' => $data['publish'],
                    'tid_parent_site' => $data['tid_parent_site']
                );
                $this->db->set($set);
                if (isset($data['weight'])) {
                    $this->db->set('weight', $data['weight']);
                }
                $this->db->where('tid', $tid);
                $query = $this->db->update('r_taxonomy');
                return TRUE;
            }
        }
        return FALSE;
    }

    function sub_delete($tid) {
        $this->db->where('tid', $tid);
        $q1 = $this->db->get('r_taxonomy');
        if ($q1->num_rows() > 0) {
            $this->db->where('tid', $tid);
            $this->db->delete('r_taxonomy');
            return TRUE;
        }
        return FALSE;
    }

// Extra
    function is_owner($tid) {
        $this->db->where('tid', $tid);
        $this->db->where('uid_owner', $this->auth->uid());
        $query = $this->db->get('r_taxonomy');
        if ($query->num_rows() > 0) {
            return TRUE;
        }
    }

    function get_form_data($tid = '') {
        if ($tid == '') {
            $data = $this->db->field_data('r_taxonomy');
            $row = array();
            foreach ($data as $v) {
                $row[$v->name] = $v->default;
            }
        } else {
            $this->db->where('tid', $tid);
            $query = $this->db->get('r_taxonomy');
            $row = $query->row_array();
        }
        return $row;
    }

    function get_tid_parent($tid) {
        $this->db->select('tid_parent');
        $this->db->where('tid', $tid);
        $q1 = $this->db->get('r_taxonomy');
        if ($q1->num_rows() > 0) {
            return $q1->row()->tid_parent;
        }
        return FALSE;
    }

    function get_title($tid) {
        $this->db->select('title');
        $this->db->where('tid', $tid);
        $q1 = $this->db->get('r_taxonomy');
        if ($q1->num_rows() > 0) {
            return $q1->row()->title;
        }
        return FALSE;
    }

}
