<?php

class subject_manager_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->helper('str');
    }

    function learning_area_find_all($page, $qtype, $query, $rp, $sortname, $sortorder, $is_main) {
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
        $this->learning_area_find_all_where('f_c51_learning_area', $qtype, $query);
        // END Sql Query State
        $total = $this->db->count_all_results();
        // Start Sql Query State
        $this->db->select('f_c51_learning_area.*');
        $this->learning_area_find_all_where('f_c51_learning_area', $qtype, $query);
        // END Sql Query State
        $this->db->limit($rp, $offset);
        $this->db->order_by($sortname, $sortorder);
        $result = $this->db->get();
        $data['total'] = $total;

        foreach ($result->result_array() as $row) {
            if ($is_main) {
                $row['action'] = '<a href="' . site_url('resource/subject_manager/main_subject/' . $row['la_id']) . '">จัดการวิชา</a>';
            } else {
                $row['action'] = '<a href="' . site_url('resource/subject_manager/subject/' . $row['la_id']) . '">จัดการวิชา</a>';
            }

            if ($this->auth->is_admin()) {
                if ($row['la_id'] > 9) {
                    $row['action'] .= '<a href="' . site_url('resource/subject_manager/edit/' . $row['la_id']) . '">แก้ไข</a>';
                }
            }
            $data['rows'][] = array(
                'id' => $row['la_id'],
                'cell' => $row
            );
        }


        return $data;
    }

    private function learning_area_find_all_where($table_name, $qtype, $query) {

        switch ($qtype) {
            case 'custom':
                foreach ($query as $k => $v) {
                    if ($v != '') {
                        switch ($k) {

                            case 'title':
                                $this->db->like($k, $v);
                                break;
                            case 'desc':
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

    function get_learning_area_data($la_id = '') {
        if ($la_id == '') {
            $fields = $this->db->field_data('f_c51_learning_area');
            $row = array();

            foreach ($fields as $field) {
                $row[$field->name] = $field->default;
            }
        } else {
            $this->db->where('la_id', $la_id);
            $q = $this->db->get('f_c51_learning_area');
            $row = $q->row_array();
        }
        return $row;
    }

// Subject ================================================================================
    function subject_find_all($page, $qtype, $query, $rp, $sortname, $sortorder, $la_id, $is_main) {
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
        $this->subject_find_all_where('f_subject', $qtype, $query, $la_id, $is_main);
        // END Sql Query State
        $total = $this->db->count_all_results();
        // Start Sql Query State
        $this->db->select('f_subject.*');
        $this->subject_find_all_where('f_subject', $qtype, $query, $la_id, $is_main);
        // END Sql Query State
        $this->db->limit($rp, $offset);
        $this->db->order_by($sortname, $sortorder);
        $result = $this->db->get();
        $data['total'] = $total;

        foreach ($result->result_array() as $row) {

            if ($is_main) {

                $row['action'] = '<a href="' . site_url('resource/subject_manager/edit_main_subject/' . $row['subj_id']) . '">แก้ไข</a>';
                $row['action'] .= '<a href="' . site_url('resource/subject_manager/main_chapter/' . $row['subj_id']) . '">จัดการบท</a>';
                if ($row['uid_owner'] == 0) {
                    $row['subj_id'] = '[ระบบ] ' . $row['subj_id'];
                }
            } else {
                if ($row['uid_owner'] == 0) {
                    $row['action'] = '';
                    $row['action'] .= '<a href="' . site_url('resource/subject_manager/chapter/' . $row['subj_id']) . '">จัดการบท</a>';
                } else {
                    $row['action'] = '<a href="' . site_url('resource/subject_manager/edit_subject/' . $row['subj_id']) . '">แก้ไข</a>';
                    $row['action'] .= '<a href="' . site_url('resource/subject_manager/chapter/' . $row['subj_id']) . '">จัดการบท</a>';
                }
            }

            $data['rows'][] = array(
                'id' => $row['subj_id'],
                'cell' => $row
            );
        }


        return $data;
    }

    private function subject_find_all_where($table_name, $qtype, $query, $la_id, $is_main) {
        $this->db->where('la_id', $la_id);
        if ($is_main) {
            
        } else {
            $this->db->where('(uid_owner =' . $this->auth->uid() . ' or uid_owner = 0)', NULL, FALSE);
        }
        switch ($qtype) {
            case 'custom':
                foreach ($query as $k => $v) {
                    if ($v != '') {
                        switch ($k) {
                            case 'la_id':
                                $this->db->like($k, $v, 'after');
                                break;
                            case 'title':
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

    function get_subject_data($subj_id = '') {
        if ($subj_id == '') {
            $fields = $this->db->field_data('f_subject');
            $row = array();

            foreach ($fields as $field) {
                $row[$field->name] = $field->default;
            }
        } else {
            $this->db->where('subj_id', $subj_id);
            $q = $this->db->get('f_subject');
            $row = $q->row_array();
        }
        return $row;
    }

    function save_subject($data) {
        $can_save = $this->auth->can_access($this->auth->permis_main_subject_manager);
        $this->db->set('title', $data['title']);
        if ($data['subj_id'] == '') { // ถ้าทำการบันทึกใหม่
            if ($data['uid_owner'] == 0) {
                $this->db->set('uid_owner', 0);
            } else {
                $this->db->set('uid_owner', $this->auth->uid());
            }

            $this->db->set('la_id', $data['la_id']);
            $this->db->insert('f_subject');
        } else {
            //ดึงข้อมูล วิชา
            $this->db->where('subj_id', $data['subj_id']);
            $q_subject = $this->db->get('f_subject');
            $row_subject = $q_subject->row_array();
            //ดึงข้อมูลจาก resource
            $this->db->where('subj_id', $data['subj_id']);
            $q_resource = $this->db->get('r_resource');
            $to_update_resource = FALSE;
            if ($q_resource->num_rows() > 0) {
                if ($q_resource->row()->subject_title != $data['title']) {
                    $to_update_resource = TRUE;
                }
            }
            //
            //ทำการบันทึก subject
            if ($row_subject['uid_owner'] == 0 && $can_save) {
                $this->db->where('subj_id', $data['subj_id']);
                $this->db->update('f_subject');
            } else if ($row_subject['uid_owner'] != 0) {
                $this->db->where('subj_id', $data['subj_id']);
                $this->db->update('f_subject');
            } else {
                return FALSE;
            }
            if ($to_update_resource) {
                $this->db->set('subject_title', $data['title']);
                $this->db->where('subj_id', $data['subj_id']);
                $this->db->update('r_resource');
            }
        }
        return TRUE;
    }

    // chapter
    function chapter_find_all($page, $qtype, $query, $rp, $sortname, $sortorder, $subj_id, $is_main) {
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
        $this->chapter_find_all_where('f_chapter', $qtype, $query, $subj_id, $is_main);
        // END Sql Query State
        $total = $this->db->count_all_results();
        // Start Sql Query State
        $this->db->select('f_chapter.*');
        $this->chapter_find_all_where('f_chapter', $qtype, $query, $subj_id, $is_main);
        // END Sql Query State
        $this->db->limit($rp, $offset);
        $this->db->order_by($sortname, $sortorder);
        $result = $this->db->get();
        $data['total'] = $total;

        foreach ($result->result_array() as $row) {

            if ($is_main) {

                $row['action'] = '<a href="' . site_url('resource/subject_manager/edit_main_chapter/' . $row['chapter_id']) . '">แก้ไข</a>';

                if ($row['uid_owner'] == 0) {
                    $row['subj_id'] = '[ระบบ] ' . $row['subj_id'];
                }
            } else {
                if ($row['uid_owner'] == 0) {
                    $row['action'] = 'ไม่สามารถแก้ไขวิชาของระบบได้';
                } else {
                    $row['action'] = '<a href="' . site_url('resource/subject_manager/edit_chapter/' . $row['chapter_id']) . '">แก้ไข</a>';
                }
            }

            $data['rows'][] = array(
                'id' => $row['subj_id'],
                'cell' => $row
            );
        }


        return $data;
    }

    private function chapter_find_all_where($table_name, $qtype, $query, $subj_id, $is_main) {
        $this->db->where('subj_id', $subj_id);
        if ($is_main) {
            
        } else {
            $this->db->where('(uid_owner =' . $this->auth->uid() . ' or uid_owner = 0)', NULL, FALSE);
        }
        switch ($qtype) {
            case 'custom':
                foreach ($query as $k => $v) {
                    if ($v != '') {
                        switch ($k) {
                            case 'la_id':
                                $this->db->like($k, $v, 'after');
                                break;
                            case 'title':
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

    function get_chapter_data($chapter_id = '') {

        if ($chapter_id == '') {
            $fields = $this->db->field_data('f_chapter');
            $row = array();

            foreach ($fields as $field) {
                $row[$field->name] = $field->default;
            }
        } else {

            $this->db->where('chapter_id', $chapter_id);
            $q = $this->db->get('f_chapter');
            $row = $q->row_array();
        }
        return $row;
    }

    function save_chapter($data) {
        $can_save = $this->auth->can_access($this->auth->permis_main_subject_manager);
        //ดึงข้อมูล วิชา
        $this->db->where('subj_id', $data['subj_id']);
        $q_subject = $this->db->get('f_subject');
        $row_subject = $q_subject->row_array();
        $this->db->set('chapter_title', $data['chapter_title']);
        $this->db->set('la_id', $row_subject['la_id']);
        if ($data['chapter_id'] == '') { // ถ้าทำการบันทึกใหม่
            if ($data['uid_owner'] == 0) {
                $this->db->set('uid_owner', 0);
            } else {
                $this->db->set('uid_owner', $this->auth->uid());
            }

            $this->db->set('subj_id', $data['subj_id']);

            $this->db->insert('f_chapter');
        } else {
            //ดึงข้อมูลจาก resource
            $this->db->where('subj_id', $data['subj_id']);
            $q_resource = $this->db->get('r_resource');
            $to_update_resource = FALSE;
            if ($q_resource->num_rows() > 0) {
                if ($q_resource->row()->chapter_title != $data['chapter_title']) {
                    $to_update_resource = TRUE;
                }
            }
            //ดึงข้อมูล บท
            $this->db->where('chapter_id', $data['chapter_id']);
            $q_chapter = $this->db->get('f_chapter');
            $row_chapter = $q_chapter->row_array();
            //ทำการบันทึก chapter
            if ($row_chapter['uid_owner'] == 0 && $can_save) {
                $this->db->where('chapter_id', $data['chapter_id']);
                $this->db->update('f_chapter');
            } else if ($row_chapter['uid_owner'] != 0) {
                $this->db->where('chapter_id', $data['chapter_id']);
                $this->db->update('f_chapter');
            } else {
                return FALSE;
            }
            if ($to_update_resource) {
                $this->db->set('chapter_title', $data['chapter_title']);
                $this->db->where('chapter_id', $data['chapter_id']);
                $this->db->update('r_resource');
            }
        }
        return TRUE;
    }

}