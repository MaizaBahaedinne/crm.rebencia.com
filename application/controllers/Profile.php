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
    if(!isset($this->session)) { $this->load->library('session'); }
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
                    'biography' => $profile['biography'],
                    'description' => $profile['description'] ?? $profile['biography']
                ]
            ];
        } else {
            $fallback = $this->user_model->get_wp_user($this->vendorId);
            if($fallback){
                $data['user'] = [
                    'id' => $fallback->user_id ?? $this->vendorId,
                    'login' => $fallback->user_login ?? '',
                    'email' => $fallback->user_email ?? '',
                    'name' => $fallback->display_name ?? ($fallback->user_login ?? ''),
                    'roles' => isset($fallback->roles)? $fallback->roles : [],
                    'mobile' => '',
                    'location' => '',
                    'joining_date' => isset($fallback->user_registered)? date('d/m/Y', strtotime($fallback->user_registered)) : '',
                    'agence' => '',
                    'avatar' => base_url('assets/images/users/avatar-1.jpg'),
                    'meta' => [ 'biography' => '', 'description' => '' ]
                ];
            } else {
                $data['user'] = [
                    'id' => $this->vendorId,
                    'login' => '', 'email' => '', 'name' => '', 'roles' => [],
                    'mobile' => '', 'location' => '', 'joining_date' => '', 'agence' => '',
                    'avatar' => base_url('assets/images/users/avatar-1.jpg'),
                    'meta' => ['biography'=>'','description'=>'']
                ];
                $this->session->set_flashdata('error', 'Profil introuvable dans la base WordPress.');
            }
        }

        $this->loadViews('profile/index', $this->global, $data, NULL);
    }
   
}
