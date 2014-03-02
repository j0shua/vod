<?php

/**
 * @author lojorider  <lojorider@gmail.com>
 * @property resource_join_model $resource_join_model
 */
class resource_join extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth->access_limit($this->auth->permis_resource);
        $this->load->model('resource/resource_join_model');
    }

//========= ALL ZONE =========================================

    function video_join($resource_id_video) {
        $resource_id = $this->resource_join_model->get_resource_id_other($resource_id_video, TRUE);
        
        $cancel_link = site_url('resource/video_manager');
        $data = array(
            'title' => 'เชื่อม เอกสาร/โจทย์/เนื้อหา',
            'form_action' => site_url('resource/resource_join/do_video_join'),
            'cancel_link' => $cancel_link,
            'resource_id_video' => $resource_id_video,
            'resource_id' => $resource_id
        );
        $this->template->script_var(array(
            'iframe_doc_manager_url' => site_url('resource/doc_manager/iframe/'),
            'iframe_dycontent_manager_url' => site_url('resource/dycontent/iframe/')
        ));
        $this->template->load_typeonly();
        $this->template->write_view('resource/resource_join/video_join_form', $data);
        $this->template->render();
    }

    function do_video_join() {
        if ($this->resource_join_model->video_join($this->input->post('data'))) {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/video_manager'),
                'heading' => 'เชื่อมโยงเอกสารเสร็จสิ้น',
                'message' => '<p>เชื่อมโยงเอกสารเสร็จสิ้น</p>'
            );
        } else {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/video_manager'),
                'heading' => 'ระบบผิดพลาด',
                'message' => '<p>ระบบผิดพลาด </p>'
            );
        }

        $this->load->view('refresh_page', $data);
    }

    function join_video($resource_id) {
        $resource_id_video = $this->resource_join_model->get_resource_id_video($resource_id, TRUE);
        $cancel_link = site_url();
        switch ($this->resource_join_model->get_resource_type_id($resource_id)) {
            case 2:
                $cancel_link = site_url('resource/doc_manager');
                break;
            case 4:
                $cancel_link = site_url('resource/dycontent');
                break;
            default:

                break;
        }

        $data = array(
            'title' => 'เชื่อมวิดีโอ',
            'form_action' => site_url('resource/resource_join/do_join_video'),
            'cancel_link' => $cancel_link,
            'resource_id' => $resource_id,
            'resource_id_video' => $resource_id_video
        );
        $this->template->script_var(array(
            'iframe_video_manager_url' => site_url('resource/video_manager/iframe/resource_id_video')
        ));

        $this->template->load_typeonly();
        $this->template->write_view('resource/resource_join/all_join_video_form', $data);
        $this->template->render();
    }

    function do_join_video() {
        $post = $this->input->post('data');
        $result = $this->resource_join_model->join_video($post);
        $redirec_url = site_url();
        switch ($result['resource_type_id']) {
            case 2:
                $redirec_url = site_url('resource/doc_manager');
                break;
            case 4:
                $redirec_url = site_url('resource/dycontent');
                break;
            default:

                break;
        }
        if ($result['status']) {

            $data = array(
                'time' => 1,
                'url' => $redirec_url,
                'heading' => 'เชื่อมโยงเอกสารเสร็จสิ้น',
                'message' => '<p>เชื่อมโยงเอกสารเสร็จสิ้น</p>'
            );
        } else {
            $data = array(
                'time' => 1,
                'url' => $redirec_url,
                'heading' => 'ระบบผิดพลาด',
                'message' => '<p>' . $result['msg'] . ' </p>'
            );
        }

        $this->load->view('refresh_page', $data);
    }

