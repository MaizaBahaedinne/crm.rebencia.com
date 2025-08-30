<?php
defined('BASEPATH') OR exit('No direct script access allowed');


require APPPATH . '/libraries/BaseController.php';
class Agent extends BaseController {
    public function __construct() {
        parent::__construct();
        $this->load->model('agent_model');
    }

    // Liste des agents
    public function index() {
        $this->isLoggedIn();
        $data['agents'] = $this->agent_model->get_all_agents();
        $this->loadViews('dashboard/agent/list', $this->global, $data, NULL);
    }

    public function json() {
        $this->isLoggedIn();
        $agents = $this->agent_model->get_all_agents();
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($agents));
    }

    // Formulaire ajout/modif agent
    public function form($id = null) {
        $this->isLoggedIn();
        $data = [];
        if ($id) {
            $data['agent'] = $this->agent_model->get_agent($id);
        }
        $data['agencies'] = $this->agency_model->get_all_agencies();
        $this->loadViews('dashboard/agent/form', $this->global, $data, NULL);
    }

    // CRUD : ajouter, modifier, supprimer (exemples)
    public function add() {
        // ... logique ajout agent ...
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
        $this->loadViews('dashboard/agent/stats', $this->global, $data, NULL);
    }
}
