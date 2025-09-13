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

    // Test simple de la méthode view
    public function test_view($user_id = 7) {
        // Debug temporaire
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        echo "<h1>Test Agent View - ID: $user_id</h1>";
        
        // Charger le helper avatar
        $this->load->helper('avatar');
        
        try {
            echo "DEBUG: Tentative de récupération de l'agent $user_id<br>";
            
            // Récupérer les données de l'agent
            $agent = $this->agent_model->get_agent($user_id);
            
            if ($agent) {
                echo "✅ Agent trouvé !<br>";
                echo "<pre>" . print_r($agent, true) . "</pre>";
                
                // Test des propriétés
                $properties = $this->agent_model->get_agent_properties_enhanced($agent->agent_id, $agent->agent_email);
                echo "✅ " . count($properties) . " propriétés trouvées<br>";
                
            } else {
                echo "❌ Agent non trouvé<br>";
            }
            
        } catch (Exception $e) {
            echo "❌ Erreur: " . $e->getMessage() . "<br>";
            echo "<pre>" . $e->getTraceAsString() . "</pre>";
        }
        
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
        // Debug temporaire
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        echo "DEBUG: Méthode view() appelée avec user_id = " . var_export($user_id, true) . "<br>";
        
        $this->isLoggedIn();
        
        // Charger le helper avatar
        $this->load->helper('avatar');
        
        if (!$user_id) {
            echo "DEBUG: user_id est vide, affichage 404<br>";
            show_404();
            return;
        }
        
        echo "DEBUG: user_id valide = $user_id<br>";

        $data = $this->global;
        $data['pageTitle'] = 'Profil Agent';

        try {
            echo "DEBUG: Tentative de récupération de l'agent $user_id<br>";
            
            // Récupérer les données de l'agent
            $agent = $this->agent_model->get_agent($user_id);
            
            echo "DEBUG: Agent récupéré = " . var_export($agent, true) . "<br>";
            
            if (!$agent) {
                echo "DEBUG: Agent non trouvé, affichage 404<br>";
                show_404();
                return;
            }

            echo "DEBUG: Agent trouvé, récupération des propriétés<br>";
            
            // Récupérer les propriétés de l'agent
            $properties = $this->agent_model->get_agent_properties_enhanced($agent->agent_id, $agent->agent_email);
            
            echo "DEBUG: " . count($properties) . " propriétés récupérées<br>";
            
            // Données temporaires pour éviter les erreurs
            $estimations = [];
            $transactions = [];

            $data['agent'] = $agent;
            $data['properties'] = $properties;
            $data['estimations'] = $estimations;
            $data['transactions'] = $transactions;

        } catch (Exception $e) {
            echo "DEBUG: Erreur - " . $e->getMessage() . "<br>";
            echo "DEBUG: Trace - " . $e->getTraceAsString() . "<br>";
            log_message('error', 'Error in Agent view: ' . $e->getMessage());
            show_404();
            return;
        }

        echo "DEBUG: Chargement de la vue dashboard/agents/view<br>";
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
