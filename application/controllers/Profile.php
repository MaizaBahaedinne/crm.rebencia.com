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
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('security');
    }

    public function index() {
        $this->isLoggedIn();

        // Récupération fiable de l'identifiant utilisateur
        // BaseController définit vendorId = session->userdata('wp_id') et global['userId'] = vendorId
        $userId = $this->global['userId'] ?? $this->vendorId ?? $this->session->userdata('wp_id');
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
            // Préparer structure minimale pour la vue profile/index
            $data['user'] = [
                'name' => $this->global['name'] ?? 'Utilisateur',
                'user_email' => '',
                'email' => '',
                'mobile' => '',
                'phone' => '',
                'location' => '',
                'bio' => '',
                'user_login' => '',
                'user_registered' => '',
                'user_status' => 'Actif',
                'first_name' => '',
                'last_name' => '',
                'nickname' => '',
                'display_name' => $this->global['name'] ?? 'Utilisateur',
                'description' => '',
                'biography' => '',
                'roles_string' => 'Utilisateur',
            ];
            $this->session->set_flashdata('error', "Profil introuvable.");
        } else {
            $data['user'] = $user; // objet accepté (la vue normalise en tableau)
        }

        $this->loadViews('profile/index_display', $this->global, $data, NULL);
    }
}
