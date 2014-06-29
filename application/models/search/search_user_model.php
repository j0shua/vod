<?php

/**
 * Description of search_user_model
 *
 * @author lojorider
 */
class search_user_model extends CI_Model {

    var $CI;

    public function __construct() {
        parent::__construct();
        $this->CI = & get_instance();
    }

    function find_all_teacher($page, $qtype, $query, $rp, $sortname, $sortorder) {

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
        $this->db->where('u_user.uid = u_user_detail.uid', NULL, FALSE);
        $this->db->from('(select u_user.uid from u_user where u_user.active=1 and  u_user.rid=3) u_user');
        $this->where_find_all_teacher('u_user_detail', $qtype, $query);
        // END Sql Query State
        $total = $this->db->count_all_results();
        // Start Sql Query State
        $this->db->select('u_user_detail.*');
        $this->db->where('u_user.uid = u_user_detail.uid', NULL, FALSE);
        $this->db->from('(select u_user.uid from u_user where u_user.active=1 and  u_user.rid=3) u_user');

        $this->where_find_all_teacher('u_user_detail', $qtype, $query);
        // END Sql Query State
        $this->db->limit($rp, $offset);
        $this->db->order_by($sortname, $sortorder);
        $result = $this->db->get();
        $data['total'] = $total;


        foreach ($result->result_array() as $row) {
            $data['rows'][] = array(
                'uid' => $row['uid'],
                'cell' => $row
            );
        } 
        return $data;
    }

    function where_find_all_teacher($table_name, $qtype, $query) {
        $this->db->where($table_name.'.uid !=', 1466);
        //$this->db->where('u_user.active', 1);
        switch ($qtype) {
            case 'custom':
                foreach ($query as $k => $v) {
                    if ($v != '') {
                        switch ($k) {
                            case 'uid':
                                $this->db->like($k, $v, 'after');
                                break;
                            case 'full_name':
                                $this->db->like('full_name', $v);
                                //$this->db->or_like('last_name', $v);
                                break;
                            case 'about_me':
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
                    switch ($qtype) {


                        case 'full_name':
                            $this->db->like('full_name', $query);
                            //$this->db->or_like('last_name', $query);
                            break;
                        case 'school_name':
                            $this->db->like('school_name', $query);

                            break;

                        default:
                            $this->db->where($k, $v);
                            break;
                    }
                }
                break;
        }
        $this->db->from($table_name);
    }

}
