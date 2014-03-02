<?php

/**
 *  @property dycontent_model $dycontent_model
 */
class import_prokru extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth->access_limit($this->auth->permis_resource);
        $this->load->model('resource/dycontent_model');
        
    }

    function import($save = 0, $p = 1, $offset = 10) {

        $start = ($p - 1) * $offset;

        $sql = "SELECT resource_id,resource_title,resource_data
FROM `a_resource`
WHERE `resource_type_id` LIKE '3'
and user_id_create = 3
AND `learning_id_subject`

IN ( 2 )
AND resource_status = \"active\"
LIMIT $start , $offset";
        $q = $this->db->query($sql);

        $a_resource = array();
        foreach ($q->result_array() as $v) {

            $resource_data = $this->decode_resource_data_mc($v['resource_data']);
            if (!$resource_data) {
                continue;
            }
            if ($save) {
                $this->save($v['resource_id']);
            }
            $a_resource[] = array(
                'resource_id' => $v['resource_id'],
                'resource_title' => $v['resource_title'],
                'resource_data' => $resource_data
            );
        }
        echo '<pre>';

        print_r($a_resource);
        echo '</pre>';
    }

    function decode_resource_data_mc($resource_data) {
        $resource_data = unserialize($resource_data);
        $resource_data['question'] = base64_decode($resource_data['question']);
        $search = array('\Tab');
        $replace = array('\indent \hspace{1cm}');

        $resource_data['question'] = str_replace($search, $replace, $resource_data['question']);


// search \includegraphics
//        $mystring = $resource_data['question'];
//        $findme = '\includegraphics';
//        $pos = strpos($mystring, $findme);
//        if ($pos !== false) {
//            return FALSE;
//        }
        $resource_data['solve'] = str_replace($search, $replace, base64_decode($resource_data['solve']));
        foreach ($resource_data['choice'] as $k => $choice) {
            $resource_data['choice'][$k] = str_replace($search, $replace, base64_decode($choice));
        }
        return $resource_data;
    }

    function get_resource_data($resource_id) {
        $sql = "SELECT resource_id,resource_title,resource_data,resource_folder
FROM `a_resource`
WHERE `resource_id` = '$resource_id'";
        $q = $this->db->query($sql);
        $a_resource = array();
        foreach ($q->result_array() as $v) {
            $resource_data = $this->decode_resource_data_mc($v['resource_data']);
            if (!$resource_data) {
                continue;
            }
            $a_resource[] = array(
                'resource_id' => $v['resource_id'],
                'resource_title' => $v['resource_title'],
                'resource_data' => $resource_data,
                'resource_folder' => $v['resource_folder']
            );
        }
        return $a_resource;
    }

    function save($resource_id) {
        $r_data = $this->get_resource_data($resource_id);
        $r_data = $r_data[0];
//        echo '<pre>';
//        print_r($r_data);
//        echo '</pre>';
//        echo $r_data['resource_data']['answer'];
//        exit();
        $desc = array();
        $desc[] = 'from:prokrue.com';
        $desc[] = 'resource_id:' . $resource_id;
        $desc[] = 'resource_folder:' . $r_data['resource_folder'];
        $desc = implode("\n", $desc);
        $data = array(
            "title" => $r_data['resource_title'],
            "desc" => $desc,
            "tags" => "",
            "publish" => 1,
            "privacy" => 1,
            "category_id" => 110,
            "render_type_id" => 1,
            "content_header" => "",
            "content_questions" => array(
                array("content_type_id" => 2,
                    "question" => $r_data['resource_data']['question'],
                    "true_answers" => array($r_data['resource_data']['answer']),
                    "choices" => $r_data['resource_data']['choice'],
                    "solve_answer" => $r_data['resource_data']['solve']
                )
            )
        );
        //  print_r($data);
        // exit();
        $this->dycontent_model->save($data);
//        $refresh_data = array(
//            'time' => 1,
//            'url' => site_url('resource/dycontent'),
//            'heading' => 'บันทึกข้อมูลเสร็จสิ้น',
//            'message' => '<p>บันทึกข้อมูลเสร็จสิ้น</p>'
//        );
//        $this->load->view('refresh_page', $refresh_data);
    }

    function get_subject_name() {
        
    }

}