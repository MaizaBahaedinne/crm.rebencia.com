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

    // Liste des agents avec vraies données
    public function index() {
        $this->isLoggedIn();
        
        // Préparer les données pour la vue
        $data = $this->global;
        $data['pageTitle'] = 'Liste des agents';
        $data['filters'] = $_GET; // Récupérer les filtres de l'URL
        
        // Récupérer tous les agents avec leurs informations complètes
        $agents = $this->agent_model->get_all_agents();
        
        // Enrichir les données d'agents avec statistiques
        foreach ($agents as $agent) {
            // Compter les propriétés de chaque agent
            $agent->properties_count = $this->property_model->count_properties_by_agent($agent->user_id);
            
            // Avatar par défaut si pas d'image
            if (empty($agent->agent_avatar)) {
                $agent->agent_avatar = 'https://ui-avatars.com/api/?name=' . urlencode($agent->agent_name) . '&background=405189&color=fff&size=100';
            }
            
            // Statut actif/inactif
            $agent->is_active = ($agent->post_status == 'publish') ? true : false;
        }
        
        $data['agents'] = $agents;
        
        // Récupérer les agences pour les filtres
        $data['agencies'] = $this->agency_model->get_all_agencies();
        
        $this->loadViews('dashboard/agents/index', $data, $data);
    }

    // Détails d'un agent
    public function view($agent_id = null) {
        $this->isLoggedIn();
        
        if (!$agent_id) {
            show_404();
        }
        
        $agent = $this->agent_model->get_agent($agent_id);
        if (!$agent) {
            show_404();
        }
        
        // Préparer les données pour la vue
        $data = $this->global;
        $data['pageTitle'] = 'Détails agent';
        $data['agent'] = $agent;
        
        // Récupérer les propriétés de l'agent
        $data['agent_properties'] = $this->property_model->get_properties_by_agent($agent->user_id);
        
        // Récupérer l'agence de l'agent
        if ($agent->agency_id) {
            $data['agency'] = $this->agency_model->get_agency_details($agent->agency_id);
        }
        
        // Statistiques de l'agent
        $data['stats'] = [
            'total_properties' => count($data['agent_properties']),
            'active_listings' => count(array_filter($data['agent_properties'], function($p) { return $p->post_status == 'publish'; })),
            'properties_sold' => 0, // À calculer selon votre logique
            'properties_rented' => 0 // À calculer selon votre logique
        ];
        
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
}
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
