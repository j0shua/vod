<?php

/**
 * Description of dycontent
 *
 * @author lojorider
 * @property dycontent_model $dycontent_model
 * @property xelatex_preview_model $xelatex_preview_model
 * @property  xelatex_dycontent_model $xelatex_dycontent_model
 */
class dycontent extends CI_Controller {

    var $content_type_options = array(
        '' => 'ทุกแบบ',
        1 => 'เนื้อหา',
        2 => 'โจทย์ตัวเลือก 1 คำตอบ(mc)',
        3 => 'โจทย์ตัวเลือกหลายคำตอบ(mcma)',
        4 => 'โจทย์เติมคำ(ct)',
//            5 => 'โจทย์เติมคำหลายคำตอบ(ctma)'
    );

    function __construct() {
        parent::__construct();
        $this->auth->access_limit($this->auth->permis_resource);
        $this->load->model('resource/dycontent_model');
        $this->load->model('core/ddoption_model');
        $this->load->model('main_side_menu_model');
        $this->load->helper('form');
    }

    /**
     * แสดง grid สำหรับ สื่อที่เป็นแบบแก้ไข้ได้
     */
    function index() {
        $data['title'] = 'โจทย์/เนื้อหา';
        $data['content_type_options'] = $this->content_type_options;
        $data['command_to_resource_options'] = $this->ddoption_model->get_command_to_resource_options();
        $data['qtype_options'] = array(
            'title' => 'ชื่อสื่อ',
            'resource_id' => 'เลขที่สื่อ',
            'desc' => 'รายละเอียด',
            'tags' => 'ป้ายกำกับ',
            'subject_title' => 'วิชา',
            'chapter_title' => 'บทเรียน',
            'sub_chapter_title' => 'ตอน'
        );
        $data['add_question_url'] = site_url('resource/dycontent/add_question_xelatex');
        $data['grid_menu'] = array(
            array('url' => "#", 'title' => 'เพิ่มโจทย์', 'extra' => 'id="btnaddquestion"'),
            // array('url' => site_url('resource/dycontent/add_question_xelatex/2'), 'title' => 'เพิ่มโจทย์', 'extra' => ''),
//            array('url' => site_url('resource/dycontent/add_question_xelatex/4'), 'title' => 'เพิ่มโจทย์เติมคำ', 'extra' => ''),
//            array('url' => site_url('resource/dycontent/add_question_xelatex/3'), 'title' => 'เพิ่มโจทย์หลายคำตอบ', 'extra' => ''),
            array('url' => site_url('resource/dycontent/add_material_xelatex'), 'title' => 'เพิ่มเนื้อหา', 'extra' => ''),
        );
        $this->template->script_var(array(
            'ajax_act_resource_url' => site_url('resource/dycontent/ajax_act_resource'),
            'ajax_grid_url' => site_url('resource/dycontent/ajax_dycontent_list')
        ));
        $data['main_side_menu'] = $this->main_side_menu_model->resource('dycontent');
        $this->template->application_script('resource/dycontent/main_grid.js');
        $this->template->write_view('resource/dycontent/main_grid', $data);
        $this->template->load_showloading();
        $this->template->load_flexgrid();
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
                    $a_result['status'] = $this->dycontent_model->delete($a_resource_id);
                    break;
                case 'to_private':
                    $a_result['status'] = $this->dycontent_model->privacy($a_resource_id, 0);
                    break;
                case 'to_no_private':
                    $a_result['status'] = $this->dycontent_model->privacy($a_resource_id, 1);
                    break;
                case 'to_publish':
                    $a_result['status'] = $this->dycontent_model->publish($a_resource_id, 1);
                    break;
                case 'to_no_publish':
                    $a_result['status'] = $this->dycontent_model->publish($a_resource_id, 0);
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

    /**
     * ดึงข้อมูล แบบ ajax
     */
    function ajax_dycontent_list() {
        $a = $this->dycontent_model->find_all($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query', TRUE), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE));
        echo json_encode($a);
    }

    /**
     * เพิ่มข้อสอบใน 1 สื่อ
     */
    function ajax_add_question() {
        $tab_subfix = microtime(TRUE) * 10000; //สร้าง id ของ tab ที่ไม่ซ้ำกัน
        $content_type_id = $this->input->post('content_type_id');
        switch ($content_type_id) {
            case 2:case 3: //MC และ MCMA
                $choices = array();
                foreach (range(1, $this->input->post('choice_num')) as $v) {
                    $choices[] = '';
                }
                $data = array(
                    'tab_subfix' => $tab_subfix,
                    'question' => '',
                    'true_answers' => array(),
                    'choices' => $choices,
                    'solve_answer' => '',
                    'content_type_id' => $content_type_id
                );
                $a = array(
                    'status' => true,
                    'render' => $this->load->view('resource/dycontent/question_type_2_3_form', $data, TRUE),
                    'tab_subfix' => $tab_subfix
                );
                break;

            case 4: case 5: //CT และ CTMA
                $true_answers = array();
                if ($this->input->post('answer_num')) {
                    foreach (range(1, $this->input->post('answer_num')) as $v) {
                        $true_answers[] = '';
                    }
                } else {
                    $true_answers[] = '';
                }
                $data = array(
                    'tab_subfix' => $tab_subfix,
                    'question' => '',
                    'true_answers' => $true_answers,
                    'solve_answer' => '',
                    'content_type_id' => $content_type_id
                );
                $a = array(
                    'status' => true,
                    'render' => $this->load->view('resource/dycontent/question_type_4_5_form', $data, TRUE),
                    'tab_subfix' => $tab_subfix
                );
                break;
            case 6://จับคู่
                $true_answers = array();
                $choices = array();
                foreach (range(1, $this->input->post('pair_num')) as $v) {
                    $choices[] = '';
                    $true_answers[] = '';
                }
                $data = array(
                    'tab_subfix' => $tab_subfix,
                    'question' => '',
                    'choices' => $choices,
                    'true_answers' => $true_answers,
                    'solve_answer' => '',
                    'answer_sort' => '',
                    'content_type_id' => $content_type_id
                );
                $a = array(
                    'status' => true,
                    'render' => $this->load->view('resource/dycontent/question_type_6_form', $data, TRUE),
                    'tab_subfix' => $tab_subfix
                );
                break;
            default:
                break;
        }
        echo json_encode($a);
    }

    /**
     * การแสดงตัวอย่าง ผ่าน ajax
     */
    function ajax_dycontent_preview() {

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
        $this->load->model('resource/xelatex_dycontent_model');
        //print_r($this->input->post('data'));
        $this->xelatex_dycontent_model->init_content($this->input->post('data'), TRUE);
        $render_result = $this->xelatex_dycontent_model->render();
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

    function html_preview() {
        $value = array(
            'status' => FALSE,
            'error_msg' => 'โปรดเปลี่ยน รูปแบบการแสดงผล เป็น latex',
            'render' => '<h2>โปรดเปลี่ยน รูปแบบการแสดงผล เป็น latex</h2>'
        );
        return $value;
    }

    function bbcode_preview() {
        $value = array(
            'status' => FALSE,
            'error_msg' => 'โปรดเปลี่ยน รูปแบบการแสดงผล เป็น latex',
            'render' => '<h2>โปรดเปลี่ยน รูปแบบการแสดงผล เป็น latex</h2>'
        );
        return $value;
    }

    function add_question_xelatex() {

        $content_type_id = $this->input->post('content_type_id');
        if (!$content_type_id) {
            $content_type_id = '2';
            $choices = array(
                '', '', '', ''
            );
        } else {
            if ($content_type_id == 2 || $content_type_id == 3) {
                $choices = array();
                for ($i = 0; $i < $this->input->post('choice_num'); $i++) {
                    $choices[] = '';
                }
            }
        }
        $this->dycontent_model->init_resource();
        $this->load->helper('form');
        $this->template->load_showloading();
        $this->template->load_jquery_colorbox();
        $this->template->load_markitup_xelatex();
        $this->template->application_script('resource/dycontent/input_question_xelatex_form.js');
        $this->template->link('assets/application/xelatex/a4_preview.css');
        $data = array(
            //ทั่วไป
            'title' => 'เพิ่มโจทย์',
            'form_action' => site_url('resource/dycontent/do_save'),
            'cancel_link' => site_url('resource/dycontent'),
            'delete_link' => site_url('resource/dycontent'),
            'publish_options' => $this->ddoption_model->get_publish_options(),
            'privacy_options' => $this->ddoption_model->get_privacy_options(),
            'degree_options' => $this->ddoption_model->get_degree_id_options(),
            'learning_area_options' => $this->ddoption_model->get_learning_area_options(),
        );
        $this->template->script_var(array(
            'ajax_subject_options_url' => site_url('core/ajax_ddoptions/get_subject_options'),
            'ajax_chapter_options_url' => site_url('core/ajax_ddoptions/get_chapter_options'),
            'ajax_sub_chapter_autocomplete_url' => site_url('core/ajax_autocomplete/get_sub_chapter'),
            'subj_id' => '',
            'chapter_id' => '',
            //dycontent
            'content_type_id' => $content_type_id,
            'image_browser_iframe_url' => site_url('resource/image_manager/iframe'),
            'ajax_preview_content_url' => site_url("resource/dycontent/ajax_dycontent_preview"),
            'ajax_add_question_url' => site_url("resource/dycontent/ajax_add_question")
        ));
        $data['form_data'] = $this->dycontent_model->get_resource_data();
        $data['form_data']['render_type_id'] = 1;
        $tab_subfix = time() . rand(111, 999);
        switch ($content_type_id) {
            case 2:case 3:
                $tab_content_data = array(
                    'tab_subfix' => $tab_subfix,
                    'question' => '',
                    'content_type_id' => $content_type_id,
                    'true_answers' => array(),
                    'solve_answer' => '',
                    'choices' => $choices
                );
                $data['content_questions'] = array(array(
                        'tab_subfix' => $tab_subfix,
                        'tab_content' => $this->load->view('resource/dycontent/question_type_2_3_form', $tab_content_data, TRUE))
                );
                break;
            case 4:case 5:
                $tab_content_data = array(
                    'tab_subfix' => $tab_subfix,
                    'question' => '',
                    'content_type_id' => $content_type_id,
                    'true_answers' => array(0 => ''),
                    'solve_answer' => ''
                );
                $data['content_questions'] = array(array(
                        'tab_subfix' => $tab_subfix,
                        'tab_content' => $this->load->view('resource/dycontent/question_type_4_5_form', $tab_content_data, TRUE))
                );
                break;
            default:
                break;
        }

        $data['form_data']['resource_id_parent'] = 0;
        $this->template->write_view('resource/dycontent/input_question_form', $data);
        $this->template->render();
    }

    function add_material_xelatex() {
        $this->dycontent_model->init_resource();
        $this->load->helper('form');
        $this->template->load_showloading();
        $this->template->load_markitup_xelatex();
        $this->template->load_jquery_colorbox();
        $this->template->application_script('resource/dycontent/input_material_xelatex_form.js');
        $this->template->link('assets/application/xelatex/a4_preview.css');
        $data = array(
            'title' => 'เพิ่มเนื้อหา',
            'content_type_id' => '1',
            'form_data' => array(),
            'form_action' => site_url('resource/dycontent/do_save'),
            'cancel_link' => site_url('resource/dycontent'),
            'delete_link' => site_url('resource/dycontent'),
            'publish_options' => $this->ddoption_model->get_publish_options(),
            'privacy_options' => $this->ddoption_model->get_privacy_options(),
            'degree_options' => $this->ddoption_model->get_degree_id_options(),
            'learning_area_options' => $this->ddoption_model->get_learning_area_options(),
        );

        $this->template->script_var(array(
            'ajax_subject_options_url' => site_url('core/ajax_ddoptions/get_subject_options'),
            'ajax_chapter_options_url' => site_url('core/ajax_ddoptions/get_chapter_options'),
            'ajax_sub_chapter_autocomplete_url' => site_url('core/ajax_autocomplete/get_sub_chapter'),
            'subj_id' => '',
            'chapter_id' => '',
            //dycontent
            'image_browser_iframe_url' => site_url('resource/image_manager/iframe/'),
            'ajax_preview_content_url' => site_url("resource/dycontent/ajax_dycontent_preview")
        ));
        $data['form_data'] = $this->dycontent_model->get_resource_data();
        $this->template->write_view('resource/dycontent/input_material_form', $data);
        $this->template->render();
    }

    function edit($resource_id) {
        $this->dycontent_model->init_resource($resource_id);
        switch ($this->dycontent_model->get_render_type_id()) {
            case 1:
                $this->edit_xelatex($resource_id);
                break;
            case 2:
                break;
            default:
                break;
        }
    }

    function edit_xelatex($resource_id) {
        $content_type_id = $this->dycontent_model->get_content_type_id();
        $this->template->link('assets/application/xelatex/a4_preview.css');
        switch ($content_type_id) {
            case 1:
                $this->load->helper('form');
                $this->template->load_showloading();
                $this->template->load_markitup_xelatex();
                $this->template->application_script('resource/dycontent/input_material_xelatex_form.js');
                $this->template->link('assets/application/xelatex/a4_preview.css');
                $data = array(
                    'title' => 'แก้ไขสื่อ',
                    'content_type_id' => $content_type_id,
                    'form_action' => site_url('resource/dycontent/do_save'),
                    'cancel_link' => site_url('resource/dycontent'),
                    'delete_link' => site_url('resource/dycontent'),
                    'publish_options' => $this->ddoption_model->get_publish_options(),
                    'privacy_options' => $this->ddoption_model->get_privacy_options(),
                    'degree_options' => $this->ddoption_model->get_degree_id_options(),
                    'learning_area_options' => $this->ddoption_model->get_learning_area_options(),
                );
                $data['form_data'] = $this->dycontent_model->get_resource_data();
                $this->template->script_var(array(
                    'ajax_subject_options_url' => site_url('core/ajax_ddoptions/get_subject_options'),
                    'ajax_chapter_options_url' => site_url('core/ajax_ddoptions/get_chapter_options'),
                    'ajax_sub_chapter_autocomplete_url' => site_url('core/ajax_autocomplete/get_sub_chapter'),
                    'subj_id' => $data['form_data']['subj_id'],
                    'chapter_id' => $data['form_data']['chapter_id'],
                    //dycontent
                    'image_browser_iframe_url' => site_url('resource/image_manager/iframe/'),
                    'ajax_preview_content_url' => site_url("resource/dycontent/ajax_dycontent_preview"),
                ));
                $this->template->write_view('resource/dycontent/input_material_form', $data);
                $this->template->render();
                break;
            default:
                $this->load->helper('form');
                $this->template->load_showloading();
                $this->template->load_markitup_xelatex();
                $this->template->application_script('resource/dycontent/input_question_xelatex_form.js');

                $data = array(
                    //ทั่วไป
                    'title' => 'แก้ไขโจทย์',
                    'content_type_id' => $content_type_id,
                    'form_action' => site_url('resource/dycontent/do_save'),
                    'cancel_link' => site_url('resource/dycontent'),
                    'delete_link' => site_url('resource/dycontent'),
                    'publish_options' => $this->ddoption_model->get_publish_options(),
                    'privacy_options' => $this->ddoption_model->get_privacy_options(),
                    'degree_options' => $this->ddoption_model->get_degree_id_options(),
                    'learning_area_options' => $this->ddoption_model->get_learning_area_options(),
                );
                $data['form_data'] = $this->dycontent_model->get_resource_data();
                $this->template->script_var(array(
                    'ajax_subject_options_url' => site_url('core/ajax_ddoptions/get_subject_options'),
                    'ajax_chapter_options_url' => site_url('core/ajax_ddoptions/get_chapter_options'),
                    'ajax_sub_chapter_autocomplete_url' => site_url('core/ajax_autocomplete/get_sub_chapter'),
                    'subj_id' => $data['form_data']['subj_id'],
                    'chapter_id' => $data['form_data']['chapter_id'],
                    //dycontent
                    'image_browser_iframe_url' => site_url('resource/image_manager/iframe/'),
                    'ajax_preview_content_url' => site_url("resource/dycontent/ajax_dycontent_preview"),
                    'ajax_add_question_url' => site_url("resource/dycontent/ajax_add_question")
                ));
                $content_questions = $data['form_data']['data']['content_questions'];
                unset($data['form_data']['data']['content_questions']);
                foreach ($content_questions as $q) {
                    $tab_subfix = time() . rand(111, 999);
                    switch ($q['content_type_id']) {
                        case 2: case 3:

                            $data['content_questions'][] = array(
                                'tab_subfix' => $tab_subfix,
                                'tab_content' => $this->load->view('resource/dycontent/question_type_2_3_form', array('tab_subfix' => $tab_subfix, 'question' => $q['question'], 'true_answers' => $q['true_answers'], 'choices' => $q['choices'], 'solve_answer' => $q['solve_answer'], 'content_type_id' => $q['content_type_id']), TRUE)
                            );
                            break;
                        case 4:case 5:
                            $data['content_questions'][] = array(
                                'tab_subfix' => $tab_subfix,
                                'tab_content' => $this->load->view('resource/dycontent/question_type_4_5_form', array('tab_subfix' => $tab_subfix, 'question' => $q['question'], 'true_answers' => $q['true_answers'], 'solve_answer' => $q['solve_answer'], 'content_type_id' => $q['content_type_id']), TRUE)
                            );
                            break;
                        default :
                    }
                }
                $data['form_data']['resource_id_parent'] = 0;
                $this->template->write_view('resource/dycontent/input_question_form', $data);
                $this->template->render();
                break;
        }
    }

    function do_save() {
        $data = $this->input->post('data');
        // print_r($data);
        // exit();
        $this->dycontent_model->save($data);
        $refresh_data = array(
            'time' => 1,
            'url' => site_url('resource/dycontent'),
            'heading' => 'บันทึกข้อมูลเสร็จสิ้น',
            'message' => '<p>บันทึกข้อมูลเสร็จสิ้น</p>'
        );
        $this->load->view('refresh_page', $refresh_data);
    }

    function delete($resource_id) {
        $data = array(
            'url' => site_url('resource/dycontent/do_delete/' . $resource_id),
            'cancel_url' => site_url('resource/dycontent'),
            'heading' => 'คุณต้องการลบข้อมูลสื่อนี้ใช่ไหม',
            'message' => '<p>หากคุณลบข้อมูลสื่อนี้แล้ว จะไม่สามารถเรียกคืนได้อีก</p>'
        );

        $this->load->view('confirm_page', $data);
    }

    function do_delete($resource_id) {
        if ($this->dycontent_model->delete($resource_id)) {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/dycontent'),
                'heading' => 'ทำการลบข้อมูลเสร็จสิ้น',
                'message' => '<p>คุณไม่สามารถเรียกคืน สื่อนี้ นี้ได้อีก</p>'
            );
        } else {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/dycontent'),
                'heading' => 'การลบข้อมูลผิดพลาด',
                'message' => '<p>คุณไม่สามารถลบ สื่อนี้ </p>'
            );
        }
        $this->load->view('refresh_page', $data);
    }

// second dycontent ==============================================================
    function second_dycontent($resource_id_parent) {

        $this->template->load_showloading();
        $this->template->load_flexgrid();
        $this->template->application_script('resource/dycontent/second_grid.js');

        $data['title'] = 'โจทย์/เนื้อหา [เทียบ]';
        $data['grid_menu'] = array(
            array('url' => site_url('resource/dycontent/'), 'title' => '<', 'extra' => ''),
            array('url' => site_url('resource/dycontent/add_second_question_xelatex/' . $resource_id_parent), 'title' => 'เพิ่มโจทย์', 'extra' => ''),
        );

        $data['content_type_options'] = $this->content_type_options;
        $data['qtype_options'] = array('title' => 'ชื่อสื่อ', 'resource_id' => 'เลขที่สื่อ', 'desc' => 'รายละเอียด', 'tags' => 'ป้ายกำกับ');
        $data['command_to_resource_options'] = $this->ddoption_model->get_command_to_resource_options();
        $this->template->script_var(array(
            'ajax_act_resource_url' => site_url('resource/dycontent/ajax_act_resource'),
            'ajax_grid_url' => site_url('resource/dycontent/ajax_second_dycontent_list/' . $resource_id_parent)
        ));
        $data['main_side_menu'] = $this->main_side_menu_model->resource('dycontent');
        $this->template->write_view('resource/dycontent/second_grid', $data);
        $this->template->render();
    }

    function ajax_second_dycontent_list($resource_id_parent) {
        $a = $this->dycontent_model->second_find_all($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query', TRUE), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE), $resource_id_parent);
        echo json_encode($a);
    }

    function add_second_question_xelatex($resource_id_parent) {
        $this->dycontent_model->init_resource($resource_id_parent);
        $content_type_id = $this->dycontent_model->get_content_type_id();
        $this->template->link('assets/application/xelatex/a4_preview.css');
        switch ($content_type_id) {
            case 1:
                $this->load->helper('form');
                $this->template->load_showloading();
                $this->template->load_markitup_xelatex();
                $this->template->load_jquery_colorbox();
                $this->template->application_script('resource/dycontent/input_material_xelatex_form.js');
                $this->template->link('assets/application/xelatex/a4_preview.css');
                $data = array(
                    'title' => 'เพิ่มโจทย์เทียบ',
                    'content_type_id' => $content_type_id,
                    'form_action' => site_url('resource/dycontent/do_second_save'),
                    'cancel_link' => site_url('resource/dycontent'),
                    'delete_link' => site_url('resource/dycontent'),
                    'publish_options' => $this->ddoption_model->get_publish_options(),
                    'privacy_options' => $this->ddoption_model->get_privacy_options(),
                    'degree_options' => $this->ddoption_model->get_degree_id_options(),
                    'learning_area_options' => $this->ddoption_model->get_learning_area_options(),
                        //dycontent
                );
                $data['form_data'] = $this->dycontent_model->get_resource_data();
                $this->template->script_var(array(
                    'ajax_subject_options_url' => site_url('core/ajax_ddoptions/get_subject_options'),
                    'ajax_chapter_options_url' => site_url('core/ajax_ddoptions/get_chapter_options'),
                    'ajax_sub_chapter_autocomplete_url' => site_url('core/ajax_autocomplete/get_sub_chapter'),
                    'subj_id' => $data['form_data']['subj_id'],
                    'chapter_id' => $data['form_data']['chapter_id'],
                    'ajax_preview_content_url' => site_url("resource/dycontent/ajax_dycontent_preview"),
                    'image_browser_iframe_url' => site_url('resource/image_manager/iframe')
                ));
                $this->template->write_view('resource/dycontent/input_material_form', $data);
                $this->template->render();
                break;
            default:
                $this->load->helper('form');
                $this->template->load_showloading();
                $this->template->load_markitup_xelatex();
                $this->template->load_jquery_colorbox();
                $this->template->application_script('resource/dycontent/input_question_xelatex_form.js');

                $data = array(
                    //ทั่วไป
                    'title' => 'เพิ่มโจทย์เทียบ',
                    'content_type_id' => $content_type_id,
                    'form_action' => site_url('resource/dycontent/do_second_save'),
                    'cancel_link' => site_url('resource/dycontent/second_dycontent/' . $resource_id_parent),
                    'delete_link' => site_url('resource/dycontent'),
                    'publish_options' => $this->ddoption_model->get_publish_options(),
                    'privacy_options' => $this->ddoption_model->get_privacy_options(),
                    'degree_options' => $this->ddoption_model->get_degree_id_options(),
                    'learning_area_options' => $this->ddoption_model->get_learning_area_options()
                        //dycontent
                );
                $data['form_data'] = $this->dycontent_model->get_resource_data();
                $this->template->script_var(array(
                    'ajax_subject_options_url' => site_url('core/ajax_ddoptions/get_subject_options'),
                    'ajax_chapter_options_url' => site_url('core/ajax_ddoptions/get_chapter_options'),
                    'ajax_sub_chapter_autocomplete_url' => site_url('core/ajax_autocomplete/get_sub_chapter'),
                    'subj_id' => $data['form_data']['subj_id'],
                    'chapter_id' => $data['form_data']['chapter_id'],
                    'ajax_preview_content_url' => site_url("resource/dycontent/ajax_dycontent_preview"),
                    'ajax_add_question_url' => site_url("resource/dycontent/ajax_add_question"),
                    'image_browser_iframe_url' => site_url('resource/image_manager/iframe')
                ));
                $content_questions = $data['form_data']['data']['content_questions'];
                unset($data['form_data']['data']['content_questions']);
                foreach ($content_questions as $q) {
                    $tab_subfix = time() . rand(111, 999);
                    switch ($q['content_type_id']) {
                        case 2: case 3:

                            $data['content_questions'][] = array(
                                'tab_subfix' => $tab_subfix,
                                'tab_content' => $this->load->view('resource/dycontent/question_type_2_3_form', array('tab_subfix' => $tab_subfix, 'question' => $q['question'], 'true_answers' => $q['true_answers'], 'choices' => $q['choices'], 'solve_answer' => $q['solve_answer'], 'content_type_id' => $q['content_type_id']), TRUE)
                            );
                            break;
                        case 4:case 5:
                            $data['content_questions'][] = array(
                                'tab_subfix' => $tab_subfix,
                                'tab_content' => $this->load->view('resource/dycontent/question_type_4_5_form', array('tab_subfix' => $tab_subfix, 'question' => $q['question'], 'true_answers' => $q['true_answers'], 'solve_answer' => $q['solve_answer'], 'content_type_id' => $q['content_type_id']), TRUE)
                            );
                            break;
                        default :
                    }
                }
                $data['form_data']['resource_id_parent'] = $data['form_data']['resource_id'];
                $data['form_data']['resource_id'] = '';
                $this->template->write_view('resource/dycontent/input_question_form', $data);
                $this->template->render();
                break;
        }
    }

    function do_second_save() {
        $data = $this->input->post('data');
        // print_r($data);
        // exit();
        $this->dycontent_model->save($data);
        $refresh_data = array(
            'time' => 1,
            'url' => site_url('resource/dycontent/second_dycontent/' . $data['resource_id_parent']),
            'heading' => 'บันทึกข้อมูลเสร็จสิ้น',
            'message' => '<p>บันทึกข้อมูลเสร็จสิ้น</p>'
        );
        $this->load->view('refresh_page', $refresh_data);
    }

    function second_edit($resource_id) {
        $this->dycontent_model->init_resource($resource_id);
        switch ($this->dycontent_model->get_render_type_id()) {
            case 1:
                $this->second_edit_xelatex($resource_id);
                break;
            case 2:
                break;
            default:
                break;
        }
    }

    function second_edit_xelatex($resource_id) {
        $content_type_id = $this->dycontent_model->get_content_type_id();
        $this->template->link('assets/application/xelatex/a4_preview.css');
        switch ($content_type_id) {

            default:
                $this->load->helper('form');
                $this->template->load_showloading();
                $this->template->load_markitup_xelatex();
                $this->template->application_script('resource/dycontent/input_question_xelatex_form.js');

                $data = array(
                    //ทั่วไป
                    'title' => 'แก้ไขโจทย์ [เทียบ]',
                    'content_type_id' => $content_type_id,
                    'form_action' => site_url('resource/dycontent/do_second_save'),
                    'publish_options' => $this->ddoption_model->get_publish_options(),
                    'privacy_options' => $this->ddoption_model->get_privacy_options(),
                    'degree_options' => $this->ddoption_model->get_degree_id_options(),
                    'learning_area_options' => $this->ddoption_model->get_learning_area_options()
                        //dycontent
                );
                $data['form_data'] = $this->dycontent_model->get_resource_data();
                $this->template->script_var(array(
                    'ajax_preview_content_url' => site_url("resource/dycontent/ajax_dycontent_preview"),
                    'ajax_add_question_url' => site_url("resource/dycontent/ajax_add_question"),
                    'ajax_subject_options_url' => site_url('core/ajax_ddoptions/get_subject_options'),
                    'ajax_chapter_options_url' => site_url('core/ajax_ddoptions/get_chapter_options'),
                    'ajax_sub_chapter_autocomplete_url' => site_url('core/ajax_autocomplete/get_sub_chapter'),
                    'subj_id' => $data['form_data']['subj_id'],
                    'chapter_id' => $data['form_data']['chapter_id'],
                    'image_browser_iframe_url' => site_url('resource/image_manager/iframe')
                ));
                $content_questions = $data['form_data']['data']['content_questions'];
                unset($data['form_data']['data']['content_questions']);
                foreach ($content_questions as $q) {
                    $tab_subfix = time() . rand(111, 999);
                    switch ($q['content_type_id']) {
                        case 2: case 3:

                            $data['content_questions'][] = array(
                                'tab_subfix' => $tab_subfix,
                                'tab_content' => $this->load->view('resource/dycontent/question_type_2_3_form', array('tab_subfix' => $tab_subfix, 'question' => $q['question'], 'true_answers' => $q['true_answers'], 'choices' => $q['choices'], 'solve_answer' => $q['solve_answer'], 'content_type_id' => $q['content_type_id']), TRUE)
                            );
                            break;
                        case 4:case 5:
                            $data['content_questions'][] = array(
                                'tab_subfix' => $tab_subfix,
                                'tab_content' => $this->load->view('resource/dycontent/question_type_4_5_form', array('tab_subfix' => $tab_subfix, 'question' => $q['question'], 'true_answers' => $q['true_answers'], 'solve_answer' => $q['solve_answer'], 'content_type_id' => $q['content_type_id']), TRUE)
                            );
                            break;
                        default :
                    }
                }
                $data['cancel_link'] = site_url('resource/dycontent/second_dycontent/' . $data['form_data']['resource_id_parent']);
                $this->template->write_view('resource/dycontent/input_question_form', $data);
                $this->template->render();
                break;
        }
    }

    function second_delete($resource_id) {
        $data = array(
            'url' => site_url('resource/dycontent/second_do_delete/' . $resource_id),
            'cancel_url' => site_url('resource/dycontent'),
            'heading' => 'คุณต้องการลบข้อมูลสื่อนี้ใช่ไหม',
            'message' => '<p>หากคุณลบข้อมูลสื่อนี้แล้ว จะไม่สามารถเรียกคืนได้อีก</p>'
        );

        $this->load->view('confirm_page', $data);
    }

    function second_do_delete($resource_id) {
        $result = $this->dycontent_model->second_delete($resource_id);
        if ($result['success']) {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/dycontent/second_dycontent/' . $result['resource_id_parent']),
                'heading' => 'ทำการลบข้อมูลเสร็จสิ้น',
                'message' => '<p>คุณไม่สามารถเรียกคืน สื่อนี้ นี้ได้อีก</p>'
            );
        } else {
            $data = array(
                'time' => 1,
                'url' => site_url('resource/dycontent/second_dycontent/' . $result['resource_id_parent']),
                'heading' => 'การลบข้อมูลผิดพลาด',
                'message' => '<p>คุณไม่สามารถลบ สื่อนี้ </p>'
            );
        }
        $this->load->view('refresh_page', $data);
    }

    //=========================================================================
    // Iframe
    //=========================================================================
    function iframe() {
        $user_data = $this->auth->get_user_data();
        $data['owner_full_name'] = $user_data['full_name'];
        $data['content_type_options'] = array(
            '' => 'ทุกแบบ',
            1 => 'เนื้อหา',
            2 => 'โจทย์หนึ่งตัวเลือก(mc)',
            3 => 'โจทย์หลายตัวเลือก(mcma)',
            4 => 'โจทย์เติมคำ(ct)',
//            5 => 'โจทย์เติมคำหลายคำตอบ(ctma)'
        );
        $data['qtype_options'] = array(
            'title' => 'ชื่อสื่อ',
            'resource_id' => 'เลขที่สื่อ',
            'desc' => 'รายละเอียด',
            'tags' => 'ป้ายกำกับ',
            'subject_title' => 'วิชา',
            'chapter_title' => 'บทเรียน'
        );
        $data['resource_level_options'] = array(
            '1' => 'โจทย์หลัก',
            '2' => 'โจทย์เทียบ',
        );
        $this->template->application_script('resource/dycontent/iframe.js');
        $this->template->load_flexgrid();
        $this->template->write_view('resource/dycontent/iframe', $data);
        $this->template->temmplate_name('normal');
        $this->template->script_var(
                array(
                    'ajax_resource_list_url' => site_url('resource/dycontent/ajax_iframe_dycontent_list'),
                    'ajax_teacher_full_name_url' => site_url('core/ajax_autocomplete/get_teacher_full_name_ref_dycontent')
                )
        );
        $this->template->render();
    }

    function ajax_iframe_dycontent_list() {
        $a = $this->dycontent_model->iframe_find_all($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query'), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE));
        echo json_encode($a);
    }

}
