<?php
defined('BASEPATH') OR exit('No direct script access allowed');


require APPPATH . '/libraries/BaseController.php';
class Report extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('agent_model');
        $this->load->model('agency_model');
        $this->load->model('activity_model');
        $this->load->model('User_model');
        $this->load->library('session');
        $this->load->helper('url');
    }
    
    public function sales() {
        $this->loadViews('reports/sales', []);
    }
    public function leads() {
        $this->loadViews('reports/leads', []);
    }
    public function agency_performance() {
        $this->loadViews('reports/agency_performance', []);
    }
    public function agency() {
        $this->loadViews('reports/agency', []);
    }
    
    public function manager() {
        // Récupérer les données de l'agence du manager
        $user_id = $this->session->userdata('user_id');
        $role = $this->session->userdata('role');
        
        if ($role !== 'manager') {
            redirect('login');
        }
        
        // Récupérer l'ID de l'agence du manager
        $this->load->model('User_model');
        $user_info = $this->User_model->get_user_info($user_id);
        $agency_id = $user_info->agency_id ?? null;
        
        if (!$agency_id) {
            show_error('Manager non associé à une agence');
        }
        
        // Récupérer les statistiques de l'équipe
        $data = [];
        $data['agency_id'] = $agency_id;
        
        // Récupérer les agents de l'agence
        $agents = $this->agent_model->get_agents_by_agency($agency_id);
        $data['agents'] = $agents;
        
        // Statistiques générales de l'équipe
        $data['total_agents'] = count($agents);
        $data['total_properties'] = 0;
        $data['total_estimations'] = 0;
        $data['total_clients'] = 0;
        
        // Calculer les totaux pour chaque agent
        foreach ($agents as $agent) {
            // Ici vous pouvez ajouter des requêtes pour obtenir les statistiques
            // par agent si nécessaire
        }
        
        $this->loadViews('reports/manager', $data);
    }
}
