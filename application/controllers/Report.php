<?php
defined('BASEPATH') OR exit('No direct script access allowed');


require APPPATH . '/libraries/BaseController.php';
class Report extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('agent_model');
        $this->load->model('agency_model');
        $this->load->model('activity_model');
        $this->load->library('session');
        $this->load->helper('url');
    }
    
    public function sales() {
        $this->loadViews('reports/sales', []);
    }
    public function leads() {
        $this->loadViews('reports/leads', []);
    }
    public function agency_performance() {
        $this->loadViews('reports/agency_performance', []);
    }
    public function agency() {
        $this->loadViews('reports/agency', []);
    }
}
