<?php

/**
 * Description of video_manager
 *
 * @author lojorider
 * @property video_manager_model $video_manager_model
 * @property disk_quota_service_model $disk_quota_service_model
 * @property resource_menu_model $resource_menu_model
 */
class video_manager extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->auth->access_limit($this->auth->permis_resource);
        $this->load->model('resource/video_manager_model');
        $this->load->model('core/ddoption_model');
        $this->load->model('main_side_menu_model');
        $this->make_money = $this->config->item('make_money');
    }

    function index() {
        $this->load->helper('form');
        $this->load->model('service/disk_quota_service_model');

        $data['title'] = 'จัดการวิดีโอ';
        $this->disk_quota_service_model->init_user_quota($this->auth->uid());
        $data['can_upload'] = $this->disk_quota_service_model->can_upload();
        $data['user_disk_size'] = $this->disk_quota_service_model->get_user_disk_size();
        $data['user_disk_quota'] = $this->disk_quota_service_model->get_user_disk_quota();
        $data['upload_link'] = site_url('resource/video_upload');
        if ($this->make_money) {
            $data['qtype_options'] = array(
                'resource_id' => 'เลขที่วิดีโอ',
                'resource_code' => 'รหัสวิดีโอ',
                'title' => 'ชื่อวิดีโอ',
                'desc' => 'รายละเอียด',
                'tags' => 'ป้ายกำกับ',
                'subject_title' => 'วิชา',
                'chapter_title' => 'บทเรียน'
            );
        } else {
            $data['qtype_options'] = array(
                'resource_id' => 'เลขที่วิดีโอ',
                //'resource_code' => 'รหัสวิดีโอ',
                'title' => 'ชื่อวิดีโอ',
                'desc' => 'รายละเอียด',
                'tags' => 'ป้ายกำกับ',
                'subject_title' => 'วิชา',
                'chapter_title' => 'บทเรียน'
            );
        }

        $data['command_to_resource_options'] = $this->ddoption_model->get_command_to_resource_options();
        $this->template->script_var(array(
            'ajax_grid_url' => site_url('resource/video_manager/ajax_list'),
            'ajax_act_resource_url' => site_url('resource/video_manager/ajax_act_resource')
        ));
        $data['main_side_menu'] = $this->main_side_menu_model->resource('video_manager');
        if ($this->make_money) {
            $this->template->application_script('resource/video_manager/main_grid.js');
        } else {
            $this->template->application_script('resource/video_manager/main_grid_freesys.js');
        }

        $this->template->load_showloading();
        $this->template->load_flexgrid();
        $this->template->write_view('resource/video_manager/main_grid', $data);
        $this->template->render();
    }

    /**
     * แก้ไข resourc แบบ ajax
     */
    function ajax_act_resource() {
        $a_result = array();
        $a_result['msg'] = "การกระทำเป็นไปด้วยดี";
        $a_resource_id = $this->input->post('cb_resource_id');
        if ($a_resource_id) {
            switch ($this->input->post('command')) {
                case 'to_delete':
                    $a_result['status'] = $this->video_manager_model->delete($a_resource_id);
                    break;
                case 'to_private':
                    $a_result['status'] = $this->video_manager_model->privacy($a_resource_id, 0);
                    break;
                case 'to_no_private':
                    $a_result['status'] = $this->video_manager_model->privacy($a_resource_id, 1);
                    break;
                case 'to_publish':
                    $a_result['status'] = $this->video_manager_model->publish($a_resource_id, 1);
                    break;
                case 'to_no_publish':
                    $a_result['status'] = $this->video_manager_model->publish($a_resource_id, 0);
                    break;
                default:
                    break;
            }
            if (!$a_result['status']) {
                $a_result['msg'] = "การกระทำเกิดข้อผิดพลาดบางอย่าง";
            }
        } else {
            $a_result['status'] = false;
            $a_result['msg'] = "ท่านยังไม่ได้เลือกข้อมูลเพื่อทำการลบ";
        }

        echo json_encode($a_result);
    }

    function ajax_list() {
        $a = $this->video_manager_model->find_all($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query'), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE));
        echo json_encode($a);
    }

    function ajax_chapter() {
        $term = $this->input->get('term');
        $this->db->select('chapter_title');
        $this->db->like('chapter_title', $term);
        $this->db->where('uid_owner', $this->auth->uid());
        $this->db->distinct('chapter_title');
        $this->db->limit(20);
        $q = $this->db->get('r_resource');
        $list = array();
        if ($q->num_rows() > 0) {
            foreach ($q->result_array() as $r) {
                $list[] = '{"id":"' . $r['chapter_title'] . '","label":"' . $r['chapter_title'] . '","value":"' . $r['chapter_title'] . '"}';
            }
            echo '[' . implode(',', $list) . ']';
        } else {
            echo '[]';
        }
    }

    function edit($resource_id) {
        $url = site_url('resource/video_manager');
        if ($this->video_manager_model->get_resource_type_id($resource_id) == 6) {
            $url = site_url('resource/video_manager/prokru');
        }
        if (!$this->video_manager_model->is_owner($resource_id)) {
            $data = array(
                'time' => 1,
                'url' => $url,
                'heading' => 'ระบบผิดพลาด',
                'message' => '<p>ระบบผิดพลาด คุณไม่สามารถแก้ไขข้อมูลชุดนี้ได้</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $this->load->helper('form');
            $this->template->load_showloading();
            $this->template->application_script('resource/video_manager/edit_form.js');

            $data = array(
                'title' => 'แก้ไขรายละเอียดวีดีโอ',
                'form_action' => site_url('resource/video_manager/do_save'),
                'form_data' => $this->video_manager_model->get_video_form_data($resource_id),
                'cancel_link' => $url,
                'delete_link' => site_url('resource/video_manager/delete/' . $resource_id),
                'publish_options' => $this->ddoption_model->get_publish_options(),
                'privacy_options' => $this->ddoption_model->get_privacy_options(),
                'degree_id_options' => $this->ddoption_model->get_degree_id_options(),
                'learning_area_options' => $this->ddoption_model->get_learning_area_options(),
                'unit_price_options' => $this->ddoption_model->get_unit_price_options()
            );


            $this->template->script_var(
                    array(
                        'ajax_subject_options_url' => site_url('core/ajax_ddoptions/get_subject_options'),
                        'ajax_chapter_options_url' => site_url('core/ajax_ddoptions/get_chapter_options'),
                        'ajax_sub_chapter_autocomplete_url' => site_url('core/ajax_autocomplete/get_sub_chapter'),
                        'subj_id' => $data['form_data']['subj_id'],
                        'chapter_id' => $data['form_data']['chapter_id'],
                    )
            );

            $this->template->write_view('resource/video_manager/edit_form', $data);
            $this->template->render();
        }
    }

    function do_save() {
        if ($this->video_manager_model->save($this->input->post('data'))) {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/video_manager'),
                'heading' => 'แก้ไขข้อมูลเสร็จสิ้น',
                'message' => '<p>แก้ไขข้อมูลเสร็จสิ้น</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/video_manager'),
                'heading' => 'ระบบผิดพลาด',
                'message' => '<p>ระบบผิดพลาด คุณไม่สามารถแก้ไขข้อมูลชุดนี้ได้</p>'
            );
            $this->load->view('refresh_page', $data);
        }
    }

    function delete($resource_id) {
        $data = array(
            'url' => site_url('resource/video_manager/do_delete/' . $resource_id),
            'cancel_url' => site_url('resource/video_manager'),
            'heading' => 'คุณต้องการลบข้อมูล video นี้ใช่ไหม',
            'message' => '<p>หากคุณลบข้อมูล video นี้แล้วระบบจะลบข้อมูล video อย่างถาวร</p>'
        );

        $this->load->view('confirm_page', $data);
    }

    function do_delete($id) {
        if ($this->video_manager_model->delete($id)) {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/video_manager'),
                'heading' => 'ทำการลบข้อมูลเสร็จสิ้น',
                'message' => '<p>คุณไม่สามารถเรียกคืน video นี้ได้อีก</p>'
            );
        } else {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/video_manager'),
                'heading' => 'การลบข้อมูลผิดพลาด',
                'message' => '<p>คุณไม่สามารถลบ video </p>'
            );
        }
        $this->load->view('refresh_page', $data);
    }

    function update_duration() {
        $this->video_manager_model->update_all_duration();
        //exit();
        $data = array(
            'time' => 1,
            'url' => site_url('resource/video_manager'),
            'heading' => 'ปรับปรุงเวลาของ video เสร็จสิ้น',
            'message' => '<p>ปรับปรุงเวลาของ video เสร็จสิ้น</p>'
        );
        $this->load->view('refresh_page', $data);
    }

    // PROKRU ==================================================================================================
    function prokru() {
        $this->load->helper('form');

        $this->video_manager_model->set_resource_type_id(6);
        $this->template->load_showloading();
        $this->template->load_flexgrid();
        $this->template->application_script('resource/video_manager/main_grid_prokru.js');
        $data['title'] = 'จัดการ วิดีโอจาก prokru';
        $data['add_link'] = site_url('resource/video_manager/add_prokru');

        $data['main_side_menu'] = $this->main_side_menu_model->resource('video_manager');

        $data['qtype_options'] = array('title' => 'ชื่อวิดีโอ', 'resource_id' => 'เลขที่วิดีโอ', 'desc' => 'รายละเอียด', 'tags' => 'tags', 'category_id' => 'หมวดหมู่');
        $data['ajax_grid_url'] = site_url('resource/video_manager/ajax_prokru_uploads_list');
        $this->template->write_view('resource/video_manager/main_grid_prokru', $data);
        $this->template->render();
    }

    function ajax_prokru_uploads_list() {
        $this->video_manager_model->set_resource_type_id(6);
        $a = $this->video_manager_model->find_all($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query', TRUE), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE));
        echo json_encode($a);
    }

    function add_prokru() {
        $this->load->helper('form');
        $this->template->load_showloading();
        $data = array(
            'title' => 'เพิ่ม วิดีโอจาก prokru',
            'form_action' => site_url('resource/video_manager/do_save_prokru'),
            'form_data' => $this->video_manager_model->get_video_form_data(),
            'cancel_link' => site_url('resource/video_manager/prokru'),
            'publish_options' => $this->ddoption_model->get_publish_options(),
            'privacy_options' => $this->ddoption_model->get_privacy_options(),
            'degree_options' => $this->ddoption_model->get_degree_id_options(),
            'learning_area_options' => $this->ddoption_model->get_learning_area_options(),
            'ajax_subject_options_url' => site_url('core/ajax_ddoptions/get_subject_options'),
            'ajax_chapter_autocomplete_url' => site_url('core/ajax_autocomplete/get_chapter'),
            'ajax_prokru_get_video_data_url' => site_url('resource/video_manager/ajax_prokru_get_video_data')
        );
        $this->template->write_view('resource/video_manager/prokru_add_form', $data);
        $this->template->render();
    }

    function edit_prokru() {
        if (!$this->video_manager_model->is_owner($resource_id)) {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/video_manager'),
                'heading' => 'ระบบผิดพลาด',
                'message' => '<p>ระบบผิดพลาด คุณไม่สามารถแก้ไขข้อมูลชุดนี้ได้</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $this->load->helper('form');
            $data = array(
                'form_action' => site_url('resource/video_manager/do_save'),
                'form_data' => $this->video_manager_model->get_video_form_data($resource_id),
                'cancel_link' => site_url('resource/video_manager'),
                'delete_link' => site_url('resource/video_manager/delete/' . $resource_id),
                'publish_options' => $this->ddoption_model->get_publish_options(),
                'privacy_options' => $this->ddoption_model->get_privacy_options(),
                'category_options' => $this->ddoption_model->get_category_array_options()
            );
            $this->template->write_view('resource/video_manager/edit_form', $data);
            $this->template->render();
        }
    }

    function do_save_prokru() {

        if ($this->video_manager_model->save($this->input->post('data'))) {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/video_manager/prokru'),
                'heading' => 'แก้ไขข้อมูลเสร็จสิ้น',
                'message' => '<p>แก้ไขข้อมูลเสร็จสิ้น</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/video_manager/prokru'),
                'heading' => 'ระบบผิดพลาด',
                'message' => '<p>ระบบผิดพลาด คุณไม่สามารถแก้ไขข้อมูลชุดนี้ได้</p>'
            );
            $this->load->view('refresh_page', $data);
        }
    }

    function ajax_prokru_get_video_data($teach_set_id = '') {
        if ($teach_set_id == '') {
            $teach_set_id = $this->input->post('teach_set_id');
        }
        $value = array(
            'status' => FALSE,
            'msg' => 'ไม่พบข้อมูล'
        );
        $db_config['hostname'] = 'localhost';
        $db_config['username'] = 'krueonline';
        $db_config['password'] = 'XHNJBKNO4';
        $db_config['database'] = 'krueonline_01';
        $db_config['dbdriver'] = 'mysql';
        $db_config['dbprefix'] = '';
        $db_config['pconnect'] = TRUE;
        $db_config['db_debug'] = TRUE;
        $db_config['cache_on'] = FALSE;
        $db_config['cachedir'] = '';
        $db_config['char_set'] = 'utf8';
        $db_config['dbcollat'] = 'utf8_general_ci';
        $db_config['swap_pre'] = '';
        $db_config['autoinit'] = TRUE;
        $db_config['stricton'] = FALSE;
        $this->db = $this->load->database($db_config, TRUE);
        $this->db->where('teach_set_id', $teach_set_id);
        $q = $this->db->get('a_teach_set1_d1');
        if ($q->num_rows() > 0 && $q->num_rows() == 1) {
            $resource_id = $q->row()->resource_id;
            $this->db->where('resource_id', $resource_id);
            $q2 = $this->db->get('a_resource_d1');
            if ($q2->num_rows() > 0 && $q2->num_rows() == 1) {
                $value = $q2->row_array();
                $value['msg'] = 'เลขที่ถูกต้อง';
                $value['status'] = TRUE;
            }
        }
        echo json_encode($value);
    }

    //=========================================================================
    // Iframe
    //=========================================================================
    function iframe($input_id) {
        $this->load->helper('form');
        $this->load->model('service/disk_quota_service_model');

        $data['title'] = 'จัดการวิดีโอ';
        $this->disk_quota_service_model->init_user_quota($this->auth->uid());
        $data['can_upload'] = $this->disk_quota_service_model->can_upload();
        $data['user_disk_size'] = $this->disk_quota_service_model->get_user_disk_size();
        $data['user_disk_quota'] = $this->disk_quota_service_model->get_user_disk_quota();
        $data['upload_link'] = site_url('resource/video_upload');
        $data['content_type_options'] = array(
            '' => 'ทุกแบบ',
            1 => 'เนื้อหา',
            2 => 'โจทย์หนึ่งตัวเลือก(mc)',
            3 => 'โจทย์หลายตัวเลือก(mcma)',
            4 => 'โจทย์เติมคำ(ct)',
//            5 => 'โจทย์เติมคำหลายคำตอบ(ctma)'
        );
        if ($this->make_money) {
            $data['qtype_options'] = array(
                'resource_id' => 'เลขที่วิดีโอ',
                'resource_code' => 'รหัสวิดีโอ',
                'title' => 'ชื่อวิดีโอ',
                'desc' => 'รายละเอียด',
                'tags' => 'ป้ายกำกับ',
                'subject_title' => 'วิชา',
                'chapter_title' => 'บทเรียน'
            );
        } else {
            $data['qtype_options'] = array(
                'resource_id' => 'เลขที่วิดีโอ',
                //'resource_code' => 'รหัสวิดีโอ',
                'title' => 'ชื่อวิดีโอ',
                'desc' => 'รายละเอียด',
                'tags' => 'ป้ายกำกับ',
                'subject_title' => 'วิชา',
                'chapter_title' => 'บทเรียน'
            );
        }

        $this->template->script_var(array(
            'ajax_grid_url' => site_url('resource/video_manager/ajax_iframe_list/'),
            'input_id' => $input_id
        ));

        if ($this->make_money) {
            $this->template->application_script('resource/video_manager/iframe_main_grid.js');
        } else {
            $this->template->application_script('resource/video_manager/iframe_main_grid_freesys.js');
        }

        $this->template->load_showloading();
        $this->template->load_flexgrid();
        $this->template->write_view('resource/video_manager/iframe_grid', $data);
        $this->template->temmplate_name('normal');
        $this->template->render();
    }

    function ajax_iframe_list() {
        $a = $this->video_manager_model->iframe_find_all($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query', TRUE), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE));
        echo json_encode($a);
    }

}