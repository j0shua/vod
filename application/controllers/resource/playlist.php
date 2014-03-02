<?php

/**
 * Description of playlist
 *
 * @author lojorider
 * @property playlist_model $playlist_model
 */
class playlist extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }
    function index(){
        
    }
    function add(){
        $this->template->load_jquery();
        $this->template->load_jquery_ui();
        $this->template->write_view('resource/playlist_add');
        $this->template->render();
    }
    

}