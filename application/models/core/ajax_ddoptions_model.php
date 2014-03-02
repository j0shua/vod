<?php

class ajax_ddoptions_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    function get_subject_options($la_id) {
        $options_render = array('' => '<option value="" ></option>');
        $array_options = array('' => '');
        $this->db->where('la_id', $la_id);
        $this->db->where('(uid_owner=0 or uid_owner=' . $this->auth->uid() . ')', NULL, FALSE);

        $q = $this->db->get('f_subject');
        $num_rows = $q->num_rows();
        if ($num_rows > 0) {

            foreach ($q->result_array() as $v) {
                $options_render[] = '<option value="' . $v['subj_id'] . '" >' . $v['title'] . '</option>';
                $array_options[$v['subj_id']] = $v['title'];
            }
        }
        $options = array(
            'num_rows' => $num_rows,
            'options_render' => implode("\n", $options_render),
            'array_options' => $array_options
        );
        return $options;
    }

    function get_chapter_options($subj_id) {

        $options_render = array('' => '<option value="" ></option>');
        $array_options = array('' => '');
        $this->db->where('subj_id', $subj_id);
        $this->db->where('(uid_owner=0 or uid_owner=' . $this->auth->uid() . ')', NULL, FALSE);

        $q = $this->db->get('f_chapter');
        $num_rows = $q->num_rows();
        if ($num_rows > 0) {

            foreach ($q->result_array() as $v) {
                $options_render[] = '<option value="' . $v['chapter_id'] . '" >' . $v['chapter_title'] . '</option>';
                $array_options[$v['chapter_id']] = $v['chapter_title'];
            }
        }
        $options = array(
            'num_rows' => $num_rows,
            'options_render' => implode("\n", $options_render),
            'array_options' => $array_options
        );
        return $options;
    }

}
