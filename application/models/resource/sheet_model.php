<?php

/**
 * Description of sheet_model
 *
 * @author lojorider
 * @property resource_codec $resource_codec
 * @property dycontent_model $dycontent_model
 */
class sheet_model extends CI_Model {

    var $CI;
    var $resource_type_id = 5;

    function __construct() {
        parent::__construct();
        $this->CI = & get_instance();
        $this->CI->load->model('core/ddoption_model');
        $this->time = time();
        $this->load->helper('tag');
        $this->load->library('resource_codec');
    }

    public function find_all($page, $qtype, $query, $rp, $sortname, $sortorder) {
        if($sortname=='title_play'){
            $sortname = 'title';
        }
        $publish_options = $this->CI->ddoption_model->get_publish_options();
        $privacy_options = $this->CI->ddoption_model->get_privacy_options();
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
        $this->find_all_where('r_resource_sheet', $qtype, $query);
        // END Sql Query State
        $total = $this->db->count_all_results();
        // Start Sql Query State
        $this->db->select('r_resource_sheet.*');


        $this->db->select('r_resource.title');
        $this->db->select('r_resource.desc');

        $this->db->select('r_resource.publish');
        $this->db->select('r_resource.privacy');
        $this->db->select('r_resource.subject_title');
        $this->db->select('r_resource.chapter_title');
        $this->db->select('r_resource.tags');



        $this->find_all_where('r_resource_sheet', $qtype, $query);
        // END Sql Query State
        $this->db->limit($rp, $offset);
        $this->db->order_by($sortname, $sortorder);
        $result = $this->db->get();
        $data['total'] = $total;

        foreach ($result->result_array() as $row) {
            if ($row['resource_id_set'] == '') {
                $this->updata_resource_set($row['resource_id'], $row['data']);
            }
            $row['checkbox'] = '<input type = "checkbox" name = "cb_resource_id[]" value = "' . $row['resource_id'] . '" />';
            $row['count_sheet_video'] = $this->count_sheet_video($row['resource_id_set']);
//            $row['action'] = '<a href="' . site_url('v/' . $row['resource_id']) . '" target="_blank">เปิด</a>';
            $row['action'] = '<a href="' . site_url('play/play_resource/pdf_sheet/' . $row['resource_id']) . '" >ดาวน์โหลด</a>';
            $row['action'] .= '<a href="' . site_url('resource/sheet/edit/' . $row['resource_id']) . '">แก้ไข</a>';
            $row['action'] .= '<a href="' . site_url('resource/sheet/delete/' . $row['resource_id']) . '">ลบ</a>';
            $row['publish'] = $publish_options[$row['publish']];
            $row['privacy'] = $privacy_options[$row['privacy']];
            $row['title'] = '<span title="' . $row['title'] . "\n" . $row['desc'] . '">' . $row['title'] . '</span>';
            //$row['title_play'] = '<a href="' . site_url('v/' . $row['resource_id']) . '" target="_blank">▶</a>' . $row['title'];
            $row['title_play'] = '<a href="' . site_url('v/' . $row['resource_id']) . '" target="_blank">เปิดดู</a>' . $row['title'];
            $data['rows'][] = array(
                'id' => $row['resource_id'],
                'cell' => $row
            );
        }
            

        return $data;
    }

    function updata_resource_set($resource_id, $data) {
        $data = json_decode($data, TRUE);
        $a_resource_id = array();

        $sheet_set = $data['sheet_set'];
        foreach ($sheet_set as $k => $v) {
            if (isset($v['resource_id'])) {
                $a_resource_id[] = $v['resource_id'];
            }
        }
        $this->db->set('resource_id_set', implode(',', $a_resource_id));
        $this->db->where('resource_id', $resource_id);
        $this->db->update('r_resource_sheet');
    }

    function count_sheet_video($resource_id_set) {
        if (!is_array($resource_id_set)) {
            $resource_id_set = explode(',', $resource_id_set);
        }
        $count_video = 0;
        if (count($resource_id_set) > 0) {
            $this->db->where_in('resource_id', $resource_id_set);
            $count_video = $this->db->count_all_results('r_resource_video_join');
        }
        return $count_video;
    }

