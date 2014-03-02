<?php

/**
 * Description of ztatic
 *
 * @author lojoriderrefresh
 * @property ztatic_model $ztatic_model
 * @property video_upload_model $video_upload_model
 * @property doc_manager_model $doc_manager_model 
 * @property disk_quota_service_model $disk_quota_service_model 
 * @property  video_upload_model $video_upload_model
 * @property MY_Email $email
 * @property  fb_model $fb_mode
 * @property xelatex_dycontent_separate_model $xelatex_dycontent_separate_model
 * @property course_model $course_model
 * @property xelatex_dycontent_merge_model $xelatex_dycontent_merge_model
 * @property play_resource_model $play_resource_model 
 */
class ztatic extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    function img_avatar_128($uid = '') {
        $image_file = $this->auth->get_avatar_filename($uid, 128);

        header('Content-Type: image/jpeg');
        readfile($image_file);
    }

    function img_avatar_64($uid = '') {
        $image_file = $this->auth->get_avatar_filename($uid, 64);
        header('Content-Type: image/jpeg');
        readfile($image_file);
    }

    function img_avatar_32($uid = '') {
        $image_file = $this->auth->get_avatar_filename($uid, 32);
//        echo $image_file;
//        if(!file_exists($image_file)){
//            echo 'NO';
//        }
        header('Content-Type: image/jpeg');
        readfile($image_file);
    }

    function resource_image($resource_id) {
        $this->load->model('play/play_resource_model');
        $image_data = $this->play_resource_model->get_resource_image_data($resource_id);
//        if (file_exists($image_data['full_file_path'])) {
        header('Content-Type: ' . $image_data['mime_type']);
        readfile($image_data['full_file_path']);
//        } else {
//            echo $image_data['full_file_path'];
//            $this->clone_vod_image($image_data['full_file_path']);
//        }
    }

