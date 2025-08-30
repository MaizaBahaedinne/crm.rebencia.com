<?php
defined('BASEPATH') OR exit('No direct script access allowed');


require APPPATH . '/libraries/BaseController.php';
class Profile extends BaseController {
    public function __construct() {
        parent::__construct();
        $this->load->model('agent_model');
        $this->load->model('agency_model');
        $this->load->model('user_model');
        $this->load->library('session');
        $this->load->helper('url');
    }

    public function index() {
        $data = [];
        $data["userInfo"] = $this->session->userdata('wp_user');
        $data["active"] = isset($active) ? $active : null;
        $this->loadViews('profile/index', $this->global, $data, NULL);
    }
   
}
