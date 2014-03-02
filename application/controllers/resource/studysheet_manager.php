<?php

/**
 * ระบบจัดการชุกการสอน สำหรับครู
 * @property studysheet_manager_model $studysheet_manager_model
 */
class studysheet_manager extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth->access_limit($this->auth->permis_resource);
        $this->load->model('resource/studysheet_manager_model');

        $this->load->model('core/ddoption_model');
    }

    /**
     * จัดการ ชุดการสอน
     */
    function index() {
        $this->load->helper('form');
        $this->template->load_showloading();
        $this->template->load_flexgrid();
        $this->template->application_script('resource/studysheet_manager/main_grid.js');
        $data['add_latex_sheet_link'] = site_url('resource/studysheet_manager/add_latex_sheet');
        $data['main_side_menu'] = $this->load->view('resource/main_side_menu', array('active' => 'studysheet_manager'), TRUE);
        $data['qtype_options'] = array('title' => 'ชื่อเอกสาร', 'ts_id' => 'เลขที่เอกสาร', 'desc' => 'รายละเอียด', 'tags' => 'tags', 'category_id' => 'หมวดหมู่');
        $data['ajax_grid_url'] = site_url('resource/studysheet_manager/ajax_teachset_list');
        $this->template->write_view('resource/studysheet_manager/main_grid', $data);
        $this->template->render();
    }

    function ajax_teachset_list() {
        $a = $this->studysheet_manager_model->find_all($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query', TRUE), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE));
        echo json_encode($a);
    }

    /**
     * เพิ่มชุดการสอน
     */
    function add_latex_sheet() {
        $this->load->helper('form');
        $this->template->load_showloading();
        $this->template->load_markitup();
        $this->template->application_script('resource/studysheet_manager/input_dycontent_form.js');
        $this->template->link('assets/application/xelatex/a4_preview.css');

        $data = array(
            'form_title' => 'สร้างใบงาน LaTex',
            'form_action' => site_url('resource/studysheet_manager/do_save'),
            'resource_data' => array(),
            'resource_type_id_combined' => '1',
            'content_type_id_combined' => '1',
            'cancel_link' => site_url('resource/studysheet_manager'),
            'publish_options' => $this->ddoption_model->get_publish_options(),
            'privacy_options' => $this->ddoption_model->get_privacy_options(),
            'category_options' => $this->ddoption_model->get_category_array_options(),
            'ajax_preview_url' => site_url('resource/studysheet_manager/ajax_sheet_preview'),
            'image_browser_iframe_url' => site_url('resource/image_browser/iframe/'),
            'ajax_li_resource_url' => site_url('resource/studysheet_manager/ajax_li_resource'),
        );
        $data['resource_data'] = $this->studysheet_manager_model->get_resource_data();
        $this->template->write_view('resource/studysheet_manager/input_dycontent_form', $data);
        $this->template->render();
    }

    function ajax_li_resource() {
        $value = array('status' => TRUE);
        $a_resource_id = explode(',', $this->input->post("resource_id"));
        $li_data['resources'] = array();
        foreach ($a_resource_id as $resource_id) {
            $li_data['resources'][] = array(
                'resource_id' => $resource_id,
                'title' => $resource_id
            );
        }

        $value['li'] = $this->load->view('resource/studysheet_manager/li_resource', $li_data, TRUE);
        echo json_encode($value);
    }

    function ajax_sheet_preview() {
        $value = array();
        switch ($this->input->post('render_type_id')) {
            case 1:
                $value = $this->latex_preview();
                break;
            case 2:
                $value = $this->html_preview();

                break;
            case 3:
                $value = $this->bbcode_preview();
                break;
            default:
                break;
        }
        echo json_encode($value);
    }

    function latex_preview() {
        $this->load->model('resource/xelatex_studysheet_model');
        $data = $this->input->post('data');
        $c_data = count($data['resources']);
        //echo $c_data;

        $this->xelatex_studysheet_model->init_content($this->input->post('data'));
        $render_result = $this->xelatex_studysheet_model->render();
        $value = array(
            'status' => TRUE,
            'error_msg' => ''
        );
        if (isset($render_result['error_msg'])) {
            $value['status'] = FALSE;
            $value['render'] = $render_result['error_msg'];
        } else {
            $data['render_result'] = $render_result;
            $value['render'] = $this->load->view('/xelatex/a4_preview', $data, TRUE);
        }
        return $value;
    }

    /**
     * เพิ่มชุดการสอน
     */
    function add_html() {
        $this->load->helper('form');
        $data = array(
            'form_title' => 'เพิ่มชุดการสอน',
            'form_action' => site_url('resource/studysheet_manager/do_save'),
            'form_data' => $this->studysheet_manager_model->get_form_data(),
            'cancel_link' => site_url('resource/studysheet_manager'),
            'publish_options' => $this->ddoption_model->get_publish_options(),
            'privacy_options' => $this->ddoption_model->get_privacy_options(),
            'category_options' => $this->ddoption_model->get_category_array_options(),
            'resource_type_id' => '4',
            'content_type_id' => '2',
            'ts_id' => ''
        );
        $this->template->write_view('resource/studysheet_manager/input_dycontent_form', $data);
        $this->template->render();
    }

    /**
     * เพิ่มชุดการสอน
     */
    function add_bbcode() {
        $this->load->helper('form');
        $data = array(
            'form_title' => 'เพิ่มชุดการสอน',
            'form_action' => site_url('resource/studysheet_manager/do_save'),
            'form_data' => $this->studysheet_manager_model->get_form_data(),
            'cancel_link' => site_url('resource/studysheet_manager'),
            'publish_options' => $this->ddoption_model->get_publish_options(),
            'privacy_options' => $this->ddoption_model->get_privacy_options(),
            'category_options' => $this->ddoption_model->get_category_array_options(),
            'resource_type_id' => '4',
            'content_type_id' => '3',
            'ts_id' => ''
        );
        $this->template->write_view('resource/studysheet_manager/input_dycontent_form', $data);
        $this->template->render();
    }

    /**
     * เพิ่มชุดการสอน
     */
    function add_docupload() {
        $this->load->helper('form');
        $data = array(
            'form_title' => 'เพิ่มชุดการสอน',
            'form_action' => site_url('resource/studysheet_manager/do_save'),
            'form_data' => $this->studysheet_manager_model->get_form_data(),
            'cancel_link' => site_url('resource/studysheet_manager'),
            'publish_options' => $this->ddoption_model->get_publish_options(),
            'privacy_options' => $this->ddoption_model->get_privacy_options(),
            'category_options' => $this->ddoption_model->get_category_array_options(),
            'resource_type_id' => '2',
            'ts_id' => ''
        );
        $this->template->write_view('resource/studysheet_manager/input_dycontent_form', $data);
        $this->template->render();
    }

    /**
     * การแก้ไขชุดการสอน
     */
    function edit($ts_td) {
        if (!$this->studysheet_manager_model->is_owner($ts_id)) {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/studysheet_manager'),
                'heading' => 'ระบบผิดพลาด',
                'message' => '<p>ระบบผิดพลาด คุณไม่สามารถแก้ไขข้อมูลชุดนี้ได้</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $this->load->helper('form');
            $data = array(
                'form_title' => 'แก้ไขชุดการสอน',
                'form_action' => site_url('resource/studysheet_manager/do_save'),
                'form_data' => $this->studysheet_manager_model->get_doc_form_data($ts_id),
                'cancel_link' => site_url('resource/studysheet_manager'),
                'publish_options' => $this->ddoption_model->get_publish_options(),
                'privacy_options' => $this->ddoption_model->get_privacy_options(),
                'category_options' => $this->ddoption_model->get_category_array_options(),
            );
            $this->template->write_view('resource/studysheet_manager/input_docupload_form', $data);
            $this->template->render();
        }
    }

    /**
     * บันทึกชุดการสอน
     */
    function save() {
        if ($this->studysheet_manager_model->save($this->input->post('ts_id'), $this->input->post('data'))) {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/studysheet_manager'),
                'heading' => 'แก้ไขข้อมูลเสร็จสิ้น',
                'message' => '<p>แก้ไขข้อมูลเสร็จสิ้น</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/studysheet_manager'),
                'heading' => 'ระบบผิดพลาด',
                'message' => '<p>ระบบผิดพลาด คุณไม่สามารถแก้ไขข้อมูลชุดนี้ได้</p>'
            );
            $this->load->view('refresh_page', $data);
        }
    }

    function delete($ts_id) {
        $data = array(
            'url' => site_url('resource/studysheet_manager/do_delete/' . $ts_id),
            'cancel_url' => site_url('resource/studysheet_manager'),
            'heading' => 'คุณต้องการลบข้อมูล video นี้ใช่ไหม',
            'message' => '<p>หากคุณลบข้อมูล video นี้แล้วระบบจะลบข้อมูล video อย่างถาวร</p>'
        );

        $this->load->view('confirm_page', $data);
    }

}