<?php

/**
 * Description of disk_quota_service_model
 *
 * @author lojorider
 */
class disk_quota_service_model extends CI_Model {

    var $time = 0;
    var $uid = '';
    var $sid = '';
    var $user_disk_quota = 0;
    var $user_disk_size = 0;
    var $user_video_size = 0;
    var $user_doc_size = 0;
    var $user_image_size = 0;
    //var $std_disk_quota = 1099511627776;
    var $std_disk_quota = 109951162777600; 
    

    public function __construct() {
        parent::__construct();
        $this->load->helper('number');
        $this->user_disk_quota = $this->config->item('normal_disk_quota');
        $this->time = time();
        $this->load->helper('time');
        
    }

    // User disk_quota ========================================================================
    public function disk_quota_find_all($page, $qtype, $query, $rp, $sortname, $sortorder) {
        $this->load->helper('number');
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
        $this->disk_quota_find_all_where('b_disk_quota', $qtype, $query);
        // END Sql Query State
        $total = $this->db->count_all_results();
        // Start Sql Query State
        $this->db->select('b_disk_quota.*');
        $this->disk_quota_find_all_where('b_disk_quota', $qtype, $query);
        // END Sql Query State
        $this->db->limit($rp, $offset);
        $this->db->order_by($sortname, $sortorder);
        $result = $this->db->get();
        $data['total'] = $total;
        foreach ($result->result_array() as $row) {
            $row['checkbox'] = '<input type = "checkbox" name = "cb_resource_id[]" value = "' . $row['dq_id'] . '" />';
            $row['action'] = '';
            $row['action'] .= '<a href="' . site_url('service/disk_quota_service/edit_disk_quota/' . $row['dq_id']) . '">แก้ไขบริการ</a>';
            //$row['action'] .= '<a href="' . site_url('service/disk_quota_service/service_billing/' . $row['dq_id']) . '">บิล</a>';
            //$row['action'] .= '<a href="' . site_url('service/disk_quota_service/renew_disk_quota/' . $row['dq_id']) . '">ต่ออายุ</a>';


            $user_data = $this->auth->get_user_data($row['uid_customer']);
            $row['full_name'] = $user_data['full_name'];
            $row['start_time_text'] = thdate('d M Y H:i', $row['start_time']);
            $row['end_time_text'] = thdate('d M Y H:i', $row['end_time']);
            $row['disk_quota'] = byte_format($row['value']);
            $row['is_active_text'] = ($row['is_active']) ? 'ให้บริการ' : '<span class="error">งดให้บริการ</span>';
            $row['days'] = floor(( $row['end_time'] - $row['start_time']) / 86400);

            $data['rows'][] = array(
                'id' => $row['dq_id'],
                'cell' => $row
            );
        }
        return $data;
    }

