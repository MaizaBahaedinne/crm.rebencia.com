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
                    $agent->properties_count = $this->property_model->count_properties_by_agent($agent->user_id);
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
            $data['properties'] = $this->agent_model->get_agent_properties($agent->agent_id, 6);
        }
        
        $this->loadViews('dashboard/agents/view_simple', $data, $data);
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
