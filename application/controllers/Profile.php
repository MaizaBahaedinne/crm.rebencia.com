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
        // Récupération via vue optimisée si disponible
        $profile = $this->user_model->get_wp_user_profile($this->vendorId);
        if($profile){
            // Adapter aux clés utilisées par la vue (profil)
            $data['user'] = [
                'id' => $profile['id'],
                'login' => $profile['login'],
                'email' => $profile['email'],
                'name' => $profile['display_name'] ?: trim($profile['first_name'].' '.$profile['last_name']),
                'roles' => $profile['roles'],
                'mobile' => $profile['mobile'] ?: $profile['phone'],
                'location' => '', // pas dans la vue pour l’instant
                'joining_date' => $profile['registration_date'] ? date('d/m/Y', strtotime($profile['registration_date'])) : '',
                'agence' => $profile['agency_name'] ?? '',
                'avatar' => base_url('assets/images/users/avatar-1.jpg'),
                'meta' => [
                    'biography' => $profile['biography']
                ]
            ];
        } else {
            $data['user'] = (array)$this->user_model->get_wp_user($this->vendorId);
        }

        $this->loadViews('profile/index', $this->global, $data, NULL);
    }
   
}
