<?php
defined('BASEPATH') OR exit('No direct script access allowed');



require APPPATH . '/libraries/BaseController.php';

/**
 * @property Agent_model $agent_model
 * @property Agency_model $agency_model
 * @property Activity_model $activity_model
 * @property Transaction_model $transaction_model
 * @property Task_model $task_model
 * @property CI_DB_query_builder $db
 * @property CI_Session $session
 */
class Dashboard extends BaseController {
    public function __construct() {
        parent::__construct();
    $this->load->model('Agent_model','agent_model');
    $this->load->model('Agency_model','agency_model');
    $this->load->model('Activity_model','activity_model');
    $this->load->model('Transaction_model','transaction_model');
    $this->load->model('Task_model','task_model');
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
        // Compteurs supplémentaires
        $data['count_agencies'] = count($data['agencies']);
        $data['count_agents'] = count($data['agents']);
        $data['count_clients'] = $this->activity_model->get_clients_count();
        $data['count_transactions'] = $this->activity_model->get_transactions_count();
        // Estimations : compter dans crm_properties si table existe
        if ($this->db->table_exists('crm_properties')) {
            $data['count_estimations'] = (int)$this->db->count_all('crm_properties');
        } else {
            $data['count_estimations'] = 0;
        }
        // Transactions récentes
        $data['recent_transactions'] = $this->transaction_model->recent(5);
        // RDV / tâches (on réutilise tbl_task comme agenda simple)
        if ($this->db->table_exists('tbl_task')) {
            $data['upcoming_tasks'] = $this->db->order_by('createdDtm','DESC')->limit(6)->get('tbl_task')->result_array();
        } else {
            $data['upcoming_tasks'] = [];
        }
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
