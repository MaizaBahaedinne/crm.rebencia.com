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
       

        $this->loadViews('dashboard/agent/list', $this->global, null, NULL);
    }


    public function json_list() {
        $this->isLoggedIn();
        $data['agents'] = $this->agent_model->get_all_agents();
        $data['agents_js'] = array_map(function($agent) {
            return [
                'id' => $agent->id,
                'coverImg' => $agent->coverImg ?? '',
                'bookmark' => false,
                'memberImg' => $agent->memberImg ?? '',
                'nickname' => $agent->agent_name ?? '',
                'memberName' => $agent->agent_name ?? '',
                'position' => $agent->role ?? '',
                'projects' => isset($agent->projects) ? (string)$agent->projects : "0",
                'tasks' => isset($agent->tasks) ? (string)$agent->tasks : "0"
            ];
        }, $data['agents']);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data['agents']));
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