//========= ZONE DOC =========================================
//    function video_join_doc($resource_id_video) {
//        $resource_id_doc = $this->resource_join_model->get_resource_id_doc_join_video($resource_id_video, ',');
//        $cancel_link = site_url('resource/video_manager');
//        $data = array(
//            'title' => 'เชื่อมเอกสาร',
//            'form_action' => site_url('resource/resource_join/do_video_join_doc'),
//            'cancel_link' => $cancel_link,
//            'resource_id_video' => $resource_id_video,
//            'resource_id_doc' => $resource_id_doc
//        );
//        $this->template->load_typeonly();
//        $this->template->write_view('resource/resource_join/video_join_doc_form', $data);
//        $this->template->render();
//    }
//
//    function do_video_join_doc() {
//        if ($this->resource_join_model->video_join_doc($this->input->post('data'))) {
//            $data = array(
//                'time' => 1,
//                'url' => site_url('resource/video_manager'),
//                'heading' => 'เชื่อมโยงเอกสารเสร็จสิ้น',
//                'message' => '<p>เชื่อมโยงเอกสารเสร็จสิ้น</p>'
//            );
//        } else {
//            $data = array(
//                'time' => 1,
//                'url' => site_url('resource/video_manager'),
//                'heading' => 'ระบบผิดพลาด',
//                'message' => '<p>ระบบผิดพลาด </p>'
//            );
//        }
//
//        $this->load->view('refresh_page', $data);
//    }
//
//    function doc_join_video($resource_id_doc) {
//        $resource_id_video = $this->resource_join_model->get_resource_id_video_join_doc($resource_id_doc, ',');
//        $cancel_link = site_url();
//        switch ($this->resource_join_model->get_resource_type_id($resource_id_doc)) {
//            case 2:
//                $cancel_link = site_url('resource/doc_manager');
//                break;
//            case 4:
//                $cancel_link = site_url('resource/dycontent');
//                break;
//            default:
//
//                break;
//        }
//
//        $data = array(
//            'title' => 'เชื่อมวิดีโอ',
//            'form_action' => site_url('resource/resource_join/do_doc_join_video'),
//            'cancel_link' => $cancel_link,
//            'resource_id_doc' => $resource_id_doc,
//            'resource_id_video' => $resource_id_video
//        );
//        $this->template->script_var(array(
//            'iframe_video_manager_url' => site_url('resource/video_manager/iframe/resource_id_video')
//        ));
//
//        $this->template->load_typeonly();
//        $this->template->write_view('resource/resource_join/doc_join_video_form', $data);
//        $this->template->render();
//    }
//
//    function do_doc_join_video() {
//        $result = $this->resource_join_model->doc_join_video($this->input->post('data'));
//        $redirec_url = site_url();
//        switch ($result['resource_type_id']) {
//            case 2:
//                $redirec_url = site_url('resource/doc_manager');
//                break;
//            case 4:
//                $redirec_url = site_url('resource/dycontent');
//                break;
//            default:
//
//                break;
//        }
//        if ($result['status']) {
//
//            $data = array(
//                'time' => 1,
//                'url' => $redirec_url,
//                'heading' => 'เชื่อมโยงเอกสารเสร็จสิ้น',
//                'message' => '<p>เชื่อมโยงเอกสารเสร็จสิ้น</p>'
//            );
//        } else {
//            $data = array(
//                'time' => 1,
//                'url' => $redirec_url,
//                'heading' => 'ระบบผิดพลาด',
//                'message' => '<p>' . $result['msg'] . ' </p>'
//            );
//        }
//
//        $this->load->view('refresh_page', $data);
//    }
//
//    //========= ZONE dycontent =========================================
//    function video_join_dycontent($resource_id_video) {
//        $resource_id_dycontent = $this->resource_join_model->get_resource_id_dycontent_join($resource_id_video, ',');
//        $cancel_link = site_url('resource/video_manager');
//        $data = array(
//            'title' => 'เชื่อมเอกสาร',
//            'form_action' => site_url('resource/resource_join/do_join_dycontent'),
//            'cancel_link' => $cancel_link,
//            'resource_id_video' => $resource_id_video,
//            'resource_id_dycontent' => $resource_id_dycontent
//        );
//        $this->template->load_typeonly();
//        $this->template->write_view('resource/resource_join/video_join_dycontent_form', $data);
//        $this->template->render();
//    }
//
//    function do_video_join_dycontent() {
//        if ($this->resource_join_model->join_dycontent($this->input->post('data'))) {
//            $data = array(
//                'time' => 1,
//                'url' => site_url('resource/video_manager'),
//                'heading' => 'เชื่อมโยงเอกสารเสร็จสิ้น',
//                'message' => '<p>เชื่อมโยงเอกสารเสร็จสิ้น</p>'
//            );
//        } else {
//            $data = array(
//                'time' => 1,
//                'url' => site_url('resource/video_manager'),
//                'heading' => 'ระบบผิดพลาด',
//                'message' => '<p>ระบบผิดพลาด </p>'
//            );
//        }
//
//        $this->load->view('refresh_page', $data);
//    }
//
//    function dycontent_join_video($resource_id_dycontent) {
//        $resource_id_video = $this->resource_join_model->get_resource_id_video_join_dycontent($resource_id_dycontent, ',');
//        $cancel_link = site_url();
//        switch ($this->resource_join_model->get_resource_type_id($resource_id_dycontent)) {
//            case 2:
//                $cancel_link = site_url('resource/dycontent_manager');
//                break;
//            case 4:
//                $cancel_link = site_url('resource/dycontent');
//                break;
//            default:
//
//                break;
//        }
//
//        $data = array(
//            'title' => 'เชื่อมวิดีโอ',
//            'form_action' => site_url('resource/resource_join/do_dycontent_join_video'),
//            'cancel_link' => $cancel_link,
//            'resource_id_dycontent' => $resource_id_dycontent,
//            'resource_id_video' => $resource_id_video
//        );
//        $this->template->script_var(array(
//            'iframe_video_manager_url' => site_url('resource/video_manager/iframe/resource_id_video')
//        ));
//
//        $this->template->load_typeonly();
//        $this->template->write_view('resource/resource_join/dycontent_join_video_form', $data);
//        $this->template->render();
//    }
//
//    function do_dycontent_join_video() {
//        $result = $this->resource_join_model->dycontent_join_video($this->input->post('data'));
//        $redirec_url = site_url();
//        switch ($result['resource_type_id']) {
//            case 2:
//                $redirec_url = site_url('resource/dycontent_manager');
//                break;
//            case 4:
//                $redirec_url = site_url('resource/dycontent');
//                break;
//            default:
//
//                break;
//        }
//        if ($result['status']) {
//
//            $data = array(
//                'time' => 1,
//                'url' => $redirec_url,
//                'heading' => 'เชื่อมโยงเอกสารเสร็จสิ้น',
//                'message' => '<p>เชื่อมโยงเอกสารเสร็จสิ้น</p>'
//            );
//        } else {
//            $data = array(
//                'time' => 1,
//                'url' => $redirec_url,
//                'heading' => 'ระบบผิดพลาด',
//                'message' => '<p>' . $result['msg'] . ' </p>'
//            );
//        }
//
//        $this->load->view('refresh_page', $data);
//    }
}