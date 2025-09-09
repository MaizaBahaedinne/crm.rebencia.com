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

    // Test simple
    public function test() {
        echo "Agent controller fonctionne !";
        echo "<br>Date: " . date('Y-m-d H:i:s');
        echo "<br><a href='" . base_url('agents') . "'>Retour aux agents</a>";
    }

    // Debug pour voir les associations agent-propriétés
    public function debug_properties($user_id = 7) {
        $this->isLoggedIn();
        
        $agent = $this->agent_model->get_agent_by_user_id($user_id);
        if (!$agent) {
            echo "Agent non trouvé pour user_id: " . $user_id;
            return;
        }
        
        echo "<h3>Debug Agent-Propriétés</h3>";
        echo "<strong>Agent:</strong> " . htmlspecialchars($agent->agent_name) . "<br>";
        echo "<strong>User ID:</strong> " . $agent->user_id . "<br>";
        echo "<strong>Agent ID:</strong> " . $agent->agent_id . "<br>";
        echo "<strong>Email:</strong> " . $agent->agent_email . "<br>";
        
        echo "<hr><h4>Test 1: Recherche par agent_id dans fave_property_agent</h4>";
        
        // Requête directe pour voir les propriétés avec cet agent_id
        $this->load->database('wordpress');
        $wp_db = $this->db;
        
        $query1 = $wp_db->query("
            SELECT p.ID, p.post_title, pm.meta_value as agent_id
            FROM wp_Hrg8P_posts p
            INNER JOIN wp_Hrg8P_postmeta pm ON p.ID = pm.post_id
            WHERE pm.meta_key = 'fave_property_agent'
            AND pm.meta_value = '{$agent->agent_id}'
            AND p.post_type = 'property'
            AND p.post_status = 'publish'
        ");
        
        echo "Propriétés trouvées avec agent_id {$agent->agent_id}: " . $wp_db->affected_rows() . "<br>";
        if ($wp_db->affected_rows() > 0) {
            $results1 = $query1->result();
            foreach ($results1 as $prop) {
                echo "- " . htmlspecialchars($prop->post_title) . " (ID: {$prop->ID})<br>";
            }
        }
        
        echo "<hr><h4>Test 2: Recherche par email dans fave_property_agent</h4>";
        
        $query2 = $wp_db->query("
            SELECT p.ID, p.post_title, pm.meta_value as agent_email
            FROM wp_Hrg8P_posts p
            INNER JOIN wp_Hrg8P_postmeta pm ON p.ID = pm.post_id
            WHERE pm.meta_key = 'fave_property_agent'
            AND pm.meta_value LIKE '%{$agent->agent_email}%'
            AND p.post_type = 'property'
            AND p.post_status = 'publish'
        ");
        
        echo "Propriétés trouvées avec email {$agent->agent_email}: " . $wp_db->affected_rows() . "<br>";
        if ($wp_db->affected_rows() > 0) {
            $results2 = $query2->result();
            foreach ($results2 as $prop) {
                echo "- " . htmlspecialchars($prop->post_title) . " (ID: {$prop->ID})<br>";
            }
        }
        
        echo "<hr><h4>Test 3: Voir toutes les valeurs fave_property_agent</h4>";
        
        $query3 = $wp_db->query("
            SELECT DISTINCT pm.meta_value as agent_value, COUNT(*) as count
            FROM wp_Hrg8P_postmeta pm
            INNER JOIN wp_Hrg8P_posts p ON pm.post_id = p.ID
            WHERE pm.meta_key = 'fave_property_agent'
            AND p.post_type = 'property'
            AND p.post_status = 'publish'
            GROUP BY pm.meta_value
            ORDER BY count DESC
            LIMIT 10
        ");
        
        echo "Échantillon des valeurs fave_property_agent:<br>";
        $results3 = $query3->result();
        foreach ($results3 as $agent_val) {
            echo "- Valeur: " . htmlspecialchars($agent_val->agent_value) . " ({$agent_val->count} propriétés)<br>";
        }
        
        echo "<hr><h4>Test 4: Propriété spécifique 's1-1-salon-spacieux-1-chambre-confortable'</h4>";
        
        $query4 = $wp_db->query("
            SELECT p.ID, p.post_title, p.post_name, pm.meta_key, pm.meta_value
            FROM wp_Hrg8P_posts p
            LEFT JOIN wp_Hrg8P_postmeta pm ON p.ID = pm.post_id
            WHERE (p.post_name = 's1-1-salon-spacieux-1-chambre-confortable' 
               OR p.post_title LIKE '%salon spacieux%')
            AND p.post_type = 'property'
            AND (pm.meta_key LIKE '%agent%' OR pm.meta_key IS NULL)
            ORDER BY p.ID, pm.meta_key
        ");
        
        echo "Informations sur la propriété salon spacieux:<br>";
        $results4 = $query4->result();
        $current_id = null;
        foreach ($results4 as $prop) {
            if ($current_id != $prop->ID) {
                echo "<strong>Propriété: " . htmlspecialchars($prop->post_title) . " (ID: {$prop->ID})</strong><br>";
                $current_id = $prop->ID;
            }
            if ($prop->meta_key) {
                echo "&nbsp;&nbsp;- {$prop->meta_key}: " . htmlspecialchars($prop->meta_value) . "<br>";
            }
        }
        
        echo "<hr><a href='" . base_url('agents/view/' . $user_id) . "'>Retour au profil</a>";
    }

    // Explorer la structure des agents WordPress
    public function explore_structure() {
        $this->isLoggedIn();
        $this->load->database('wordpress');
        $wp_db = $this->db;
        
        echo "<h3>Structure des Agents WordPress HOUZEZ</h3>";
        
        echo "<h4>1. Tous les agents houzez_agent</h4>";
        $query1 = $wp_db->query("
            SELECT p.ID, p.post_title, p.post_name, p.post_author
            FROM wp_Hrg8P_posts p
            WHERE p.post_type = 'houzez_agent'
            AND p.post_status = 'publish'
            ORDER BY p.ID DESC
            LIMIT 10
        ");
        
        $agents = $query1->result();
        foreach ($agents as $agent) {
            echo "- ID: {$agent->ID}, Nom: " . htmlspecialchars($agent->post_title) . ", Slug: {$agent->post_name}<br>";
            
            // Récupérer les métadonnées de cet agent
            $meta_query = $wp_db->query("
                SELECT meta_key, meta_value 
                FROM wp_Hrg8P_postmeta 
                WHERE post_id = {$agent->ID} 
                AND meta_key LIKE '%agent%'
                ORDER BY meta_key
            ");
            
            $metas = $meta_query->result();
            foreach ($metas as $meta) {
                echo "&nbsp;&nbsp;- {$meta->meta_key}: " . htmlspecialchars(substr($meta->meta_value, 0, 100)) . "<br>";
            }
            echo "<br>";
        }
        
        echo "<h4>2. Comment les propriétés sont associées</h4>";
        $query2 = $wp_db->query("
            SELECT 
                p.ID as property_id, 
                p.post_title as property_title,
                pm.meta_value as agent_reference,
                agent.post_title as agent_name
            FROM wp_Hrg8P_posts p
            INNER JOIN wp_Hrg8P_postmeta pm ON p.ID = pm.post_id
            LEFT JOIN wp_Hrg8P_posts agent ON agent.ID = pm.meta_value
            WHERE pm.meta_key = 'fave_property_agent'
            AND p.post_type = 'property'
            AND p.post_status = 'publish'
            LIMIT 10
        ");
        
        $properties = $query2->result();
        foreach ($properties as $prop) {
            echo "Propriété: " . htmlspecialchars($prop->property_title) . "<br>";
            echo "&nbsp;&nbsp;- Agent Reference: {$prop->agent_reference}<br>";
            echo "&nbsp;&nbsp;- Agent Name: " . htmlspecialchars($prop->agent_name ?? 'N/A') . "<br><br>";
        }
        
        echo "<h4>3. Recherche spécifique pour Montasar</h4>";
        $query3 = $wp_db->query("
            SELECT p.*, pm.meta_key, pm.meta_value
            FROM wp_Hrg8P_posts p
            LEFT JOIN wp_Hrg8P_postmeta pm ON p.ID = pm.post_id
            WHERE p.post_title LIKE '%Montasar%' 
            OR p.post_title LIKE '%Barkouti%'
            OR pm.meta_value LIKE '%montasar%'
            OR pm.meta_value LIKE '%barkouti%'
            ORDER BY p.ID, pm.meta_key
        ");
        
        echo "Résultats pour Montasar/Barkouti:<br>";
        $montasar_results = $query3->result();
        $current_post = null;
        foreach ($montasar_results as $result) {
            if ($current_post != $result->ID) {
                echo "<strong>Post ID {$result->ID}: " . htmlspecialchars($result->post_title) . " (Type: {$result->post_type})</strong><br>";
                $current_post = $result->ID;
            }
            if ($result->meta_key) {
                echo "&nbsp;&nbsp;- {$result->meta_key}: " . htmlspecialchars($result->meta_value) . "<br>";
            }
        }
    }

        // Liste des agents avec vraies données
    public function index() {
        $this->isLoggedIn();
        
        // Préparer les données pour la vue
        $data = $this->global;
        $data['pageTitle'] = 'Liste des agents';
        $data['filters'] = $_GET; // Récupérer les filtres de l'URL
        
        try {
            // Récupérer tous les agents avec leurs informations complètes
            $agents = $this->agent_model->get_all_agents($data['filters']);
            
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
            $data['agents'] = [];
            $data['error'] = 'Erreur lors du chargement des agents: ' . $e->getMessage();
        }
        
        // Récupérer la liste des agences pour les filtres
        $data['agencies'] = $this->agency_model->get_all_agencies();
        
        $this->loadViews('dashboard/agents/index', $data, $data);
    }

    // Détails d'un agent
    public function view($user_id = null) {
        $this->isLoggedIn();
        
        if (!$user_id) {
            show_404();
        }
        
        // Debugging
        log_message('debug', 'Agent view: user_id = ' . $user_id);
        
        $agent = $this->agent_model->get_agent_by_user_id($user_id);
        
        // Debugging
        log_message('debug', 'Agent found: ' . ($agent ? 'Yes' : 'No'));
        if (!$agent) {
            log_message('debug', 'No agent found for user_id: ' . $user_id);
        }
        
        // Si pas d'agent trouvé, créons une page de débogage temporaire
        if (!$agent) {
            $data = $this->global;
            $data['pageTitle'] = 'Agent Debug';
            $data['user_id'] = $user_id;
            $data['debug_info'] = 'Aucun agent trouvé pour user_id: ' . $user_id;
            
            // Testons quelques agents existants
            $data['all_agents'] = $this->agent_model->get_all_agents();
            
            $this->loadViews('dashboard/agents/debug', $data, $data);
            return;
        }
        
        // Préparer les données pour la vue
        $data = $this->global;
        $data['pageTitle'] = 'Profil Agent - ' . $agent->agent_name;
        $data['agent'] = $agent;
        
        // Récupérer les propriétés de l'agent (limité à 6 pour la vue)
        $data['properties'] = [];
        if ($agent->agent_id) {
            // Utiliser la méthode améliorée qui teste différentes approches
            $data['properties'] = $this->agent_model->get_agent_properties_enhanced($agent->agent_id, $agent->agent_email, 6);
        }
        
        $this->loadViews('dashboard/agents/view', $data, $data);
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

    public function edit($id) {
        // ... logique modif agent ...
    }
    
    public function delete($id) {
        // ... logique suppression agent ...
    }

    // Voir les propriétés d'un agent
    public function properties($agent_id) {
        $this->isLoggedIn();
        $data['properties'] = $this->property_model->get_properties_by_agent($agent_id);
        $data['agent'] = $this->agent_model->get_agent($agent_id);
        $this->loadViews('dashboard/property/list', $this->global, $data, NULL);
    }

    // Statistiques agent
    public function stats($agent_id) {
        $this->isLoggedIn();
    $data['stats'] = $this->agent_model->get_agent_stats($agent_id);
    $data['agent'] = $this->agent_model->get_agent($agent_id);
    $this->loadViews('dashboard/agent/stats', $this->global, $data, NULL);
    }
}
