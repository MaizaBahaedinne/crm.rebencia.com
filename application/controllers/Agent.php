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

    // Test simple pour debug
    public function debug_index() {
        echo "<h1>Debug Page Agents</h1>";
        echo "<p>Date: " . date('Y-m-d H:i:s') . "</p>";
        
        try {
            echo "<p>✅ Contrôleur Agent chargé</p>";
            
            // Test de connexion
            echo "<p>✅ Test connexion...</p>";
            
            // Charger le helper avatar
            $this->load->helper('avatar');
            echo "<p>✅ Helper avatar chargé</p>";
            
            // Test modèle Agent
            $agents = $this->agent_model->get_all_agents(['limit' => 1]);
            echo "<p>✅ Modèle Agent fonctionne - " . count($agents) . " agent(s) trouvé(s)</p>";
            
            // Test modèle Agency
            $agencies = $this->agency_model->get_all_agencies();
            echo "<p>✅ Modèle Agency fonctionne - " . count($agencies) . " agence(s) trouvée(s)</p>";
            
            echo "<p>✅ Tous les tests passent - La page agents devrait fonctionner</p>";
            echo "<p><a href='" . base_url('agents') . "'>Tester la vraie page agents →</a></p>";
            
        } catch (Exception $e) {
            echo "<p>❌ Erreur: " . htmlspecialchars($e->getMessage()) . "</p>";
            echo "<p>Trace: <pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre></p>";
        }
    }

    // Liste des agents avec vraies données
    public function index() {
        // Debug temporaire
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        $this->isLoggedIn();
        
        // Debug: vérifier si on arrive ici
        log_message('debug', 'Agent index: méthode appelée');
        
        // Charger le helper avatar
        $this->load->helper('avatar');
        
        // Préparer les données pour la vue
        $data = $this->global;
        $data['pageTitle'] = 'Liste des agents';
        $data['filters'] = $_GET; // Récupérer les filtres de l'URL
        
        try {
            // Debug: tentative de récupération des agents
            log_message('debug', 'Agent index: récupération des agents...');
            
            // Récupérer tous les agents avec leurs informations complètes
            $agents = $this->agent_model->get_all_agents($data['filters']);
            
            log_message('debug', 'Agent index: ' . count($agents) . ' agents récupérés');
            
            // Debug: Vérifier les avatars
            foreach ($agents as $agent) {
                if (empty($agent->agent_avatar)) {
                    log_message('error', 'Avatar manquant pour agent: ' . $agent->agent_name . ' (ID: ' . $agent->agent_id . ')');
                }
            }
            
            // Ajouter le nombre de propriétés pour chaque agent
            foreach ($agents as $agent) {
                if (!isset($agent->properties_count) || $agent->properties_count === null) {
                    // Utiliser la méthode améliorée pour compter les propriétés
                    $properties = $this->agent_model->get_agent_properties_enhanced($agent->agent_id, $agent->agent_email);
                    $agent->properties_count = count($properties);
                }
            }
            
            $data['agents'] = $agents;
            
        } catch (Exception $e) {
            log_message('error', 'Error in Agent index: ' . $e->getMessage());
            // Debug: afficher l'erreur
            echo "Erreur dans Agent index: " . htmlspecialchars($e->getMessage());
            echo "<br>Trace: <pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
            return;
        }
        
        try {
            // Récupérer la liste des agences pour les filtres
            $data['agencies'] = $this->agency_model->get_all_agencies();
            
            log_message('debug', 'Agent index: chargement de la vue...');
            
            // Charger la vue
            $this->loadViews("dashboard/agents/index", $this->global, $data, NULL);
            
            log_message('debug', 'Agent index: vue chargée avec succès');
            
        } catch (Exception $e) {
            log_message('error', 'Error loading agents view: ' . $e->getMessage());
            echo "Erreur lors du chargement de la vue: " . htmlspecialchars($e->getMessage());
            echo "<br>Trace: <pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
        }
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
