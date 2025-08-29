<?php
defined('BASEPATH') OR exit('No direct script access allowed');


require APPPATH . '/libraries/BaseController.php';
class Lead extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('agent_model');
        $this->load->model('agency_model');
        $this->load->model('activity_model');
        $this->load->library('session');
        $this->load->helper('url');
    }
    
    public function index() {
        $this->loadViews('leads/list', []);
    }
    public function conversion() {
        $this->loadViews('leads/conversion', []);
    }
    public function followup() {
        $this->loadViews('leads/followup', []);
    }
    public function status() {
        $this->loadViews('leads/status', []);
    }
}
