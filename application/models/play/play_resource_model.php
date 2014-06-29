<?php

class play_resource_model extends CI_Model {

    var $resource_data;
    var $full_video_dir;
    var $is_rtmp;
    var $playlist_option;

    public function __construct() {
        parent::__construct();
        $this->full_video_dir = $this->config->item('full_video_dir');
        $this->is_rtmp = $this->config->item('is_rtmp');
    }

    function init_resource($resource_id) {
        $row1 = $this->init_resource_data($resource_id);
        if (!$row1) {
            return FALSE;
        }

        switch ($row1['resource_type_id']) {
            case 1: //video
                $row2 = $this->get_resource_video_data($resource_id);
                //$row2['join_doc'] = $this->get_join_doc_data($resource_id);
                //$row2['have_join_content'] = $this->have_join_content($resource_id);

                break;
            case 2: //doc upload
                $row2 = $this->get_resource_doc_data($resource_id);
                break;
            case 3: // image
                $row2 = $this->get_resource_image_data($resource_id);

                break;
            case 4: // dycontent
                $row2 = $this->get_resource_dycontent_data($resource_id);
                break;
            case 5: // sheet
                $row2 = $this->get_resource_sheet_data($resource_id);
                break;

            case 6: // site video

                $row2 = $this->get_resource_video_parent_data($resource_id);

                //$row2['join_doc'] = $this->get_join_doc_data($resource_id);
                //$row2['have_join_content'] = $this->have_join_content($resource_id);
                break;
            case 7: // video prokru
                $row2 = $this->get_resource_video_data($resource_id);
                //$row2['join_doc'] = $this->get_join_doc_data($resource_id);
                //$row2['have_join_content'] = $this->have_join_content($resource_id);
                break;
            case 8: // video prokru
                $row2 = $this->get_resource_flash_media($resource_id);

                break;
            default:
                break;
        }

        $this->resource_data = array_merge($row1, $row2);
        return TRUE;
    }

    function get_resource_type_id() {
        return $this->resource_data['resource_type_id'];
    }

    function is_owner() {
        if ($this->auth->uid() == $this->resource_data['uid_owner']) {
            return TRUE;
        }
        return FALSE;
    }

    function is_free() {
        if ($this->resource_data['unit_price'] == 0) {
            return TRUE;
        }
        return FALSE;
    }

    function plus_view() {
        $this->db->set('views', 'views+1', FALSE);
        $this->db->where('resource_id', $this->resource_data['resource_id']);
        $this->db->update('r_resource');
    }

    function get_resource_id() {
        return $this->resource_data['resource_id'];
    }

    function get_videosite_id() {
        return $this->resource_data['videosite_id'];
    }

//    function get_join_doc() {
//        return $this->resource_data['join_doc'];
//    }


    function get_unit_price() {

        if ($this->resource_data['unit_price'] == -1) {
            return $this->config->item('standard_unit_price');
        }
        return $this->resource_data['unit_price'];
    }

    function get_title() {
        return $this->resource_data['title'];
    }

    function get_resource_code() {
        return $this->resource_data['resource_code'];
    }

    function get_desc() {
        return $this->resource_data['desc'];
    }

    function get_video_path() {
        $file_path = $this->resource_data['file_path'];
        $pathinfo = pathinfo($this->full_video_dir . $file_path);
        if ($this->is_rtmp) {
            switch ($pathinfo['extension']) {
                case 'mp4':
                    return 'mp4:' . $file_path;
                    break;
                case 'flv':
                    return 'flv:' . $file_path;
                    break;
                default:
                    return $file_path;
                    break;
            }
        } else {
            switch ($pathinfo['extension']) {
                case 'mp4':
                    return $file_path;
                    break;
                case 'flv':
                    return $file_path;
                    break;
                default:
                    return $file_path;
                    break;
            }
        }
    }

    function get_file_ext() {
        return $this->resource_data['file_ext'];
    }

    function get_file_path() {
        return $this->resource_data['file_path'];
    }

    function get_full_file_path() {
        return $this->config->item('full_doc_dir') . $this->resource_data['file_path'];
    }

    function get_resource_data() {
        return $this->resource_data;
    }

    function get_resource_id_parent($resource_id) {
        $this->db->where('resource_id', $resource_id);
        $q = $this->db->get('r_resource');
        if ($q->num_rows() > 0) {
            return $q->row()->resource_id_parent;
        }
        return FALSE;
    }

