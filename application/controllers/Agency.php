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
    }

    // Liste des agences
    public function index() {
        $this->isLoggedIn();
    $data['agencies'] = $this->agency_model->get_all_agencies();
        $this->loadViews('dashboard/agency/list', $this->global, $data, NULL);
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

    // Voir les agents d'une agence
    public function agents($agency_id) {
        $this->isLoggedIn();
    $data['agents'] = $this->agent_model->get_agents_by_agency($agency_id);
    $data['agency'] = $this->agency_model->get_agency($agency_id);
        $this->loadViews('dashboard/agent/list', $this->global, $data, NULL);
    }

    // Statistiques agence
    public function stats($agency_id) {
        $this->isLoggedIn();
    $data['stats'] = $this->agency_model->get_agency_stats($agency_id);
        $this->loadViews('dashboard/agency/stats', $this->global, $data, NULL);
    }
}
