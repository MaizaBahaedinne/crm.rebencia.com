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
            // Préparer structure minimale pour la vue (évite notices)
            $data['user'] = [
                'name' => $this->global['name'] ?? 'Utilisateur',
                'user_email' => '',
                'email' => '',
                'mobile' => '',
                'phone' => '',
                'location' => '',
                'bio' => '',
            ];
            $this->global['flash_error'] = "Profil introuvable.";
        } else {
            $data['user'] = $user; // objet accepté (la vue normalise en tableau)
        }

        $this->loadViews('profile/index', $this->global, $data, NULL);
    }
}
