<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Objectives extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('Objective_model');
        $this->load->library('form_validation');
        $this->load->helper('form');
        
        // Configuration de la base de données WordPress
        $this->wp_db = $this->load->database('wordpress', TRUE);
    }

    /**
     * Dashboard des objectifs
     */
    public function index() {
        $this->isLoggedIn();
        $month = $this->input->get('month') ?: date('Y-m');
        
        $data['title'] = 'Tableau de Bord des Objectifs';
        $data['current_month'] = $month;
        $data['objectives_data'] = $this->Objective_model->get_objectives_dashboard($month);
        $data['stats'] = $this->Objective_model->get_objectives_stats($month);
        
        $this->load->view('includes/header', $data);
        $data['objectives_data'] = $this->load->view('objectives/index', $data);
        $this->load->view('includes/footer');
    }

    /**
     * Définir les objectifs mensuels
     */
    public function set_monthly() {
        $this->isLoggedIn();
        $data['title'] = 'Définir les Objectifs Mensuels';
        $data['selected_month'] = $this->input->get('month') ?: date('Y-m');
        
        // Récupérer la liste des agents depuis le modèle
        $data['agents'] = $this->Objective_model->get_agents();
        
        // Récupérer les objectifs existants si il y en a
        $existing_objectives = $this->Objective_model->get_monthly_objectives_by_month($data['selected_month']);
        $data['existing_objectives'] = array();
        
        if ($existing_objectives) {
            foreach ($existing_objectives as $obj) {
                $data['existing_objectives'][$obj->agent_id] = $obj;
            }
        }

        // Si c'est une soumission
        if ($this->input->method() === 'post') {
            $this->_process_monthly_objectives();
            return;
        }

        $this->load->view('header', $data);
        $this->load->view('objectives/set_monthly', $data);
        $this->load->view('footer');
    }

    /**
     * Traiter la soumission des objectifs mensuels
     */
    private function _process_monthly_objectives() {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('month', 'Mois', 'required|regex_match[/^\d{4}-\d{2}$/]');
        $this->form_validation->set_rules('agent_id', 'Agent', 'required|integer');
        $this->form_validation->set_rules('estimations_target', 'Objectif Estimations', 'required|integer|greater_than_equal_to[0]');
        $this->form_validation->set_rules('contacts_target', 'Objectif Contacts', 'required|integer|greater_than_equal_to[0]');
        $this->form_validation->set_rules('transactions_target', 'Objectif Transactions', 'required|integer|greater_than_equal_to[0]');
        $this->form_validation->set_rules('revenue_target', 'Objectif CA', 'required|numeric|greater_than_equal_to[0]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', 'Erreur dans les données : ' . validation_errors());
            return;
        }

        $agent_id = $this->input->post('agent_id');
        $month = $this->input->post('month');
        $created_by = $this->session->userdata('user_id');

        $objectives = [
            'estimations_target' => $this->input->post('estimations_target'),
            'contacts_target' => $this->input->post('contacts_target'),
            'transactions_target' => $this->input->post('transactions_target'),
            'revenue_target' => $this->input->post('revenue_target')
        ];

        if ($this->Objective_model->set_monthly_objectives($agent_id, $month, $objectives, $created_by)) {
            $this->session->set_flashdata('success', 'Objectifs définis avec succès !');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de la définition des objectifs.');
        }
    }

    /**
     * Voir les objectifs d'un agent
     */
    public function agent($agent_id = null) {
        $this->isLoggedIn();
        if (!$agent_id) {
            show_404();
        }

        $month = $this->input->get('month');
        
        $data['title'] = 'Objectifs de l\'Agent';
        $data['agent_id'] = $agent_id;
        $data['current_month'] = $month ?: date('Y-m');
        $data['objectives'] = $this->Objective_model->get_agent_objectives($agent_id, $month);
        $data['performance'] = $this->Objective_model->get_agent_performance($agent_id, $month);

        // Récupérer les infos de l'agent
        $this->load->database('wordpress');
        $data['agent'] = $this->db->select('ID, display_name, user_email')
                                 ->where('ID', $agent_id)
                                 ->get('wp_users')
                                 ->row();

        if (!$data['agent']) {
            show_404();
        }

        $this->load->view('header', $data);
        $this->load->view('objectives/agent_detail', $data);
        $this->load->view('footer');
    }

    /**
     * Mettre à jour les performances d'un agent
     */
    public function update_performance() {
        $this->isLoggedIn();
        if ($this->input->method() !== 'post') {
            show_404();
        }

        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('agent_id', 'Agent', 'required|integer');
        $this->form_validation->set_rules('month', 'Mois', 'required|regex_match[/^\d{4}-\d{2}$/]');
        $this->form_validation->set_rules('estimations_count', 'Nombre Estimations', 'required|integer|greater_than_equal_to[0]');
        $this->form_validation->set_rules('contacts_count', 'Nombre Contacts', 'required|integer|greater_than_equal_to[0]');
        $this->form_validation->set_rules('transactions_count', 'Nombre Transactions', 'required|integer|greater_than_equal_to[0]');
        $this->form_validation->set_rules('revenue_amount', 'CA Réalisé', 'required|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('commission_earned', 'Commission Gagnée', 'required|numeric|greater_than_equal_to[0]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', 'Erreur dans les données : ' . validation_errors());
            redirect('objectives/agent/' . $this->input->post('agent_id'));
            return;
        }

        $agent_id = $this->input->post('agent_id');
        $month = $this->input->post('month');

        $performance_data = [
            'estimations_count' => $this->input->post('estimations_count'),
            'contacts_count' => $this->input->post('contacts_count'),
            'transactions_count' => $this->input->post('transactions_count'),
            'revenue_amount' => $this->input->post('revenue_amount'),
            'commission_earned' => $this->input->post('commission_earned')
        ];

        if ($this->Objective_model->update_agent_performance($agent_id, $month, $performance_data)) {
            $this->session->set_flashdata('success', 'Performances mises à jour avec succès !');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de la mise à jour des performances.');
        }

        redirect('objectives/agent/' . $agent_id . '?month=' . $month);
    }

    /**
     * Calculer automatiquement les performances
     */
    public function calculate_performance($agent_id = null, $month = null) {
        $this->isLoggedIn();
        if (!$agent_id || !$month) {
            $this->session->set_flashdata('error', 'Agent et mois requis.');
            redirect('objectives');
            return;
        }

        $performance = $this->Objective_model->calculate_performance($agent_id, $month);

        if ($performance) {
            $this->session->set_flashdata('success', 'Performances calculées automatiquement !');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors du calcul automatique.');
        }

        redirect('objectives/agent/' . $agent_id . '?month=' . $month);
    }

    /**
     * Objectifs par équipe/agence
     */
    public function team() {
        $this->isLoggedIn();
        $month = $this->input->get('month') ?: date('Y-m');
        
        $data['title'] = 'Objectifs par Équipe';
        $data['current_month'] = $month;
        $data['team_objectives'] = $this->Objective_model->get_monthly_objectives($month);
        $data['team_stats'] = $this->Objective_model->get_objectives_stats($month);

        $this->load->view('header', $data);
        $this->load->view('objectives/team', $data);
        $this->load->view('footer');
    }

    /**
     * API pour récupérer les données d'objectifs (AJAX)
     */
    public function api_get_data() {
        $this->isLoggedIn();
        $month = $this->input->get('month') ?: date('Y-m');
        $agent_id = $this->input->get('agent_id');

        if ($agent_id) {
            $data = $this->Objective_model->get_agent_objectives($agent_id, $month);
        } else {
            $data = $this->Objective_model->get_monthly_objectives($month);
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['success' => true, 'data' => $data]));
    }

    /**
     * Définition d'objectifs en masse
     */
    public function bulk_set() {
        $this->isLoggedIn();
        $data['title'] = 'Définir Objectifs en Masse';
        
        // Récupérer tous les agents
        $this->load->database('wordpress');
        $data['agents'] = $this->db->select('u.ID, u.display_name')
                                  ->from('wp_users u')
                                  ->join('wp_usermeta um', 'um.user_id = u.ID')
                                  ->where('um.meta_key', 'wp_capabilities')
                                  ->like('um.meta_value', 'houzez_agent')
                                  ->order_by('u.display_name', 'ASC')
                                  ->get()
                                  ->result();

        if ($this->input->method() === 'post') {
            $this->_process_bulk_objectives();
        }

        $this->load->view('header', $data);
        $this->load->view('objectives/bulk_set', $data);
        $this->load->view('footer');
    }

    /**
     * Traiter la définition d'objectifs en masse
     */
    private function _process_bulk_objectives() {
        $month = $this->input->post('month');
        $agents_data = $this->input->post('agents');
        $created_by = $this->session->userdata('user_id');

        if (!$month || !$agents_data) {
            $this->session->set_flashdata('error', 'Mois et données agents requis.');
            return;
        }

        $success_count = 0;
        $error_count = 0;

        foreach ($agents_data as $agent_id => $objectives) {
            if (!empty($objectives['estimations_target']) || 
                !empty($objectives['contacts_target']) || 
                !empty($objectives['transactions_target']) || 
                !empty($objectives['revenue_target'])) {
                
                if ($this->Objective_model->set_monthly_objectives($agent_id, $month, $objectives, $created_by)) {
                    $success_count++;
                } else {
                    $error_count++;
                }
            }
        }

        if ($success_count > 0) {
            $this->session->set_flashdata('success', "Objectifs définis pour {$success_count} agent(s) !");
        }

        if ($error_count > 0) {
            $this->session->set_flashdata('error', "Erreur pour {$error_count} agent(s).");
        }
    }
}
