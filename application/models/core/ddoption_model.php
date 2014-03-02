<?php

class ddoption_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * get_publish_options
     * ค่า Default ควรเป็น 1
     * @return array option for form_dropdown
     */
    function get_publish_options() { 
        $options = array(
            0 => 'ไม่ให้ใช้',
            1 => 'ใช้ได้'
        );
        return $options;
    }

    /**
     * get_publish_options
     * ค่า Default ควรเป็น 1
     * @return array option for form_dropdown
     */
    function get_taxonomy_publish_options() {
        $options = array(
            0 => 'ไม่แสดง',
            1 => 'เปิดแสดง'
        );
        return $options;
    }

    function get_cmat_id_options() {
        $options = array();
        $this->db->order_by('weight', 'asc');
        $query = $this->db->get('s_command_act_type');

        foreach ($query->result_array() as $v) {

            $options[$v['cmat_id']] = $v['title'];
        }
        return $options;
    }

    /**
     * get_privacy_options
     * ค่า Default ควรเป็น 1
     * @return array option for form_dropdown
     */
    function get_privacy_options() {
        $options = array(
            0 => 'ใช้ส่วนตัวเท่านั้น',
            1 => 'ให้ผู้อื่นใช้ได้ด้วย'
        );
        return $options;
    }

    /**
     * get_privacy_options
     * ค่า Default ควรเป็น 1
     * @return array option for form_dropdown
     */
    function get_weight_options() {
        $rang = range(-120, 120);
        $options = array();
        foreach ($rang as $v) {
            $options[$v] = $v;
        }
        return $options;
    }

    function get_province_options($default = '') {
        $options = array(
            '' => ''
        );
        $this->db->where('is_active', 1);
        $query = $this->db->get('f_province');
        foreach ($query->result_array() as $v) {
            $options[$v['id']] = $v['province_name'];
        }
        return $options;
    }

    function get_school_name_options($default = '') {
        $options = array(
            '' => ''
        );
        //$this->db->where('is_active', 1);
        $this->db->limit(100);
        $query = $this->db->get('f_school');
        foreach ($query->result_array() as $v) {
            $options[$v['school_name']] = $v['school_name'];
        }
        return $options;
    }

    function get_bank_options($default = '') {
        $options = array(
            '' => $default
        );
        //$this->db->where('is_active', 1);
        $this->db->limit(100);
        $query = $this->db->get('f_bank');
        foreach ($query->result_array() as $v) {
            $options[$v['bank_id']] = $v['bank_name'];
        }
        return $options;
    }

    function get_sex_options() {
        $options = array(
            '' => '',
            'หญิง' => 'หญิง',
            'ชาย' => 'ชาย',
        );
        return $options;
    }

    function get_enroll_type_options() {
        $enroll_type_id_options = array(
            1 => 'สมัครแล้วเรียนได้เลย',
            2 => 'สมัครแล้วรอครูอนุญาติ',
            3 => 'สมัครโดยใช้รหัสที่ครูบอก'
        );
        return $enroll_type_id_options;
    }

    function get_enroll_limit_options() {
        $limit = range(1, 20);
        $options = array();
        foreach ($limit as $v) {
            $options[$v * 50] = $v * 50;
        }
        return $options;
    }

    function get_location_type_options() {
        $options = array(
            'ที่สถาบัน' => 'ที่สถาบัน',
            'ที่บ้าน' => 'ที่บ้าน'
        );
        return $options;
    }

    function get_post_tutor_time_limit_options() {
        $options = array(
            15 => '15',
            30 => '30',
            45 => '45',
            60 => '60'
        );
        return $options;
    }

    function get_post_tutor_type_options() {
        $options = array(
            'สอนพิเศษ' => 'สอนพิเศษ',
            'หาครู' => 'หาครู'
        );
        return $options;
    }

    function get_degree_id_options($empty_text = '', $get_long_name = TRUE) {
        $options = array('' => $empty_text);
        $this->db->order_by('weight', 'asc');
        $query = $this->db->get('f_degree');

        foreach ($query->result_array() as $v) {

            $options[$v['degree_id']] = $v['degree_long'];
        }
        return $options;
    }

    function get_learning_area_options() {
        $options = array('' => '');
        $this->db->order_by('title', 'asc');
        $query = $this->db->get('f_c51_learning_area');

        foreach ($query->result_array() as $v) {

            $options[$v['la_id']] = $v['title'];
        }
        return $options;
    }

    function get_unit_price_options() {
        $options = array(
            -1 => 'ค่าบริการปกติ ' . $this->config->item('standard_unit_price') . ' บาท/ชั่วโมง',
            0 => 'ฟรี',
            30 => '30 บาท/ชั่วโมง',
            35 => '35 บาท/ชั่วโมง',
            40 => '40 บาท/ชั่วโมง',
            45 => '45 บาท/ชั่วโมง',
            50 => '50 บาท/ชั่วโมง'
        );

        return $options;
    }

    function get_send_type_options() {
        $options = array();
        $this->db->order_by('weight', 'asc');
        $query = $this->db->get('s_send_type');

        foreach ($query->result_array() as $v) {

            $options[$v['st_id']] = $v['send_type_title'];
        }
        return $options;
    }

    function get_act_type_options() {
        $options = array();
        $this->db->order_by('weight', 'asc');
        $query = $this->db->get('s_act_type');

        foreach ($query->result_array() as $v) {

            $options[$v['at_id']] = $v['title'];
        }
        return $options;
    }

    function get_command_to_resource_options() {
        $options = array(
            'to_publish' => 'ใช้ได้',
            'to_no_publish' => 'ไม่ให้ใช้',
            'to_private' => 'ใช้ส่วนตัวเท่านั้น',
            'to_no_private' => 'ให้ผู้อื่นใช้ได้ด้วย',
            'to_delete' => 'ลบข้อมูล',
        );
        return $options;
    }

    function have_preposttest_options() {
        $options = array(
            0 => 'ไม่มีการสอบ',
            1 => 'มีการสอบ'
        );
        return $options;
    }

}