    public function iframe_find_all($page, $qtype, $query, $rp, $sortname, $sortorder) {
        $publish_options = $this->CI->ddoption_model->get_publish_options();
        $privacy_options = $this->CI->ddoption_model->get_privacy_options();
        if ($qtype == 'custom') {
            parse_str($query, $query);
        }
        $user_data = $this->auth->get_user_data();
        $uid = $this->auth->uid();
        $owner_full_name = $user_data['full_name'];
        if (isset($query['owner_full_name'])) {
            if ($query['owner_full_name'] != '') {
                $this->db->where('full_name', $query['owner_full_name']);
                $this->db->where('dycontent_count >', 0);
                $this->db->limit(1);
                $q = $this->db->get('u_user_detail');
                if ($q->num_rows() > 0) {
                    $uid = $q->row()->uid;
                    $owner_full_name = $q->row()->full_name;
                }
            }
        }
        $data = array(
            'rows' => array(),
            'page' => $page,
            'total' => 0
        );
        //make offset
        $offset = (($page - 1) * $rp);
        // Start Sql Query State for count row
        $this->find_all_where('r_resource_sheet', $qtype, $query, $uid);
        // END Sql Query State
        $total = $this->db->count_all_results();
        // Start Sql Query State
        $this->db->select('r_resource_sheet.*');


        $this->db->select('r_resource.title');
        $this->db->select('r_resource.uid_owner');

        $this->db->select('r_resource.publish');
        $this->db->select('r_resource.privacy');
        $this->db->select('r_resource.subject_title');
        $this->db->select('r_resource.chapter_title');
        $this->db->select('r_resource.tags');




        $this->find_all_where('r_resource_sheet', $qtype, $query, $uid);
        // END Sql Query State
        $this->db->limit($rp, $offset);
        $this->db->order_by($sortname, $sortorder);
        $result = $this->db->get();
        $data['total'] = $total;

        $uid = $this->auth->uid();
        foreach ($result->result_array() as $row) {
            $row['checkbox'] = '<input type = "checkbox" name = "cb_resource_id[]" value = "' . $row['resource_id'] . '" />';
            $row['count_sheet_video'] = $this->count_sheet_video($row['resource_id_set']);
            //$row['action'] = '<a href=\'javascript:insert_resource_id("' . $row['resource_id'] . '")\'>เลือกใช้</a>';
            $row['action'] = '<a href="' . site_url('v/' . $row['resource_id']) . '" target="_blank">เปิดดู</a>';
            $row['action'] .= '<a href="' . site_url('play/play_resource/pdf_sheet/' . $row['resource_id']) . '" target="_blank">ดาวน์โหลด</a>';

            if ($row['uid_owner'] == $uid) {
                $row['action'] .= '<a href="' . site_url('resource/sheet/edit/' . $row['resource_id']) . '" target="_blank">แก้ไข</a>';
            }
//              $row['action'] .= '<a href="' . site_url('v/' . $row['resource_id']) . '" target="_blank">เปิด</a>';
            $row['select_action'] = '<a href=\'javascript:insert_resource_id(' . $row['resource_id'] . ')\'>เลือกใบงานนี้</a>';
            $row['title_action'] = '<a href=\'javascript:insert_resource_id(' . $row['resource_id'] . ')\'>' . $row['title'] . '</a>';

            $row['publish'] = $publish_options[$row['publish']];
            $row['privacy'] = $privacy_options[$row['privacy']];
            $data['rows'][] = array(
                'id' => $row['resource_id'],
                'cell' => $row
            );
        }


        return $data;
    }

