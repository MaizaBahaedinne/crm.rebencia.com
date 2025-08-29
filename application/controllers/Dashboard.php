<?php
defined('BASEPATH') OR exit('No direct script access allowed');



require APPPATH . '/libraries/BaseController.php';



class Dashboard extends BaseController {
    public function __construct() {
        parent::__construct();
        $this->load->model('agent_model');
        $this->load->model('agency_model');
        $this->load->model('activity_model');
        $this->load->library('session');
        $this->load->helper('url');
    }


    public function index() {
        $this->isLoggedIn();
        $role = $this->session->userdata('role');
        if ($role === 'administrator') {
            redirect('dashboard/admin');
        } elseif ($role === 'agency') {
            $agency_id = $this->session->userdata('agency_id');
            redirect('dashboard/agency/' . $agency_id);
        } elseif ($role === 'agent') {
            $agent_id = $this->session->userdata('agent_id');
            redirect('dashboard/agent/' . $agent_id);
        } else {
            redirect('login');
        }
    }


    // Vue Admin : toutes les agences, agents, stats globales
    public function admin() {
        $this->isLoggedIn();
        $data['agencies'] = $this->agency_model->get_all_agencies();
        $data['agents'] = $this->agent_model->get_all_agents();
        $data['stats'] = $this->activity_model->get_global_stats();
        $this->loadViews('dashboard/admin', $this->global, $data, NULL);
    }

    // Vue Agence : données d'une agence
    public function agency($agency_id) {
        $this->isLoggedIn();
        $data['agency'] = $this->agency_model->get_agency($agency_id);
        $data['agents'] = $this->agent_model->get_agents_by_agency($agency_id);
        $data['stats'] = $this->activity_model->get_agency_stats($agency_id);
        $this->loadViews('dashboard/agency', $this->global, $data, NULL);
    }

    // Vue Agent : données d'un agent
    public function agent($agent_id) {
        $this->isLoggedIn();
        $data['agent'] = $this->agent_model->get_agent($agent_id);
        $data['stats'] = $this->activity_model->get_agent_stats($agent_id);
        $this->loadViews('dashboard/agent', $this->global, $data, NULL);
    }
}
