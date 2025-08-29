<?php
defined('BASEPATH') OR exit('No direct script access allowed');


require APPPATH . '/libraries/BaseController.php';
class Property extends BaseController {
    public function __construct() {
        parent::__construct();
        $this->load->model('property_model');
    }

    // Liste des propriétés avec filtres
    public function index() {
        $this->isLoggedIn();
        $filters = $this->input->get();
        $data['properties'] = $this->property_model->get_all_properties($filters);
        $data['agencies'] = $this->agency_model->get_all_agencies();
        $data['agents'] = $this->agent_model->get_all_agents();
        $this->loadViews('dashboard/property/list', $this->global, $data, NULL);
    }

    // Formulaire ajout/modif propriété
    public function form($id = null) {
        $this->isLoggedIn();
        $data = [];
        if ($id) {
            $data['property'] = $this->property_model->get_property($id);
        }
        $data['agencies'] = $this->agency_model->get_all_agencies();
        $data['agents'] = $this->agent_model->get_all_agents();
        $this->loadViews('dashboard/property/form', $this->global, $data, NULL);
    }

    // CRUD : ajouter, modifier, supprimer (exemples)
    public function add() {
        // ... logique ajout propriété ...
    }
    public function edit($id) {
        // ... logique modif propriété ...
    }
    public function delete($id) {
        // ... logique suppression propriété ...
    }

    // Statistiques propriétés
    public function stats() {
        $this->isLoggedIn();
        $data['stats'] = $this->property_model->get_properties_stats();
        $this->loadViews('dashboard/property/stats', $this->global, $data, NULL);
    }
}
