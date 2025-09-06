<?php
defined('BASEPATH') OR exit('No direct script access allowed');


require APPPATH . '/libraries/BaseController.php';
class Agency extends BaseController {
    public function __construct() {
        parent::__construct();
        $this->load->model('agency_model');
        $this->load->model('agent_model');
        $this->load->model('property_model');
        $this->load->library('form_validation');
        $this->load->helper(['url', 'form']);
    }

    // Liste des agences avec statistiques et filtres
    public function index() {
        $this->isLoggedIn();
        
        // Récupération des filtres
        $filters = [
            'search' => $this->input->get('search'),
            'ville' => $this->input->get('ville'),
            'status' => $this->input->get('status')
        ];
        
        // Récupération des données depuis HOUZEZ WordPress
        $data = $this->global;
        $data['agencies'] = $this->agency_model->get_agencies_with_stats($filters);
        $data['filters'] = $filters;
        
        // Statistiques globales
        $data['total_stats'] = [
            'total_agencies' => count($data['agencies']),
            'total_agents' => array_sum(array_column($data['agencies'], 'agents_count')),
            'total_properties' => array_sum(array_column($data['agencies'], 'properties_count')),
            'total_sales' => array_sum(array_column($data['agencies'], 'sales_count'))
        ];
        
        $this->loadViews('dashboard/agency/list_cards', $data, NULL, NULL);
    }

    // Formulaire ajout/modif agence
    public function form($id = null) {
        $this->isLoggedIn();
        $data = [];
        if ($id) {
            $data['agency'] = $this->agency_model->get_agency($id);
        }
        $this->loadViews('dashboard/agency/form', $this->global, $data, NULL);
    }

    // CRUD : ajouter, modifier, supprimer (exemples)
    public function add() {
        // ... logique ajout agence ...
    }
    public function edit($id) {
        // ... logique modif agence ...
    }
    public function delete($id) {
        // ... logique suppression agence ...
    }

    // Voir les détails d'une agence
    public function view($agency_id) {
        $this->isLoggedIn();
        
        $data = $this->global;
        $data['agency'] = $this->agency_model->get_agency_details($agency_id);
        $data['agents'] = $this->agent_model->get_agents_by_agency($agency_id);
        $data['properties'] = $this->property_model->get_properties_by_agency($agency_id, 5); // Limite 5 pour aperçu
        $data['stats'] = $this->agency_model->get_agency_stats($agency_id);
        
        if (empty($data['agency'])) {
            show_404();
            return;
        }
        
        $this->loadViews('dashboard/agency/view', $data, NULL, NULL);
    }

    // Voir les agents d'une agence
    public function agents($agency_id) {
        $this->isLoggedIn();
        
        $data = $this->global;
        $data['agents'] = $this->agent_model->get_agents_by_agency($agency_id);
        $data['agency'] = $this->agency_model->get_agency($agency_id);
        
        if (empty($data['agency'])) {
            show_404();
            return;
        }
        
        $this->loadViews('dashboard/agency/agents_list', $data, NULL, NULL);
    }

    // Statistiques agence
    public function stats($agency_id) {
        $this->isLoggedIn();
    $data['stats'] = $this->agency_model->get_agency_stats($agency_id);
        $this->loadViews('dashboard/agency/stats', $this->global, $data, NULL);
    }
}
