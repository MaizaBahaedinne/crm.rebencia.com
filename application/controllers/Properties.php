<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Properties extends BaseController {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Property_model');
        $this->load->model('Agency_model');
        $this->load->model('Agent_model');
    }

    
    // Liste des propriétés
    public function index() {
        $data = $this->loadPage('properties', 'Liste des propriétés', 'properties');
        
        if (isset($_GET['ajax'])) {
            $properties = $this->Property_model->get_properties();
            foreach ($properties as $property) {
                $property->metas = $this->Property_model->get_property_metas($property->ID);
                $property->status = $this->Property_model->get_property_status($property->ID);
                $property->type = $this->Property_model->get_property_type($property->ID);
            }
            $data['properties'] = $properties;
        } else {
            $data['properties'] = [];
        }
        
        // Récupérer les données pour les filtres
        $data['property_statuses'] = $this->Property_model->get_property_statuses();
        $data['property_types'] = $this->Property_model->get_property_types();
        $data['property_cities'] = $this->Property_model->get_property_cities();
        
        $this->loadViews($data, 'dashboard/properties/index');
    }
    
    // Vue détaillée d'une propriété
    public function view($property_id = null) {
        if (!$property_id) {
            show_404();
        }
        
        $property = $this->Property_model->get_property($property_id);
        if (!$property) {
            show_404();
        }
        
        $data = $this->loadPage('properties', 'Détails propriété', 'properties');
        $data['property'] = $property;
        
        // Récupérer les métadonnées de la propriété
        $property_metas = $this->Property_model->get_property_metas($property_id);
        foreach ($property_metas as $meta) {
            $data['property']->{$meta->meta_key} = $meta->meta_value;
        }
        
        // Récupérer statut et type de la propriété
        $data['property_status'] = $this->Property_model->get_property_status($property_id);
        $data['property_type'] = $this->Property_model->get_property_type($property_id);
        
        // Récupérer les informations de l'agent
        $agent = $this->Agent_model->get_agent_by_user_id($property->post_author);
        $data['agent'] = $agent;
        
        if ($agent) {
            $agency = $this->Agency_model->get_agency_details($agent->agency_id);
            $data['agency'] = $agency;
        }
        
        $similar_properties = $this->Property_model->get_similar_properties($property_id, 4);
        $data['similar_properties'] = $similar_properties;
        
        $this->loadViews($data, 'dashboard/properties/view');
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
