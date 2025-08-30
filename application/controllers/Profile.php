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
        if($wpUser){
            $meta = isset($wpUser->meta)? $wpUser->meta : [];
            $data['user'] = [
                'id' => $wpUser->ID,
                'login' => $wpUser->user_login,
                'email' => $wpUser->user_email,
                'name' => $wpUser->display_name,
                'roles' => $wpUser->roles ?? [],
                'avatar' => base_url('assets/images/users/avatar-1.jpg'), // TODO: remonter avatar WP si disponible
                'location' => $meta['billing_city'] ?? ($meta['city'] ?? ''),
                'mobile' => $meta['mobile'] ?? ($meta['billing_phone'] ?? ''),
                'joining_date' => isset($meta['user_registered']) ? date('d/m/Y', strtotime($meta['user_registered'])) : '',
                'agence' => $meta['agency_name'] ?? ''
            ];
        } else {
            $data['user'] = [];
        }

        $this->loadViews('profile/index', $this->global, $data, NULL);
    }
   
}
