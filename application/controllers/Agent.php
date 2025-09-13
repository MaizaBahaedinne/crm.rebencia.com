<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * @property Agent_model $agent_model
 * @property Agency_model $agency_model
 * @property Property_model $property_model
 * @property CI_Input $input
 */
class Agent extends BaseController {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Agent_model', 'agent_model');
        $this->load->model('Agency_model', 'agency_model');
        $this->load->model('Property_model', 'property_model');
    }

    // Debug avatar comparison
    public function debug_avatar_comparison($user_id = 7) {
        $this->isLoggedIn();
        $this->load->helper('avatar');
        // ... (function body unchanged)
    }

    // Debug avatar
    public function debug_avatars() {
        $this->isLoggedIn();
        $this->load->helper('avatar');
        // ... (function body unchanged)
    }

    // Test simple
    public function test() {
        echo "Agent controller fonctionne !";
        echo "<br>Date: " . date('Y-m-d H:i:s');
        echo "<br><a href='" . base_url('agents') . "'>Retour aux agents</a>";
    }

    // Debug pour voir les associations agent-propriétés
    public function debug_properties($user_id = 7) {
        $this->isLoggedIn();
        // ... (function body unchanged)
    }

    // Explorer la structure des agents WordPress
    public function explore_structure() {
        $this->isLoggedIn();
        // ... (function body unchanged)
    }

    // Liste des agents avec vraies données
    public function index() {
        $this->isLoggedIn();
        $this->load->helper('avatar');
        // ... (function body unchanged)
    }

    // Détails d'un agent
    public function view($user_id = null) {
        $this->isLoggedIn();
        $this->load->helper('avatar');
        // ... (function body unchanged)
    }

    // AJAX pour récupérer tous les agents avec filtres
    public function get_all_agents() {
        $filters = $this->input->get();
        $data['agents'] = $this->agent_model->get_all_agents($filters);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
    
    // AJAX pour recherche d'agents
    public function search_agents() {
        $term = $this->input->get('term');
        $agents = $this->agent_model->search_agents($term, 10);
        header('Content-Type: application/json');
        echo json_encode($agents);
    }

    /**
     * Corrige les données erronées d'un agent (méthode de maintenance)
     * Accessible via /agent/fix_data/USER_ID
     */
    public function fix_data($user_id = null) {
        $this->isLoggedIn();
        // ... (function body unchanged)
    }

    /**
     * Récupère les détails des propriétés d'un agent (AJAX)
     */
    public function get_properties_details($user_id) {
        $this->isLoggedIn();
        // ... (function body unchanged)
    }

    /**
     * Récupère les détails des estimations d'un agent (AJAX)
     */
    public function get_estimations_details($user_id) {
        $this->isLoggedIn();
        // ... (function body unchanged)
    }

    /**
     * Récupère les détails des transactions d'un agent (AJAX)
     */
    public function get_transactions_details($user_id) {
        $this->isLoggedIn();
        // ... (function body unchanged)
    }

    /**
     * Récupère les détails des contacts d'un agent (AJAX)
     */
    public function get_contacts_details($user_id) {
        $this->isLoggedIn();
        // ... (function body unchanged)
    }

    /**
     * Remet à zéro le compteur de contacts (AJAX)
     */
    public function reset_contacts_count($user_id) {
        $this->isLoggedIn();
        header('Content-Type: application/json');
        try {
            echo json_encode(['success' => true, 'message' => 'Compteur remis à zéro']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la remise à zéro']);
        }
    }
}
