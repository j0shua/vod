<?php

class resource_join_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    //========= ZONE All =========================================
    function video_join($data) {

        $data['resource_id'] = trim($data['resource_id']);


        if ($data['resource_id'] == '') {
            $this->db->where('resource_id_video', $data['resource_id_video']);
            $this->db->delete('r_resource_video_join');
            return TRUE;
        }
        $tmp_resource_id = $new_resource_id = explode(',', $data['resource_id']);

        foreach ($tmp_resource_id as $k => $v) {
            $new_resource_id[$k] = trim($v);
        }

        $new_resource_id = array_unique($new_resource_id);
       
        $this->db->where('resource_id_video', $data['resource_id_video']);
        $this->db->delete('r_resource_video_join');
        foreach ($new_resource_id as $k => $v) {
            $this->db->where('resource_id', $v);
            $count_resource = $this->db->count_all_results('r_resource_doc');
            $this->db->where('resource_id', $v);
            $count_resource += $this->db->count_all_results('r_resource_dycontent');
            if ($count_resource > 0) {
                $this->db->set('resource_id', $v);
                $this->db->set('resource_id_video', $data['resource_id_video']);
                $this->db->insert('r_resource_video_join');
            }
        }

        return TRUE;
    }

    function join_video($data) {
        $result['status'] = FALSE;
        $result['msg'] = 'ไม่มีข้อมูลเพื่อบันทึก';

        $result['resource_type_id'] = $this->get_resource_type_id($data['resource_id']);
        $data['resource_id_video'] = trim($data['resource_id_video']);
        // ถ้าไม่มีข้อมูลส่งมา
        if ($data['resource_id_video'] == '') {
            $this->db->where('resource_id', $data['resource_id']);
            $this->db->delete('r_resource_video_join');
            return $result;
        }

        $tmp_resource_id_video = $new_resource_id_video = explode(',', $data['resource_id_video']);
        foreach ($tmp_resource_id_video as $k => $v) {
            $new_resource_id_video[$k] = trim($v);
        }
        $new_resource_id_video = array_unique($new_resource_id_video);
        //ล้างข้อมูลเดิม
        $this->db->where('resource_id', $data['resource_id']);
        $this->db->delete('r_resource_video_join');

        foreach ($new_resource_id_video as $k => $v) {
            // ดูว่า เลขที่ มีอยู่จริงไหม VIDEO กับ sitevideo
            $this->db->where('resource_id', $v);
            $this->db->where_in('resource_type_id', array(1, 6, 7));
            if ($this->db->count_all_results('r_resource') > 0) {
                $this->db->set('resource_id_video', $v);
                $this->db->set('resource_id', $data['resource_id']);
                $this->db->insert('r_resource_video_join');
                $result['status'] |= TRUE;
            }
        }
        if ($result['status']) {
            $result['msg'] = 'บันทึการเชื่อมโยงเสร็จสิ้น';
        } else {
            $result['msg'] = 'ไม่สามารถบันทึกการเชื่อมโยงได้';
        }

        return $result;
    }

    /**
     * get_resource_id
     * @param type $resource_id
     * @param type $implode_to_string ถ้าเป็น true จะทำการรวมเป็น string หรือ false เป็น array
     * @param type $implode_glue
     * @return type
     */
    function get_resource_id_video($resource_id, $implode_to_string = FALSE, $implode_glue = '') {
        $resource_id_video = array();
        $this->db->where('resource_id', $resource_id);
        $q1 = $this->db->get('r_resource_video_join');
        if ($q1->num_rows() > 0) {
            foreach ($q1->result_array() as $v) {
                $resource_id_video[] = $v['resource_id_video'];
            }
        }
        if ($implode_to_string) {
            if ($implode_glue == '') {
                $resource_id_video = implode(',', $resource_id_video);
            } else {
                $resource_id_video = implode($implode_glue, $resource_id_video);
            }
        }
        return $resource_id_video;
    }

    /**
     * get_resource_id_
     * @param type $resource_id
     * @param type $implode_to_string ถ้าเป็น true จะทำการรวมเป็น string หรือ false เป็น array
     * @param type $implode_glue
     * @return type
     */
    function get_resource_id_other($resource_id, $implode_to_string = FALSE, $implode_glue = '') {
        
        $resource_id_other = array();
        $this->db->where('resource_id_video', $resource_id);
        $q1 = $this->db->get('r_resource_video_join');
        if ($q1->num_rows() > 0) {
            foreach ($q1->result_array() as $v) {
                $resource_id_other[] = $v['resource_id'];
            }
        }
        if ($implode_to_string) {
            if ($implode_glue == '') {
                $resource_id_other = implode(',', $resource_id_other);
            } else {
                $resource_id_other = implode($implode_glue, $resource_id_other);
            }
        }
        return $resource_id_other;
    }

    //========= ZONE extra =========================================
    function get_resource_type_id($resource_id) {
        $this->db->where('resource_id', $resource_id);
        $q = $this->db->get('r_resource');
        return $q->row()->resource_type_id;
    }
    
    //========Zone Download =============================================================================
    
   
   
    

}