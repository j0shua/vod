<?php

/**
 * Description of play_report_model
 *
 * @author lojorider
 */
class play_report_model extends CI_Model {

    var $doc_dir = '';
    var $resource_type_id = 2;

    public function __construct() {
        parent::__construct();
        $this->load->helper('number');
        $this->full_doc_dir = $this->config->item('full_doc_dir');
        $this->load->helper('tag');
        $this->time = time();
        $this->load->helper('time');
    }

    public function find_all($page, $qtype, $query, $rp, $sortname, $sortorder) {
        $can_view_all = $this->auth->can_access($this->auth->permis_all_view_video_report);
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
        //select count sum and ...
        $this->db->select_sum('b_view_log.money', 'sum_money');
        $this->db->select('count(b_view_log.id) count_row', FALSE);

        // Start Sql Query State for count row
        $this->find_all_where('b_view_log', $qtype, $query, $can_view_all);
        // END Sql Query State
        $q = $this->db->get();
        $q_row = $q->row_array();
        $total = $q_row['count_row'];
        $sum_money = $q_row['sum_money'];

        $this->db->select_sum('b_view_log.money', 'sum_money');
        $this->db->where('view_type', 'money_bonus');
        $this->find_all_where('b_view_log', $qtype, $query, $can_view_all);
        $q_bonus = $this->db->get();
        $q_bonus_row = $q->row_array();
        $sum_money_bonus = $q_bonus_row['sum_money'];
        $sum_money = $sum_money - $q_bonus_row['sum_money'];
        // Start Sql Query State
        $this->db->select('b_view_log.*');

        $this->find_all_where('b_view_log', $qtype, $query, $can_view_all);
        // END Sql Query State
        $this->db->limit($rp, $offset);
        $this->db->order_by($sortname, $sortorder);
        $result = $this->db->get();
        $data['total'] = $total;

        foreach ($result->result_array() as $row) {
            $row['action'] = '';
            $row['uid_view'] = $row['uid_view'];
            $row['uid_owner'] = $row['uid_owner'];
            if ($row['view_type'] == 'money_bonus') {
                $row['money_bonus'] = number_format($row['money'], 2);
                $row['money'] = 'ใช้เงินโบนัส';
            } else {
                $row['money_bonus'] = '-';
                $row['money'] = number_format($row['money'], 2);
            }

            $view_times = $row['last_time'] - $row['first_time'];

            $row['view_times'] = floor($view_times / 60) . ':' . str_pad($view_times % 60, 2, '0', STR_PAD_LEFT);
            $row['first_time'] = thdate("Y-M-d H:i:s", $row['first_time']);
            $row['last_time'] = thdate("Y-M-d H:i:s", $row['last_time']);

            if ($this->auth->is_admin()) {
                $row['user_view_fullname'] = anchor('admin/users/detail/' . $row['uid_view'], $this->get_user_fullname($row['uid_view']), 'target="_blank"');
                $row['user_owner_fullname'] = anchor('admin/users/detail/' . $row['uid_owner'], $this->get_user_fullname($row['uid_owner']), 'target="_blank"');
            } else {
                $row['user_view_fullname'] = $this->get_user_fullname($row['uid_view']);
                $row['user_owner_fullname'] = $this->get_user_fullname($row['uid_owner']);
            }
            $row = array_merge($row, $this->get_video_data($row['resource_id']));
            $data['rows'][] = array(
                'id' => $row['id'],
                'cell' => $row
            );
        }
        $data['rows'][] = array(
            'id' => 0,
            'cell' => array(
                'money' => '<strong>' . number_format($sum_money, 2) . '</strong>',
                'money_bonus' => '<strong>' . number_format($sum_money_bonus, 2) . '</strong>',
                'first_time' => '',
                'view_times' => '',
                'uid_view' => '',
                'uid_owner' => '',
                'last_time' => '',
                'title' => '',
                'resource_id' => '',
                'action' => '',
                'id' => '',
                'user_view_fullname' => '',
                'user_owner_fullname' => '',
                'unit_price' => ''
            )
        );


        return $data;
    }

