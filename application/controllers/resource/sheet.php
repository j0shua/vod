<?php

/**
 * ระบบจัดการชุกการสอน สำหรับครู -
 * @property sheet_model $sheet_model
 * @property dycontent_model $dycontent_model

 */
class sheet extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth->access_limit($this->auth->permis_resource);
        $this->load->model('resource/sheet_model');

        $this->load->model('core/ddoption_model');
        $this->load->model('main_side_menu_model');
        $this->load->helper('str');
        $this->load->helper('form');
    }

    /**
     * จัดการ ชุดการสอน
     */
    function index() {

        $this->template->load_showloading();
        $this->template->load_flexgrid();
        $this->template->load_jquery_colorbox();
        $this->template->application_script('resource/sheet/main_grid.js');

        $data['title'] = 'ใบงานการสอน';
        $data['grid_menu'] = array(
            array('url' => site_url('resource/sheet/add_latex_sheet'), 'title' => 'เพิ่มใบงาน', 'extra' => '')
        );
        $data['qtype_options'] = array(
            'resource_id' => 'เลขที่ใบงาน',
            //'resource_code' => 'รหัสใบงาน',
            'title' => 'ชื่อใบงาน',
            'desc' => 'รายละเอียด',
            'tags' => 'ป้ายกำกับ',
            'subject_title' => 'วิชา',
            'chapter_title' => 'บทเรียน'
        );
        $data['command_to_resource_options'] = $this->ddoption_model->get_command_to_resource_options();

        $data['main_side_menu'] = $this->main_side_menu_model->study('sheet');
        $this->template->script_var(array(
            'ajax_grid_url' => site_url('resource/sheet/ajax_sheet_list'),
            'ajax_act_resource_url' => site_url('resource/sheet/ajax_act_resource')
        ));
        $this->template->write_view('resource/sheet/main_grid', $data);
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
                    $a_result['status'] = $this->sheet_model->delete($a_resource_id);
                    break;
                case 'to_private':
                    $a_result['status'] = $this->sheet_model->privacy($a_resource_id, 0);
                    break;
                case 'to_no_private':
                    $a_result['status'] = $this->sheet_model->privacy($a_resource_id, 1);
                    break;
                case 'to_publish':
                    $a_result['status'] = $this->sheet_model->publish($a_resource_id, 1);
                    break;
                case 'to_no_publish':
                    $a_result['status'] = $this->sheet_model->publish($a_resource_id, 0);
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

    function ajax_sheet_list() {
        $a = $this->sheet_model->find_all($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query'), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE));
        echo json_encode($a);
    }

    function add_latex_sheet() {
        $resource_data = $this->sheet_model->get_resource_data();
        $resource_data['render_type_id'] = 1;

        $data = array(
            //ทั่วไป
            'title' => 'สร้างใบงาน',
            'form_action' => site_url('resource/sheet/save'),
            'content_type_id_combined' => '1',
            'cancel_link' => site_url('resource/sheet'),
            'publish_options' => $this->ddoption_model->get_publish_options(),
            'privacy_options' => $this->ddoption_model->get_privacy_options(),
            'degree_options' => $this->ddoption_model->get_degree_id_options(),
            'learning_area_options' => $this->ddoption_model->get_learning_area_options(),
        );
        $data['form_data'] = $resource_data;
        $data['pass_score'] = '';
        $this->template->script_var(array(
            'ajax_subject_options_url' => site_url('core/ajax_ddoptions/get_subject_options'),
            'ajax_chapter_options_url' => site_url('core/ajax_ddoptions/get_chapter_options'),
            'ajax_sub_chapter_autocomplete_url' => site_url('core/ajax_autocomplete/get_sub_chapter'),
            'subj_id' => '',
            'chapter_id' => '',
            'ajax_preview_url' => site_url('resource/sheet/ajax_sheet_preview'),
            'image_manager_iframe_url' => site_url('resource/image_manager/iframe/'),
            'resource_iframe_url' => site_url('resource/dycontent/iframe/'),
            'ajax_li_resource_url' => site_url('resource/sheet/ajax_li_resource'),
            'ajax_li_section_url' => site_url('resource/sheet/ajax_li_section'),
            'ajax_pass_score_input_url' => site_url('resource/sheet/ajax_pass_score_input')
        ));
        // gen sheet_set

        $sheet_set = $resource_data['data']['sheet_set'];
        $render_li = array();
        foreach ($sheet_set as $v) {
            $li_data = array();
            if (isset($v['section_title'])) {
                $li_data['resources'][] = array(
                    'section_title' => $v['section_title'],
                    'title' => $v['section_title'],
                    'vspace' => $v['vspace']
                );
                $render_li[] = $this->load->view('resource/sheet/li_section', $li_data, TRUE);
            } else {

                $li_data['resources'][] = array(
                    'resource_id' => $v['resource_id'],
                    'title' => $v['resource_id'],
                    'vspace' => $v['vspace']
                );
                $render_li[] = $this->load->view('resource/sheet/li_resource', $li_data, TRUE);
            }
        }



        $data['render_li'] = $render_li;
        $this->template->write_view('resource/sheet/input_xelatex_dycontent_form', $data);
        $this->template->load_showloading();
        $this->template->load_markitup_xelatex();
        $this->template->application_script('resource/sheet/input_xelatex_dycontent_form.js');
        $this->template->link('assets/application/xelatex/a4_preview.css');
        $this->template->render();
    }

    function ajax_li_section() {
        $value = array('status' => TRUE);
        $li_data['resources'][0]['section_title'] = 'จงตอบคำถามให้ถูกต้อง';
        $li_data['resources'][0]['vspace'] = 0;
        $value['li'] = $this->load->view('resource/sheet/li_section', $li_data, TRUE);
        echo json_encode($value);
    }

    /**
     * เพิ่ม Li ของเอกสาร มี 2 แบบ คือ เนื้อหาและโจทย์
     */
    function ajax_li_resource() {
        $value = array('status' => TRUE);
        $a_resource_id = explode(',', $this->input->post("resource_id"));
        $li_data['resources'] = array();
        $this->load->model("resource/dycontent_model");
        foreach ($a_resource_id as $resource_id) {
            if ($this->dycontent_model->init_resource($resource_id)) {
                $join_video_data = $this->sheet_model->get_join_video_data($resource_id);
                if ($join_video_data) {

                    if ($join_video_data['is_first']) {
                        $play_video_link = array('title' => '► วิดีโอ', 'url' => site_url('v/' . $join_video_data['resource_id_video'] . '?plrid=' . $resource_id));
                    } else {
                        $play_video_link = array('title' => '► วิดีโอแนะแนว', 'url' => site_url('v/' . $join_video_data['resource_id_video'] . '?plrid=' . $resource_id));
                    }
                } else {
                    $play_video_link = FALSE;
                }
                $resource_data = $this->dycontent_model->get_resource_data();
                $resource_data['vspace'] = 0;
                $resource_data['score_pq'] = 1;
                $resource_data['play_video_link'] = $play_video_link;
                $li_data['resources'][] = $resource_data;
            }
        }

        $value['li'] = $this->load->view('resource/sheet/li_resource', $li_data, TRUE);
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

    function pass_score_input_data($data) {

        //$start_with_section = TRUE;
        $result['section_num'] = 0;
        $result['question_num'] = 0;
        $result['status'] = TRUE;

        if (count($data['resources']) > 0) {
            $end_resource = end($data['resources']);
        } else {
            $result['status'] &= FALSE;
            $result['msg'] = 'กรุณาเพิ่มข้อมูลโจทย์หรือเนื้อหาด้วย';
            return $result;
        }
        if (isset($end_resource['section_title'])) {
            $result['status'] &= FALSE;
            $result['msg'] = 'ตำแหน่งตอนอยู่ท้ายสุดไม่ได้ ควรเพิ่มข้อมูลโจทย์ หรือ ย้ายตอนไปไว้ตอนต้นของใบงาน';
            return $result;
        }
        $section_num = -1;
        $section_full_score = 0;

        foreach ($data['resources'] as $r) {
            if (isset($r['section_title'])) {
                if ($result['question_num'] > 0 && $result['section_num'] == 0) {//ตำแหน่งผิด โจทย์ขึ้นก่อน ตอน
                    $result['status'] &= FALSE;
                    $result['msg'] = 'ตำแหน่งตอนต้องอยู่ก่อนโจทย์';
                    return $result;
                }

                $result['section_num']++;
                $section_num++;
                if ($section_num != 0) {
                    $result['section_score'][$section_num - 1] = array(
                        'pass_score' => ceil($section_full_score / 2),
                        'full_score' => $section_full_score
                    );
                }
                $result['section_score'][$section_num] = array();
                $section_full_score = 0;
            } else {
                if (isset($r['num_questions'])) {
                    $result['question_num']++;
                    $section_full_score += $r['score_pq'] * $r['num_questions'];
                }
            }
        }
        if ($section_num == -1) {
            $section_num = 0;
        }
        $result['section_score'][$section_num] = array(
            'pass_score' => ceil($section_full_score / 2),
            'full_score' => $section_full_score
        );
        return $result;
    }

    function ajax_pass_score_input() {
        $post = $this->input->post('data');
        $result = $this->pass_score_input_data($post);
        if ($result['status']) {
            $data = array(
                'section_num' => $result['section_num'],
                'question_num' => $result['question_num'],
                'section_score' => $result['section_score']
            );
            if ($result['status']) {
                $result['render'] = $this->load->view('resource/sheet/pass_score_input', $data, TRUE);
            }
        }

        echo json_encode($result);
    }

    function latex_preview() {

        $this->load->model('resource/xelatex_sheet_model');
        $data = $this->input->post('data');
        $data['uid_owner'] = $this->auth->uid();
        if (!$this->xelatex_sheet_model->init_content($data)) {
            $value['status'] = FALSE;
            $value['render'] = implode('', $this->xelatex_sheet_model->error_msg());
            return $value;
        }
        $render_result = $this->xelatex_sheet_model->render();
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
            'form_action' => site_url('resource/sheet/save'),
            'form_data' => $this->sheet_model->get_form_data(),
            'cancel_link' => site_url('resource/sheet'),
            'publish_options' => $this->ddoption_model->get_publish_options(),
            'privacy_options' => $this->ddoption_model->get_privacy_options(),
            'resource_type_id' => '4',
            'content_type_id' => '2',
            'ts_id' => ''
        );
        $this->template->write_view('resource/sheet/input_dycontent_form', $data);
        $this->template->render();
    }

    /**
     * เพิ่มชุดการสอน
     */
    function add_bbcode() {
        $this->load->helper('form');
        $data = array(
            'form_title' => 'เพิ่มชุดการสอน',
            'form_action' => site_url('resource/sheet/save'),
            'form_data' => $this->sheet_model->get_form_data(),
            'cancel_link' => site_url('resource/sheet'),
            'publish_options' => $this->ddoption_model->get_publish_options(),
            'privacy_options' => $this->ddoption_model->get_privacy_options(),
            'resource_type_id' => '4',
            'content_type_id' => '3',
            'ts_id' => ''
        );
        $this->template->write_view('resource/sheet/input_dycontent_form', $data);
        $this->template->render();
    }

    /**
     * เพิ่มชุดการสอน
     */
    function add_docupload() {
        $this->load->helper('form');
        $data = array(
            'form_title' => 'เพิ่มชุดการสอน',
            'form_action' => site_url('resource/sheet/save'),
            'form_data' => $this->sheet_model->get_form_data(),
            'cancel_link' => site_url('resource/sheet'),
            'publish_options' => $this->ddoption_model->get_publish_options(),
            'privacy_options' => $this->ddoption_model->get_privacy_options(),
            'resource_type_id' => '2',
            'ts_id' => ''
        );
        $this->template->write_view('resource/sheet/input_dycontent_form', $data);
        $this->template->render();
    }

    /**
     * การแก้ไขชุดการสอน
     */
    function edit($resource_id) {
        $resource_data = $this->sheet_model->get_resource_data($resource_id);

        switch ($resource_data['render_type_id']) {
            case 1:
                $this->edit_latex_sheet($resource_data);

                break;

            default:
                break;
        }
    }

    function edit_latex_sheet($resource_data) {

        $this->load->helper('form');
        $this->template->load_showloading();
        $this->template->load_markitup_xelatex();
        $this->template->application_script('resource/sheet/input_xelatex_dycontent_form.js');
        $this->template->link('assets/application/xelatex/a4_preview.css');
        $data = array(
            //ทั่วไป
            'title' => 'แก้ไขใบงาน',
            'form_action' => site_url('resource/sheet/save'),
            'render_type_id' => '1',
            'cancel_link' => site_url('resource/sheet'),
            'publish_options' => $this->ddoption_model->get_publish_options(),
            'privacy_options' => $this->ddoption_model->get_privacy_options(),
            'degree_options' => $this->ddoption_model->get_degree_id_options(),
            'learning_area_options' => $this->ddoption_model->get_learning_area_options(),
                //sheet
        );
        $data['form_data'] = $resource_data;
        $data_section_score = array(
            'section_num' => $resource_data['data']['section_num'],
            'question_num' => $resource_data['data']['question_num'],
            'section_score' => $resource_data['section_score']
        );

        $data['pass_score'] = $this->load->view('resource/sheet/pass_score_input', $data_section_score, TRUE);
        // gen sheet_set
        $sheet_set = $resource_data['data']['sheet_set'];
        foreach ($sheet_set as $v) {
            $li_data = array();
            if (isset($v['section_title'])) {
                $li_data['resources'][] = array(
                    'section_title' => $v['section_title'],
                    'title' => $v['section_title'],
                    'vspace' => $v['vspace']
                );
                $render_li[] = $this->load->view('resource/sheet/li_section', $li_data, TRUE);
            } else {
                $join_video_data = $this->sheet_model->get_join_video_data($v['resource_id']);
                if ($join_video_data) {

                    if ($join_video_data['is_first']) {
                        $play_video_link = array('title' => '► วิดีโอ', 'url' => site_url('v/' . $join_video_data['resource_id_video'] . '?plrid=' . $v['resource_id']));
                    } else {
                        $play_video_link = array('title' => '► วิดีโอแนะแนว', 'url' => site_url('v/' . $join_video_data['resource_id_video'] . '?plrid=' . $v['resource_id']));
                    }
                } else {
                    $play_video_link = FALSE;
                }

                // สร้าง li ของโจทย์
                if ($v['content_type_id'] == 1) {
                    $li_data['resources'][] = array(
                        'resource_id' => $v['resource_id'],
                        'vspace' => $v['vspace'],
                        'content_type_id' => $v['content_type_id'],
                        'num_questions' => $v['num_questions'],
                        'play_video_link' => $play_video_link
                    );
                } else {
                    $li_data['resources'][] = array(
                        'resource_id' => $v['resource_id'],
                        'vspace' => $v['vspace'],
                        'score_pq' => $v['score_pq'],
                        'content_type_id' => $v['content_type_id'],
                        'num_questions' => $v['num_questions'],
                        'play_video_link' => $play_video_link
                    );
                }


                $render_li[] = $this->load->view('resource/sheet/li_resource', $li_data, TRUE);
            }
        }



        $data['render_li'] = $render_li;

        $this->template->script_var(array(
            'ajax_subject_options_url' => site_url('core/ajax_ddoptions/get_subject_options'),
            'ajax_chapter_options_url' => site_url('core/ajax_ddoptions/get_chapter_options'),
            'ajax_sub_chapter_autocomplete_url' => site_url('core/ajax_autocomplete/get_sub_chapter'),
            'subj_id' => $data['form_data']['subj_id'],
            'chapter_id' => $data['form_data']['chapter_id'],
            'ajax_preview_url' => site_url('resource/sheet/ajax_sheet_preview'),
            'image_manager_iframe_url' => site_url('resource/image_manager/iframe/'),
            'resource_iframe_url' => site_url('resource/dycontent/iframe/'),
            'ajax_li_resource_url' => site_url('resource/sheet/ajax_li_resource'),
            'ajax_li_section_url' => site_url('resource/sheet/ajax_li_section'),
            'ajax_pass_score_input_url' => site_url('resource/sheet/ajax_pass_score_input')
        ));
        $this->template->write_view('resource/sheet/input_xelatex_dycontent_form', $data);
        $this->template->render();
    }

    /**
     * บันทึกชุดการสอน
     */
    function save() {
//exit();
        if ($this->sheet_model->save($this->input->post('data'), $this->input->post('resource_id'))) {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/sheet'),
                'heading' => 'บันทึกข้อมูลเสร็จสิ้น',
                'message' => '<p>บันทึกข้อมูลเสร็จสิ้น</p>'
            );
            $this->load->view('refresh_page', $data);
        } else {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/sheet'),
                'heading' => 'ระบบผิดพลาด',
                'message' => '<p>ระบบผิดพลาด คุณไม่สามารถแก้ไขข้อมูลชุดนี้ได้</p>'
            );
            $this->load->view('refresh_page', $data);
        }
    }

    function delete($resource_id) {
        $data = array(
            'url' => site_url('resource/sheet/do_delete/' . $resource_id),
            'cancel_url' => site_url('resource/sheet'),
            'heading' => 'คุณต้องการลบข้อมูล ใบงาน นี้ใช่ไหม',
            'message' => '<p>หากคุณลบข้อมูล ใบงาน นี้แล้วระบบจะลบข้อมูล ใบงาน อย่างถาวร</p>'
        );

        $this->load->view('confirm_page', $data);
    }

    function do_delete($resource_id) {
        if ($this->sheet_model->delete($resource_id)) {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/sheet/'),
                'heading' => 'ทำการลบข้อมูลเสร็จสิ้น',
                'message' => '<p>คุณไม่สามารถเรียกคืน ใบงาน นี้ได้อีก</p>'
            );
        } else {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/sheet/'),
                'heading' => 'การลบข้อมูลผิดพลาด',
                'message' => '<p>คุณไม่สามารถลบ ใบงานนี้ </p>'
            );
        }
        $this->load->view('refresh_page', $data);
    }

    function iframe($input_form_id) {
        $this->load->helper('form');
        $user_data = $this->auth->get_user_data();
        $data['owner_full_name'] = $user_data['full_name'];
        $data['input_form_id'] = $input_form_id;
        $data['upload_url'] = site_url('resource/image_manager/iframe_upload/' . $input_form_id);
        $data['qtype_options'] = array(
            'title' => 'ชื่อใบงาน',
            'resource_id' => 'เลขที่ใบงาน',
            'desc' => 'รายละเอียด',
            'tags' => 'ป้ายกำกับ',
            'subject_title' => 'วิชา',
            'chapter_title' => 'บทเรียน'
        ); 
        $this->template->script_var(
                array(
                    'ajax_grid_url' => site_url('resource/sheet/ajax_iframe_list/' . $input_form_id),
                    'input_form_id' => $input_form_id,
                    'ajax_teacher_full_name_url' => site_url('core/ajax_autocomplete/get_teacher_full_name_ref_sheet')
                )
        );
        $this->template->load_flexgrid();
        $this->template->application_script('resource/sheet/iframe_grid.js');
        $this->template->write_view('resource/sheet/iframe_grid', $data);
        $this->template->temmplate_name('normal');
        $this->template->render();
    }

    function ajax_iframe_list($input_form_id) {
        $a = $this->sheet_model->iframe_find_all($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query'), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE), $input_form_id);
        echo json_encode($a);
    }

}

