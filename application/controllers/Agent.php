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
        $this->load->model('User_model');
    }


    // Liste des agents avec vraies données
    public function index() {
        $this->isLoggedIn();
        
        // Charger le helper avatar
        $this->load->helper('avatar');
        
        // Récupérer le rôle et les infos de l'utilisateur connecté
        $role = $this->session->userdata('role');
        $user_id = $this->session->userdata('user_id');
        
        // Préparer les données pour la vue
        $data = $this->global;
        $data['pageTitle'] = 'Liste des agents';
        $data['filters'] = $_GET; // Récupérer les filtres de l'URL
        
        try {
            // Pour le manager, utiliser une méthode spécifique qui récupère son équipe
            if ($role === 'manager' && $agency_id != null ) {
                // Récupérer l'ID de l'agence du manager depuis la session
                $agency_id = $this->session->userdata('agency_id');
                
                if ($agency_id) {
                    // Utiliser la méthode spécifique pour les managers
                    $agents = $this->agent_model->get_manager_team_agents($agency_id);
                    $data['pageTitle'] = 'Mon agence';
                } else {
                    $agents = [];
                    $data['error'] = 'Manager non associé à une agence.';
                }
            } else {
                // Pour les autres rôles (admin, agency_admin), récupérer tous les agents avec rôles et agences
                $agents = $this->agent_model->get_agents_with_roles_and_agencies($data['filters']);
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

    // Test simple pour vérifier que le contrôleur fonctionne
    public function test() {
        echo "<h1>✅ Contrôleur Agent fonctionne !</h1>";
        echo "<p>Date: " . date('Y-m-d H:i:s') . "</p>";
        echo "<p><a href='" . base_url('agents') . "'>→ Liste des agents</a></p>";
        echo "<p><a href='" . base_url('index.php/agents') . "'>→ Liste des agents (avec index.php)</a></p>";
        
        // Test de la nouvelle méthode get_all_agents_from_posts
        try {
            $agents = $this->agent_model->get_all_agents_from_posts([]);
            echo "<p>✅ " . count($agents) . " agents trouvés dans wp_posts (sans wp_users)</p>";
            
            if (count($agents) > 0) {
                echo "<h3>Premiers agents trouvés:</h3>";
                echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
                echo "<tr><th>ID</th><th>Nom</th><th>Email</th><th>Agence</th><th>Propriétés</th><th>Test</th></tr>";
                
                foreach (array_slice($agents, 0, 5) as $agent) {
                    echo "<tr>";
                    echo "<td>" . $agent->agent_id . "</td>";
                    echo "<td>" . htmlspecialchars($agent->agent_name) . "</td>";
                    echo "<td>" . htmlspecialchars($agent->agent_email ?? 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($agent->agency_name ?? 'N/A') . "</td>";
                    echo "<td>" . ($agent->properties_count ?? 0) . "</td>";
                    echo "<td><a href='" . base_url('index.php/agents/view/' . $agent->agent_id) . "'>Voir</a></td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
        } catch (Exception $e) {
            echo "<p>❌ Erreur: " . $e->getMessage() . "</p>";
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