    private function find_all_where($table_name, $qtype, $query, $view_all) {
        if (!$view_all) {
            $this->db->where('uid_owner', $this->auth->uid());
        }
        switch ($qtype) {
            case 'custom':
                foreach ($query as $k => $v) {
                    if ($v != '') {
                        switch ($k) {
                            case 'id':
                                $this->db->like($k, $v, 'after');
                                break;
                            case 'title':
                                $this->db->like($k, $v);
                                break;
                            case 'from':
                                $a_v = explode('/', $v);

                                if (count($a_v) == 3) {
                                    list($d, $m, $y) = $a_v;
                                    $v = $y . $m . $d;
                                }

                                $this->db->where("FROM_UNIXTIME(`first_time`,'%Y%m%d') >=" . $v, NULL, FALSE);
                                break;
                            case 'to':
                                $a_v = explode('/', $v);

                                if (count($a_v) == 3) {
                                    list($d, $m, $y) = $a_v;
                                    $v = $y . $m . $d;
                                }
                                $this->db->where("FROM_UNIXTIME(`first_time`,'%Y%m%d') <=" . $v, NULL, FALSE);
                                break;
//                            case 'is_end':
//                                $this->db->where($k, $v);
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
    
     public function share_find_all($page, $qtype, $query, $rp, $sortname, $sortorder) {
        $can_view_all = $this->auth->can_access($this->auth->permis_all_view_video_report);
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
        //select count sum and ...
        $this->db->select_sum('b_view_log.money', 'sum_money');
        $this->db->select('count(b_view_log.id) count_row', FALSE);

        // Start Sql Query State for count row
        $this->share_find_all_where('b_view_log', $qtype, $query, $can_view_all);
        // END Sql Query State
        $q = $this->db->get();
        $q_row = $q->row_array();
        $total = $q_row['count_row'];
        $sum_money = $q_row['sum_money'];

        $this->db->select_sum('b_view_log.money', 'sum_money');
        $this->db->where('view_type', 'money_bonus');
        $this->share_find_all_where('b_view_log', $qtype, $query, $can_view_all);
        $q_bonus = $this->db->get();
        $q_bonus_row = $q->row_array();
        $sum_money_bonus = $q_bonus_row['sum_money'];
        $sum_money = $sum_money - $q_bonus_row['sum_money'];
        // Start Sql Query State
        $this->db->select('b_view_log.*');

        $this->share_find_all_where('b_view_log', $qtype, $query, $can_view_all);
        // END Sql Query State
        $this->db->limit($rp, $offset);
        $this->db->order_by($sortname, $sortorder);
        $result = $this->db->get();
        $data['total'] = $total;

        foreach ($result->result_array() as $row) {
            $row['action'] = '';
            $row['uid_view'] = $row['uid_view'];
            $row['uid_owner'] = $row['uid_owner'];
            if ($row['view_type'] == 'money_bonus') {
                $row['money_bonus'] = number_format($row['money'], 2);
                $row['money'] = 'ใช้เงินโบนัส';
            } else {
                $row['money_bonus'] = '-';
                $row['money'] = number_format($row['money'], 2);
            }

            $view_times = $row['last_time'] - $row['first_time'];

            $row['view_times'] = floor($view_times / 60) . ':' . str_pad($view_times % 60, 2, '0', STR_PAD_LEFT);
            $row['first_time'] = thdate("Y-M-d H:i:s", $row['first_time']);
            $row['last_time'] = thdate("Y-M-d H:i:s", $row['last_time']);

            if ($this->auth->is_admin()) {
                $row['user_view_fullname'] = anchor('admin/users/detail/' . $row['uid_view'], $this->get_user_fullname($row['uid_view']), 'target="_blank"');
                $row['user_owner_fullname'] = anchor('admin/users/detail/' . $row['uid_owner'], $this->get_user_fullname($row['uid_owner']), 'target="_blank"');
            } else {
                $row['user_view_fullname'] = $this->get_user_fullname($row['uid_view']);
                $row['user_owner_fullname'] = $this->get_user_fullname($row['uid_owner']);
            }
            $row = array_merge($row, $this->get_video_data($row['resource_id']));
            $data['rows'][] = array(
                'id' => $row['id'],
                'cell' => $row
            );
        }
        $data['rows'][] = array(
            'id' => 0,
            'cell' => array(
                'money' => '<strong>' . number_format($sum_money, 2) . '</strong>',
                'money_bonus' => '<strong>' . number_format($sum_money_bonus, 2) . '</strong>',
                'first_time' => '',
                'view_times' => '',
                'uid_view' => '',
                'uid_owner' => '',
                'last_time' => '',
                'title' => '',
                'resource_id' => '',
                'action' => '',
                'id' => '',
                'user_view_fullname' => '',
                'user_owner_fullname' => '',
                'unit_price' => ''
            )
        );


        return $data;
    }

    private function share_find_all_where($table_name, $qtype, $query, $view_all) {
        if (!$view_all) {
            $this->db->where('uid_referer', $this->auth->uid());
            $this->db->where('uid_referer !=','`uid_owner`',FALSE);
        }
        switch ($qtype) {
            case 'custom':
                foreach ($query as $k => $v) {
                    if ($v != '') {
                        switch ($k) {
                            case 'id':
                                $this->db->like($k, $v, 'after');
                                break;
                            case 'title':
                                $this->db->like($k, $v);
                                break;
                            case 'from':
                                $a_v = explode('/', $v);

                                if (count($a_v) == 3) {
                                    list($d, $m, $y) = $a_v;
                                    $v = $y . $m . $d;
                                }

                                $this->db->where("FROM_UNIXTIME(`first_time`,'%Y%m%d') >=" . $v, NULL, FALSE);
                                break;
                            case 'to':
                                $a_v = explode('/', $v);

                                if (count($a_v) == 3) {
                                    list($d, $m, $y) = $a_v;
                                    $v = $y . $m . $d;
                                }
                                $this->db->where("FROM_UNIXTIME(`first_time`,'%Y%m%d') <=" . $v, NULL, FALSE);
                                break;
//                            case 'is_end':
//                                $this->db->where($k, $v);
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

    function get_user_data($uid) {
        $this->db->select('u_user_detail.first_name');
        $this->db->select('u_user_detail.last_name');
        $this->db->where('uid', $uid);
        $query = $this->db->get('u_user_detail');
        if ($query->num_rows() > 0) {
            $r = $query->row_array();
            $row['name'] = $r['first_name'] . ' ' . $r['last_name'];
        } else {
            $row = array(
                'name' => '! ไม่มีบุคคลนี้อยู่แล้ว'
            );
        }
        return $row;
    }

    function get_video_data($resource_id) {
        $this->db->select('r_resource.title');
        $this->db->where('resource_id', $resource_id);
        $query = $this->db->get('r_resource');
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
        } else {
            $row = array(
                'title' => '! ไม่มีเอกสารนี้อยู่แล้ว',
                'file ext' => '',
                'file size' => '',
            );
        }
        return $row;
    }

}