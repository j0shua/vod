<?php

/**
 * โปรแกรมสำหรับ หลักสูตร
 */
class course_model extends CI_Model {

    var $CI;
    var $time;
    var $command_act_type;

    //var $course_data;

    public function __construct() {
        parent::__construct();
        $this->CI = & get_instance();
        $this->CI->load->model('core/ddoption_model');
        $this->load->helper('time');
        $this->time = time();
    }

    /**
     * เรียกข้อมูลหลักสูตร
     * @return type
     */
    function get_course_data($c_id = '') {

        if ($c_id == '') {
            $data = $this->db->field_data('s_course');
            foreach ($data as $field) {
                $row[$field->name] = $field->default;
            }
            $row['start_time_form_text'] = date('d/m/Y');
            $row['end_time_form_text'] = '';
            $row['degree_name'] = '';
        } else {
            $this->db->where('c_id', $c_id);
            $q = $this->db->get('s_course');
            if ($q->num_rows() > 0) {
                $row = $q->row_array();
                $row['start_time_form_text'] = date('d/m/Y', $row['start_time']);
                $row['end_time_form_text'] = date('d/m/Y', $row['end_time']);
                $degree_id_options = $this->CI->ddoption_model->get_degree_id_options();
                $degree_id_options[0] = FALSE;
                $row['degree_name'] = $degree_id_options[$row['degree_id']];
                $learning_area_options = $this->CI->ddoption_model->get_learning_area_options();
                $learning_area_options[0] = FALSE;
                $row['learning_area_name'] = $learning_area_options[$row['la_id']];
            } else {
                $data = $this->db->field_data('s_course');
                foreach ($data as $field) {
                    $row[$field->name] = $field->default;
                }
                $row['start_time_form_text'] = date('d/m/Y');
                $row['end_time_form_text'] = '';
                $row['degree_name'] = '';
            }
        }
        return $row;
    }

//===============================================================================================================
// FOR teacher
//===============================================================================================================

