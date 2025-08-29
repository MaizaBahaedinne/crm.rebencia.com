<?php
defined('BASEPATH') OR exit('No direct script access allowed');


require APPPATH . '/libraries/BaseController.php';
class Settings extends BaseController {
    public function __construct() {
        parent::__construct();
        $this->load->model('agent_model');
        $this->load->model('agency_model');
        $this->load->model('activity_model');
        $this->load->library('session');
        $this->load->helper('url');
    }
    public function roles() {
        $this->loadViews('settings/roles', []);
    }
    public function wordpress() {
        $this->loadViews('settings/wordpress', []);
    }
    public function crm() {
        $this->loadViews('settings/crm', []);
    }
}
