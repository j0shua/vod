<?php

/**
 * Description of backup_model
 *
 * @author lojorider
 */
class backup_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    function backup_sql() {
        // Load the DB utility class
        $this->load->dbutil();

// Backup your entire database and assign it to a variable
        $backup = & $this->dbutil->backup();

// Load the file helper and write the file to your server
        $this->load->helper('file');
        write_file(FCPATH . 'database.gz', $backup);
    }

}