    private function disk_quota_find_all_where($table_name, $qtype, $query) {
        switch ($qtype) {
            case 'custom':
                foreach ($query as $k => $v) {
                    if ($v != '') {
                        switch ($k) {
                            case 'dq_id':
                                $this->db->like($table_name . '.' . $k, $v, 'after');
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

    function add_disk_quota($data) {
        $start_time = mktimestamp($data['start_time'] . ' 00:00');
        $end_time = mktimestamp($data['end_time'] . ' 23:59');
        $uid = $this->auth->uid();
        $data['value'] = $data['value_mb'] * 1048576;
        $set_disk_quota = array(
            'uid_customer' => $data['uid_customer'],
            'uid_accept' => $uid,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'price' => $data['price'],
            'warn_expire' => 0,
            'value' => $data['value'],
            'is_active' => $data['paymoney']
        );
        $this->db->set($set_disk_quota);
        $this->db->insert('b_disk_quota');

        $dq_id = $this->db->insert_id();

        if ($data['paymoney']) {
            $pay_time = $this->time;
        } else {
            $pay_time = 0;
        }
        $disk_quota_billing = array(
            'dq_id' => $dq_id,
            'uid_customer' => $data['uid_customer'],
            'uid_accept' => $uid,
            'order_time' => $this->time,
            'pay_time' => $pay_time,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'price' => $data['price']
        );
        $this->db->set($disk_quota_billing);
        $this->db->insert('b_disk_quota_billing');
        return TRUE;
    }

    function edit_disk_quota($data) {
        $start_time = mktimestamp($data['start_time'] . ' 00:00');
        $end_time = mktimestamp($data['end_time'] . ' 23:59');
        $data['value'] = $data['value_mb'] * 1024;
        $set_disk_quota = array(
            'start_time' => $start_time,
            'end_time' => $end_time,
            'price' => $data['price'],
            'value' => $data['value'],
            'is_active' => $data['is_active']
        );
        $this->db->set($set_disk_quota);
        $this->db->where('dq_id', $data['dq_id']);
        $this->db->update('b_disk_quota');
        return TRUE;
    }

    function get_disk_quota_data($dq_id) {
        $this->db->where('dq_id', $dq_id);
        $q = $this->db->get('b_disk_quota');
        $row = $q->row_array();
        $row['value_mb'] = floor($row['value'] / 1024);
        $row['start_time_text'] = date('d/m/Y', $row['start_time']);
        $row['end_time_text'] = date('d/m/Y', $row['end_time']);
        $user_data = $this->auth->get_user_data($row['uid_customer']);
        $row['user_data'] = $user_data;
        return $row;
    }

// Billing ========================================================================
    public function billing_disk_quota_find_all($page, $qtype, $query, $rp, $sortname, $sortorder) {
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
        $this->billing_disk_quota_find_all_where('b_disk_quota_billing', $qtype, $query);
        // END Sql Query State
        $total = $this->db->count_all_results();
        // Start Sql Query State
        $this->db->select('b_disk_quota_billing.*');
        $this->billing_disk_quota_find_all_where('b_disk_quota_billing', $qtype, $query);
        // END Sql Query State
        $this->db->limit($rp, $offset);
        $this->db->order_by($sortname, $sortorder);
        $result = $this->db->get();
        $data['total'] = $total;

        foreach ($result->result_array() as $row) {
            $row['checkbox'] = '<input type = "checkbox" name = "cb_resource_id[]" value = "' . $row['dqb_id'] . '" />';
            $row['action'] = '';
            $data['rows'][] = array(
                'id' => $row['resource_id'],
                'cell' => $row
            );
        }
        return $data;
    }

    private function billing_disk_quota_find_all_where($table_name, $qtype, $query) {
        switch ($qtype) {
            case 'custom':
                foreach ($query as $k => $v) {
                    if ($v != '') {
                        switch ($k) {
                            case 'dqb_id':
                                $this->db->like($table_name . '.' . $k, $v, 'after');
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

// Template ========================================================================
    public function template_disk_quota_find_all($page, $qtype, $query, $rp, $sortname, $sortorder) {
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
        $this->template_disk_quota_find_all_where('b_disk_quota_template', $qtype, $query);
        // END Sql Query State
        $total = $this->db->count_all_results();
        // Start Sql Query State
        $this->db->select('b_disk_quota_template.*');
        $this->template_disk_quota_find_all_where('b_disk_quota_template', $qtype, $query);
        // END Sql Query State
        $this->db->limit($rp, $offset);
        $this->db->order_by($sortname, $sortorder);
        $result = $this->db->get();
        $data['total'] = $total;

        foreach ($result->result_array() as $row) {
            $row['checkbox'] = '<input type = "checkbox" name = "cb_resource_id[]" value = "' . $row['dqt_id'] . '" />';
            $row['action'] = '';
            $row['action'] .= '<a href="' . site_url('service/disk_quota_service/edit_template_disk_quota/' . $row['dqt_id']) . '">แก้ไข</a>';
            $row['action'] .= '<a href="' . site_url('service/disk_quota_service/delete_template_disk_quota/' . $row['dqt_id']) . '">ลบ</a>';
            $row['disk_quota'] = byte_format($row['value']);
            $data['rows'][] = array(
                'id' => $row['dqt_id'],
                'cell' => $row
            );
        }
        return $data;
    }

    private function template_disk_quota_find_all_where($table_name, $qtype, $query) {
        switch ($qtype) {
            case 'custom':
                foreach ($query as $k => $v) {
                    if ($v != '') {
                        switch ($k) {
                            case 'dqt_id':
                                $this->db->like($table_name . '.' . $k, $v, 'after');
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

    function get_template_disk_quota_data($dqt_id = '') {
        $this->db->where('dqt_id', $dqt_id);
        $q = $this->db->get('b_disk_quota_template');
        if ($q->num_rows() > 0) {
            $r = $q->row_array();
            $r['value_mb'] = floor($r['value'] / 1048576);
            return $r;
        } else {
            $data = array();
            $fields = $this->db->field_data('b_disk_quota_template');
            foreach ($fields as $field) {
                $data[$field->name] = $field->default;
            }
            $data['value_mb'] = 0;
            $data['days'] = 365;
            $data['price'] = 0;
        }
        return $data;
    }

    function save_template_disk_quota($data) {
        $data['value'] = $data['value_mb'] * 1048576;
        $set = array(
            'title' => $data['title'],
            'price' => $data['price'],
            'value' => $data['value'],
            'days' => $data['days'],
            'desc' => $data['desc'],
            'create_time' => $this->time
        );
        $this->db->set($set);
        if ($data['dqt_id'] == '') {

            $this->db->insert('b_disk_quota_template');
        } else {
            $this->db->where('dqt_id', $data['dqt_id']);
            $this->db->update('b_disk_quota_template');
        }
        return TRUE;
    }

    function delete_template_disk_quota($dqt_id) {
        $this->db->where('dqt_id', $dqt_id);
        $this->db->delete('b_disk_quota_template');
        return TRUE;
    }

// extra function  ========================================================================
    function get_paymoney_options() {
        $options = array(
            0 => 'ยังไม่ได้จ่ายเงิน',
            1 => 'จ่ายแล้ว'
        );
        return $options;
    }

    function get_is_active_options() {
        $options = array(
            0 => 'ปิดให้บริการ',
            1 => 'ให้บริการ'
        );
        return $options;
    }

    function get_disk_quota_template_options() {

        $options = array('' => '');
        $query = $this->db->get('b_disk_quota_template');
        foreach ($query->result_array() as $v) {
            $options[$v['dqt_id']] = $v['title'];
        }
        return $options;
    }

// API ========================================================================
    function init_user_quota($uid) {

        $this->uid = $uid;
        //ดึงเลขที่ service
        $this->db->select('dq_id');
        $this->db->select('value');
        $this->db->where('uid_customer', $this->uid);
        $this->db->where('start_time <', $this->time);
        $this->db->where('end_time >', $this->time);
        $this->db->where('is_active', 1);
        $this->db->from('b_disk_quota');
        $q_service = $this->db->get();
        if ($q_service->num_rows() > 0) {
            $this->user_disk_quota = $q_service->row()->value;
        }
        if ($this->user_disk_quota == -1) {
            $this->user_disk_quota = $this->std_disk_quota;
        }


        //set video size
        $this->db->where('uid_owner', $this->uid);
        $this->db->select_sum('file_size', 'sum_file_size');
        $this->db->from('r_resource_video');
        $q_resource_video = $this->db->get();
        $this->user_video_size = $q_resource_video->row()->sum_file_size;
        //set doc size
        $this->db->where('uid_owner', $this->uid);
        $this->db->select_sum('file_size', 'sum_file_size');
        $this->db->from('r_resource_doc');
        $q_resource_doc = $this->db->get();
        $this->user_doc_size = $q_resource_doc->row()->sum_file_size;
        //set image size
        $this->db->where('uid_owner', $this->uid);
        $this->db->select_sum('file_size', 'sum_file_size');
        $this->db->from('r_resource_image');
        $q_resource_doc = $this->db->get();
        $this->user_image_size = $q_resource_doc->row()->sum_file_size;
        //sum size
        $this->user_disk_size = $this->user_video_size + $this->user_doc_size;
    }

    function bk_init_user_quota($uid) {

        $this->uid = $uid;
        //ดึงเลขที่ service
        $this->db->select('sid');
        $this->db->where('uid_customer', $this->uid);
        $this->db->where('start_time <', $this->time);
        $this->db->where('end_time >', $this->time);
        $this->db->from('b_service_billing');
        $q_service_b = $this->db->get();
        if ($q_service_b->num_rows() > 0) {
            //ดึงข้อมูล service
            $this->db->select('value');
            $this->db->where('sid', $q_service_b->row()->sid);
            $this->db->from('b_service');
            $q_service = $this->db->get();
            if ($q_service->num_rows() > 0) {
                $this->user_disk_quota = $q_service->row()->value;
            }
        }

        //set video size
        $this->db->where('uid_owner', $this->uid);
        $this->db->select_sum('file_size', 'sum_file_size');
        $this->db->from('r_resource_video');
        $q_resource_video = $this->db->get();
        $this->user_video_size = $q_resource_video->row()->sum_file_size;
        //set doc size
        $this->db->where('uid_owner', $this->uid);
        $this->db->select_sum('file_size', 'sum_file_size');
        $this->db->from('r_resource_doc');
        $q_resource_doc = $this->db->get();
        $this->user_doc_size = $q_resource_doc->row()->sum_file_size;
        //set image size
        $this->db->where('uid_owner', $this->uid);
        $this->db->select_sum('file_size', 'sum_file_size');
        $this->db->from('r_resource_image');
        $q_resource_doc = $this->db->get();
        $this->user_image_size = $q_resource_doc->row()->sum_file_size;
        //sum size
        $this->user_disk_size = $this->user_video_size + $this->user_doc_size;
    }

    function can_upload() {
        if ($this->user_disk_size < $this->user_disk_quota || $this->user_disk_quota < 0) {
            return TRUE;
        }
        return FALSE;
    }

    function get_user_disk_size($use_byte_format = TRUE) {
        if ($use_byte_format) {
            return byte_format($this->user_disk_size);
        }
        return $this->user_disk_size;
    }

    function get_user_disk_quota($use_byte_format = TRUE) {
        if ($use_byte_format) {
            return byte_format($this->user_disk_quota);
        }
        return $this->user_disk_quota;
    }

//    public function cron_lock_video() {
//        $this->db->select('uid_owner');
//        $this->db->select_sum('file_size');
//        $this->db->group_by('uid_owner');
//        $this->db->from('r_resource_video');
//        $q1 = $this->db->get();
//        if ($q1->num_rows() > 0) {
//            foreach ($q1->result_array() as $v1) {
//                if ($v1['file_size'] > $this->normal_disk_quota) {
//                    $sid = $this->have_service_billing($v1['uid_owner']);
//                    if ($sid) {
//                        $disk_quota_by_service = $this->get_disk_quota_by_service($sid);
//                        if ($v1['file_size'] > $disk_quota_by_service) {
//                            $this->db->set('lock_time', $this->time);
//                            $this->db->where('uid_owner', $v1['uid_owner']);
//                            $this->db->update('r_resource');
//                        }
//                    }
//                }
//            }
//        }
//    }
}