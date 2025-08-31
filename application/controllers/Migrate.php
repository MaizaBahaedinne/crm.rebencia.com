<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migrate extends CI_Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load->library('migration');
        if ($this->migration->latest() === FALSE) {
            show_error($this->migration->error_string());
            return;
        }
        $version = $this->config->item('migration_version');
        echo 'Migrations appliquées avec succès. Version actuelle : '.$version;
    }
}