    private function find_all_where($table_name, $qtype, $query, $uid = '') {

        if ($uid == '') {
            $this->db->where('r_resource.uid_owner', $this->auth->uid());
        } elseif ($uid == $this->auth->uid()) {
            $this->db->where('r_resource.uid_owner', $uid);
        } else {
            $this->db->where('privacy', 1);
            $this->db->where('publish', 1);
            $this->db->where('r_resource.uid_owner', $uid);
        }



        $this->db->where('r_resource.resource_id=r_resource_sheet.resource_id', NULL, FALSE);
        $this->db->from('r_resource');


        switch ($qtype) {
            case 'custom':
                foreach ($query as $k => $v) {
                    if ($v != '') {
                        switch ($k) {
                            case 'resource_id':
                                $this->db->like('r_resource.' . $k, $v, 'after');
                                break;
                            case 'title':
                            case 'desc':
                            case 'tags':
                            case 'subject_title':
                            case 'chapter_title':
                                $this->db->like('r_resource.' . $k, $v);
                                break;
                            case 'owner_full_name':

                                break;
                            default:
                                $this->db->where('r_resource.' . $k, $v);
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

    function get_resource_data($resource_id = '') {
        $row = array();
        if ($resource_id == '') {
            //if ($resource_id == '') {
//ตารางแม่
            foreach ($this->db->field_data('r_resource') as $v) {
                $row[$v->name] = $v->default;
            }
//ตารางลูก
            foreach ($this->db->field_data('r_resource_sheet') as $v) {
                $row[$v->name] = $v->default;
            }
            $row['data'] = $this->resource_codec->sheet_decode();
        } else {
//ตารางแม่            
            $this->db->where('resource_id', $resource_id);
            $query1 = $this->db->get('r_resource');
            if ($query1->num_rows() == 0) {
                return FALSE;
            }
            foreach ($query1->row_array() as $k => $v) {
                $row[$k] = $v;
            }
//ตารางลูก            
            $this->db->where('resource_id', $resource_id);
            $query2 = $this->db->get('r_resource_sheet');
            foreach ($query2->row_array() as $k => $v) {
                $row[$k] = $v;
            }
//            if(!isset($row['data'])){
//                echo $resource_id;
//            }
            $row['data'] = $this->resource_codec->sheet_decode($row['data']);
        }
        $row['section_score'] = json_decode($row['section_score'], TRUE);
        return $row;
    }

    //SAVE
    function save($data) {
        $subject_title = $this->get_subject_title($data['subj_id']);
        $chapter_title = $this->get_chapter_title($data['chapter_id']);
        $this->db->trans_start();
        $set = array(
            'title' => $data['title'],
            'desc' => $data['desc'],
            'update_time' => $this->time,
            'tags' => encode_tags($data['tags']),
            'publish' => $data['publish'],
            'privacy' => $data['privacy'],
            //new field
            'degree_id' => $data['degree_id'],
            'la_id' => $data['la_id'],
            'subj_id' => $data['subj_id'],
            'subject_title' => $subject_title,
            'chapter_id' => $data['chapter_id'],
            'chapter_title' => $chapter_title,
            'sub_chapter_title' => $data['sub_chapter_title']
        );
        $this->db->set($set);
        if ($data['resource_id'] == '') {
            $this->db->set('resource_type_id', $this->resource_type_id);
            $this->db->set('uid_owner', $this->auth->uid());
            $this->db->set('create_time', $this->time);
            $this->db->insert('r_resource');
            $new_resource_id = $this->db->insert_id();
        } else {
            $this->db->where('resource_id', $data['resource_id']);
            $this->db->update('r_resource');
        }
        // tbl r_resource_sheet
        $resources_data = array();
        $this->load->model('resource/dycontent_model');
        $total_question = 0;
        $resource_id_set = array();
        foreach ($data['resources'] as $resource) {
            if (isset($resource['resource_id'])) {
                $resource_id_set[] = $resource['resource_id'];
                $this->dycontent_model->init_resource($resource['resource_id']);
                $tmp_resource_data = $this->dycontent_model->get_resource_data();
                if (isset($resource['score_pq'])) {
                    $resource['score_pq'] = (int) $resource['score_pq'];
                }
                $resources_data[] = array_merge($resource, array(
                    'content_type_id' => $tmp_resource_data['content_type_id'],
                    'num_questions' => $tmp_resource_data['num_questions']
                ));
                $total_question +=$tmp_resource_data['num_questions'];
            } else {
                $resources_data[] = $resource;
            }
        }
        $this->db->set('data', $this->resource_codec->sheet_encode($resources_data));
        $section_score = array();
        $total_full_score = 0;
        foreach ($data['section_score'] as $score) {
            $section_score[] = $score;
            $total_full_score +=$score['full_score'];
        }
        $this->db->set('total_question', $total_question);
        $this->db->set('section_score', json_encode($section_score));
        $this->db->set('total_full_score', $total_full_score);
        $this->db->set('explanation', $data['explanation']);
        $this->db->set('resource_id_set', implode(',', $resource_id_set));
        if ($data['resource_id'] == '') {
            $this->db->set('render_type_id', $data['render_type_id']);
            $this->db->set('uid_owner', $this->auth->uid());
            $this->db->set('resource_id', $new_resource_id);
            $this->db->insert('r_resource_sheet');
        } else {
            $this->db->where('resource_id', $data['resource_id']);
            $this->db->update('r_resource_sheet');
        }
        $this->db->trans_complete();
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

    function get_chapter_title($chapter_id) {
        $this->db->select('chapter_title');
        $this->db->where('chapter_id', $chapter_id);
        $q = $this->db->get('f_chapter');
        if ($q->num_rows() > 0) {
            return $q->row()->chapter_title;
        } else {
            return '';
        }
    }

    function is_owner($resource_id) {
        $this->db->where('resource_id', $resource_id);
        $this->db->where('uid_owner', $this->auth->uid());
        if ($this->db->count_all_results('r_resource_sheet') > 0) {
            return TRUE;
        }
        return FALSE;
    }

//    function get_dycontent_data($resource_id) {
//        $this->db->where('resource_id', $resource_id);
//        $q = $this->db->get('r_resource_dycontent');
//        if ($q->num_rows() > 0) {
//            $row = $q->row_array();
//            $row['data'] = $this->resource_codec->sheet_decode($row['data']);
//            return $row;
//        }
//        return FALSE;
//    }

    function publish($resource_id, $publish) {
        $this->db->set('publish', $publish);
        if (is_array($resource_id)) {
            $this->db->where_in('resource_id', $resource_id);
        } else {
            $this->db->where('resource_id', $resource_id);
        }
        $this->db->where('uid_owner', $this->auth->uid());
        $this->db->update('r_resource');
        return TRUE;
    }

    function privacy($resource_id, $publish) {
        $this->db->set('privacy', $publish);
        if (is_array($resource_id)) {
            $this->db->where_in('resource_id', $resource_id);
        } else {
            $this->db->where('resource_id', $resource_id);
        }
        $this->db->where('uid_owner', $this->auth->uid());
        $this->db->update('r_resource');
        return TRUE;
    }

    function delete($resource_id) {
        if (is_array($resource_id)) {
            $this->db->where_in('resource_id', $resource_id);
        } else {
            if ($resource_id == '') {
                return FALSE;
            }
            $this->db->where('resource_id', $resource_id);
        }
        $this->db->where('uid_owner', $this->auth->uid());
        $q = $this->db->get('r_resource_sheet');

        if ($q->num_rows() > 0) {
            $this->db->trans_start();
            foreach ($q->result_array() as $row) {

                $this->db->where('resource_id', $row['resource_id']);
                $this->db->where('uid_owner', $this->auth->uid());
                $this->db->delete('r_resource');

                $this->db->where('uid_owner', $this->auth->uid());
                $this->db->where('resource_id', $row['resource_id']);
                $this->db->delete('r_resource_sheet');
            }
            $this->db->trans_complete();
            return TRUE;
        }

        return FALSE;
    }

    function get_join_video_data($resource_id) {
        $this->db->where('resource_id', $resource_id);
        $q_resource_org = $this->db->get('r_resource');
        if ($q_resource_org->num_rows() > 0) {
            $row = $q_resource_org->row_array();
            $result = array(
                'is_first' => TRUE,
                'resource_id' => $resource_id,
                'resource_id_video' => FALSE
            );
            if ($row['resource_id_parent'] != 0) {
                $resource_id = $row['resource_id_parent'];
                $result['is_first'] = FALSE;
            }
            $this->db->where('resource_id', $resource_id);
            $q_video_join = $this->db->get('r_resource_video_join');
            if ($q_video_join->num_rows() > 0) {
                foreach ($q_video_join->result_array() as $video_join_row) {
                    $this->db->where('resource_id', $video_join_row['resource_id_video']);
                    $this->db->where('resource_type_id', 1);
                    $q_resource_video = $this->db->get('r_resource');
                    if ($q_resource_video->num_rows() > 0) {
                        $row_resource = $q_resource_video->row_array();
                        $result['resource_id_video'] = $row_resource['resource_id'];
                        break;
                    } else {
                        $this->db->where('resource_id_video', $video_join_row['resource_id_video']);
                        $this->db->delete('r_resource_video_join');
                    }
                }
                if ($result['resource_id_video'] == FALSE) {
                    return FALSE;
                } else {
                    return $result;
                }
            } else {
                return FALSE;
            }
        }
        return FALSE;
    }

//    function decode_data($data) {
//        return json_decode($data, TRUE);
//    }
}

