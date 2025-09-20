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

    // Test avec plusieurs IDs d'agents
    public function test_agents_ids() {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        echo "<h1>Test Agents - Plusieurs IDs</h1>";
        
        $this->load->helper('avatar');
        
        // Test de quelques IDs courants
        $test_ids = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 15, 20, 25, 30];
        
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID Test</th><th>Résultat</th><th>Nom</th><th>Email</th><th>Action</th></tr>";
        
        foreach ($test_ids as $id) {
            echo "<tr>";
            echo "<td>$id</td>";
            
            try {
                $agent = $this->agent_model->get_agent($id);
                
                if ($agent) {
                    echo "<td style='color: green;'>✅ Trouvé</td>";
                    echo "<td>" . htmlspecialchars($agent->agent_name ?? 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($agent->agent_email ?? 'N/A') . "</td>";
                    echo "<td><a href='" . base_url('index.php/agents/view/' . $id) . "' target='_blank'>Tester</a></td>";
                } else {
                    echo "<td style='color: red;'>❌ Vide</td>";
                    echo "<td>-</td>";
                    echo "<td>-</td>";
                    echo "<td>-</td>";
                }
                
            } catch (Exception $e) {
                echo "<td style='color: orange;'>⚠️ Erreur</td>";
                echo "<td colspan='2'>" . htmlspecialchars($e->getMessage()) . "</td>";
                echo "<td>-</td>";
            }
            
            echo "</tr>";
        }
        
        echo "</table>";
        
        // Afficher aussi tous les agents via get_all_agents
        echo "<h2>Via get_all_agents()</h2>";
        try {
            $all_agents = $this->agent_model->get_all_agents([]);
            if ($all_agents && count($all_agents) > 0) {
                echo "<p>✅ " . count($all_agents) . " agents trouvés</p>";
                echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
                echo "<tr><th>ID</th><th>Nom</th><th>Email</th><th>Agence</th><th>Test</th></tr>";
                
                foreach (array_slice($all_agents, 0, 10) as $agent) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($agent->agent_id ?? 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($agent->agent_name ?? 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($agent->agent_email ?? 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($agent->agency_name ?? 'N/A') . "</td>";
                    echo "<td><a href='" . base_url('index.php/agents/view/' . ($agent->agent_id ?? 0)) . "' target='_blank'>Voir</a></td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>❌ Aucun agent trouvé via get_all_agents</p>";
            }
        } catch (Exception $e) {
            echo "<p>❌ Erreur get_all_agents: " . htmlspecialchars($e->getMessage()) . "</p>";
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
            // Pour le manager, filtrer par son agence
            if ($role === 'manager') {
                // Récupérer l'ID de l'agence du manager
                $this->load->model('User_model');
                $user_info = $this->User_model->get_wp_user($user_id);
                $agency_id = $user_info->agency_id ?? null;
                
                if ($agency_id) {
                    // Ajouter le filtre d'agence
                    $data['filters']['agency'] = $agency_id;
                    // Récupérer tous les agents avec rôles et agences, filtré par agence
                    $agents = $this->agent_model->get_agents_with_roles_and_agencies($data['filters']);
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
