<?php

/**
 * โปรแกรมเข้ารหัส ถอดระหัส ข้อมูลเอกสาร
 */
class resource_codec {

    function dycontent_encode($data) {
        return json_encode($data);
    }

    function dycontent_decode($data = array()) {
        if (empty($data)) {
            return array('content_header' => '', 'content_questions' => '');
        } else {

            return json_decode($data, TRUE);
        }
    }

    /**
     * เข้ารหัส เอกสารประกอบการสอน ใบงาน
     * @param type $resources
     * @return type
     */
    function sheet_encode($resources = array()) {
        $sheet_set = array();
        $data['question_num'] = 0;
        $data['material_num'] = 0;
        $data['section_num'] = 0;
        if (count($resources) > 0) {
            foreach ($resources as $resource) {
                if (isset($resource['resource_id'])) {
                    //$dycontent_data = $this->get_dycontent_data($resource['resource_id']);
                    if ($resource['content_type_id'] == 1) {
                        $data['material_num']++;
                    } else {
                        $data['question_num']++;
                    }
                    $sheet_set[] = $resource;
                } else {
                    $data['section_num']++;
                    $sheet_set[] = $resource;
                }
            }
        }
        $data['sheet_set'] = $sheet_set;
        return json_encode($data);
    }

    /**
     * ถอดรหัสเอกสารประกอบการเรียน ใบงาน
     * @param type $data
     * @return type
     */
    function sheet_decode($data = array()) {
        if (empty($data)) {
            $data['sheet_set'] = array();
            $data['question_num'] = 0;
            $data['material_num'] = 0;
            $data['section_num'] = 0;
            return $data;
        } else {

            return json_decode($data, TRUE);
        }
    }

}