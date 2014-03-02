<?php

/**
 * Description of permission_model
 *
 * @author lojorider
 */
class permission_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function permission_data() {
        $data = array();
        $role_array = $this->role_array();
        $q_mg = $this->db->get('p_module_group')->result_array();
        foreach ($q_mg as $k_mg => $v_mg) {
            $this->db->where('mg_id', $v_mg['mg_id']);
            $data[$k_mg]['group_title'] = $v_mg['title'];
            $q_module = $this->db->get('p_module')->result_array();
            foreach ($q_module as $k_module => $v_module) {
                $data[$k_mg]['modules'][$k_module]['mid'] = $v_module['mid'];
                $data[$k_mg]['modules'][$k_module]['mudule_title'] = $v_module['title'];
                foreach ($role_array as $rid => $title) {
                    $this->db->where('rid', $rid);
                    $this->db->where('mid', $v_module['mid']);
                    $result = $this->db->get('p_role_module');
                    $row_rm = $result->row_array();
                    if ($result->num_rows() > 0) {
                        $data[$k_mg]['modules'][$k_module]['roles'][$rid] = $row_rm['active'];
                    } else {
                        $this->db->set('rid', $rid);
                        $this->db->set('mid', $v_module['mid']);
                        $this->db->insert('p_role_module');
                        $data[$k_mg]['modules'][$k_module]['roles'][$rid] = 0;
                    }
                }
            }
        }
        $permission_data['role_title'] = $role_array;
        $permission_data['rows'] = $data;
        return $permission_data;
    }

    function role_array() {
        $data = array();
        $q_role = $this->db->get('p_role')->result_array();
        foreach ($q_role as $v) {
            $data[$v['rid']] = $v['title'];
        }
        return $data;
    }

    function save($data) {
        $role_array = $this->role_array();
        $q_module = $this->db->get('p_module')->result_array();
        foreach ($q_module as $k_module => $v_module) {
            foreach ($role_array as $rid => $title) {
                $this->db->where('rid', $rid);
                $this->db->where('mid', $v_module['mid']);
                $result = $this->db->get('p_role_module');
                $row_rm = $result->row_array();
                if ($result->num_rows() > 0) {
                    if (isset($data[$v_module['mid']][$rid])) {
                        $active = 1;
                    } else {
                        $active = 0;
                    }
                    $this->db->where('rid', $rid);
                    $this->db->where('mid', $v_module['mid']);
                    $this->db->set('active', $active);
                    $this->db->update('p_role_module');
                } else {
                    $this->db->set('rid', $rid);
                    $this->db->set('mid', $v_module['mid']);
                    $this->db->insert('p_role_module');
                    $data[$k_mg]['modules'][$k_module]['roles'][$rid] = 0;
                }
            }
        }
    }

}