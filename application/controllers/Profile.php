<?php
defined('BASEPATH') OR exit('No direct script access allowed');


require APPPATH . '/libraries/BaseController.php';

/**
 * @property User_model $user_model
 */
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
        $this->isLoggedIn();
       
        $wpUser = $this->user_model->get_wp_user($this->vendorId,'wp_Hrg8P_');
        $data['user'] = $this->user_model->get_wp_user($this->vendorId,'wp_Hrg8P_');

        $this->loadViews('profile/index', $this->global, $data, NULL);
    }
   
}
