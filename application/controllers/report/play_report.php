<?php

/**
 * @property play_report_model $play_report_model
 */
class play_report extends CI_Controller {

    var $percent_video_owner = 0;
    var $percent_video_share = 0;

    public function __construct() {
        parent::__construct();
        if (!$this->auth->is_superadmin()) {
            $this->auth->access_limit($this->auth->permis_resource);
        }
        $this->load->model('report/play_report_model');
        $this->load->model('core/ddoption_model');
        $this->load->model('admin/admin_menu_model');
        $this->load->model('main_side_menu_model');
        $this->make_money = $this->config->item('make_money');
        $this->percent_video_owner = $this->config->item('percent_video_owner');
        $this->percent_video_share = $this->config->item('percent_video_share');
    }

    function show_all($date = '') {
        $data['title'] = 'รายงานการเข้าชม วิดีโอ';
        $date = explode('-', $date);
        if (count($date) == 2) {
            $data['date_query'] = 'from=' . $date[0] . '&to=' . $date[1];
            $data['date_from_stamp'] = mktime(0, 0, 0, substr($date[0], 4, 2), substr($date[0], 6, 2), substr($date[0], 0, 4));
            $data['date_to_stamp'] = mktime(0, 0, 0, substr($date[1], 4, 2), substr($date[1], 6, 2), substr($date[1], 0, 4));
        } else {
            $data['date_from_stamp'] = mktime(0, 0, 0, date('m'), 1, date('Y'));
            $data['date_to_stamp'] = time();
            $data['date_query'] = 'from=' . date('Ymd', $data['date_from_stamp']) . '&to=' . date('Ymd');
        }
        $data['percent_video'] = $this->percent_video_owner;
        $this->load->helper('form');
        $this->template->load_showloading();
        $this->template->load_flexgrid();

        if ($this->auth->can_access($this->auth->permis_all_view_video_report)) {
            $data['main_side_menu'] = $this->admin_menu_model->main_side_menu('view_video_report');
            $data['title'] = 'รายงานการเข้าชม วิดีโอทั้งหมด';
        } else {
            $data['main_side_menu'] = $this->main_side_menu_model->study('view_video_report');
            $data['title'] = 'รายงานการเข้าชม วิดีโอของตนเอง';
        }
        if ($this->make_money) {
            $this->template->application_script('report/play_report/main_grid.js');
        } else {
            $this->template->application_script('report/play_report/main_grid_free.js');
        }
        $script_var = array(
            'ajax_grid_url' => site_url('report/play_report/ajax_play_report') 
        );
        $this->template->script_var($script_var);
        if ($this->make_money) {
            $this->template->write_view('report/play_report/main_grid', $data);
        } else {
            $this->template->write_view('report/play_report/main_grid_free', $data);
        }
        $this->template->render();
    }

    function ajax_play_report() {
        $a = $this->play_report_model->find_all($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query', TRUE), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE));
        echo json_encode($a);
    }

    function share_show_all($date = '') {
        $data['title'] = 'รายงานการเข้าชม วิดีโอ';
        $date = explode('-', $date);
        if (count($date) == 2) {
            $data['date_query'] = 'from=' . $date[0] . '&to=' . $date[1];
            $data['date_from_stamp'] = mktime(0, 0, 0, substr($date[0], 4, 2), substr($date[0], 6, 2), substr($date[0], 0, 4));
            $data['date_to_stamp'] = mktime(0, 0, 0, substr($date[1], 4, 2), substr($date[1], 6, 2), substr($date[1], 0, 4));
        } else {
            $data['date_from_stamp'] = mktime(0, 0, 0, date('m'), 1, date('Y'));
            $data['date_to_stamp'] = time();
            $data['date_query'] = 'from=' . date('Ymd', $data['date_from_stamp']) . '&to=' . date('Ymd');
        }
        $data['percent_video'] = $this->percent_video_share;
        $this->load->helper('form');
        $this->template->load_showloading();
        $this->template->load_flexgrid();

        if ($this->auth->can_access($this->auth->permis_all_view_video_report)) {
            $data['main_side_menu'] = $this->admin_menu_model->main_side_menu('view_video_report');
            $data['title'] = 'รายงานการเข้าชม วิดีโอทั้งหมด';
        } else {
            $data['main_side_menu'] = $this->main_side_menu_model->study('view_video_report');
            $data['title'] = 'รายงานการเข้าชม วิดีโอของตนเอง';
        }
        if ($this->make_money) {
            $this->template->application_script('report/play_report/main_grid.js');
        } else {
            $this->template->application_script('report/play_report/main_grid_free.js');
        }
        $script_var = array(
            'ajax_grid_url' => site_url('report/play_report/ajax_share_play_report')
        );
        $this->template->script_var($script_var);
        $this->template->write_view('report/play_report/main_grid', $data);
        $this->template->render();
    }

    function ajax_share_play_report() {
        $a = $this->play_report_model->share_find_all($this->input->post('page', TRUE), $this->input->post('qtype', TRUE), $this->input->post('query', TRUE), $this->input->post('rp', TRUE), $this->input->post('sortname', TRUE), $this->input->post('sortorder', TRUE));
        echo json_encode($a);
    }

}