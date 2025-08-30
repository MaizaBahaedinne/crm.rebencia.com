<?php
defined('BASEPATH') OR exit('No direct script access allowed');


require APPPATH . '/libraries/BaseController.php';

/**
 * @property User_model $user_model
 * @property CI_Session $session
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

        // Ensure userId is set in $this->global from session
        if (!isset($this->global['userId'])) {
            $userId = $this->session->userdata('userId');
            if ($userId) {
                $this->global['userId'] = $userId;
            } else {
                echo "Error: userId is not set in global or session.";
                return;
            }
        }

        $data['user'] = $this->user_model->get_wp_user($this->global['userId']);

        // Debug: Check if user data is returned
        if (empty($data['user'])) {
            echo "No user found for userID: " . $this->global['userId'];
            return;
        }

        echo "userID : " . $this->global['userId'];
        echo '<pre>';
        print_r($data['user']);
        echo '</pre>';
        // $this->loadViews('profile/index', $this->global, $data, NULL);
    }
}
