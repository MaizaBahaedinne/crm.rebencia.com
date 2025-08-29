<?php
defined('BASEPATH') OR exit('No direct script access allowed');


require APPPATH . '/libraries/BaseController.php';
class Transaction extends BaseController {
    public function __construct() {
        parent::__construct();
        $this->load->model('agent_model');
        $this->load->model('agency_model');
        $this->load->model('activity_model');
        $this->load->library('session');
        $this->load->helper('url');
    }

    public function index() {
        $this->loadViews('transactions/list',  $this->global, NULL, NULL);
    }
    public function sales() {
        $this->loadViews('transactions/sales',  $this->global, NULL, NULL);
    }
    public function rentals() {
        $this->loadViews('transactions/rentals',  $this->global, NULL, NULL);
    }
}
