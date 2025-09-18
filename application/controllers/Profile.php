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
        $this->load->library('form_validation');
        $this->load->library('input');
        $this->load->library('security');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('security');
    }

    public function index() {
        $this->isLoggedIn();

        // Récupération fiable de l'identifiant utilisateur
        $userId = $this->global['userId'] ?? $this->vendorId ?? $this->session->userdata('userId');
        if (empty($userId)) {
            // Pas d'ID -> retour login
            redirect('login');
            return;
        }
        // Synchroniser global si manquant
        $this->global['userId'] = $userId;

        // Récupération du profil WP
        $user = $this->user_model->get_wp_user($userId);

        if (empty($user)) {
            // Préparer structure minimale pour la vue users/profile
            $userInfo = new stdClass();
            $userInfo->userId = $userId;
            $userInfo->name = $this->global['name'] ?? 'Utilisateur';
            $userInfo->email = '';
            $userInfo->mobile = '';
            $userInfo->roleId = 1;
            $userInfo->role = 'Utilisateur';
            
            $data['userInfo'] = $userInfo;
            $data['active'] = 'details';
            $this->global['flash_error'] = "Profil introuvable.";
        } else {
            // Transformer l'objet user en format compatible avec la vue users/profile
            $userInfo = new stdClass();
            $userInfo->userId = $user->ID ?? $userId;
            $userInfo->name = $user->display_name ?? $user->user_login ?? 'Utilisateur';
            $userInfo->email = $user->user_email ?? '';
            $userInfo->mobile = $user->phone ?? '';
            $userInfo->roleId = 1; // Par défaut
            $userInfo->role = 'Utilisateur';
            
            $data['userInfo'] = $userInfo;
            $data['active'] = 'details'; // Tab actif par défaut
        }

        $this->loadViews('users/profile', $this->global, $data, NULL);
    }
}
