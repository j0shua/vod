<?php

class ajax_autocomplete_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    function get_chapter($term, $subj_id) {
        $list = array();
        $this->db->like('chapter_title', $term);
        $this->db->where('subj_id', $subj_id);

        $this->db->where('uid_owner', $this->auth->uid());
        $this->db->select('chapter_title');
        $this->db->distinct('chapter_title');
        $this->db->limit(50);
        $q = $this->db->get('r_resource');
        if ($q->num_rows() > 0) {
            foreach ($q->result_array() as $r) {
                $list[] = '{"id":"' . $r['chapter_title'] . '","label":"' . $r['chapter_title'] . '","value":"' . $r['chapter_title'] . '"}';
            }
            return '[' . implode(',', $list) . ']';
        } else {
            return '[]';
        }
    }

    function get_sub_chapter($term, $chapter_id) {
        $list = array();
        $this->db->like('sub_chapter_title', $term);
        $this->db->where('chapter_id', $chapter_id);

        $this->db->where('uid_owner', $this->auth->uid());
        $this->db->select('sub_chapter_title');
        $this->db->distinct('sub_chapter_title');
        $this->db->limit(50);
        $q = $this->db->get('r_resource');
        if ($q->num_rows() > 0) {
            foreach ($q->result_array() as $r) {
                $list[] = '{"id":"' . $r['sub_chapter_title'] . '","label":"' . $r['sub_chapter_title'] . '","value":"' . $r['sub_chapter_title'] . '"}';
            }
            return '[' . implode(',', $list) . ']';
        } else {
            return '[]';
        }
    }

    function get_school_name($term) {

        $this->db->like('school_name', $term);
        $this->db->limit(20);
        $q = $this->db->get('f_school');
        $list = array();
        if ($q->num_rows() > 0) {
            foreach ($q->result_array() as $r) {
                $list[] = '{"id":"' . $r['school_id'] . '","label":"' . $r['school_name'] . '","value":"' . $r['school_name'] . '"}';
            }
            echo '[' . implode(',', $list) . ']';
        } else {
            echo '[]';
        }
    }

    function get_user_school_name($term, $rid) {

        $this->db->select('school_name');
        $this->db->distinct('school_name');
        $this->db->like('school_name', $term);
        $this->db->limit(20);
        $this->db->where('rid', $rid);
        $q = $this->db->get('u_user_detail');
        $list = array();
        if ($q->num_rows() > 0) {
            foreach ($q->result_array() as $r) {
                $list[] = '{"id":"' . $r['school_name'] . '","label":"' . $r['school_name'] . '","value":"' . $r['school_name'] . '"}';
            }
            echo '[' . implode(',', $list) . ']';
        } else {
            echo '[]';
        }
    }

    function get_user_full_name($term, $rid) {
        $this->db->where('rid', $rid);
        $this->db->like('full_name', $term);
        $this->db->limit(20);
        $q = $this->db->get('u_user_detail');
        $list = array();
        if ($q->num_rows() > 0) {
            foreach ($q->result_array() as $r) {
                $list[] = '{"id":"' . $r['uid'] . '","label":"' . $r['full_name'] . '","value":"' . $r['full_name'] . '"}';
            }
            echo '[' . implode(',', $list) . ']';
        } else {
            echo '[]';
        }
    }

    function get_user_full_name_ref_dycontent($term, $rid) {
        $this->db->where('rid', $rid);
        $this->db->like('full_name', $term);
        $this->db->limit(20);
        $this->db->where('dycontent_count >', 0);
        $q = $this->db->get('u_user_detail');
        $list = array();
        if ($q->num_rows() > 0) {
            foreach ($q->result_array() as $r) {
                $list[] = '{"id":"' . $r['uid'] . '","label":"' . $r['full_name'] . '","value":"' . $r['full_name'] . '"}';
            }
            echo '[' . implode(',', $list) . ']';
        } else {
            echo '[]';
        }
    }
     function get_user_full_name_ref_sheet($term, $rid) {
        $this->db->where('rid', $rid);
        $this->db->like('full_name', $term);
        $this->db->limit(20);
        $this->db->where('sheet_count >', 0);
        $q = $this->db->get('u_user_detail');
        $list = array();
        if ($q->num_rows() > 0) {
            foreach ($q->result_array() as $r) {
                $list[] = '{"id":"' . $r['uid'] . '","label":"' . $r['full_name'] . '","value":"' . $r['full_name'] . '"}';
            }
            echo '[' . implode(',', $list) . ']';
        } else {
            echo '[]';
        }
    }

}
