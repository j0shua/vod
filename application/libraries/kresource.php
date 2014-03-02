<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class CI_kresource {

    //หลัก =======================================
    private $CI;

    function __construct() {
        $this->CI = & get_instance();
    }

    function encode_resource($data=array(), $resource_type_id='') {
        $value = '';
        switch ($resource_type_id) {
            case '1': // หัว
                $value = $this->encode_questionhead($data);
                break;
            case '2': // เนื้อหา
                $value = $this->encode_lecture($data);
                break;
            case '3': // เลือก
                $value = $this->encode_multiplechoice($data);
                break;
            case '4': //  เติมคำ
                $value = $this->encode_close_test($data);
                break;
            default:
                break;
        }
        return $value;
    }

    private function encode_questionhead($data) {
        $encode_data['resource_type_id'] = '1';
        $encode_data['question_head'] = trim(base64_encode($data['question_head']), '=');
        if (isset($data['question_head_detail'])) {
            $decode_data['question_head_detail'] = $data['question_head_detail'];
        } else {
            $decode_data['question_head_detail'] = array();
        }
        $encode_data = serialize($encode_data);
        return $encode_data;
    }

    private function encode_lecture($data) {
        $encode_data['resource_type_id'] = '2';
        $encode_data['lecture'] = trim(base64_encode($data['lecture']), '=');
        $encode_data = serialize($encode_data);
        return $encode_data;
    }

    private function encode_multiplechoice($data) {
        $encode_data['resource_type_id'] = '3';
        $encode_data['question'] = base64_encode($data['question']);
        $encode_data['answer'] = $data['answer'];
        $encode_data['solve'] = base64_encode($data['solve']);
        foreach ($data['choice'] as $k => $v) {
            $encode_data['choice'][$k] = base64_encode($v);
        }

        $encode_data = serialize($encode_data);
        return $encode_data;
    }

    private function encode_close_test($data) {
        $encode_data['resource_type_id'] = '4';
        $encode_data['question'] = base64_encode($data['question']);
        $encode_data['answer'] = $data['answer'];
        $encode_data['solve'] = base64_encode($data['solve']);
        $encode_data = serialize($encode_data);
        return $encode_data;
    }

// ======================================================================
    function decode_resource($data) {

        $data = unserialize($data);

        if (isset($data['resource_type_id'])) {
            $resource_type_id = $data['resource_type_id'];
        } else {
            $resource_type_id = $data['resource_type'];
        }

        switch ($resource_type_id) {
            case '1':
                $value = $this->decode_questionhead($data);
                break;
            case '2':
                $value = $this->decode_lecture($data);
                break;
            case '3':
                $value = $this->decode_multiplechoice($data);
                break;
            case '4':
                $value = $this->decode_close_test($data);
                break;
            default:
                break;
        }
        return $value;
    }

    private function decode_questionhead($data) {
        $decode_data['resource_type_id'] = '1';
        $decode_data['question_head'] = base64_decode($data['question_head']);
        if (isset($data['question_head_detail'])) {
            $decode_data['question_head_detail'] = $data['question_head_detail'];
        } else {
            $decode_data['question_head_detail'] = array();
        }
        return $decode_data;
    }

    private function decode_lecture($data) {
        $decode_data['resource_type_id'] = '2';
        $decode_data['lecture'] = base64_decode($data['lecture']);
        return $decode_data;
    }

    private function decode_multiplechoice($data) {
        $encode_data['resource_type_id'] = '3';
        $encode_data['question'] = base64_decode($data['question']);
        $encode_data['answer'] = $data['answer'];
        $encode_data['solve'] = base64_decode($data['solve']);
        foreach ($data['choice'] as $k => $v) {
            $encode_data['choice'][$k] = base64_decode($v);
        }

        return $encode_data;
    }

    private function decode_close_test($data) {
        $encode_data['resource_type_id'] = '4';
        $encode_data['question'] = base64_decode($data['question']);
        $encode_data['answer'] = $data['answer'];
        $encode_data['solve'] = base64_decode($data['solve']);

        return $encode_data;
    }

}