    private function init_resource_data($resource_id) {
        $this->db->where('resource_id', $resource_id);
        $this->db->from('r_resource');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row_array();
        }return FALSE;
    }

    private function get_resource_video_data($resource_id) {
        $this->db->where('resource_id', $resource_id);
        $q1 = $this->db->get('r_resource_video');
        if ($q1->num_rows() > 0) {
            $row = $q1->row_array();
            return $row;
        }
        return FALSE;
    }

    private function get_resource_video_parent_data($resource_id) {
        $this->db->where('resource_id', $resource_id);
        $q1 = $this->db->get('r_resource_video_parent');
        if ($q1->num_rows() > 0) {
            $row = $q1->row_array();
            return $row;
        }
        return FALSE;
    }

    private function get_resource_flash_media($resource_id) {
        $this->db->where('resource_id', $resource_id);
        $q1 = $this->db->get('r_resource_flash_media');
        if ($q1->num_rows() > 0) {
            $row = $q1->row_array();
            return $row;
        }
        return FALSE;
    }

    function set_playlist_option($playlist_option) {
        $this->playlist_option = $playlist_option;
    }

    function get_playlist_option() {
        return $this->playlist_option;
    }

    function get_resource_video_data_same_sub_taxonomy($resource_id) {

        $data = array(
            'total' => 0,
            'rows' => array(),
            'next' => array(),
            'resource_id' => array(),
            'playlist_value' => 0
        );


        $a_resource_id = array();
        $this->db->where("CONCAT(',',r_taxonomy.`data`,',') like '%,$resource_id,%'", NULL, FALSE);
        $this->db->limit(1);
        $q = $this->db->get('r_taxonomy');
        if ($q->num_rows() > 0) {
            // foreach ($q->result_array() as $row) {
            $row = $q->row_array();
            $data['playlist_value'] = $row['tid'];
            $row['data'] = trim($row['data'], ',');
            $tmp_resource_id = explode(',', $row['data']);
            $a_resource_id = array_merge($a_resource_id, $tmp_resource_id);
            // }
        }
        $to_next = FALSE;
        $have_next = FALSE;
        $a_resource_id = array_unique($a_resource_id);

        foreach ($a_resource_id as $v) {

            $r = $this->get_video_data($v);

            if ($r) {
                if ($r['publish'] == 0) {
                    continue;
                }
                $data['resource_id'][] = $v;
                if ($to_next && !$have_next) {
                    $data['next'] = array(
                        'resource_id' => $v,
                        'url' => site_url('v/' . $v),
                        'title' => $r['title'],
                        'desc' => $r['desc'],
                        'current' => FALSE,
                        'duration' => $r['duration']
                    );
                    $have_next = TRUE;
                }
                if ($v != $resource_id) {
                    $data['rows'][] = array(
                        'resource_id' => $v,
                        'url' => site_url('v/' . $v),
                        'title' => $r['title'],
                        'desc' => $r['desc'],
                        'current' => FALSE,
                        'duration' => $r['duration']
                    );
                    $data['total'] ++;
                } else {
                    $data['rows'][] = array(
                        'resource_id' => $v,
                        'url' => site_url('v/' . $v),
                        'title' => $r['title'],
                        'desc' => $r['desc'],
                        'current' => TRUE,
                        'duration' => $r['duration']
                    );
                    $to_next = TRUE;
                }
            }
        }

        return $data;
    }

    /**
     * ดึงข้อมูล video  ใน บทเดียวกัน (ชุดการเรียน)
     * @param type $tid
     * @param type $resource_id_current
     * @return type
     */
    function get_resource_video_data_in_sub_taxonomy($tid, $resource_id_current) {
        $this->db->where('tid', $tid);
        $this->db->where('data !=', '');
        $q = $this->db->get('r_taxonomy');

        if ($q->num_rows() > 0) {
            $data = array(
                'total' => 0,
                'rows' => array(),
                'next' => array(),
                'resource_id' => array()
            );
            $a_resource_id = array();
            foreach ($q->result_array() as $row) {
                $row['data'] = trim($row['data'], ',');
                $tmp_resource_id = explode(',', $row['data']);
                $a_resource_id = array_merge($a_resource_id, $tmp_resource_id);
            }


            $to_next = FALSE;
            $have_next = FALSE;

            foreach ($a_resource_id as $v) {

                $r = $this->get_video_data($v);

                if ($r) {
                    if ($r['publish'] == 0) {
                        continue;
                    }
                    $data['resource_id'][] = $v;
                    if ($to_next && !$have_next) {
                        $data['next'] = array(
                            'resource_id' => $v,
                            'url' => site_url('v/' . $v . '?pltid=' . $tid),
                            'title' => $r['title'],
                            'desc' => $r['desc'],
                            'current' => FALSE,
                            'duration' => $r['duration']
                        );
                        $have_next = TRUE;
                    }
                    if ($v != $resource_id_current) {
                        $data['rows'][] = array(
                            'resource_id' => $v,
                            'url' => site_url('v/' . $v . '?pltid=' . $tid),
                            'title' => $r['title'],
                            'desc' => $r['desc'],
                            'current' => FALSE,
                            'duration' => $r['duration']
                        );
                        $data['total'] ++;
                    } else {
                        $data['rows'][] = array(
                            'resource_id' => $v,
                            'url' => site_url('v/' . $v . '?pltid=' . $tid),
                            'title' => $r['title'],
                            'desc' => $r['desc'],
                            'current' => TRUE,
                            'duration' => $r['duration']
                        );
                        $to_next = TRUE;
                    }
                }
            }

            return $data;
        } else {

            return $this->get_resource_video_data_same_sub_taxonomy($resource_id_current);
        }
    }

    /**
     * ดึงข้อมูล video ในชีท ใบงาน
     * @param type $resource_id_sheet
     * @param type $resource_id_current
     * @return type
     */
    function get_resource_video_data_in_sheet($resource_id_sheet, $resource_id_current) {
        $this->db->where('resource_id', $resource_id_sheet);
        $this->db->where('resource_id_set !=', '');
        $q = $this->db->get('r_resource_sheet');
        //ถ้ามีข้อมูล resource_id_dycontent
        if ($q->num_rows() > 0) {
            $data = array(
                'total' => 0,
                'rows' => array(),
                'next' => array(),
                'resource_id' => array()
            );

            $a_resource_id_dycontent = explode(',', $q->row()->resource_id_set);
            $tmp_a_resource_id_video = array();
            foreach ($a_resource_id_dycontent as $resource_id_dycontent) {
                $resource_id_parent = $this->get_resource_id_parent($resource_id_dycontent);
                if ($resource_id_parent) {
                    if ($resource_id_parent > 0) {
                        $resource_id_dycontent = $resource_id_parent;
                    }
                }
                $this->db->where('resource_id', $resource_id_dycontent);
                $q_resource_join = $this->db->get('r_resource_video_join');
                if ($q_resource_join->num_rows() > 0) {

                    foreach ($q_resource_join->result_array() as $v) {
                        $tmp_a_resource_id_video[] = $v['resource_id_video'];
                    }
                }
            }
            $a_resource_id_video = array_unique($tmp_a_resource_id_video);
            if (count($a_resource_id_video) > 0) {
                $to_next = FALSE;
                foreach ($a_resource_id_video as $v) {
                    $r = $this->get_video_data($v);

                    if ($r) {
                        if ($r['publish'] == 0) {
                            continue;
                        }
                        $data['resource_id'][] = $v;

                        if ($to_next) {

                            $data['next'] = array(
                                'resource_id' => $v,
                                'url' => site_url('v/' . $v . '?plsheet=' . $resource_id_sheet),
                                'title' => $r['title'],
                                'desc' => $r['desc'],
                                'current' => FALSE,
                                'duration' => $r['duration']
                            );
                            $to_next = FALSE;
                        }
                        if ($resource_id_current != end($a_resource_id_video)) {
                            if ($resource_id_current == $v) {
                                $to_next = TRUE;
                            }
                        }
                        if ($v != $resource_id_current) {
                            $data['rows'][] = array(
                                'resource_id' => $v,
                                'url' => site_url('v/' . $v . '?plsheet=' . $resource_id_sheet),
                                'title' => $r['title'],
                                'desc' => $r['desc'],
                                'current' => FALSE,
                                'duration' => $r['duration']
                            );
                            $data['total'] ++;
                        } else {
                            $data['rows'][] = array(
                                'resource_id' => $v,
                                'url' => site_url('v/' . $v . '?plsheet=' . $resource_id_sheet),
                                'title' => $r['title'],
                                'desc' => $r['desc'],
                                'current' => TRUE,
                                'duration' => $r['duration']
                            );
                            $to_next = TRUE;
                        }
                    }
                }
            }

            return $data;
        } else {

            return $this->get_resource_video_data_same_sub_taxonomy($resource_id_current);
        }
    }

    /**
     * ดึงข้อมูล video ในชีท ใบงาน
     * @param type $resource_id_sheet
     * @param type $resource_id_current
     * @return type
     */
    function get_resource_video_data_in_join_video($resource_id, $resource_id_current) {
        $this->db->where('resource_id', $resource_id);

        $q = $this->db->get('r_resource_video_join');
        //ถ้ามีข้อมูล resource_id_dycontent
        if ($q->num_rows() > 0) {
            $data = array(
                'total' => 0,
                'rows' => array(),
                'next' => array(),
                'resource_id' => array()
            );

            $a_resource_id_dycontent = array($resource_id);
            $tmp_a_resource_id_video = array();
            foreach ($a_resource_id_dycontent as $resource_id_dycontent) {
                $resource_id_parent = $this->get_resource_id_parent($resource_id_dycontent);
                if ($resource_id_parent) {
                    if ($resource_id_parent > 0) {
                        $resource_id_dycontent = $resource_id_parent;
                    }
                }
                $this->db->where('resource_id', $resource_id_dycontent);
                $q_resource_join = $this->db->get('r_resource_video_join');
                if ($q_resource_join->num_rows() > 0) {

                    foreach ($q_resource_join->result_array() as $v) {
                        $tmp_a_resource_id_video[] = $v['resource_id_video'];
                    }
                }
            }
            $a_resource_id_video = array_unique($tmp_a_resource_id_video);
            if (count($a_resource_id_video) > 0) {
                $to_next = FALSE;
                foreach ($a_resource_id_video as $v) {
                    $r = $this->get_video_data($v);

                    if ($r) {
                        $data['resource_id'][] = $v;

                        if ($to_next) {

                            $data['next'] = array(
                                'resource_id' => $v,
                                'url' => site_url('v/' . $v . '?plrid=' . $resource_id),
                                'title' => $r['title'],
                                'desc' => $r['desc'],
                                'current' => FALSE,
                                'duration' => $r['duration']
                            );
                            $to_next = FALSE;
                        }
                        if ($resource_id_current != end($a_resource_id_video)) {
                            if ($resource_id_current == $v) {
                                $to_next = TRUE;
                            }
                        }
                        if ($v != $resource_id_current) {
                            $data['rows'][] = array(
                                'resource_id' => $v,
                                'url' => site_url('v/' . $v . '?plrid=' . $resource_id),
                                'title' => $r['title'],
                                'desc' => $r['desc'],
                                'current' => FALSE,
                                'duration' => $r['duration']
                            );
                            $data['total'] ++;
                        } else {
                            $data['rows'][] = array(
                                'resource_id' => $v,
                                'url' => site_url('v/' . $v . '?plrid=' . $resource_id),
                                'title' => $r['title'],
                                'desc' => $r['desc'],
                                'current' => TRUE,
                                'duration' => $r['duration']
                            );
                            $to_next = TRUE;
                        }
                    }
                }
            }
            return $data;
        } else {

            return $this->get_resource_video_data_same_sub_taxonomy($resource_id_current);
        }
    }

    function get_first_resource_id_video_in_sheet($resource_id_sheet) {
        $this->db->where('resource_id', $resource_id_sheet);
        $this->db->where('resource_id_set !=', '');
        $q = $this->db->get('r_resource_sheet');
        if ($q->num_rows() > 0) {
            $a_resource_id_dycontent = explode(',', $q->row()->resource_id_set);
            foreach ($a_resource_id_dycontent as $resource_id_dycontent) {
                $resource_id_parent = $this->get_resource_id_parent($resource_id_dycontent);
                if ($resource_id_parent) {
                    if ($resource_id_parent > 0) {
                        $resource_id_dycontent = $resource_id_parent;
                    }
                }
                //ดึงข้อมูลเชื่อม video
                $this->db->where('resource_id', $resource_id_dycontent);
                $q_resource_join = $this->db->get('r_resource_video_join');
                //วนลูปดึง video
                foreach ($q_resource_join->result_array() as $v) {
                    $r = $this->get_video_data($v['resource_id_video']);
                    if ($r) {
                        return $v['resource_id_video'];
                    }
                }
            }
        }
        return FALSE;
    }

    private function get_resource_sitevideo_data($resource_id) {
        $this->db->where('resource_id', $resource_id);
        $q1 = $this->db->get('r_resource_sitevideo');
        if ($q1->num_rows() > 0) {
            $row = $q1->row_array();
            return $row;
        }
        return FALSE;
    }

    function get_resource_doc_data($resource_id) {
        $this->db->where('r_resource_doc.resource_id', $resource_id);
        $q1 = $this->db->get('r_resource_doc');
        if ($q1->num_rows() > 0) {
            $row = $q1->row_array();
            return $row;
        }
        return FALSE;
    }

    function get_resource_image_data($resource_id) {
        $this->db->where('r_resource_image.resource_id', $resource_id);
        $q1 = $this->db->get('r_resource_image');
        if ($q1->num_rows() > 0) {
            $row = $q1->row_array();
            $row['full_file_path'] = $this->config->item('full_image_dir') . $row['file_path'];
            return $row;
        }
        return FALSE;
    }

    function get_all_resource_image_data() {

        $q1 = $this->db->get('r_resource_image');
        $full_image_dir = $this->config->item('full_image_dir');
        $result = array();
        if ($q1->num_rows() > 0) {
            foreach ($q1->result_array() as $v) {
                $v['full_file_path'] = $full_image_dir . $v['file_path'];
                $result[] = $v;
            }
            return $result;
        }
        return FALSE;
    }

    function get_resource_dycontent_data($resource_id) {
        $this->db->where('r_resource_dycontent.resource_id', $resource_id);
        $q1 = $this->db->get('r_resource_dycontent');
        if ($q1->num_rows() > 0) {
            $row = $q1->row_array();
            return $row;
        }
        return FALSE;
    }

    function get_resource_sheet_data($resource_id) {
        $this->db->where('r_resource_sheet.resource_id', $resource_id);
        $q1 = $this->db->get('r_resource_sheet');
        if ($q1->num_rows() > 0) {
            $row = $q1->row_array();
            return $row;
        }
        return FALSE;
    }

    function have_join_content($resource_id_video) {
        $this->db->where('resource_id_video', $resource_id_video);
        $q1 = $this->db->get('r_resource_video_join');
        if ($q1->num_rows() > 0) {
            return TRUE;
        }
        return FALSE;
    }

