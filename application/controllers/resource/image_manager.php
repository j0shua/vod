<?php

/**
 * Description of image_manager
 *
 * @author lojorider
 * @property image_manager_model $image_manager_model
 * @property disk_quota_service_model $disk_quota_service_model
 * @property resource_menu_model $resource_menu_model
 */
class image_manager extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->auth->access_limit($this->auth->permis_resource);
        $this->load->model('resource/image_manager_model');
        $this->load->model('core/ddoption_model');
        $this->load->model('main_side_menu_model');
        $this->make_money = $this->config->item('make_money');
    }

    function index() {
        $this->load->helper('form');
        $this->load->model('service/disk_quota_service_model');
        $this->template->load_showloading();
        $this->template->load_flexgrid();
        $this->template->load_jquery_colorbox();


        $data['title'] = 'จัดการรูปภาพ';
        $this->disk_quota_service_model->init_user_quota($this->auth->uid());
        $data['can_upload'] = $this->disk_quota_service_model->can_upload();
        $data['user_disk_size'] = $this->disk_quota_service_model->get_user_disk_size();
        $data['user_disk_quota'] = $this->disk_quota_service_model->get_user_disk_quota();
        $data['upload_link'] = site_url('resource/image_manager/upload');
        $data['main_side_menu'] = $this->main_side_menu_model->resource('image_manager');
        $data['qtype_options'] = array(
            'title' => 'ชื่อรูปภาพ',
            'resource_id' => 'เลขที่รูปภาพ',
            'desc' => 'รายละเอียด',
            'tags' => 'ป้ายกำกับ',
            'subject_title' => 'วิชา',
            'chapter_title' => 'บทเรียน'
        );
        $data['command_to_resource_options'] = $this->ddoption_model->get_command_to_resource_options();
        $this->template->write_view('resource/image_manager/main_grid', $data);
        $this->template->script_var(array(
            'ajax_grid_url' => site_url('resource/image_manager/ajax_uploads_list'),
            'ajax_act_resource_url' => site_url('resource/image_manager/ajax_act_resource')
        ));
        $this->template->application_script('resource/image_manager/main_grid.js');
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
                    $a_result['status'] = $this->image_manager_model->delete($a_resource_id);
                    break;
                case 'to_private':
                    $a_result['status'] = $this->image_manager_model->privacy($a_resource_id, 0);
                    break;
                case 'to_no_private':
                    $a_result['status'] = $this->image_manager_model->privacy($a_resource_id, 1);
                    break;
                case 'to_publish':
                    $a_result['status'] = $this->image_manager_model->publish($a_resource_id, 1);
                    break;
                case 'to_no_publish':
                    $a_result['status'] = $this->image_manager_model->publish($a_resource_id, 0);
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

    function ajax_uploads_list() {
        $a = $this->image_manager_model->find_all($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query', TRUE), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE));
        echo json_encode($a);
    }

    function edit($resource_id) {
        if (!$this->image_manager_model->is_owner($resource_id)) {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/image_manager'),
                'heading' => 'ระบบผิดพลาด',
                'message' => '<p>ระบบผิดพลาด คุณไม่สามารถแก้ไขข้อมูลชุดนี้ได้</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $this->load->helper('form');
            $this->template->load_showloading();
            $this->template->application_script('resource/image_manager/edit_form.js');
            $data = array(
                //ทั่วไป
                'title' => 'แก้ไขข้อมูลรูปภาพ',
                'form_action' => site_url('resource/image_manager/do_save'),
                'form_data' => $this->image_manager_model->get_image_form_data($resource_id),
                'cancel_link' => site_url('resource/image_manager'),
                'delete_link' => site_url('resource/image_manager/delete/' . $resource_id),
                'publish_options' => $this->ddoption_model->get_publish_options(),
                'privacy_options' => $this->ddoption_model->get_privacy_options(),
                'degree_options' => $this->ddoption_model->get_degree_id_options(),
                'learning_area_options' => $this->ddoption_model->get_learning_area_options(),
                'ajax_subject_options_url' => site_url('core/ajax_ddoptions/get_subject_options'),
                'ajax_chapter_autocomplete_url' => site_url('core/ajax_autocomplete/get_chapter'),
            );
            $this->template->script_var(
                    array(
                        'ajax_subject_options_url' => site_url('core/ajax_ddoptions/get_subject_options'),
                        'ajax_chapter_options_url' => site_url('core/ajax_ddoptions/get_chapter_options'),
                        'ajax_sub_chapter_autocomplete_url' => site_url('core/ajax_autocomplete/get_sub_chapter'),
                        'subj_id' => $data['form_data']['subj_id'],
                        'chapter_id' => $data['form_data']['chapter_id']
                    )
            );
            $this->template->write_view('resource/image_manager/edit_form', $data);
            $this->template->render();
        }
    }

    function do_save() {
        if ($this->image_manager_model->save($this->input->post('resource_id'), $this->input->post('data'))) {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/image_manager'),
                'heading' => 'แก้ไขข้อมูลเสร็จสิ้น',
                'message' => '<p>แก้ไขข้อมูลเสร็จสิ้น</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/image_manager'),
                'heading' => 'ระบบผิดพลาด',
                'message' => '<p>ระบบผิดพลาด คุณไม่สามารถแก้ไขข้อมูลชุดนี้ได้</p>'
            );
            $this->load->view('refresh_page', $data);
        }
    }

    function delete($resource_id) {
        $data = array(
            'url' => site_url('resource/image_manager/do_delete/' . $resource_id),
            'cancel_url' => site_url('resource/image_manager'),
            'heading' => 'คุณต้องการลบข้อมูล รูปภาพ นี้ใช่ไหม',
            'message' => '<p>หากคุณลบข้อมูล รูปภาพ นี้แล้วระบบจะลบข้อมูล รูปภาพ อย่างถาวร</p>'
        );

        $this->load->view('confirm_page', $data);
    }

    function do_delete($id) {
        if ($this->image_manager_model->delete($id)) {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/image_manager'),
                'heading' => 'ทำการลบข้อมูลเสร็จสิ้น',
                'message' => '<p>คุณไม่สามารถเรียกคืน รูปภาพนี้ นี้ได้อีก</p>'
            );
        } else {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/image_manager'),
                'heading' => 'การลบข้อมูลผิดพลาด',
                'message' => '<p>คุณไม่สามารถลบ รูปภาพนี้ </p>'
            );
        }
        $this->load->view('refresh_page', $data);
    }

    function upload() {
        $this->load->model('resource/image_upload_model');
        $this->load->model('service/disk_quota_service_model');
        $this->disk_quota_service_model->init_user_quota($this->auth->uid());
        if (!$this->disk_quota_service_model->can_upload()) {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/image_manager'),
                'heading' => 'ไม่สามารถ Upload ได้',
                'message' => '<p>ไม่สามารถ Upload ได้</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $this->load->helper('form');

            $this->template->load_fileuploader();
            $this->template->load_showloading();
            $this->template->application_script('resource/image_manager/upload_form.js');
            $data = array(
                //ทั่วไป
                'title' => 'อัพโหลดรูปภาพ',
                'form_action' => site_url('resource/image_manager/do_upload_save'),
                'cancel_link' => site_url('resource/image_manager'),
                'publish_options' => $this->ddoption_model->get_publish_options(),
                'privacy_options' => $this->ddoption_model->get_privacy_options(),
                'degree_options' => $this->ddoption_model->get_degree_id_options(),
                'learning_area_options' => $this->ddoption_model->get_learning_area_options()
            );
            $extension_whitelist = $this->image_upload_model->get_extension_whitelist();
            $data['extension_whitelist'] = implode(',', $extension_whitelist);
            $extension_whitelist = "['" . implode("', '", $extension_whitelist) . "']";
            $data['file_size_limit'] = $file_size_limit = $this->image_upload_model->get_file_size_limit();
            $this->template->script_var(array(
                'cgi_bin_url' => $this->config->item('cgi_upload_url'),
                'upload_dir' => $this->config->item('cgi_upload_dir'),
                'extension_whitelist' => array('value' => $extension_whitelist),
                'file_size_limit' => array('value' => $file_size_limit),
                'ajax_subject_options_url' => site_url('core/ajax_ddoptions/get_subject_options'),
                'ajax_chapter_options_url' => site_url('core/ajax_ddoptions/get_chapter_options'),
                'ajax_sub_chapter_autocomplete_url' => site_url('core/ajax_autocomplete/get_sub_chapter'),
                'subj_id' => '',
                'chapter_id' => ''
            ));
            $this->template->write_view('resource/image_manager/upload_form', $data);
            $this->template->render();
        }
    }

    function do_upload_save() {

        $this->load->model('resource/image_upload_model');
        if ($this->image_upload_model->save($this->input->post('data'), $this->input->post('resume_file'))) {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/image_manager'),
                'heading' => 'upload เสร็จสิ้น',
                'message' => '<p>ผลการ upload เป็นไปได้ด้วยดี</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/image_manager'),
                'heading' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล',
                'message' => '<p>โปรดลองใหม่อีกครั้ง</p>'
            );
            $this->load->view('refresh_page', $data);
        }
    }

    //--------------------------------------------------------------------------
    // FOR IFRAME
    //--------------------------------------------------------------------------
    function iframe($input_form_id) {
        $this->load->helper('form');
        $data['input_form_id'] = $input_form_id;
        $data['upload_url'] = site_url('resource/image_manager/iframe_upload/' . $input_form_id);
        $data['qtype_options'] = array(
            'title' => 'ชื่อรูปภาพ',
            'resource_id' => 'เลขที่รูปภาพ',
            'desc' => 'รายละเอียด',
            'tags' => 'ป้ายกำกับ',
            'subject_title' => 'วิชา',
            'chapter_title' => 'บทเรียน'
        );
        $this->template->script_var(
                array(
                    'ajax_grid_url' => site_url('resource/image_manager/ajax_iframe_uploads_list/' . $input_form_id),
                    'input_form_id' => $input_form_id
                )
        );
        $this->template->load_flexgrid();
        $this->template->application_script('resource/image_manager/iframe.js');
        $this->template->write_view('resource/image_manager/iframe', $data);
        $this->template->temmplate_name('normal');
        $this->template->render();
    }

    function ajax_iframe_uploads_list($input_form_id) {
        $a = $this->image_manager_model->iframe_find_all($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query', TRUE), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE), $input_form_id);
        echo json_encode($a);
    }

    function iframe_upload($input_form_id) {
        $this->load->model('resource/image_upload_model');
        $this->load->model('service/disk_quota_service_model');
        $this->disk_quota_service_model->init_user_quota($this->auth->uid());
        if (!$this->disk_quota_service_model->can_upload()) {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/image_manager'),
                'heading' => 'ไม่สามารถ Upload ได้',
                'message' => '<p>ไม่สามารถ Upload ได้</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $this->load->helper('form');

            $this->template->load_fileuploader();
            $this->template->load_showloading();
            $this->template->application_script('resource/image_manager/iframe_upload_form.js');
            $data = array(
                //ทั่วไป
                'title' => 'อัพโหลดรูปภาพ',
                'form_action' => site_url('resource/image_manager/iframe_upload_save/' . $input_form_id),
                'cancel_link' => site_url('resource/image_manager/iframe/' . $input_form_id),
                'publish_options' => $this->ddoption_model->get_publish_options(),
                'privacy_options' => $this->ddoption_model->get_privacy_options(),
                'degree_options' => $this->ddoption_model->get_degree_id_options(),
                'learning_area_options' => $this->ddoption_model->get_learning_area_options()
            );
            $extension_whitelist = $this->image_upload_model->get_extension_whitelist();

            $extension_whitelist = "['" . implode("', '", $extension_whitelist) . "']";
            $file_size_limit = $this->image_upload_model->get_file_size_limit();
            $this->template->script_var(array(
                'cgi_bin_url' => $this->config->item('cgi_upload_url'),
                'upload_dir' => $this->config->item('cgi_upload_dir'),
                'extension_whitelist' => array('value' => $extension_whitelist),
                'file_size_limit' => array('value' => $file_size_limit),
                'ajax_subject_options_url' => site_url('core/ajax_ddoptions/get_subject_options'),
                'ajax_chapter_options_url' => site_url('core/ajax_ddoptions/get_chapter_options'),
                'ajax_sub_chapter_autocomplete_url' => site_url('core/ajax_autocomplete/get_sub_chapter'),
                'subj_id' => '',
                'chapter_id' => ''
            ));
            $this->template->write_view('resource/image_manager/iframe_upload_form', $data);
            $this->template->temmplate_name('normal');
            $this->template->render();
        }
    }

    function iframe_edit($resource_id, $input_form_id) {
        if (!$this->image_manager_model->is_owner($resource_id)) {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/image_manager'),
                'heading' => 'ระบบผิดพลาด',
                'message' => '<p>ระบบผิดพลาด คุณไม่สามารถแก้ไขข้อมูลชุดนี้ได้</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $this->load->helper('form');
            $this->template->load_showloading();
            $this->template->application_script('resource/image_manager/edit_form.js');
            $data = array(
                //ทั่วไป
                'title' => 'แก้ไขข้อมูลรูปภาพ',
                'form_action' => site_url('resource/image_manager/iframe_do_save/' . $input_form_id),
                'form_data' => $this->image_manager_model->get_image_form_data($resource_id),
                'cancel_link' => site_url('resource/image_manager/iframe/' . $input_form_id),
                'delete_link' => site_url('resource/image_manager/iframe_delete/' . $resource_id),
                'publish_options' => $this->ddoption_model->get_publish_options(),
                'privacy_options' => $this->ddoption_model->get_privacy_options(),
                'degree_options' => $this->ddoption_model->get_degree_id_options(),
                'learning_area_options' => $this->ddoption_model->get_learning_area_options(),
                'ajax_subject_options_url' => site_url('core/ajax_ddoptions/get_subject_options'),
                'ajax_chapter_autocomplete_url' => site_url('core/ajax_autocomplete/get_chapter'),
            );
            $this->template->script_var(
                    array(
                        'ajax_subject_options_url' => site_url('core/ajax_ddoptions/get_subject_options'),
                        'ajax_chapter_options_url' => site_url('core/ajax_ddoptions/get_chapter_options'),
                        'ajax_sub_chapter_autocomplete_url' => site_url('core/ajax_autocomplete/get_sub_chapter'),
                        'subj_id' => $data['form_data']['subj_id'],
                        'chapter_id' => $data['form_data']['chapter_id']
                    )
            );
            $this->template->write_view('resource/image_manager/iframe_edit_form', $data);
            $this->template->temmplate_name('normal');
            $this->template->render();
        }
    }

    function iframe_upload_save($input_form_id) {
        $this->load->model('resource/image_upload_model');
        if ($this->image_upload_model->save($this->input->post('data'), $this->input->post('resume_file'))) {
            $data = array(
                'time' => 0,
                'url' => site_url('resource/image_manager/iframe/' . $input_form_id),
                'heading' => 'upload เสร็จสิ้น',
                'message' => '<p>ผลการ upload เป็นไปได้ด้วยดี</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $data = array(
                'time' => 3,
                'url' => site_url('resource/image_manager/iframe/' . $input_form_id),
                'heading' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล',
                'message' => '<p>โปรดลองใหม่อีกครั้ง</p>'
            );
            $this->load->view('refresh_page', $data);
        }
    }

    function iframe_do_save($input_form_id) {
        if ($this->image_manager_model->save($this->input->post('resource_id'), $this->input->post('data'))) {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/image_manager/iframe/' . $input_form_id),
                'heading' => 'แก้ไขข้อมูลเสร็จสิ้น',
                'message' => '<p>แก้ไขข้อมูลเสร็จสิ้น</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/image_manager/iframe/' . $input_form_id),
                'heading' => 'ระบบผิดพลาด',
                'message' => '<p>ระบบผิดพลาด คุณไม่สามารถแก้ไขข้อมูลชุดนี้ได้</p>'
            );
            $this->load->view('refresh_page', $data);
        }
    }

    function iframe_delete($resource_id, $input_form_id) {
        $data = array(
            'url' => site_url('resource/image_manager/iframe_do_delete/' . $resource_id . '/' . $input_form_id),
            'cancel_url' => site_url('resource/image_manager/iframe/' . $input_form_id),
            'heading' => 'คุณต้องการลบข้อมูล รูปภาพ นี้ใช่ไหม',
            'message' => '<p>หากคุณลบข้อมูล รูปภาพ นี้แล้วระบบจะลบข้อมูล รูปภาพ อย่างถาวร</p>'
        );

        $this->load->view('confirm_page', $data);
    }

    function iframe_do_delete($id, $input_form_id) {
        if ($this->image_manager_model->delete($id)) {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/image_manager/iframe/' . $input_form_id),
                'heading' => 'ทำการลบข้อมูลเสร็จสิ้น',
                'message' => '<p>คุณไม่สามารถเรียกคืน รูปภาพนี้ นี้ได้อีก</p>'
            );
        } else {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/image_manager/iframe/' . $input_form_id),
                'heading' => 'การลบข้อมูลผิดพลาด',
                'message' => '<p>คุณไม่สามารถลบ รูปภาพนี้ </p>'
            );
        }
        $this->load->view('refresh_page', $data);
    }

}