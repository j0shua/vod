<?php

/**
 * Description of setup
 *
 * @author lojorider
 * @property setup_model $setup_model
 */
class setup extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    function index() {
        $this->set_files_dir();
    }

    function set_files_dir() {
        $path = $this->config->item("full_video_dir");
        $this->recursiveChmod($path, 0644, 0777);
        $path = $this->config->item("full_video_upload_temp_dir");
        $this->recursiveChmod($path, 0644, 0777);
        $path = $this->config->item("full_doc_dir");
        $this->recursiveChmod($path, 0644, 0777);
        $path = $this->config->item("full_doc_upload_temp_dir");
        $this->recursiveChmod($path, 0644, 0777);
        $path = $this->config->item("full_video_thumbnail_dir");
        $this->recursiveChmod($path, 0644, 0777);
    }

    function recursiveChmod($path, $filePerm = 0644, $dirPerm = 0777) {
        if (!file_exists($path)) {
            mkdir($path, $dirPerm, TRUE);
        }
        if (is_file($path)) {
            chmod($path, $filePerm);
        } elseif (is_dir($path)) {
            $foldersAndFiles = scandir($path);
            $entries = array_slice($foldersAndFiles, 2);
            foreach ($entries as $entry) {
                $this->recursiveChmod($path . "/" . $entry, $filePerm, $dirPerm);
            }
            chmod($path, $dirPerm);
        }
        return TRUE;
    }


}