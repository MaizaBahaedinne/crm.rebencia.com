<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Properties extends BaseController {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('property_model');
        $this->load->model('agency_model');
        $this->load->model('agent_model');
    }

    
    // Liste des propriétés
    public function index() {
        $this->isLoggedIn();
        $filters = $this->input->get();
        $data['properties'] = $this->Property_model->get_all_properties($filters);
        $data['agencies'] = $this->Agency_model->get_agencies_with_stats();
        $data['agents'] = $this->Agent_model->get_all_agents();
        $data['filters'] = $filters;
        
        $this->loadViews('dashboard/properties/index', $this->global, $data, NULL);
    }
    
    // Vue détaillée d'une propriété
    public function view($property_id) {
        $this->isLoggedIn();
        $property = $this->Property_model->get_property($property_id);
        
        if (!$property) {
            show_404();
        }
        
        // Enrichir avec les métadonnées
        $property_metas = $this->Property_model->get_property_metas($property_id);
        foreach ($property_metas as $meta) {
            $property->{$meta->meta_key} = $meta->meta_value;
        }
        
        // Récupérer l'agent propriétaire
        $agent = $this->Agent_model->get_agent_by_user_id($property->post_author);
        
        // Récupérer l'agence de l'agent
        $agency = null;
        if ($agent && !empty($agent->agency_id)) {
            $agency = $this->Agency_model->get_agency_details($agent->agency_id);
        }
        
        // Propriétés similaires
        $similar_properties = $this->Property_model->get_similar_properties($property_id, 4);
        
        $data['property'] = $property;
        $data['agent'] = $agent;
        $data['agency'] = $agency;
        $data['similar_properties'] = $similar_properties;
        
        $this->loadViews('dashboard/properties/view', $this->global, $data, NULL);
    }
    
    // AJAX - Liste filtrée
    public function ajax_list() {
        $filters = $this->input->get();
        $data['properties'] = $this->Property_model->get_all_properties($filters);
        $this->load->view('dashboard/properties/ajax_list', $data);
    }
    
    // AJAX - Recherche autocomplete
    public function ajax_search() {
        $term = $this->input->get('term');
        $properties = $this->Property_model->search_properties($term, 10);
        
        $results = [];
        foreach ($properties as $property) {
            $results[] = [
                'id' => $property->ID,
                'label' => $property->post_title,
                'value' => $property->post_title,
                'address' => isset($property->fave_property_address) ? $property->fave_property_address : '',
                'price' => isset($property->fave_property_price) ? $property->fave_property_price : ''
            ];
        }
        
        header('Content-Type: application/json');
        echo json_encode($results);
    }
}