//    private function get_join_doc_data_bk($resource_id_video) {
//        $a_resource_id = array();
//        $this->db->where('resource_id_video', $resource_id_video);
//        $q1 = $this->db->get('r_resource_video_join');
//        if ($q1->num_rows() > 0) {
//            foreach ($q1->result_array() as $v) {
//                $a_resource_id = $v['resource_id'];
//            }
//            $this->db->select('r_resource.*');
//            $this->db->from('r_resource');
//            $this->db->where_in('r_resource.resource_id', $a_resource_id);
//
//            $this->db->select('r_resource_doc.file_path');
//            $this->db->from('r_resource_doc');
//            $this->db->where('r_resource.resource_id=r_resource_doc.resource_id', NULL, FALSE);
//            $q2 = $this->db->get();
//            return $q2->result_array();
//        }
//        return array();
//    }

    public function get_video_data($resource_id) {
        $sql = "SELECT r_resource.* ,r_resource_video.file_size_org,r_resource_video.file_size,r_resource_video.duration FROM(SELECT
*
FROM
r_resource
WHERE r_resource.`resource_id` = $resource_id)r_resource 
LEFT JOIN r_resource_video
ON r_resource.resource_id=r_resource_video.resource_id
";
        $q1 = $this->db->query($sql);
        if ($q1->num_rows() > 0) {
            return $q1->row_array();
        }
        return FALSE;
    }

    public function get_join_content_in_taxonomy($tid) {
        $a_resource_id_join = array();
        $a_resource_id_video = array();
        $this->db->where('tid', $tid);
        $q = $this->db->get('r_taxonomy');
        if ($q->num_rows() > 0) {

            $row = $q->row_array();
            $a_resource_id_video = explode(',', $row['data']);
            foreach ($a_resource_id_video as $resource_id) {

                $this->db->where('resource_id_video', $resource_id);
                $q_resource_join = $this->db->get('r_resource_video_join');
                foreach ($q_resource_join->result_array() as $row_resource_join) {
                    $a_resource_id_join[] = $row_resource_join['resource_id'];
                }
            }
        }
        return $a_resource_id_join;
    }

    public function get_join_content_in_video($resource_id) {
        $a_resource_id_join = array();
        $this->db->where('resource_id_video', $resource_id);
        $q_resource_join = $this->db->get('r_resource_video_join');
        foreach ($q_resource_join->result_array() as $row_resource_join) {
            $a_resource_id_join[] = $row_resource_join['resource_id'];
        }

        return $a_resource_id_join;
    }

}
