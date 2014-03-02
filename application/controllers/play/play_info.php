<?php

/**
 * @property play_info_model $play_info_model
 *  @property play_video_model $play_video_model
 */
class play_info extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('play/play_info_model');
        $this->load->model('play/play_video_model');
    }

    /**
     * หน้าแรก
     */
    function user_online($width = 180) {
        $this->play_video_model->clear_view_log();
        $data['width'] = $width;
        $data['play_info'] = $this->play_info_model->get_user_view_video_data();
        $this->template->write_view('play/play_info/iframe_user_online', $data);
        $this->template->temmplate_name('normal');
        $this->template->render();
    }

}