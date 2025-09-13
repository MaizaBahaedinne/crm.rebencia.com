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

    // Liste des agents
    public function index() {
        $this->isLoggedIn();
        
        // Charger le helper avatar
        $this->load->helper('avatar');
        
        // Préparer les données pour la vue
        $data = $this->global;
        $data['pageTitle'] = 'Liste des agents';
        $data['filters'] = $_GET;
        
        try {
            // Récupérer tous les agents avec leurs informations complètes
            $agents = $this->agent_model->get_all_agents($data['filters']);
            
            // Ajouter le nombre de propriétés pour chaque agent
            foreach ($agents as $agent) {
                if (!isset($agent->properties_count) || $agent->properties_count === null) {
                    $properties = $this->agent_model->get_agent_properties_enhanced($agent->agent_id, $agent->agent_email);
                    $agent->properties_count = count($properties);
                }
            }
            
            $data['agents'] = $agents;
            
        } catch (Exception $e) {
            log_message('error', 'Error in Agent index: ' . $e->getMessage());
            $data['agents'] = [];
            $data['error'] = 'Erreur lors du chargement des agents.';
        }
        
        // Récupérer la liste des agences pour les filtres
        $data['agencies'] = $this->agency_model->get_all_agencies();
        
        // Charger la vue
        $this->loadViews("dashboard/agents/index", $this->global, $data, NULL);
    }

    // Détails d'un agent
    public function view($user_id = null) {
        $this->isLoggedIn();
        
        // Charger le helper avatar
        $this->load->helper('avatar');
        
        if (!$user_id) {
            show_404();
            return;
        }

        $data = $this->global;
        $data['pageTitle'] = 'Profil Agent';

        try {
            // Récupérer les données de l'agent
            $agent = $this->agent_model->get_agent($user_id);
            
            if (!$agent) {
                show_404();
                return;
            }

            // Récupérer les propriétés de l'agent
            $properties = $this->agent_model->get_agent_properties_enhanced($agent->agent_id, $agent->agent_email);
            
            // Données temporaires pour éviter les erreurs
            $estimations = [];
            $transactions = [];

            $data['agent'] = $agent;
            $data['properties'] = $properties;
            $data['estimations'] = $estimations;
            $data['transactions'] = $transactions;

        } catch (Exception $e) {
            log_message('error', 'Error in Agent view: ' . $e->getMessage());
            show_404();
            return;
        }

        $this->loadViews("dashboard/agents/view", $this->global, $data, NULL);
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

    // Informations d'un agent spécifique
    public function fix_data($user_id = null) {
        // Méthode pour corriger des données si nécessaire
        if ($user_id) {
            // Logique de correction
        }
    }

    public function get_properties_details($user_id) {
        // Récupérer les détails des propriétés d'un agent
    }

    public function get_estimations_details($user_id) {
        // Récupérer les détails des estimations d'un agent
    }

    public function get_transactions_details($user_id) {
        // Récupérer les détails des transactions d'un agent
    }

    public function get_contacts_details($user_id) {
        // Récupérer les détails des contacts d'un agent
    }

    public function reset_contacts_count($user_id) {
        // Réinitialiser le compteur de contacts d'un agent
    }
}
?>
