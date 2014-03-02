<?php

/**
 * Description of ztatic
 *
 * @author lojoriderrefresh
 * @property search_user_model $search_user_model

 */
class search extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
    }

    //===================================================================================================
    //  for Student
    //===================================================================================================
    function teacher() {
        $this->load->model('search/search_user_model');
        $per_page = 20; 
        //$search_var = $this->input->get();
        $t = $this->input->get('t');
        $qtype = $this->input->get('qtype');
        if (!$qtype) {
            $qtype = 'full_name';
        }
        $t = ($t) ? $t : '';
        $current_page = $this->input->get('page');
        if (!$current_page) {
            $current_page = 1;
        }
        $data['teacher_data'] = $this->search_user_model->find_all_teacher($current_page, $qtype, $t, $per_page, 'uid', 'desc');
        $data['form_action'] = site_url('search/teacher');
        $data['qtype_options'] = array(
            'full_name' => 'ชื่อครู',
            'school_name' => 'ชื่อโรงเรียน'
        );
        $data['default_qtype'] = $qtype;
        $data['t'] = $t;
        $data['summary_text'] =  'มีครูทั้งหมดจำนวน ' . $data['teacher_data']['total'] . ' คน';;
        if ($data['default_qtype'] == 'school_name') {
            $data['title'] = 'ค้นหาโรงเรียน';
            if ($t != '') {
                $data['summary_text'] = 'ค้นพบครูในโรงเรียน "' . $t . '" จำนวน ' . $data['teacher_data']['total'] . ' คน';
            }
        } else {
            $data['title'] = 'ค้นหาครู';
            if ($t != '') {
                $data['summary_text'] = 'ค้นพบครู จำนวน ' . $data['teacher_data']['total'] . ' คน';
            }
        }




        $pagination_config['base_url'] = site_url('search/teacher?t=' . $t);
        $pagination_config['total_rows'] = $data['teacher_data']['total'];
        $pagination_config['per_page'] = $per_page;
        $pagination_config['current_page'] = $current_page;


        $data['pagination'] = $this->load->view('search/pagination', $pagination_config, TRUE);
        $this->template->script_var(
                array(
                    'ajax_school_name_url' => site_url('core/ajax_autocomplete/get_teacher_school_name'),
                    'ajax_teacher_full_name_url' => site_url('core/ajax_autocomplete/get_teacher_full_name')
                )
        );
        
        $this->template->write_view('search/search_teacher', $data);
        $this->template->render();
    }

}

