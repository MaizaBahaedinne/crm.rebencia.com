<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * @property Property_model $property_model
 * @property Agency_model $agency_model
 * @property Agent_model $agent_model
 * @property CI_Input $input
 */
class Properties extends BaseController {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Property_model', 'property_model');
        $this->load->model('Agency_model', 'agency_model');
        $this->load->model('Agent_model', 'agent_model');
    }

    // Liste des propriétés
    public function index( ) {
        $this->isLoggedIn();
        
        // Préparer les données pour la vue
        $data = $this->global;
        $data['pageTitle'] = 'Liste des propriétés';
        $data['filters'] = $_GET; // Récupérer les filtres de l'URL
        $affichage = $this->input->get('affichage', TRUE);
      

        if ((($this->role === 'manager' || $this->role === 'agent') ) && $this->agencyId != null) {
            $properties = $this->property_model->get_properties_agency($this->agencyId);
        }
        else {
            // Pour les autres rôles, récupérer toutes les propriétés
            $properties = $this->property_model->get_all_properties($data['filters']);
        }

        foreach ($properties as $property) {
            $property->metas = $this->property_model->get_property_metas($property->property_id);
            
            $property->status = $this->property_model->get_property_status($property->property_id);
            $property->type = $this->property_model->get_property_type($property->property_id);
            $property->images = $this->property_model->get_property_images($property->property_id);
        }
        $data['properties'] = $properties;
        
       

        // Récupérer les données pour les filtres
        $data['property_statuses'] = $this->property_model->get_property_statuses();
        $data['property_types'] = $this->property_model->get_property_types();
        $data['property_cities'] = $this->property_model->get_property_cities();
        
       // echo json_encode($data['properties']);

    if ($affichage === null) {
        $this->loadViews('dashboard/properties/index', $data, $data);
    } else {
        header('Content-Type: application/json');
        echo json_encode($data['properties']);
        exit;
    }
    }

    // Détails d'une propriété
    public function view($property_id = null) {
        $this->isLoggedIn();
        
        if (!$property_id) {
            show_404();
        }
        
        $property = $this->property_model->get_property($property_id);
        if (!$property) {
            show_404();
        }
        
        // Préparer les données pour la vue
        $data = $this->global;
        $data['pageTitle'] = 'Détails propriété';
        $data['property'] = $property;
        
        // Récupérer les métadonnées de la propriété
        $property_metas = $this->property_model->get_property_metas($property_id);
        foreach ($property_metas as $meta) {
            $data['property']->{$meta->meta_key} = $meta->meta_value;
        }
        
        // Récupérer statut et type de la propriété
        $data['property_status'] = $this->property_model->get_property_status($property_id);
        $data['property_type'] = $this->property_model->get_property_type($property_id);
        
        // Récupérer les images de la propriété
        $data['property_images'] = $this->property_model->get_property_images($property_id);
        
        // Récupérer les informations de l'agent
        $agent = $this->agent_model->get_agent_by_user_id($property->post_author);
        $data['agent'] = $agent;
        
        if ($agent) {
            $agency = $this->agency_model->get_agency_details($agent->agency_id);
            $data['agency'] = $agency;
        }
        
        $similar_properties = $this->property_model->get_similar_properties($property_id, 4);
        $data['similar_properties'] = $similar_properties;
        
        $this->loadViews('dashboard/properties/view', $data, $data);
    }

    // AJAX pour récupérer toutes les propriétés avec filtres
    public function get_all_properties() {
        $filters = $this->input->get();
        $data['properties'] = $this->property_model->get_all_properties($filters);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
    
    // AJAX pour recherche de propriétés
    public function search_properties() {
        $term = $this->input->get('term');
        $properties = $this->property_model->search_properties($term, 10);
        header('Content-Type: application/json');
        echo json_encode($properties);
    }
}