    /**
     * ดึงข้อมูล หลักสูตรของครู
     */
    function find_all_course($page, $qtype, $query, $rp, $sortname, $sortorder) {
        $publish_options = $this->CI->ddoption_model->get_publish_options();
        if ($qtype == 'custom') {
            parse_str($query, $query);
        }
        if ($sortname == 'title_play') {
            $sortname = 'title';
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
        $this->where_find_all_course('s_course', $qtype, $query);
        // END Sql Query State
        $total = $this->db->count_all_results();
        // Start Sql Query State
        $this->db->select('s_course.*');
        $this->where_find_all_course('s_course', $qtype, $query);
        // END Sql Query State
        $this->db->limit($rp, $offset);
        $this->db->order_by($sortname, $sortorder);
        $result = $this->db->get();
        $data['total'] = $total;

        foreach ($result->result_array() as $row) {

            $row['action'] = '<a href="' . site_url('study/course_manager/edit_course/' . $row['c_id']) . '">แก้ไข</a>';
            $row['action'] .= '<a href="' . site_url('study/course_manager/delete_course/' . $row['c_id']) . '">ลบ</a>';
            $row['title'] = '<a href="' . site_url('study/course_manager/course_act/' . $row['c_id']) . '">' . $row['title'] . '</a>';
            $row['start_time'] = thdate('d-M-Y', $row['start_time']);
            $row['end_time'] = thdate('d-M-Y', $row['end_time']);
            $student_count = $this->get_count_student_in_course($row['c_id']);
            if ($student_count > 0) {
                $row['student_count'] = '<a href="' . site_url('study/course_manager/student_course/' . $row['c_id']) . '">' . $student_count . '</a>';
            } else {
                $row['student_count'] = $student_count;
            }

            $row['enroll_limit_text'] = $row['enroll_limit'];
            if ($row['enroll_limit'] == 0) {
                $row['enroll_limit_text'] = 'ไม่จำกัด';
            }
            $row['publish'] = $publish_options[$row['publish']];
            $data['rows'][] = array(
                'id' => $row['c_id'],
                'cell' => $row
            );
        }
        return $data;
    }

    /**
     * ดึงข้อมูล หลักสูตรของครูมาสร้างรายงาน
     */
    function find_all_course_rp($page, $qtype, $query, $rp, $sortname, $sortorder) {
        $publish_options = $this->CI->ddoption_model->get_publish_options();
        if ($qtype == 'custom') {
            parse_str($query, $query);
        }
        if ($sortname == 'title_play') {
            $sortname = 'title';
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
        $this->where_find_all_course('s_course', $qtype, $query);
        // END Sql Query State
        $total = $this->db->count_all_results();
        // Start Sql Query State
        $this->db->select('s_course.*');
        $this->where_find_all_course('s_course', $qtype, $query);
        // END Sql Query State
        $this->db->limit($rp, $offset);
        $this->db->order_by($sortname, $sortorder);
        $result = $this->db->get();
        $data['total'] = $total;

        foreach ($result->result_array() as $row) {
            $row['action'] = '';
            $row['action'] .= '<a href="' . site_url('study/course_manager/rp_student_course/' . $row['c_id']) . '">รายชื่อนักเรียน</a>';
            $row['action'] .= '<a href="' . site_url('study/course_manager/student_course_score/' . $row['c_id']) . '">คะแนนนักเรียน</a>';
            $row['title_play'] = '<a href="' . site_url('study/course_manager/course_act/' . $row['c_id']) . '">' . $row['title'] . '</a>';

            $row['start_time'] = thdate('d-M-Y', $row['start_time']);
            $row['end_time'] = thdate('d-M-Y', $row['end_time']);
            $row['student_count'] = $this->get_count_student_in_course($row['c_id']);


            $row['enroll_limit_text'] = $row['enroll_limit'];
            if ($row['enroll_limit'] == 0) {
                $row['enroll_limit_text'] = 'ไม่จำกัด';
            }
            $row['publish'] = $publish_options[$row['publish']];
            $data['rows'][] = array(
                'id' => $row['c_id'],
                'cell' => $row
            );
        }
        return $data;
    }

    function where_find_all_course($table_name, $qtype, $query) {
        $this->db->where('uid_owner', $this->auth->uid());
        switch ($qtype) {
            case 'custom':
                foreach ($query as $k => $v) {
                    if ($v != '') {
                        switch ($k) {
                            case 'c_id':
                                $this->db->like($k, $v, 'after');
                                break;
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

    /**
     * ครูบันทึกหลักสูตร
     * @param type $post
     * @return boolean
     */
    function save_course($data) {
        $uid_owner = $this->auth->uid();
        $subject_title = $this->get_subject_title($data['subj_id']);
        $start_time = mktimestamp($data['start_time'] . ' 00:00');
        $end_time = mktimestamp($data['end_time'] . ' 23:59');

        $set_extra = array(
            'degree_id' => $data['degree_id'],
            'la_id' => $data['la_id'],
            'subj_id' => $data['subj_id'],
            'subject_title' => $subject_title
        );
        if ($data['c_id'] == '') {
            $user_data = $this->auth->get_user_data($uid_owner);
            $set_extra['full_name_owner'] = $user_data['full_name'];
            $set_extra['school_name_owner'] = $user_data['school_name'];
        }
        $this->db->set($set_extra);
        $this->db->set('title', $data['title']);
        $this->db->set('desc', $data['desc']);
        $this->db->set('desc', $data['desc']);
        $this->db->set('publish', $data['publish']);

        $this->db->set('start_time', $start_time);
        $this->db->set('end_time', $end_time);
        $this->db->set('enroll_type_id', $data['enroll_type_id']);
        if ($data['enroll_type_id'] == 3) {
            $this->db->set('enroll_password', $data['enroll_password']);
        }
        $this->db->set('enroll_limit', $data['enroll_limit']);
        if ($data['c_id'] == '') {
            $this->db->set('uid_owner', $uid_owner);
            $this->db->insert('s_course');
        } else {
            $this->db->where('c_id', $data['c_id']);
            $this->db->update('s_course');
        }
        return TRUE;
    }

    function get_subject_title($subj_id) {
        $this->db->select('title');
        $this->db->where('subj_id', $subj_id);
        $q = $this->db->get('f_subject');
        if ($q->num_rows() > 0) {
            return $q->row()->title;
        } else {
            return '';
        }
    }

    /**
     * ลบหลักสูตร
     */
    function delete_course($c_id) {
        //จัดการลบไฟล์ที่เกี่ยวข้อง
        $send_act_upload_dir = $this->config->item('send_act_upload_dir');
        $this->db->where('c_id', $c_id);
        $q = $this->db->get('s_course_act_send');
        foreach ($q->result_array() as $row) {
            if ($row['st_id'] == 1) {//ถ้าเป็นการ upload
                @unlink($send_act_upload_dir . $row['data']);
            }
        }
        //ที่เกี่ยวข้องทั้งหมด
        // course 
        $this->db->where('c_id', $c_id);
        $this->db->delete('s_course');
        // course act
        $this->db->where('c_id', $c_id);
        $this->db->delete('s_course_act');
        // course enroll
        $this->db->where('c_id', $c_id);
        $this->db->delete('s_course_enroll');
        // course act send
        $this->db->where('c_id', $c_id);
        $this->db->delete('s_course_act_send');
        // course act send asheet
        $this->db->where('c_id', $c_id);
        $this->db->delete('s_course_act_asheet');
        // course act send asheet ซ้อมสอบ
        $this->db->where('c_id', $c_id);
        $this->db->delete('s_course_act_asheet_practice');
        // course act send asheet พรีเทส โพสเทส
        $this->db->where('c_id', $c_id);
        $this->db->delete('s_course_act_asheet_ptest');

        return TRUE;
    }

    /**
     * ลบการสั่งงาน
     */
    function delete_course_act($ca_id) {
        //จัดการลบไฟล์ที่เกี่ยวข้อง
        $send_act_upload_dir = $this->config->item('send_act_upload_dir');
        $this->db->where('ca_id', $ca_id);
        $q = $this->db->get('s_course_act_send');
        foreach ($q->result_array() as $row) {
            @unlink($send_act_upload_dir . $row['data']);
        }
        //ที่เกี่ยวข้องทั้งหมด
        $this->db->where('ca_id', $ca_id);
        $this->db->delete('s_course_act');

        $this->db->where('ca_id', $ca_id);
        $this->db->delete('s_course_act_send');
        return TRUE;
    }

    /**
     * อนุมัตินักเรียนเข้าหลักสูตร
     * @param type $ce_id
     * @return boolean
     */
    function course_enroll_approve($ce_id) {
        $this->db->where('ce_id', $ce_id);
        $q = $this->db->get('s_course_enroll');
        if ($q->num_rows() > 0) {
            $this->db->where('ce_id', $ce_id);
            $this->db->set('approve_time', $this->time);
            $this->db->set('active', 1);
            $this->db->update('s_course_enroll');
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * ครูลบนักเรนียนออกจากหลักสูตร
     * @param type $ce_id
     * @return boolean
     */
    function course_enroll_delete($ce_id) {
        $this->db->where('ce_id', $ce_id);
        $q = $this->db->get('s_course_enroll');
        if ($q->num_rows() > 0) {
            $row = $q->row_array();
            $this->delete_enroll($row['c_id'], $row['uid_student']);

            return array('success' => TRUE, 'course_enroll_data' => $row);
        } else {
            return array('success' => FALSE, 'course_enroll_data' => $row);
        }
    }

    /**
     * สั่งกิจกรรมให้นักเรียน
     * @param type $post
     * @return boolean
     */
    function save_assign_act($post) {
        $uid = $this->auth->uid();
        $start_time = mktimestamp($post['start_time_d'] . ' ' . $post['start_time_h']);
        if ($post['end_time_d'] != '' && $post['end_time_h'] != '') {
            $end_time = mktimestamp($post['end_time_d'] . ' ' . $post['end_time_h']);
        } else {
            $end_time = 2145891600;
        }
        //check act sent 
        if ($post['ca_id'] != '') {
            $this->db->where('ca_id', $post['ca_id']);
            $q_act_send = $this->db->get('s_course_act_send');
            if ($q_act_send->num_rows() > 0) {
                foreach ($q_act_send->result_array() as $row) {
                    if ($post['full_score'] != $row['full_score']) {

                        $new_full_score = $post['full_score'];
                        $new_get_score = ($row['get_score'] * $new_full_score) / $row['full_score'];
                        $this->db->set('get_score', $new_get_score);
                        $this->db->set('full_score', $new_full_score);
                        $this->db->where('cas_id', $row['cas_id']);
                        $this->db->update('s_course_act_send');
                    }
                }
            }
            if ($post['have_preposttest'] == 0) {
                $this->db->where('ca_id', $post['ca_id']);
                $this->db->delete('s_course_act_asheet_ptest');
            }
        }


        $this->db->set('start_time', $start_time);
        $this->db->set('end_time', $end_time);
        $this->db->set('st_id', $post['st_id']);
        $this->db->set('data', $post['data']);
        $this->db->set('full_score', $post['full_score']);
        $this->db->set('title', $post['title']);
        $this->db->set('create_time', $this->time);
        $this->db->set('cmat_id', $post['cmat_id']);
        $this->db->set('at_id', $post['at_id']);
        $this->db->set('have_preposttest', $post['have_preposttest']);

        $this->db->set('uid_assign', $uid);
        if ($post['ca_id'] == '') {
            $this->db->set('c_id', $post['c_id']);
            $this->db->insert('s_course_act');
        } else {
            $this->db->where('ca_id', $post['ca_id']);
            $this->db->update('s_course_act');
        }
        return TRUE;
    }

    /**
     * ดึงข้อมูลนักเรียนที่ต้องทำกิจกรรม [แสดงรายชื่อนักเรียนเป็นตารางของแต่ละการสั่งงาน]
     * @param type $page
     * @param type $qtype
     * @param type $query
     * @param type $rp
     * @param type $sortname
     * @param type $sortorder
     * @param type $ca_id
     * @return string
     */
    function find_all_student_course_act($page, $qtype, $query, $rp, $sortname, $sortorder, $ca_id) {
        $ca_data = $this->get_act_data($ca_id);
        if ($qtype == 'custom') {
            parse_str($query, $query);
        }
        if ($sortname == 'title_play') {
            $sortname = 'title';
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
        $this->where_find_all_student_course_act('s_course_enroll', $qtype, $query, $ca_data['c_id']);
        // END Sql Query State
        $total = $this->db->count_all_results();
        // Start Sql Query State
        $this->db->select('s_course_enroll.*');
        $this->where_find_all_student_course_act('s_course_enroll', $qtype, $query, $ca_data['c_id']);
        // END Sql Query State
        $this->db->limit($rp, $offset);
        $this->db->order_by($sortname, $sortorder);
        $result = $this->db->get();
        $data['total'] = $total;
        foreach ($result->result_array() as $row) {

            switch ($ca_data['st_id']) {
                case 1:case 3: //check ก่อน
                    if ($this->is_send_act($row['uid_student'], $ca_id)) {
                        $row['action'] = '<a href="' . site_url('study/course_manager/give_score_student_act/' . $ca_id . '/' . $row['uid_student']) . '">ให้คะแนน</a>';
                    } else {
                        if ($ca_data['end_time'] > $this->time) {
                            $row['action'] = 'รอการส่งงาน';
                        } else {
                            $row['action'] = 'หมดเวลาส่งงาน';
                        }
                    }

                    break;
                case 2:
                    $row['action'] = '<a href="' . site_url('study/course_manager/give_score_student_act/' . $ca_id . '/' . $row['uid_student']) . '">ให้คะแนน</a>';

                    break;

                default:
                    $row['action'] = '';
                    break;
            }

            $user_detail = $this->get_user_detail($row['uid_student']);
            $send_act_data = $this->get_send_act_data($ca_id, $row['uid_student']);
            $row['student_fullname'] = $user_detail['first_name'] . ' ' . $user_detail['last_name'];
            if ($send_act_data['give_score_time'] == 0) {
                if ($ca_data['st_id'] == 5) {
                    $send_act_data['give_score_time_text'] = 'ไม่เก็บคะแนน';
                } else {
                    $send_act_data['give_score_time_text'] = 'ยังไม่ได้ให้คะแนน';
                }
                $send_act_data['get_score'] = '-';
            } else {
                $send_act_data['give_score_time_text'] = thdate('d-M-Y H:i', $send_act_data['give_score_time']);
            }

            if ($send_act_data['send_time'] == 0) {
                if ($ca_data['st_id'] == 5) {
                    $send_act_data['send_time_text'] = 'ไม่ต้องส่งงาน';
                } else {
                    $send_act_data['send_time_text'] = 'ยังไม่ได้ส่ง';
                }
            } else {
                $send_act_data['send_time_text'] = thdate('d-M-Y H:i', $send_act_data['send_time']);
            }

            // PREPOST 
            $row['pre_full_score'] = '0.00';
            $row['pre_get_score'] = '0.00';
            $row['post_full_score'] = '0.00';
            $row['post_get_score'] = '0.00';
            $row['ptest_score'] = '-';
            if ($ca_data['cmat_id'] == 2 && $ca_data['have_preposttest'] == 1) {
                if ($ca_data['at_id'] == 5) {
                    //check จำนวนข้อสอบ ของ ใบงาน
                    $this->CI->load->model('resource/sheet_model');

                    $sheet_data = $this->CI->sheet_model->get_resource_data($ca_data['data']);

                    if ($sheet_data['total_question'] > 0) {
                        $ptest_data = $this->get_ptest_data($ca_data['ca_id'], $row['uid_student']); //ดึงข้อมูล ptest

                        if ($ptest_data) {
                            if ($ptest_data['pre_send_time'] > 0) {
                                //$ptest_btn['pretest'] = '<a href="' . site_url('study/ptest/summary_ptest/pretest/' . $ptest_data['caaspt_id']) . '" target="_blank">คะแนนพรีเทส</a>';
                                $row['pre_full_score'] = $ptest_data['pre_full_score'];
                                $row['pre_get_score'] = $ptest_data['pre_get_score'];
                                if ($ptest_data['post_send_time'] > 0) {
                                    // $ptest_btn['posttest'] = '<a href="' . site_url('study/ptest/summary_ptest/posttest/' . $ptest_data['caaspt_id']) . '" target="_blank">คะแนนโพสเทส</a>';
                                    $row['post_full_score'] = $ptest_data['post_full_score'];
                                    $row['post_get_score'] = $ptest_data['post_get_score'];
                                } else {
                                    //$ptest_btn['posttest'] = '<a href="' . site_url('study/ptest/do_posttest/' . $ca_data['ca_id']) . '" target="_blank">ทำโพสเทส</a>';
                                }
                            } else {
                                // $ptest_btn['pretest'] = '<a href="' . site_url('study/ptest/do_pretest/' . $ca_data['ca_id']) . '" target="_blank">ทำพรีเทส</a>';
                            }
                        } else {
                            //$ptest_btn['pretest'] = '<a href="' . site_url('study/ptest/do_pretest/' . $ca_data['ca_id']) . '" target="_blank">ทำพรีเทส</a>';
                        }
                    }
                    $row['ptest_score'] = $row['pre_get_score'] . ' / ' . $row['post_get_score'] . ' / ' . $row['pre_full_score'];
                }
            }
            //ss
            // end PREPOST 



            $row = array_merge($row, $send_act_data);
            $data['rows'][] = array(
                'id' => $row['c_id'],
                'cell' => $row
            );
        }
        return $data;
    }

    function where_find_all_student_course_act($table_name, $qtype, $query, $c_id) {
        $this->db->where('c_id', $c_id);

        switch ($qtype) {
            case 'custom':
                foreach ($query as $k => $v) {
                    if ($v != '') {
                        switch ($k) {
                            case 'c_id':
                                $this->db->like($k, $v, 'after');
                                break;
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

    /**
     * บันทึกการให้คะแนน
     * @param type $post
     * @return boolean
     */
    function save_give_score_send_act($post) {
        if ($this->is_send_act($this->auth->uid(), $post['ca_id'])) {
            $this->db->set('data', $post['data']);
            $this->db->set('comment', $post['comment']);
            $this->db->set('get_score', $post['get_score']);
            $this->db->set('give_score_time', $this->time);
            $this->db->set('uid_give_score', $this->auth->uid());
            $this->db->where('uid_sender', $this->auth->uid());
            $this->db->where('ca_id', $post['ca_id']);
            $this->db->update('s_course_act_send');
            return TRUE;
        }
        return FALSE;
    }

    function is_give_score_send_act($uid_sender, $ca_id) {
        $this->db->where('uid_sender', $uid_sender);
        $this->db->where('ca_id', $ca_id);
        $q = $this->db->get('s_course_act_send');
        if ($q->num_rows() > 0) {
            $r = $q->row_array();
            if ($r['give_score_time'] == 0) {
                return FALSE;
            }
            return TRUE;
        } return FALSE;
    }

    /**
     * ดึงข้อมูลนักเรียนที่อยู่ในหลักสูตร
     * @param type $page
     * @param type $qtype
     * @param type $query
     * @param type $rp
     * @param type $sortname
     * @param type $sortorder
     * @param type $c_id
     * @return string
     */
    function find_all_student_course($page, $qtype, $query, $rp, $sortname, $sortorder, $c_id) {
        if ($qtype == 'custom') {
            parse_str($query, $query);
        }
        if ($sortname == 'title_play') {
            $sortname = 'title';
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
        $this->where_find_all_student_course('s_course_enroll', $qtype, $query, $c_id);
        // END Sql Query State
        $total = $this->db->count_all_results();
        // Start Sql Query State
        $this->db->select('s_course_enroll.*');
        $this->where_find_all_student_course('s_course_enroll', $qtype, $query, $c_id);
        // END Sql Query State
        $this->db->limit($rp, $offset);
        $this->db->order_by($sortname, $sortorder);
        $result = $this->db->get();
        $data['total'] = $total;
        foreach ($result->result_array() as $row) {
            $row['action'] = '';
            if ($row['enroll_type_id'] == 2 && $row['active'] == 0) {
                $row['action'] .='<a href="' . site_url('study/course_manager/course_enroll_approve/' . $row['ce_id']) . '" calss="btn-a">อนุมัติ</a>';
            }
            $row['action'] .='<a href="' . site_url('study/course_manager/course_enroll_delete/' . $row['ce_id']) . '" calss="btn-a">เอาออกจากหลักสูตร</a>';

            $user_detail = $this->get_user_detail($row['uid_student']);
            $row['active_text'] = ($row['active']) ? 'อยู่ในชั้นเรียน' : 'รออนุมัติ';
            $row['student_fullname'] = '<a href="' . site_url('admin/users/detail/' . $row['uid_student']) . '" calss="btn-a" target="_blank">' . $user_detail['first_name'] . ' ' . $user_detail['last_name'] . '</a>';

            $data['rows'][] = array(
                'id' => $row['c_id'],
                'cell' => $row
            );
        }
        return $data;
    }

    function where_find_all_student_course($table_name, $qtype, $query, $c_id) {
        $this->db->where('c_id', $c_id);

        switch ($qtype) {
            case 'custom':
                foreach ($query as $k => $v) {
                    if ($v != '') {
                        switch ($k) {
                            case 'c_id':
                                $this->db->like($k, $v, 'after');
                                break;
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

    /**
     * ครูดึงข้อมูลงานในหลักสูตร 
     * @param type $page
     * @param type $qtype
     * @param type $query
     * @param type $rp
     * @param type $sortname
     * @param type $sortorder
     * @param type $c_id
     * @return type
     */
    function find_all_course_act($page, $qtype, $query, $rp, $sortname, $sortorder, $c_id) {
        $cmat_id_options = $this->CI->ddoption_model->get_cmat_id_options();
        $send_type_options = $this->CI->ddoption_model->get_send_type_options();
        $act_type_options = $this->CI->ddoption_model->get_act_type_options();

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
        $this->where_find_all_course_act('s_course_act', $qtype, $query, $c_id);
        // END Sql Query State
        $total = $this->db->count_all_results();
        // Start Sql Query State
        $this->db->select('s_course_act.*');
        $this->where_find_all_course_act('s_course_act', $qtype, $query, $c_id);
        // END Sql Query State
        $this->db->limit($rp, $offset);
        if ($sortname == 'title_play') {
            $this->db->order_by('title', $sortorder);
        } else if ($sortname == 'act_date' || $sortname == 'time_range') {
            $this->db->order_by('start_time', $sortorder);
            $this->db->order_by('end_time', $sortorder);
        } else {
            $this->db->order_by($sortname, $sortorder);
        }

        $result = $this->db->get();
        $data['total'] = $total;

        //send type

        $full_score = 0;
        foreach ($result->result_array() as $row) {
            //$row['action'] = '<a href="' . site_url('study/course_manager/student_act/' . $row['ca_id']) . '">ประเมินผล</a>';
            $row['action'] = '';
            if ($row['cmat_id'] == 2) {

                $row['action'] .= '<a href="' . site_url('play/play_resource/open_sheet/' . $row['data'] . '/' . $row['uid_assign']) . '" target="_blank">ดูใบงาน</a>';
            }
            if ($row['st_id'] == 4) {
                $row['action'] .= '<a href="' . site_url('/study/exam/do_teacher_exam/' . $row['ca_id']) . '">ลองทำ</a>';
                if ($row['at_id'] != 5) {
                    if ($this->practice_num_rows($row['ca_id']) > 0) {
                        $row['action'] .= '<a href="' . site_url('study/course_manager/summary_practice/' . $row['ca_id']) . '">ข้อมูลซ้อมสอบ</a>';
                    }
                }
            }

            //if ($row['st_id'] == 4 && $row['at_id'] == 5) {
            if ($row['cmat_id'] == 2 && $row['at_id'] == 5) {
                $row['action'] .= '<a href="' . site_url('/study/exam/do_teacher_exam/' . $row['ca_id']) . '">ลองทำ</a>';
                if ($row['have_preposttest']) {

                    if ($this->ptest_num_rows($row['ca_id']) > 0) {
                       // $row['action'] .= '<a href="' . site_url('study/course_manager/summary_ptest/' . $row['ca_id']) . '">ข้อมูลพรีเทส โพสเทส</a>';
                        $row['action'] .= '<a href="' . site_url('study/course_manager/ranking_ptest/' . $row['ca_id']) . '">ข้อมูลพรีเทส โพสเทส</a>';
                    } 
                }
            }


            $row['action'] .= '<a href="' . site_url('study/course_manager/edit_course_act/' . $row['ca_id']) . '">แก้ไข</a>';
            $row['action'] .= '<a href="' . site_url('study/course_manager/delete_course_act/' . $row['ca_id']) . '">ลบ</a>';
            $row['act_date'] = thdate('d-M-Y', $row['start_time']);
            $row['start_time_text'] = thdate('d-M-Y H:i', $row['start_time']);
            $row['end_time_text'] = thdate('d-M-Y H:i', $row['end_time']);

            $row['send_type'] = $send_type_options[$row['st_id']];

            $row = array_merge($row, $this->get_course_act_summary($row['ca_id']));
            $row['title_text'] = '<span title="' . $row['title'] . ' : ' . $row['data'] . '" >' . $row['title'] . '</span>';
            //$row['title_play'] = '<a href="' . site_url('study/course_manager/student_act/' . $row['ca_id']) . '" target="_blank">▶</a>' . $row['title_text'];
            //check can play video
            if ($row['cmat_id'] == 2) {
                $sheet_data = $this->get_sheet_data($row['data']);
            } else {
                $sheet_data = array('count_video' => 0);
            }
            //print_r($sheet_data);
//            if ($row['at_id'] == 1 && $sheet_data['count_video'] > 0) {
            $row['action_sheet'] = '';
            if ($row['at_id'] == 5) {

                if ($row['cmat_id'] == 2) {
                    $row['title_play'] = '<a href="' . site_url('study/course_manager/student_act/' . $row['ca_id']) . '" target="_blank">คะแนนนักเรียน</a>' . '<a href="' . site_url('play/play_resource/play_sheet_video/' . $row['data']) . '" target="_blank">▶</a>' . $row['title_text'];
                    $row['action_sheet'] = '<a href="' . site_url('v/' . $row['data']) . '" target="_blank" class="btn-a-small">ใบงาน</a>';
                } else {
                    $row['title_play'] = '<a href="' . site_url('study/course_manager/student_act/' . $row['ca_id']) . '" target="_blank">คะแนนนักเรียน</a>' . $row['title_text'];
                }
            } else {
                $row['title_play'] = '<a href="' . site_url('study/course_manager/student_act/' . $row['ca_id']) . '" target="_blank">คะแนนนักเรียน</a>' . $row['title_text'];
                if ($row['cmat_id'] == 2) {
                    $row['action_sheet'] = '<a href="' . site_url('v/' . $row['data']) . '" target="_blank" class="btn-a-small">ใบงาน</a>';
                }
            }

            $row['full_score_text'] = ($row['full_score'] > 0) ? $row['full_score'] : 'ไม่เก็บคะแนน';
            $full_score +=$row['full_score'];
            $row['command_act_type'] = $cmat_id_options[$row['cmat_id']];
            if (date('Ymd', $row['start_time']) == date('Ymd', $row['end_time'])) {
                $row['time_range'] = thdate('d-M-Y', $row['start_time']) . ' เวลา ' . thdate('H:i', $row['start_time']) . ' น. ถึง ' . thdate('H:i', $row['end_time']) . ' น.';
            } else {
                if ($row['end_time'] == 2145891600) {
                    $row['time_range'] = thdate('d-M-Y H:i', $row['start_time']) . ' น. เป็นต้นไป';
                } else {
                    $row['time_range'] = thdate('d-M-Y H:i', $row['start_time']) . ' น. ถึง ' . thdate('d-M-Y H:i', $row['end_time']) . ' น.';
                }
            }
            $row['act_type'] = $act_type_options[$row['at_id']];
            $data['rows'][] = array(
                'id' => $row['ca_id'],
                'cell' => $row
            );
        }

        $field_row = array(
            'ca_id' => '',
            'c_id' => '',
            'course_group' => '',
            'title' => '',
            'create_time' => '',
            'cmat_id' => '',
            'data' => '',
            'start_time' => '',
            'end_time' => '',
            'st_id' => '',
            'uid_assign' => '',
            'full_score' => $full_score,
            'full_score_text' => $full_score,
            'at_id' => '',
            'have_preposttest' => '',
            'start_time_text' => '',
            'title_play' => '',
            'time_range' => '',
            'act_type' => '',
            'title_text' => '',
            'send_type' => '',
            'act_date' => '',
            'count_send' => '',
            'action' => '',
            'action_sheet' => ''
        );
        $data['rows'][] = array(
            'id' => 0,
            'cell' => $field_row
        );
        return $data;
    }

    function where_find_all_course_act($table_name, $qtype, $query, $c_id) {

        $this->db->where('c_id', $c_id);
        //$this->db->where('uid_owner', $this->auth->uid());
        switch ($qtype) {
            case 'custom':
                foreach ($query as $k => $v) {
                    if ($v != '') {
                        switch ($k) {
                            case 'c_id':
                                $this->db->like($k, $v, 'after');
                                break;
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

    /**
     * ดึงผลสรุปของงานในหลักสูตร
     * @param type $ca_id
     * @return type
     */
    function get_course_act_summary($ca_id) {
        $data = array(
            'count_send' => 0,
        );
        $this->db->where('ca_id', $ca_id);
        $q = $this->db->get('s_course_act_send');
        $data['count_send'] = $q->num_rows();
        return $data;
    }

    function get_sheet_data($resource_id_sheet) {
        $this->db->where('resource_id', $resource_id_sheet);
        $q = $this->db->get('r_resource_sheet');
        if ($q->num_rows() > 0) {
            $row = $q->row_array();
            $row['data'] = json_decode($row['data'], TRUE);
            $count_video = 0;
            $a_resource_id = array();

            $sheet_set = $row['data']['sheet_set'];
            foreach ($sheet_set as $v) {
                if (isset($v['resource_id'])) {
                    $a_resource_id[] = $v['resource_id'];
                }
            }

            if (count($a_resource_id) > 0) {
                $this->db->where_in('resource_id', $a_resource_id);
                $count_video = $this->db->count_all_results('r_resource_video_join');
            }
            $row['count_video'] = $count_video;
            return $row;
        } else {
            return FALSE;
        }
    }

    function copy_course($c_id, $uid = '') {
        if ($uid == '') {
            $uid = $this->auth->uid();
        }
        $this->db->trans_start();
        $course_data = $this->get_course_data($c_id);
        //check have subject 
        $this->db->where('subj_id', $course_data['subj_id']);
        $q = $this->db->get('f_subject');
        if ($q->num_rows() > 0) {
            $row = $q->row_array();
            if ($row['uid_owner'] != 0) {
                $subject_set = array(
                    'la_id' => $course_data['la_id'],
                    'title' => $course_data['subject_title'],
                    'uid_owner' => $uid
                );
                $this->db->set($subject_set);
                $this->db->insert('f_subject');
            }
        }



        $course_set = array(
            'title' => $course_data['title'],
            'uid_owner' => $uid,
            'desc' => $course_data['desc'],
            'start_time' => $course_data['start_time'],
            'end_time' => $course_data['end_time'],
            'enroll_type_id' => 1,
            'enroll_limit' => $course_data['enroll_limit'],
            'la_id' => $course_data['la_id'],
            'chapter' => $course_data['chapter'],
            'subj_id' => $course_data['subj_id'],
            'subject_title' => $course_data['subject_title'],
            'publish' => $course_data['publish'],
            'degree_id' => $course_data['degree_id']
        );
        $this->db->set($course_set);
        $this->db->insert('s_course');
        $new_c_id = $this->db->insert_id();

        $all_course_act_data = $this->get_all_course_act_data($c_id);
        foreach ($all_course_act_data as $row_course_act) {
            $course_act_set = array(
                'c_id' => $new_c_id,
                'title' => $row_course_act['title'],
                'create_time' => $this->time,
                'cmat_id' => $row_course_act['cmat_id'],
                'data' => $row_course_act['data'],
                'start_time' => $row_course_act['start_time'],
                'end_time' => $row_course_act['end_time'],
                'st_id' => $row_course_act['st_id'],
                'uid_assign' => $uid,
                'full_score' => $row_course_act['full_score'],
                'at_id' => $row_course_act['at_id'],
                'have_preposttest' => $row_course_act['have_preposttest']
            );
            $this->db->set($course_act_set);
            $this->db->insert('s_course_act');
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * อัพเดตชื่อเจ้าของ
     * @param type $uid_owner
     */
    function update_course_owner_detail($uid_owner = '') {
        if ($uid_owner != '') {
            $user_data = $this->auth->get_user_data($uid_owner);
            $set = array(
                'full_name_owner' => $user_data['full_name'],
                'school_name_owner' => $user_data['school_name']
            );
            $this->db->set($set);
            $this->db->where('uid_owner', $uid_owner);
            $this->db->update('s_course');
        } else {
            $this->db->distinct('uid_owner');
            $q = $this->db->get('s_course');
            if ($q->num_rows() > 0) {
                foreach ($q->result_array() as $row) {
                    $user_data = $this->auth->get_user_data($row['uid_owner']);
                    $set = array(
                        'full_name_owner' => $user_data['full_name'],
                        'school_name_owner' => $user_data['school_name']
                    );
                    $this->db->set($set);
                    $this->db->where('uid_owner', $row['uid_owner']);
                    $this->db->update('s_course');
                }
            }
        }
    }

    function find_all_course_open_teacher($page, $qtype, $query, $rp, $sortname, $sortorder, $uid_owner = '', $hide_for_enroll = FALSE, $publish = 1) {
        $degree_options = $this->CI->ddoption_model->get_degree_id_options();
        $degree_options[0] = '';

        //$this->update_course_owner_detail();
        $rid = $this->auth->get_rid();
        if ($sortname == 'title_play') {
            $sortname = 'title';
        }
        if ($qtype == 'custom') {
            parse_str($query, $query);
        }


        //init search cond
        //initial data        
        $data = array(
            'rows' => array(),
            'page' => $page,
            'total' => 0
        );
        //make offset
        $offset = (($page - 1) * $rp);
        // Start Sql Query State for count row
        $this->where_find_all_course_open_tescher('s_course', $qtype, $query, $uid_owner, $publish);
        // END Sql Query State
        $total = $this->db->count_all_results();
        //exit();
        // Start Sql Query State
        $this->db->select('s_course.*');
        $this->where_find_all_course_open_tescher('s_course', $qtype, $query, $uid_owner, $publish);
        // END Sql Query State
        $this->db->limit($rp, $offset);
        $this->db->order_by($sortname, $sortorder);
        $result = $this->db->get();
        $data['total'] = $total;

        foreach ($result->result_array() as $row) {
            $have_enroll = $this->have_enroll($row['c_id']);
            //$have_enroll = TRUE;
            $row['action'] = '';
            if ($hide_for_enroll) {
                if ($have_enroll) {
                    continue;
                }
            }
            $student_count = $this->get_count_student_in_course($row['c_id']);
            //$student_count = 1;
            if ($rid == 2) {


                if ($row['enroll_limit'] > $student_count || $row['enroll_limit'] == 0) {
                    if (!$have_enroll) {

                        $row['action'] .= '<a href="' . site_url('study/course/enroll/' . $row['c_id']) . '">สมัคร</a>';
                    } else {
                        $row['action'] .= '<span class="error">สมัครไปแล้ว</span>';
                    }
                } else {
                    $row['action'] .= '<span class="error">เต็ม</span>';
                }
            } else if ($rid == 3) {
                $row['action'] .= '<a href="' . site_url('study/course_manager/view_course_details/' . $row['c_id']) . '">เปิดดู</a>';
            }
            $row['start_time'] = thdate('d-M-Y', $row['start_time']);
            $row['end_time'] = thdate('d-M-Y', $row['end_time']);
            $row['enroll_limit_text'] = $row['enroll_limit'];
            if ($row['enroll_limit'] == 0) {
                $row['enroll_limit_text'] = 'ไม่จำกัด';
            }
            $row['degree_text'] = $degree_options[$row['degree_id']];

            $data['rows'][] = array(
                'id' => $row['c_id'],
                'cell' => $row
            );
        }
        return $data;
    }

    function where_find_all_course_open_tescher($table_name, $qtype, $query, $uid_owner = '', $publish = 1) {
        if ($publish != 2) {
            $this->db->where('publish', $publish);
        }

        $this->db->where('end_time >', $this->time);
        $this->db->where('start_time <', $this->time);

        if ($uid_owner != '') {
            $this->db->where('uid_owner', $uid_owner);
        }

        switch ($qtype) {
            case 'custom':
                foreach ($query as $k => $v) {
                    if ($v != '') {
                        switch ($k) {
                            case 'c_id':
                                $this->db->like($k, $v, 'after');
                                break;
                            case 'title':
                                $this->db->like($k, $v);
                                break;
                            case 'desc':
                                $this->db->like($k, $v);
                                break;
                            case 'subject_title':
                                $this->db->like($k, $v);
                                break;
                            case 'full_name_owner':
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

    /**
     * ดึงข้อมูลคะแนนนักเรียน
     * @param type $c_id
     * @return type
     */
    function get_studeunt_score_data($c_id) {
        $course_data = $this->get_course_data($c_id);
        $course_act_data = $this->get_all_course_act_data($c_id);
        $course_act_data_tmp = array();
        //หาคะแนนรวมทั้งหมดในหลักสูตร
        $course_act_full_score = 0;
        foreach ($course_act_data as $v_course_act) {
            if ($v_course_act['full_score'] > 0) {
                $course_act_data_tmp[] = $v_course_act;
                $course_act_full_score +=$v_course_act['full_score'];
            }
        }




        $course_score_data = array(
            'course_data' => $course_data,
            'score_data' => array(),
            'student_count' => 0,
            'course_enroll_data' => array(),
            'course_act_data' => $course_act_data_tmp,
            'course_act_full_score' => $course_act_full_score
        );


        $course_enroll_data = $this->get_all_course_enroll_data($c_id);

        $tmp_score = array();
        foreach ($course_enroll_data as $v_enroll) {
            $course_score_data['student_count'] ++;
            $score_data = array(
                'score' => array(),
                'sum_score' => 0
            );
            //ดึงข้อมูลคะแนนให้นักเรียนแต่ละคน
            $sum_score = 0;
            foreach ($course_act_data as $v_course_act) {
                if ($v_course_act['full_score'] > 0) {
                    $this->db->where('uid_sender', $v_enroll['uid_student']);
                    $this->db->where('ca_id', $v_course_act['ca_id']);
                    $q_course_act_send = $this->db->get('s_course_act_send');
                    if ($q_course_act_send->num_rows() > 0) {
                        $r_course_act_send = $q_course_act_send->row_array();
                        $score_data['score'][] = $r_course_act_send['get_score'];
                        $sum_score += $r_course_act_send['get_score'];
                    } else {
                        $score_data['score'][] = '-';
                    }
                }
            }
            $score_data['sum_score'] = $sum_score;
            //print_r($v_enroll);
            $tmp_score[] = array(
                'uid_student' => $v_enroll['uid_student'],
                'user_detail' => $v_enroll['user_datail'],
                'score_data' => $score_data
            );
        }

        $course_score_data['score_data'] = $tmp_score;

        return $course_score_data;
    }

    /**
     * ดึงข้อมูลนักเรียนที่อยู่ในหลักสูตร
     * @param type $c_id
     * @return type
     */
    function get_all_course_enroll_data($c_id) {
        $data = array();
        $this->db->where('c_id', $c_id);
        $q = $this->db->get('s_course_enroll');
        if ($q->num_rows() > 0) {
            $data = $q->result_array();
            foreach ($data as $k => $v) {
                $data[$k]['user_datail'] = $this->get_user_detail($v['uid_student']);
            }
        }
        return $data;
    }

    /**
     * ดึงข้อมูลการสมัครคอร์ของนักเรียน
     * @param type $c_id
     * @return type
     */
    function get_course_enroll_data($ce_id) {
        $this->db->where('ce_id', $ce_id);
        $q = $this->db->get('s_course_enroll');
        $r = $q->row_array();
        $r['user_datail'] = $this->get_user_detail($r['uid_student']);
        return $r;
    }

    /**
     * ให้คะแนนการส่งงาน
     * @param type $data
     * @return boolean
     */
    function give_score_course_act($data) {
        $this->db->set('uid_give_score', $this->auth->uid());
        $this->db->set('get_score', $data['get_score']);
        $this->db->set('comment', $data['comment']);
        $this->db->set('give_score_time', $this->time);
        $this->db->set('uid_sender', $data['uid_sender']);
        if ($data['cas_id'] == '') {
            $this->db->set('ca_id', $data['ca_id']);
            $this->db->set('c_id', $data['c_id']);
            $this->db->insert('s_course_act_send');
        } else {
            $this->db->where('cas_id', $data['cas_id']);
            $this->db->update('s_course_act_send');
        }
        return TRUE;
    }

    /**
     * ดึงข้อมูลงานในหลักสูตรมาทั้งหมด
     * @param type $c_id
     * @param type $publish 0=ไม่ตีพิมพ์  1=ตีพิมพ์ 2=ทั้งหมด
     * @return string
     */
    function get_all_course_act_data($c_id) {
        $cmat_id_options = $this->CI->ddoption_model->get_cmat_id_options();
        $send_type_options = $this->CI->ddoption_model->get_send_type_options();
        $act_type_options = $this->CI->ddoption_model->get_act_type_options();
        $data = array();
        $this->db->where('c_id', $c_id);
        $this->db->order_by('start_time');
        $this->db->order_by('end_time');
        $q = $this->db->get('s_course_act');

        if ($q->num_rows() > 0) {
            foreach ($q->result_array() as $row) {
                $row['start_time_text'] = thdate('d-M-Y H:i', $row['start_time']);
                $row['end_time_text'] = thdate('d-M-Y H:i', $row['end_time']);
                if ($row['st_id'] == 0) {
                    $row['time_range'] = '-';
                } else {
                    $row['time_range'] = $row['start_time_text'] . ' ถึง ' . $row['end_time_text'];
                }
                $row['send_type'] = $send_type_options[$row['st_id']];
                $row['act_type'] = $act_type_options[$row['at_id']];
                $row['command_act_type'] = $cmat_id_options[$row['cmat_id']];
                $data[] = $row;
            }
        }


        return $data;
    }

//================================================================================================================    
// FOR student
//================================================================================================================    

    /**
     * ดึงข้อมูลหลักสูตรของฉันตามเลขที่หลักสูตร
     * @param type $c_id
     * @return boolean
     */
    function get_my_course_enroll_data($c_id) {
        $uid = $this->auth->uid();
        $this->db->where('c_id', $c_id);
        $this->db->where('uid_student', $uid);
        $q1 = $this->db->get('s_course_enroll');
        if ($q1->num_rows() > 0) {
            $this->db->where('c_id', $c_id);
            $q2 = $this->db->get('s_course');
            $row = array_merge($q1->row_array(), $q2->row_array());
            $row['start_time_form_text'] = date('d/m/Y', $row['start_time']);
            $row['end_time_form_text'] = date('d/m/Y', $row['end_time']);
            $row['user_data_owner'] = $this->get_user_detail($row['uid_owner']);
            return $row;
        }
        return FALSE;
    }

    /**
     * ดึงข้อมูล รายการหลักสูตรที่ฉันเรียน
     * @param type $page
     * @param type $qtype
     * @param type $query
     * @param type $rp
     * @param type $sortname
     * @param type $sortorder
     * @return type
     */
    function find_all_my_course($page, $qtype, $query, $rp, $sortname, $sortorder) {
        if ($qtype == 'custom') {
            parse_str($query, $query);
        }
        if ($sortname == 'title_play') {
            $sortname = 'title';
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
        $this->where_find_all_my_course('s_course', $qtype, $query);
        // END Sql Query State
        $total = $this->db->count_all_results();
        // Start Sql Query State
        $this->db->select('s_course.*');
        $this->where_find_all_my_course('s_course', $qtype, $query);
        // END Sql Query State
        $this->db->limit($rp, $offset);
        $this->db->order_by($sortname, $sortorder);
        $result = $this->db->get();
        $data['total'] = $total;

        foreach ($result->result_array() as $row) {
//            $row['action'] = '<a href="' . site_url('study/course/course_act/' . $row['c_id']) . '">เข้าเรียน</a>';
            $row['action'] = '';
            $row['action'] .= '<a href="' . site_url('study/course/report_course_score/' . $row['c_id']) . '">ผลการเรียน</a>';
            $row['action'] .= '<a href="' . site_url('study/course/delete_enroll/' . $row['c_id']) . '">ออกจากหลักสูตร</a>';
            $enroll_data = $this->get_my_course_enroll_data($row['c_id']);
            if ($enroll_data['active'] == 0 && $enroll_data['enroll_type_id'] == 2) {
                $row['title'] = $row['title'] . ' [รอการอนุมัติเพื่อเข้าเรียน]';
            } else {
                $row['title'] = '<a href="' . site_url('study/course/course_act/' . $row['c_id']) . '" calss="btn-a">' . $row['title'] . '</a>';
            }
            $row['start_time'] = thdate('d-M-Y', $row['start_time']);
            $row['end_time'] = thdate('d-M-Y', $row['end_time']);
            $owner_detail = $this->get_user_detail($row['uid_owner']);
            $row['owner_full_name'] = '<span title="' . $owner_detail['full_name'] . ' | ' . $owner_detail['school_name'] . '">' . $owner_detail['full_name'] . '</span>';
            $row['owner_school_name'] = $owner_detail['school_name'];
            $data['rows'][] = array(
                'id' => $row['c_id'],
                'cell' => $row
            );
        }
        return $data;
    }

    /**
     * ดึงข้อมูล รายการหลักสูตรที่ฉันเรียน
     * @param type $page
     * @param type $qtype
     * @param type $query
     * @param type $rp
     * @param type $sortname
     * @param type $sortorder
     * @return type
     */
    function find_all_my_course_rp($page, $qtype, $query, $rp, $sortname, $sortorder) {
        if ($qtype == 'custom') {
            parse_str($query, $query);
        }
        if ($sortname == 'title_play') {
            $sortname = 'title';
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
        $this->where_find_all_my_course('s_course', $qtype, $query);
        // END Sql Query State
        $total = $this->db->count_all_results();
        // Start Sql Query State
        $this->db->select('s_course.*');
        $this->where_find_all_my_course('s_course', $qtype, $query);
        // END Sql Query State
        $this->db->limit($rp, $offset);
        $this->db->order_by($sortname, $sortorder);
        $result = $this->db->get();
        $data['total'] = $total;

        foreach ($result->result_array() as $row) {
//            $row['action'] = '<a href="' . site_url('study/course/course_act/' . $row['c_id']) . '">เข้าเรียน</a>';
            $row['action'] = '';
            $row['action'] .= '<a href="' . site_url('study/course/report_course_score/' . $row['c_id']) . '">ผลการเรียน</a>';
//            $row['action'] .= '<a href="' . site_url('study/course/delete_enroll/' . $row['c_id']) . '">ออกจากหลักสูตร</a>';
            $enroll_data = $this->get_my_course_enroll_data($row['c_id']);
//            if ($enroll_data['active'] == 0 && $enroll_data['enroll_type_id'] == 2) {
//                $row['title'] = $row['title'] . ' [รอการอนุมัติเพื่อเข้าเรียน]';
//            } else {
//                $row['title'] = '<a href="' . site_url('study/course/course_act/' . $row['c_id']) . '" calss="btn-a">' . $row['title'] . '</a>';
//            }
            $row['start_time'] = thdate('d-M-Y', $row['start_time']);
            $row['end_time'] = thdate('d-M-Y', $row['end_time']);
            $owner_detail = $this->get_user_detail($row['uid_owner']);
            $row['owner_full_name'] = '<span title="' . $owner_detail['full_name'] . ' | ' . $owner_detail['school_name'] . '">' . $owner_detail['full_name'] . '</span>';
            $row['owner_school_name'] = $owner_detail['school_name'];
            $data['rows'][] = array(
                'id' => $row['c_id'],
                'cell' => $row
            );
        }
        return $data;
    }

    /**
     * where ดึงข้อมูล รายการหลักสูตรที่ฉันเรียน 
     * @param type $table_name
     * @param type $qtype
     * @param type $query
     */
    function where_find_all_my_course($table_name, $qtype, $query) {
        $uid = $this->auth->uid();
        $this->db->where("c_id in(select c_id from s_course_enroll where uid_student=$uid)");

        switch ($qtype) {
            case 'custom':
                foreach ($query as $k => $v) {
                    if ($v != '') {
                        switch ($k) {
                            case 'c_id':
                                $this->db->like($k, $v, 'after');
                                break;
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

    /**
     * ดึงข้อมูลงานในคอร์ของนักเรียน--ตัวเอง
     * @param type $page
     * @param type $qtype
     * @param type $query
     * @param type $rp
     * @param type $sortname
     * @param type $sortorder
     * @param type $c_id
     * @return string
     */
    function find_all_my_course_act($page, $qtype, $query, $rp, $sortname, $sortorder, $c_id, $get_summary = TRUE) {
        $uid = $this->auth->uid();
        $send_type_options = $this->CI->ddoption_model->get_send_type_options();
        $act_type_options = $this->CI->ddoption_model->get_act_type_options();

        if ($sortname == 'title_play') {
            $sortname = 'title';
        }
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
        $this->where_find_all_course_act('s_course_act', $qtype, $query, $c_id);
        // END Sql Query State
        $total = $this->db->count_all_results();
        // Start Sql Query State
        $this->db->select('s_course_act.*');
        $this->where_find_all_course_act('s_course_act', $qtype, $query, $c_id);
        // END Sql Query State
        $this->db->limit($rp, $offset);
        $this->db->order_by($sortname, $sortorder);
        $result = $this->db->get();
        $data['total'] = $total;

        $sum_get_score = 0;
        $sum_full_score = 0;
        foreach ($result->result_array() as $row) {
            $row['pre_full_score'] = '';
            $row['pre_get_score'] = '';
            $row['post_full_score'] = '';
            $row['post_get_score'] = '';
            $course_act_send_data = $this->get_course_act_send_data($row['ca_id']);
            $row['action'] = '';
            if ($row['cmat_id'] == 2) {
                $sheet_data = $this->get_sheet_data($row['data']);
            } else {
                $sheet_data = array('count_video' => 0);
            }
            $ptest_btn = array(
                'pretest' => '',
                'posttest' => ''
            );
            if ($row['cmat_id'] == 2 && $row['have_preposttest'] == 1) {
                if ($row['at_id'] == 5) {
                    //check จำนวนข้อสอบ ของ ใบงาน
                    $this->CI->load->model('resource/sheet_model');
                    $sheet_data = $this->CI->sheet_model->get_resource_data($row['data']);
                    if ($sheet_data['total_question'] > 0) {
                        $ptest_data = $this->get_ptest_data($row['ca_id']); //ดึงข้อมูล ptest

                        if ($ptest_data) {
                            if ($ptest_data['pre_send_time'] > 0) {
                                $ptest_btn['pretest'] = '<a href="' . site_url('study/ptest/summary_ptest/pretest/' . $ptest_data['caaspt_id']) . '" target="_blank">คะแนนพรีเทส</a>';
                                $row['pre_full_score'] = $ptest_data['pre_full_score'];
                                $row['pre_get_score'] = $ptest_data['pre_get_score'];
                                if ($ptest_data['post_send_time'] > 0) {
                                    $ptest_btn['posttest'] = '<a href="' . site_url('study/ptest/summary_ptest/posttest/' . $ptest_data['caaspt_id']) . '" target="_blank">คะแนนโพสเทส</a>';
                                    $row['post_full_score'] = $ptest_data['post_full_score'];
                                    $row['post_get_score'] = $ptest_data['post_get_score'];
                                } else {
                                    $ptest_btn['posttest'] = '<a href="' . site_url('study/ptest/do_posttest/' . $row['ca_id']) . '" target="_blank">ทำโพสเทส</a>';
                                }
                            } else {
                                $ptest_btn['pretest'] = '<a href="' . site_url('study/ptest/do_pretest/' . $row['ca_id']) . '" target="_blank">ทำพรีเทส</a>';
                            }
                        } else {
                            $ptest_btn['pretest'] = '<a href="' . site_url('study/ptest/do_pretest/' . $row['ca_id']) . '" target="_blank">ทำพรีเทส</a>';
                        }
                    }
                }
            }
            $row['action'] .= $ptest_btn['pretest'];

            if ($this->time > $row['start_time']) {
                if ($row['at_id'] == 5) {//ถ้าเป็นการบ้าน
                    if ($row['cmat_id'] == 2) {

                        
                        //if ($row['st_id'] == 5) {
                            $row['action'] .= '<a href="' . site_url('play/play_resource/play_sheet_video/' . $row['data']) . '" target="_blank">▶ ดูวิดีโอ</a>';
                       // }
                        $row['action'] .= '<a href="' . site_url('v/' . $row['data']) . '" target="_blank">เปิดดูใบงาน</a>';
                    }
                }
            }



            if ($this->time < $row['end_time']) {
                if ($this->time > $row['start_time']) {




                    switch ($row['st_id']) {
                        case 1:
                            if ($this->is_give_score_send_act($uid, $row['ca_id'])) {
                                $row['action'] .= '<a href="' . site_url('study/course/summary_answer_sent/' . $course_act_send_data['cas_id']) . '"  >ผลการส่งงาน</a>';
                            } else {
                                $row['action'] .= '<a href="' . site_url('study/course/do_act/' . $row['ca_id']) . '">อัพโหลดงาน</a>';
                            }
                            break;
                        case 2: //ส่งที่โต๊ะ
                            $row['action'] .= 'ส่งงานที่โต๊ะครู';
                            break;
                        case 3: //พิมพ์ส่งหน้าเว็บ

                            if ($this->is_give_score_send_act($uid, $row['ca_id'])) {
                                $row['action'] .= '<a href="' . site_url('study/course/summary_answer_sent/' . $course_act_send_data['cas_id']) . '"  >ผลการส่งงาน</a>';
                            } else {
                                $row['action'] .= '<a href="' . site_url('study/course/do_act/' . $row['ca_id']) . '">พิมพ์งานส่ง</a>';
                            }


                            break;
                        case 4: // ทำออนไลน์
                            if ($course_act_send_data) {
                                $row['action'] .= '<a href="' . site_url('study/exam/summary_answer_sheet/' . $course_act_send_data['cas_id']) . '"  >รายละเอียดคะแนน</a>';
                            } else {
                                switch ($row['at_id']) {
                                    case 1://การบ้าน

                                        $row['action'] .= '<a href="' . site_url('study/course/do_act/' . $row['ca_id']) . '">ทำการบ้าน</a>';

                                        break;
                                    case 2: //สอบเก็บคะแนน
                                        $row['action'] .= '<a href="' . site_url('study/course/do_act/' . $row['ca_id']) . '">สอบเก็บคะแนน</a>';
                                        break;

                                    case 3: //สอบกลางภาค
                                        $row['action'] .= '<a href="' . site_url('study/course/do_act/' . $row['ca_id']) . '">สอบกลางภาค</a>';
                                        break;
                                    case 4://สอบปลายภาค
                                        $row['action'] .= '<a href="' . site_url('study/course/do_act/' . $row['ca_id']) . '">สอบปลายภาค</a>';
                                        break;
                                    case 5://ชุดการเรียน
                                        $row['action'] .= '<a href="' . site_url('study/course/do_act/' . $row['ca_id']) . '">ทำแบบทดสอบ</a>';
                                        break;
                                    default:
                                        break;
                                }
                            }


                            break;
                        default:
                            break;
                    }
                } else {
                    $row['action'] = '';
                    switch ($row['at_id']) {
                        case 1://การบ้าน
                            //$row['action'] .= '<a href="#">ซ้อมสอบ</a>';
                            $row['action'] .= '<span class="warning" >ยังไม่ถึงเวลา</span> ';
                            break;
                        case 2: //สอบเก็บคะแนน
                        case 3: //สอบกลางภาค
                        case 4://สอบปลายภาค
                            $row['action'] .= '<a href="' . site_url('study/practice/do_practice/' . $row['ca_id']) . '" target="_blank">ซ้อมสอบ</a>';
                            $row['action'] .= '<span class="warning" >ยังไม่ถึงเวลาสอบ</span> ';
                            break;
                        case 5://ชุดการเรียน
                            break;
                        default:
                            break;
                    }
                }
            } else {
                if ($course_act_send_data) { //ถ้ามีการส่งงานแล้ว
                    if ($row['st_id'] == 4) {//ถ้าเป็นออนไลน์
                        if ($row['end_time'] < $this->time) {
                            $row['action'] .= '<a href="' . site_url('study/exam/summary_answer_sheet/' . $course_act_send_data['cas_id']) . '"  >รายละเอียดคะแนน/เฉลย</a>';
                        } else {
                            $row['action'] .= '<a href="' . site_url('study/exam/summary_answer_sheet/' . $course_act_send_data['cas_id']) . '"  >รายละเอียดคะแนน</a>';
                        }
                    } else {//ไม่ออนไลน์
                        $row['action'] .= '<a href="' . site_url('study/course/summary_answer_sent/' . $course_act_send_data['cas_id']) . '"  >ผลการส่งงาน</a>';
                    }
                }
                if ($row['at_id'] != 1) {
                    $row['action'] .= '<a href="' . site_url('study/practice/do_practice/' . $row['ca_id']) . '" target="_blank">ซ้อมสอบ</a>';
                } else if ($row['at_id'] != 5) {
                    $row['action'] .= '<a href="' . site_url('study/practice/do_practice/' . $row['ca_id']) . '" target="_blank">ซ้อมสอบ</a>';
                }
                $row['action'] .= '<span class="error" >หมดเวลาส่งงาน</span>';
            }

            $row['action'] .= $ptest_btn['posttest'];
            $row['full_score_text'] = ($row['full_score'] > 0) ? $row['full_score'] : 'ไม่เก็บคะแนน';
            // $row['title'] = '<a href="' . site_url('study/course/view_act/' . $row['ca_id']) . '" title="' . $row['data'] . '">' . $row['title'] . '</a>';
            $row['title'] = '<span  title="' . $row['title'] . ' | ' . $row['data'] . '">' . $row['title'] . '</span>';

            $row['start_time_text'] = thdate('d-M-Y H:i', $row['start_time']);
            $row['end_time_text'] = thdate('d-M-Y H:i', $row['end_time']);
            $row['act_date'] = thdate('d-M-Y', $row['start_time']);
            if (date('Ymd', $row['start_time']) == date('Ymd', $row['end_time'])) {
                $row['time_range'] = thdate('d-M-Y', $row['start_time']) . ' เวลา ' . thdate('H:i', $row['start_time']) . ' น. ถึง ' . thdate('H:i', $row['end_time']) . ' น.';
            } else {
                if ($row['end_time'] == 2145891600) {
                    $row['time_range'] = thdate('d-M-Y H:i', $row['start_time']) . ' น. เป็นต้นไป';
                } else {
                    $row['time_range'] = thdate('d-M-Y H:i', $row['start_time']) . ' น. ถึง ' . thdate('d-M-Y H:i', $row['end_time']) . ' น.';
                }
            }
            $row['act_type'] = $act_type_options[$row['at_id']];
            $row['send_type'] = $send_type_options[$row['st_id']];
            $send_course_act_data = $this->get_send_act_data($row['ca_id']);

            if ($send_course_act_data['send_time'] != '') {
                $row['send_time_text'] = thdate('d-M-Y H:i', $send_course_act_data['send_time']);
                $row['get_score'] = $send_course_act_data['get_score'];
            } else {
                if ($row['st_id'] == 5) {
                    $row['send_time_text'] = '-';
                } else {
                    $row['send_time_text'] = '<span class="error">ยังไม่ได้ส่ง</span>';
                }

                $row['get_score'] = '-';
            }

            $sum_get_score += $row['get_score'];
            $sum_full_score +=$row['full_score'];

            $data['rows'][] = array(
                'id' => $row['ca_id'],
                'cell' => $row
            );
        }


        //summary row
        if ($get_summary) {
            foreach ($this->db->field_data('s_course_act') as $field) {
                $r[$field->name] = '';
            }
            $r['start_time_text'] = '';
            $r['end_time_text'] = '';
            $r['time_range'] = '';
            $r['act_date'] = '';
            $r['act_type'] = '';
            $r['send_type'] = '';
            $r['get_score'] = $sum_get_score;
            $r['full_score'] = $sum_full_score;
            $r['action'] = '';
            $r['send_time_text'] = 'รวม';
            $r['full_score_text'] = $sum_full_score;
            $data['rows'][] = array(
                'id' => $r['ca_id'],
                'cell' => $r
            );
        }
        //end summary row
        return $data;
    }

    function where_find_all_my_course_act($table_name, $qtype, $query, $c_id) {
        $this->db->order_by('start_time');
        $this->db->order_by('end_time');
        $this->db->where('c_id', $c_id);

        //$this->db->where('uid_owner', $this->auth->uid());
        switch ($qtype) {
            case 'custom':
                foreach ($query as $k => $v) {
                    if ($v != '') {
                        switch ($k) {
                            case 'c_id':
                                $this->db->like($k, $v, 'after');
                                break;
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

    /**
     * สมัครหลักสูตรแบบปกติ
     * @param type $c_id
     * @param type $uid
     * @return boolean
     */
    function enroll($c_id, $uid = '') {
        $course_data = $this->get_course_data($c_id);
        if ($uid == '') {
            $uid = $this->auth->uid();
        }
        if ($this->have_enroll($c_id)) {
            return FALSE;
        }
        $this->db->set('c_id', $c_id);
        $this->db->set('uid_student', $uid);
        $this->db->set('enroll_time', $this->time);
        $this->db->set('enroll_type_id', $course_data['enroll_type_id']);
        $this->db->set('uid_owner', $course_data['uid_owner']);
        switch ($course_data['enroll_type_id']) {
            case 1://เข้าทันที
            case 3://รหัสผ่าน
                $this->db->set('active', 1);
                break;
            case 2://รออนุมัติ
                $this->db->set('active', 0);
                break;
            default:
                break;
        }
        $this->db->insert('s_course_enroll');
        return TRUE;
    }

    /**
     * สมัครหลักสูตรแบบใช้รหัสผ่าน
     * @param type $post
     * @return string
     */
    function enroll_password($post) {
        $course_data = $this->get_course_data($post['c_id']);
        $result = array(
            'success' => FALSE,
            'message' => 'ไม่สามารถบันทึกข้อมูลได้'
        );

        $uid = $this->auth->uid();
        $this->db->where('uid_student', $uid);
        $this->db->where('c_id', $post['c_id']);
        if ($this->db->count_all_results('s_course_enroll') > 0) {
            $result = array(
                'success' => FALSE,
                'message' => 'ไม่สามารถบันทึกข้อมูลได้เนื่องจากท่านได้สมัครหลักสูตรนี้ไปแล้ว'
            );
        }
        //check password
        $this->db->where('c_id', $post['c_id']);
        $q = $this->db->get('s_course');
        $r = $q->row_array();
        if ($r['enroll_password'] == $post['enroll_password']) {
            $this->db->set('c_id', $post['c_id']);
            $this->db->set('uid_student', $uid);
            $this->db->set('enroll_time', $this->time);
            $this->db->set('enroll_type_id', 3);
            $this->db->set('active', 1);
            $this->db->set('uid_owner', $course_data['uid_owner']);
            $this->db->insert('s_course_enroll');
            $result = array(
                'success' => TRUE,
                'message' => 'ท่านสามารถเข้าเรียนหลักสูตรได้ทันที'
            );
        } else {
            $result = array(
                'success' => FALSE,
                'message' => 'ไม่สามารถบันทึกข้อมูลได้เนื่องจากข้อมูลรหัสผ่านผิด'
            );
        }
        return $result;
    }

    /**
     * ยกเลิกการสมัครหลักสูตร คะแนนทุกอย่างหายหมด
     * @param type $c_id
     * @param type $uid
     * @return boolean
     */
    function delete_enroll($c_id, $uid = '') {
        if ($uid == '') {
            $uid = $this->auth->uid();
        }
        $send_act_upload_dir = $this->config->item('send_act_upload_dir');
        $this->db->where('c_id', $c_id);
        $this->db->where('uid_sender', $uid);
        $q = $this->db->get('s_course_act_send');
        //ลบไฟล์การส่งงานของตนเอง
        foreach ($q->result_array() as $row) {
            if ($row['st_id'] == 1) {//ถ้าเป็นการ upload
                @unlink($send_act_upload_dir . $row['data']);
            }
        }
        //ลบข้อมูลการส่งงานของตนเอง
        $this->db->where('c_id', $c_id);
        $this->db->where('uid_sender', $uid);
        $this->db->delete('s_course_act_send');
        //ลบข้อมูลการส่งงานแบบออนไลน์ของตนเอง
        $this->db->where('c_id', $c_id);
        $this->db->where('uid', $uid);
        $this->db->delete('s_course_act_asheet');
        //ลบการสมัครของตนเอง
        $this->db->where('c_id', $c_id);
        $this->db->where('uid_student', $uid);
        $this->db->delete('s_course_enroll');
// การทำข้อสอบ ต่างๆ
        // course act send asheet ซ้อมสอบ
//        $this->db->where('uid', $uid);
//        $this->db->where('c_id', $c_id);
//        $this->db->delete('s_course_act_asheet_practice');
        // course act send asheet พรีเทส โพสเทส
//        $this->db->where('uid', $uid);
//        $this->db->where('c_id', $c_id);
//        $this->db->delete('s_course_act_asheet_ptest');

        return TRUE;
    }

    /**
     * ดึงข้อมูลงาน 1 งาน
     * @param type $ca_id
     * @return string
     */
    function get_course_act_data($ca_id = '') {
        $send_type_options = $this->CI->ddoption_model->get_send_type_options();
        if ($ca_id == '') {
            $fields = $this->db->field_data('s_course_act');
            $row = array();

            foreach ($fields as $field) {
                $row[$field->name] = $field->default;
            }
            $row['start_time_d'] = date('d/m/Y');
            $row['start_time_h'] = date('H:i');
            $row['end_time_d'] = '';
            $row['end_time_h'] = '';
            $row['st_id'] = 3;
            $row['create_time'] = $this->time;
            $row['have_preposttest'] = 1;
        } else {
            $this->db->where('ca_id', $ca_id);
            $q = $this->db->get('s_course_act');

            $row = $q->row_array();

            if ($row['st_id'] == 0) {
                $row['start_time_d'] = '';
                $row['start_time_h'] = '';
                $row['end_time_d'] = '';
                $row['end_time_h'] = '';
                $row['deadline'] = '-';
            } else {
                $row['start_time_d'] = date('d/m/Y', $row['start_time']);
                $row['start_time_h'] = date('H:i', $row['start_time']);
                $row['end_time_d'] = date('d/m/Y', $row['end_time']);
                $row['end_time_h'] = date('H:i', $row['end_time']);
                $row['deadline'] = thdate('d-M-Y H:i', $row['start_time']) . ' ถึง ' . thdate('d-M-Y H:i', $row['end_time']);
            }
        }
        $row['create_time_text'] = thdate('d-M-Y H:i', $row['create_time']);
        $row['send_type'] = $send_type_options[$row['st_id']];
        return $row;
    }

    /**
     * บันทึกการส่งงาน
     * @param type $data
     * @return boolean
     */
    function save_send_course_act($data) {
        $act_data = $this->get_act_data($data['ca_id']);
        if ($this->is_send_act($this->auth->uid(), $data['ca_id'])) {
            $this->db->set('data', $data['data']);
            $this->db->set('update_time', $this->time);

            $this->db->where('uid_sender', $this->auth->uid());
            $this->db->where('ca_id', $data['ca_id']);
            $this->db->update('s_course_act_send');
        } else {
            $this->db->set('data', $data['data']);
            $this->db->set('send_time', $this->time);
            $this->db->set('update_time', $this->time);
            $this->db->set('uid_sender', $this->auth->uid());
            $this->db->set('ca_id', $data['ca_id']);
            $this->db->set('c_id', $data['c_id']);
            $this->db->set('st_id', $data['st_id']);
            $this->db->set('full_score', $act_data['full_score']);
            $this->db->insert('s_course_act_send');
        }
        return TRUE;
    }

    /**
     * ดึงข้อมูลการส่งงานของนักเรียน
     * @param type $ca_id
     * @param type $uid
     * @return type
     */
    function get_send_act_data($ca_id, $uid_sender = '') {
        if ($uid_sender == '') {
            $uid_sender = $this->auth->uid();
        }
        $this->db->where('ca_id', $ca_id);
        $this->db->where('uid_sender', $uid_sender);
        $q = $this->db->get('s_course_act_send');
        if ($q->num_rows() > 0) {
            $row = $q->row_array();
        } else {
            $data = $this->db->field_data('s_course_act_send');
            foreach ($data as $field) {
                $row[$field->name] = $field->default;
            }
        }
        if ($row['give_score_time'] == 0) {
            $row['get_score'] = '';
            $row['send_time_d'] = date('d/m/Y', $this->time);
            $row['send_time_h'] = date('H:i', $this->time);
        } else {
            $row['send_time_d'] = date('d/m/Y', $row['send_time']);
            $row['send_time_h'] = date('H:i', $row['send_time']);
        }

        return $row;
    }

    function get_all_my_course_act_data($c_id) {
        $data = $a = $this->course_model->find_all_my_course_act(1, '', '', 1000, 'start_time', 'asc', $c_id, FALSE);

        $result = array();
        foreach ($data['rows'] as $row) {
            $result[$row['cell']['at_id']][] = $row['cell'];
        }
        return $result;
    }

//================================================================================================================    
// Extra
//================================================================================================================  
    /**
     * ดึงข้อมูลจำนวนนักเรียนในหลักสูตร
     * @param type $c_id
     * @return type
     */
    function get_count_student_in_course($c_id) {
        $this->db->where('c_id', $c_id);
        return $this->db->count_all_results('s_course_enroll');
    }

    /**
     * เช็คว่ามีการสมัครหลักสูตรหรือยัง
     * @param type $c_id
     * @param type $uid
     * @return boolean
     */
    function have_enroll($c_id, $uid = '') {
        if ($uid == '') {
            $uid = $this->auth->uid();
        }

        $this->db->where('uid_student', $uid);
        $this->db->where('c_id', $c_id);
        if ($this->db->count_all_results('s_course_enroll') > 0) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * ดึงข้อมูล course ที่กำลังเปิดสำหรับครูแต่ละคน 
     */
    function find_all_course_open($page, $qtype, $query, $rp, $sortname, $sortorder, $uid_owner = '', $hide_for_enroll = FALSE, $publish = 1) {
        $degree_options = $this->CI->ddoption_model->get_degree_id_options();
        $degree_options[0] = '';
        //$this->update_course_owner_detail();
        $rid = $this->auth->get_rid();

        if ($sortname == 'title_play') {
            $sortname = 'title';
        }
        if ($qtype == 'custom') {
            parse_str($query, $query);
        }


        //init search cond
        //initial data        
        $data = array(
            'rows' => array(),
            'page' => $page,
            'total' => 0
        );
        //make offset
        $offset = (($page - 1) * $rp);
        // Start Sql Query State for count row
        $this->where_find_all_course_open('s_course', $qtype, $query, $uid_owner, $publish);
        // END Sql Query State
        $total = $this->db->count_all_results();
        // Start Sql Query State
        $this->db->select('s_course.*');
        $this->where_find_all_course_open('s_course', $qtype, $query, $uid_owner, $publish);
        // END Sql Query State
        $this->db->limit($rp, $offset);
        $this->db->order_by($sortname, $sortorder);
        $this->db->order_by('title', 'asc');
        $result = $this->db->get();
        $data['total'] = $total;

        foreach ($result->result_array() as $row) {
            $row['action'] = '';
            if ($hide_for_enroll) {
                if ($this->have_enroll($row['c_id'])) {
                    continue;
                }
            }
            $student_count = $this->get_count_student_in_course($row['c_id']);
            if ($rid == 2) {


                if ($row['enroll_limit'] > $student_count || $row['enroll_limit'] == 0) {
                    if (!$this->have_enroll($row['c_id'])) {

                        //$row['action'] .= '<a href="' . site_url('study/course/enroll/' . $row['c_id']) . '">สมัคร</a>';
                        $row['action'] .= '<a href="' . site_url('study/course/view_course_details/' . $row['c_id']) . '">เปิดดู</a>';
                    } else {
                        $row['action'] .= '<span class="error">สมัครไปแล้ว</span>';
                    }
                } else {
                    $row['action'] .= '<span class="error">เต็ม</span>';
                }
            } else if ($rid == 3) {
                $row['action'] .= '<a href="' . site_url('study/course_manager/view_course_details/' . $row['c_id']) . '">เปิดดู</a>';
            }
            $row['start_time'] = thdate('d-M-Y', $row['start_time']);
            $row['end_time'] = thdate('d-M-Y', $row['end_time']);
            $row['enroll_limit_text'] = $row['enroll_limit'];
            if ($row['enroll_limit'] == 0) {
                $row['enroll_limit_text'] = 'ไม่จำกัด';
            }
            $row['degree_text'] = $degree_options[$row['degree_id']];
            $data['rows'][] = array(
                'id' => $row['c_id'],
                'cell' => $row
            );
        }
        return $data;
    }

    function where_find_all_course_open($table_name, $qtype, $query, $uid_owner = '', $publish = 1) {
        if ($publish != 2) {
            $this->db->where('publish', $publish);
        }

        $this->db->where('end_time >', $this->time);
        $this->db->where('start_time <', $this->time);

        if ($uid_owner != '') {
            $this->db->where('uid_owner', $uid_owner);
        }

        switch ($qtype) {
            case 'custom':
                foreach ($query as $k => $v) {
                    if ($v != '') {
                        switch ($k) {
                            case 'c_id':
                                $this->db->like($k, $v, 'after');
                                break;
                            case 'title':
                                $this->db->like($k, $v);
                                break;
                            case 'desc':
                                $this->db->like($k, $v);
                                break;
                            case 'subject_title':
                                $this->db->like($k, $v);
                                break;
                            case 'full_name_owner':
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

    function get_user_detail($uid) {
        $this->db->where('uid', $uid);
        $q = $this->db->get('u_user_detail');
        if ($q->num_rows() > 0) {
            $row = $q->row_array();
            $row['full_name'] = $row['first_name'] . ' ' . $row['last_name'];
        } else {

            $row['full_name'] = '** ผู้ใช้ถูกลบ **';
        }
        return $row;
    }

    function is_send_act($uid, $ca_id) {
        $this->db->where('uid_sender', $uid);
        $this->db->where('ca_id', $ca_id);
        $q = $this->db->get('s_course_act_send');
        if ($q->num_rows() > 0) {
            return TRUE;
        }
        return FALSE;
    }

    function get_give_score_options($start, $limit) {
        $range = range($start, $limit);
        $options = array('' => '');
        foreach ($range as $v) {
            $options[$v] = $v;
        }
        return $options;
    }

    function pretest_has_done($ca_id, $uid = '') {
        if ($uid == '') {
            $uid = $this->auth->uid();
        }
        $this->db->where('uid', $uid);
        $this->db->where('ca_id', $ca_id);
        $this->db->where('pre_send_time > ', 0);
        if ($this->db->count_all_results('s_course_act_asheet_ptest') > 0) {
            return TRUE;
        }
        return FALSE;
    }

    function posttest_has_done($ca_id, $uid = '') {
        if ($uid == '') {
            $uid = $this->auth->uid();
        }
        $this->db->where('uid', $uid);
        $this->db->where('ca_id', $ca_id);
        $this->db->where('post_send_time > ', 0);
        if ($this->db->count_all_results('s_course_act_asheet_ptest') > 0) {
            return TRUE;
        }
        return FALSE;
    }

    function get_course_act_send_data($ca_id, $uid_sender = '') {
        if ($uid_sender == '') {
            $uid_sender = $this->auth->uid();
        }
        $this->db->where('ca_id', $ca_id);
        $this->db->where('uid_sender', $uid_sender);
        $q = $this->db->get('s_course_act_send');
        if ($q->num_rows() > 0) {
            return $q->row_array();
        } else {
            return FALSE;
        }
    }

    function get_course_act_send_data_by_id($cas_id) {

        $this->db->where('cas_id', $cas_id);

        $q = $this->db->get('s_course_act_send');
        if ($q->num_rows() > 0) {
            return $q->row_array();
        } else {
            return FALSE;
        }
    }

    function get_course_act_options($c_id) {
        $options = array(
        );
        $this->db->where('c_id', $c_id);
        $query = $this->db->get('s_course_act');
        foreach ($query->result_array() as $v) {
            $options[$v['ca_id']] = $v['title'];
        }
        return $options;
    }

    function get_send_type_array() {
        $send_type_options = array();
        $this->db->order_by('weight', 'asc');
        $q_send_type = $this->db->get('s_send_type');
        foreach ($q_send_type->result_array() as $v) {
            $send_type_options[$v['st_id']] = $v['send_type_title'];
        }
        return $send_type_options;
    }

    function get_act_data($ca_id) {
        $this->db->where('ca_id', $ca_id);
        $q = $this->db->get('s_course_act');
        $row = $q->row_array();
        $row['can_send'] = TRUE;
        if ($this->time > $row['end_time']) {
            $row['can_send'] = FALSE;
        }
        return $row;
    }

    function can_edit_course_act($ca_id) {

        $course_data = $this->get_act_data($ca_id);
//        if ($course_data['start_time'] < $this->time) {
//            return FALSE;
//        }

        $this->db->where('ca_id', $ca_id);
        if ($this->db->count_all_results('s_course_act_asheet') > 0) {
            return FALSE;
        }
        $this->db->where('ca_id', $ca_id);
        if ($this->db->count_all_results('s_course_act_asheet_practice') > 0) {
            return FALSE;
        }
        $this->db->where('ca_id', $ca_id);
        if ($this->db->count_all_results('s_course_act_asheet_ptest') > 0) {
            return FALSE;
        }
        $this->db->where('ca_id', $ca_id);
        if ($this->db->count_all_results('s_course_act_send') > 0) {
            return FALSE;
        }
        return TRUE;
    }

    function can_edit_course_act_start_time($ca_id) {
        $this->db->where('ca_id', $ca_id);
        if ($this->db->count_all_results('s_course_act_send') > 0) {
            return FALSE;
        }
        return TRUE;
    }

    function practice_num_rows($ca_id) {
        $this->db->where('ca_id', $ca_id);
        return $this->db->count_all_results('s_course_act_asheet_practice');
    }

    function ptest_num_rows($ca_id) {
        $this->db->where('ca_id', $ca_id);
        return $this->db->count_all_results('s_course_act_asheet_ptest');
    }

    function get_ptest_data($ca_id, $uid = '') {

        if ($uid == '') {
            $uid = $this->auth->uid();
        }
        $this->db->where('uid', $uid);
        $this->db->where('ca_id', $ca_id);
        $q = $this->db->get('s_course_act_asheet_ptest');
        if ($q->num_rows() > 0) {
            return $q->row_array();
        }
        return FALSE;
    }

//    function get_caaspt_id($ca_id, $uid = '') {
//        if ($uid == '') {
//            $uid = $this->auth->uid();
//        }
//        $this->db->where('ca_id', $ca_id);
//        $this->db->where('pre_send_time > ', 0);
//        $this->db->where('uid', $uid);
//        $q = $this->db->get('s_course_act_asheet_ptest');
//        if ($q->num_rows() > 0) {
//            return $q->row()->caaspt_id;
//        }
//        return FALSE;
//    }
}