//    function clone_img() {
//        $this->load->model('play/play_resource_model');
//        $images_data = $this->play_resource_model->get_all_resource_image_data();
//        foreach ($images_data as $r) {
//            $this->clone_vod_image($r['full_file_path']);
//        }
//    }
//
//    function clone_vod_image($output_file) {
//        $source = FCPATH . 'image_vod_clone/1/2/' . basename($output_file);
//        if (file_exists($source) && !file_exists($output_file)) {
//            echo 'have vod file';
//            copy($source, $output_file);
//        } else {
//            echo 'not have vod image';
//        }
//        //copy($source, $output_file)
//    }

    function personal_document($file_name) {
        $personal_document_dir = $this->config->item('personal_document_dir');
        $file_name = $personal_document_dir . $file_name;
        header('Content-Type: ' . mime_content_type($file_name));
        readfile($file_name);
    }

    function send_act_file($cas_id) {
        $this->load->model('study/course_model');
        $this->load->helper('download');
        $act_send_data = $this->course_model->get_course_act_send_data_by_id($cas_id);
        $send_act_upload_dir = $this->config->item('send_act_upload_dir');
        $filename = $send_act_upload_dir . $act_send_data['data'];
        $title = end(explode('/', $act_send_data['data']));
        force_download_file($title, $filename);
    }

    function download_join_content() {
        $this->load->model('play/play_resource_model');
        $plopt = $this->input->get('plopt');
        $plvalue = $this->input->get('plvalue');
        //exit;
        if ($plopt == 'plsheet') { //เอกสารเป็น sheet เท่านั้น
            exit('1');
            redirect('play/play_resource/pdf_sheet/' . $plvalue);
        } else if ($plopt == 'pltid' || $plopt == 'plrid') {//ชุดวิดีโอหน้าเว็บ อาจมีคละกันบ้าง
            $a_resource_id_join = array();

            if ($plopt == 'pltid') {
                $a_resource_id_join = $this->play_resource_model->get_join_content_in_taxonomy($plvalue);
            } else {
                $a_resource_id_join[] = $plvalue;
            }
            if (!$a_resource_id_join) {
                exit("DOSE NOT HAVE CONTENT");
            }



            $a_resource_id_doc = array();
            $a_resource_id_dycontent = array();
            foreach ($a_resource_id_join as $resource_id_join) {
                $this->db->where('resource_id', $resource_id_join);
                $q3 = $this->db->get('r_resource');
                foreach ($q3->result_array() as $v3) {

                    if ($v3['resource_type_id'] == 4) {
                        $a_resource_id_dycontent[] = $v3['resource_id'];
                    }
                    if ($v3['resource_type_id'] == 2) {
                        $a_resource_id_doc[] = $v3['resource_id'];
                    }
                }
            }
            $a_resource_id_dycontent = array_unique($a_resource_id_dycontent);
            $a_resource_id_doc = array_unique($a_resource_id_doc);
            $count_dycontent = count($a_resource_id_dycontent);
            $count_doc = count($a_resource_id_doc);
            //echo $count_dycontent . '|' . $count_doc;
            if ($count_dycontent > 0 && $count_doc > 0) {

                //exit("ALL");
                //มีทั้งสอง
                $this->load->library('zip');
                //Dycontent
                $this->load->model('resource//xelatex_dycontent_merge_model');
                $this->xelatex_dycontent_merge_model->init_content($a_resource_id_dycontent);
                $result_dycontent = $this->xelatex_dycontent_merge_model->render_pdf();
                $this->zip->read_file($result_dycontent['files'][0]);
                //Doc
                $full_doc_dir = $this->config->item('full_doc_dir');
                $this->db->where_in('resource_id', $a_resource_id_doc);
                $q_doc = $this->db->get('r_resource_doc');
                foreach ($q_doc->result_array() as $r_doc) {
                    $this->zip->read_file($full_doc_dir . $r_doc['file_path']);
                }
                $this->zip->download('เอกสารประกอบการเรียน.zip');
            } else if ($count_dycontent > 0) {

                // มีแค่ dycontent
                if ($count_dycontent == 1) {

                    redirect('play/play_resource/pdf_dycontent/' . $a_resource_id_dycontent[0]);
                } else {


                    $this->load->model('resource//xelatex_dycontent_merge_model');
                    $this->xelatex_dycontent_merge_model->init_content($a_resource_id_dycontent);
                    $result = $this->xelatex_dycontent_merge_model->render_pdf();
                    //print_r($result);
                    $this->load->helper('download');

                    force_download_file('เอกสารประกอบการเรียน.pdf', $result['files'][0]);
                }
            } else if ($count_doc > 0) {
                // มีแค่ doc
                $full_doc_dir = $this->config->item('full_doc_dir');

                if (count($a_resource_id_doc) > 1) {
                    $where_in = implode(",", $a_resource_id_doc);

                    $sql = "SELECT 
                    r_resource.title,
                    r_resource.resource_id,
                    r_resource_doc.file_path
                    FROM (`r_resource_doc`, 
                    (select resource_id, `title` from r_resource 
                    where resource_id in($where_in)) as r_resource) 
                    WHERE r_resource_doc.resource_id=r_resource.resource_id";
                    $q_doc = $this->db->query($sql);
                    if ($q_doc->num_rows() > 1) {
                        $this->load->library('zip');
                        foreach ($q_doc->result_array() as $r_doc) {
                            $this->zip->read_file($full_doc_dir . $r_doc['file_path']);
                        }
                        $this->zip->download('เอกสารประกอบการเรียน.zip');
                    }
                } else {
                    $sql = "SELECT 
                    r_resource.title,
                    r_resource.resource_id,
                    r_resource_doc.file_path
                    FROM (`r_resource_doc`, 
                    (select resource_id, `title` from r_resource 
                    where resource_id = " . $a_resource_id_doc[0] . ") as r_resource) 
                    WHERE r_resource_doc.resource_id=r_resource.resource_id";
                    $q_doc = $this->db->query($sql);
                    $r_doc = $q_doc->row_array();

                    $file_detail = pathinfo($r_doc['file_path']);
                    $this->load->helper('download');
                    $full_doc_dir = $this->config->item('full_doc_dir');

                    force_download_file($r_doc['title'] . $file_detail['extension'], $full_doc_dir . $r_doc['file_path']);
                }
            }
        }
    }

    function play_flash_media($resource_id, $swf_file) {
        $ext = end(explode('.', $swf_file));
        if ($ext != 'play') {
            $swf_path = $this->config->item('full_flash_media_dir') . str_replace('ztatic/play_flash_media/', '', uri_string());
            header("Content-Type: application/x-shockwave-flash");
            readfile($swf_path);
        } else {


            $this->db->where('resource_id', $resource_id);
            $q = $this->db->get('r_resource_flash_media');
            $resource_data = $q->row_array();
            $data['title'] = $ext;
            $this->template->script_var(
                    array(
                        'expressInstall_url' => base_url('files/swf/expressInstall.swf'),
                        //'swf_url' => base_url($this->config->item('flash_media_dir') . $resource_data['resource_id'] . '/' . $resource_data['file_path'])//ควรเปลี่ยนเป็น file_path
                        'swf_url' => site_url('ztatic/flash_media/' . $resource_id . '/' . $resource_data['file_path'])//ควรเปลี่ยนเป็น file_path
                    )
            );
            $this->template->load_swfobject();
            $this->template->write_view('play/play_flash_media', $data);
            $this->template->render();
        }
    }

    function flash_media($resource_id, $file_path) {
        $file_path = $this->config->item('full_flash_media_dir') . $resource_id . '/' . $file_path;
        header("Content-Type: application/x-shockwave-flash");
        readfile($file_path);
    }

    function content_doc_swf($resource_id) {
        $this->load->model('play/play_resource_model');
        $init_result = $this->play_resource_model->init_resource($resource_id);
        if ($init_result) {
            //$resource_data = $this->play_resource_model->get_resource_data();
            $file_path = $this->play_resource_model->get_full_file_path();

            header("Content-Type: application/x-shockwave-flash");
            readfile($file_path);
        } else {
            echo 'not';
        }
    }

}
